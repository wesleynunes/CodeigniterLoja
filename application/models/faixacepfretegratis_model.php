<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FaixaCepFreteGratis_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('faixacepfretegratis');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('codfaixacepfretegratis, cepinicialfaixacepfretegratis');
		$this->db->select('cepfinalfaixacepfretegratis, pesoinicialfaixacepfretegratis');
		$this->db->select('pesofinalfaixacepfretegratis, valorminimofaixacepfretegratis');
		$this->db->select('codformaentrega, habilitafaixacepfretegratis');
		$this->db->where($condicao);
		$this->db->from('faixacepfretegratis');
		
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
		$res = $this->db->insert('faixacepfretegratis', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codfaixacepfretegratis) {
		$this->db->where('codfaixacepfretegratis', $codfaixacepfretegratis, FALSE);
		$res = $this->db->update('faixacepfretegratis', $itens);
		if ($res) {
			return $codfaixacepfretegratis;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codfaixacepfretegratis) {
		$this->db->where('codfaixacepfretegratis', $codfaixacepfretegratis, FALSE);
		return $this->db->delete('faixacepfretegratis');
	}
}