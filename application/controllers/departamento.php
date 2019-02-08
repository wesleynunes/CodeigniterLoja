<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Departamento extends CI_Controller {
    public function __construct() {
        parent::__construct ();
        $this->layout = LAYOUT_LOJA;
    }
    public function listagem($coddepartamento) {
        $pg = $this->input->get ( "pg" );
        $to = $tipoOrdenacao = $this->input->get ( "to" );
        $oc = $campoOrdenacao = $this->input->get ( "oc" );
        
        if (! $pg) {
            $pg = 0;
        } else {
            $pg --;
        }
        
        $offset = LINHAS_PESQUISA_DASHBOARD * $pg;
        
        switch ($tipoOrdenacao) {
            case "asc" :
            case "desc" :
                $tipoOrdenacao = strtoupper ( $tipoOrdenacao );
                break;
            default :
                $tipoOrdenacao = "ASC";
        }
        
        switch ($campoOrdenacao) {
            case "nome" :
                $campoOrdenacao = "p.nomeproduto";
                break;
            case "valor" :
                $campoOrdenacao = "p.valorproduto";
                break;
            default :
                $campoOrdenacao = "p.nomeproduto";
        }
        
        $this->load->model ( "Produto_Model", "ProdutoM" );
        
        $filtro = array (
                "dp.codprodutodepartamento" => $coddepartamento 
        );
        
        $produto = $this->ProdutoM->getDepartamento ( $filtro, FALSE, $offset, LINHAS_PESQUISA_DASHBOARD, $campoOrdenacao, $tipoOrdenacao );
        
        if (! $produto) {
            show_error ( "Não foram encontrados produtos." );
        }
        
        $html = montaListaProduto ( $produto );
        
        $data = array ();
        $data ["LISTAGEM"] = $html;
        $data ["BLC_DEPARTAMENTOS"] = array ();
        
        $this->load->model ( "Departamento_Model", "DepartamentoM" );
        
        $departamentos = $this->DepartamentoM->getDepartamentosDisponiveis ( $coddepartamento );
        
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
        
        $totalItens = $this->ProdutoM->getDepartamentoTotal ( $filtro );
        
        $queryString = "?" . $_SERVER ['QUERY_STRING'];
        
        $data ["BLC_ORDENACAO"] = array ();
        $data ["BLC_ORDENACAO"] [] = array (
                "ITENSEXIBICAO" => sizeof ( $produto ),
                "TOTALITENS" => $totalItens,
                "URLATUAL" => current_url () . $queryString 
        );
        
        $totalPaginas = ceil ( $totalItens / LINHAS_PESQUISA_DASHBOARD );
        
        $data ["BLC_PAGINACAO"] = array ();
        
        $paginas = array ();
        
        for($i = 1; $i <= $totalPaginas; $i ++) {
            $paginas [] = array (
                    "URLPAGINA" => current_url () . "?pg={$i}&oc={$oc}&to={$to}",
                    "INDICE" => $i 
            );
        }
        
        $data ["BLC_PAGINACAO"] [] = array (
                "BLC_PAGINA" => $paginas 
        );
        
        $this->parser->parse ( "inicial", $data );
    }
    
    /**
     * Remapeamento
     *
     * @param unknown $method            
     * @param unknown $params            
     */
    public function _remap($method, $params = array()) {
        if (method_exists ( $this, $method )) {
            return call_user_func ( array (
                    $this,
                    $method 
            ), $params );
        } else {
            $this->listagem ( $method );
        }
    }
}