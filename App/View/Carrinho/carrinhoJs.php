<script>
    $(document).ready(function() {


        // recuperar dados para mostrar na tabela

        var table = $('#table').DataTable({

            "language": {
                "url": "<?php echo PATH_URL_DATATABLE_LANG ?>" // colocar as mensagem em pt-br
            },

            "pageLength": 5,
            "sDom": "ltipr", //removendo a caixa de texto da pesquisa
            "lengthChange": false,
            "ordering": false, //removendo a ordenação
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',

            // Carregar a tabela por Ajax
            "ajax": {
                "url": "<?= url('carrinho') ?>",
                dataSrc: function(json) { // retorno dos dados por Ajax
                    if (json.CSRF_token !== undefined) { // acerta o CSRF_token
                        $('[name="CSRF_token"]').val(json.CSRF_token).trigger('change');
                    }
                    return json.data; // continua para exibição dos dados na tabala
                }
            },
            'columns': [{ // estrutura da tabela com as suas colunas na ordem correta
                    "visible": true,
                    data: 'nomeItem',
                    "width": "50%"
                },
                {
                    data: 'quantidade',
                    "width": "20%"
                },
                {
                    data: 'preco',
                    "width": "20%"
                },
                {
                    data: 'acoes',
                    orderable: false,
                    "width": "10%"
                }
            ]

        }); // fim datatable
        //----------------------------------------------------------------------------------------------
        // implementa a compra que vai esvaziar o carrinho e diminuir o número de produtos no estoque, e não houver inconsistencias
        $('#comprar').on('click', function() {

            Swal.fire({
                title: 'Confirma a Compra?',
                text: '',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar Compra'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('comprar') ?>",
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {

                            if (data.status) {
                                table.ajax.reload(null, false); //reload datatable ajax
                                $('#total').val(data.preco);
                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Compra Realizada Com Sucesso",
                                    icon: "success",
                                });

                            } else {
                                Swal.fire({
                                    title: "Erro",
                                    text: data.mensagem,
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
        //----------------------------------------------------------------------------------
        //Remover item do carrinho
        $('#table tbody').on('click', 'a.remover', function() {

            var id = $(this).attr("data-id");
            var nome = $(this).attr("data-nomeItem");
            var quantidade = $(this).attr("data-quantidade");
            var preco = $(this).attr("data-preco");

            Swal.fire({
                title: 'Confirma a Exclusão do Item?',
                text: nome,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('tirardocarrinho') ?>/" + id + "/" + quantidade + "/" + preco,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {

                            if (data.status) {
                                table.ajax.reload(null, false); //reload datatable ajax
                                $('#total').val(data.preco);

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
    });
</script>