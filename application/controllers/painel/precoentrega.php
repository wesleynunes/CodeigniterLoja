<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PrecoEntrega extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('PrecoEntrega_Model', 'PrecoEntregaM');
		$this->load->model('FormaEntrega_Model', 'FormaEntregaM');
	}
	
	public function ver($codformaentrega) {
	    
		$data					  = array();
		$data['URLADICIONAR']	  = site_url('painel/precoentrega/adicionar/'.$codformaentrega);
		$data['URLLISTAR']		  = site_url('painel/precoentrega/ver/'.$codformaentrega);
		$data['BLC_DADOS']		  = array();
		$data['BLC_SEMDADOS']	  = array();
		$data['BLC_PAGINAS']	  = array();
		$data['BLC_EXIBEGERACAO'] = array();
		
		$res	= $this->FormaEntregaM->get(array("codformaentrega" => $codformaentrega), TRUE);
		
		if (!empty($res->codigocorreiosformaentrega)) {
		  $data['BLC_EXIBEGERACAO'][] = array(
		          "URLGERAFRETE" => 
		          site_url("precoentrega/gera/".$codformaentrega)
		  );
		}
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->PrecoEntregaM->get(array("codformaentrega" => $codformaentrega), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"FAIXACEP"		=> str_pad($r->cepinicialfaixaprecoformaentrega, 8, "0", STR_PAD_LEFT). ' até '.str_pad($r->cepfinalfaixaprecoformaentrega, 8, "0", STR_PAD_LEFT),
				    "FAIXAPRECO"    => number_format($r->pesoinicialfaixaprecoformaentrega, 3, ',', '.').'kg - '.number_format($r->pesofinalfaixaprecoformaentrega, 3, ',', '.').'kg',
				    "PRECO"     => number_format($r->valorfaixaprecoformaentrega, 2, ',', '.'),
					"URLEDITAR"	=> site_url('painel/precoentrega/editar/'.$r->codfaixaprecoformaentrega),
					"URLEXCLUIR"=> site_url('painel/precoentrega/excluir/'.$r->codfaixaprecoformaentrega)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->PrecoEntregaM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/precoentrega?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/precoentrega?pagina='.($pagina-1));
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/precoentrega?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/precoentrega_listar', $data);
	}
	
	public function adicionar($codformaentrega) {
	
		$data						                = array();
		$data['ACAO']				                = 'Novo';
		$data['codprecoentrega']	                = '';
		$data['codformaentrega']	                = $codformaentrega;
		$data['codfaixaprecoformaentrega']	        = '';
		$data['cepinicialfaixaprecoformaentrega']	= '';
		$data['cepfinalfaixaprecoformaentrega']	    = '';
		$data['pesoinicialfaixaprecoformaentrega']  = '0,000';
		$data['pesofinalfaixaprecoformaentrega']    = '0,000';
		$data['valorfaixaprecoformaentrega']        = '0,00';
		$data['prazofaixaprecoformaentrega']        = '0';
		
		$this->setURL($data, $codformaentrega);
		
		$this->parser->parse('painel/precoentrega_form', $data);
	}
	
	public function editar($id) {
		$data						= array();
		$data['ACAO']				= 'Edição';
		
		$res	= $this->PrecoEntregaM->get(array("codfaixaprecoformaentrega" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
			
			$data["cepinicialfaixaprecoformaentrega"] = str_pad($res->cepinicialfaixaprecoformaentrega, 8, "0", STR_PAD_LEFT);
			$data["cepfinalfaixaprecoformaentrega"] = str_pad($res->cepfinalfaixaprecoformaentrega, 8, "0", STR_PAD_LEFT);
			
			$data['pesoinicialfaixaprecoformaentrega'] = modificaNumericPeso($res->pesoinicialfaixaprecoformaentrega);
			$data['pesofinalfaixaprecoformaentrega'] = modificaNumericPeso($res->pesofinalfaixaprecoformaentrega);
			$data['valorfaixaprecoformaentrega'] = modificaNumericValor($res->valorfaixaprecoformaentrega);
			
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$this->setURL($data, $res->codformaentrega);
		
		$this->parser->parse('painel/precoentrega_form', $data);
	}
	
	public function salvar() {
		
		$codfaixaprecoformaentrega	         = $this->input->post('codfaixaprecoformaentrega');
		$cepinicialfaixaprecoformaentrega	 = $this->input->post('cepinicialfaixaprecoformaentrega');
		$cepfinalfaixaprecoformaentrega	     = $this->input->post('cepfinalfaixaprecoformaentrega');
		$pesoinicialfaixaprecoformaentrega	 = $this->input->post('pesoinicialfaixaprecoformaentrega');
		$pesofinalfaixaprecoformaentrega	 = $this->input->post('pesofinalfaixaprecoformaentrega');
		$valorfaixaprecoformaentrega	     = $this->input->post('valorfaixaprecoformaentrega');
		$codformaentrega	                 = $this->input->post('codformaentrega');
		$prazofaixaprecoformaentrega	     = $this->input->post('prazofaixaprecoformaentrega');

		$cepinicialfaixaprecoformaentrega = str_replace("-", null, $cepinicialfaixaprecoformaentrega);
		$cepfinalfaixaprecoformaentrega = str_replace("-", null, $cepfinalfaixaprecoformaentrega);
		$pesoinicialfaixaprecoformaentrega = modificaDinheiroBanco($pesoinicialfaixaprecoformaentrega);
		$pesofinalfaixaprecoformaentrega = modificaDinheiroBanco($pesofinalfaixaprecoformaentrega);
		$valorfaixaprecoformaentrega = modificaDinheiroBanco($valorfaixaprecoformaentrega);
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$erros) {
			$itens	= array(
				"cepinicialfaixaprecoformaentrega"	         => $cepinicialfaixaprecoformaentrega,
				"cepfinalfaixaprecoformaentrega"	         => $cepfinalfaixaprecoformaentrega,
				"pesoinicialfaixaprecoformaentrega"	         => $pesoinicialfaixaprecoformaentrega,
				"pesofinalfaixaprecoformaentrega"	         => $pesofinalfaixaprecoformaentrega,
				"valorfaixaprecoformaentrega"	             => $valorfaixaprecoformaentrega,
				"codformaentrega"	                         => $codformaentrega,
			    "prazofaixaprecoformaentrega"                => $prazofaixaprecoformaentrega
			);
			
			if ($codfaixaprecoformaentrega) {
				$codfaixaprecoformaentrega = $this->PrecoEntregaM->update($itens, $codfaixaprecoformaentrega);
			} else {
				$codfaixaprecoformaentrega = $this->PrecoEntregaM->post($itens);
			}
			
			if ($codfaixaprecoformaentrega) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/precoentrega/ver/'.$codformaentrega);
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codfaixaprecoformaentrega) {
					redirect('painel/precoentrega/editar/'.$codfaixaprecoformaentrega);
				} else {
					redirect('painel/precoentrega/adicionar/'.$codformaentrega);
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codfaixaprecoformaentrega) {
				redirect('painel/precoentrega/editar/'.$codfaixaprecoformaentrega);
			} else {
				redirect('painel/precoentrega/adicionar/'.$codformaentrega);
			}
		}
		
	}
	
	private function setURL(&$data, $id) {
		$data['URLLISTAR']	= site_url('painel/precoentrega/ver/'.$id);
		$data['ACAOFORM']	= site_url('painel/precoentrega/salvar');
	}
	
	public function excluir($id) {
	    $info	= $this->PrecoEntregaM->get(array("codfaixaprecoformaentrega" => $id), TRUE);
	    
		$res    = $this->PrecoEntregaM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Forma de entrega removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Forma de entrega não pode ser removido.');
		}
		
		redirect('painel/precoentrega/ver/'.$info->codformaentrega);
	}
	
	public function gera($codformaentrega) {
	    $res	= $this->FormaEntregaM->get(array("codformaentrega" => $codformaentrega), TRUE);
	}
	
}