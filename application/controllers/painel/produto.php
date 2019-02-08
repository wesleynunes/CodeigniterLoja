<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produto extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->layout	= LAYOUT_DASHBOARD;
		$this->load->model('Produto_Model', 'ProdutoM');
		$this->load->model('TipoAtributo_Model', 'TipoAtributoM');
		$this->load->model('Departamento_Model', 'DepartamentoM');
		$this->load->model('ProdutoDepartamento_Model', 'ProdDepM');
		$this->load->model('ProdutoFoto_Model', 'FotoM');
		$this->load->model('Sku_Model', 'SkuM');
	}
	
	public function index() {
		$data					= array();
		$data['URLADICIONAR']	= site_url('painel/produto/adicionar');
		$data['URLLISTAR']		= site_url('painel/produto');
		$data['BLC_DADOS']		= array();
		$data['BLC_SEMDADOS']	= array();
		$data['BLC_PAGINAS']	= array();
	
		$pagina			= $this->input->get('pagina');
	
		if (!$pagina) {
			$pagina = 0;
		} else {
			$pagina = ($pagina-1) * LINHAS_PESQUISA_DASHBOARD;
		}
	
		$res	= $this->ProdutoM->get(array(), FALSE, $pagina);
	
		if ($res) {
			foreach($res as $r) {
				$data['BLC_DADOS'][] = array(
						"CODPRODUTO"	        => $r->codproduto,
						"NOME"			         => $r->nomeproduto,
						"URLEDITAR"		         => site_url('painel/produto/editar/'.$r->codproduto),
						"URLEXCLUIR"	         => site_url('painel/produto/excluir/'.$r->codproduto),
						"URLATRIBUTOS"	         => site_url('painel/produto/atributos/'.$r->codproduto),
						"URLUPLOAD"		         => site_url('painel/produto/uploadfoto/'.$r->codproduto),
				        "URLVINCULAIMAGEMSKU"    => site_url('painel/produto/fotosku/'.$r->codproduto)
				);
			}
		} else {
			$data['BLC_SEMDADOS'][] = array();
		}
	
		$totalItens		= $this->ProdutoM->getTotal();
		$totalPaginas	= ceil($totalItens/LINHAS_PESQUISA_DASHBOARD);
		
		$pagina			= $this->input->get('pagina');
	
		$indicePg		= 1;
		if (!$pagina) {
		    $pagina = 1;
		}
		$pagina			= ($pagina==0)?1:$pagina;
	
		if ($totalPaginas > $pagina) {
			$data['HABPROX']	= null;
			$data['URLPROXIMO']	= site_url('painel/produto?pagina='.($pagina+1));
		} else {
			$data['HABPROX']	= 'disabled';
			$data['URLPROXIMO']	= '#';
		}
	
		if ($pagina <= 1) {
			$data['HABANTERIOR']= 'disabled';
			$data['URLANTERIOR']= '#';
		} else {
		    $paginaVoltar = 99999;
		    
		    if ($pagina > 1) {
		        $paginaVoltar = $pagina - 1;
		    }
			$data['HABANTERIOR']= null;
			$data['URLANTERIOR']= site_url('painel/produto?pagina='.$paginaVoltar);
		}
	
	
	
		while ($indicePg <= $totalPaginas) {
			$data['BLC_PAGINAS'][] = array(
					"LINK"		=> ($indicePg==$pagina)?'active':null,
					"INDICE"	=> $indicePg,
					"URLLINK"	=> site_url('painel/produto?pagina='.$indicePg)
			);
				
			$indicePg++;
		}
	
		$this->parser->parse('painel/produto_listar', $data);
	}

	public function adicionar() {
	
		$data						= array();
		$data['ACAO']				= 'Novo';
		$data['codproduto']			= null;
		$data['nomeproduto']		= null;
		$data['resumoproduto']		= null;
		$data['fichaproduto']		= null;
		$data['peso']		        = null;
		$data['altura']		        = null;
		$data['largura']		    = null;
		$data['comprimento']		= null;
		$data['valorproduto']		= '0,00';
		$data['valorpromocional']	= '0,00';
		$data['BLC_TIPOATRIBUTOS']	= array();
		$data['BLC_DEPARTAMENTOPAI']= array();
		$data['des_tipoatributo']	= null;
		$data['readonly']	        = null;
		
		$tipo	= $this->TipoAtributoM->get(array(), FALSE, 0, FALSE);
		
		foreach($tipo as $t){
			$data['BLC_TIPOATRIBUTOS'][] = array(
					"CODTIPOATRIBUTO"		=> $t->codtipoatributo,
					"NOME"					=> $t->nometipoatributo,
					"sel_codtipoatributo"	=> null
			);
		}
		
		setURL($data, 'produto');
		
		/**
		 * BUSCA DEPARTAMENTOS PAI
		 */
		
		$depPai = $this->DepartamentoM->get(array("d.coddepartamentopai IS NULL" => NULL), FALSE, 0, FALSE);
		
		if ($depPai) {
			foreach($depPai as $dp) {
				
				//DEFINE OS ELEMENTOS DO FILHO - INÍCIO
				$aFilhos = array();
				
				$deFilhos = $this->DepartamentoM->get(array("d.coddepartamentopai" => $dp->codepartamento), FALSE, 0, FALSE);
				
				if ($deFilhos) {
					foreach($deFilhos as $df) {
						$aFilhos[] = array(
								"CODDEPARTAMENTOFILHO" 	=> $df->codepartamento,
								"NOMEDEPARTAMENTOFILHO"	=> $df->nomedepartamento,
								"CODDEPARTAMENTOPAI"	=> $df->coddepartamentopai,
								"chk_departamentofilho"	=> null
						);
					}
				}
				//DEFINE OS ELEMENTOS DO FILHO - FIM
				
				//DEFINE OS ELEMENTOS DO PAI
				$data['BLC_DEPARTAMENTOPAI'][] = array(
					"CODDEPARTAMENTO" 		=> $dp->codepartamento,
					"NOMEDEPARTAMENTO"		=> $dp->nomedepartamento,
					"BLC_DEPARTAMENTOFILHO" => $aFilhos,
					"chk_departamentopai"	=> null
				);
			}
		}
	
		$this->parser->parse('painel/produto_form', $data);
	}
	
	public function salvar() {
	
		$codproduto			= $this->input->post('codproduto');
		$nomeproduto		= $this->input->post('nomeproduto');
		$resumoproduto		= $this->input->post('resumoproduto');
		$fichaproduto		= $this->input->post('fichaproduto');
		$valorproduto		= $this->input->post('valorproduto');
		$valorpromocional	= $this->input->post('valorpromocional');
		$codtipoatributo	= $this->input->post('codtipoatributo');
		$quantidade			= $this->input->post('quantidade');
		$peso     			= $this->input->post('peso');
		$altura   			= $this->input->post('altura');
		$largura			= $this->input->post('largura');
		$comprimento		= $this->input->post('comprimento');
		
		$departamento		= $this->input->post('departamento');
		
		$valorproduto 		= modificaDinheiroBanco($valorproduto);
		$valorpromocional   = modificaDinheiroBanco($valorpromocional);
		$peso               = modificaDinheiroBanco($peso);
		$altura             = modificaDinheiroBanco($altura);
		$largura            = modificaDinheiroBanco($largura);
		$comprimento        = modificaDinheiroBanco($comprimento);

	
		$erros			= FALSE;
		$mensagem		= null;
	
		if (!$nomeproduto) {
			$erros		= TRUE;
			$mensagem	.= "Informe nome do produto.\n";
		}
		if (!$resumoproduto) {
			$erros		= TRUE;
			$mensagem	.= "Informe o resumo do produto.\n";
		}
		if (!$fichaproduto) {
			$erros		= TRUE;
			$mensagem	.= "Informe a ficha do produto.\n";
		}
		if (!$valorproduto) {
			$erros		= TRUE;
			$mensagem	.= "Informe o valor do produto.\n";
		} else {
			if ($valorpromocional) {
				if ($valorpromocional >= $valorproduto) {
					$erros		= TRUE;
					$mensagem	.= "Valor promocional não pode ser maior que o valor de venda.\n";
				}
			}
		}
	
		if (!$erros) {
			
			$itens	= array(
					"nomeproduto"		=> $nomeproduto,
					"resumoproduto"		=> $resumoproduto,
					"fichaproduto"		=> $fichaproduto,
					"valorproduto"		=> $valorproduto,
					"valorpromocional"	=> $valorpromocional,
					"peso"	            => $peso,
					"altura"	        => $altura,
					"largura"	        => $largura,
					"comprimento"	    => $comprimento
			);
			
			if (!$codproduto) {
				$itens["urlseo"] 			= url_title(strtolower($nomeproduto));
				
				if ($codtipoatributo) {
					$itens["codtipoatributo"] 	= $codtipoatributo;
				}
			}
				
			if ($codproduto) {
				$codproduto = $this->ProdutoM->update($itens, $codproduto);
			} else {
				$codproduto = $this->ProdutoM->post($itens);
				
				if (!$codtipoatributo) {
					$sku = array(
							"referencia"	=> null,
							"quantidade"	=> $quantidade,
							"codproduto"	=> $codproduto
					);
					
					$this->SkuM->post($sku);
				}
			}
				
			if ($codproduto) {
				$this->session->set_flashdata('sucesso', 'Dados inseridos com sucesso.');
				
				$this->ProdDepM->delete($codproduto);
				
				//VINCULA DEPARTAMENTOS
				foreach($departamento as $dep) {
					$itenDepProd = array();
					$itenDepProd['codproduto'] 				= $codproduto;
					$itenDepProd['codprodutodepartamento']	= $dep;
					$this->ProdDepM->post($itenDepProd);
				}
				
				redirect('painel/produto');
			} else {
				$this->session->set_flashdata('erro', 'Ocorreu um erro ao realizar a operação.');
	
				if ($codproduto) {
					redirect('painel/produto/editar/'.$codproduto);
				} else {
					redirect('painel/produto/adicionar');
				}
			}
		} else {
			$this->session->set_flashdata('erro', nl2br($mensagem));
			if ($codproduto) {
				redirect('painel/produto/editar/'.$codproduto);
			} else {
				redirect('painel/produto/adicionar');
			}
		}
	
	}

	public function editar($id) {
		$data						= array();
		$data['ACAO']				= 'Edição';
		$data['des_tipoatributo']	= 'disabled="disabled"';
		$data['readonly']	        = 'readonly';
	
		$res	= $this->ProdutoM->get(array("codproduto" => $id), TRUE);
	
		if ($res) {
			foreach($res as $chave => $valor) {
				$data[$chave] = $valor;
			}
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		if (empty($res->codtipoatributo)) {
		    $sku = $this->SkuM->getPorProdutoSimples ( $id );
		    $data["quantidade"] = $sku->quantidade;
		}
		
		$data['valorproduto'] 		= modificaNumericValor($res->valorproduto);
		$data['valorpromocional']	= modificaNumericValor($res->valorpromocional);
		$data['peso']	            = modificaNumericPeso($res->peso);
		$data['altura']	            = modificaNumericPeso($res->altura);
		$data['largura']	        = modificaNumericPeso($res->largura);
		$data['comprimento']	    = modificaNumericPeso($res->comprimento);
	
		setURL($data, 'produto');
		
		$tipo	= $this->TipoAtributoM->get(array(), FALSE, 0, FALSE);
		
		foreach($tipo as $t){
			$data['BLC_TIPOATRIBUTOS'][] = array(
					"CODTIPOATRIBUTO"		=> $t->codtipoatributo,
					"NOME"					=> $t->nometipoatributo,
					"sel_codtipoatributo"	=> ($t->codtipoatributo == $res->codtipoatributo)?'selected="selected"':null
			);
		}
		
		//ARMAZENA OS DEPARTAMENTOS VINCULADO
		$aDepartamentosVinculados = array();
		
		$depVinc = $this->ProdDepM->get(array("dp.codproduto" => $id));
		
		if ($depVinc) {
			foreach ($depVinc as $depv) {
				array_push($aDepartamentosVinculados, $depv->codprodutodepartamento);
			}
		}
		
		/**
		 * BUSCA DEPARTAMENTOS PAI
		 */
		
		$depPai = $this->DepartamentoM->get(array("d.coddepartamentopai IS NULL" => NULL), FALSE, 0, FALSE);
		
		if ($depPai) {
			foreach($depPai as $dp) {
		
				//DEFINE OS ELEMENTOS DO FILHO - INÍCIO
				$aFilhos = array();
		
				$deFilhos = $this->DepartamentoM->get(array("d.coddepartamentopai" => $dp->codepartamento), FALSE, 0, FALSE);
		
				if ($deFilhos) {
					foreach($deFilhos as $df) {
						$aFilhos[] = array(
								"CODDEPARTAMENTOFILHO" 	=> $df->codepartamento,
								"NOMEDEPARTAMENTOFILHO"	=> $df->nomedepartamento,
								"CODDEPARTAMENTOPAI"	=> $df->coddepartamentopai,
								"chk_departamentofilho"	=> (in_array($df->codepartamento, $aDepartamentosVinculados))?'checked="checked"':null
						);
					}
				}
				//DEFINE OS ELEMENTOS DO FILHO - FIM
		
				//DEFINE OS ELEMENTOS DO PAI
				$data['BLC_DEPARTAMENTOPAI'][] = array(
						"CODDEPARTAMENTO" 		=> $dp->codepartamento,
						"NOMEDEPARTAMENTO"		=> $dp->nomedepartamento,
						"BLC_DEPARTAMENTOFILHO" => $aFilhos,
						"chk_departamentopai"	=> (in_array($dp->codepartamento, $aDepartamentosVinculados))?'checked="checked"':null
				);
			}
		}
		
	
		$this->parser->parse('painel/produto_form', $data);
	}
	
	public function excluir($id) {
		$res = $this->ProdutoM->delete($id);
	
		if ($res) {
			$this->session->set_flashdata('sucesso', 'Produto removido com sucesso.');
		} else {
			$this->session->set_flashdata('erro', 'Produto não pode ser removido.');
		}
	
		redirect('painel/produto');
	}
	
	public function atributos($id) {
		$data						= array();
		$data['BLC_SEMVINCULADOS']	= array();
		$data['BLC_VINCULADOS']		= array();
		$data['BLC_SEMDISPONIVEIS']	= array();
		$data['BLC_DISPONIVEIS']	= array();
		$data['URLSALVAATRIBUTO']	= site_url('painel/produto/salvaatributo');
		$data['URLLISTAR']			= site_url('painel/produto');
		
		$infoProduto	= $this->ProdutoM->get(array("codproduto" => $id), TRUE);
		
		if ($infoProduto) {
			$data['NOMEPRODUTO']= $infoProduto->nomeproduto;
			$data['CODPRODUTO']	= $infoProduto->codproduto;
		} else {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$variosSKUs = FALSE;
		
		if (empty($infoProduto->codtipoatributo)) {
			$resExistente 	= $this->SkuM->getPorProdutoSimples($id);
		} else {
		    $variosSKUs = TRUE;
			$resExistente 	= $this->SkuM->getPorProdutoAtributo($id);
		}
		
		if ($resExistente) {
		    if ($variosSKUs) {
    			foreach($resExistente as $rEx) {
    				$data['BLC_VINCULADOS'][] = array(
    						"CODSKU"	 => $rEx->codsku,
    						"REFERENCIA" => $rEx->referencia,
    						"QUANTIDADE" => $rEx->quantidade,
    						"DESCRICAO"	 => $rEx->nomeatributo
    				);
    			}
		    } else {
		        $data['BLC_VINCULADOS'][] = array(
		                "CODSKU"	 => $resExistente->codsku,
		                "REFERENCIA" => $resExistente->referencia,
		                "QUANTIDADE" => $resExistente->quantidade,
		                "DESCRICAO"	 => $resExistente->nomeatributo
		        );
		    }
		} else {
			$data['BLC_SEMVINCULADOS'][] = array();
		}
		
		//ATRIBUTOS DISPONÍVEIS

		if (empty($infoProduto->codtipoatributo)) {
			$data['BLC_SEMDISPONIVEIS'][]	= array();
		} else {
			$atribDisponivel = $this->SkuM->getAtributosDisponiveis($id);
			
			if ($atribDisponivel) {
				foreach($atribDisponivel as $aD) {
					$data['BLC_DISPONIVEIS'][] 	= array(
							"DESCRICAO" 	=> $aD->nomeatributo,
							"CODATRIBUTO"	=> $aD->codatributo
					);
				}
			} else {
				$data['BLC_SEMDISPONIVEIS'][]	= array();
			}
		}
		
		$this->parser->parse('painel/produtoatributo_listar', $data);
	}
	
	public function salvaatributo() {
		
		$codproduto = $this->input->post('codproduto');
		
		//INSERE ATRIBUTOS E TRANSFORMA EM SKU
		$atributo	= $this->input->post('atributo');
		
		foreach($atributo as $codatributo => $valores) {
			if ((!empty($valores['referencia'])) || (!empty($valores['quantidade']))) {
				
				$sku = array(
					"referencia" 	=> $valores['referencia'],
					"quantidade"	=> $valores['quantidade'],
					"codproduto"	=> $codproduto
				);
				
				$codsku = $this->SkuM->post($sku);
				
				if ($codsku) {
					$atributoSku = array(
						"codsku" 		=> $codsku,
						"codatributo"	=> $codatributo
					);
					$this->SkuM->postAtributo($atributoSku);
				}
			}
		}
		
		//ATUALIZA SKUS
		$skuExistente	= $this->input->post('sku');
		
		if ($skuExistente) {
			foreach($skuExistente as $codsku => $valores) {
				if ($valores['remover'] === 'S') {
					$this->SkuM->delete($codsku);
				} else {
					
					$skuAtualiza = array(
							"referencia" => $valores['referencia'],
							"quantidade" => $valores['quantidade']
					);

					$this->SkuM->update($codsku, $skuAtualiza);
				}
			}
		}
		
		$this->session->set_flashdata('sucesso', 'SKUs salvos com sucesso.');
		
		redirect('painel/produto/atributos/'.$codproduto);
	}
	
	/**
	 * Cria o formulário de upload de foto
	 * @param integer $id
	 */
	public function uploadfoto($id) {
	    
	    $this->jsRequire = "upload";
	    
		$infoProduto	= $this->ProdutoM->get(array("codproduto" => $id), TRUE);
		
		if (!$infoProduto) {
			show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
		}
		
		$data				= array();
		$data['CODPRODUTO']	= $id;
		$data['NOMEPRODUTO']= $infoProduto->nomeproduto;
		$data['URLLISTAR']	= site_url('painel/produto');
		$data['URLUPLOAD']	= site_url('painel/produto/salvafoto');
		$this->parser->parse('painel/produto_foto_upload', $data);
	}
	
	/**
	 * Criação das fotos no servidor
	 */
	public function salvafoto() {
		$codproduto	= $this->input->post('codproduto');
		$arquivo	= $_FILES['fotos'];
		
		if ($arquivo['error'][0]) {
			
		}
		
		$arquivoNome= $arquivo["name"][0];
		
		$extensao	= strtolower(pathinfo($arquivoNome, PATHINFO_EXTENSION));
		
		$foto = array(
			"codproduto" 			=> $codproduto,
			"produtofotoextensao"	=> $extensao
		);
		
		$codfoto 		= $this->FotoM->post($foto);
		
		$enderecoFoto	= '/assets/img/produto/original/'.$codfoto.'.'.$extensao;
		
		move_uploaded_file($arquivo['tmp_name'][0], FCPATH.$enderecoFoto);
		
		//CRIA A MINIATURA DA GALERIA DE FOTOS
		$this->redimensionaFoto($codfoto, $extensao, 80, 80);
		
		//CRIA A MINIATURA DA PÁGINA INICIAL
		$this->redimensionaFoto($codfoto, $extensao, 150, 150);
		
		//CRIA A FOTO PARA A FICHA DE PRODUTOS
		$this->redimensionaFoto($codfoto, $extensao, 500, 500);
		
		$jsonRetorno	= array("files" => 
				array(array(
					"name" 			=> $arquivo["name"][0],
					"type" 			=> $arquivo["type"][0],
					"url"			=> base_url($enderecoFoto),
					"thumbnarilUrl"	=> base_url($enderecoFoto),
					"deleteUrl"		=> site_url('painel/produto/removefoto/'.$codfoto.'/'.$codproduto),
					"deleteType"	=> 'DELETE'	
				))
			);
		
		$this->layout = '';
		echo json_encode($jsonRetorno);
		die();
	}
	
	/**
	 * Remove as fotos do banco de dados e do servidor
	 * @param integer $codfoto
	 * @param integer $codproduto
	 */
	public function removefoto($codprodutofoto, $codproduto) {
		
		$condicao = array(
			"codprodutofoto"=> $codprodutofoto,
			"codproduto"	=> $codproduto
		);
		
		$infoFoto = $this->FotoM->get($condicao, TRUE);
		
		if ($this->FotoM->delete($codprodutofoto, $codproduto)) {
			
			unlink(FCPATH.'/assets/img/produto/original/'.$codprodutofoto.'.'.$infoFoto->produtofotoextensao);
	
		}
	}
	
	/**
	 * Redimensiona as imagens
	 * @param integer $codprodutofoto
	 * @param string $extensao
	 * @param integer $altura
	 * @param integer $largura
	 */
	private function redimensionaFoto($codprodutofoto, $extensao, $altura, $largura) {
		
		if (!is_dir(FCPATH."/assets/img/produto/{$altura}x{$largura}/")){
			mkdir(FCPATH."/assets/img/produto/{$altura}x{$largura}/");
		}
		
		$configImagem['image_library']	= 'gd2'; //BIBLIOTECA RESPONSÁVEL PELO REDIMENSIONAMENTO
		$configImagem['source_image']	= FCPATH.'/assets/img/produto/original/'.$codprodutofoto.'.'.$extensao;
		$configImagem['new_image']		= FCPATH."/assets/img/produto/{$altura}x{$largura}/".$codprodutofoto.'.'.$extensao;
		$configImagem['create_thumb']	= FALSE;
		$configImagem['maintain_ratio']	= TRUE;
		$configImagem['width']			= $largura;
		$configImagem['height']			= $altura;
		
		$this->load->library('image_lib');
		$this->image_lib->clear();
		$this->image_lib->initialize($configImagem);
		
		$this->image_lib->resize();
		
	}
	
	/**
	 * Exibe página de vinculação de fotos com atributos/skus
	 * @param unknown $codproduto
	 */
	public function fotosku($codproduto) {
	    $res	= $this->ProdutoM->get(array("codproduto" => $codproduto), TRUE);
	    
	    if (!$res) {
	        show_error('Não foram encontrados dados.', 500, 'Ops, erro encontrado.');
	    }
	    
	    $fotos = $this->FotoM->get(array("codproduto" => $codproduto));
	    
	    $data = array();
	    $data['NOMEPRODUTO'] = $res->nomeproduto;
	    $data['BLC_FOTOS'] = array();
	    $data['BLC_SEMFOTOS'] = array();
	    $data['URLSALVAFOTOATRIBUTO'] = site_url('painel/produto/salvafotosku');
	    
	    $sku = $this->SkuM->getPorProdutoAtributo($codproduto);
	    
	    $this->load->model('FotoSku_Model', 'FotoSkuM');
	    
	    if ($fotos) {
            foreach($fotos as $f) {
                
                $skusvinculados = $this->FotoSkuM->getSKUsFoto($f->codprodutofoto);
                
                $aSKUsVinculados = array();
                
                if ($skusvinculados) {
                    foreach ($skusvinculados as $sv) {
                        array_push($aSKUsVinculados, $sv->codsku);
                    }
                }
                
                $aSKU = array();
                if ($sku) {
                    foreach($sku as $s) {
                        $aSKU[] = array(
                                "CODSKU"  => $s->codsku,
                                "NOMESKU" => $s->nomeatributo,
                                "SEL_SKU" => in_array($s->codsku, $aSKUsVinculados)?"selected=\"selected\"":null
                        );
                    }
                }
                
    	        $data['BLC_FOTOS'][] = array(
    	        	"URLIMAGEM"        => base_url('assets/img/produto/80x80/'.$f->codprodutofoto.'.'.$f->produtofotoextensao),
    	            "BLC_SKUSPRODUTO"  => $aSKU,
    	            "CODPRODUTOFOTO"   => $f->codprodutofoto
    	        );
            }
	    } else {
	        $data['BLC_SEMFOTOS'][] = array();
	    }
	    
	    $data['CODPRODUTO'] = $codproduto;
	    
	    $this->parser->parse('painel/produtogerenciafoto_form', $data);
	}
	
	/**
	 * Vincula as fotos com o SKU no banco de dado
	 */
	public function salvafotosku() {
	    $skus      = $this->input->post('skus');
	    $codproduto= $this->input->post('codproduto');
	    $remover   = $this->input->post('remover');
	    
	    $aImagensRemover = array();
	    
	    if ($remover) {
	        foreach($remover as $codprodutofoto => $remove) {
	            array_push($aImagensRemover, $codprodutofoto);
	            
	            $this->FotoM->delete($codprodutofoto, $codproduto);
	        }
	    }
	    
	    $this->load->model('FotoSku_Model', 'FotoSkuM');

	    foreach($skus as $codprodutofoto => $codigossku) {
	        
	        if (in_array($codprodutofoto, $aImagensRemover)) {
	            continue;
	        }
	        
	        $this->FotoSkuM->limpaImagens($codprodutofoto);
	        
	        foreach($codigossku as $codsku) {
	           $itemFoto = new stdClass();
	           $itemFoto->codprodutofoto = $codprodutofoto;
	           $itemFoto->codsku = $codsku;
	           
	           $this->FotoSkuM->post($itemFoto);
	        }
	    }
	    
	    $this->session->set_flashdata('sucesso', 'Imagens modificadas com sucesso.');
	    redirect('painel/produto/fotosku/'.$codproduto);
	}
}