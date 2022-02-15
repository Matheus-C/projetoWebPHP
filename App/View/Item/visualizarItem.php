<div class="card container-sm" style="width: 800px;">
    <div class="card-body">
        <h5 class="card-title"><?= $data['nome_item'] ?></h5>
        <p class="card-text"><?= $data['descricao'] ?></p>
        <br><br>
        <p class="card-text">quantidade disponível <?= $data['quantidade'] ?></p>
        <div class="card-footer" style="position: relative;">
            <div class="row">
                <div class="col">
                    <p class="card-text col-sm" style="position: absolute;top: 50%;transform: translate(0, -50%);">Preço: <?= $data['preco'] ?></p>
                </div>
                <div class="form-group col">
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" class="form-control" min="1" max="<?= $data['quantidade'] ?>" required id="quantidade" name="quantidade">
                    <input type="hidden" value="<?= $data['hashid'] ?>" id="hashidProd" name="hashidProd">
                </div>
                <div class="col">
                    <?php if ($data['quantidade'] > 0) { ?>
                        <button id="btAdiciona" name="adiciona" class="btn btn-outline-success" style="width:100%;height:100%;">Adicionar ao carrinho</button>
                    <?php } else { ?>
                        <button id="btFora" name="btFora" disabled class="btn btn-danger" style="width:100%;height:100%;">Fora de estoque</button>
                    <?php } ?>
                </div>
            </div>
        </div>