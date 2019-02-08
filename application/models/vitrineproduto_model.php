<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VitrineProduto_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('vitrineproduto');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('v.codvitrineproduto, v.codvitrine, v.ordemvitrineproduto');
		$this->db->select('p.nomeproduto, p.codproduto, p.valorproduto, p.valorpromocional, p.codtipoatributo, p.urlseo');
		$this->db->from('vitrineproduto v');
		$this->db->from('produto p');
		$this->db->where($condicao);
		$this->db->where('p.codproduto', 'v.codproduto', FALSE);
		
		if ($primeiraLinha) {
			return $this->db->get()->first_row();
		} else {
			$this->db->order_by('v.ordemvitrineproduto', 'ASC');
			if ($limite !== FALSE) {
				$this->db->limit($limite, $pagina);
			}
			return $this->db->get()->result();
		}
	}
	
	public function post($itens) {
		$res = $this->db->insert('vitrineproduto', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function delete($codvitrineproduto) {
		$this->db->where('codvitrineproduto', $codvitrineproduto, FALSE);
		return $this->db->delete('vitrineproduto');
	}
	
	public function diminuirOrdem($codvitrineproduto) {
	    $this->db->set('ordemvitrineproduto', 'ordemvitrineproduto - 1', FALSE);
	    $this->db->where('codvitrineproduto', $codvitrineproduto, FALSE);
	    $this->db->update('vitrineproduto');
	}
	
	public function aumentarOrdem($codvitrineproduto) {
	    $this->db->set('ordemvitrineproduto', 'ordemvitrineproduto + 1', FALSE);
	    $this->db->where('codvitrineproduto', $codvitrineproduto, FALSE);
	    $this->db->update('vitrineproduto');
	}
	
	public function redefineAntigo($codvitrine, $codvitrineproduto, $novaPosicao, $velhaPosicao) {
	    $this->db->set('ordemvitrineproduto', $velhaPosicao, FALSE);
	    $this->db->where('ordemvitrineproduto', $novaPosicao, FALSE);
	    $this->db->where('codvitrineproduto != ', $codvitrineproduto, FALSE);
	    $this->db->where('codvitrine', $codvitrine, FALSE);
	    $this->db->update('vitrineproduto');
	}
	
	public function atualizaDelete($codvitrine, $velhaPosicao) {
	    $this->db->set('ordemvitrineproduto', 'ordemvitrineproduto - 1', FALSE);
	    $this->db->where('codvitrine', $codvitrine, FALSE);
	    $this->db->where('ordemvitrineproduto >', $velhaPosicao, FALSE);
	    $this->db->update('vitrineproduto');
	}
}