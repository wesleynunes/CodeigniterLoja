<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FormaEntrega extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('FormaEntrega_Model', 'FormaEntregaM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/formaentrega/adicionar');
		$data['URLLISTAR']		= site_url('painel/formaentrega');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->FormaEntregaM->get(array(), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"NOME"		=> $r->nomeformaentrega,
				    "HABILITADO"=> ($r->habilitaformaentrega=='S')?'Sim':'Não', 
					"URLADICIONARPRECO"	=> site_url('painel/precoentrega/ver/'.$r->codformaentrega),
					"URLFRETE"	=> site_url('painel/faixacepfretegratis/ver/'.$r->codformaentrega),
					"URLEDITAR"	=> site_url('painel/formaentrega/editar/'.$r->codformaentrega),
					"URLEXCLUIR"=> site_url('painel/formaentrega/excluir/'.$r->codformaentrega)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->FormaEntregaM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/formaentrega?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/formaentrega?pagina='.($pagina-1));
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/formaentrega?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/formaentrega_listar', $data);
	}
	
	public function adicionar() {
	
		$data						= array();
		$data['ACAO']				= 'Novo';
		$data['codformaentrega']	= '';
		$data['nomeformaentrega']	= '';
		$data['codigocorreiosformaentrega'] = null;
		$data['chk_habilitaformaentrega'] = null;
		
		$this->setURL($data);
		
		$this->parser->parse('painel/formaentrega_form', $data);
	}
	
	public function editar($id) {
		$data						= array();
		$data['ACAO']				= 'Edição';
		$data['chk_habilitaformaentrega'] = null;
		
		$res	= $this->FormaEntregaM->get(array("codformaentrega" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
			if ($res->habilitaformaentrega === 'S') {
			    $data['chk_habilitaformaentrega'] = 'checked="checked"';
			}	
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$this->setURL($data);
		
		$this->parser->parse('painel/formaentrega_form', $data);
	}
	
	public function salvar() {
		
		$codformaentrega	        = $this->input->post('codformaentrega');
		$nomeformaentrega	        = $this->input->post('nomeformaentrega');
		$codigocorreiosformaentrega	= $this->input->post('codigocorreiosformaentrega');
		$habilitaformaentrega	    = $this->input->post('habilitaformaentrega');
		
		if (!$habilitaformaentrega) {
		    $habilitaformaentrega = 'N';
		}
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$nomeformaentrega) {
			$erros		= TRUE;
			$mensagem	.= "Informe nome da forma de entrega\n";
		}
		
		if (!$erros) {
			$itens	= array(
				"nomeformaentrega"	         => $nomeformaentrega,
			    "codigocorreiosformaentrega" => $codigocorreiosformaentrega,
			    "habilitaformaentrega"       => $habilitaformaentrega
			);
			
			if ($codformaentrega) {
				$codformaentrega = $this->FormaEntregaM->update($itens, $codformaentrega);
			} else {
				$codformaentrega = $this->FormaEntregaM->post($itens);
			}
			
			if ($codformaentrega) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/formaentrega');
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codformaentrega) {
					redirect('painel/formaentrega/editar/'.$codformaentrega);
				} else {
					redirect('painel/formaentrega/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codformaentrega) {
				redirect('painel/formaentrega/editar/'.$codformaentrega);
			} else {
				redirect('painel/formaentrega/adicionar');
			}
		}
		
	}
	
	private function setURL(&$data) {
		$data['URLLISTAR']	= site_url('painel/formaentrega');
		$data['ACAOFORM']	= site_url('painel/formaentrega/salvar');
	}
	
	public function excluir($id) {
		$res = $this->FormaEntregaM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Forma de entrega removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Forma de entrega não pode ser removido.');
		}
		
		redirect('painel/formaentrega');
	}
	
}