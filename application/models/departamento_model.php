<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Departamento_Model extends CI_Model {
    public function getTotal($condicao = array()) {
        $this->db->where ( $condicao );
        $this->db->from ( 'departamento' );
        return $this->db->count_all_results ();
    }
    public function getDepartamentosDisponiveis($coddepartamentopai = FALSE) {
        $this->db->select ( "d.codepartamento, d.nomedepartamento" );
        $this->db->from ( "departamento d" );
        $this->db->where ( "d.codepartamento IN (SELECT pd.codprodutodepartamento FROM produtodepartamento pd WHERE fun_totalsku(pd.codproduto) > 0)", NULL, FALSE );
        
        if ($coddepartamentopai) {
            $this->db->where ( "d.coddepartamentopai", $coddepartamentopai, FALSE );
        } else {
            $this->db->where ( "d.coddepartamentopai IS NULL", NULL, FALSE );
        }
        
        $this->db->order_by ( "d.nomedepartamento", "ASC" );
        
        return $this->db->get ()->result ();
    }
    public function get($condicao = array(), $primeiraLinha = FALSE, $pagina = 0, $limite = LINHAS_PESQUISA_DASHBOARD) {
        $this->db->select ( 'd.codepartamento, d.nomedepartamento, d.coddepartamentopai' );
        $this->db->select ( 'p.nomedepartamento as nomepai' );
        $this->db->where ( $condicao );
        $this->db->from ( 'departamento d' );
        $this->db->join ( 'departamento p', 'p.codepartamento = d.coddepartamentopai', 'LEFT' );
        
        if ($primeiraLinha) {
            return $this->db->get ()->first_row ();
        } else {
            if ($limite !== FALSE) {
                $this->db->limit ( $limite, $pagina );
            }
            return $this->db->get ()->result ();
        }
    }
    public function post($itens) {
        $res = $this->db->insert ( 'departamento', $itens );
        if ($res) {
            return $this->db->insert_id ();
        } else {
            return FALSE;
        }
    }
    public function update($itens, $coddepartamento) {
        $this->db->where ( 'codepartamento', $coddepartamento, FALSE );
        $res = $this->db->update ( 'departamento', $itens );
        if ($res) {
            return $coddepartamento;
        } else {
            return FALSE;
        }
    }
    public function delete($coddepartamento) {
        $this->db->where ( 'codepartamento', $coddepartamento, FALSE );
        return $this->db->delete ( 'departamento' );
    }
}