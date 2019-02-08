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
		<h3>Entrega</h3>
		<table>
			<tr>
				<td>Comprador:</td>
				<td>{NOMECOMPRADOR}</td>
			</tr>
			<tr>
				<td>Endereço:</td>
				<td>{ENDERECOCOMPRADOR} / {CEPCOMPRADOR}</td>
			</tr>
			<tr>
				<td>Cidade:</td>
				<td>{CIDADECOMPRADOR} / {UFCOMPRADOR}</td>
			</tr>
		</table>
		<form action="{URLSITUACAO}" method="post" class="form-inline">
		  <input type="hidden" name="codcarrinho" value="{CODCARRINHO}">
		  <select name="situacao">
		      <option value="A" {SITUACAO_A}>Aguardando Confirmação de Pagamento</option>
		      <option value="T" {SITUACAO_T}>Enviado</option>
		      <option value="E" {SITUACAO_E}>Entregue</option>
		      <option value="C" {SITUACAO_C}>Cancelado</option>
		  </select>
		  <button class="btn btn-success">Atualizar</button>
		</form>
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
