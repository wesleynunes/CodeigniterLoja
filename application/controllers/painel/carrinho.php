<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Carrinho extends CI_Controller {
    public function __construct() {
        parent::__construct ();
        $this->layout = LAYOUT_DASHBOARD;
        $this->load->model ( 'Comprador_Model', 'CompradorM' );
        $this->load->model ( 'Carrinho_Model', 'CarrinhoM' );
    }
    public function index() {
        $data = array ();
        $data ['URLADICIONAR'] = site_url ( 'painel/carrinho/adicionar' );
        $data ['URLLISTAR'] = site_url ( 'painel/carrinho' );
        $data ['BLC_DADOS'] = array ();
        $data ['BLC_SEMDADOS'] = array ();
        $data ['BLC_PAGINAS'] = array ();
        
        $pagina = $this->input->get ( 'pagina' );
        
        if (! $pagina) {
            $pagina = 0;
        } else {
            $pagina = ($pagina - 1) * LINHAS_PESQUISA_DASHBOARD;
        }
        
        $res = $this->CarrinhoM->get ( array (), FALSE, $pagina );
        
        if ($res) {
            foreach ( $res as $r ) {
                $data ["BLC_DADOS"] [] = array (
                        "URLRESUMO" => site_url ( 'painel/carrinho/resumo/' . $r->codcarrinho ),
                        "CODPEDIDO" => $r->codcarrinho,
                        "DATA" => $r->data,
                        "VALOR" => number_format ( $r->valorcompra, 2, ",", "." ) 
                );
            }
        } else {
            $data ['BLC_SEMDADOS'] [] = array ();
        }
        
        $totalItens = $this->CarrinhoM->getTotal ();
        $totalPaginas = ceil ( $totalItens / LINHAS_PESQUISA_DASHBOARD );
        
        $indicePg = 1;
        $pagina = $this->input->get ( 'pagina' );
        if (! $pagina) {
            $pagina = 1;
        }
        $pagina = ($pagina == 0) ? 1 : $pagina;
        
        if ($totalPaginas > $pagina) {
            $data ['HABPROX'] = null;
            $data ['URLPROXIMO'] = site_url ( 'painel/carrinho?pagina=' . ($pagina + 1) );
        } else {
            $data ['HABPROX'] = 'disabled';
            $data ['URLPROXIMO'] = '#';
        }
        
        if ($pagina <= 1) {
            $data ['HABANTERIOR'] = 'disabled';
            $data ['URLANTERIOR'] = '#';
        } else {
            $data ['HABANTERIOR'] = null;
            $data ['URLANTERIOR'] = site_url ( 'painel/carrinho?pagina=' . ($pagina - 1) );
        }
        
        while ( $indicePg <= $totalPaginas ) {
            $data ['BLC_PAGINAS'] [] = array (
                    "LINK" => ($indicePg == $pagina) ? 'active' : null,
                    "INDICE" => $indicePg,
                    "URLLINK" => site_url ( 'painel/carrinho?pagina=' . $indicePg ) 
            );
            
            $indicePg ++;
        }
        
        $this->parser->parse ( 'painel/carrinho_listar', $data );
    }
    private function setURL(&$data) {
        $data ['URLLISTAR'] = site_url ( 'painel/carrinho' );
        $data ['ACAOFORM'] = site_url ( 'painel/carrinho/salvar' );
    }
    public function adicionar() {
        $data = array ();
        $data ['ACAO'] = 'Novo';
        $data ['codcomprador'] = '';
        $data ['nomecomprador'] = '';
        $data ['cpfcomprador'] = '';
        $data ['cepcomprador'] = '';
        $data ['enderecocomprador'] = '';
        $data ['cidadecomprador'] = '';
        $data ['ufcomprador'] = '';
        $data ['emailcomprador'] = '';
        $data ['telefonecomprador'] = '';
        $data ['sexocomprador'] = '';
        $data ['senhacomprador'] = '';
        
        $this->setURL ( $data );
        
        $this->parser->parse ( 'painel/carrinho_form', $data );
    }
    public function resumo($codcarrinho) {
        $this->load->model ( "ItemCarrinho_Model", "ItemCarrinhoM" );
        
        $carrinho = $this->CarrinhoM->get ( array (
                "c.codcarrinho" => $codcarrinho 
        ), TRUE );
        
        if (! $carrinho) {
            $this->session->set_flashdata ( 'erro', 'Carrinho não existente.' );
            redirect ( 'conta' );
        }
        
        $data = array ();
        
        $data ["BLC_SHOWBOLETO"] = array ();
        
        $formasBoleto = array (
                "3" 
        );
        
        $data ["CODCARRINHO"] = $codcarrinho;
        
        if ((in_array ( $carrinho->codformapagamento, $formasBoleto )) && ($carrinho->situacao == CAR_ABERTO)) {
            
            switch ($carrinho->codformapagamento) {
                case "3" :
                    $tipoBoleto = "bb";
                    break;
            }
            
            $data ["BLC_SHOWBOLETO"] [] = array (
                    "URLBOLETO" => site_url ( 'conta/boleto/' . $codcarrinho . '/' . $tipoBoleto ) 
            );
        }
        
        $data ["DATA"] = $carrinho->data;
        $data ["NOMEFORMAPAGAMENTO"] = $carrinho->nomeformapagamento;
        $data ["NOMEFORMAENTREGA"] = $carrinho->nomeformaentrega;
        $data ["VALORFINALCOMPRA"] = number_format ( $carrinho->valorfinalcompra, 2, ",", "." );
        
        $data ["NOMECOMPRADOR"] = $carrinho->nomecomprador;
        $data ["ENDERECOCOMPRADOR"] = $carrinho->enderecocomprador;
        $data ["CIDADECOMPRADOR"] = $carrinho->cidadecomprador;
        $data ["UFCOMPRADOR"] = $carrinho->ufcomprador;
        $data ["CEPCOMPRADOR"] = $carrinho->cepcomprador;
        $data ["URLSITUACAO"] = site_url ( 'painel/carrinho/atualizapedido' );
        $data ["SITUACAO_A"] = '';
        $data ["SITUACAO_E"] = '';
        $data ["SITUACAO_T"] = '';
        $data ["SITUACAO_C"] = '';
        $data ["SITUACAO_" . $carrinho->situacao] = 'selected="selected"';
        
        $itens = $this->ItemCarrinhoM->get ( array (
                "ic.codcarrinho" => $codcarrinho 
        ) );
        
        $data ["BLC_DADOS"] = array ();
        
        if ($itens) {
            foreach ( $itens as $i ) {
                $data ["BLC_DADOS"] [] = array (
                        "NOMEPRODUTO" => $i->nomeproduto,
                        "QTD" => $i->quantidadeitem,
                        "VLRUN" => number_format ( $i->valoritem, 2, ",", "." ),
                        "VLRTOTAL" => number_format ( $i->valorfinal, 2, ",", "." ) 
                );
            }
        }
        
        $this->parser->parse ( "painel/resumo", $data );
    }
    public function atualizapedido() {
        $codcarrinho = $this->input->post ( "codcarrinho" );
        $situacao = $this->input->post ( "situacao" );
        
        $itens = array ();
        $itens ["situacao"] = $situacao;
        $this->CarrinhoM->update ( $itens, $codcarrinho );
        $this->session->set_flashdata ( 'sucesso', 'Carrinho atualizado.' );
        
        $descricao = "";
        
        switch ($situacao) {
            case "A" :
                $descricao = "Aguardando pagamento";
                break;
            case "E" :
                $descricao = "Entregue";
                break;
            case "T" :
                $descricao = "Enviado";
                break;
            case "C" :
                $descricao = "Cancelado";
                break;
        }
        
        $carrinho = $this->CarrinhoM->get ( array (
                "c.codcarrinho" => $codcarrinho 
        ), TRUE );
        
        $comprador = $this->CompradorM->get ( array (
                "codcomprador" => $carrinho->codcomprador 
        ), TRUE );
        
        $this->load->library("email");
        $this->email->from("minhaloja@teste.com", "Loja XYZ");
        $this->email->to($comprador->emailcomprador);
        $this->email->subject("Seu Pedido {$codcarrinho} foi {$descricao}.");
        $this->email->message("O Pedido {$codcarrinho} foi {$descricao}.");
        $this->email->send();
        
        redirect ( 'painel/carrinho/resumo/' . $codcarrinho );
    }
}