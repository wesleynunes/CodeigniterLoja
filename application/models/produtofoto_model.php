<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProdutoFoto_Model extends CI_Model {
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
		$this->db->select('p.codprodutofoto, p.produtofotoextensao, p.codproduto');
		$this->db->where($condicao);
		$this->db->from('produtofoto p');
	
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
		$res = $this->db->insert('produtofoto', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function delete($codprodutofoto, $codproduto) {
		$this->db->where('codprodutofoto', $codprodutofoto, FALSE);
		$this->db->where('codproduto', $codproduto, FALSE);
		return $this->db->delete('produtofoto');
	}
}