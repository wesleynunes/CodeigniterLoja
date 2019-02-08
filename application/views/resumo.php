<div class="row-fluid">
	<div class="span12">
		<legend>Resumo do Pedido: {CODCARRINHO}</legend>
	</div>
</div>
<div class="row-fluid">
	<div class="span12">
		<table>
			<tr>
				<td>Data:</td>
				<td>{DATA}</td>
			</tr>
			<tr>
				<td>Forma Pagamento:</td>
				<td>{NOMEFORMAPAGAMENTO}</td>
			</tr>
			<tr>
				<td>Forma Entrega:</td>
				<td>{NOMEFORMAENTREGA}</td>
			</tr>
			<tr>
				<td>Valor:</td>
				<td>{VALORFINALCOMPRA}</td>
			</tr>
		</table>
		<hr>
		<table class="table">
			<thead>
				<td>Produto</td>
				<td>Quantidade</td>
				<td>Valor Un.</td>
				<td>Valor Total.</td>
			</thead>
			<tbody>
				{BLC_DADOS}
				<tr>
					<td>{NOMEPRODUTO}</td>
					<td>{QTD}</td>
					<td>{VLRUN}</td>
					<td>{VLRTOTAL}</td>
				</tr>
				{/BLC_DADOS}
			</tbody>
		</table>
	</div>
</div>
{BLC_SHOWBOLETO}
<div class="row-fluid">
	<div class="span12">
		<a href="{URLBOLETO}" class="btn btn-large btn-success">Imprimir
			Boleto</a>
	</div>
</div>
{/BLC_SHOWBOLETO}
