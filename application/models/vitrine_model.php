<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vitrine_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('vitrine');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('codvitrine, nomevitrine, vitrineativa');
		$this->db->select('datainiciovitrine, datafinalvitrine');
		$this->db->where($condicao);
		$this->db->from('vitrine');
		
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
		$res = $this->db->insert('vitrine', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codvitrine) {
		$this->db->where('codvitrine', $codvitrine, FALSE);
		$res = $this->db->update('vitrine', $itens);
		if ($res) {
			return $codvitrine;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codvitrine) {
		$this->db->where('codvitrine', $codvitrine, FALSE);
		return $this->db->delete('vitrine');
	}
}