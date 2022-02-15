<div class="table-responsive">
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Vendedor</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <?php if (isset($_SESSION['hashid'])) { ?>
                    <th>Ações</th>
                <?php } ?>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>