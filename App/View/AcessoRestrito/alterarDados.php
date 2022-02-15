<br>
<div class="card container-sm" style="width: 800px;">
  <div class="card-body">
    <h5 class="card-title">Alterar seus dados</h5>
    <?php
    if (isset($data['mensagens'])) {
      // se há mensagens de erro irá exibir uma mensagem de erro
      foreach ($data['mensagens'] as $mensagem) {
        echo '<div class="alert alert-danger" role="alert">' . $mensagem . "</div>";
      }
    }
    ?>
    <form action="/alterar" method="post">
      <input id="CSRF_token" type="hidden" name="CSRF_token" value="<?= $_SESSION['CSRF_token'] ?>">

      <div class="form-group">
        <label for="email">Email:</label>
        <input id="email" class="form-control" type="email" name="email" value="<?= $data['email'] ?>">
      </div>
      <br>
      <div class="form-group">
        <label for="nome">Nome:</label>
        <input id="nome" class="form-control" type="text" name="nome" value="<?= $data['nome'] ?>">
      </div>
      <br>

      <div class="form-group">
        <label for="senha">Senha Atual:</label>
        <input id="senha" class="form-control" type="password" name="senha" value="" placeholder="sua senha">
      </div>
      <div class="form-group">
        <label for="pergunta-senha">Nova Senha?</label>
        <input name="pergunta-senha" id="pergunta-senha" role="switch" class="form-check-input" type="checkbox">
      </div>
      <br>
      <div class="form-group" id="nova">
        <label for="senha">Nova Senha:</label>
        <input id="nova-senha" class="form-control" type="password" name="nova-senha" value="" placeholder="sua senha">
        <br>
      </div>

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