<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FormaEntrega_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('formaentrega');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('codformaentrega, nomeformaentrega, habilitaformaentrega, codigocorreiosformaentrega');
		$this->db->where($condicao);
		$this->db->from('formaentrega');
		
		if ($primeiraLinha) {
			return $this->db->get()->first_row();
		} else {
			if ($limite !== FALSE) {
				$this->db->limit($limite, $pagina);
			}
			return $this->db->get()->result();
		}
	}
	
	public function post($itens) {
		$res = $this->db->insert('formaentrega', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codformaentrega) {
		$this->db->where('codformaentrega', $codformaentrega, FALSE);
		$res = $this->db->update('formaentrega', $itens);
		if ($res) {
			return $codformaentrega;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codformaentrega) {
		$this->db->where('codformaentrega', $codformaentrega, FALSE);
		return $this->db->delete('formaentrega');
	}
}