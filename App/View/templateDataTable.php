<!DOCTYPE html>
<html>

<head>
  <title><?= $data['titulo'] ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="shortcut icon" href="#">
  <link href="<?= URL_CSS ?>bootstrap.min.css" rel="stylesheet">

  <link href="<?= PATH_URL_DATATABLE_bootstrap4 ?>" rel="stylesheet">
  <link href="<?= PATH_URL_DATATABLE_Responsive ?>" rel="stylesheet">
  <!-- css para quebrar linhas das colunas da tabela caso o texto seja muito grande -->
  <style>
    tbody>tr>td {
      word-break: break-all;
    }
  </style>

</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div id="menu" class="container-fluid">
      <a class="navbar-brand" href="<?= URL_BASE ?>">ShopNow</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="/home">Home</a></li>
          <?php if (!isset($_SESSION['hashid'])) { ?>
            <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/login">login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/registro">Registrar</a></li>
          <?php } elseif ($_SESSION['tipoUser'] == 0) { ?>
            <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/usuarios">Usuários</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/painelitens">Meus itens à venda</a></li>
            <li class="nav-item"><a class="nav-link" href="/carrinho">Meu Carrinho</a></li>
            <li class="nav-item"><a class="nav-link" href="/alterar">Alterar Dados</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/logout">Logout</a></li>
        </ul>
        <span class="navbar-text ml-auto">Olá <?= $_SESSION['nomeUsuario'] ?></span>
      <?php } else { ?>
        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/painelitens">Meus itens à venda</a></li>
        <li class="nav-item"><a class="nav-link" href="carrinho">Meu Carrinho</a></li>
        <li class="nav-item"><a class="nav-link" href="/alterar">Alterar Dados</a></li>

        <li class="nav-item"><a class="nav-link" href="<?= URL_BASE ?>/logout">Logout</a></li>
        </ul>
        <span class="navbar-text ml-auto">Olá <?= $_SESSION['nomeUsuario'] ?></span>
      <?php } ?>

      </div>
    </div>
  </nav>
  <!-- Conteúdo da Página -->
  <div class="container" style="padding-bottom: 80px;padding-top: 40px;">
    <!-- Vai inserir a view no template que será passada por parâmetro -->
    <?php require_once VIEW_ROOT . $view . '.php' ?>
  </div>
  <div id="inferior" class="container-fluid fixed-bottom bg-info">
    <footer id="foot">
      <p id="nome">Matheus Custódio Pereira de Oliveira</p>
    </footer>
  </div>
  <!-- scripts básicos para as páginas -->

  <script src="<?= URL_JS ?>jquery-3.4.1.min.js"></script>
  <script src="<?= URL_JS ?>popper.min.js"></script>
  <script src="<?= URL_JS ?>bootstrap.min.js"></script>
  <script src="<?= URL_JS ?>sweetalert2/sweetalert2.js"></script>
  <script src="<?= URL_JS ?>jQuery-Mask-Plugin-master/src/jquery.mask.js"></script>
  <!-- Scripts específicos para datatable -->

  <script src="<?= PATH_URL_DATATABLE_dataTablesminjs ?>"> </script>
  <script src="<?= PATH_URL_DATATABLE_dataTablesbootstrap4minjs ?>"> </script>
  <script src="<?= PATH_URL_DATATABLE_dataTablesresponsiveminjs ?>"> </script>
  <script src="<?= PATH_URL_DATATABLE_responsivebootstrap4minjs ?>"> </script>

  <?php  // verifica ser existe inclusão de script js

  if ($js != null) :
    require_once $js . '.php';
  endif;

  ?>
  <script>
    $(document).ready(function() {
      $('.preco').mask('#0.00', {
        reverse: true
      });
    });
  </script>
</body>

</html>