<?php $this->lang->load('publico/entrar/mudar_senha_mensagem_view'); ?>
<!DOCTYPE html>
<html lang="en-US" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths" style="">
    <script async src="<?php echo base_url()?>public/script/analytics.js"></script>
    <script id="tinyhippos-injected">if (window.top.ripple) { window.top.ripple("bootstrap").inject(window, document); }</script><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<title><?php echo $this->lang->line('label_titulo');?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo APP_BASE_URL."icon.png";?>">
<meta name="robots" content="all">
<meta name="keywords" content="empresa!">
<meta name="description" content="empresa chega aos 5 continentes para revolucionar o mercado de vendas diretas através de um dos mais preciosos e promissores produtos, Pedras Preciosas e Jóias">


<script type="text/javascript" src="<?php echo base_url()?>public/script/modernizr.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.themepunch.plugins.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.themepunch.revolution.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.carousello.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.flexslider.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.isotope.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.magnific-popup.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.parallax.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.simpleplaceholder.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.smoothScroll.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.horparallax.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/plugins.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/waypoints.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/us.widgets.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url()?>public/css/switcher.css">
<script type="text/javascript" src="<?php echo base_url()?>public/script/w-switcher.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/jquery-ui-1.10.4.custom.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/arquivos.css">



<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/mask_moeda.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/mascara.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.validationEngine.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/validar/js/languages/<?php echo $this->lang->line('script_validate');?>"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.dcmegamenu.1.3.3.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.dcmegamenu.1.3.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery.hoverIntent.minified.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>public/script/jquery-ui-1.10.4.custom.js"></script>
        <script type="text/javascript">
        jQuery(function() {
            jQuery("form").validationEngine();
            //class='validate[required]'
        });
        </script>
        <script type="text/javascript">
            $(document).ready(function($) {
                $("input[type='image']").css("border", "none");

                $('#mega-menu-2').dcMegaMenu({
                    rowItems: '3',
                    speed: 'fast',
                    effect: 'slide'
                });

            });
        </script>


        <style>
            #localatendimento {
                display: none;
            }
            .estado {
                width: 287px
            }
            td p {

            }


            input[type='radio'] {
                margin: 0;
                padding: 0;
            }
            input[type='text'], select, input[type='password'] {
                margin-bottom: 5px;
                width:90%;

            }


            table {
                color: #000;
            }
            fieldset {
                padding: 6px;
            }
            .row-separator {
                display: block;
                padding: 3px 0;
                margin: 5px 0;
                color: #069;
                font-size: 15px;
                border-bottom: 1px dashed #ccc;
            }
            .indisponivel {
                font-size: 14px;
                color: #F00 !important;
            }
            fieldset {
                font-size: 16px;
                border: none;
            }
            .alert-patrocinador {
                font-size: 16px;
                font-weight: bold;
            }
            #condend {
                float: right;
            }
            #pais {
                position: absolute;
                right: 0px;
                top: -153px;
            }
            .no-select {
                -webkit-touch-callout: none;
                -webkit-user-select: none;
                -khtml-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                -o-user-select: none;
                user-select: none;
            }

            table
            {
                border-collapse:collapse;
                border: none;
            }
            table, th, td
            {
                border: none;
            }






            /* Z-INDEX */
            .formError { z-index: 990; }
            .formError .formErrorContent { z-index: 991; }
            .formError .formErrorArrow { z-index: 996; }

            .formErrorInsideDialog.formError { z-index: 5000; }
            .formErrorInsideDialog.formError .formErrorContent { z-index: 5001; }
            .formErrorInsideDialog.formError .formErrorArrow { z-index: 5006; }




            .inputContainer {
                position: relative;
                float: left;
            }

            .formError {
                position: absolute;
                top: 300px;
                left: 300px;
                display: block;
                cursor: pointer;
            }

            .ajaxSubmit {
                padding: 20px;
                background: #55ea55;
                border: 1px solid #999;
                display: none
            }

            .formError .formErrorContent {


                width: 100%;
                background: #507cae;
                position:relative;
                color: #fff;
                width: 250px;
                font-size: 12px;
                border: 2px solid #fff;

                padding: 4px 10px 4px 10px;
                border-radius: 6px;
                -moz-border-radius: 6px;
                -webkit-border-radius: 6px;
            }

            .greenPopup .formErrorContent {
                background: #33be40;
            }

            .blackPopup .formErrorContent {
                background: #393939;
                color: #FFF;
            }

            .formError .formErrorArrow {
                width: 15px;
                margin: -2px 0 0 13px;
                position:relative;
                display:none;
            }
            body[dir='rtl'] .formError .formErrorArrow,
            body.rtl .formError .formErrorArrow {
                margin: -2px 13px 0 0;
            }

            .formError .formErrorArrowBottom {
                box-shadow: none;
                -moz-box-shadow: none;
                -webkit-box-shadow: none;
                margin: 0px 0 0 12px;
                top:2px;
            }

            .formError .formErrorArrow div {
                border-left: 2px solid #ddd;
                border-right: 2px solid #ddd;
                box-shadow: 0 2px 3px #444;
                -moz-box-shadow: 0 2px 3px #444;
                -webkit-box-shadow: 0 2px 3px #444;
                font-size: 0px;
                height: 1px;
                background: #F8A52A;
                margin: 0 auto;
                line-height: 0;
                font-size: 0;
                display: block;
            }

            .formError .formErrorArrowBottom div {
                box-shadow: none;
                -moz-box-shadow: none;
                -webkit-box-shadow: none;
            }

            .greenPopup .formErrorArrow div {
                background: #33be40;
            }

            .blackPopup .formErrorArrow div {
                background: #393939;
                color: #FFF;
            }

            .formError .formErrorArrow .line10 {
                width: 15px;
                border: none;
            }

            .formError .formErrorArrow .line9 {
                width: 13px;
                border: none;
            }

            .formError .formErrorArrow .line8 {
                width: 11px;
            }

            .formError .formErrorArrow .line7 {
                width: 9px;
            }

            .formError .formErrorArrow .line6 {
                width: 7px;
            }

            .formError .formErrorArrow .line5 {
                width: 5px;
            }

            .formError .formErrorArrow .line4 {
                width: 3px;
            }

            .formError .formErrorArrow .line3 {
                width: 1px;
                border-left: 2px solid #ddd;
                border-right: 2px solid #ddd;
                border-bottom: 0 solid #ddd;
            }

            .formError .formErrorArrow .line2 {
                width: 3px;
                border: none;
                background: #ddd;
            }

            .formError .formErrorArrow .line1 {
                width: 1px;
                border: none;
                background: #ddd;
            }



        </style>
    </head>

    <body class="l-body">

        <!-- CANVAS -->
        <div class="l-canvas type_wide col_cont headerpos_fixed headertype_extended">
            <div class="l-canvas-h">

                <!-- HEADER -->
                <?php $this->load->view('fixo/topo_view')?>
                <!-- /HEADER -->

                <!-- MAIN -->
                <div class="l-main">
                    <div class="l-main-h">
                        <div style="padding: 20px 0 0 0; text-align:center;">
                            <h2 class="align_center"><strong><?php echo $this->lang->line('titulo_conteudo'); ?></strong></h2>
                        </div>

                        <div class="box-content" style="margin:0px auto; width:940px; padding:0;">
                            <div class="box-content-body border-radios">
                                <fieldset>
                                    <div class="worning" style=" width: 104px;float: left;margin-top: 12px;">
                                        <img src="<?php echo base_url('public/imagem/worning.png'); ?>"/>
                                    </div>
                                    <form method="post" id="form" action="" name="form" style="margin-bottom: 60px;">
                                        <form id="form-recuperar" name="form1" method="post" action="" style="border:2px solid #C49643;-webkit-border-radius: 8px;
                                              -moz-border-radius: 8px;
                                              border-radius: 8px;background:#FFF;">
                                              <?php if($this->uri->segment(4) == 1){$di = $this->db->where('di_id', $this->uri->segment(3))->get('distribuidores')->result(); }?>
                                              <?php if($this->uri->segment(4) == 2){$di = $this->db->select('rf_email as di_email')->where('rf_id', $this->uri->segment(3))->get('responsaveis_fabrica')->result(); }?>
                                            <br /><br />
                                            <label style="font-size:17px;">
                                                <?php echo $this->lang->line('info_email1'); ?><strong><?php echo $di[0]->di_email ?></strong><?php echo $this->lang->line('info_email2'); ?>
                                            </label><br />
                                        </form>



<!--                                        <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="right">
                                                    <a style="margin-left:10px;" class="links-recupera" href="<?php echo base_url('index.php/entrar'); ?>"><?php echo $this->lang->line('link_voltar'); ?></a>
                                                </td>
                                                <td align="center">
                                                    <a style="margin-left:10px;" class="links-recupera"  id="link-chat" href="#<?php //echo URL_LOJA;   ?>index.php?route=chat/chat/"  >
                                                        <?php echo $this->lang->line('link_chat'); ?>
                                                    </a>
                                                </td>
                                                <td align="left">
                                                    <a style="margin-left:10px;" class="links-recupera"  id="link-chat" href="<?php echo base_url('index.php/url/redirecionar?uri=//empresa.com/sp/soporte'); ?>" >
                                                           <?php echo $this->lang->line('link_suporte'); ?>
                                                    </a>
                                                </td>
                                            <script language="javascript">
                                                var win = null;
                                                function NovaJanela(pagina, nome, w, h, scroll) {
                                                    LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
                                                    TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
                                                    settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
                                                    win = window.open(pagina, nome, settings);
                                                }
                                            </script>
                                            </tr>
                                        </table>-->
                                </fieldset>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /MAIN -->

<!--         Fim inclusão Páginas
        <div class="l-submain color_primary" style="background-color:#122a3a;">
            <div class="l-submain-h g-html i-cf" style="background-color:#122a3a;">
                <div class="g-cols" style="background-color:#122a3a;">
                    <div class="full-width" style="background-color:#122a3a;">
                        <div class="w-actionbox controls_aside color_primary" style="background-color:#122a3a;" align="center">
                            <img src="<?php echo base_url("public/imagem/logo_0_br.png"); ?>" alt="" width="309" height="57" style="margin-right:30px;"> <a href="http://www.gemstonemineradora.com/home" target="_blank">
                                <img src="<?php echo base_url("public/imagem/logo.png"); ?>" alt="gemstone mineradora" width="231" height="56"></a> </div>
                    </div>
                </div>
            </div>
        </div>-->

        <!-- /CANVAS -->

        <a class="w-toplink" href="http://empresa.com/register2/index.html#"><i class="fa fa-angle-up"></i></a>
        <script type="text/javascript">
            window.color_scheme = "color_0";
            window.body_layout = "wide";
        </script>

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-89331704-1', 'auto');
            ga('send', 'pageview');
        </script>

        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="display: none;"></ul></body></html>
