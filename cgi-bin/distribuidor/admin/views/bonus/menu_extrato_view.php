<?php $this->lang->load('distribuidor/bonus/menu_extrato_view'); ?>
<ul class="nav nav-tabs">

    <?php if (!verificar_permissao_acesso(false)) { ?>
        <li <?php echo $active == 'meu_saldo' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/extrato') ?>"><?php echo $this->lang->line('label_meu_saldo'); ?></a></li>
    <?php } ?> 
    <?php if (!verificar_permissao_acesso(false)) { ?>
        <li <?php echo $active == 'transacoes' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/transacoes') ?>"><?php echo $this->lang->line('label_historico_transacao'); ?></a></li>
    <?php } ?>
    <?php if (!verificar_permissao_acesso(false) && verificar_compra_valor_acima_permitido()) { ?>
        <li <?php echo $active == 'transferencia_usuarios' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/transferencia_usuarios') ?>"><?php echo $this->lang->line('label_transferencia_entre_usuarios'); ?></a></li>
    <?php } ?>
    <li <?php echo $active == 'por_categoria' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/por_categoria') ?>"><?php echo $this->lang->line('label_historico_bonus'); ?></a></li>
    <li <?php echo $active == 'credito' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/credito') ?>"><?php echo $this->lang->line('label_relatorio_bonus'); ?></a></li>
    <li <?php echo $active == 'token_loja' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/token_loja') ?>"><?php echo $this->lang->line('label_codigo_loja_interna'); ?></a></li>
    <li <?php echo $active == 'codigo_promocional' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/codigo_promocional_ativacao') ?>"><?php echo $this->lang->line('label_codigo_promocional'); ?></a></li>

    <!--So vai mostrar se o país permitir o parcelamento.-->
    <?php if (ComprasModel::compra_foi_parcelada(get_user())) { ?>
        <li <?php echo $active == 'parcelas' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/parcelas') ?>"><?php echo $this->lang->line('label_parcelas'); ?></a></li>
    <?php } ?>

        <li <?php echo $active == 'historico_ativacao' ? 'class="active"' : '' ?>><a href="<?php echo base_url('index.php/bonus/historico_ativacao') ?>"><?php echo $this->lang->line('label_ativacao_mensal'); ?></a></li>
    <li></li>
</ul>