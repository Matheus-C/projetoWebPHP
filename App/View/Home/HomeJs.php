<script>
    $(document).ready(function() {

        //recupera os dados para mostrar na tabela, se não estiver logado não surgirá a coluna de ações
        <?php if (isset($_SESSION['hashid'])) { ?>
            var table = $('#table').DataTable({

                "language": {
                    "url": "<?php echo PATH_URL_DATATABLE_LANG ?>" // colocar as mensagem em pt-br
                },

                "pageLength": 5,
                "lengthChange": false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'POST',

                // Carregar a tabela por Ajax
                "ajax": {
                    "url": "<?= url('ajaxitens') ?>",
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
                        "width": "20%"
                    },
                    {
                        data: 'descricao',
                        "width": "30%"
                    },
                    {
                        data: 'vendedor',
                        "width": "20%"
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
            });
        <?php } else { ?>
            var table = $('#table').DataTable({

                "language": {
                    "url": "<?php echo PATH_URL_DATATABLE_LANG ?>" // colocar as mensagem em pt-br
                },

                "pageLength": 5,
                "lengthChange": false,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'POST',

                // Carregar a tabela por Ajax
                "ajax": {
                    "url": "<?= url('ajaxitens') ?>",
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
                        "width": "30%"
                    },
                    {
                        data: 'vendedor',
                        "width": "20%"
                    },
                    {
                        data: 'quantidade',
                        "width": "10%"
                    },
                    {
                        data: 'preco',
                        "width": "10%"
                    }
                ]
            });
        <?php } ?>

        // fim datatable
        //------------------------------------------------------------
        //adiciona o produto ao carrinho
        $('#table tbody').on('click', 'a.adiciona_carrinho', function() {
            var id = $(this).attr("data-id");

            $.ajax({
                url: "<?= url('adicionar') ?>/" + id + "/1",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.status == true) {
                        Swal.fire({
                            title: "Sucesso",
                            text: "Item Adicionado Com Sucesso ",
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
                error: function() { // erro de processamento
                    Swal.fire({
                        title: "Erro",
                        text: "Erro Interno",
                        icon: "error",
                    });

                }
            });
        });
        //chama a tela para visualizar melhor o produto
        $('#table tbody').on('click', 'a.visualizar', function() {
            var id = $(this).attr("data-id");
            $(location).attr("href", "<?= URL_BASE ?>/visualizar/" + id);

        });
    });
</script>