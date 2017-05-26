<?php $this->lang->load('distribuidor/home/menu_view'); ?>
<div class="menu_section">
    <h3>Menu</h3>
    <ul class="nav side-menu">
        <li>
            <a href="<?php echo base_url() ?>">
                <i class="fa fa-home"></i> <?php echo $this->lang->line('label_pagina_inicial'); ?>
            </a>
        </li>
                        <li>
                    <a href="<?php echo base_url('index.php/bonus/transferir') ?>">
                        <i class="fa fa-dollar"></i>
                        Transferência de Saldo
                    </a>
                </li>
        <li>
            <a href="<?php echo base_url('index.php/distribuidor/meus_dados') ?>">
                <i class="fa fa-id-card-o"></i> <?php echo $this->lang->line('label_meus_dados'); ?>
            </a>
        </li>
        <?php /*if (ConfigSingleton::getValue("loja_externa_liberada") == 1 || get_user()->di_id == 18) { ?>
        <li>
            <a target="_blank" href="<?php echo base_url('index.php/acessar_loja') ?>">
                <i class="fa fa-shopping-cart"></i> <?php echo $this->lang->line('label_loja'); ?>
            </a>
        </li>
        <?php }*/ ?>
        <?php
        $binario = new Binario(get_user());
        if ($binario->e_binario() != false) {
        ?>
        <li>
            <a href="#<?php //echo base_url('index.php/comprar_cartao') ?>">
                <i class="fa fa-shopping-cart"></i><?php echo $this->lang->line('label_compra_cartao'); ?>
            </a>
        </li>
        <?php } ?>
        <li>
            <a>
                <i class="fa fa-shopping-basket"></i> <?php echo $this->lang->line('label_meus_pedidos'); ?> <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu">
                <li >
                    <a href="<?php echo base_url('index.php/pedidos') ?>">
                        <?php echo $this->lang->line('label_meu_pedido'); ?>
                    </a>
                </li>
                <?php
                $users_liberado = array(6,7,8,18, 82, 20, 166, 38, 159, 49, 108, 86, 19, 252, 43, 35, 22, 336);
                //if (in_array(get_user()->di_id, $users_liberado)) { ?>
                <li>
                    <a href="<?php echo base_url('index.php/loja/opcao_pagamento') ?>">
                        <i class="fa fa-dollar"></i>
                        <?php echo $this->lang->line('label_pagar_pedido'); ?>
                    </a>
                </li>

                <?php //} ?>
            <?php if (get_user()->distribuidor->getAtivo() != 0) { ?>
                <li>
                    <a href="<?php echo base_url('index.php/loja/para_meu_id') ?>">
                        <?php echo $this->lang->line('label_fazer_pedido'); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url('index.php/pacotes') ?>">
                        <?php echo $this->lang->line('label_meus_planos'); ?>
                    </a>
                </li>
            <?php } ?>
            </ul>
        </li>
        <?php if (get_user()->distribuidor->getAtivo() != 0) { ?>
        <li>
            <a>
                <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('label_minha_rede'); ?> <span class="fa fa-chevron-down">
            </a>
            <ul class="nav child_menu">
                <li>
                    <a href="<?php echo base_url('index.php/distribuidor/minha_rede') ?>"><?php echo $this->lang->line('label_minha_rede'); ?></a>
                </li>
                <li>
                    <a href="<?php echo base_url('index.php/distribuidor/rede_linear') ?>"><?php echo $this->lang->line('label_minha_equipe'); ?></a>
                </li>
                <li>
                    <a href="<?php echo base_url('index.php/distribuidor/pendentes') ?>"><?php echo $this->lang->line('label_cadastro_pendentes'); ?></a>
                </li>
            </ul>
        </li>
        <?php } ?>
        <li>
            <a href="<?php echo base_url('index.php/bonus/extrato') ?>">
                <i class="fa fa-bank"></i> <?php echo $this->lang->line('label_banco'); ?>
            </a>
        </li>
        <li>
            <a href="<?php echo base_url('index.php/distribuidor/upgrade_plano'); ?>">
                <i class="fa fa-line-chart"></i> Upgrade
            </a>
        </li>
    </ul>
</div>