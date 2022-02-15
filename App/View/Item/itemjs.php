<script>
    $(document).ready(function() {

        // recuperar dados para mostrar na tabela

        var table = $('#table').DataTable({

            "language": {
                "url": "<?php echo PATH_URL_DATATABLE_LANG ?>" // colocar as mensagem em pt-br
            },

            "pageLength": 5,
            "lengthChange": false,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'columnDefs': {
                "max-width": "40%",
                'targets': 1
            },

            // Carregar a tabela por Ajax
            "ajax": {
                "url": "<?= url('meusitens') ?>",
                dataSrc: function(json) { // retorno dos dados por Ajax
                    if (json.CSRF_token !== undefined) { // acerta o CSRF_token
                        $('[name="CSRF_token"]').val(json.CSRF_token).trigger('change');
                    }
                    return json.data; // continua para exibição dos dados na tabala
                }
            },
            'columns': [{ // estrutura da tabela com as suas colunas na ordem correta
                    "visible": true,
                    data: 'nome_item',
                    "width": "30%"
                },
                {
                    data: 'descricao',
                    "width": "40%",
                },
                {
                    data: 'quantidade',
                    "width": "10%"
                },
                {
                    data: 'preco',
                    "width": "10%"
                },
                {
                    data: 'acoes',
                    orderable: false,
                    "width": "10%"
                }
            ]

        }); // fim datatable

        //------------------------------------------------------------------------------------

        // recuperar dados do Item para alteração

        $('#table tbody').on('click', 'a.selecionar_editar', function() {

            var hashid = $(this).attr("data-id");
            $.ajax({
                url: "<?= url('ajaxgetitem') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    'hashid': hashid,
                    'CSRF_token': $('[name="CSRF_token"]').val()
                },
                success: function(json) {
                    // acertando os dados para exibição no form
                    $('[name="CSRF_token"]').val(json.CSRF_token).trigger('change');
                    $('[name="hashid"]').val(json.hashid).trigger('change');
                    $('[name="nome"]').val(json.nome).trigger('change');
                    $('[name="descricao"]').val(json.descricao).trigger('change');
                    $('[name="quantidade"]').val(json.quantidade).trigger('change');
                    $('[name="preco"]').val(json.preco).trigger('change');
                    $('#img_captcha').empty(); // apaga todo o conteudo da div do captcha
                    $('#img_captcha').append(json.imagem); // insere a img do capctha
                    // abre o modal com os dados para alteração
                    $("#modalAlterarItem").modal('show');
                },
                error: function() { // erro de processamento
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });

                }
            })
        })

        //----------------------------------------------------------------------------------
        // salvar dados alterados do Item

        $('#btSalvarAlteracao').on('click', function() {
            $.ajax({
                url: "<?= url('gravaralteracaoitem') ?>",
                type: "POST",
                data: $('#formAlterar').serialize(),
                dataType: "JSON",
                success: function(data) {

                    // Update CSRF hash

                    if (data.status) //if successo fecha modal and reload ajax table e apresenta mensagem
                    {
                        $("#modalAlterarItem").modal('hide'); //  fecha modal
                        table.ajax.reload(null, false); //reload datatable ajax
                        Swal.fire({
                            title: "Sucesso",
                            text: "Item Alterado Com Sucesso",
                            icon: "success",
                        });

                    } else { // validação de dados com erros

                        $('[name="CSRF_token"]').val(data.CSRF_token).trigger('change');
                        $('[name="mensagem_erro_alteracao"]').addClass('alert alert-danger');
                        $('[name="mensagem_erro_alteracao"]').html(data.erros);
                        $('#img_captcha').empty(); // apaga todo o conteudo da div do captcha
                        $('#img_captcha').append(data.imagem); // insere a img do captcha

                    }
                },
                error: function(data) { // algo deu errado fecha modal e apresenta mensagem

                    $("#modalAlterarUsuario").modal('hide');

                    Swal.fire({
                        title: "Erro",
                        text: "Erro Inesperado",
                        icon: "error",
                    });


                }
            });
        })

        //---------------------------------------------------------------------------------

        // excluir o Item
        $('#table tbody').on('click', 'a.excluir', function() {

            var id = $(this).attr("data-id");
            var nome = $(this).attr("data-nomeItem");

            Swal.fire({
                title: 'Confirma a Exclusão do Item?',
                text: nome,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirma Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('excluiritem') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {

                            if (data.status) {
                                table.ajax.reload(null, false); //reload datatable ajax

                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Item Excluido Com Sucesso - " + nome,
                                    icon: "success",
                                });

                            } else {
                                Swal.fire({
                                    title: "Erro",
                                    text: "Erro Inesperado",
                                    icon: "error",
                                });
                            }
                        },
                        error: function(data) {
                            Swal.fire({
                                title: "Erro",
                                text: "Erro Inesperado",
                                icon: "error",
                            });
                        }
                    });
                }
            })
        })

        //-------------------------------------------------------------

        // incluir novo Item
        $('#btIncluir').on('click', function() {

            location.href = "<?= URL_BASE ?>/registraritem";

        })


    });
</script>