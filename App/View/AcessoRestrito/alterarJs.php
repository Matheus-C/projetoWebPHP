<script>
    $(document).ready(function() { //função que mostra e esconde o campo de nova senha
        $('#nova').hide();
        $('#pergunta-senha').on('click', function() {
            if ($(this).is(":checked")) {
                $('#nova').show();
            } else {
                $('#nova').hide();
            }

        });
    });
</script>