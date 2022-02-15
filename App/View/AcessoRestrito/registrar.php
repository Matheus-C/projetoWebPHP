<div class="card container-sm" style="width: 800px;">
  <div class="card-body">
    <h5 class="card-title">Registrar</h5>
    <br>
    <?php
    // se há mensagens de erro irá exibir uma mensagem de erro
    if (isset($data['mensagens'])) {
      foreach ($data['mensagens'] as $mensagem) {
        echo '<div class="alert alert-danger" role="alert">' . $mensagem . "</div>";
      }
    }
    ?>
    <form action="/registrar" method="post">
      <input id="CSRF_token" type="hidden" name="CSRF_token" value="<?= $_SESSION['CSRF_token'] ?>">
      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" class="form-control" type="email" name="email" value="" placeholder="seunome@seuemail.com">
      </div>
      <br>
      <div class="form-group">
        <label for="nome">Nome</label>
        <input id="nome" class="form-control" type="text" name="nome" value="" placeholder="seu nome">
      </div>
      <br>
      <div class="form-group">
        <select class="form-select" id="tipoUser" name="tipoUser">
          <option selected>Selecione o tipo de utilizador</option>
          <option value="0">Administrador</option>
          <option value="1">Usuário comum</option>
        </select>
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
        <button type="submit" class="btn btn-primary">Registrar</button>
      </div>

    </form>
  </div>
</div>