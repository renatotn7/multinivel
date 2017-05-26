<?php $this->lang->load('publico/entrar/index_view'); ?>
<!DOCTYPE html>
<html lang="en-US" class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths" style="">
    <script async src="<?php echo base_url() ?>public/script/analytics.js"></script>
    <script id="tinyhippos-injected">if (window.top.ripple) {
            window.top.ripple("bootstrap").inject(window, document);
        }</script><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <title><?php echo $this->lang->line('label_titulo'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo APP_BASE_URL . "icon.png"; ?>">
        <meta name="robots" content="all">
        <meta name="keywords" content="empresa!">
        <meta name="description" content="empresa chega aos 5 continentes para revolucionar o mercado de vendas diretas através de um dos mais preciosos e promissores produtos, Pedras Preciosas e Jóias">


        <script type="text/javascript" src="<?php echo base_url() ?>public/script/modernizr.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.themepunch.plugins.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.themepunch.revolution.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.carousello.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.flexslider.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.isotope.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.magnific-popup.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.parallax.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.simpleplaceholder.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.smoothScroll.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.horparallax.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/plugins.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/waypoints.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/us.widgets.js"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url() ?>public/css/switcher.css">
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/w-switcher.js"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/jquery-ui-1.10.4.custom.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/arquivos.css">



        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mask_moeda.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mascara.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/languages/<?php echo $this->lang->line('script_validate'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.dcmegamenu.1.3.3.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.dcmegamenu.1.3.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery.hoverIntent.minified.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/jquery-ui-1.10.4.custom.js"></script>
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
                width: 90%;
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
            table {
                border-collapse: collapse;
                border: none;
            }
            table, th, td {
                border: none;
            }
            /* Z-INDEX */
            .formError {
                z-index: 990;
            }
            .formError .formErrorContent {
                z-index: 991;
            }
            .formError .formErrorArrow {
                z-index: 996;
            }
            .formErrorInsideDialog.formError {
                z-index: 5000;
            }
            .formErrorInsideDialog.formError .formErrorContent {
                z-index: 5001;
            }
            .formErrorInsideDialog.formError .formErrorArrow {
                z-index: 5006;
            }
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
                position: relative;
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
                position: relative;
                display: none;
            }
            body[dir='rtl'] .formError .formErrorArrow, body.rtl .formError .formErrorArrow {
                margin: -2px 13px 0 0;
            }
            .formError .formErrorArrowBottom {
                box-shadow: none;
                -moz-box-shadow: none;
                -webkit-box-shadow: none;
                margin: 0px 0 0 12px;
                top: 2px;
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

            .login1 {
                margin: 50px auto;
                padding: 50px;
                background: #fff;
                border-radius: 10px;
                -moz-border-radius: 10px;
                -webkit-border-radius: 10px;
                max-width: 440px;
                -webkit-box-shadow: 2px 2px 10px 0px rgba(50, 50, 50, 0.3);
                -moz-box-shadow: 2px 2px 10px 0px rgba(50, 50, 50, 0.3);
                box-shadow: 2px 2px 10px 0px rgba(50, 50, 50, 0.3);

            }


        </style>


    </head>

    <body class="l-body">

        <!-- CANVAS -->
        <div class="l-canvas type_wide col_cont headerpos_fixed headertype_extended">
            <div class="l-canvas-h"> 
                <!-- HEADER -->
                <?php $this->load->view('fixo/topo_view') ?>
                <!-- /HEADER --> 
                <!--- MAIN -->
                <div class="l-main" style="background-color:#ffffff; height:100%;">
                    <div class="login1">
                        <div style="padding: 0px 0 0 0; text-align:center; ">
                            <a class="w-logo-link" href="<?php echo base_url('index.php/url/redirecionar?uri=' . $this->lang->line('url_home')); ?>"> 
                                <img class="w-logo-img" src="<?php echo base_url('public/imagem/logo.png'); ?>" alt="empresa"> 
                            </a>
                            <h3 class="align_center" style="color:#036;">
                                <i class="fa fa-lock"></i>
                                <strong>
                                    <?php echo $this->lang->line('label_titulo'); ?>
                                </strong>
                            </h3>
                        </div>
                        <div class="box-content" style="margin:0px auto;   padding:0;">
                            <div class="box-content-body border-radios">
                                <fieldset>
                                    <form method="post" id="form-login" action="<?php echo APP_BASE_URL . APP_PUBLICO ?>/index.php/entrar/autenticar">

                                        <?php
                                        if (isset($_GET['msg']) && $_GET['msg'] != "xvt1") {

                                               echo "<p style='color:#f00;'>" . $_GET['msg'] . "</p>";
                                            //Tipo de mensagem... pode incluir links.
                                            if (isset($_GET['tipo']) && $_GET['tipo'] == 1) {
                                               echo "<a  style='color: blue;font-size: 14px;text-transform: lowercase;'href='" . base_url('index.php/entrar/nova_solicitacao') . "'>" . $this->lang->line('label_link_novo_solicitacao') . "</a>";
                                            }
                                        }
                                        ?>

                                        <?php
                                        if (isset($_GET['msg']) && $_GET['msg'] == "xvt1") {
                                            echo "<h4  style='color:#f00;'> 
                                       Cadastro inexistente, favor entrar em contato com nosso departamento de suporte pelo link:
                                        </h4>";
                                            echo "<a href='http://www.empresa.com/ticket/'>http://www.empresa.com/ticket/</a>";
                                        }
                                        ?>
                                        <center>
                                            <p style="color:#f00;"></p>
                                        </center><table width="340px" border="0" cellspacing="0" cellpadding="0" style=" ">

                                            <tbody><tr>
                                                    <td><strong><?php echo $this->lang->line('label_usuario'); ?>:</strong><br>
                                                        <input placeholder="<?php echo $this->lang->line('input_usuario'); ?>" type="text" name="entrar1" id="usuario_new" value="" onblur="usuario_disponivel(this.value)" class="entrar1 validate[required]"></td>
                                                </tr>
                                                <tr>
                                                    <td><strong><?php echo $this->lang->line('label_senha'); ?></strong><br>
                                                        <input placeholder="<?php echo $this->lang->line('input_senha'); ?>" type="password" name="entrar2" id="usuario_new" value="" onblur="usuario_disponivel(this.value)" class="entrar2 validate[required]"></td>
                                                </tr>
                                                <!--<tr>
                                                    <td><strong<?php echo $this->lang->line('label_captcha'); ?></strong><br>
                                                        <input style="width: 130px;float: left;margin-right: 6px;"  type="text" name="cap" value="">
                                                        <div id="captcha" style="float: left;width: 148px;"></div>
                                                        <img style="cursor: pointer;" onclick="atualizar_captcha();" src="<?php echo base_url('/public/imagem/atualizar.png'); ?>" alt=""/>
                                                    </td>
                                                </tr>-->
                                                <tr>
                                                    <td style="text-align: center; padding-top:20px;">
                                                        <button type="submit" class="w-actionbox-button g-btn type_contrast outlined size_big"> 
                                                            <span><?php echo $this->lang->line('label_entrar'); ?></span> 
                                                        </button>
                                                        <br>
                                                        <a href="<?php echo APP_BASE_URL . APP_PUBLICO ?>/index.php/entrar/solicitar_alterar_senha"><span><?php echo $this->lang->line('link_recupera_senha'); ?></span></a></td>
                                                </tr>
                                            </tbody></table>
                                    </form>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /MAIN --->
            </div>
        </div>
        <!-- /MAIN --> 

        <!-- /CANVAS --> 

        <!-- FOOTER -->
        <?php // $this->load->view('fixo/rodape_view') ?>
        <!-- /FOOTER -->
        <a class="w-toplink" href=""><i class="fa fa-angle-up"></i></a> 
        <script type="text/javascript">

            function atualizar_captcha() {
                $.ajax({
                    url: '<?php echo base_url("/index.php/entrar/captchar_ajax"); ?>',
                    type: 'post',
                    success: function(data) {
                        $("#captcha").html(data);
                    }
                });
            }
            atualizar_captcha();
            window.color_scheme = "color_0";
            window.body_layout = "wide";

        </script> 





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

            ga('create', 'UA-54031608-1', 'auto');
            ga('send', 'pageview');

        </script>


        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content ui-corner-all" id="ui-id-1" tabindex="0" style="display: none;"></ul></body></html>
