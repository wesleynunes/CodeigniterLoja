<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TipoAtributo_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('tipoatributo');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('codtipoatributo, nometipoatributo');
		$this->db->where($condicao);
		$this->db->from('tipoatributo');
		
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
		$res = $this->db->insert('tipoatributo', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codtipoatributo) {
		$this->db->where('codtipoatributo', $codtipoatributo, FALSE);
		$res = $this->db->update('tipoatributo', $itens);
		if ($res) {
			return $codtipoatributo;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codtipoatributo) {
		$this->db->where('codtipoatributo', $codtipoatributo, FALSE);
		return $this->db->delete('tipoatributo');
	}
}