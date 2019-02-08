<div class="row-fluid">
    <div class="span12">
        <legend>Meu Carrinho</legend>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th colspan="2">Produto</th>
                    <th>Quantidade</th>
                    <th></th>
                    <th>Valor Un.</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                {BLC_PRODUTOS}
                <tr>
                    <td><img src="{URLFOTO}"></td>
                    <td>{NOMEPRODUTO}</td>
                    <td>{QUANTIDADE}</td>
                    <td><a href="{URLAUMENTAQTD}" title="Aumentar"><em class="icon-plus"></em></a><a href="{URLDIMINUIQTD}" title="Diminuir"><em class="icon-minus"></em></a> | <a href="{URLREMOVEQTD}" title="Remover"><em class="icon-trash"></em></a></td>
                    <td>R$ {VALOR}</td>
                    <td>R$ {VALORTOTAL}</td>
                </tr>
                {/BLC_PRODUTOS}
                {BLC_SEMPRODUTOS}
                <tr>
                    <td colspan="6">Seu carrinho está vazio.</td>
                </tr>
                {/BLC_SEMPRODUTOS}
                {BLC_FINALIZAR}
                <tr>
                    <td colspan="6"><p class="text-right"><a href="{URLFINALIZAR}" title="Finalizar o carrinho de compras" class="btn btn-success">Finalizar</a></p></td>
                </tr>
                {/BLC_FINALIZAR}
            </tbody>
        </table>
    </div>
</div>