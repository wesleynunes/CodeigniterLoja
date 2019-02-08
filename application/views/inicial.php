<div class="row-fluid">
	<div class="span2">
		<div class="departamento">
			{BLC_DEPARTAMENTOS}
			<h4>
				<a href="{URLDEPARTAMENTO}" title="{NOMEDEPARTAMENTO}">{NOMEDEPARTAMENTO}</a>
			</h4>
			<ul class="nav nav-tabs nav-stacked">
				{BLC_DEPARTAMENTOSFILHOS}
				<li><a href="{URLDEPARTAMENTO_FILHO}"
					title="{NOMEDEPARTAMENTO_FILHO}">{NOMEDEPARTAMENTO_FILHO}</a></li>
				{/BLC_DEPARTAMENTOSFILHOS}
			</ul>
			{/BLC_DEPARTAMENTOS}
		</div>
	</div>
	<div class="span10">
		{BLC_ORDENACAO}
		<div class="row-fluid">
			<div class="span6">Exibindo {ITENSEXIBICAO} de {TOTALITENS} produtos
			</div>
			<div class="span6">
				Ordenar por: <a href="{URLATUAL}&oc=nome&to=asc">De A-Z</a> | <a
					href="{URLATUAL}&oc=nome&to=desc">De Z-A</a> | <a
					href="{URLATUAL}&oc=valor&to=asc">Menor Preço</a> | <a
					href="{URLATUAL}&oc=valor&to=desc">Maior Preço</a>
			</div>
		</div>
		<hr>
		{/BLC_ORDENACAO} {LISTAGEM} {BLC_PAGINACAO}
		<hr>
		<div class="pagination">
			<ul>
				{BLC_PAGINA}
				<li><a href="{URLPAGINA}">{INDICE}</a></li> {/BLC_PAGINA}
			</ul>
		</div>
		{/BLC_PAGINACAO}
	</div>
</div>