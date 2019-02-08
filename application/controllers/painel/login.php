<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Login extends CI_Controller {
    public function __construct() {
        parent::__construct ();
        $this->layout = '';
    }
    public function index() {
        $this->load->view ( "painel/login" );
    }
    public function verifica() {
        $this->load->model ( "Usuario_Model", "UsuarioM" );
        
        $email = $this->input->post ( "email" );
        $senha = $this->input->post ( "senha" );
        
        $this->load->library ( 'form_validation' );
        
        $this->form_validation->set_rules ( 'email', 'Email', 'required|valid_email' );
        $this->form_validation->set_rules ( 'senha', 'senha', 'required' );
        
        if ($this->form_validation->run () == FALSE) {
            $this->session->set_flashdata ( 'erro', 'Informe os campos corretamente.' . validation_errors () );
            redirect ( 'painel/login' );
        }
        
        $senha = $this->encrypt->sha1 ( $senha );
        $res = $this->UsuarioM->get ( array (
                "emailusuario" => $email,
                "senhausuario" => $senha,
                "ativadousuario" => 'S' 
        ), TRUE );
        $erro = false;
        
        if ($res) {
            $this->session->set_userdata ( "loginatendimento", $res );
            redirect ( "painel" );
        } else {
            $this->session->set_flashdata ( 'erro', 'Usuário não encontrado.' );
            redirect ( "painel/login" );
        }
    }
    public function sair() {
        $this->session->unset_userdata ( "loginatendimento" );
        redirect ( "painel/login" );
    }
}