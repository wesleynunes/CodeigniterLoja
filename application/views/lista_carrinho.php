<div class="row-fluid">
    <div class="span12">
        <legend>Pedidos</legend>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <table class="table">
            <thead>
                <td>Código do Pedido</td>
                <td>Data</td>
                <td>Valor</td>
            </thead>
            <tbody>
        {BLC_DADOS}
            <tr>
                <td><a href="{URLRESUMO}">{CODPEDIDO} / {DATA}</a></td>
                <td><a href="{URLRESUMO}">{VALOR}</a></td>
            </tr>
        {/BLC_DADOS}
            </tbody>
        </table>
    </div>
</div>
{BLC_PAGINACAO}
<hr>
<div class="pagination">
	<ul>
		{BLC_PAGINA}
		<li><a href="{URLPAGINA}">{INDICE}</a></li> {/BLC_PAGINA}
	</ul>
</div>
{/BLC_PAGINACAO}