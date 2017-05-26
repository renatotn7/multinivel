<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
    <head>
        <meta charset="UTF-8" />
        <title><?php echo $title; ?></title>
        <base href="<?php echo $base; ?>" />
        <?php if ($description) { ?>
        <meta name="description" content="<?php echo $description; ?>" />
        <?php } ?>

        <?php if ($keywords) { ?>
        <meta name="keywords" content="<?php echo $keywords; ?>" />
        <?php } ?>

        <?php if ($icon) { ?>
        <link href="<?php echo $icon; ?>" rel="icon" />
        <?php } ?>

        <?php foreach ($links as $link) { ?>
        <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
        <?php } ?>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/theme/default/stylesheet/stylesheet.css" />
        <?php foreach ($styles as $style) { ?>
        <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
        <?php } ?>
        <script type="text/javascript" src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
        <script type="text/javascript" src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
        <script type="text/javascript" src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/tabs.js"></script>
        <script type="text/javascript" src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/common.js"></script>
        <?php foreach ($scripts as $script) { ?>
        <script type="text/javascript" src="<?php echo $script; ?>"></script>
        <?php } ?>
        <?php echo $google_analytics; ?>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300,600' rel='stylesheet' type='text/css'>
        <script type="text/javascript">

            $(document).ready(function() {
                $(window).resize(function() {
                    console.log($(window).width());
                    console.log($(window).height());
                });
            });
        </script>


    <body>
    </head>
    <div id="fb-root"></div>
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id))
                return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }
        (document, 'script', 'facebook-jssdk'));


    </script>
    <div id="topo">

        <div id="conteiner-topo">
            <div id="logo">
                <?php /* <a href="<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=common/home"> */ ?>
                <a href="<?php echo $link_logo;?>">
                    <img src="<?php echo HTTP_IMAGE_TEMP.'logomarca.png'; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" />
                </a>
            </div>

            <div class="loja-owner" style="float: right;
                 margin-top: 5px;
                 color: red;
                 ">
                <?php 

                if(count($this->session->data['distribuidor_recebera_bonus'])>0){?>
                    <h3><?php echo $text_voce_estar_comprando_loja;?>: <?php echo $this->session->data['distribuidor_recebera_bonus']->di_usuario;?></h3>
                <?php }?>
            </div>
            <?php echo $language; ?>

            <!-- Busca -->
            <div class="content-produrar">
                <div id="form-procura" class="posicao-procura">
                    <?php if ($filter_name) { ?>
                    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="busca"  />
                    <?php } else { ?>
                    <input type="text" name="filter_name" value="<?php echo $text_buscar; ?>" class="busca" onBlur="if (this.value == ''){this.value='<?php echo $text_buscar; ?>'}" onFocus="if (this.value == '<?php echo $text_buscar; ?>'){this.value=''}" />
                    <?php } ?>
                    <input type="image" class="button-search" src="<?php echo HTTP_IMAGE_TEMP ?>layout_hobbz/<?php echo $btn_buscar;?>" id="img_buttom" class="button" value="Buscar"/>
                </div>

                <?php /* <a href="<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=account/order" id="btn-meus-pedido"> */ ?>
                <a href="<?php echo $link_meus_pedidos;?>" id="btn-meus-pedido">
                    <img src="<?php echo HTTP_IMAGE_TEMP ?>layout_hobbz/<?php echo $btn_meus_pedidos;?>" />     
                </a>

                <div id="header-list" style="background: url('<?php echo HTTP_IMAGE_TEMP;?>layout_hobbz/<?php echo $btn_carrinho;?>') center no-repeat !important;" class="box-pedido">   
                    <div class="itens">
                        <span id="itens">
                            <a href="<?php echo $this->url->link('checkout/cart')?>">
                                <strong><?php echo $qtd_itens?></strong>				      
                        </span>
                        <span>Itens</span>
                        </a>
                    </div>
                </div>

            </div>
            <!--End-->

            <div class="menu-header">
                <?php if (!$logged){ ?>
                <a id="login-user" style="background: none;position: relative;" href="javascript:void(0);">
                    Login
                    <i class="icon-login-top"></i>  
                </a>
                <?php }else{ ?>
                <a class="logout" href="<?php echo $this->url->link('account/logout')?>">Sair</a>
                <a href="<?php echo $this->url->link('account/account')?>">Olá <?php echo $this->customer->getFirstName(); ?>!</a>

                <?php } 
                /*
                <a href="<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=information/contact">
                */ ?>
                <a href="<?php echo $this->url->link('information/contact')?>">
                    <?php echo $text_atedimentos;?>
                </a>

                <a href="<?php echo $this->url->link('account/wishlist')?>">
                    <?php echo $text_lista_desejos;?>
                </a>
                <a href="<?php echo $this->url->link('account/order')?>">
                    <?php echo $text_pedidos;?>
                </a>

                <div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">

                    <form action="<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=account/login" method="post" enctype="multipart/form-data">

                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-user"></i></span>
                            <input type="text" placeholder="E-mail" style="margin:5px 0 10px 0;" name="email">
                        </div>

                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-lock"></i></span>
                            <input type="password" placeholder="Senha" name="password">
                        </div>
                        <input type="submit" class="button" value="Logar" style="display: block;margin:5px 0 10px 0;">

                        <a style="float: none; text-align: left; color: #0044cc; padding: 3px; margin: 0; border: none; background: none;" href="<?php echo $this->url->link('account/register')?>">ou cadastre-se</a>
                        <br>
                    </form>
                </div>

            </div>  


            <div  id="atendimento" style="display: none;">
                <?php if (!$logged){ ?>
                <span class="bem-vindo">Seja bem vindo!</span>
                <span class="link">Faça seu <a href="<?php echo $account; ?>">login</a> ou <a href="<?php echo $this->url->link('account/register') ?>">cadastre-se</a></span>
                <?php }else{ ?>
                <span class="bem-vindo">Olá <?= $this->customer->getFirstName(); ?>!</span>
                <span class="link">Seja Bem Vindo, boas compras!<br />
                    <a class="logout" href="<?php echo $this->url->link('account/logout')?>">Sair ( Logout )</a>
                </span>
                <?php } ?>
            </div>

        </div>

        <!-- menu -->
    </div>

    <div id="conteiner-menu">

        <div id="box-menu-topo" style="display: none;">
            <?php if($this->request->get['route'] == 'pavblog/category' || $this->request->get['route'] == 'pavblog/blog' || empty($this->request->get['route']))
            { 
            ?>   
            <a class="tira-margim" href="index.php?route=pavblog/category&id=25">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/saude-animal.png'; ?>" />
                <div class="text-menu"> Saúde Animal </div>
            </a>
            <a href="index.php?route=pavblog/category&id=26">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/clinica.png'; ?>" />
                <div class="text-menu"> Clínica </div>
            </a>
            <a href="<?php echo $home ?>">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/loja.png'; ?>" />
                <div class="text-menu">loja</div>
            </a>
            <a href="index.php?route=pavblog/category&id=28">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/revista.png'; ?>" />
                <div class="text-menu">Revista</div>
            </a>
            <a href="index.php?route=pavblog/category&id=29">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/plano-saude.png'; ?>" />
                <div class="text-menu">Plano de Saúde</div>
            </a>
            <a href="index.php?route=pavblog/category&id=30">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/hotel.png'; ?>" />
                <div class="text-menu">Hotel</div>
            </a>
            <a href="index.php?route=pavblog/category&id=31">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/buscar-entrega.png'; ?>" />
                <div class="text-menu">Busca e Entrega</div>
            </a>
            <a href="index.php?route=pavblog/category&id=32">
                <img src="<?php echo HTTP_IMAGE_TEMP.'layout/filhotes.png'; ?>" />
                <div class="text-menu">Filhotes</div>
            </a>

            <?php }?>


        </div>
    </div>

