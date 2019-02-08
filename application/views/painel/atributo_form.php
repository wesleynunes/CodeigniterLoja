<legend>
Manutenção de Atributos - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar atributos" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codatributo" id="codatributo" value="{codatributo}">
	<div class="control-group">
		<label class="control-label" for="nomeatributo">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nomeatributo" name="nomeatributo" value="{nomeatributo}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="codtipoatributo">Tipo:</label>
	    <div class="controls">
	    	<select name="codtipoatributo" id="codtipoatributo" class="required">
	    		<option value="">Selecione o tipo do atributo</option>
	    		{BLC_TIPOATRIBUTOS}
	    		<option value="{CODTIPOATRIBUTO}" {sel_codtipoatributo}>{NOME}</option>
	    		{/BLC_TIPOATRIBUTOS}
	    	</select>
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>