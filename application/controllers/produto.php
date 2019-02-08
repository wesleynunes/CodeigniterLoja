<?php
if (! defined ( 'BASEPATH' ))
    exit ( 'No direct script access allowed' );
class Produto extends CI_Controller {
    public function __construct() {
        parent::__construct ();
        $this->layout = LAYOUT_LOJA;
        $this->load->model ( "Produto_Model", "ProdutoM" );
        $this->load->model ( "ProdutoFoto_Model", "ProdutoFotoM" );
        $this->load->model ( "Sku_Model", "SkuM" );
        $this->load->model ( "FotoSku_Model", "FotoSkuM" );
    }
    
    /**
     * Exibe a ficha do produto
     *
     * @param integer $codproduto            
     */
    public function getFicha($codproduto) {
        $data = array ();
        
        $data["COMPRAR_VISIBILDADE"] = null;
        $data["INDISPONIVEL_VISIBILDADE"] = "hide";
        
        $info = $this->ProdutoM->get ( array (
                "codproduto" => $codproduto 
        ), TRUE );
        
        if (! $info) {
            show_error ( utf8_decode ( "Produto que você procura não foi encontrado." ), 404, utf8_decode ( "Produto não encontrado" ) );
        }
        
        $data ["NOMEPRODUTO"] = $info->nomeproduto;
        $data ["DESCRICAOBASICA"] = $info->resumoproduto;
        $data ["DESCRICAOCOMPLETA"] = $info->fichaproduto;
        
        $data ["BLC_PROMOCAO"] = array ();
        
        if (($info->valorpromocional > 0) && ($info->valorproduto > $info->valorpromocional)) {
            $valorFinal = $info->valorpromocional;
            $data ["BLC_PROMOCAO"] [] = array (
                    "VALORANTIGO" => number_format ( $info->valorproduto, 2, ',', '.' ) 
            );
        } else {
            $valorFinal = $info->valorproduto;
        }
        $data ["VALORFINALPRODUTO"] = number_format ( $valorFinal, 2, ',', '.' );
        
        $data ["BLC_SKUSIMPLES"] = array ();
        $data ["BLC_SKUCOMPLEXO"] = array ();
        $data ["CODPRODUTO"] = $codproduto;
        
        $data ["BLC_GALERIA"] = array ();
        
        if (! empty ( $info->codtipoatributo )) {
            $sku = $this->SkuM->getPorProdutoAtributo ( $codproduto );
            
            $i = 0;
            foreach ( $sku as $s ) {
                
                $i++;
                $data ["BLC_SKUCOMPLEXO"] [] = array (
                        "CODSKU" => $s->codsku,
                        "NOMEATRIBUTO" => $s->nomeatributo,
                        "REFERENCIA" => $s->referencia,
                        "DISPONIBILIDADE_SKU" => ($s->quantidade>0)?true:false,
                        "SELECTEDSKU" => ($i==sizeof($sku))?"checked":null
                );
                
                if ($i == sizeof($sku)) {
                    if ($s->quantidade<=0) {
                        $data["COMPRAR_VISIBILDADE"] = "hide";
                        $data["INDISPONIVEL_VISIBILDADE"] = null;
                    }
                }
                
                //CARREGA FOTOS DO SKU
                
                $foto = $this->FotoSkuM->getFotoSKU ( $s->codsku );
                
                if ($foto) {
                    foreach ( $foto as $f ) {
                        $fotoGaleria = array ();
                        $fotoGaleria [] = array (
                                "URLFOTOTHUMB" => base_url ( "assets/img/produto/80x80/" . $f->codprodutofoto . "." . $f->produtofotoextensao ),
                                "URLFOTOZOOM" => base_url ( "assets/img/produto/original/" . $f->codprodutofoto . "." . $f->produtofotoextensao ),
                                "URLFOTONORMAL" => base_url ( "assets/img/produto/500x500/" . $f->codprodutofoto . "." . $f->produtofotoextensao ),
                                "CODPRODUTOFOTO" => $f->codprodutofoto
                        );
                    }
                
                    $data ["BLC_GALERIA"] [] = array (
                            "BLC_FOTOS" => $fotoGaleria,
                            "NOMEGAL" => "gal-sku-".$s->codsku,
                            "CLASSEGALERIA" => "hide"
                    );
                }
            }
            
        } else {
            $sku = $this->SkuM->getPorProdutoSimples ( $codproduto );
            $data ["BLC_SKUSIMPLES"] [] = array (
                    "CODSKU" => $sku->codsku 
            );
            
            if ($sku->quantidade<=0) {
                $data["COMPRAR_VISIBILDADE"] = "hide";
                $data["INDISPONIVEL_VISIBILDADE"] = null;
            }
        }
        $foto = $this->ProdutoFotoM->get ( array (
                "p.codproduto" => $codproduto 
        ), TRUE );
        
        if ($foto) {
            $data ["FOTOPRINCIPAL"] = base_url ( "assets/img/produto/500x500/" . $foto->codprodutofoto . "." . $foto->produtofotoextensao );
            $data ["FOTOZOOM"] = base_url ( "assets/img/produto/original/" . $foto->codprodutofoto . "." . $foto->produtofotoextensao );
        } else {
            $data ["FOTOPRINCIPAL"] = base_url ( "assets/img/foto-indisponivel.jpg" );
            $data ["FOTOZOOM"] = "";
        }
        
        $foto = $this->ProdutoFotoM->get ( array (
                "p.codproduto" => $codproduto 
        ) );
        
        if ($foto) {
            foreach ( $foto as $f ) {
                $fotoGaleria = array ();
                $fotoGaleria [] = array (
                        "URLFOTOTHUMB" => base_url ( "assets/img/produto/80x80/" . $f->codprodutofoto . "." . $f->produtofotoextensao ),
                        "URLFOTOZOOM" => base_url ( "assets/img/produto/original/" . $f->codprodutofoto . "." . $f->produtofotoextensao ),
                        "URLFOTONORMAL" => base_url ( "assets/img/produto/500x500/" . $f->codprodutofoto . "." . $f->produtofotoextensao ),
                        "CODPRODUTOFOTO" => $f->codprodutofoto
                );
            }
            $data ["BLC_GALERIA"] [] = array (
                    "BLC_FOTOS" => $fotoGaleria,
                    "NOMEGAL" => "fotos-sem-sku",
                    "CLASSEGALERIA" => ""
            );
        }
        
        $this->parser->parse ( "ficha_produto", $data );
    }
    
    public function _remap($method, $params = array()) {
        
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $params);
        } else {
            $this->getFicha($method);
        }
    }
}