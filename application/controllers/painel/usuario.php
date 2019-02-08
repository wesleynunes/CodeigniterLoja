<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('Usuario_Model', 'UsuarioM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/usuario/adicionar');
		$data['URLLISTAR']		= site_url('painel/usuario');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->UsuarioM->get(array(), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"NOME"		=> $r->nomeusuario,
					"URLEDITAR"	=> site_url('painel/usuario/editar/'.$r->codusuario),
					"URLEXCLUIR"=> site_url('painel/usuario/excluir/'.$r->codusuario)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->UsuarioM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/usuario?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/usuario?pagina='.$pagina-1);
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/usuario?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/usuario_listar', $data);
	}
	
	public function adicionar() {
	
		$data						= array();
		$data['ACAO']				= 'Novo';
		$data['codusuario']			= '';
		$data['nomeusuario']		= '';
		$data['emailusuario']		= '';
		$data['chk_ativousuario']	= '';
		
		$this->setURL($data);
		
		$this->parser->parse('painel/usuario_form', $data);
	}
	
	public function editar($id) {
		$data						= array();
		$data['ACAO']				= 'Edição';
		
		$res	= $this->UsuarioM->get(array("codusuario" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
			
			$data['chk_ativousuario'] = ($res->ativadousuario=='S')?'checked="checked"':null;
			
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$this->setURL($data);
		
		$this->parser->parse('painel/usuario_form', $data);
	}
	
	public function salvar() {
		
		$codusuario		= $this->input->post('codusuario');
		$nomeusuario	= $this->input->post('nomeusuario');
		$emailusuario	= $this->input->post('emailusuario');
		$senhausuario	= $this->input->post('senhausuario');
		$ativadousuario	= $this->input->post('ativadousuario');
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$nomeusuario) {
			$erros		= TRUE;
			$mensagem	.= "Informe nome do usuário\n";
		}
		if (!$emailusuario) {
			$erros		= TRUE;
			$mensagem	.= "Informe email do usuário\n";
		}
		if (!$senhausuario) {
			if (!$codusuario) {
				$erros		= TRUE;
				$mensagem	.= "Informe senha do usuário\n";
			}
		}
		
		if (!$erros) {
			$itens	= array(
				"nomeusuario"	=> $nomeusuario,
				"emailusuario"	=> $emailusuario,
				"ativadousuario"=> ($ativadousuario)?$ativadousuario:'N'
			);
			
			if ($senhausuario) {
				$itens['senhausuario']	= $this->encrypt->sha1($senhausuario);
			}
			
			if ($codusuario) {
				$codusuario = $this->UsuarioM->update($itens, $codusuario);
			} else {
				$codusuario = $this->UsuarioM->post($itens);
			}
			
			if ($codusuario) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/usuario');
			} else {
				$this->session->set_flashdata('error', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codusuario) {
					redirect('painel/usuario/editar/'.$codusuario);
				} else {
					redirect('painel/usuario/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('error', nl2br($mensagem));
			if ($codusuario) {
				redirect('painel/usuario/editar/'.$codusuario);
			} else {
				redirect('painel/usuario/adicionar');
			}
		}
		
	}
	
	private function setURL(&$data) {
		$data['URLLISTAR']	= site_url('painel/usuario');
		$data['ACAOFORM']	= site_url('painel/usuario/salvar');
	}
	
	public function excluir($id) {
		$res = $this->UsuarioM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Usuário removido com sucesso.');
		} else {
			$this->session->set_flashdata('error', 'Usuário não pode ser removido.');
		}
		
		redirect('painel/usuario');
	}
	
}