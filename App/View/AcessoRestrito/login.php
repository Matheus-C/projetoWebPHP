<div class="card container-sm" style="width: 800px;">
  <div class="card-body">
    <h5 class="card-title">Login</h5>
    <br>
    <?php
    if (isset($data['mensagens'])) {
      // se há mensagens de erro irá exibir uma mensagem de erro
      foreach ($data['mensagens'] as $mensagem) {
        echo '<div class="alert alert-danger" role="alert">' . $mensagem . "</div>";
      }
    }
    ?>
    <form action="<?= URL_BASE . '/logar' ?>" method="post">
      <input id="CSRF_token" type="hidden" name="CSRF_token" value="<?= $_SESSION['CSRF_token'] ?>">
      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" class="form-control" type="email" name="email" value="" placeholder="seunome@seuemail.com">
      </div>
      <br>
      <div class="form-group">
        <label for="senha">Senha</label>
        <input id="senha" class="form-control" type="password" name="senha" value="" placeholder="sua senha">
      </div>
      <br>
      <div class="form-group">
        <?php echo $data['imagem'] ?>
      </div>
      <br>
      <div class="form-group">
        <input id="captcha" class="form-control" type="text" name="captcha" placeholder="Digite o código acima">
      </div>
      <br>
      <div class="form-group">
        <button type="submit" class="btn btn-primary">Logar</button>
      </div>


    </form>
  </div>
</div>