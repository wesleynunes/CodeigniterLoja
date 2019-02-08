<legend>
Vitrine de Produtos - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar vitrines de produtos" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<input type="hidden" name="codvitrine" id="codvitrine" value="{codvitrine}">
	<div class="control-group">
		<label class="control-label" for="nomevitrine">Nome <span class="required">*</span>:</label>
	    <div class="controls">
	    	<input type="text" id="nomevitrine" name="nomevitrine" value="{nomevitrine}" required="required">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="datainiciovitrine">Data de Início:</label>
	    <div class="controls">
	    	<input type="text" id="datainiciovitrine" name="datainiciovitrine" value="{datainiciovitrine}" class="set-date">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="datafinalvitrine">Data de Fim:</label>
	    <div class="controls">
	    	<input type="text" id="datafinalvitrine" name="datafinalvitrine" value="{datafinalvitrine}" class="set-date">
	    </div>
	</div>
	<div class="control-group">
		<label class="control-label" for="vitrineativa"></label>
		<div class="controls">
    	    <label class="checkbox">
    	       <input type="checkbox" {chk_vitrineativa} name="vitrineativa" value="S"> Habilitar no Site
    	    </label>
	    </div>
	</div>
  	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>