<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ItemCarrinho_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('atributo');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0) {
		$this->db->select('p.nomeproduto');
		$this->db->select('ic.quantidadeitem, ic.valoritem');
		$this->db->select("ic.quantidadeitem * ic.valoritem AS valorfinal", FALSE);
		$this->db->where($condicao);
		$this->db->from('itemcarrinho ic');
		$this->db->from('sku s');
		$this->db->from('produto p');
		$this->db->where('s.codsku', 'ic.codsku', FALSE);
		$this->db->where('p.codproduto', 's.codproduto', FALSE);

		if ($primeiraLinha) {
			return $this->db->get()->first_row();
		} else {
			$this->db->limit(LINHAS_PESQUISA_DASHBOARD, $pagina);
			return $this->db->get()->result();
		}
	}
	
	public function post($itens) {
		$res = $this->db->insert('itemcarrinho', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codatributo) {
		$this->db->where('codcarrinho', $codcarrinho, FALSE);
		$res = $this->db->update('carrinho', $itens);
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