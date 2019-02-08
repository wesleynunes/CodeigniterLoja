<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vitrine extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('Vitrine_Model', 'VitrineM');
		$this->load->model('VitrineProduto_Model', 'VitrineProdutoM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/vitrine/adicionar');
		$data['URLLISTAR']		= site_url('painel/vitrine');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
		
		$pagina			= $this->input->get('pagina');
		
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
		
		$res	= $this->VitrineM->get(array(), FALSE, $pagina);

		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
					"NOME"		   => $r->nomevitrine,
				    "HABILITADO"   => ($r->vitrineativa=='S')?'Sim':'Não', 
					"URLPRODUTOS"  => site_url('painel/vitrine/produtos/'.$r->codvitrine),
					"URLEDITAR"	   => site_url('painel/vitrine/editar/'.$r->codvitrine),
					"URLEXCLUIR"   => site_url('painel/vitrine/excluir/'.$r->codvitrine)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
		
		$totalItens		= $this->VitrineM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
		
		
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/vitrine?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
		
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/vitrine?pagina='.$pagina-1);
		}
		
		
		
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
				"LINK"		=> ($indicePg==$pagina)?'active':null,
				"INDICE"	=> $indicePg,
				"URLLINK"	=> site_url('painel/vitrine?pagina='.$indicePg)
			);
			
			$indicePg++;
		}
		
		$this->parser->parse('painel/vitrine_listar', $data);
	}
	
	public function adicionar() {
	
		$data						          = array();
		$data['ACAO']				          = 'Novo';
		$data['codvitrine']	                  = '';
		$data['nomevitrine']	              = '';
		$data['descontovitrine']              = 0;
		$data['datainiciovitrine']            = null;
		$data['datafinalvitrine']             = null;
		$data['chk_vitrineativa']             = null;
		
		$this->setURL($data);
		
		$this->parser->parse('painel/vitrine_form', $data);
	}
	
	public function editar($id) {
		$data						          = array();
		$data['ACAO']				          = 'Edição';
		$data['chk_vitrineativa']             = null;
		
		$res	= $this->VitrineM->get(array("codvitrine" => $id), TRUE);
		
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
			if ($res->vitrineativa === 'S') {
			    $data['chk_vitrineativa'] = 'checked="checked"';
			}
			
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$data['datainiciovitrine']            = dateMySQL2BR($res->datainiciovitrine);
		$data['datafinalvitrine']             = dateMySQL2BR($res->datafinalvitrine);
		
		$this->setURL($data);
		
		$this->parser->parse('painel/vitrine_form', $data);
	}
	
	public function salvar() {
		
		$codvitrine	              = $this->input->post('codvitrine');
		$nomevitrine	          = $this->input->post('nomevitrine');
		$vitrineativa	          = $this->input->post('vitrineativa');
		$datainiciovitrine	      = $this->input->post('datainiciovitrine');
		$datafinalvitrine	      = $this->input->post('datafinalvitrine');
		
	   if ($datainiciovitrine) {
		    $datainiciovitrine = dateBR2MySQL($datainiciovitrine);
		}
		
		if ($datafinalvitrine) {
		    $datafinalvitrine = dateBR2MySQL($datafinalvitrine);
		}
		
		if (!$vitrineativa) {
		    $vitrineativa = 'N';
		}
		
		$erros			= FALSE;
		$mensagem		= null;
		
		if (!$nomevitrine) {
			$erros		= TRUE;
			$mensagem	.= "Informe o nome da vitrine\n";
		}
		
		if (!$erros) {
			$itens	= array(
				"nomevitrine"	          => $nomevitrine,
			    "vitrineativa"            => $vitrineativa,
			    "datainiciovitrine"       => $datainiciovitrine,
			    "datafinalvitrine"        => $datafinalvitrine
			        
			);
			
			if ($codvitrine) {
				$codvitrine = $this->VitrineM->update($itens, $codvitrine);
			} else {
				$codvitrine = $this->VitrineM->post($itens);
			}
			
			if ($codvitrine) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				redirect('painel/vitrine');
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
				
				if ($codvitrine) {
					redirect('painel/vitrine/editar/'.$codvitrine);
				} else {
					redirect('painel/vitrine/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codvitrine) {
				redirect('painel/vitrine/editar/'.$codvitrine);
			} else {
				redirect('painel/vitrine/adicionar');
			}
		}
		
	}
	
	private function setURL(&$data) {
		$data['URLLISTAR']	= site_url('painel/vitrine');
		$data['ACAOFORM']	= site_url('painel/vitrine/salvar');
	}
	
	public function excluir($id) {
		$res = $this->VitrineM->delete($id);
		
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Vitrine removida com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Vitrine não pode ser removido.');
		}
		
		redirect('painel/vitrine');
	}
	
	/**
	 * Visualiza produtos da vitrine
	 */
	public function produtos($codvitrine) {
	    $data					= array();
	    $data['URLADICIONAR']	= site_url('painel/vitrine/adicionarproduto/'.$codvitrine);
	    $data['URLLISTAR']		= site_url('painel/vitrine/produtos/'.$codvitrine);
	    $data['URLVOLTAR']		= site_url('painel/vitrine');
	    $data['BLC_DADOS']		= array();
	    $data['BLC_SEMDADOS']	= array();
	    $data['BLC_PAGINAS']	= array();
	    
	    $infoVitrine	= $this->VitrineM->get(array("codvitrine" => $codvitrine), TRUE);
	    
	    if (!$infoVitrine) {
	        show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
	    }
	    
	    $data['NOMEVITRINE']   = $infoVitrine->nomevitrine;
	    
	    $pagina			= $this->input->get('pagina');
	    
	    if (!$pagina) {
	        $pagina = 0;
	    } else {
	        $pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
	    }
	    
	    $res	    = $this->VitrineProdutoM->get(array("codvitrine" => $codvitrine), FALSE, $pagina);
	    $totalItens	= $this->VitrineProdutoM->getTotal(array("codvitrine" => $codvitrine));
	    if ($res) {
	        foreach($res as $r) {
	            
    	        $aUp   = array();
    	        $aDown = array();
    	        
    	        if ($r->ordemvitrineproduto > 1) {
    	            $aUp[] = array(
    	            	"URLUP" => site_url('painel/vitrine/aumentarproduto/'.$codvitrine.'/'.$r->codvitrineproduto)
    	            );
    	        }
    	        
    	        if ($r->ordemvitrineproduto < $totalItens) {
    	            $aDown[] = array(
    	                "URLDOWN" => site_url('painel/vitrine/diminuiproduto/'.$codvitrine.'/'.$r->codvitrineproduto)
    	            );
    	        }
    	        
	            $data['BLC_DADOS'][] = array(
	                    "NOME"		   => $r->nomeproduto,
	                    "BLC_UP"       => $aUp,
	                    "BLC_DOWN"     => $aDown,
	                    "URLEXCLUIR"   => site_url('painel/vitrine/excluirproduto/'.$codvitrine.'/'.$r->codvitrineproduto)
	            );
	        }
	    } else {
	        $data['BLC_SEMDADOS'][] = array();
	    }
	    
	    //$totalItens		= $this->VitrineM->getTotal(array("codvitrine" => $codvitrine));
	    $totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
	    
	    $indicePg		= 1;
		$pagina			= $this->input->get('pagina');
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
	    
	    if ($totalPaginas > $pagina) {
	        $data['HABPROX']	= null;
	        $data['URLPROXIMO']	= site_url('painel/vitrine/produtos/'.$codvitrine.'?pagina='.($pagina+1));
	    } else {
	        $data['HABPROX']	= 'disabled';
	        $data['URLPROXIMO']	= '#';
	    }
	    
	    if ($pagina <= 1) {
	        $data['HABANTERIOR']= 'disabled';
	        $data['URLANTERIOR']= '#';
	    } else {
	        $data['HABANTERIOR']= null;
	        $data['URLANTERIOR']= site_url('painel/vitrine/produtos/'.$codvitrine.'?pagina='.$pagina-1);
	    }
	    
	    
	    
	    while ($indicePg <= $totalPaginas) {
	        $data['BLC_PAGINAS'][] = array(
	                "LINK"		=> ($indicePg==$pagina)?'active':null,
	                "INDICE"	=> $indicePg,
	                "URLLINK"	=> site_url('painel/vitrine/produtos/'.$codvitrine.'?pagina='.$indicePg)
	        );
	        	
	        $indicePg++;
	    }
	    
	    $this->parser->parse('painel/produtovitrine_listar', $data);
	}
	
	public function adicionarproduto($codvitrine) {
	    $infoVitrine	= $this->VitrineM->get(array("codvitrine" => $codvitrine), TRUE);
	     
	    if (!$infoVitrine) {
	        show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
	    }
	    
	    $data = array();
	    $data['codvitrine']    = $codvitrine;
	    $data['NOMEVITRINE']   = $infoVitrine->nomevitrine;
	    $data['BLC_PRODUTOS']  = array();
	    $data['ACAOFORM']      = site_url('painel/vitrine/salvaproduto');
	    $data['URLLISTAR']     = site_url('painel/vitrine/produtos/'.$codvitrine);
	    
	    $this->load->model('Produto_Model', 'ProdutoM');
	    
	    $res	= $this->ProdutoM->get(array(
	    	"codproduto NOT IN (SELECT codproduto FROM vitrineproduto WHERE codvitrine = $codvitrine)" => NULL
	    ), FALSE, 0, 9999);
	    
	    if ($res) {
	        foreach($res as $r) {
	            $data['BLC_PRODUTOS'][] = array(
	            	"CODPRODUTO" => $r->codproduto,
	                 "NOMEPRODUTO" => $r->nomeproduto
	            );
	        }
	    }
	    
	    $this->parser->parse('painel/vitrineproduto_form', $data);
	}
	
	public function salvaproduto() {
	    $codproduto = $this->input->post('codproduto');
	    $codvitrine = $this->input->post('codvitrine');
	    
	    $post = array(
	    	"codproduto" => $codproduto,
	        "codvitrine" => $codvitrine
	    );
	    
	    $this->VitrineProdutoM->post($post);
	    
	    $this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
	    redirect('painel/vitrine/produtos/'.$codvitrine);
	}
	
	public function aumentarproduto($codvitrine, $codvitrineproduto) {
	    
	    $filtro = array(
	    	"codvitrineproduto" => $codvitrineproduto
	    );
	    
	    $infoProduto   = $this->VitrineProdutoM->get($filtro, TRUE);
	    
	    if (!$infoProduto) {
	        $this->session->set_flashdata('erro', 'Produto não existe.');
	        redirect('painel/vitrine/produtos/'.$codvitrine);
	    }
	    
	    $velhaPosicao  = $infoProduto->ordemvitrineproduto;
	    $novaPosicao   = $velhaPosicao - 1;
	    
	    $this->VitrineProdutoM->diminuirOrdem($codvitrineproduto);
	    
	    $this->VitrineProdutoM->redefineAntigo($codvitrine, $codvitrineproduto, $novaPosicao, $velhaPosicao);
	    
	    $this->session->set_flashdata('sucesso', 'Produto reposicionado com sucesso.');
	    redirect('painel/vitrine/produtos/'.$codvitrine);
	}
	
	public function diminuiproduto($codvitrine, $codvitrineproduto) {
	     
	    $filtro = array(
	            "codvitrineproduto" => $codvitrineproduto
	    );
	     
	    $infoProduto   = $this->VitrineProdutoM->get($filtro, TRUE);
	    
	    if (!$infoProduto) {
	        $this->session->set_flashdata('erro', 'Produto não existe.');
	        redirect('painel/vitrine/produtos/'.$codvitrine);
	    }
	     
	    $velhaPosicao  = $infoProduto->ordemvitrineproduto;
	    $novaPosicao   = $velhaPosicao + 1;
	     
	    $this->VitrineProdutoM->aumentarOrdem($codvitrineproduto);
	     
	    $this->VitrineProdutoM->redefineAntigo($codvitrine, $codvitrineproduto, $novaPosicao, $velhaPosicao);
	     
	    $this->session->set_flashdata('sucesso', 'Produto reposicionado com sucesso.');
	    redirect('painel/vitrine/produtos/'.$codvitrine);
	}
	
	public function excluirproduto($codvitrine, $codvitrineproduto) {
	    $filtro = array(
	            "codvitrineproduto" => $codvitrineproduto
	    );
	    
	    $infoProduto   = $this->VitrineProdutoM->get($filtro, TRUE);
	    
	    if (!$infoProduto) {
	        $this->session->set_flashdata('erro', 'Produto não existe.');
	        redirect('painel/vitrine/produtos/'.$codvitrine);
	    }
	    
	    $velhaPosicao  = $infoProduto->ordemvitrineproduto;
	    
	    $this->VitrineProdutoM->delete($codvitrineproduto);
	    
	    $this->VitrineProdutoM->atualizaDelete($codvitrine, $velhaPosicao);
	    
	    $this->session->set_flashdata('sucesso', 'Produto removido com sucesso.');
	    redirect('painel/vitrine/produtos/'.$codvitrine);
	}
}