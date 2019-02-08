<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <title>Painel Administração da Loja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
    <link href="<?php echo base_url('assets/css/bootstrap-responsive.min.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/dashboard.css')?>" rel="stylesheet">
	
	<link href="<?php echo base_url('assets/css/jquery.fileupload.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/jquery.fileupload-ui.css')?>" rel="stylesheet">
	
	<link href="<?php echo base_url('assets/css/select2.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/select2-bootstrap.css')?>" rel="stylesheet">
	
	<noscript>
		<link href="<?php echo base_url('assets/css/jquery.fileupload-noscript.css')?>" rel="stylesheet">
	</noscript>
	<noscript>
		<link href="<?php echo base_url('assets/css/jquery.fileupload-ui-noscript.css')?>" rel="stylesheet">
	</noscript>	
	<link href="<?php echo base_url('assets/css/datepicker.css')?>" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Meu eCommerce: Administração</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logado como <a href="#" class="navbar-link">{NOMEUSUARIOLOGIN}</a>
            </p>
            <ul class="nav">
              <li><a href="<?php echo site_url('painel/login/sair');?>" title="Sair">Sair</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              	<li class="nav-header">Gerência</li>
              	<li><a href="<?php echo site_url('painel/usuario')?>" title="Usuários">Usuário</a></li>
              	<li class="nav-header">Produtos</li>
              	<li><a href="<?php echo site_url('painel/departamento')?>" title="Departamentos">Departamento</a></li>
            	<li><a href="<?php echo site_url('painel/tipoatributo')?>" title="Tipo de Atributo">Tipo de Atributo</a></li>
            	<li><a href="<?php echo site_url('painel/atributo')?>" title="Atributos">Atributos</a></li>
            	<li><a href="<?php echo site_url('painel/produto')?>" title="Produtos">Produtos</a></li>
                <li class="nav-header">Compradores</li>
                <li><a href="<?php echo site_url('painel/comprador')?>" title="Compradores">Compradores</a></li>
                <li class="nav-header">Venda</li>
                <li><a href="<?php echo site_url('painel/vitrine')?>" title="Vitrine">Vitrine</a></li>
                <li><a href="<?php echo site_url('painel/formaentrega')?>" title="Formas de Entrega">Formas de Entrega</a></li>
                <li><a href="<?php echo site_url('painel/formapagamento')?>" title="Formas de Pagamento">Formas de Pagamento</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
        	{MENSAGEM_SISTEMA_ERRO}
        	{MENSAGEM_SISTEMA_SUCESSO}
          	{CONTEUDO}
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>

    </div><!--/.fluid-container-->
    
    <input type="hidden" id="siteURL" value="<?php echo site_url();?>">

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('assets/js/jquery-1.10.2.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.ui.widget.js')?>"></script>
    <script src="<?php echo base_url('assets/js/tmpl.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/load-image.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/canvas-to-blob.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.blueimp-gallery.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.iframe-transport.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-process.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-image.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-validate.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.fileupload-ui.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.maskedinput.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.maskMoney.js')?>"></script>
    <script src="<?php echo base_url('assets/js/select2.min.js')?>"></script>
    <script src="<?php echo base_url('assets/js/select2_locale_pt-BR.js')?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap-datepicker.js')?>"></script>
    <script src="<?php echo base_url('assets/js/dashboard.js')?>"></script>
  </body>
</html>
