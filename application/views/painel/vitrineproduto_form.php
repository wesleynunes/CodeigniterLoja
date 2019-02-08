<legend>
Itens da Vitrine: {NOMEVITRINE} - Inserir
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar itens da vitrine" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codvitrine" id="codvitrine" value="{codvitrine}">
	<div class="control-group">
		<label class="control-label" for="codproduto">Produto <span class="required">*</span>:</label>
	    <div class="controls">
	    	<select name="codproduto" id="codproduto" required="required" class="select2" style="width:100%;">
	    	{BLC_PRODUTOS}
	    	  <option value="{CODPRODUTO}">{NOMEPRODUTO}</option>
	    	{/BLC_PRODUTOS}
	    	</select>
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>