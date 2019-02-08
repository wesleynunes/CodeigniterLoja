<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Produto_Model extends CI_Model {
    public function getTotal($condicao = array()) {
        $this->db->where ( $condicao );
        $this->db->from ( 'produto' );
        return $this->db->count_all_results ();
    }
    public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD, $ordenacao = FALSE, $tipoOrdem = "ASC") {
        $this->db->select ( 'codproduto, nomeproduto, resumoproduto, fichaproduto, valorproduto, valorpromocional, codtipoatributo, urlseo' );
        $this->db->select ( 'peso, altura, largura, comprimento' );
        $this->db->where ( $condicao );
        $this->db->from ( 'produto' );
        
        if ($primeiraLinha) {
            return $this->db->get ()->first_row ();
        } else {
            if ($limite !== FALSE) {
                $this->db->limit ( $limite, $pagina );
            }
            
            if ($ordenacao) {
                $this->db->order_by ( $ordenacao, $tipoOrdem );
            }
            
            return $this->db->get ()->result ();
        }
    }
    public function getBuscaTotal($condicao = array()) {
        $this->db->from ( "produto p" );
        foreach ( $condicao as $c ) {
            $this->db->like ( "UPPER(p.nomeproduto)", strtoupper ( $c ), 'both' );
        }
        $this->db->where ( "fun_totalsku(p.codproduto) > ", 0, FALSE );
        
        return $this->db->count_all_results ();
    }
    public function getBusca($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD, $ordenacao = FALSE, $tipoOrdem = "ASC") {
        $this->db->select ( 'codproduto, nomeproduto, resumoproduto, fichaproduto, valorproduto, valorpromocional, codtipoatributo, urlseo' );
        foreach ( $condicao as $c ) {
            $this->db->like ( "UPPER(nomeproduto)", strtoupper ( $c ), 'both' );
        }
        $this->db->from ( 'produto' );
        
        if ($primeiraLinha) {
            return $this->db->get ()->first_row ();
        } else {
            if ($limite !== FALSE) {
                $this->db->limit ( $limite, $pagina );
            }
            
            if ($ordenacao) {
                $this->db->order_by ( $ordenacao, $tipoOrdem );
            }
            
            return $this->db->get ()->result ();
        }
    }
    public function getDepartamentoTotal($condicao = array()) {
        $this->db->from ( "produto p" );
        $this->db->from ( "produtodepartamento dp" );
        $this->db->where ( $condicao );
        $this->db->where ( "dp.codproduto", "p.codproduto", FALSE );
        $this->db->where ( "fun_totalsku(p.codproduto) > ", 0, FALSE );
        
        return $this->db->count_all_results ();
    }
    public function getDepartamento($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD, $ordenacao = FALSE, $tipoOrdem = "ASC") {
        $this->db->select ( 'p.codproduto, p.nomeproduto, p.resumoproduto, p.fichaproduto, p.valorproduto, p.valorpromocional, p.codtipoatributo, p.urlseo' );
        $this->db->from ( "produto p" );
        $this->db->from ( "produtodepartamento dp" );
        $this->db->where ( $condicao );
        $this->db->where ( "dp.codproduto", "p.codproduto", FALSE );
        $this->db->where ( "fun_totalsku(p.codproduto) > ", 0, FALSE );
        
        if ($primeiraLinha) {
            return $this->db->get ()->first_row ();
        } else {
            if ($limite !== FALSE) {
                $this->db->limit ( $limite, $pagina );
            }
            
            if ($ordenacao) {
                $this->db->order_by ( $ordenacao, $tipoOrdem );
            }
            
            return $this->db->get ()->result ();
        }
    }
    public function post($itens) {
        $res = $this->db->insert ( 'produto', $itens );
        if ($res) {
            return $this->db->insert_id ();
        } else {
            return FALSE;
        }
    }
    public function update($itens, $codproduto) {
        $this->db->where ( 'codproduto', $codproduto, FALSE );
        $res = $this->db->update ( 'produto', $itens );
        if ($res) {
            return $codproduto;
        } else {
            return FALSE;
        }
    }
    public function delete($codproduto) {
        $this->db->where ( 'codproduto', $codproduto, FALSE );
        return $this->db->delete ( 'produto' );
    }
}