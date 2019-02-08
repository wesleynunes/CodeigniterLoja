<legend>
Atributos do Produto: {NOMEPRODUTO}
	<div class="pull-right">
 		<a href="{URLLISTAR}" title="Listar produtos" class="btn">Listar</a>
	</div>
</legend>
<form action="{URLSALVAATRIBUTO}" method="post">
<input type="hidden" name="codproduto" value="{CODPRODUTO}">
<h5>SKUs Vinculados</h5>

<table class="table table-bordered table-condensed">
	<tr>
		<th style="width:80px;">Referência</th>
		<th>Descrição</th>
		<th style="width:50px;">Quantidade</th>
		<th class="coluna-acao text-center">Remover</th>
	</tr>
	{BLC_SEMVINCULADOS}
	<tr>
		<td colspan="4">Não há SKUs vinculados.</td>
	</tr>
	{/BLC_SEMVINCULADOS}
	{BLC_VINCULADOS}
	<tr>
		<td><input type="text" class="input input-small" name="sku[{CODSKU}][referencia]" value="{REFERENCIA}"></td>
		<td>{DESCRICAO}</td>
		<td><input type="text" class="input input-small set-integer" name="sku[{CODSKU}][quantidade]" value="{QUANTIDADE}"></td>
		<td><input type="checkbox" name="sku[{CODSKU}][remover]" value="S"></td>
	</tr>
	{/BLC_VINCULADOS}
</table>

<h5>Atributos Disponíveis</h5>

<table class="table table-bordered table-condensed">
	<tr>
		<th style="width:80px;">Referência</th>
		<th>Descrição</th>
		<th style="width:50px;">Quantidade</th>
	</tr>
	{BLC_SEMDISPONIVEIS}
	<tr>
		<td colspan="3">Não há atributos disponíveis para este produto.</td>
	</tr>
	{/BLC_SEMDISPONIVEIS}
	{BLC_DISPONIVEIS}
	<tr>
		<td><input type="text" class="input input-small" name="atributo[{CODATRIBUTO}][referencia]" value=""></td>
		<td>{DESCRICAO}</td>
		<td><input type="text" class="input input-small set-integer" name="atributo[{CODATRIBUTO}][quantidade]" value=""></td>
	</tr>
	{/BLC_DISPONIVEIS}
</table>

<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>