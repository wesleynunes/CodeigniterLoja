<legend>
Formas de Pagamento - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar formas de pagamento" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codformapagamento" id="codformapagamento" value="{codformapagamento}">
	<div class="control-group">
		<label class="control-label" for="nomeformapagamento">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nomeformapagamento" name="nomeformapagamento" value="{nomeformapagamento}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="maximoparcelasformapagamento">Máximo de Parcelas <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="maximoparcelasformapagamento" name="maximoparcelasformapagamento" value="{maximoparcelasformapagamento}" required="required" class="set-integer">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="descontoformapagamento">Desconto:</label>
	    <div class="controls">
	    	<input type="text" id="descontoformapagamento" name="descontoformapagamento" value="{descontoformapagamento}" class="set-numeric">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="tipoformapagamento">Tipo de Forma:</label>
	    <div class="controls">
	       <select id="tipoformapagamento" name="tipoformapagamento">
	           <option value="1" {sel_tipoformapagamento1}>Boleto</option>
	           <option value="2" {sel_tipoformapagamento2}>Cartão de Crédito</option>
	       </select>
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="habilitaformapagamento"></label>
		<div class="controls">
    	    <label class="checkbox">
    	       <input type="checkbox" {chk_habilitaformapagamento} name="habilitaformapagamento" value="S"> Habilitar no Site
    	    </label>
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>