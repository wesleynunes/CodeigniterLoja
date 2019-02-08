<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TipoAtributo extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('TipoAtributo_Model', 'TipoAtributoM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/tipoatributo/adicionar');
		$data['URLLISTAR']		= site_url('painel/tipoatributo');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->TipoAtributoM->get(array(), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"NOME"		=> $r->nometipoatributo,
					"URLEDITAR"	=> site_url('painel/tipoatributo/editar/'.$r->codtipoatributo),
					"URLEXCLUIR"=> site_url('painel/tipoatributo/excluir/'.$r->codtipoatributo)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->TipoAtributoM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/tipoatributo?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/tipoatributo?pagina='.($pagina-1));
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/tipoatributo?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/tipoatributo_listar', $data);
	}
	
	public function adicionar() {
	
		$data						= array();
		$data['ACAO']				= 'Novo';
		$data['codtipoatributo']	= '';
		$data['nometipoatributo']	= '';
		
		$this->setURL($data);
		
		$this->parser->parse('painel/tipoatributo_form', $data);
	}
	
	public function editar($id) {
		$data						= array();
		$data['ACAO']				= 'Edição';
		
		$res	= $this->TipoAtributoM->get(array("codtipoatributo" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}			
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$this->setURL($data);
		
		$this->parser->parse('painel/tipoatributo_form', $data);
	}
	
	public function salvar() {
		
		$codtipoatributo	= $this->input->post('codtipoatributo');
		$nometipoatributo	= $this->input->post('nometipoatributo');
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$nometipoatributo) {
			$erros		= TRUE;
			$mensagem	.= "Informe nome do tipo de atributo.\n";
		}
		
		if (!$erros) {
			$itens	= array(
				"nometipoatributo"	=> $nometipoatributo
			);
			
			if ($codtipoatributo) {
				$codtipoatributo = $this->TipoAtributoM->update($itens, $codtipoatributo);
			} else {
				$codtipoatributo = $this->TipoAtributoM->post($itens);
			}
			
			if ($codtipoatributo) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/tipoatributo');
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codtipoatributo) {
					redirect('painel/tipoatributo/editar/'.$codtipoatributo);
				} else {
					redirect('painel/tipoatributo/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codtipoatributo) {
				redirect('painel/tipoatributo/editar/'.$codtipoatributo);
			} else {
				redirect('painel/tipoatributo/adicionar');
			}
		}
		
	}
	
	private function setURL(&$data) {
		$data['URLLISTAR']	= site_url('painel/tipoatributo');
		$data['ACAOFORM']	= site_url('painel/tipoatributo/salvar');
	}
	
	public function excluir($id) {
		$res = $this->TipoAtributoM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Tipo de atributo removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Tipo de atributo não pode ser removido.');
		}
		
		redirect('painel/tipoatributo');
	}
	
}