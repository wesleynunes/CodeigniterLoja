<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departamento extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('Departamento_Model', 'DepartamentoM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/departamento/adicionar');
		$data['URLLISTAR']		= site_url('painel/departamento');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->DepartamentoM->get(array(), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"NOME"		=> $r->nomedepartamento,
					"NOMEPAI"	=> (empty($r->nomepai))?'-':$r->nomepai,
					"URLEDITAR"	=> site_url('painel/departamento/editar/'.$r->codepartamento),
					"URLEXCLUIR"=> site_url('painel/departamento/excluir/'.$r->codepartamento)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->DepartamentoM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/departamento?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/departamento?pagina='.($pagina-1));
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/departamento?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/departamento_listar', $data);
	}
	
	public function adicionar() {
	
		$data							= array();
		$data['ACAO']					= 'Novo';
		$data['BLC_DEPARTAMENTOS']		= array();
		$data['hab_coddepartamentopai']	= null;
		$data['codepartamento']			= '';
		$data['nomedepartamento']		= '';
		
		
		$dep	= $this->DepartamentoM->get(array("d.coddepartamentopai IS NULL" => null));
		
		foreach($dep as $d){
			$data['BLC_DEPARTAMENTOS'][] = array(
				"CODDEPARTAMENTO"		=> $d->codepartamento,
				"NOME"					=> $d->nomedepartamento,
				"sel_coddepartamentopai"=> null
			);
		}
		
		$this->setURL($data);
		
		$this->parser->parse('painel/departamento_form', $data);
	}
	
	public function editar($id) {
		$data							= array();
		$data['ACAO']					= 'Edição';
		$data['BLC_DEPARTAMENTOS']		= array();
		
		$totalFilho = $this->DepartamentoM->getTotal(array("coddepartamentopai" => $id));
		
		$res	= $this->DepartamentoM->get(array("d.codepartamento" => $id), TRUE);
		
		if ($totalFilho > 0) {
			$data['BLC_DEPARTAMENTOS']		= array();
			$data['hab_coddepartamentopai']	= 'disabled="disabled"';
		} else {
			$dep	= $this->DepartamentoM->get(array("d.coddepartamentopai IS NULL" => null, "d.codepartamento != " => $id));
			
			foreach($dep as $d){
				$data['BLC_DEPARTAMENTOS'][] = array(
					"CODDEPARTAMENTO"		=> $d->codepartamento,
					"NOME"					=> $d->nomedepartamento,
					"sel_coddepartamentopai"=> ($res->coddepartamentopai==$d->codepartamento)?'selected="selected"':null
				);
			}
		}
		

		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
			
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$this->setURL($data);
		
		$this->parser->parse('painel/departamento_form', $data);
	}
	
	public function salvar() {
		
		$codepartamento		= $this->input->post('codepartamento');
		$nomedepartamento	= $this->input->post('nomedepartamento');
		$coddepartamentopai	= $this->input->post('coddepartamentopai');
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$nomedepartamento) {
			$erros		= TRUE;
			$mensagem	.= "Informe nome do departamento.\n";
		}
		if (!$erros) {
			$itens	= array(
				"nomedepartamento"	=> $nomedepartamento,
				"coddepartamentopai"=> ($coddepartamentopai)?$coddepartamentopai:null
			);
			
			
			if ($codepartamento) {
				$codepartamento = $this->DepartamentoM->update($itens, $codepartamento);
			} else {
				$codepartamento = $this->DepartamentoM->post($itens);
			}
			
			if ($codepartamento) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/departamento');
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codepartamento) {
					redirect('painel/departamento/editar/'.$codepartamento);
				} else {
					redirect('painel/departamento/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codepartamento) {
				redirect('painel/departamento/editar/'.$codepartamento);
			} else {
				redirect('painel/departamento/adicionar');
			}
		}
		
	}
	
	private function setURL(&$data) {
		$data['URLLISTAR']	= site_url('painel/departamento');
		$data['ACAOFORM']	= site_url('painel/departamento/salvar');
	}
	
	public function excluir($id) {
		$res = $this->DepartamentoM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Departamento removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Departamento não pode ser removido.');
		}
		
		redirect('painel/departamento');
	}
	
}