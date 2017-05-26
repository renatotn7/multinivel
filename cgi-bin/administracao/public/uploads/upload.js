$(document).ready(function() {

    $(document).on('click', '#btnEnviar', function() {
        $('#formUpload').ajaxForm({
            uploadProgress: function(event, position, total, percentComplete) {
                $('.bar').attr('value', percentComplete);
                $('.bar').html(percentComplete + '%');
            },
            success: function(data) {
                $('progress').attr('value', '100');
                $('#porcentagem').html('100%');
                if (data.sucesso == true) {
                    $('#resposta').html('<img src="' + data.msg + '" />');
                }
                else {
                    $('#resposta').html(data.msg);
                }
            },
            error: function() {
                $('#resposta').html('Erro ao enviar requisição!!!');
            },
            dataType: 'json',
            url: 'enviar_arquivo.php',
            resetForm: true
        }).submit();
    });
});