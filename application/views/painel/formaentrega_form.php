<legend>
Formas de Entrega - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar formas de entrega" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codformaentrega" id="codformaentrega" value="{codformaentrega}">
	<div class="control-group">
		<label class="control-label" for="nomeformaentrega">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nomeformaentrega" name="nomeformaentrega" value="{nomeformaentrega}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="codigocorreiosformaentrega">Código Correios:</label>
	    <div class="controls">
	    	<input type="text" id="codigocorreiosformaentrega" name="codigocorreiosformaentrega" value="{codigocorreiosformaentrega}">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="codigocorreiosformaentrega"></label>
		<div class="controls">
    	    <label class="checkbox">
    	       <input type="checkbox" {chk_habilitaformaentrega} name="habilitaformaentrega" value="S"> Habilitar no Site
    	    </label>
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>