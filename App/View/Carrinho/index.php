<div class="table-responsive">
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Quantidade</th>
                <th>Preco</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <br>
    <br>
    <div class="col-9">
        <label for="total">Total: R$</label> <input disabled id="total" name="total" value="<?= $_SESSION['carrinho']->getTotal() ?>">
        <br>
        <br>
        <button class="btn-success" id="comprar">Comprar agora</button>
    </div>
</div>