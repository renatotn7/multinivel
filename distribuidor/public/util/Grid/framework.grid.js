(function($) {

    $.fn.grid = function(options) {

        var defaults = {
            'width': '700',
            'height': '450',
            'title': 'MEU TITULO',
            'botao': true,
            'subbotao': false,//verificando a opção de colocar uma função externa.
            'mascarar': true,
            'url': ''

        };

        var settings = $.extend({}, defaults, options);

        return this.each(function() {
            /*
             *Criar e configurar DIV GRID
             */
            var margin = '-' + (settings.width / 2) + 'px';
            var html = '<div id="grid-modal" class="modal in fade" style=" left:50%; margin-left:' + margin + '; width:' + settings.width + 'px;higth:' + settings.height + 'px">';
            html += '<div class="modal-header">';

            if (settings.botao) {
                html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            }

            html += '<h3>' + settings.title + '</h3>';
            html += '</div>';
            html += '<div id="conteudo-grid" class="modal-body">';
            html += '</div>';
            html += '<div class="modal-footer">';
            
            if (settings.subbotao) {
                html += '<a href="#" class="btn">Close</a>';
                html += '<a href="#" class="btn btn-primary">Save changes</a>';
            }
            
            html += '</div>';
            html += '</div>';

            if ($('#grid-modal').length == 0) {
                $('body').append(html);
                //Mostra a mascara de fundo ou não.
                if (settings.mascarar) {
                    $('body').append('<div class="modal-backdrop fade in"></div>');
                }

                //Chamar a página.
                if (settings.url != '') {
                    $("#conteudo-grid").html("<div class='grid-loading'>Carregando...</div>");
                    $.ajax({
                        url: settings.url,
                        type: 'GET',
                        dataType: 'html',
                        success: function(dataHtml) {
                            $('#conteudo-grid').html(dataHtml);
                        },
                        error: function() {
                            $('#conteudo-grid').html('<div class="grid-error">Desculpa ocorreu um erro ao carregar.</div>');
                        }
                    });
                } else {
                    $('#conteudo-grid').html('<div class="alert">Página não encontrada erro: 404</div>');
                }
            }
            //funções fechar e outras...
            $(document).on('click', '.close', function() {
                $('#grid-modal').addClass('hide').removeClass('in');
                $('.modal-backdrop').addClass('hide').removeClass('in');
            });


        });



    };

})(jQuery);
