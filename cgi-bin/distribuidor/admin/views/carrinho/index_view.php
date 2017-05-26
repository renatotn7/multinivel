<?php $this->lang->load('distribuidor/carrinho/carrinho'); ?>

<div class="box-content min-height border-radios">
    <div class="box-content-header">
        <a href="<?php echo base_url('index.php/comprar_cartao'); ?>"><?php echo $this->lang->line('label_titulo'); ?></a>
    </div>
    <div class="box-content-body">
        <table class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <td  ><strong><?php echo $this->lang->line('label_numero_produto'); ?></strong></td>
                    <td width="50%"><strong><?php echo $this->lang->line('label_produto'); ?></strong></td>
                    <td ><strong><?php echo $this->lang->line('label_quantidade'); ?></strong></td>
                    <!--<td width="17%" ><strong><?php // echo $this->lang->line('label_situacao');  ?></strong></td>-->
                    <!--<td ><strong><?php echo $this->lang->line('label_valor_total'); ?></strong></td>-->
                    <td ></td>
                </tr>
            </thead>
            <tr>
                <td colspan="3"></td>
                <td><a class="btn btn-success" href="<?php echo base_url('/index.php/pedidos/confirmar_pagamento_loja_interna'); ?>"><?php echo $this->lang->line('label_pagar'); ?></a></td>
            </tr>
            <?php
            $carrinho_compra = produtoModel::getProdutoComprados(get_user(), 7,null,false,0);
            $totalProdutos = 0.00;
            if (count($carrinho_compra) == 0) {
                ?>
                <tr>
                    <td colspan="5" style="text-align: center;"><?php echo $this->lang->line('label_nenhum_registro'); ?></td>
                </tr>
                <?php
            } else {
                foreach ($carrinho_compra as $carrinho) {
                    if ($carrinho->co_pago == 1) {
                        continue;
                    }

                    $totalProdutos+=($carrinho->pr_valor * $carrinho->pm_quantidade);
                    ?>
                    <tr>
                        <td><?php echo $carrinho->pr_id; ?></td>
                        <td><?php echo $carrinho->pr_nome; ?></td>
                        <td>
                            <input style="float: left;" class="span1" id="crt-<?php echo $carrinho->pm_id; ?>" name="pm_quantidade" onblur="atualiazarCarrinho(<?php echo $carrinho->pm_id; ?>);" value="<?php echo $carrinho->pm_quantidade; ?>" disabled="" />
                            <i style="display: table; cursor: pointer;" onclick="add(<?php echo $carrinho->pm_id; ?>);" class="icon-arrow-up"></i>
                            <i style="display: table; cursor: pointer;" onclick="retira(<?php echo $carrinho->pm_id; ?>);" class="icon-arrow-down"></i>
                        </td>
                        <td>
                            <strong>US$:</strong><?php echo number_format(($carrinho->pr_valor * $carrinho->pm_quantidade), 2); ?>
                            <?php echo $carrinho->co_pago == 1 ? '' : '<a href="' . base_url("index.php/loja/excluir_produto_carrinho?id_pedido={$carrinho->co_id}&id_prod={$carrinho->pr_id}") . '"><i class="icon-remove"></i></a>'; ?>
                            <?php // echo $carrinho->co_pago == 1 ? '' : '<a href="' . base_url("index.php/loja/excluir_produto_carrinho?id_pedido={$carrinho->co_id}") . '">< i class="icon-remove"></i>' . $this->lang->line('label_remover') . '</a>'; ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <td colspan="3" style="text-align: right;font-size: 22px;"><strong>Total</strong>:</td>
                <td><strong>US$:</strong><?php echo number_format($totalProdutos, 2); ?></td>
            </tr>


        </table>

    </div>

</div>
<script>
    function atualiazarCarrinho(cartnew) {
        var cart = cartnew;
        $.ajax({
            url: '<?php echo base_url('index.php/loja/atualizar_carrinho'); ?>',
            type: 'get',
            data: {cart: cart, pm_quantidade: $('#crt-' + cart).val()},
            success: function(data) {
                window.location = '<?php echo base_url('index.php/loja/carrinho_compra'); ?>';
            }
        });

    }

    function add(cartnew) {
        var cart = cartnew;
        $.ajax({
            url: '<?php echo base_url('index.php/loja/atualizar_carrinho'); ?>',
            type: 'get',
            data: {cart: cart, pm_quantidade: (parseInt($('#crt-' + cart).val()) + parseInt(1))},
            success: function(data) {
                window.location = '<?php echo base_url('index.php/loja/carrinho_compra'); ?>';
            }
        });
    }

    function retira(cartnew) {
        var cart = cartnew;
        $.ajax({
            url: '<?php echo base_url('index.php/loja/atualizar_carrinho'); ?>',
            type: 'get',
            data: {cart: cart, pm_quantidade: (parseInt($('#crt-' + cart).val()) - parseInt(1))},
            success: function(data) {
                window.location = '<?php echo base_url('index.php/loja/carrinho_compra'); ?>';
            }
        });
    }
</script>