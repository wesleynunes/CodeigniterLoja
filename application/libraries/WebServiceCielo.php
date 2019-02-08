<?php
class WebServiceCielo {
    private $linkWS = null;
    private $numero = null;
    private $chave = null;
    private $moeda = "986";
    private $lang = "PT";
    private $captA = "false";
    private $versao = "1.1.1";
    public $sucesso = false;
    public $mensagem = false;
    public $erro = false;
    public $tid = false;
    public function __construct() {
        switch (ENVIRONMENT) {
            case 'production' :
                $this->linkWS = "https://ecommerce.cielo.com.br/servicos/ecommwsec.do";
                $this->numero = "XXXXXX";
                $this->chave = "XXXXXX";
                break;
            case 'development' :
            case 'testing' :
                $this->linkWS = "https://qasecommerce.cielo.com.br/servicos/ecommwsec.do";
                $this->numero = "1006993069";
                $this->chave = "25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3";
                break;
        }
    }
    public function enviaAutorizacao($cartao, $pedido) {
        $xml = '';
        $xml .= '<?xml version="1.0" encoding="ISO-8859-1"?>';
        $xml .= '<requisicao-transacao id="7" versao="1.1.1">';
        
        $xml .= '<dados-ec>';
        $xml .= '<numero>' . $this->numero . '</numero>';
        $xml .= '<chave>' . $this->chave . '</chave>';
        $xml .= '</dados-ec>';
        
        $xml .= '<dados-portador>';
        $xml .= '<numero>' . $cartao->numero . '</numero>';
        $xml .= '<validade>' . $cartao->ano . '' . $cartao->mes . '</validade>';
        $xml .= '<indicador>1</indicador>';
        $xml .= '<codigo-seguranca>' . $cartao->codigoseguranca . '</codigo-seguranca>';
        $xml .= '</dados-portador>';
        
        $xml .= '<dados-pedido>';
        $xml .= '<numero>' . $pedido->codcarrinho . '</numero>';
        $xml .= '<valor>' . $pedido->valor . '</valor>';
        $xml .= '<moeda>986</moeda>';
        $xml .= '<data-hora>' . $pedido->datahora . '</data-hora>';
        $xml .= '<descricao>CARRINHO' . $pedido->codcarrinho . '</descricao>';
        $xml .= '<idioma>PT</idioma>';
        $xml .= '</dados-pedido>';
        
        $xml .= '<forma-pagamento>';
        $xml .= '<bandeira>' . $pedido->bandeira . '</bandeira>';
        $xml .= '<produto>' . $pedido->produto . '</produto>';
        $xml .= '<parcelas>' . $pedido->parcelas . '</parcelas>';
        $xml .= '</forma-pagamento>';
        
        $xml .= '<autorizar>3</autorizar>';
        $xml .= '<capturar>false</capturar>';
        $xml .= '</requisicao-transacao>';
        
        $ch = curl_init ();
        
        $retorno = new stdClass();
        $retorno->error = false;
        $retorno->message = '';
        $retorno->tid = '';
        
        if ($ch) {
            curl_setopt ( $ch, CURLOPT_HEADER, 0 );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
            curl_setopt ( $ch, CURLOPT_SSLVERSION, 3 );
            curl_setopt ( $ch, CURLOPT_URL, $this->linkWS );
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ( array (
                    "mensagem" => $xml 
            ) ) );
            
            $xml = curl_exec($ch);
            $ern = (bool) curl_errno($ch);
            $err = curl_error($ch);
            
            curl_close($ch);
            
            if ($ern) {
                $retorno->error = true;
                $retorno->message = $err;
                return $retorno;
            } else {
                $cielo = simplexml_load_string($xml);
                
                if (!empty($cielo->autorizacao->lr)) {
                    
                    $lr = $cielo->autorizacao->lr;
                    
                    if (($lr == 00) || ($lr == 0) || ($lr == 000) || ($lr == 11)) {
                        $retorno->tid = $cielo->tid;
                        return $retorno;
                    } else {
                        $retorno->error = true;
                        $retorno->message = $cielo->autorizao->codigo." - ".$cielo->autorizao->mensagem;
                        return $retorno;
                    }
                    
                } else {
                    $retorno->error = true;
                    $retorno->message = $cielo->autorizao->codigo." - ".$cielo->autorizao->mensagem;
                    return $retorno;
                }
            }
        } else {
            $retorno->error = true;
            $retorno->message = "Não foi possível processar a operação.";
            return $retorno;
        }
    }
}