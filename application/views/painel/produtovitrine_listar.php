<legend>
Itens da Vitrine: {NOMEVITRINE}
	<div class="pull-right">
 		<a href="{URLVOLTAR}" title="Listar vitrines" class="btn">Voltar</a> 
 		<a href="{URLLISTAR}" title="Listar vitrines" class="btn">Listar</a> 
 		<a href="{URLADICIONAR}" title="Adicionar vitrines" class="btn"><em class="icon-plus"></em> Adicionar</a>
	</div>
</legend>
<table class="table table-bordered table-condensed">
	<tr>
		<th>Nome</th>
		<th class="coluna-acao text-center"></th>
		<th class="coluna-acao text-center"></th>
		<th class="coluna-acao text-center"></th>
	</tr>
	{BLC_DADOS}
	<tr>
		<td>{NOME}</td>
		<td class="alinha-centro">
		  {BLC_UP}
		      <a href="{URLUP}" title="Subir na exibição"><em class="icon-chevron-up"></em></a>
		  {/BLC_UP}
		</td>
		<td class="alinha-centro">
		  {BLC_DOWN}
		      <a href="{URLDOWN}" title="Rebaixar na exibição"><em class="icon-chevron-down"></em></a>
		  {/BLC_DOWN}
		</td>
		<td class="alinha-centro"><a href="{URLEXCLUIR}" title="Excluir" class="link-excluir"><em class="icon-trash"></em></a></td>
	</tr>
	{/BLC_DADOS}
	{BLC_SEMDADOS}
	<tr>
		<td colspan="4" class="alinha-centro">Não há dados</td>
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