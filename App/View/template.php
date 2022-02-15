<!DOCTYPE html>
<html>

<head>
  <title><?= $data['titulo'] ?></title>
  <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
        <li class="nav-item"><a class="nav-link" href="/carrinho">Meu Carrinho</a></li>
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