<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atributo extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('Atributo_Model', 'AtributoM');
		$this->load->model('TipoAtributo_Model', 'TipoAtributoM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/atributo/adicionar');
		$data['URLLISTAR']		= site_url('painel/atributo');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
	
		$pagina			= $this->input->get('pagina');
	
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
	
		$res	= $this->AtributoM->get(array(), FALSE, $pagina);
	
		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
						"NOME"		=> $r->nomeatributo,
						"NOMETIPO"	=> (empty($r->nomepai))?'-':$r->nomepai,
						"URLEDITAR"	=> site_url('painel/atributo/editar/'.$r->codatributo),
						"URLEXCLUIR"=> site_url('painel/atributo/excluir/'.$r->codatributo)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
	
		$totalItens		= $this->AtributoM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
	
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
	
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/atributo?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
	
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/atributo?pagina='.($pagina-1));
		}
	
	
	
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
					"LINK"		=> ($indicePg==$pagina)?'active':null,
					"INDICE"	=> $indicePg,
					"URLLINK"	=> site_url('painel/atributo?pagina='.$indicePg)
			);
				
			$indicePg++;
		}
	
		$this->parser->parse('painel/atributo_listar', $data);
	}
	
	private function setURL(&$data) {
		$data['URLLISTAR']	= site_url('painel/atributo');
		$data['ACAOFORM']	= site_url('painel/atributo/salvar');
	}
	
	public function adicionar() {
	
		$data							= array();
		$data['ACAO']					= 'Novo';
		$data['BLC_TIPOATRIBUTOS']		= array();
		$data['codatributo']			= '';
		$data['nomeatributo']			= '';
	
	
		$tipo	= $this->TipoAtributoM->get(array(), FALSE, 0, FALSE);
	
		foreach($tipo as $t){
			$data['BLC_TIPOATRIBUTOS'][] = array(
					"CODTIPOATRIBUTO"		=> $t->codtipoatributo,
					"NOME"					=> $t->nometipoatributo,
					"sel_codtipoatributo"	=> null
			);
		}
	
		$this->setURL($data);
	
		$this->parser->parse('painel/atributo_form', $data);
	}

	public function salvar() {
		$codatributo		= $this->input->post('codatributo');
		$nomeatributo		= $this->input->post('nomeatributo');
		$codtipoatributo	= $this->input->post('codtipoatributo');
	
		$erros			= FALSE;
		$mensagem		= null;
	
		if (!$nomeatributo) {
			$erros		= TRUE;
			$mensagem	.= "Informe nome do atributo.\n";
		}
		if (!$codtipoatributo) {
			$erros		= TRUE;
			$mensagem	.= "Informe o tipo de atributo.\n";
		}
		if (!$erros) {
			$itens	= array(
					"nomeatributo"		=> $nomeatributo,
					"codtipoatributo"	=> $codtipoatributo
			);
				
				
			if ($codatributo) {
				$codatributo = $this->AtributoM->update($itens, $codatributo);
			} else {
				$codatributo = $this->AtributoM->post($itens);
			}
				
			if ($codatributo) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/atributo');
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
	
				if ($codatributo) {
					redirect('painel/atributo/editar/'.$codatributo);
				} else {
					redirect('painel/atributo/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codatributo) {
				redirect('painel/atributo/editar/'.$codatributo);
			} else {
				redirect('painel/atributo/adicionar');
			}
		}
	}
	
	public function excluir($id) {
		$res = $this->AtributoM->delete($id);
	
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Atributo removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Atributo não pode ser removido.');
		}
	
		redirect('painel/atributo');
	}

	public function editar($id) {		
		$data							= array();
		$data['ACAO']					= 'Edição';
		$data['BLC_TIPOATRIBUTOS']		= array();

		//INFORMAÇÕES DO ATRIBUTO
		$res	= $this->AtributoM->get(array("a.codatributo" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
	
		//TIPOS DO ATRIBUTO
		$tipo	= $this->TipoAtributoM->get(array(), FALSE, 0, FALSE);
	
		foreach($tipo as $t){
			$data['BLC_TIPOATRIBUTOS'][] = array(
					"CODTIPOATRIBUTO"		=> $t->codtipoatributo,
					"NOME"					=> $t->nometipoatributo,
					"sel_codtipoatributo"	=> ($res->codtipoatributo==$t->codtipoatributo)?'selected="selected"':null
			);
		}
	
		$this->setURL($data);
	
		$this->parser->parse('painel/atributo_form', $data);
	}
}