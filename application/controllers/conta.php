<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Conta extends CI_Controller {
    public function __construct() {
        parent::__construct ();
        $this->layout = LAYOUT_LOJA;
        $this->load->model ( 'Comprador_Model', 'CompradorM' );
    }
    public function novaconta() {
        if (clienteLogado ()) {
            redirect ( 'conta/editarconta' );
        }
        
        $this->title = "Criar uma nova conta";
        $this->showDepartamento = FALSE;
        
        $data = array ();
        $data ['ACAO'] = 'Criar uma nova conta';
        $data ['ACAOFORM'] = site_url ( 'conta/salvar' );
        
        $data ['nomecomprador'] = null;
        $data ['cpfcomprador'] = null;
        $data ['cepcomprador'] = null;
        $data ['enderecocomprador'] = null;
        $data ['cidadecomprador'] = null;
        $data ['ufcomprador'] = null;
        $data ['emailcomprador'] = null;
        $data ['telefonecomprador'] = null;
        $data ['sexocomprador'] = null;
        $data ['senhacomprador'] = null;
        
        $this->parser->parse ( 'conta_form', $data );
    }
    public function salvar() {
        $nomecomprador = $this->input->post ( 'nomecomprador' );
        $enderecocomprador = $this->input->post ( 'enderecocomprador' );
        $cidadecomprador = $this->input->post ( 'cidadecomprador' );
        $ufcomprador = $this->input->post ( 'ufcomprador' );
        $cepcomprador = $this->input->post ( 'cepcomprador' );
        $emailcomprador = $this->input->post ( 'emailcomprador' );
        $cpfcomprador = $this->input->post ( 'cpfcomprador' );
        $sexocomprador = $this->input->post ( 'sexocomprador' );
        $senhacomprador = $this->input->post ( 'senhacomprador' );
        $telefonecomprador = $this->input->post ( 'telefonecomprador' );
        
        $cpfcomprador = str_replace ( ".", null, $cpfcomprador );
        $cpfcomprador = str_replace ( "-", null, $cpfcomprador );
        $cpfcomprador = str_replace ( " ", null, $cpfcomprador );
        $cepcomprador = str_replace ( "-", null, $cepcomprador );
        
        $this->load->library ( 'form_validation' );
        
        $this->form_validation->set_rules ( 'nomecomprador', 'Nome', 'required' );
        $this->form_validation->set_rules ( 'enderecocomprador', 'Endereço', 'required' );
        $this->form_validation->set_rules ( 'cidadecomprador', 'Cidade', 'required' );
        $this->form_validation->set_rules ( 'ufcomprador', 'UF', 'required' );
        $this->form_validation->set_rules ( 'cpfcomprador', 'CPF', 'required' );
        $this->form_validation->set_rules ( 'cepcomprador', 'CEP', 'required' );
        $this->form_validation->set_rules ( 'sexocomprador', 'Sexo', 'required' );
        $this->form_validation->set_rules ( 'emailcomprador', 'Email', 'required|valid_email|is_unique[comprador.emailcomprador]' );
        
        if ($this->form_validation->run () == FALSE) {
            $this->session->set_flashdata ( 'erro', 'Informe os campos corretamente.' . validation_errors () );
            redirect ( 'conta/novaconta' );
        }
        
        $codcomprador = '';
        
        if (! validaCPF ( $cpfcomprador )) {
            $erros = TRUE;
            $this->session->set_flashdata ( 'erro', 'Informe um CPF correto.' );
            redirect ( 'conta/novaconta' );
        } else {
            $cpfUsado = $this->CompradorM->validaCPFDuplicado ( $codcomprador, $cpfcomprador );
            if ($cpfUsado > 0) {
                $this->session->set_flashdata ( 'erro', 'Já há um cliente utilizando este CPF.' );
                redirect ( 'conta/novaconta' );
            }
        }
        
        $itens = array (
                "nomecomprador" => $nomecomprador,
                "enderecocomprador" => $enderecocomprador,
                "cidadecomprador" => $cidadecomprador,
                "ufcomprador" => $ufcomprador,
                "cepcomprador" => $cepcomprador,
                "emailcomprador" => $emailcomprador,
                "cpfcomprador" => $cpfcomprador,
                "sexocomprador" => $sexocomprador 
        );
        
        if ($senhacomprador) {
            $itens ['senhacomprador'] = md5 ( $senhacomprador );
        }
        
        $codcomprador = $this->CompradorM->post ( $itens );
        
        $sessaoLoja = array (
                'nomecomprador' => $nomecomprador,
                'emailcomprador' => $emailcomprador,
                'codcomprador' => $codcomprador 
        );
        
        $this->session->set_userdata ( 'loja', $sessaoLoja );
        
        $this->session->set_flashdata ( 'sucesso', 'Conta criada com sucesso.' );
        redirect ( '/' );
    }
    public function login() {
        if (clienteLogado ()) {
            redirect ( 'conta' );
        }
        
        $data = array ();
        $data ["URLVALIDACONTA"] = site_url ( "conta/valida" );
        
        $this->parser->parse ( "login", $data );
    }
    public function valida() {
        $email = $this->input->post ( "email" );
        $senha = $this->input->post ( "senha" );
        
        $this->load->library ( 'form_validation' );
        
        $this->form_validation->set_rules ( 'email', 'Email', 'required|valid_email' );
        $this->form_validation->set_rules ( 'senha', 'Senha', 'required' );
        
        if ($this->form_validation->run () == FALSE) {
            $this->session->set_flashdata ( 'erro', 'Informe os campos corretamente.' . validation_errors () );
            redirect ( 'conta/login' );
        }
        
        $infoComprador = $this->CompradorM->get ( array (
                "emailcomprador" => $email,
                "senhacomprador" => md5 ( $senha ) 
        ), TRUE );
        
        if (! $infoComprador) {
            $this->session->set_flashdata ( 'erro', 'Email ou senha inválidos.' );
            redirect ( 'conta/login' );
        } else {
            $sessaoLoja = array (
                    'nomecomprador' => $infoComprador->nomecomprador,
                    'emailcomprador' => $infoComprador->emailcomprador,
                    'codcomprador' => $infoComprador->codcomprador 
            );
            
            $this->session->set_userdata ( 'loja', $sessaoLoja );
            
            redirect ();
        }
    }
    public function sair() {
        $this->session->unset_userdata ( 'loja' );
        redirect ();
    }
    public function pedidos() {
        clienteLogado ( true );
        
        $comprador = $this->session->userdata ( 'loja' );
        
        $pg = $this->input->get ( "pg" );
        
        if (! $pg) {
            $pg = 0;
        } else {
            $pg --;
        }
        
        $this->load->model ( "Carrinho_Model", "CarrinhoM" );
        
        $totalItens = $this->CarrinhoM->getTotal ( array (
                "codcomprador" => $comprador ["codcomprador"] 
        ) );
        
        $totalPaginas = ceil ( $totalItens / LINHAS_PESQUISA_DASHBOARD );
        
        $data = array ();
        $data ["BLC_DADOS"] = array ();
        $data ["BLC_PAGINACAO"] = array ();
        
        $data ["BLC_PAGINACAO"] = array ();
        
        $paginas = array ();
        
        for($i = 1; $i <= $totalPaginas; $i ++) {
            $paginas [] = array (
                    "URLPAGINA" => current_url () . "?&pg={$i}",
                    "INDICE" => $i 
            );
        }
        
        $data ["BLC_PAGINACAO"] [] = array (
                "BLC_PAGINA" => $paginas 
        );
        
        $carrinho = $this->CarrinhoM->get ( array (
                "c.codcomprador" => $comprador ["codcomprador"] 
        ), FALSE, $pg );
        
        if ($carrinho) {
            foreach ( $carrinho as $car ) {
                $data ["BLC_DADOS"] [] = array (
                        "URLRESUMO" => site_url ( 'conta/resumo/' . $car->codcarrinho ),
                        "CODPEDIDO" => $car->codcarrinho,
                        "DATA" => $car->data,
                        "VALOR" => number_format ( $car->valorcompra, 2, ",", "." ) 
                );
            }
        }
        
        $this->parser->parse ( "lista_carrinho", $data );
    }
    public function resumo($codcarrinho) {
        clienteLogado ( true );
        
        $comprador = $this->session->userdata ( 'loja' );
        
        $this->load->model ( "Carrinho_Model", "CarrinhoM" );
        $this->load->model ( "ItemCarrinho_Model", "ItemCarrinhoM" );
        
        $carrinho = $this->CarrinhoM->get ( array (
                "c.codcarrinho" => $codcarrinho,
                "c.codcomprador" => $comprador ["codcomprador"] 
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
        
        $this->parser->parse ( "resumo", $data );
    }
    public function boleto($codcarrinho, $tipo) {
        clienteLogado ( true );
        
        $comprador = $this->session->userdata ( 'loja' );
        
        $this->load->model ( "Carrinho_Model", "CarrinhoM" );
        
        $carrinho = $this->CarrinhoM->get ( array (
                "c.codcarrinho" => $codcarrinho,
                "c.codcomprador" => $comprador ["codcomprador"] 
        ), TRUE );
        
        if (! $carrinho) {
            $this->session->set_flashdata ( 'erro', 'Carrinho não existente.' );
            redirect ( 'conta' );
        }
        
        $this->layout = '';
        
        switch ($tipo) {
            case "bb" :
                $arquivoBoleto = "boleto_bb";
                break;
        }
        
        $boleto = new stdClass ();
        $boleto->codcarrinho = $carrinho->codcarrinho;
        $boleto->valor = $carrinho->valorfinalcompra;
        $boleto->nomecomprador = $carrinho->nomecomprador;
        $boleto->endereco = $carrinho->enderecocomprador;
        $boleto->endereco2 = $carrinho->cidadecomprador . "/" . $carrinho->ufcomprador . " - " . $carrinho->cepcomprador;
        
        require_once FCPATH . "/application/libraries/boleto/" . $arquivoBoleto . ".php";
    }
}