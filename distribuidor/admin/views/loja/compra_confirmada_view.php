<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>
            Escritório Virtual / 
            <?php
            //Nome Titulo
            if (isset(get_user()->di_nome)) {
                echo get_user()->di_nome . " / " . 'Detalhes da compra';
            } else {
                echo 'Detalhes da compra';
            }
            ?>
        </title>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/css/administracao.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/script/validar/css/validationEngine.jquery.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/Grid/framework.grid.css" />
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mask_moeda.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mascara.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/util/Grid/framework.grid.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>public/util/jquery-ui/css/ui-lightness/jquery-ui-1.9.2.custom.min.css" />
        <script src="<?php echo base_url() ?>public/util/jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/jquery.validationEngine.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/mask_moeda.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>public/script/validar/js/languages/jquery.validationEngine-pt_BR.js"></script>
        <script type="text/javascript">
            jQuery(function() {
                jQuery("form").validationEngine();
                $(".mdata").mask('99/99/9999');
                $(".mdata").datepicker({
                    dateFormat: "dd/mm/yy",
                    dayNamesMin: ["Do", "Se", "Te", "Qu", "Qu", "Se", "Sa"],
                    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]
                });

                $(".moeda").maskMoney({symbol: "R$", decimal: ",", thousands: "."})
            });


        </script>


        <style>
            .botao-pagamento {
                background-color:#5FB342;
                background-position:initial initial;
                background-repeat:initial initial;
                border:1px solid #40772F;
                color:#FFFFFF !important;
                display:block;
                font-weight:bold;
                margin:10px 0 0;
                max-width:136px;
                padding:8px;
                text-align:center;
                text-decoration:none;
            }

            #fbaixa{float:right;margin:0 0 0 0;}

            .botao-imprimir {
                background-color:#EEEEEE;
                background-position:initial initial;
                background-repeat:initial initial;
                border:1px solid #ccc;
                color:#333 !important;
                display:block;
                margin:10px 0 0;
                max-width:136px;
                padding:8px;
                text-align:center;
                text-decoration:none;
            }

            #topo{
                background-image:url(<?php echo URL_SITE; ?>public/imagem/layout/topo-header.png);
                background-position:50% 50%;
                background-repeat:no-repeat no-repeat;
                border-bottom-color:#BBBBBB;
                border-bottom-style:solid;
                border-bottom-width:1px;
                display:block;
                height:95px;
                margin:0 auto 10px;
                min-width:900px;
                padding:0 30px;
            }


            #topo img{
                margin:18px 0 0 10px;
            }

            #informe-valor-boleto{display:none;margin-top:6px;}
            #informe-valor-cielo{display:none;margin-top:6px;}

            .error{

                padding: 10px 10px 10px 43px;

                margin-bottom: 15px;

                color: #555555;

                -webkit-border-radius: 5px 5px 5px 5px;

                -moz-border-radius: 5px 5px 5px 5px;

                -khtml-border-radius: 5px 5px 5px 5px;

                border-radius: 5px 5px 5px 5px;

            }


            .error{
                background: #FFD1D1 url(<?php echo base_url() ?>public/imagem/warning.png) 10px center no-repeat;

                border: 1px solid #F8ACAC;

                -webkit-border-radius: 5px 5px 5px 5px;

                -moz-border-radius: 5px 5px 5px 5px;

                -khtml-border-radius: 5px 5px 5px 5px;

                border-radius: 5px 5px 5px 5px;

                width:1144px;
                margin:5px auto 10px;	
            }

            .sucesso{

                padding: 10px 10px 10px 43px;

                margin-bottom: 15px;

                color: #555555;

                -webkit-border-radius: 5px 5px 5px 5px;

                -moz-border-radius: 5px 5px 5px 5px;

                -khtml-border-radius: 5px 5px 5px 5px;

                border-radius: 5px 5px 5px 5px;

            }

            .sucesso {

                background: #EAF7D9 url(<?php echo base_url() ?>public/imagem/success.png) 10px center no-repeat;

                border: 1px solid #BBDF8D;

                -webkit-border-radius: 5px 5px 5px 5px;

                -moz-border-radius: 5px 5px 5px 5px;

                -khtml-border-radius: 5px 5px 5px 5px;

                border-radius: 5px 5px 5px 5px;

                width:1144px;
                margin:5px auto 10px;	

            }

            .button-baixa {
                -webkit-appearance:none;
                -webkit-transition:background-color 300ms ease-out;
                background-color:#EEE;
                border:1px solid #ccc;
                color:#333;
                cursor:pointer;
                display:inline-block;
                font-family:'Helvetica Neue', Helvetica, Helvetica, Arial, sans-serif !important;
                font-size:10px;
                font-weight:bold !important;
                line-height:normal;
                margin:0;
                padding:6px;
                position:relative;
                text-align:center;
                text-decoration:none;
                width:130px !important;
                transition:background-color 300ms ease-out;
                outline:none;
            }

            #fpagamento{
                width:120px;
                padding:10px;
                height:60px;
                display:block;
                background:#f4f4f4;
                border:1px solid #ccc;	
            }

            #fpagamento strong{
                display:block;
                width:100%;
                float:left;	
            }

            #label{
                display:block;
                padding:4px;
                margin:5px 0 5px 0;
                height:22px;
                background:#f0f0f0;
                border:1px solid #DDD;
                -webkit-border-radius: 5px; border-radius: 5px; -webkit-box-shadow:  0px 0px 2px 1px rgba(0, 0, 0, 0.2); box-shadow:  0px 0px 2px 1px rgba(0, 0, 0, 0.1);
            }



            #label-parcial{}

            #valor-total{display:block;}
            #valor-parcial{display:none;}
            .recebe-erro-valor-parcial{
                color:#F00;
                font-weight:bold;
                font-size:16px;
            }
        </style>

    </head>

    <body>

        <div id="topo">
            <img height="60px" src="<?php echo URL_SITE; ?>public/imagem/layout/logomarca.png"/>
        </div>

        <?php
        $nots = get_notificacao();

        foreach ($nots as $k => $n) {
            if ($n['tipo'] == 1) {
                ?>
                <div id="noti_<?php echo $k ?>" class="sucesso"><?php echo $n['mensagem'] ?></div>
                <script type="text/javascript">
                    jQuery(function() {
                        setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                    });
                </script>
            <?php } else if ($n['tipo'] == 2) { ?>
                <div id="noti_<?php echo $k ?>" class="error"><?php echo $n['mensagem'] ?></div>
                <script type="text/javascript">
                    jQuery(function() {
                        setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                    });
                </script>
                <?php
            }
        }
        ?>

        <div class="box-content min-height">
            <div class="box-content-header" style="font-size:18px;">

                Compra n° <?php echo $compra->order_id; ?> 

                <?php
                if (isset(get_user()->di_id) && !isset($_SESSION['administrador'])) {
                    ?>

                    <a style="float:right;margin-right:10px;" class="botao" href="<?php echo APP_BASE_URL . APP_LOJA ?>/index.php?route=account/order">Meus Pedidos</a>

                    <a style="float:right;margin-right:10px;" class="botao" href="<?php echo base_url() ?>">Ir para seu escritório</a>

                <?php } ?>

            </div>

            <div class="box-content-body">

                Nota: Ao realizar o pagamento da franquia não há possibilidade remover seu cadastro.
                <!--DETALHES DO CONSULTOR E PEDIDO-->
                <table class="list table table-bordered">

                    <thead>

                        <tr>

                            <td class="left" >Detalhes do Pedido</td>
                            <td class="left" >Envio</td>
                            <td class="left" >Entrega</td>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td class="left" style="width: 30%;">

                                <b>Consultor: </b> <?php echo isset($consultor->di_nome) ? $consultor->di_nome : $compra->firstname . " " . $compra->lastname ?><br />

                                <b>Adicionado em: </b> <?php echo $dataCompra; ?></td>

                            <td class="left" style="width: 30%;">

                                <b>Método de Pagamento: </b> <?php echo $compra->payment_method; ?><br />         



                                <b>Método de Envio:</b> <?php echo $compra->shipping_method; ?>

                            </td>

                            <td class="left" style="width: 30%;">
                                <?php
                                echo "<b>" . $compra->shipping_firstname . " " . $compra->shipping_lastname . "</b><br>";
                                echo $compra->shipping_address_1 . " " . $compra->shipping_address_2 . "<br>";
                                echo $compra->shipping_city . " - " . $compra->code . "<br>";
                                echo $compra->shipping_postcode;
                                ?> 
                            </td>

                        </tr>

                    </tbody>

                </table>


                <!--Detalhes da Compra-->
                <table width="100%" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
                    <tr style="background:#EEEEEE;">
                        <td width="33%">Produto</td>
                        <td width="9%">Quantidade</td>
                        <td>Pontos</td>
                        <td width="13%">Valor Unitário</td>
                        <td width="15%">Valor Total</td>
                    </tr>
                    <?php
                    $subTotal = 0;
                    $totalProdutos = 0;
                    $totalPontos = 0;
                    foreach ($produtos as $produto) {
                        $totalPontos += $produto->reward;
                        $totalProdutos += $produto->quantity;
                        $subTotal += $produto->quantity * $produto->price;

                        $optionsProduct = $this->db
                                        ->where('order_product_id', $produto->order_product_id)
                                        ->where('order_id', $produto->order_id)
                                        ->get('loja_order_option')->result();
                        ?>
                        <tr>

                            <td>
                                <strong><?php echo $produto->name ?></strong>
                                <i style="font-size:11px;">
                                    <?php foreach ($optionsProduct as $optionProduct) { ?>
                                        <div><b><?php echo $optionProduct->name ?>:</b> <?php echo $optionProduct->value ?></div>
                                    <?php } ?>
                                </i>
                            </td>
                            <td><?php echo $produto->quantity ?></td>
                            <td><?php echo $produto->reward; ?>
                                <td>R$ <?php echo number_format($produto->price, 2, ',', '.') ?></td>
                                <td>R$ <?php echo number_format($produto->price * $produto->quantity, 2, ',', '.') ?></td>   
                        </tr>
                    <?php } ?>

                    <tr>
                        <td style="text-align: right;"><b>Total produtos:</b></td>
                        <td><?php echo $totalProdutos; ?></td>
                        <td><?php echo $totalPontos; ?></td>
                        <td></td>
                        <td>
                    </tr>
                    <tr style="display:none;">
                        <td width="33%" style="border-right:none;"></td>
                        <td colspan="3"  style="border-left:none;text-align:right;"><span style="text-align:right;">Sub-Total:</span></td>
                        <td style="border-left:none;" >R$ <?php echo number_format($subTotal, 2, ',', '.') ?></td>
                    </tr> 

                    <?php foreach ($totalOrders as $totalOrder) { ?>
                        <tr>
                            <td width="33%" style="border-right:none;"></td>
                            <td colspan="3"  style="border-left:none;text-align:right;">
                                <span style="text-align:right;"><b><?php echo $totalOrder->title ?> :</b></span>
                            </td>
                            <td style="border-left:none;">R$ <?php echo number_format($totalOrder->value, 2, ',', '.') ?></td>
                        </tr>
                    <?php } ?>
                </table>

                <?php if ($compra->pay == 0 && isset(get_user()->di_id)) { ?>
                    <!--- Aqui vem a forma de pagamento --->
                    <h5>Escolha uma forma de pagamento</h5>

                <?php } ?>

                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0 && $compra->order_status_id <> 5) { ?>
                    <!--- Aqui vem a forma de pagamento --->
                    <h5>Gerênciar compra - <i><?php echo $statusAtual; ?></i></h5> 
                    <form action="<?php echo base_url('index.php/loja/baixar/' . $compra->order_id) ?>" method="post">
                        <?php if ($compra->order_status_id == 0) { ?>
                            Forma Pagamento:<br>
                                <select name="payment_method_id">
                                    <?php foreach ($formasPagamentosAtivas as $formaPagamento) { ?>
                                        <option value="<?php echo $formaPagamento->payment_method_id ?>"><?php echo $formaPagamento->description ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>    
                            <br>
                               
                                Situação: <br>
                                    <select name="status">
                                        <?php foreach ($OrderStatus as $status) { ?>
                                        <option  value="<?php echo $status->order_status_id ?>"><?php echo $status->name ?></option>
                                        <?php } ?>
                                    </select>
                                    <br>
                                        <input type="submit" class="btn btn-success" value="<?php echo $compra->order_status_id == 0 ? 'Baixar Pedido' : 'Alterar Situação'; ?>" />    
                                        </form>
                                    <?php } ?>

                                    <a class="botao-imprimir botao" href="javascript:void(0);">Imprimir</a>
                                    </div>
                                    </div>
                                    </body>
                                    <style media="print">
                                        .botao {
                                            display: none;
                                        }
                                        #obs{display: none;}
                                        #payefetuados{display: none;}
                                    </style>
                                    <script type="text/javascript">

                                        function hide_notificacao(id) {
                                            $(id).fadeOut(1000);
                                        }

                                        $('.botao-pagamento').click(function() {
                                            $("#form-pagamento").submit();
                                        })

                                        $('.botao-imprimir').click(function() {
                                            window.print();
                                        })

                                        $("#label-boleto").click(function() {
                                            $("#label-parcial").fadeIn();
                                            $("#label-valor-total").fadeIn();
                                        })

                                        $("#label-parcial").click(function() {
                                            $("#valor-parcial").fadeIn();
                                            $("#valor-total").fadeOut();
                                        })

                                        $("#label-valor-total").click(function() {
                                            $("#valor-parcial").fadeOut();
                                            $("#valor-total").fadeIn();

                                        })


                                        $(".field-valor-total").click(function() {
                                            $("#valor-parcial").fadeOut();
                                            $("#valor-total").fadeIn();
                                        })

                                        $(".field-valor-parcial").click(function() {
                                            $("#valor-parcial").fadeIn();
                                            $("#valor-total").fadeOut();
                                        })

                                    </script>

                                    </html>

                                    <?php set_notificacao(array()); ?>