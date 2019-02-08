<legend>
Frete Grátis para Entrega - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar fretes grátis e faixas da formas de entrega" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codfaixacepfretegratis" id="codfaixacepfretegratis" value="{codfaixacepfretegratis}">
	<input type="hidden" name="codformaentrega" id="codformaentrega" value="{codformaentrega}">
	<div class="control-group">
		<label class="control-label" for="cepinicialfaixacepfretegratis">CEP Inicial <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="mask-cep" id="cepinicialfaixacepfretegratis" name="cepinicialfaixacepfretegratis" value="{cepinicialfaixacepfretegratis}" required="required" maxlength="9">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="cepfinalfaixacepfretegratis">CEP Final <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="mask-cep" id="cepfinalfaixacepfretegratis" name="cepfinalfaixacepfretegratis" value="{cepfinalfaixacepfretegratis}" required="required" maxlength="9">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pesoinicialfaixacepfretegratis">Peso Inicial <span>*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="set-peso" id="pesoinicialfaixacepfretegratis" name="pesoinicialfaixacepfretegratis" value="{pesoinicialfaixacepfretegratis}">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="pesofinalfaixacepfretegratis">Peso Final <span>*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="set-peso" id="pesofinalfaixacepfretegratis" name="pesofinalfaixacepfretegratis" value="{pesofinalfaixacepfretegratis}">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="valorminimofaixacepfretegratis">Valor <span>*</span>:</label>
	    <div class="controls">
	    	<input type="text" class="set-numeric" id="valorminimofaixacepfretegratis" name="valorminimofaixacepfretegratis" value="{valorminimofaixacepfretegratis}">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="habilitafaixacepfretegratis"></label>
		<div class="controls">
    	    <label class="checkbox">
    	       <input type="checkbox" {chk_habilitafaixacepfretegratis} name="habilitafaixacepfretegratis" value="S"> Habilitar
    	    </label>
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>