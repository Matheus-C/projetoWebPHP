<button type="button" id="btIncluir" class="btn btn-outline-primary mb-1">
    Novo Item
</button>
<div class="table-responsive">
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal alteracao de item-->
<div class="modal fade" id="modalAlterarItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?= url('gravarInclusao') ?>" id="formAlterar" method="POST">

                    <div id="mensagem_erro_alteracao" name="mensagem_erro_alteracao"></div>

                    <input type="hidden" id="CSRF_token" name="CSRF_token" value="" />
                    <input type="hidden" id="hashid" name="hashid" value="" />

                    <div class="form-group">
                        <label for="nome">Nome*</label>
                        <input type="text" class="form-control" id="nome" name="nome">
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição*</label>
                        <input type="text" class="form-control" id="descricao" name="descricao">
                    </div>
                    <div class="form-group">
                        <label for="preco">Preco*</label>
                        <input type="text" class="form-control preco" id="preco" name="preco">
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Quantidade*</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade">
                    </div>
                    <div class="form-group" id="img_captcha"></div>
                    <br>
                    <div class="form-group">
                        <input id="captcha" class="form-control" type="text" name="captcha" placeholder="Digite o código acima">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btSalvarAlteracao" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>