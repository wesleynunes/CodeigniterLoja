<?php

if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Sku_Model extends CI_Model {
    
    public function getPorSKU($codsku) {
        $this->db->select ( 's.codsku, s.referencia, s.quantidade, s.codproduto' );
        $this->db->select ( 'p.nomeproduto, p.valorproduto, p.valorpromocional, p.peso, p.altura, p.largura, p.comprimento' );
        $this->db->select ( '(SELECT codprodutofoto FROM produtofoto WHERE produtofoto.codproduto = p.codproduto LIMIT 1) AS codprodutofoto', FALSE );
        $this->db->select ( '(SELECT produtofotoextensao FROM produtofoto WHERE produtofoto.codproduto = p.codproduto LIMIT 1) AS produtofotoextensao', FALSE );
        $this->db->select ( '(SELECT produtofoto.codprodutofoto FROM produtofoto, produtofotosku WHERE produtofotosku.codsku = s.codsku AND produtofoto.codprodutofoto = produtofotosku.codprodutofoto LIMIT 1) AS codprodutofotosku', FALSE );
        $this->db->select ( '(SELECT produtofoto.produtofotoextensao FROM produtofoto, produtofotosku WHERE produtofotosku.codsku = s.codsku AND produtofoto.codprodutofoto = produtofotosku.codprodutofoto LIMIT 1) AS produtofotoextensaosku', FALSE );
        $this->db->from ( 'sku s' );
        $this->db->from ( 'produto p' );
        $this->db->where ( 's.codsku', $codsku, FALSE );
        $this->db->where ( 'p.codproduto', 's.codproduto', FALSE );
        return $this->db->get ()->first_row ();
    }
    
    /**
     * Retorna SKU para produtos simples
     * 
     * @param unknown $codproduto            
     */
    public function getPorProdutoSimples($codproduto) {
        $this->db->select ( 's.codsku, s.referencia, s.quantidade, s.codproduto' );
        $this->db->select ( "'Produto Simples' AS nomeatributo", FALSE );
        $this->db->from ( 'sku s' );
        $this->db->where ( 's.codproduto', $codproduto, FALSE );
        return $this->db->get ()->first_row ();
    }
    
    /**
     * Retorna SKUs de produtos com atributos
     * 
     * @param unknown $codproduto            
     */
    public function getPorProdutoAtributo($codproduto) {
        $this->db->select ( 's.codsku, s.referencia, s.quantidade, s.codproduto' );
        $this->db->select ( 'a.nomeatributo' );
        $this->db->from ( 'sku s' );
        $this->db->from ( 'skuatributo sa' );
        $this->db->from ( 'atributo a' );
        $this->db->where ( 'sa.codsku', 's.codsku', FALSE );
        $this->db->where ( 'a.codatributo', 'sa.codatributo', FALSE );
        
        return $this->db->get ()->result ();
    }
    public function getAtributosDisponiveis($codproduto) {
        $this->db->select ( 'a.nomeatributo, a.codatributo' );
        $this->db->from ( 'atributo a' );
        $this->db->where ( "a.codatributo NOT IN (SELECT sa.codatributo FROM skuatributo sa, sku s WHERE sa.codsku = s.codsku AND s.codproduto = {$codproduto})" );
        
        return $this->db->get ()->result ();
    }
    public function post($itens) {
        $res = $this->db->insert ( 'sku', $itens );
        if ($res) {
            return $this->db->insert_id ();
        } else {
            return FALSE;
        }
    }
    public function update($codsku, $itens) {
        $this->db->where ( 'codsku', $codsku, FALSE );
        $res = $this->db->update ( 'sku', $itens );
    }
    public function delete($codsku) {
        $this->db->where ( 'codsku', $codsku, FALSE );
        $res = $this->db->delete ( 'sku' );
    }
    public function postAtributo($itens) {
        $res = $this->db->insert ( 'skuatributo', $itens );
        if ($res) {
            return $this->db->insert_id ();
        } else {
            return FALSE;
        }
    }
}