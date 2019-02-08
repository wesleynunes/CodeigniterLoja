<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('usuario');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0) {
		$this->db->select('codusuario, nomeusuario, emailusuario, ativadousuario');
		$this->db->where($condicao);
		$this->db->from('usuario');
		
		if ($primeiraLinha) {
			return $this->db->get()->first_row();
		} else {
			$this->db->limit(LINHAS_PESQUISA_DASHBOARD, $pagina);
			return $this->db->get()->result();
		}
	}
	
	public function post($itens) {
		$res = $this->db->insert('usuario', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codusuario) {
		$this->db->where('codusuario', $codusuario, FALSE);
		$res = $this->db->update('usuario', $itens);
		if ($res) {
			return $codusuario;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codusuario) {
		$this->db->where('codusuario', $codusuario, FALSE);
		return $this->db->delete('usuario');
	}
}