<legend>
Tipos de Atributos - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar tipos de atributos" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codtipoatributo" id="codtipoatributo" value="{codtipoatributo}">
	<div class="control-group">
		<label class="control-label" for="nometipoatributo">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nometipoatributo" name="nometipoatributo" value="{nometipoatributo}" required="required">
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>