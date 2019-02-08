<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class ProdutoDepartamento_Model extends CI_Model {
    public function get($condicao = array()) {
        $this->db->select ( 'dp.codproduto, dp.codprodutodepartamento' );
        $this->db->where ( $condicao );
        $this->db->from ( 'produtodepartamento dp' );
        
        return $this->db->get ()->result ();
    }
    public function getDepartamentoPaiProduto($codproduto) {
        $this->db->select ( 'dp.codproduto, dp.codprodutodepartamento' );
        $this->db->from ( 'produtodepartamento dp' );
        $this->db->from ( 'departamento d' );
        $this->db->where ( "dp.codproduto", $codproduto, FALSE );
        $this->db->where ( "d.codepartamento", "dp.codprodutodepartamento", FALSE );
        $this->db->where ( "d.coddepartamentopai IS NULL", NULL, FALSE );
        
        return $this->db->get ()->result ();
    }
    public function post($itens) {
        $res = $this->db->insert ( 'produtodepartamento', $itens );
    }
    public function delete($codproduto) {
        $this->db->where ( 'codproduto', $codproduto, FALSE );
        return $this->db->delete ( 'produtodepartamento' );
    }
}