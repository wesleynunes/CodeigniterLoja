<legend>
	Imagens do Produto: {NOMEPRODUTO}
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar produtos" class="btn">Listar</a>
	</div>
</legend>
<form action="{URLSALVAFOTOATRIBUTO}" method="post">
	<input type="hidden" name="codproduto" value="{CODPRODUTO}">

	<table class="table table-bordered table-condensed">
		<tr>
			<th style="width: 80px;">Imagem</th>
			<th>Vincular com Atributos</th>
			<th style="text-align:center; width:100px;">Remover</th>
		</tr>
		{BLC_FOTOS}
		<tr>
			<td><img src="{URLIMAGEM}"></td>
			<td><select name="skus[{CODPRODUTOFOTO}][]" multiple="multiple" class="select2" style="width: 90%;">
					{BLC_SKUSPRODUTO}
					<option value="{CODSKU}" {SEL_SKU}>{NOMESKU}</option> {/BLC_SKUSPRODUTO}
			</select>
		    <td style="text-align:center;"><input type="checkbox" name="remover[{CODPRODUTOFOTO}]" value="S">
		</tr>
		{/BLC_FOTOS}
		{BLC_SEMFOTOS}
		<tr>
			<td colspan="2">Não foram encontradas fotos para este produto.</td>
		</tr>
		{/BLC_SEMFOTOS}
	</table>

	<div class="well">
		<button type="submit" class="btn">Salvar</button>
	</div>
</form>