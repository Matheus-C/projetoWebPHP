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

            // Carregar a tabela por Ajax
            "ajax": {
                "url": "<?= url('ajaxusuarios') ?>",
                dataSrc: function(json) { // retorno dos dados por Ajax
                    if (json.CSRF_token !== undefined) { // acerta o CSRF_token
                        $('[name="CSRF_token"]').val(json.CSRF_token).trigger('change');
                    }
                    return json.data; // continua para exibição dos dados na tabala
                }
            },
            'columns': [{ // estrutura da tabela com as suas colunas na ordem correta
                    "visible": true,
                    data: 'id',
                    "width": "30%"
                },
                {
                    data: 'nome',
                    "width": "30%"
                },
                {
                    data: 'email',
                    "width": "30%"
                },
                {
                    data: 'acoes',
                    orderable: false,
                    "width": "10%"
                }
            ]

        }); // fim datatable

        //-------------------------------------------------------------------

        // excluir o Usuário
        $('#table tbody').on('click', 'a.excluir', function() {

            var id = $(this).attr("data-id");
            var nome = $(this).attr("data-nome");

            Swal.fire({
                title: 'Confirma a Exclusão do Usuário?',
                text: nome,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirma Exclusão'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= url('excluirusuario') ?>/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {

                            if (data.status) {
                                table.ajax.reload(null, false); //reload datatable ajax

                                Swal.fire({
                                    title: "Sucesso",
                                    text: "Usuário Excluido Com Sucesso - " + nome,
                                    icon: "success",
                                });

                            } else {
                                if (typeof data.mensagem !== 'undefined') {
                                    Swal.fire({
                                        title: "Erro",
                                        text: data.mensagem,
                                        icon: "error",
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Erro",
                                        text: "Erro Inesperado",
                                        icon: "error",
                                    });
                                }
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