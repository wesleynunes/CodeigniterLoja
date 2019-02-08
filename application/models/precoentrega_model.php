<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PrecoEntrega_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('faixaprecoformaentrega');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('codfaixaprecoformaentrega, cepinicialfaixaprecoformaentrega, cepfinalfaixaprecoformaentrega, prazofaixaprecoformaentrega, pesoinicialfaixaprecoformaentrega, pesofinalfaixaprecoformaentrega, valorfaixaprecoformaentrega, codformaentrega');
		$this->db->where($condicao);
		$this->db->from('faixaprecoformaentrega');
		
		if ($primeiraLinha) {
			return $this->db->get()->first_row();
		} else {
			if ($limite !== FALSE) {
				$this->db->limit($limite, $pagina);
			}
			return $this->db->get()->result();
		}
	}
	
	public function getPrecoEntrega($cep, $pesoCarrinho, $codformaentrega = FALSE) {
	    $this->db->select('f.codfaixaprecoformaentrega, f.valorfaixaprecoformaentrega, f.prazofaixaprecoformaentrega, f.codformaentrega');
	    $this->db->select('e.nomeformaentrega, e.codigocorreiosformaentrega');
	    $this->db->from('faixaprecoformaentrega f');
	    $this->db->from('formaentrega e');
	    
	    $this->db->where("f.cepinicialfaixaprecoformaentrega <= ", $cep, FALSE);
	    $this->db->where("f.cepfinalfaixaprecoformaentrega >= ", $cep, FALSE);
	    
	    $this->db->where("f.pesoinicialfaixaprecoformaentrega <= ", $pesoCarrinho, FALSE);
	    $this->db->where("f.pesofinalfaixaprecoformaentrega >= ", $pesoCarrinho, FALSE);
	     $this->db->where("e.codigocorreiosformaentrega", '');
	    
	    $this->db->where("e.codformaentrega", "f.codformaentrega", FALSE);
	    $this->db->where("e.habilitaformaentrega", 'S');
	    if ($codformaentrega) {
           $this->db->where("f.codformaentrega", $codformaentrega, FALSE);
	       return $this->db->get()->first_row();
	    } else {
	       return $this->db->get()->result();
	    }
	}
	
	public function post($itens) {
		$res = $this->db->insert('faixaprecoformaentrega', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codfaixaprecoformaentrega) {
		$this->db->where('codfaixaprecoformaentrega', $codfaixaprecoformaentrega, FALSE);
		$res = $this->db->update('faixaprecoformaentrega', $itens);
		if ($res) {
			return $codfaixaprecoformaentrega;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codfaixaprecoformaentrega) {
		$this->db->where('codfaixaprecoformaentrega', $codfaixaprecoformaentrega, FALSE);
		return $this->db->delete('faixaprecoformaentrega');
	}
}