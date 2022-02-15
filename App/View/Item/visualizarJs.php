<script>
    //adiciona o item no carrinho com a quantidade especificada
    $(document).ready(function() {
        $('#btAdiciona').on('click', function() {
            var id = $('#hashidProd').attr("value");
            var quantidade = $('#quantidade').val();
            if (quantidade == "") {
                Swal.fire({
                    title: "Erro",
                    text: "preencha o campo quantidade",
                    icon: "error",
                });
            } else {
                $.ajax({
                    url: "<?= url('adicionar') ?>/" + id + "/" + quantidade,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status) {
                            console.log(data.a);
                            Swal.fire({
                                title: "Sucesso",
                                text: "Item Adicionado Com Sucesso",
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
                    error: function() { // erro de processamento ou caso o usuário tente colocar 
                        //mais itens que sua quantidade
                        Swal.fire({
                            title: "Erro",
                            text: "Erro Interno",
                            icon: "error",
                        });

                    }
                });
            }
        })
    });
</script>