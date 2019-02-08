<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Checkout extends CI_Controller {
    public function __construct() {
        parent::__construct ();
        $this->layout = LAYOUT_LOJA;
        $this->load->model ( "Sku_Model", "SkuM" );
    }
    private function getPesoCarrinho() {
        $carrinho = $this->session->userdata ( "carrinho" );
        
        $peso = 0;
        
        if ($carrinho) {
            $carrinho = unserialize ( $carrinho );
            
            if (sizeof ( $carrinho ) > 0) {
                foreach ( $carrinho as $codprodutosku => $quantidade ) {
                    $infoSKU = $this->SkuM->getPorSKU ( $codprodutosku );
                    
                    if ($quantidade <= 0) {
                        continue;
                    }
                    
                    if ($quantidade > $infoSKU->quantidade) {
                        continue;
                    }
                    
                    $pesoCubico = ($infoSKU->altura * $infoSKU->largura * $infoSKU->comprimento) / 6000;
                    
                    if ($pesoCubico > $infoSKU->peso) {
                        $peso += $pesoCubico;
                    } else {
                        $peso += $infoSKU->peso;
                    }
                }
            }
        }
        
        return $peso;
    }
    public function index() {
        $carrinho = $this->session->userdata ( "carrinho" );
        
        if (! $carrinho) {
            $carrinho = array ();
        } else {
            $carrinho = unserialize ( $carrinho );
        }
        
        $data = array ();
        $data ["BLC_PRODUTOS"] = array ();
        $data ["BLC_FINALIZAR"] = array ();
        $data ["BLC_SEMPRODUTOS"] = array ();
        
        if (sizeof ( $carrinho ) === 0) {
            $data ["BLC_SEMPRODUTOS"] [] = array ();
        } else {
            foreach ( $carrinho as $codsku => $quantidade ) {
                $infoSKU = $this->SkuM->getPorSKU ( $codsku );
                
                if (($infoSKU->valorpromocional > 0) && ($infoSKU->valorproduto > $infoSKU->valorpromocional)) {
                    $valorFinal = $infoSKU->valorpromocional;
                } else {
                    $valorFinal = $infoSKU->valorproduto;
                }
                $valorTotal = $valorFinal * $quantidade;
                
                $referencia = "";
                
                if (! empty ( $infoSKU->referencia )) {
                    $referencia = " - " . $infoSKU->referencia;
                }
                
                $urlFoto = "";
                
                if (! empty ( $infoSKU->codprodutofotosku )) {
                    $urlFoto = base_url ( "assets/img/produto/80x80/" . $infoSKU->codprodutofotosku . "." . $infoSKU->produtofotoextensaosku );
                } else {
                    $urlFoto = base_url ( "assets/img/produto/80x80/" . $infoSKU->codprodutofoto . "." . $infoSKU->produtofotoextensao );
                }
                
                $data ["BLC_PRODUTOS"] [] = array (
                        "URLFOTO" => $urlFoto,
                        "NOMEPRODUTO" => $infoSKU->nomeproduto . $referencia,
                        "QUANTIDADE" => $quantidade,
                        "VALOR" => number_format ( $valorFinal, 2, ",", "." ),
                        "VALORTOTAL" => number_format ( $valorTotal, 2, ",", "." ),
                        "URLAUMENTAQTD" => site_url ( "checkout/aumenta/" . $infoSKU->codsku ),
                        "URLDIMINUIQTD" => site_url ( "checkout/diminui/" . $infoSKU->codsku ),
                        "URLREMOVEQTD" => site_url ( "checkout/remove/" . $infoSKU->codsku ) 
                );
            }
            
            $data ["BLC_FINALIZAR"] [] = array (
                    "URLFINALIZAR" => site_url ( 'checkout/formaentrega' ) 
            );
        }
        
        $this->parser->parse ( "carrinho", $data );
    }
    public function formaentrega() {
        clienteLogado ( true );
        
        $carrinho = $this->session->userdata ( "carrinho" );
        $enderecoentrega = $this->session->userdata ( "enderecoentrega" );
        $comprador = $this->session->userdata ( 'loja' );
        
        if (! $carrinho) {
            $carrinho = array ();
        } else {
            $carrinho = unserialize ( $carrinho );
        }
        
        if (sizeof ( $carrinho ) === 0) {
            redirect ();
        }
        
        if ($enderecoentrega) {
            $enderecoentrega = unserialize ( $enderecoentrega );
        }
        
        if ((! $enderecoentrega) || (sizeof ( $enderecoentrega ) === 0)) {
            $this->load->model ( 'Comprador_Model', 'CompradorM' );
            
            $infoComprador = $this->CompradorM->get ( array (
                    "codcomprador" => $comprador ["codcomprador"] 
            ), TRUE );
            
            $infoEntrega = array ();
            $infoEntrega ['enderecocomprador'] = $infoComprador->enderecocomprador;
            $infoEntrega ['cidadecomprador'] = $infoComprador->cidadecomprador;
            $infoEntrega ['ufcomprador'] = $infoComprador->ufcomprador;
            $infoEntrega ['cepcomprador'] = $infoComprador->cepcomprador;
            
            $infoEntregaSessao = serialize ( $infoEntrega );
            
            $this->session->set_userdata ( 'enderecoentrega', $infoEntregaSessao );
        } else {
            $infoEntrega = $enderecoentrega;
        }
        
        $data = array ();
        
        $data ["ENDERECO"] = $infoEntrega ['enderecocomprador'];
        $data ["CIDADE"] = $infoEntrega ['cidadecomprador'];
        $data ["UF"] = $infoEntrega ['ufcomprador'];
        $data ["CEP"] = $infoEntrega ['cepcomprador'];
        
        $data ["BLC_FORMAENTREGA"] = array ();
        
        $this->load->model ( "PrecoEntrega_Model", "PrecoEntregaM" );
        
        $formasEntrega = $this->PrecoEntregaM->getPrecoEntrega ( $infoEntrega ['cepcomprador'], $this->getPesoCarrinho () );
        
        $checked = false;
        
        if ($formasEntrega) {
            foreach ( $formasEntrega as $fe ) {
                $data ["BLC_FORMAENTREGA"] [] = array (
                        "CODFORMAENTREGA" => $fe->codformaentrega,
                        "NOMEFORMAENTREGA" => $fe->nomeformaentrega,
                        "DIASENTREGA" => $fe->prazofaixaprecoformaentrega,
                        "CHECKED_FE" => (! $checked) ? "checked" : null,
                        "VALOR" => number_format ( $fe->valorfaixaprecoformaentrega, 2, ",", "." ) 
                );
                
                if ((! $checked)) {
                    $checked = true;
                }
            }
        }
        
        $this->load->model ( 'FormaEntrega_Model', 'FormaEntregaM' );
        
        $formasDisponiveis = $this->FormaEntregaM->get ( array (
                "habilitaformaentrega" => 'S',
                "codigocorreiosformaentrega !=" => '' 
        ) );
        
        if ($formasDisponiveis) {
            foreach ( $formasDisponiveis as $fd ) {
                $xml = null;
                
                $pesoCarrinhoOriginal = $this->getPesoCarrinho ();
                
                $totalPacotes = 1;
                
                if ($pesoCarrinhoOriginal > 30) {
                    $totalPacotes = $pesoCarrinhoOriginal / 30;
                    $totalPacotes = ceil ( $totalPacotes );
                    $pesoCarrinho = 30;
                } else {
                    $pesoCarrinho = $pesoCarrinhoOriginal;
                }
                
                $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
                // $url .= "nCdEmpresa=";
                // $url .= "&nDsSenha=";
                $url .= "&sCepOrigem=9901030";
                $url .= "&sCepDestino=" . $infoEntrega ['cepcomprador'];
                $url .= "&nVlPeso=" . $pesoCarrinho;
                $url .= "&nCdServico=" . $fd->codigocorreiosformaentrega;
                $url .= "&nCdFormato=1&nVlComprimento=25&nVlAltura=2&nVlLargura=11";
                $url .= "&sCdMaoPropria=n&nVlValorDeclarado=0&sCdAvisoRecebimento=n";
                $url .= "&nVlDiametro=0&StrRetorno=xml";
                
                $ch = curl_init ();
                curl_setopt ( $ch, CURLOPT_URL, $url );
                curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 3 );
                curl_setopt ( $ch, CURLOPT_USERAGENT, 'Checkout' );
                $query = curl_exec ( $ch );
                curl_close ( $ch );
                
                if ($query) {
                    
                    $xml = new SimpleXMLElement ( $query );
                    
                    foreach ( $xml as $cor ) {
                        
                        if ($cor->Erro == '0') {
                            
                            $data ["BLC_FORMAENTREGA"] [] = array (
                                    "CODFORMAENTREGA" => $fd->codformaentrega,
                                    "NOMEFORMAENTREGA" => $fd->nomeformaentrega,
                                    "DIASENTREGA" => $cor->PrazoEntrega,
                                    "CHECKED_FE" => (! $checked) ? "checked" : null,
                                    "VALOR" => number_format ( $totalPacotes * ( float ) $cor->Valor, 2, ',', '.' ) 
                            );
                            
                            if ((! $checked)) {
                                $checked = true;
                            }
                        }
                    }
                }
            }
        }
        
        $data ["BLC_PERMITECOMPRAR"] = array ();
        if ($checked) {
            $data ["URLPAGAMENTO"] = site_url ( 'checkout/pagamento' );
            $data ["BLC_PERMITECOMPRAR"] [] = array ();
        }
        
        $data ["URLALTERAENTREGA"] = site_url ( 'checkout/alteraendereco' );
        
        $this->parser->parse ( "formaentrega", $data );
    }
    public function alteraendereco() {
        $enderecocomprador = $this->input->post ( 'enderecocomprador' );
        $cidadecomprador = $this->input->post ( 'cidadecomprador' );
        $ufcomprador = $this->input->post ( 'ufcomprador' );
        $cepcomprador = $this->input->post ( 'cepcomprador' );
        
        $this->load->library ( 'form_validation' );
        
        $this->form_validation->set_rules ( 'enderecocomprador', 'Endereço', 'required' );
        $this->form_validation->set_rules ( 'cidadecomprador', 'Cidade', 'required' );
        $this->form_validation->set_rules ( 'ufcomprador', 'UF', 'required' );
        $this->form_validation->set_rules ( 'cepcomprador', 'CEP', 'required' );
        
        if ($this->form_validation->run () == FALSE) {
            $this->session->set_flashdata ( 'erro', 'Informe os campos corretamente.' . validation_errors () );
        } else {
            $infoEntrega = array ();
            $infoEntrega ['enderecocomprador'] = $enderecocomprador;
            $infoEntrega ['cidadecomprador'] = $cidadecomprador;
            $infoEntrega ['ufcomprador'] = $ufcomprador;
            $infoEntrega ['cepcomprador'] = $cepcomprador;
            
            $infoEntrega = serialize ( $infoEntrega );
            
            $this->session->set_userdata ( 'enderecoentrega', $infoEntrega );
        }
        
        redirect ( 'checkout/formaentrega' );
    }
    
    /**
     * Adiciona um produto ao carrinho
     */
    public function adicionar() {
        $codproduto = $this->input->post ( "codproduto" );
        $codsku = $this->input->post ( "codsku" );
        
        $carrinho = $this->session->userdata ( "carrinho" );
        
        if (! $carrinho) {
            $carrinho = array ();
        } else {
            $carrinho = unserialize ( $carrinho );
        }
        
        $infoSKU = $this->SkuM->getPorSKU ( $codsku );
        
        if ($infoSKU) {
            
            if (! isset ( $carrinho [$codsku] )) {
                if ($infoSKU->quantidade > 0) {
                    $carrinho [$codsku] = 1;
                }
            } else {
                
                if ($infoSKU->quantidade > $carrinho [$codsku] + 1) {
                    $carrinho [$codsku] = $carrinho [$codsku] + 1;
                }
            }
        }
        
        $carrinho = serialize ( $carrinho );
        $this->session->set_userdata ( "carrinho", $carrinho );
        
        redirect ( "checkout" );
    }
    public function aumenta($codsku) {
        $carrinho = $this->session->userdata ( "carrinho" );
        
        if (! $carrinho) {
            $carrinho = array ();
        } else {
            $carrinho = unserialize ( $carrinho );
        }
        
        $infoSKU = $this->SkuM->getPorSKU ( $codsku );
        
        if ($infoSKU) {
            
            if (! isset ( $carrinho [$codsku] )) {
                if ($infoSKU->quantidade > 0) {
                    $carrinho [$codsku] = 1;
                }
            } else {
                
                if ($infoSKU->quantidade > $carrinho [$codsku] + 1) {
                    $carrinho [$codsku] = $carrinho [$codsku] + 1;
                }
            }
        }
        
        $carrinho = serialize ( $carrinho );
        $this->session->set_userdata ( "carrinho", $carrinho );
        
        redirect ( "checkout" );
    }
    public function diminui($codsku) {
        $carrinho = $this->session->userdata ( "carrinho" );
        
        if (! $carrinho) {
            $carrinho = array ();
        } else {
            $carrinho = unserialize ( $carrinho );
        }
        
        $infoSKU = $this->SkuM->getPorSKU ( $codsku );
        
        if ($infoSKU) {
            
            if (! isset ( $carrinho [$codsku] )) {
                if ($infoSKU->quantidade > 0) {
                    $carrinho [$codsku] = 1;
                }
            } else {
                if (($carrinho [$codsku] - 1) <= 0) {
                    $carrinho [$codsku] = 1;
                } elseif ($infoSKU->quantidade > $carrinho [$codsku] - 1) {
                    $carrinho [$codsku] = $carrinho [$codsku] - 1;
                }
            }
        }
        
        $carrinho = serialize ( $carrinho );
        $this->session->set_userdata ( "carrinho", $carrinho );
        
        redirect ( "checkout" );
    }
    public function remove($codsku) {
        $carrinho = $this->session->userdata ( "carrinho" );
        
        if (! $carrinho) {
            $carrinho = array ();
        } else {
            $carrinho = unserialize ( $carrinho );
        }
        
        unset ( $carrinho [$codsku] );
        
        $carrinho = serialize ( $carrinho );
        $this->session->set_userdata ( "carrinho", $carrinho );
        
        redirect ( "checkout" );
    }
    public function pagamento() {
        clienteLogado ( true );
        $codformaentrega = $this->input->post ( "codformaentrega" );
        
        if ($codformaentrega) {
            $this->session->set_userdata ( "codformaentrega", $codformaentrega );
        } else {
            $codformaentrega = $this->session->userdata ( "codformaentrega" );
            
            if (! $codformaentrega) {
                redirect ( "checkout/formaentrega" );
            }
        }
        
        $data = array ();
        $data ["SUBTOTAL"] = null;
        $data ["FRETE"] = null;
        $data ["TOTAL"] = null;
        $data ["URLPAGAMENTO"] = site_url ( "checkout/finalizar" );
        
        $data ["BLC_FORMAPAGAMENTO"] = array ();
        
        $this->load->model ( "FormaPagamento_Model", "FormaPagamentoM" );
        
        $formasPagamento = $this->FormaPagamentoM->get ( array (
                "habilitaformapagamento" => 'S' 
        ) );
        
        $checked = false;
        
        $valorTotal = 0;
        
        $enderecoentrega = $this->session->userdata ( "enderecoentrega" );
        $enderecoentrega = unserialize ( $enderecoentrega );
        
        $valorFrete = $this->getPrecoEntrega ( $codformaentrega, $enderecoentrega ['cepcomprador'] );
        $valorSubTotal = $this->getPrecoCarrinho ();
        
        $valorTotal += $valorSubTotal + $valorFrete;
        
        $data ["FRETE"] = number_format ( $valorFrete, 2, ",", "." );
        $data ["SUBTOTAL"] = number_format ( $valorSubTotal, 2, ",", "." );
        $data ["TOTAL"] = number_format ( $valorTotal, 2, ",", "." );
        
        $data ["BLC_PARCELACARTAO"] = array ();
        
        for($i = 1; $i <= 6; $i ++) {
            if ((($valorSubTotal / $i) < 10) && ($i > 1)) {
                continue;
            }
            $data ["BLC_PARCELACARTAO"] [] = array (
                    "NUMEROPARCELA" => $i,
                    "VALORPARCELA" => number_format ( $valorSubTotal / $i, 2, ",", "." ) 
            );
        }
        
        if ($formasPagamento) {
            foreach ( $formasPagamento as $fp ) {
                
                $valorComDesconto = $valorSubTotal;
                
                if ($fp->descontoformapagamento > 0) {
                    $valorComDesconto = $valorComDesconto - (($valorSubTotal * $fp->descontoformapagamento) / 100);
                }
                
                $valorComDesconto += $valorFrete;
                
                $data ["BLC_FORMAPAGAMENTO"] [] = array (
                        "CODFORMAPAGAMENTO" => $fp->codformapagamento,
                        "NOMEFORMAPAGAMENTO" => $fp->nomeformapagamento,
                        "CHECKED_FE" => (! $checked) ? "checked" : null,
                        "TIPO" => $fp->tipoformapagamento,
                        "VALOR" => number_format ( $valorComDesconto, 2, ",", "." ) 
                );
                
                if ((! $checked)) {
                    $checked = true;
                }
            }
        }
        
        $data ["BLC_PERMITECOMPRAR"] = array ();
        
        if ($checked) {
            $data ["BLC_PERMITECOMPRAR"] [] = array ();
        }
        
        $this->parser->parse ( "formapagamento", $data );
    }
    private function getPrecoCarrinho($codformapagamento = false) {
        $carrinho = $this->session->userdata ( "carrinho" );
        
        if (! $carrinho) {
            $carrinho = array ();
        } else {
            $carrinho = unserialize ( $carrinho );
        }
        
        $valorTotal = 0;
        
        foreach ( $carrinho as $codsku => $quantidade ) {
            $infoSKU = $this->SkuM->getPorSKU ( $codsku );
            
            if (($infoSKU->valorpromocional > 0) && ($infoSKU->valorproduto > $infoSKU->valorpromocional)) {
                $valorFinal = $infoSKU->valorpromocional;
            } else {
                $valorFinal = $infoSKU->valorproduto;
            }
            $valorTotal += $valorFinal * $quantidade;
        }
        
        if ($codformapagamento) {
            $this->load->model ( "FormaPagamento_Model", "FormaPagamentoM" );
            
            $formasPagamento = $this->FormaPagamentoM->get ( array (
                    "codformapagamento" => $codformapagamento 
            ), TRUE );
            
            if ($formasPagamento->descontoformapagamento > 0) {
                $valorTotal = $valorTotal - (($valorTotal * $formasPagamento->descontoformapagamento) / 100);
            }
        }
        
        return $valorTotal;
    }
    private function getPrecoEntrega($codformaentrega, $cep) {
        $pesoCarrinho = $this->getPesoCarrinho ();
        
        $this->load->model ( "PrecoEntrega_Model", "PrecoEntregaM" );
        $formasEntrega = $this->PrecoEntregaM->getPrecoEntrega ( $cep, $pesoCarrinho, $codformaentrega );
        
        if ($formasEntrega) {
            return $formasEntrega->valorfaixaprecoformaentrega;
        } else {
            $this->load->model ( 'FormaEntrega_Model', 'FormaEntregaM' );
            
            $formasEntrega = $this->FormaEntregaM->get ( array (
                    "codformaentrega" => $codformaentrega 
            ), TRUE );
            
            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
            // $url .= "nCdEmpresa=";
            // $url .= "&nDsSenha=";
            $url .= "&sCepOrigem=9901030";
            $url .= "&sCepDestino=" . $cep;
            $url .= "&nVlPeso=" . $pesoCarrinho;
            $url .= "&nCdServico=" . $formasEntrega->codigocorreiosformaentrega;
            $url .= "&nCdFormato=1&nVlComprimento=25&nVlAltura=2&nVlLargura=11";
            $url .= "&sCdMaoPropria=n&nVlValorDeclarado=0&sCdAvisoRecebimento=n";
            $url .= "&nVlDiametro=0&StrRetorno=xml";
            
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 3 );
            curl_setopt ( $ch, CURLOPT_USERAGENT, 'Checkout' );
            $query = curl_exec ( $ch );
            curl_close ( $ch );
            
            if ($query) {
                
                $xml = new SimpleXMLElement ( $query );
                
                foreach ( $xml as $cor ) {
                    
                    if ($cor->Erro == '0') {
                        return ( float ) $cor->Valor;
                    }
                }
            }
        }
    }
    public function finalizar() {
        clienteLogado ( true );
        
        $codformapagamento = $this->input->post ( "codformapagamento" );
        $codformaentrega = $this->session->userdata ( "codformaentrega" );
        
        $this->load->model ( "FormaPagamento_Model", "FormaPagamentoM" );
        
        $formasPagamento = $this->FormaPagamentoM->get ( array (
                "codformapagamento" => $codformapagamento 
        ), TRUE );
        
        $numerocartao = $this->input->post ( "numerocartao" );
        $codigoverificador = $this->input->post ( "codigoverificador" );
        $mescartao = $this->input->post ( "mescartao" );
        $anocartao = $this->input->post ( "anocartao" );
        $parcela = $this->input->post ( "parcela" );
        
        if ($formasPagamento->tipoformapagamento == 2) {
            
            $this->load->library ( 'form_validation' );
            
            $this->form_validation->set_rules ( 'numerocartao', 'Número do Cartão', 'required' );
            $this->form_validation->set_rules ( 'codigoverificador', 'Código Verificador', 'required' );
            $this->form_validation->set_rules ( 'mescartao', 'Mês do Cartão', 'required' );
            $this->form_validation->set_rules ( 'anocartao', 'Ano do Cartão', 'required' );
            $this->form_validation->set_rules ( 'parcela', 'Parcela', 'required' );
            
            if ($this->form_validation->run () == FALSE) {
                $this->session->set_flashdata ( 'erro', 'Informe os campos corretamente.' . validation_errors () );
                redirect ( "checkout/pagamento" );
            }
        }
        
        if (! $codformaentrega) {
            redirect ( "checkout/pagamento" );
        }
        
        $carrinho = $this->session->userdata ( "carrinho" );
        $enderecoentrega = $this->session->userdata ( "enderecoentrega" );
        $comprador = $this->session->userdata ( 'loja' );
        
        if ((! $carrinho) || (sizeof ( $carrinho ) === 0)) {
            redirect ();
        }
        
        if ((! $enderecoentrega) || (sizeof ( $enderecoentrega ) === 0)) {
            redirect ();
        }
        
        $carrinho = unserialize ( $carrinho );
        $enderecoentrega = unserialize ( $enderecoentrega );
        
        $valorFrete = $this->getPrecoEntrega ( $codformaentrega, $enderecoentrega ['cepcomprador'] );
        $valorSubTotal = $this->getPrecoCarrinho ( $codformapagamento );
        
        $valorTotal = $valorFrete + $valorSubTotal;
        
        $this->load->model ( "Carrinho_Model", "CarrinhoM" );
        $this->load->model ( "ItemCarrinho_Model", "ItemCarrinhoM" );
        
        $objeto = array (
                "datahoracompra" => date ( "Y-m-d H:i:s" ),
                "valorcompra" => $valorSubTotal,
                "valorfrete" => $valorFrete,
                "valorfinalcompra" => $valorTotal,
                "codcomprador" => $comprador ["codcomprador"],
                "codformaentrega" => $codformaentrega,
                "codformapagamento" => $codformapagamento,
                "enderecoentrega" => $enderecoentrega ['enderecocomprador'],
                "cidadeentrega" => $enderecoentrega ['cidadecomprador'],
                "ufentrega" => $enderecoentrega ['ufcomprador'],
                "cepentrega" => $enderecoentrega ['cepcomprador'] 
        );
        
        $codCarrinho = $this->CarrinhoM->post ( $objeto );
        
        foreach ( $carrinho as $codsku => $quantidade ) {
            
            $infoSKU = $this->SkuM->getPorSKU ( $codsku );
            
            if (($infoSKU->valorpromocional > 0) && ($infoSKU->valorproduto > $infoSKU->valorpromocional)) {
                $valorFinal = $infoSKU->valorpromocional;
            } else {
                $valorFinal = $infoSKU->valorproduto;
            }
            
            if ($formasPagamento->descontoformapagamento > 0) {
                $valorFinal = $valorFinal - (($valorFinal * $formasPagamento->descontoformapagamento) / 100);
            }
            
            $objetoItem = array (
                    "valoritem" => $valorFinal,
                    "quantidadeitem" => $quantidade,
                    "codcarrinho" => $codCarrinho,
                    "codsku" => $codsku 
            );
            
            $this->ItemCarrinhoM->post ( $objetoItem );
        }
        
        if ($formasPagamento->tipoformapagamento == 2) {
            
            $this->load->library ( 'WebServiceCielo' );
            
            $dataHoraPedido = date('c');
            $dataHoraPedido = substr($dataHoraPedido, 0, 19);
            
            switch($codformapagamento) {
            	case 1:
            	    $bandeira = 'mastercard';
            	    break;
        	    case 2:
        	        $bandeira = 'visa';
        	        break;
            }
            
            if ($parcela == 1) {
                $produto = 1;
            } else {
                $produto = 2;
            }
            
            $cartao = new stdClass();
            $pedido = new stdClass();
            
            $cartao->numero = $numerocartao;
            $cartao->ano = $anocartao;
            $cartao->mes = $mescartao;
            $cartao->codigoseguranca = $codigoverificador;
            
            $pedido->codcarrinho = $codCarrinho;
            $pedido->valor = $valorTotal * 100;
            $pedido->datahora = $dataHoraPedido;
            $pedido->bandeira = $bandeira;
            $pedido->produto = $produto;
            $pedido->parcelas = $parcela;
            
            $autorizacao = $this->webservicecielo->enviaAutorizacao($cartao, $pedido);
            
            if (!$autorizacao->error) {
                redirect("conta/resumo/".$codCarrinho);
            } else {
                $this->session->set_flashdata ( 'erro', "Não foi possível processar o seu pagamento." );
                redirect ( "checkout/pagamento" );
            }
        }
    }
}