<legend>
Faixa de Preço para Entrega - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar preços e faixas da formas de entrega" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codfaixaprecoformaentrega" id="codfaixaprecoformaentrega" value="{codfaixaprecoformaentrega}">
	<input type="hidden" name="codformaentrega" id="codformaentrega" value="{codformaentrega}">
	<div class="control-group">
		<label class="control-label" for="cepinicialfaixaprecoformaentrega">CEP Inicial <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="mask-cep" id="cepinicialfaixaprecoformaentrega" name="cepinicialfaixaprecoformaentrega" value="{cepinicialfaixaprecoformaentrega}" required="required" maxlength="9">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cepfinalfaixaprecoformaentrega">CEP Final <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="mask-cep" id="cepfinalfaixaprecoformaentrega" name="cepfinalfaixaprecoformaentrega" value="{cepfinalfaixaprecoformaentrega}" required="required" maxlength="9">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pesoinicialfaixaprecoformaentrega">Peso Inicial <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="set-peso" id="pesoinicialfaixaprecoformaentrega" name="pesoinicialfaixaprecoformaentrega" value="{pesoinicialfaixaprecoformaentrega}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pesofinalfaixaprecoformaentrega">Peso Final <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="set-peso" id="pesofinalfaixaprecoformaentrega" name="pesofinalfaixaprecoformaentrega" value="{pesofinalfaixaprecoformaentrega}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="prazofinalfaixaprecoformaentrega">Prazo <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="set-integer" id="prazofaixaprecoformaentrega" name="prazofaixaprecoformaentrega" value="{prazofaixaprecoformaentrega}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="valorfaixaprecoformaentrega">Valor <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="set-numeric" id="valorfaixaprecoformaentrega" name="valorfaixaprecoformaentrega" value="{valorfaixaprecoformaentrega}" required="required">
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>