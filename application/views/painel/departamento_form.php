<legend>
Manutenção de Departamentos - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar departamentos" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codepartamento" id="codepartamento" value="{codepartamento}">
	<div class="control-group">
		<label class="control-label" for="nomedepartamento">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nomedepartamento" name="nomedepartamento" value="{nomedepartamento}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="coddepartamentopai">Departamento Pai:</label>
	    <div class="controls">
	    	<select name="coddepartamentopai" id="coddepartamentopai" {hab_coddepartamentopai}>
	    		<option value="">Selecione um departamento</option>
	    		{BLC_DEPARTAMENTOS}
	    		<option value="{CODDEPARTAMENTO}" {sel_coddepartamentopai}>{NOME}</option>
	    		{/BLC_DEPARTAMENTOS}
	    	</select>
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>