<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FormaPagamento_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('formapagamento');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('codformapagamento, nomeformapagamento, habilitaformapagamento, tipoformapagamento');
		$this->db->select('descontoformapagamento, 	maximoparcelasformapagamento');
		$this->db->where($condicao);
		$this->db->from('formapagamento');
		
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
		$res = $this->db->insert('formapagamento', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codformapagamento) {
		$this->db->where('codformapagamento', $codformapagamento, FALSE);
		$res = $this->db->update('formapagamento', $itens);
		if ($res) {
			return $codformapagamento;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codformapagamento) {
		$this->db->where('codformapagamento', $codformapagamento, FALSE);
		return $this->db->delete('formapagamento');
	}
}