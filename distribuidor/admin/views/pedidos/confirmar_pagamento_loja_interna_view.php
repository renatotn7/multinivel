<?php
$this->lang->load('distribuidor/pedidos/confirmar_pagamento');
//Verificando se tem pedidos 
$c = get_user();

if ($this->input->get('pedido')) {
    $c = $this->db->where('co_id', $this->input->get('pedido'))
                    ->join('distribuidores', 'co_id_distribuidor=di_id')
                    ->get('compras')->result();
}


$produtosComprados = produtoModel::getProdutoComprados($c, 7);


$url_base = '';
$cartao_execao = array('1');
$pais = DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id;
$moeda = DistribuidorDAO::getMoeda($pais)->ps_moeda;
$plano = PlanosModel::getPlanoDistribuidorNaoPago(get_user()->di_id);
?>
<div class="box-content min-height">
    <div class="box-content-header"><?php echo $this->lang->line('label_comfirmar_pagmento_pedido'); ?></div>
    <div class="box-content-body">
        <div class="panel">
            <?php if (count($produtosComprados) == 0) { ?>
                <p class="alert alert-info"><?php echo $this->lang->line('label_nenhum_pedido_encontrado'); ?></p>
                <?php
            } else {
                
                ?>


                <form name="form1" method="post" action="<?php echo base_url('index.php/loja/pagar_transparente'); ?>">
                    <?php if (isset($_REQUEST['paymentMethod'])) { ?>
                        <input type="hidden" id="paymentMethod" name="paymentMethod" value="<?php echo $_REQUEST['paymentMethod']; ?>"/>    
                        <input type="hidden" id="debitCardAccessCode" name="debitCardAccessCode" value="<?php echo isset($_REQUEST['debitCardAccessCode']) ? $_REQUEST['debitCardAccessCode'] : ''; ?>"/>    
                    <?php } ?>
                    <input type="hidden" name="c" value="<?php echo $produtosComprados[0]->pm_id_compra ?>">
                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td width="70%" VALIGN="top">
                                <table class="table table-bordered">
                                    <tr>
                                        <td><?php echo $this->lang->line('label_produto'); ?></td>
                                        <td><?php echo $this->lang->line('label_quantidade'); ?>.:</td>
                                        <td><?php echo $this->lang->line('label_valor_unitario'); ?>.</td>
                                        <td><?php echo $this->lang->line('label_total'); ?></td>
                                    </tr>
                                    <?php
                                    $valorTota = 0.00;
                                    foreach ($produtosComprados as $key => $pr_value) {
                                        $valorTota+= ($pr_value->pm_quantidade * $pr_value->pr_valor);
                                        ?>
                                        <tr>
                                            <td width="52%"><?php echo $pr_value->pr_nome; ?> </td>
                                            <td width="8%"><?php echo $pr_value->pm_quantidade; ?> </td>
                                            <td width="20%">US$: <?php echo number_format($pr_value->pr_valor, 2); ?> </td>
                                            <td width="20%">US$: <?php echo number_format(($pr_value->pr_valor * $pr_value->pm_quantidade), 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>
                                            <strong><?php echo $this->lang->line('label_total'); ?>: US$:</strong> <?php echo number_format($valorTota, 2); ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                            <td width="30%" VALIGN="top">
                                <div class="alert">
                                    <strong><?php echo $c->di_nome ?> (<?php echo $c->di_usuario ?>)</strong><br />
                                    <?php echo $c->di_endereco ?><br />
                                    <?php echo $c->di_bairro ?>, <?php echo $this->lang->line('label_cep'); ?>: <?php echo $c->di_cep ?><br />
                                    <?php echo DistribuidorDAO::getCidade($c->di_cidade)->ci_nome; ?>-<?php echo DistribuidorDAO::getEstado(DistribuidorDAO::getCidade($c->di_cidade)->ci_estado)->es_uf; ?><br />                                 
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>

                                <?php if (!isset($_REQUEST['paymentMethod'])) { ?>
                                    <h4><?php echo $this->lang->line('label_escolha_forma_pagamento'); ?>.</h4>

                                    <?php if (!in_array($pais, $cartao_execao)) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod" type="radio" name="opcao-cartao" value="2" />
                                            <h4 style="margin-left: 0px;float: left;margin-top: -5px;">
                                                <?php echo $this->lang->line('label_new_cart_intercash'); ?> <i style="color:green"><?php echo $this->lang->line('label_nota_saldo_e_ediz'); ?></i> 
                                            </h4><br>
                                            <p style=" font-size: 12px; float: left; margin-top: -10px;width: 380px;color: red;">
                                                <?php echo $this->lang->line('label_nota_intercash'); ?>
                                            </p><br>
                                        </label>   
                                        <input type="password" name="debitCardAccessCode" id="debitCardAccessCode" placeholder="<?php echo $this->lang->line('label_nota_input_intercash'); ?>" value=""/>
                                    <?php } ?>

                                    <?php if (!in_array($pais, array(253, 48, 168, 61))) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod"  type="radio" name="opcao-cartao" value="0" />
                                            <?php echo $this->lang->line('label_saldo_descricao_saldo_empresa'); ?>
                                        </label>
                                    <?php } ?>  
                                    <?php if ($pais == 1) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod"  type="radio" name="opcao-cartao" value="3" />
                                            <?php echo $this->lang->line('label_saldo_descricao_boleto'); ?>
                                        </label>
                                    <?php } ?>  
                                    <?php if ($pais != 61 && $pais != 48 && $pais != 168 && $pais != 63 && $pais != 253) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod"  type="radio" name="opcao-cartao" value="13-0" />
                                            <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dorla'); ?>
                                        </label>
                                    <?PHP } ?>
                                    <?php if ($moeda == 'Euro' && $pais != 1 || $pais == 253 || $pais == 63) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-1" />
                                            <?php echo $this->lang->line('label_saldo_descricao_tranferecia_euro'); ?>
                                        </label>
                                    <?php } ?>
                                    <?php if ($pais == 61) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-2" />
                                            <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso'); ?>
                                        </label>
                                    <?php } ?>
                                    <?php if ($pais == 48) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-3" />
                                            <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso_col'); ?>
                                        </label>
                                    <?php } ?>
                                    <?php if ($pais == 168) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-4" />
                                            <?php echo $this->lang->line('label_saldo_descricao_tranferecia_soles'); ?>
                                        </label>  
                                    <?php } ?>
                                    <?php if ($pais == 63 or $pais == 253) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-5" />
                                            <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dolar_eq'); ?>
                                        </label>    
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td width="30%" VALIGN="top">
                                <!--espaço reservado a propaganda--> 
                            </td>
                        </tr>
                    </table>
                    <br>
                    <br>

                    <?php echo $this->lang->line('label_senha_seguranca'); ?>:<br>
                    <input type="password" name="senha">
                    <br>
                    <button class="btn btn-large btn-success" type="submit"> 
                        <?php echo $this->lang->line('label_confirmar_pagamento'); ?>
                    </button>
                    <a class="btn" href="<?php echo base_url() ?>">
                        <?php echo $this->lang->line('label_cancelar'); ?>
                    </a>
                </form>

            <?php } ?>

        </div> 
    </div>
</div> 