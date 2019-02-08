<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Welcome extends CI_Controller {
    
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * http://example.com/index.php/welcome
     * - or -
     * http://example.com/index.php/welcome/index
     * - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     *
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index() {
        $this->layout = LAYOUT_LOJA;
        
        $this->load->model ( "Vitrine_Model", "VitrineM" );
        
        $dataatual = date ( "Y-m-d" );
        
        $filtro = array (
                "vitrineativa" => "S",
                "datainiciovitrine <= " => $dataatual,
                "datafinalvitrine >= " => $dataatual 
        );
        
        $vitrine = $this->VitrineM->get ( $filtro, TRUE );
        
        $produto = FALSE;
        
        if ($vitrine) {
            $this->load->model ( "VitrineProduto_Model", "VitrineProdutoM" );
            
            $filtro = array (
                    "v.codvitrine" => $vitrine->codvitrine 
            );
            
            $produto = $this->VitrineProdutoM->get ( $filtro, FALSE, 0, FALSE );
        }
        
        if (! $produto) {
            $this->load->model ( "Produto_Model", "ProdutoM" );
            
            $produto = $this->ProdutoM->get ( array (), FALSE, 0, LINHAS_PESQUISA_DASHBOARD, "codproduto", "DESC" );
        }
        
        $data = array ();
        
        $html = montaListaProduto ( $produto );
        
        $data = array ();
        $data ["LISTAGEM"] = $html;
        $data ["BLC_DEPARTAMENTOS"] = array ();
        
        $this->load->model ( "Departamento_Model", "DepartamentoM" );
        
        $departamentos = $this->DepartamentoM->getDepartamentosDisponiveis ();
        
        foreach ( $departamentos as $dep ) {
            
            $filhos = array ();
            
            $departamentosFilhos = $this->DepartamentoM->getDepartamentosDisponiveis ( $dep->codepartamento );
            
            foreach ( $departamentosFilhos as $depf ) {
                
                $filhos [] = array (
                        "URLDEPARTAMENTO_FILHO" => site_url ( "departamento/" . $depf->codepartamento ),
                        "NOMEDEPARTAMENTO_FILHO" => $depf->nomedepartamento 
                );
            }
            
            $data ["BLC_DEPARTAMENTOS"] [] = array (
                    "URLDEPARTAMENTO" => site_url ( "departamento/" . $dep->codepartamento ),
                    "NOMEDEPARTAMENTO" => $dep->nomedepartamento,
                    "BLC_DEPARTAMENTOSFILHOS" => $filhos 
            );
        }
        
        $data["BLC_ORDENACAO"] = array();
        $data["BLC_PAGINACAO"] = array();
        
        $this->parser->parse ( "inicial", $data );
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */