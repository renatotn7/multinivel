<?php
$this->lang->load('distribuidor/pedidos/confirmar_pagamento');

$c = $this->db
    ->join('planos', 'pa_id=co_id_plano', 'left')
    ->join('distribuidores', 'di_id=co_id_distribuidor')
    ->join('cidades', 'di_cidade=ci_id')
    ->where('co_id', get_parameter('id_pedido'))
    ->where('co_total_valor >=', 0.01, false)
    ->get('compras')->row();

if (count($c) > 0) {
    $compraModel = new ComprasModel($c);
    $valorCompra = $compraModel->valorCompra();
}

$url_base = '';
$cartao_execao = array('1');
$pais = DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id;
$moeda = DistribuidorDAO::getMoeda($pais)->ps_moeda;
$plano = PlanosModel::getPlanoDistribuidorNaoPago(get_user()->di_id);
?>
<div class="page-title">
    <div class="title_left">
        <h3><?php echo $this->lang->line('label_comfirmar_pagmento_pedido'); ?></h3>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
            <?php if (count($c) == 0) { ?>
                <p class="alert alert-info"><?php echo $this->lang->line('label_nenhum_pedido_encontrado'); ?></p>
            <?php
            } else {
                if ($c->co_pago == 1) {
                ?>
                    <p class="alert alert-info"><?php echo $this->lang->line('label_pedido_pago'); ?></p>
                <?php } else { ?>

                    <?php
                    $url_base = base_url('index.php/loja/pagar_transparente');
                    if (isset($_REQUEST['paymentMethod']) && $_REQUEST['paymentMethod'] == 1) {
                        $url_base = base_url('index.php/pedidos/pagar_pedido_com_bonus');
                    }
                    ?>

                    <form name="form1" id="form1" method="post" action="<?php echo $url_base; ?>">
                        <?php if (isset($_REQUEST['paymentMethod'])) { ?>
                            <input type="hidden" id="paymentMethod" name="paymentMethod" value="<?php echo $_REQUEST['paymentMethod']; ?>"/>
                            <input type="hidden" id="debitCardAccessCode" name="debitCardAccessCode" value="<?php echo isset($_REQUEST['debitCardAccessCode']) ? $_REQUEST['debitCardAccessCode'] : ''; ?>"/>
                        <?php } ?>
                        <input type="hidden" name="c" value="<?php echo $c->co_id ?>">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                <strong><?php echo $c->di_nome ?> (<?php echo $c->di_usuario ?>)</strong><br />
                                <?php echo $c->di_endereco ?><br />
                                <?php echo $c->di_bairro ?>, <?php echo $this->lang->line('label_cep'); ?>: <?php echo $c->di_cep ?><br />
                                <?php echo $c->ci_nome ?>-<?php echo $c->ci_uf ?><br />
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                <b><?php echo $this->lang->line('label_data'); ?>:</b> <?php echo date('d/m/Y H:i:s', strtotime($c->co_data_compra)) ?><br />
                                <b><?php echo $this->lang->line('label_num_pedido'); ?>:</b> <?php echo $c->co_id; ?><br />
                                <b><?php echo $this->lang->line('label_valor'); ?>:</b> US$ <?php echo number_format($valorCompra, 2, ',', '.'); ?><br />
                                <?php
                                $produto = $this->db->query("select * from produtos
                                                         join produtos_comprados on pm_id_produto=pr_id
                                                         where pm_id_compra={$c->co_id}")->row();

                                if (count($produto) > 0) {
                                    ?>
                                    <b><?php echo $this->lang->line('label_descricao'); ?> :</b> <?php echo $produto->pr_nome ?><br />
                                <?php } ?>
                            </div>
                            <?php if (!isset($_REQUEST['paymentMethod'])) { ?>
                                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                    <h4><strong>Escolha forma de pagamento.</strong></h4>
                                    <ul>
                                        <?php if (!in_array($pais, array(48, 61, 168, 253))) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod" class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" value="0" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_saldo_empresa'); ?>
                                                </label>
                                            </li>
                                        <?php } ?>
                                        <?php if ($pais == 1) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod" class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" value="3" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_boleto'); ?>
                                                </label>
                                            </li>
                                        <?php } ?>
                                        <?php if (!in_array($pais, array(48, 61, 63, 168, 253))) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod" class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" value="13-0" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dorla'); ?>
                                                </label>
                                            </li>
                                        <?PHP } ?>
                                        <?php
                $users_liberado = array(6,7,8,18, 82, 20, 166, 38, 159, 49, 108, 86, 19, 252, 43, 35, 22, 336);
                                        //if (in_array(get_user()->di_id, $users_liberado)) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod" class="pagamento" data-url="<?php echo base_url('index.php/pedidos/pagar_pedido_com_bonus'); ?>" type="radio" value="1" />
                                                    <?php echo $this->lang->line('label_pagamento_com_bonus'); ?>
                                                </label>
                                            </li>
                                        <?php //} ?>
                                        <?php /*if (!in_array($pais, $cartao_execao)) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod"  class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" name="opcao-cartao" value="2" />
                                                    <h4 style="margin-left: 0px;float: left;margin-top: -5px;">
                                                        <?php echo $this->lang->line('label_new_cart_intercash'); ?> <i style="color:green"><?php echo $this->lang->line('label_nota_saldo_e_ediz'); ?></i>
                                                    </h4><br>
                                                    <p class="red"><?php echo $this->lang->line('label_nota_intercash'); ?></p>
                                                </label>
                                                <input type="password" name="debitCardAccessCode" id="debitCardAccessCode" placeholder="<?php echo $this->lang->line('label_nota_input_intercash'); ?>" value=""/>
                                            </li>
                                        <?php }*/ ?>
                                        <?php /*if ($moeda == 'Euro' && $pais != 1 || $pais == 253 || $pais == 63) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod"  class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" name="opcao-cartao" value="13-1" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_euro'); ?>
                                                </label>
                                            </li>
                                        <?php }*/ ?>
                                        <?php /*if ($pais == 61) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod"  class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" name="opcao-cartao" value="13-2" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso'); ?>
                                                </label>
                                            </li>
                                        <?php }*/ ?>
                                        <?php /*if ($pais == 48) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod"  class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" name="opcao-cartao" value="13-3" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso_col'); ?>
                                                </label>
                                            </li>
                                        <?php }*/ ?>
                                        <?php /*if ($pais == 168) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod"  class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" name="opcao-cartao" value="13-4" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_soles'); ?>
                                                </label>
                                            </li>
                                        <?php }*/ ?>
                                        <?php /*if ($pais == 63 or $pais == 253) { ?>
                                            <li>
                                                <label class="radio">
                                                    <input name="paymentMethod"  class="pagamento" data-url="<?php echo base_url('index.php/loja/pagar_transparente'); ?>" type="radio" name="opcao-cartao" value="13-5" />
                                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dolar_eq'); ?>
                                                </label>
                                            </li>
                                        <?php }*/ ?>
                                    </ul>
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('label_senha_seguranca'); ?></label>
                                        <input type="password" name="senha" class="form-control">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <button class="btn btn-large btn-success" type="submit">
                                <?php echo $this->lang->line('label_confirmar_pagamento'); ?>
                            </button>
                            <a class="btn btn-default" href="<?php echo base_url() ?>">
                                <?php echo $this->lang->line('label_cancelar'); ?>
                            </a>
                        </div>
                    </form>
                <?php
                }
            }
            ?>
            </div>
        </div>
    </div>
</div>
<script>
    $('.pagamento').on('click', function () {
        $('#form1').attr('action', $(this).data('url'));
    });
</script>