<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FaixaCepFreteGratis extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('FaixaCepFreteGratis_Model', 'FaixaCepFreteGratisM');
	}
	
	public function ver($codformaentrega) {
	    
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/faixacepfretegratis/adicionar/'.$codformaentrega);
		$data['URLLISTAR']		= site_url('painel/faixacepfretegratis/ver/'.$codformaentrega);
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->FaixaCepFreteGratisM->get(array("codformaentrega" => $codformaentrega), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"FAIXACEP"		=> $r->cepinicialfaixacepfretegratis. ' até '.$r->cepfinalfaixacepfretegratis,
				    "FAIXAPRECO"    => number_format($r->pesoinicialfaixacepfretegratis, 3, ',', '.').'kg - '.number_format($r->pesofinalfaixacepfretegratis, 3, ',', '.').'kg',
				    "PRECO"     => number_format($r->valorminimofaixacepfretegratis, 2, ',', '.'),
					"URLEDITAR"	=> site_url('painel/faixacepfretegratis/editar/'.$r->codfaixacepfretegratis),
					"URLEXCLUIR"=> site_url('painel/faixacepfretegratis/excluir/'.$r->codfaixacepfretegratis)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->FaixaCepFreteGratisM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/faixacepfretegratis?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/faixacepfretegratis?pagina='.($pagina-1));
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/faixacepfretegratis?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/faixacepfretegratis_listar', $data);
	}
	
	public function adicionar($codformaentrega) {
	
		$data						= array();
		$data['ACAO']				= 'Novo';
		$data['codfaixacepfretegratis']	= '';
		$data['codformaentrega']	= $codformaentrega;
		$data['cepinicialfaixacepfretegratis']	= '';
		$data['cepfinalfaixacepfretegratis']	= '';
		$data['pesoinicialfaixacepfretegratis']  = '0,000';
		$data['pesofinalfaixacepfretegratis']    = '0,000';
		$data['valorminimofaixacepfretegratis']  = '0,00';
		$data['chk_habilitafaixacepfretegratis'] = 'checked="checked"';
		
		$this->setURL($data, $codformaentrega);
		
		$this->parser->parse('painel/faixacepfretegratis_form', $data);
	}
	
	public function editar($id) {
		$data						= array();
		$data['ACAO']				= 'Edição';
		
		$res	= $this->FaixaCepFreteGratisM->get(array("codfaixacepfretegratis" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
			
			$data['chk_habilitafaixacepfretegratis'] = ($res->habilitafaixacepfretegratis=='S')?'checked="checked"':null;
			$data['pesoinicialfaixacepfretegratis'] = modificaNumericPeso($res->pesoinicialfaixacepfretegratis);
			$data['pesofinalfaixacepfretegratis'] = modificaNumericPeso($res->pesofinalfaixacepfretegratis);
			$data['valorminimofaixacepfretegratis'] = modificaNumericValor($res->valorminimofaixacepfretegratis);
			
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$this->setURL($data, $res->codformaentrega);
		
		$this->parser->parse('painel/faixacepfretegratis_form', $data);
	}
	
	public function salvar() {
		
		$codfaixacepfretegratis	         = $this->input->post('codfaixacepfretegratis');
		$cepinicialfaixacepfretegratis	 = $this->input->post('cepinicialfaixacepfretegratis');
		$cepfinalfaixacepfretegratis	     = $this->input->post('cepfinalfaixacepfretegratis');
		$pesoinicialfaixacepfretegratis	 = $this->input->post('pesoinicialfaixacepfretegratis');
		$pesofinalfaixacepfretegratis	 = $this->input->post('pesofinalfaixacepfretegratis');
		$valorminimofaixacepfretegratis	     = $this->input->post('valorminimofaixacepfretegratis');
		$habilitafaixacepfretegratis	     = $this->input->post('habilitafaixacepfretegratis');
		$codformaentrega	                 = $this->input->post('codformaentrega');

		if (!$habilitafaixacepfretegratis) {
		    $habilitafaixacepfretegratis = 'N';
		}
		
		$cepinicialfaixacepfretegratis = str_replace("-", null, $cepinicialfaixacepfretegratis);
		$cepfinalfaixacepfretegratis = str_replace("-", null, $cepfinalfaixacepfretegratis);
		$pesoinicialfaixacepfretegratis = modificaDinheiroBanco($pesoinicialfaixacepfretegratis);
		$pesofinalfaixacepfretegratis = modificaDinheiroBanco($pesofinalfaixacepfretegratis);
		$valorminimofaixacepfretegratis = modificaDinheiroBanco($valorminimofaixacepfretegratis);
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$erros) {
			$itens	= array(
				"cepinicialfaixacepfretegratis"	         => $cepinicialfaixacepfretegratis,
				"cepfinalfaixacepfretegratis"	         => $cepfinalfaixacepfretegratis,
				"pesoinicialfaixacepfretegratis"	     => $pesoinicialfaixacepfretegratis,
				"pesofinalfaixacepfretegratis"	         => $pesofinalfaixacepfretegratis,
				"valorminimofaixacepfretegratis"	     => $valorminimofaixacepfretegratis,
				"habilitafaixacepfretegratis"	         => $habilitafaixacepfretegratis,
				"codformaentrega"	                     => $codformaentrega,
			);
			
			if ($codfaixacepfretegratis) {
				$codfaixacepfretegratis = $this->FaixaCepFreteGratisM->update($itens, $codfaixacepfretegratis);
			} else {
				$codfaixacepfretegratis = $this->FaixaCepFreteGratisM->post($itens);
			}
			
			if ($codfaixacepfretegratis) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/faixacepfretegratis/ver/'.$codformaentrega);
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codfaixacepfretegratis) {
					redirect('painel/faixacepfretegratis/editar/'.$codfaixacepfretegratis);
				} else {
					redirect('painel/faixacepfretegratis/adicionar/'.$codformaentrega);
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codfaixacepfretegratis) {
				redirect('painel/faixacepfretegratis/editar/'.$codfaixacepfretegratis);
			} else {
				redirect('painel/faixacepfretegratis/adicionar/'.$codformaentrega);
			}
		}
		
	}
	
	private function setURL(&$data, $id) {
		$data['URLLISTAR']	= site_url('painel/faixacepfretegratis/ver/'.$id);
		$data['ACAOFORM']	= site_url('painel/faixacepfretegratis/salvar');
	}
	
	public function excluir($id) {
	    $info	= $this->FaixaCepFreteGratisM->get(array("codfaixacepfretegratis" => $id), TRUE);
	    
		$res    = $this->FaixaCepFreteGratisM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Forma de entrega removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Forma de entrega não pode ser removido.');
		}
		
		redirect('painel/faixacepfretegratis/ver/'.$info->codformaentrega);
	}
	
}