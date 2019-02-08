<div class="row-fluid">
    <div class="span12">
        <legend>Forma de Pagamento</legend>
    </div>
</div>
<div class="row-fluid">
    <div class="span6">
        <div class="well">
            <h5>Informações da Compra:</h5>
            SubTotal: R$ {SUBTOTAL}<br>
            Frete: R$ {FRETE}<br>
            <hr>
            Total: R$ {TOTAL}<br>
        </div>
    </div>
    <div class="span6">
        <div class="well">
            <h5>Formas de Pagamento:</h5>
            <form action="{URLPAGAMENTO}" method="post">
            {BLC_FORMAPAGAMENTO}
            <label>
                <input name="codformapagamento" {CHECKED_FE} type="radio" value="{CODFORMAPAGAMENTO}" data-tipo="{TIPO}" class="set-formapagamento">{NOMEFORMAPAGAMENTO} -  R$ {VALOR}
            </label>
            {/BLC_FORMAPAGAMENTO}
            
            <div class="info-cartao-credito">
                <label>Número do Cartão:</label>
                <input type="text" name="numerocartao" maxlength="16" class="set-integer">
                <label>Código Verificador:</label>
                <input type="text" name="codigoverificador" maxlength="3" class="set-integer">
                <label>Vencimento:</label>
                <input type="text" name="mescartao" maxlength="2" class="set-integer" placeholder="MM">
                <input type="text" name="anocartao" maxlength="4" class="set-integer" placeholder="AAAA">
                <label>Parcelamento:</label>
                <select name="parcela">
                    {BLC_PARCELACARTAO}
                        <option value="{NUMEROPARCELA}">{NUMEROPARCELA}x - R$ {VALORPARCELA}</option>
                    {/BLC_PARCELACARTAO}
                </select>
            </div>
            <hr>
            {BLC_PERMITECOMPRAR}
            <button type="submit" class="btn btn-success pull-right">Finalizar</button>
            {/BLC_PERMITECOMPRAR}
            </form>
        </div>
    </div>
</div>