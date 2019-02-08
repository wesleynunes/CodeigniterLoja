<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Carrinho_Model extends CI_Model {
	
	public function getTotal($condicao = array()) {
		$this->db->where($condicao);
		$this->db->from('carrinho');
		return $this->db->count_all_results();
	}
	
	public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0) {
		$this->db->select('c.codcarrinho, c.datahoracompra, c.valorcompra, c.valorfrete, c.valorfinalcompra');
		$this->db->select('c.codcomprador, c.situacao, c.observacao, c.codformaentrega');
		$this->db->select('c.codformapagamento, c.enderecoentrega, c.cidadeentrega');
		$this->db->select('c.ufentrega, c.cepentrega');
		$this->db->select('fp.nomeformapagamento');
		$this->db->select('fe.nomeformaentrega');
		$this->db->select("DATE_FORMAT(c.datahoracompra, '%d/%c/%Y %H:%i') AS data", FALSE);
		$this->db->select('co.nomecomprador, co.enderecocomprador, co.cidadecomprador, co.ufcomprador, co.cepcomprador');
		$this->db->where($condicao);
		$this->db->from('carrinho c');
		$this->db->join('comprador co', 'co.codcomprador = c.codcomprador');
		$this->db->join('formapagamento fp', 'fp.codformapagamento = c.codformapagamento');
		$this->db->join('formaentrega fe', 'fe.codformaentrega = c.codformaentrega');
		
		if ($primeiraLinha) {
			return $this->db->get()->first_row();
		} else {
			$this->db->limit(LINHAS_PESQUISA_DASHBOARD, $pagina);
			$this->db->order_by('c.codcarrinho', 'desc');
			return $this->db->get()->result();
		}
	}
	
	public function post($itens) {
		$res = $this->db->insert('carrinho', $itens);
		if ($res) {
			return $this->db->insert_id();
		} else {
			return FALSE;
		}
	}
	
	public function update($itens, $codcarrinho) {
		$this->db->where('codcarrinho', $codcarrinho, FALSE);
		$res = $this->db->update('carrinho', $itens);
		if ($res) {
			return $codcarrinho;
		} else {
			return FALSE;
		}
	}
	
	public function delete($codatributo) {
		$this->db->where('codatributo', $codatributo, FALSE);
		return $this->db->delete('atributo');
	}
}