<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Atributo_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('atributo');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0) {
		$this->db->select('a.codatributo, a.nomeatributo, a.codtipoatributo');
		$this->db->select('t.nometipoatributo as nomepai');
		$this->db->where($condicao);
		$this->db->from('atributo a');
		$this->db->join('tipoatributo t', 't.codtipoatributo = a.codtipoatributo', 'LEFT');
		
		if ($primeiraLinha) {
			return $this->db->get()->first_row();
		} else {
			$this->db->limit(LINHAS_PESQUISA_DASHBOARD, $pagina);
			return $this->db->get()->result();
		}
	}
	
	public function post($itens) {
		$res = $this->db->insert('atributo', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codatributo) {
		$this->db->where('codatributo', $codatributo, FALSE);
		$res = $this->db->update('atributo', $itens);
		if ($res) {
			return $codatributo;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codatributo) {
		$this->db->where('codatributo', $codatributo, FALSE);
		return $this->db->delete('atributo');
	}
}