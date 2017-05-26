<?php
autenticar();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="<?php echo APP_BASE_URL ?>icon.png" />
        <title><?php echo get_user()->rf_nome ?> / Administração</title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/bootstrap/css/bootstrap.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/administracao.css" />


        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/script/validar/css/validationEngine.jquery.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/Grid/framework.grid.css" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/tagits/css/jquery.tagit.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/tagits/css/tagit.ui-zendesk.css" />

        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mask_moeda.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mascara.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/util/Grid/framework.grid.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/bootstrap/js/bootstrap.js"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/jquery-ui/css/ui-lightness/jquery-ui-1.9.2.custom.min.css" />
        <script src="<?php echo base_url() ?>public/util/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/languages/jquery.validationEngine-pt_BR.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/util/tagits/js/tag-it.js" charset="utf-8"></script>

        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.tablesorter.pager.js"></script>

        <!--ck editor.-->
        <script type="text/javascript" src="<?php echo base_url() ?>public/editor/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/editor/ckeditor/adapters/jquery.js"></script>

        <!--upload de imagems-->
        <script type="text/javascript" src="<?php echo base_url() ?>public/uploads/jquery.form.js"></script>

        <script type="text/javascript">
            jQuery(function() {
                jQuery("form").validationEngine();
                //class='validate[required]'
                $(".date-filtro").datepicker({
                    dateFormat: "dd/mm/yy",
                    dayNamesMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
                });
                $('.fone').mask('(99)9999-9999');

                $(document).ready(function() {
                    $('textarea#editor').ckeditor();
                });
                
                $(document).on('click','.fullscreen-button',function(){
                    $(this).parent().parent().parent().find('#fullscreen').toggleClass('fullscreen');
                });
                
    });
        </script>

    </head>

    <body>
        <div class="corpo">


            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="220px" style="background:#f9f9f9;" valign="top">
                        <a href="<?php echo base_url() ?>">
                            <img style="margin:2px 0 2px 0; width: 210px;" src="<?php echo base_url() ?>public/imagem/logomarca.png" />
                        </a> 
                        <?php echo $this->load->view('home/menu_view') ?></td>
                    <td width="90%" valign="top">

                        <!--- Boa Vindas-->

                        <div id="boas-vindas">
                            <strong id="nome-dis">Seja bem vindo, <?php echo get_user()->rf_nome ?>
                                <a style="font-size:10px;" href="<?php echo base_url('index.php/fabrica/editar_responsavel') ?>">
                                    Editar dados
                                </a>
                            </strong>

                            <a id="btn-sair" href="<?php echo base_url('index.php/fabrica/sair') ?>"></a>

                        </div>

                        <!-- end boas vindas-->
                        <?php
                        $nots = get_notificacao();
                        if (count($nots) > 0) {
                            foreach (@$nots as $k => $n) {
                                if ( isset($n['tipo']) && $n['tipo'] == 1) {
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
            </table>

        </div>



        <script>
            function hide_notificacao(id) {
                $(id).fadeOut(1000);
            }

            $(function() {
                $(".remover-icon").click(function() {
                    return confirm('Deseja realmente remover?');
                });
                $(".moeda").maskMoney({symbol: "R$", decimal: ".", thousands: ""});

                /*ESTADOS AJAX*/
                $(".ajax-uf").change(function() {

                    var uf_sel_id = $(this).val();
                    $(".recebe-cidade").html("<option value=''>Aguarde...</option>");
                    $.ajax({
                        url: '<?php echo base_url('index.php/estados/cidades') ?>',
                        type: 'POST',
                        data: {es_id: uf_sel_id},
                        dataType: 'json',
                        success: function(cidadesJson) {
                            var txt_cidades = "<option value=''>--Selecione a cidade--</option>";
                            $.each(cidadesJson, function(index, cidade) {
                                txt_cidades += "<option value='" + cidade.ci_id + "'>" + cidade.ci_nome + "</option>";
                            });
                            $(".recebe-cidade").html(txt_cidades);
                            $(".recebe-cidade").removeAttr("disabled");
                        }
                    });

                });
                /*ESTADOS AJAX*/
                /*PAIS AJAX*/
                $(".ajax-pais").change(function() {

                    var es_pais = $(this).val();
                    $(".recebe-uf").html("<option value=''>Aguarde...</option>");
                    $.ajax({
                        url: '<?php echo base_url('index.php/estados/uf') ?>',
                        type: 'POST',
                        data: {es_pais: es_pais},
                        dataType: 'json',
                        success: function(cidadesJson) {
                            var txt_cidades = "<option value=''>--Selecione a Estado--</option>";
                            $.each(cidadesJson, function(index, estado) {
                                txt_cidades += "<option value='" + estado.es_id + "'>" + estado.es_nome + "</option>";
                            });
                            $(".recebe-uf").html(txt_cidades);
                            $(".recebe-uf").removeAttr("disabled");
                        }
                    });

                });
                /*PAIS AJAX*/


                //Mascaras

                $(".mdata").mask('99/99/9999');

            });

        </script>

    </body>
</html>

<?php set_notificacao(array()); ?>