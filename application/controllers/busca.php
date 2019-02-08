<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Busca extends CI_Controller {
    public function __construct() {
        parent::__construct ();
        $this->layout = LAYOUT_LOJA;
    }
    public function listagem($coddepartamento) {
        $pesquisa = $this->input->get ( "pesquisa" );
        $pg = $this->input->get ( "pg" );
        $to = $tipoOrdenacao = $this->input->get ( "to" );
        $oc = $campoOrdenacao = $this->input->get ( "oc" );
        
        if (! $pesquisa) {
            redirect ();
        }
        
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
                $campoOrdenacao = "nomeproduto";
                break;
            case "valor" :
                $campoOrdenacao = "valorproduto";
                break;
            default :
                $campoOrdenacao = "nomeproduto";
        }
        
        $this->load->model ( "Produto_Model", "ProdutoM" );
        
        $filtro = explode ( " ", $pesquisa );
        
        $produto = $this->ProdutoM->getBusca ( $filtro, FALSE, $offset, LINHAS_PESQUISA_DASHBOARD, $campoOrdenacao, $tipoOrdenacao );
        
        if (! $produto) {
            show_error ( "Não foram encontrados produtos." );
        }
        
        $html = montaListaProduto ( $produto );
        
        $data = array ();
        $data ["LISTAGEM"] = $html;
        $data ["BLC_DEPARTAMENTOS"] = array ();
        
        $aCodProduto = array ();
        
        foreach ( $produto as $p ) {
            array_push ( $aCodProduto, $p->codproduto );
        }
        
        $departamentosExibir = array ();
        
        $this->load->model ( "ProdutoDepartamento_Model", "ProdutoDepartamentoM" );
        $this->load->model ( "Departamento_Model", "DepartamentoM" );
        
        foreach ( $aCodProduto as $key => $value ) {
            
            $infoDepartamentos = $this->ProdutoDepartamentoM->getDepartamentoPaiProduto ( $value );
            
            foreach ( $infoDepartamentos as $infodep ) {
                array_push ( $departamentosExibir, $infodep->codprodutodepartamento );
            }
        }
        
        $departamentosExibir = array_unique ( $departamentosExibir );
        
        foreach ( $departamentosExibir as $indice => $valor ) {
            
            $dep = $this->DepartamentoM->get ( array (
                    "d.codepartamento" => $valor 
            ), TRUE );
            
            $filhos = array ();
            $departamentosFilhos = $this->DepartamentoM->getDepartamentosDisponiveis ( $valor );
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
        $totalItens = $this->ProdutoM->getBuscaTotal ( $filtro );
        
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
                    "URLPAGINA" => current_url () . "?pesquisa={$pesquisa}&pg={$i}&oc={$oc}&to={$to}",
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