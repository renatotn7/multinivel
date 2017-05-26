<?php $this->lang->load('distribuidor/bonus/meu_saldo_view'); ?>
<?php
if (verificar_permissao_acesso(false)) {
    ?>
    <div class="alert alert-warning">
        <?php echo $this->lang->line('label_notificacao_bloqueio'); ?>

    </div>
    <?php
    exit;
}
    /**
     * Verifica qual tipo de pagamento.
     * 1 com bônus do backoffice
     * e nada para pagamento de bônus.
     */
     $type = isset($_REQUEST['type']) && $_REQUEST['type']?$_REQUEST['type']:'';
?>
</h3>
<form action="<?php echo base_url('index.php/pedidos/confirmar_pagamento');?>" method="get" style="margin-bottom:0;">
    Informe o Número do pedido:<br>
    <div class="input-append" style="margin-bottom:0;">
        <input type="hidden"  name="type" value="<?php echo $type;?>" >
        <input  type="text" name='id_pedido' class="validade[required]" placeholder='Digite o número do Pedido'>
        <button class="btn btn-danger" type="submit">OK</button>
    </div>
</form>