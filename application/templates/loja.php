<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <title>{TITLE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/loja.css')?>" rel="stylesheet">
  </head>
  <body>
    <div class="container">
        <div class="row-fluid linha-topo-menu">
            <div class="span12">
                <ul class="menu-superior">
                    <li><a href="<?php echo site_url(); ?>" title="Ir para a página inicial">Inicial</a></li>
                    <li>Olá <strong>{NOMECLIENTE}</strong></li>
                    <li><a href="" title="Acessar sua conta">Minha Conta</a></li>
                    <li><a href="<?php echo site_url('checkout'); ?>" title="Meu Carrinho">Meu Carrinho</a></li>
                    <naologado>
                    <li><a href="<?php echo site_url('conta/login'); ?>" title="Login">Login</a></li>
                    <li><a href="<?php echo site_url('conta/novaconta'); ?>" title="Realizar cadastro na loja">Cadastrar-se</a></li>
                    </naologado>
                    <logado>
                    <li><a href="<?php echo site_url('conta/pedidos'); ?>" title="Verifique seus pedidos">Meus Pedidos</a></li>
                    <li><a href="<?php echo site_url('conta/sair'); ?>" title="Sair da loja">Sair</a></li>
                    </logado>
                </ul>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span4">
                <a href="<?php echo site_url(); ?>" title="Ir para a página inicial"><h1>Minha Loja</h1></a>
            </div>
            <div class="span6">
                <form method="get" action="{URLBUSCA}">
                    <input type="text" name="pesquisa" class="pull-left" id="pesquisa" placeholder="Pesquisar produtos">
                    <button type="submit" class="btn" class="pull-right">Pesquisar</button>
                </form>
            </div>
            <div class="span2">
                <a href="">Meu Carrinho</a>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                {MENSAGEM_SISTEMA_ERRO}
                {MENSAGEM_SISTEMA_SUCESSO}
                {CONTEUDO}
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('assets/js/jquery-1.10.2.min.js')?>"></script>
    
    <script src="<?php echo base_url('assets/js/jquery.maskedinput.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.maskMoney.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.elevateZoom-3.0.8.min.js')?>"></script>
    
    <script src="<?php echo base_url('assets/js/loja.js')?>"></script>
  </body>
</html>