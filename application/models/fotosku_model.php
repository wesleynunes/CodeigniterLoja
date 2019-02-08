<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FotoSku_Model extends CI_Model {
    
    public function post($item) {
        $this->db->insert('produtofotosku', $item);
    }
    
    public function limpaImagens($codprodutofoto) {
        $this->db->where('codprodutofoto', $codprodutofoto, FALSE);
        $this->db->delete('produtofotosku');
    }
    
    public function getSKUsFoto($codprodutofoto) {
        $this->db->select('codsku');
        $this->db->from('produtofotosku');
        $this->db->where('codprodutofoto', $codprodutofoto, FALSE);
        return $this->db->get()->result();
    }
    
    public function getFotoSKU($codsku) {
        $this->db->select('produtofoto.codprodutofoto, produtofoto.produtofotoextensao');
        $this->db->from('produtofotosku');
        $this->db->from('produtofoto');
        $this->db->where('codsku', $codsku, FALSE);
        $this->db->where('produtofoto.codprodutofoto', 'produtofotosku.codprodutofoto', FALSE);
        return $this->db->get()->result();
    }
}