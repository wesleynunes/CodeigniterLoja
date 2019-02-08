<legend>
	Produtos
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar produtos" class="btn">Listar</a> <a
			href="{URLADICIONAR}" title="Adicionar produtos" class="btn"><em
			class="icon-plus"></em> Adicionar</a>
	</div>
</legend>
<table class="table table-bordered table-condensed">
	<tr>
		<th class="coluna-acao text-center"></th>
		<th style="width: 80px;">Atributos</th>
		<th style="width: 80px;">Imagens</th>
		<th style="width: 50px;">Código</th>
		<th>Nome</th>
		<th class="coluna-acao text-center"></th>
	</tr>
	{BLC_DADOS}
	<tr>
		<td class="alinha-centro"><a href="{URLEDITAR}" title="Editar"><em
				class="icon-pencil"></em></a></td>
		<td class="alinha-centro"><a href="{URLATRIBUTOS}"
			title="Vincular Atributos"><em class="icon-list-alt"></em></a> <a
			href="{URLVINCULAIMAGEMSKU}" title="Vincular imagens com atributos"><em
				class="icon-picture"></em></a></td>
		<td class="alinha-centro"><a href="{URLUPLOAD}"
			title="Upload de Imagens"><em class="icon-upload"></em></a></td>
		<td>{CODPRODUTO}</td>
		<td>{NOME}</td>
		<td class="alinha-centro"><a href="{URLEXCLUIR}" title="Excluir"
			class="link-excluir"><em class="icon-trash"></em></a></td>
	</tr>
	{/BLC_DADOS} {BLC_SEMDADOS}
	<tr>
		<td colspan="4" class="alinha-centro">Não há dados</td>
	</tr>
	{/BLC_SEMDADOS}
</table>
<div class="pagination pull-right">
	<ul>
		<li class="{HABANTERIOR}"><a href="{URLANTERIOR}">&laquo;</a>
			{BLC_PAGINAS}
		
		<li class="{LINK}"><a href="{URLLINK}">{INDICE}</a> {/BLC_PAGINAS}
		
		<li class="{HABPROX}"><a href="{URLPROXIMO}">&raquo;</a>
	
	</ul>
</div>