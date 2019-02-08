<legend>
Manutenção de Usuários - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar usuários" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codusuario" id="codusuario" value="{codusuario}">
	<div class="control-group">
		<label class="control-label" for="nomeusuario">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nomeusuario" name="nomeusuario" value="{nomeusuario}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="emailusuario">Email <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="email" id="emailusuario" name="emailusuario" value="{emailusuario}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="senhausuario">Senha <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="password" id="senhausuario" name="senhausuario" value="">
	    </div>
	</div>
	<div class="control-group">
 	  <div class="controls">
			<label class="checkbox">
				<input type="checkbox" name="ativadousuario" id="ativadousuario" value="S" {chk_ativousuario}> Ativo
			</label>
		</div>
  	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>