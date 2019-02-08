<legend>
Manutenção de Produtos - {ACAO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar produtos" class="btn">Voltar</a>
	</div>
</legend>
<form action="{ACAOFORM}" method="post" class="form-horizontal">
	<div class="row-fluid">
		<div class="span8">
			<input type="hidden" name="codproduto" id="codproduto" value="{codproduto}">
			<div class="control-group">
				<label class="control-label" for="nomeproduto">Nome <span class="required">*</span>:</label>
			    <div class="controls">
			    	<input type="text" id="nomeproduto" name="nomeproduto" value="{nomeproduto}" required="required">
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="resumoproduto">Resumo <span class="required">*</span>:</label>
			    <div class="controls">
			    	<textarea name="resumoproduto" id="resumoproduto" required="required">{resumoproduto}</textarea>
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="fichaproduto">Ficha <span class="required">*</span>:</label>
			    <div class="controls">
			    	<textarea name="fichaproduto" id="fichaproduto" required="required">{fichaproduto}</textarea>
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="valorproduto">Valor <span class="required">*</span>:</label>
			    <div class="controls">
			    	<input type="text" id="valorproduto" name="valorproduto" value="{valorproduto}" required="required" class="set-numeric">
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="valorpromocional">Valor Promocional:</label>
			    <div class="controls">
			    	<input type="text" id="valorpromocional" name="valorpromocional" value="{valorpromocional}" class="set-numeric">
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="peso">Peso (kg) <span class="required">*</span>:</label>
			    <div class="controls">
			    	<input type="text" id="peso" name="peso" value="{peso}" required="required" class="set-peso">
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="altura">Altura (cm) <span class="required">*</span>:</label>
			    <div class="controls">
			    	<input type="text" id="altura" name="altura" value="{altura}" required="required" class="set-peso">
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="largura">Largura (cm) <span class="required">*</span>:</label>
			    <div class="controls">
			    	<input type="text" id="largura" name="largura" value="{largura}" required="required" class="set-peso">
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="comprimento">Comprimento (cm) <span class="required">*</span>:</label>
			    <div class="controls">
			    	<input type="text" id="comprimento" name="comprimento" value="{comprimento}" required="required" class="set-peso">
			    </div>
			</div>
			<div class="control-group">
				<label class="control-label" for="codtipoatributo">Tipo:</label>
			    <div class="controls">
			    	<select name="codtipoatributo" id="codtipoatributo" {des_tipoatributo} class="set-quantidade-sku">
			    		<option value="">Não especificado</option>
			    		{BLC_TIPOATRIBUTOS}
			    		<option value="{CODTIPOATRIBUTO}" {sel_codtipoatributo}>{NOME}</option>
			    		{/BLC_TIPOATRIBUTOS}
			    	</select>
			    </div>
			</div>
			<div class="control-group" id="quantidade-sku">
				<label class="control-label" for="quantidade">Quantidade:</label>
			    <div class="controls">
			    	<input type="text" id="quantidade" name="quantidade" value="{quantidade}" class="set-integer input-small" {readonly}>
			    </div>
			</div>
		</div>
		<div class="span4">
			<h5>Departamentos</h5>
			<ul class="lista-departamentos">
				{BLC_DEPARTAMENTOPAI}
				<li>
					<label for="departamento-{CODDEPARTAMENTO}" class="checkbox">
						<input {chk_departamentopai} name="departamento[]" class="set-departamento-pai" type="checkbox" id="departamento-{CODDEPARTAMENTO}" value="{CODDEPARTAMENTO}"> {NOMEDEPARTAMENTO}</label>
				</li>
				<li>
					<ul class="lista-departamentos">
					{BLC_DEPARTAMENTOFILHO}
						<li>
							<label for="departamento-{CODDEPARTAMENTOFILHO}" class="checkbox">
								<input {chk_departamentofilho} name="departamento[]" class="set-departamento-filho" data-pai="{CODDEPARTAMENTOPAI}" type="checkbox" id="departamento-{CODDEPARTAMENTOFILHO}" value="{CODDEPARTAMENTOFILHO}"> {NOMEDEPARTAMENTOFILHO}</label>
						</li>
					{/BLC_DEPARTAMENTOFILHO}
					</ul>
				</li>
				{/BLC_DEPARTAMENTOPAI}
			</ul>
		</div>
	</div>
	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>