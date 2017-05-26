<?php

autenticar();
$this->lang->load('distribuidor/home/index_view');
$this->lang->load('util/calendario');

$distribuidor = $this->db->where('di_id', get_user()->di_id)->get('distribuidores')->row();

if ($distribuidor->di_niv == 0) {
    $atm = new atm();
    $dados = $atm->consulta_cadastro_ewallet($distribuidor->di_email);

    if (isset($dados['niv']) && !empty($dados['niv'])) {
        $this->db->where('di_id', $distribuidor->di_id)->update('distribuidores', array(
            'di_niv' => $dados['niv']
        ));
    }
}
//Realizando upgrade Automatico para os planos fast e membership
upgradeModel::RealizarUpgradeAutomatico(get_user()->di_id);

//Verifica se país permiter parcelar
if (ComprasModel::pode_parcelar(get_user())) {
    //Geran do parcela se poder parcelar
    ComprasModel::gerar_parcelas_compras(get_user());

    if (count(ComprasModel::parcelas_pendentes(get_user())) > 0) {


        //Verifica e realiza o pagamento parcelado.
        $compra = $this->db->where('co_pago', 1)
                        ->where('co_parcelado', 1)
                        ->where('co_eplano', 1)
                        ->where('co_id_distribuidor', get_user()->di_id)
                        ->get('compras')->row();

        $pagamento = new Pagamento();
        $pagamento->realizarPagamento(new PagamentoParcelado($compra));
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link rel="shortcut icon" href="<?php echo APP_BASE_URL ?>icon.png" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $this->lang->line('janela_titulo'); ?> / <?php echo get_user()->di_nome ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/administracao.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/script/validar/css/validationEngine.jquery.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/Grid/framework.grid.css" />
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mask_moeda.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mascara.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/util/bootstrap/js/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/util/Grid/framework.grid.js"></script>


        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jqzoom/js/jquery.jqzoom-core.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/script/jqzoom/css/jquery.jqzoom.css" />


        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/jquery-ui/css/ui-lightness/jquery-ui-1.9.2.custom.min.css" />
        <script src="<?php echo base_url() ?>public/util/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/languages/<?php echo $this->lang->line('script_validate'); ?>.js"></script>
        <script type="text/javascript">
            jQuery(function() {
                jQuery("form").validationEngine();
                $(".mdata").mask('99/99/9999');
                $(".mdatanasc").mask('99/99/9999');
                $(".mdata").datepicker({
                    dateFormat: "dd/mm/yy",
                    dayNamesMin: [
                        "<?php echo $this->lang->line('domingo_min'); ?>",
                        "<?php echo $this->lang->line('segunda_min'); ?>",
                        "<?php echo $this->lang->line('terca_min'); ?>",
                        "<?php echo $this->lang->line('quarta_min'); ?>",
                        "<?php echo $this->lang->line('quinta_min'); ?>",
                        "<?php echo $this->lang->line('sexta_min'); ?>",
                        "<?php echo $this->lang->line('domingo_min'); ?>"
                    ],
                    monthNames: [
                        "<?php echo $this->lang->line('janeiro'); ?>",
                        "<?php echo $this->lang->line('fevereiro'); ?>",
                        "<?php echo $this->lang->line('marco'); ?>",
                        "<?php echo $this->lang->line('abril'); ?>",
                        "<?php echo $this->lang->line('maio'); ?>",
                        "<?php echo $this->lang->line('junho'); ?>",
                        "<?php echo $this->lang->line('julho'); ?>",
                        "<?php echo $this->lang->line('agosto'); ?>",
                        "<?php echo $this->lang->line('setembro'); ?>",
                        "<?php echo $this->lang->line('outubro'); ?>",
                        "<?php echo $this->lang->line('novembro'); ?>",
                        "<?php echo $this->lang->line('dezembro'); ?>"
                    ]
                });
            });

            function show_senha_seguranca() {
                $("#perna_senha_seguranca").css('display', 'block');
            }

            $(document).ready(function() {

                $("#helpline").click(function() {
                    $('.modal').addClass('in');
                    $('.modal').removeClass('hide');
                });


                $(".cancela").click(function() {
                    $('.modal').addClass('hide');
                    $('.modal').removeClass('in');
                });

                $("#idioma").click(function() {
                    $("#list-idiomas").toggle();
                });

                $("#idioma").blur(function() {
                    $("#list-idiomas").toggle();
                });

            });

            $(document).on('click', '.verificar_token', function() {
                $('.control-group-token').removeClass('error');
                $('.descricao-error').hide();
                $('.img-error').hide();
                $('.img-success').hide();
            });

            //Verfifica se a token é válida
            $(document).on('blur', '.verificar_token', function() {
                $.ajax({
                    url: '<?php echo base_url('index.php/distribuidor/verificar_token_pagamento_ajax'); ?>',
                    data: $('#form-token-produto-comprado').serialize(),
                    type: 'post',
                    success: function(data)
                    {
                        if (data == 'Error') {
                            $('.control-group-token').addClass('error');
                            $('.descricao-error').show();
                            $('.img-error').show();
                        } else if (data == 'ok') {
                            $('.control-group-token').addClass('success');
                            $('.img-success').show();
                        }
                    }

                });
            });

            //mostrar e minimizar janelas
            $(document).on('click', '.button-min', function() {
                if ($(this).parent().parent().find('.minizend').css('display') == 'block') {
                    $(this).parent().parent().find('.minizend').hide();
                } else {
                    $(this).parent().parent().find('.minizend').show();
                }
            });

            //mover
            $(document).ready(function() {
                $('.move').draggable({
                    revert: true,
                    proxy: 'clone',
                    onStartDrag: function() {
                        $(this).draggable('options').cursor = 'not-allowed';
                        $(this).draggable('proxy').css('z-index', 10);
                    },
                    onStopDrag: function() {
                        $(this).draggable('options').cursor = 'move';
                    }
                });

            });
            function var_dump(obj) {
                console.debug(obj);
            }
        </script>
        <style>
            #mask-plano{
                position:absolute;
                top:0;
                left:0;
            }
            #titulo-plano{
                position:absolute;
                top:94px;
                left:23px;
                display:block;
                z-index:10;
                color:#525252;
                font-size:12px;
                font-weight:bold;
                text-align:center;
                width:72px;
            }

            /*NUNCA ALTERAR CSS DINAMICAMENTE ALTERADO */
            #btn-sair { background: url("<?php echo base_url('/public/imagem/') . '/' . $this->lang->line('css_btn_sair'); ?>");
            }

            .botao-padrao{
                background: #122A3A;
                color: #FFF;
                font-size: 14px;
                min-width: 70px;
                min-height: 35px;
                border: none;
                border-radius: 3px;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <?php if (ComprasModel::informouRecebimento(get_user())) { ?>
            <div  id="modal-recebimento" class = "modal fade" style = "width:917px; margin-left: -458px;"> <div class = "modal-header">
                    <button type = "button" onclick="fechar();" class = "close" data-dismiss = "modal" aria-hidden = "true">&times;</button>
                    <h3>Atenção</h3>
                </div>
                <div id="modal-body" class = "modal-body" >
                    <?php
                    if(!$this->input->get('form_inconformidade_recebimento')){
                        $this->load->view("confirmacao_recebimento/confirmacao_recebimento_view");
                    }else{
                        $this->load->view("confirmacao_recebimento/nao_recebeu_produto_view");
                    }
                    ?>
                </div>
            </div>
            <script type="text/javascript">
                $('#modal-recebimento').modal('show');
                function fechar() {
                    $('#modal-recebimento').modal('hide')
                }
            </script>
            <?PHP
        }
        ?>
        <?php
        //Verificando se o cara ja fez a escolha dele
        if (count(ComprasModel::fez_escolha_recebimento(get_user())) > 0 && false) {
            ?>

            <div class = "modal in fade" style = "width:917px; margin-left: -458px;">
                <div class = "modal-header">
                    <!--                    <button type = "button" onclick="fechar();" class = "close" data-dismiss = "modal" aria-hidden = "true">&times;</button>-->
                    <h3>Atenção</h3>
                </div>
                <div id="modal-body" class = "modal-body" >
                    <?php $this->load->view("home/escolha_produto_view"); ?>
                </div>
            </div>
            <div class="modal-backdrop fade in"></div>
            <script type="text/javascript">
                $(document).on('click', '.btn-escola', function() {

                    if ($(this).attr('rel') == 0) {
                        $('#modal-body').fadeOut().load('<?php echo base_url("index.php/home/escolha_produto") ?>', function() {
                            $(this).fadeIn();
                        });
                    }
                    if ($(this).attr('rel') == 1) {
                        $('#modal-body').fadeOut(500).load('<?php echo base_url("index.php/home/escolha_confirmacao1") ?>', function() {
                            $(this).fadeIn();
                        });
                    }

                    if ($(this).attr('rel') == 2) {
                        $('#modal-body').fadeOut(500).load('<?php echo base_url("index.php/home/escolha_confirmacao2") ?>', function() {
                            $(this).fadeIn();
                        });
                    }

                    if ($(this).attr('rel') == 3) {
                        $('#modal-body').fadeOut(500).load('<?php echo base_url("index.php/home/escolha_confirmacao3") ?>', function() {
                            $(this).fadeIn();
                        });
                    }


                });
                function fechar() {
                    $('.modal').removeClass('in');
                }
            </script>
        <?php } ?>
        <?php $fabrica = $this->db->get('fabricas')->row(); ?>
        <div class="escritorio-position-fixed">
            <div class="topo">
                <div id="idioma">
                    <span>Idioma </span>
                    <img src="<?php echo base_url() ?>public/imagem/arrow-down.png" style="margin-left: 5px;"/>
                    <div id="list-idiomas">
                        <div class="list-idiomas-item">
                            <a href="<?php echo base_url("index.php/home/set_idioma/pt?url=http://" . $_SERVER['SERVER_NAME'] . $_SERVER ['REQUEST_URI']); ?>">
                                <span>Português</span>
                            </a>
                        </div>
                        <div class="list-idiomas-item">
                            <a href="<?php echo base_url("index.php/home/set_idioma/en?url=http://" . $_SERVER['SERVER_NAME'] . $_SERVER ['REQUEST_URI']); ?>">
                                <span>English</span>
                            </a>
                        </div>
                        <div class="list-idiomas-item">
                            <a href="<?php echo base_url("index.php/home/set_idioma/es?url=http://" . $_SERVER['SERVER_NAME'] . $_SERVER ['REQUEST_URI']); ?>">
                                <span>Español</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="redes-sociais">
                    <div class="social-icon">
                        <a target="_blank" href="<?php echo $this->lang->line('url_facebook') ?>">
                            <i class="facebook"></i>
                        </a>
                    </div>
                    <div class="social-icon">
                        <a target="_blank" href="<?php echo $this->lang->line('url_twitter') ?>">
                            <i class="twitter"></i>
                        </a>
                    </div>
                    <div class="social-icon">
                        <a target="_blank" href="<?php echo $this->lang->line('url_youtube') ?>">
                            <i class="youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="corpo">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td id="corpo-menu" valign="top">
                            <?php echo $this->load->view('home/menu_view') ?>
                        </td>
                        <td width="80%" valign="top">
                            <div id="boas-vindas">
                                <strong id="nome-dis"><?php echo $this->lang->line('boas_vindas'); ?><?php echo get_user()->distribuidor->getNome(); ?>, <?php echo $this->lang->line('info_login'); ?> (<?php echo get_user()->di_usuario; ?>). </strong>
                                <a id="btn-sair" href="<?php echo base_url('/index.php/distribuidor/sair'); ?>"></a>
                            </div>
                            <?php
                            $nots = get_notificacao();

                            if (is_array($nots)) {
                                foreach ($nots as $k => $n) {
                                    if ($n['tipo'] == 1) {
                                        ?>
                                        <div id="noti_<?php echo $k ?>" class="notificacao alert alert-success"><?php echo $n['mensagem'] ?></div>
                                        <script type="text/javascript">
                                            jQuery(function() {
                                                setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                                            });
                                        </script>
                                    <?php } else if ($n['tipo'] == 2) { ?>
                                        <div id="noti_<?php echo $k ?>" class="notificacao alert alert-error"><?php echo $n['mensagem'] ?></div>
                                        <script type="text/javascript">
                                            jQuery(function() {
                                                setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                                            });
                                        </script>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <?php
                            if (isset($pagina)) {
                                $this->load->view("{$pagina}_view");
                            } else {
                                $this->load->view("home/meio_view");
                            }
                            ?>
                        </td>
                    </tr>
                    <tr align="center">
                        <td colspan="10" align="center"><span style="text-align:center;font-weight:bold;font-size:12px;">Copyright © <?php echo date('Y') ?> - nossa empresa.</span></td>
                    </tr>
                </table>
            </div>
        </div>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-89331704-1', 'MMN');
            ga('send', 'pageview');


        </script>
    </body>
</html>

<!--verifica se a senha de login é igual a senha de segurança-->
<?php
$id = get_user()->di_id;
$igual = $this->db->query("SELECT count(*) as igual
                                        FROM (`distribuidores`)
                                        WHERE di_id = '$id' and di_senha =  di_pw")->row()->igual;

if ($igual > 0 && $this->router->fetch_method() != 'mudar_senha2') {
    ?>

    <script type="text/javascript">
        $(function() {
            grid('<?php echo base_url('index.php/notificacao/alteracao_senhas_iguais') ?>', 'Atenção', '750', '200', false);
        });
    </script>
<?php } ?>



<?php set_notificacao(array()); ?>
