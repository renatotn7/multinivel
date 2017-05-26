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

//Final de semana (Sabado).
// if (date("N")) == 6) {
    //Verifica o Binario
    $binario = new Binario(get_user());
// }

//Realizando upgrade Automatico para os planos fast e membership
upgradeModel::RealizarUpgradeAutomatico(get_user()->di_id);

//Verifica se país permiter parcelar
// if (ComprasModel::pode_parcelar(get_user())) {
//     //Geran do parcela se poder parcelar
//     ComprasModel::gerar_parcelas_compras(get_user());

//     if (count(ComprasModel::parcelas_pendentes(get_user())) > 0) {


//         //Verifica e realiza o pagamento parcelado.
//         $compra = $this->db->where('co_pago', 1)
//                         ->where('co_parcelado', 1)
//                         ->where('co_eplano', 1)
//                         ->where('co_id_distribuidor', get_user()->di_id)
//                         ->get('compras')->row();

//         $pagamento = new Pagamento();
//         $pagamento->realizarPagamento(new PagamentoParcelado($compra));
//     }
// }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $this->lang->line('janela_titulo'); ?> / <?php echo get_user()->di_nome ?></title>

        <link rel="shortcut icon" href="<?php echo APP_BASE_URL ?>icon.png" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/bootstrap/css/bootstrap.css" /> -->
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" crossorigin="anonymous"> -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/template.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/custom.css" />
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/administracao.css" /> -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/script/validar/css/validationEngine.jquery.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/Grid/framework.grid.css" />
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/script/jqzoom/css/jquery.jqzoom.css" /> -->
        <!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/jquery-ui/css/ui-lightness/jquery-ui-1.9.2.custom.min.css" /> -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/script/mCS/jquery.mCustomScrollbar.min.css" />
        <!-- <style>
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
            /* #btn-sair {
                background: url("<?php echo base_url('/public/imagem/') . '/' . $this->lang->line('css_btn_sair'); ?>");
            } */

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
        </style> -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    </head>
    <body class="nav-md footer_fixed">
        <?php if (ComprasModel::informouRecebimento(get_user())) { ?>
        <div id="modalRecebimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRecebimento" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" onclick="fechar();" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>Atenção</h3>
                    </div>
                    <div id="modal-body" class="modal-body" >
                        <?php
                        if(!$this->input->get('form_inconformidade_recebimento')){
                            $this->load->view("confirmacao_recebimento/confirmacao_recebimento_view");
                        }else{
                            $this->load->view("confirmacao_recebimento/nao_recebeu_produto_view");
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $('#modalRecebimento').modal('show');
            function fechar() {
                $('#modalRecebimento').modal('hide')
            }
        </script>
        <?php } ?>
        <?php
        //Verificando se o cara ja fez a escolha dele
        if (count(ComprasModel::fez_escolha_recebimento(get_user())) > 0 && false) {
            ?>
            <div id="modalRecebimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRecebimento" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class = "modal-header">
                            <!-- <button type = "button" onclick="fechar();" class = "close" data-dismiss = "modal" aria-hidden = "true">&times;</button> -->
                            <h3>Atenção</h3>
                        </div>
                        <div id="modal-body" class = "modal-body" >
                            <?php $this->load->view("home/escolha_produto_view"); ?>
                        </div>
                    </div>
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
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col menu_fixed">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="<?php echo base_url() ?>" class="site_title text-center">
                                <img src="<?php echo base_url("public/imagem/logo_white.png"); ?>" width="100%" />
                            </a>
                        </div>

                        <div class="clearfix"></div>
                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                            <?php echo $this->load->view('home/menu_view') ?>
                        </div>
                        <!-- /sidebar menu -->
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <div class="nav_menu">
                        <nav>
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>
                            <ul class="nav navbar-nav navbar-right">
                                <li>
                                    <a href="javascript:;" class="switch-language dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-globe"></i> <?php echo $this->lang->line('label_idioma'); ?>
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu">
                                        <li>
                                            <a href="<?php echo base_url("index.php/home/set_idioma/pt?url=http://" . $_SERVER['SERVER_NAME'] . $_SERVER ['REQUEST_URI']); ?>">
                                                <span>Portugu&ecirc;s</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url("index.php/home/set_idioma/en?url=http://" . $_SERVER['SERVER_NAME'] . $_SERVER ['REQUEST_URI']); ?>">
                                                <span>English</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url("index.php/home/set_idioma/es?url=http://" . $_SERVER['SERVER_NAME'] . $_SERVER ['REQUEST_URI']); ?>">
                                                <span>Espa&ntilde;ol</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-user"></i>
                                        <?php echo get_user()->distribuidor->getNome(); ?> (<?php echo get_user()->di_usuario; ?>)
                                        <span class=" fa fa-angle-down"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li>
                                            <a href="<?php echo base_url('index.php/distribuidor/meus_dados') ?>">
                                                <?php echo $this->lang->line('label_meus_dados'); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('/index.php/distribuidor/sair'); ?>">
                                                <i class="fa fa-sign-out pull-right"></i> <?php echo $this->lang->line('label_sair'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a target="_blank" href="<?php echo $this->lang->line('url_facebook') ?>">
                                        <i class="fa fa-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="<?php echo $this->lang->line('url_twitter') ?>">
                                        <i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="<?php echo $this->lang->line('url_youtube') ?>">
                                        <i class="fa fa-youtube"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- page content -->
                <div class="right_col" role="main">
                    <?php
                    $nots = get_notificacao();

                    if (is_array($nots)) {
                        foreach ($nots as $k => $n) {
                        ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php
                                if ($n['tipo'] == 1) {
                                    ?>
                                    <div id="noti_<?php echo $k ?>" class="notificacao alert alert-success"><?php echo $n['mensagem'] ?></div>
                                    <script type="text/javascript">
                                        jQuery(function() {
                                            setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                                        });
                                    </script>
                                <?php } else if ($n['tipo'] == 2) { ?>
                                    <div id="noti_<?php echo $k ?>" class="notificacao alert alert-danger"><?php echo $n['mensagem'] ?></div>
                                    <script type="text/javascript">
                                        jQuery(function() {
                                            setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                                        });
                                    </script>
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                        <?php
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
                </div>
                <!-- /page content -->

                <!-- footer content -->
                <footer>
                    <div class="pull-right">
                        Copyright &copy; <?php echo date('Y') ?> - MWG Gold J&oacute;ias
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
            </div>
        </div>

        <!-- <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery-1.7.2.min.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mask_moeda.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.maskedinput.min.js"></script>
        <!-- <script type="text/javascript" src="<?php echo base_url() ?>public/script/mascara.js"></script> -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!-- <script type="text/javascript" src="<?php echo base_url() ?>public/util/bootstrap/js/bootstrap.js"></script> -->
        <!-- <script type="text/javascript" src="<?php echo base_url() ?>public/util/Grid/framework.grid.js"></script> -->
        <!-- <script type="text/javascript" src="<?php echo base_url() ?>public/script/jqzoom/js/jquery.jqzoom-core.js"></script> -->
        <script type="text/javascript" src="<?php echo base_url() ?>public/util/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/languages/<?php echo $this->lang->line('script_validate'); ?>.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mCS/jquery.mCustomScrollbar.concat.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url() ?>public/script/custom.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/bootstrap-confirmation.min.js"></script>
        <!-- <script type="text/javascript" src="<?php echo base_url() ?>public/script/bootstrap3-confirmation.js"></script> -->

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

                //mover
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

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-89331704-1', 'auto');
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
