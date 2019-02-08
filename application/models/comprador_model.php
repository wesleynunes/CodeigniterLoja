<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comprador_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('comprador');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('codcomprador, nomecomprador, enderecocomprador, cidadecomprador, ufcomprador, cepcomprador, emailcomprador, telefonecomprador, cpfcomprador, sexocomprador, senhacomprador');
		$this->db->where($condicao);
		$this->db->from('comprador');
		
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
	    $res = $this->db->insert('comprador', $itens);
	    if ($res) {
	        return $this->db->insert_id();
	    } else {
	        return FALSE;
	    }
	}
	
	public function update($itens, $codcomprador) {
	    $this->db->where('codcomprador', $codcomprador, FALSE);
	    $res = $this->db->update('comprador', $itens);
	    if ($res) {
	        return $codcomprador;
	    } else {
	        return FALSE;
	    }
	}
	
    public function validaCPFDuplicado($codcomprador, $cpfcomprador) {
	    $this->db->from('comprador');
	    $this->db->where('cpfcomprador', $cpfcomprador, TRUE);
	    $this->db->where('codcomprador !=', $codcomprador, TRUE);
	    return $this->db->count_all_results();
	}
	
	public function validaEmailDuplicado($codcomprador, $emailcomprador) {
	    $this->db->from('comprador');
	    $this->db->where('emailcomprador', $emailcomprador, TRUE);
	    $this->db->where('codcomprador !=', $codcomprador, TRUE);
	    return $this->db->count_all_results();
	}
	
	public function delete($codcomprador) {
	    $this->db->where('codcomprador', $codcomprador, FALSE);
	    return $this->db->delete('comprador');
	}
}