<legend>
Formas de Pagamento
	<div class="pull-right">
 		<a href="{URLLISTAR}" title="Listar formas de pagamento" class="btn">Listar</a> 
 		<a href="{URLADICIONAR}" title="Adicionar formas de pagamento" class="btn"><em class="icon-plus"></em> Adicionar</a>
	</div>
</legend>
<table class="table table-bordered table-condensed">
	<tr>
		<th class="coluna-acao text-center"></th>
		<th>Nome</th>
		<th style="width:50px;">Habilitado</th>
		<th style="width:150px;">Máximo de Parcelas</th>
		<th class="coluna-acao text-center"></th>
	</tr>
	{BLC_DADOS}
	<tr>
		<td class="alinha-centro"><a href="{URLEDITAR}" title="Editar"><em class="icon-pencil"></em></a></td>
		<td>{NOME}</td>
		<td>{HABILITADO}</td>
		<td>{MAXIMOPARCELA}</td>
		<td class="alinha-centro"><a href="{URLEXCLUIR}" title="Excluir" class="link-excluir"><em class="icon-trash"></em></a></td>
	</tr>
	{/BLC_DADOS}
	{BLC_SEMDADOS}
	<tr>
		<td colspan="5" class="alinha-centro">Não há dados</td>
	</tr>
	{/BLC_SEMDADOS}
</table>
<div class="pagination pull-right">
	<ul>
		<li class="{HABANTERIOR}"><a href="{URLANTERIOR}">&laquo;</a>
		{BLC_PAGINAS}
		<li class="{LINK}"><a href="{URLLINK}">{INDICE}</a>
		{/BLC_PAGINAS}
		<li class="{HABPROX}"><a href="{URLPROXIMO}">&raquo;</a>
	</ul>
</div>