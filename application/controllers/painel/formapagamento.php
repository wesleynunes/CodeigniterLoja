<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FormaPagamento extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('FormaPagamento_Model', 'FormaPagamentoM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/formapagamento/adicionar');
		$data['URLLISTAR']		= site_url('painel/formapagamento');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->FormaPagamentoM->get(array(), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"NOME"		   => $r->nomeformapagamento,
					"MAXIMOPARCELA"=> ($r->maximoparcelasformapagamento==1)?'À Vista':$r->maximoparcelasformapagamento.' vezes',
				    "HABILITADO"   => ($r->habilitaformapagamento=='S')?'Sim':'Não', 
					"URLEDITAR"	   => site_url('painel/formapagamento/editar/'.$r->codformapagamento),
					"URLEXCLUIR"   => site_url('painel/formapagamento/excluir/'.$r->codformapagamento)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->FormaPagamentoM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/formapagamento?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/formapagamento?pagina='.($pagina-1));
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/formapagamento?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/formapagamento_listar', $data);
	}
	
	public function adicionar() {
	
		$data						          = array();
		$data['ACAO']				          = 'Novo';
		$data['codformapagamento']	          = '';
		$data['nomeformapagamento']	          = '';
		$data['descontoformapagamento']       = 0;
		$data['maximoparcelasformapagamento'] = 1;
		$data['chk_habilitaformapagamento']   = null;
		$data['sel_tipoformapagamento1']      = null;
		$data['sel_tipoformapagamento2']      = null;
		
		$this->setURL($data);
		
		$this->parser->parse('painel/formapagamento_form', $data);
	}
	
	public function editar($id) {
		$data						          = array();
		$data['ACAO']				          = 'Edição';
		$data['chk_habilitaformapagamento']   = null;
		$data['sel_tipoformapagamento1']      = null;
		$data['sel_tipoformapagamento2']      = null;
		
		$res	= $this->FormaPagamentoM->get(array("codformapagamento" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
			if ($res->habilitaformapagamento === 'S') {
			    $data['chk_habilitaformapagamento'] = 'checked="checked"';
			}
			
			$data['sel_tipoformapagamento'.$res->tipoformapagamento] = 'selected="selected"';
			$data['descontoformapagamento'] = number_format($res->descontoformapagamento, '2', ',', '.');
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$this->setURL($data);
		
		$this->parser->parse('painel/formapagamento_form', $data);
	}
	
	public function salvar() {
		
		$codformapagamento	          = $this->input->post('codformapagamento');
		$nomeformapagamento	          = $this->input->post('nomeformapagamento');
		$tipoformapagamento	          = $this->input->post('tipoformapagamento');
		$descontoformapagamento	      = $this->input->post('descontoformapagamento');
		$maximoparcelasformapagamento = $this->input->post('maximoparcelasformapagamento');
		$habilitaformapagamento	      = $this->input->post('habilitaformapagamento');
		
		$descontoformapagamento       = str_replace(".", null, $descontoformapagamento);
		$descontoformapagamento       = str_replace(",", ".", $descontoformapagamento);
		
		if (!$habilitaformapagamento) {
		    $habilitaformapagamento = 'N';
		}
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$nomeformapagamento) {
			$erros		= TRUE;
			$mensagem	.= "Informe nome da forma de pagamento\n";
		}
		
		if (!$erros) {
			$itens	= array(
				"nomeformapagamento"	         => $nomeformapagamento,
			    "tipoformapagamento"             => $tipoformapagamento,
			    "descontoformapagamento"         => $descontoformapagamento,
			    "maximoparcelasformapagamento"   => $maximoparcelasformapagamento,
			    "habilitaformapagamento"         => $habilitaformapagamento
			        
			);
			
			if ($codformapagamento) {
				$codformapagamento = $this->FormaPagamentoM->update($itens, $codformapagamento);
			} else {
				$codformapagamento = $this->FormaPagamentoM->post($itens);
			}
			
			if ($codformapagamento) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/formapagamento');
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codformapagamento) {
					redirect('painel/formapagamento/editar/'.$codformapagamento);
				} else {
					redirect('painel/formapagamento/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codformapagamento) {
				redirect('painel/formapagamento/editar/'.$codformapagamento);
			} else {
				redirect('painel/formapagamento/adicionar');
			}
		}
		
	}
	
	private function setURL(&$data) {
		$data['URLLISTAR']	= site_url('painel/formapagamento');
		$data['ACAOFORM']	= site_url('painel/formapagamento/salvar');
	}
	
	public function excluir($id) {
		$res = $this->FormaPagamentoM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Forma de pagamento removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Forma de pagamento não pode ser removido.');
		}
		
		redirect('painel/formapagamento');
	}
	
}