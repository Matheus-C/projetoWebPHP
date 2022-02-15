<div class="card container-sm" style="width: 800px">
  <div class="card-body">
    <h5 class="card-title">Incluir novo item</h5>
    <?php
    if (isset($data['mensagens'])) {
      // se há mensagens de erro irá exibir uma mensagem de erro
      foreach ($data['mensagens'] as $mensagem) {
        echo '<div class="alert alert-danger" role="alert">' . $mensagem . "</div>";
      }
    }
    ?>
    <form action="<?= URL_BASE . '/novoitem' ?>" method="post">
      <input id="CSRF_token" type="hidden" name="CSRF_token" value="<?= $_SESSION['CSRF_token'] ?>">
      <br>
      <div class="form-group">
        <label for="nome">Nome do item</label>
        <input id="nome" class="form-control" type="text" name="nome" value="" placeholder="nome do produto">
      </div>
      <br>
      <div class="form-group">
        <label for="descricao">Descrição do item</label>
        <textarea id="descricao" class="form-control" name="descricao" value="" placeholder="escreva a descrição aqui"></textarea>
      </div>
      <br>
      <div class="form-group">
        <label for="quantidade">Quantidade Disponível</label>
        <input id="quantidade" class="form-control" type="number" name="quantidade" min="1">
      </div>
      <br>
      <div class="form-group">
        <label for="preco">Preço por unidade (R$)</label>
        <input id="preco" class="form-control preco" type="text" name="preco" placeholder="50,00">
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
      <input type="hidden" value=<?= $_SESSION['hashid'] ?> id="idVendedor" name="idVendedor">

      <div class="form-group">
        <button id="submit-incluir" type="submit" class="btn btn-primary">Registrar</button>
      </div>
    </form>
  </div>
</div>