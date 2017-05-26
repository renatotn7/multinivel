<?php
// linguagem
$this->lang->load('distribuidor/home/menu_view');
?>
<div id="corpo-menu-distribuidor">
    <div id="cont-menu">
        <center>
            <a class="logo-envix" href="<?php echo base_url() ?>">
                <img src="<?php echo base_url("public/imagem/logomarca.png"); ?>"  style="width: 225px;"/>
            </a>
        </center>
        <!-- Novo menu-->
        <img style="display:none;" src="<?php echo base_url() ?>public/imagem/menu-principal.jpg"  />

        <ul class="list-group menu-h">
            <li  class="list-group-item">
                <a class="micon-principal" href="<?php echo base_url() ?>">
                    <?php echo $this->lang->line('label_pagina_inicial'); ?>
                </a>
            </li>
            <li  class="list-group-item">
                <a class="micon-dados" href="<?php echo base_url('index.php/distribuidor/meus_dados') ?>">
                    <?php echo $this->lang->line('label_meus_dados'); ?>
                </a>
            </li>

            <?php
            if (ConfigSingleton::getValue("loja_externa_liberada") == 1 || get_user()->di_id == 5698) {
                ?>
                <li  class="list-group-item" >
                    <a class="micon-pedidos" target="_blank" href="<?php echo base_url('index.php/acessar_loja') ?>">
                        <?php echo $this->lang->line('label_loja'); ?>
                    </a>
                </li>
                <?php
            }
            ?>

            <li  class="list-group-item">
                <a class="micon-pedidos" href="javascript:void(0)">
                    <?php echo $this->lang->line('label_meus_pedidos'); ?>
                </a>
                <ul>
                    <li >
                        <a href="<?php echo base_url('index.php/pedidos') ?>">
                            <?php echo $this->lang->line('label_meu_pedido'); ?>
                        </a>
                    </li>
                    <?php if (get_user()->distribuidor->getAtivo() != 0) { ?>
                        <li  style="display:none;"><a href="<?php echo base_url('index.php/loja/para_meu_id') ?>"><?php echo $this->lang->line('label_fazer_pedido'); ?></a></li>
                        <li  ><a href="<?php echo base_url('index.php/pacotes') ?>"><?php echo $this->lang->line('label_meus_planos'); ?></a></li>
                        <?php
                        $binario = new Binario(get_user());
                        if ($binario->e_binario() != false) {
                            ?>
                            <li><a href="<?php echo base_url('index.php/comprar_cartao') ?>"><?php echo $this->lang->line('label_compra_cartao'); ?></a></li>

                        <?php } ?>
                    <?php } ?>
                </ul>
            </li>
            <?php if (get_user()->distribuidor->getAtivo() != 0) { ?>
                <li id="li-minha-rede"  class="list-group-item">
                    <a class="micon-redes" href="javascript:void(0)">
                        <?php echo $this->lang->line('label_minha_rede'); ?>
                        <div id="marcador-minha-rede"></div>
                    </a>
                    <ul>
                        <li><a href="<?php echo base_url('index.php/distribuidor/rede_linear') ?>"><?php echo $this->lang->line('label_minha_equipe'); ?></a></li>
                        <li><a href="<?php echo base_url('index.php/distribuidor/pendentes') ?>"><?php echo $this->lang->line('label_cadastro_pendentes'); ?></a></li>
                    </ul>
                </li>
            <?php } ?>

            <li  class="list-group-item" >
                <a class="micon-extrato" href="<?php echo base_url('index.php/bonus/extrato') ?>">
                    <?php echo $this->lang->line('label_banco'); ?>
                </a>
            </li>

        </ul>
        <div style="background:#18539A; height:20px; display:none;"></div>

        <?php $this->load->view('home/infor_home_view') ?>
        <script>
            $(".menu-h li").click(function() {
                if ($(this).find('ul').css('display') != 'undefined') {
                    $(".menu-h li ul").slideUp(500);
                    $(this).find('ul').slideDown(500);
                }
            });
        </script>
