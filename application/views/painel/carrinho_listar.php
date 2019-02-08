<legend>
	Carrinhos
	<div class="pull-right">
		<a href="{URLLISTAR}" title="Listar carrinhos" class="btn">Listar</a>
	</div>
</legend>
<table class="table table-bordered table-condensed">
	<thead>
		<td>Código do Pedido</td>
		<td>Data</td>
		<td>Valor</td>
	</thead>
	<tbody>
		{BLC_DADOS}
		<tr>
			<td><a href="{URLRESUMO}">{CODPEDIDO}</a></td>
			<td><a href="{URLRESUMO}">{DATA}</a></td>
			<td><a href="{URLRESUMO}">{VALOR}</a></td>
		</tr>
		{/BLC_DADOS}
	</tbody>
	{BLC_SEMDADOS}
	<tr>
		<td colspan="3" class="alinha-centro">Não há dados</td>
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