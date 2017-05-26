<?php
//Coloca o id do pais para liberar a permissão.
$cartao_execao = array('1');
$saldo_atm = array(1, 2);
$boleto = array(1);
$voucher = array(1, 2, 100);

$pais = DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id;
$moeda = DistribuidorDAO::getMoeda($pais)->ps_moeda;
$plano = PlanosModel::getPlanoDistribuidorNaoPago(get_user()->di_id);

$cartao_atm = $this->db->where('ccm_niv', get_user()->di_niv)
                ->get('compra_cartao_memberships')->row();

$valor_compra_taxa = $this->db->where('co_id_distribuidor', get_user()->di_id)
                ->where('co_tipo', 100)->where('co_pago', 0)
                ->get('compras')->row();

$valor_taxa = PlanosModel::getPlano(99);

if (count($valor_taxa) > 0) {
    $valor_taxa = $valor_taxa->pa_valor;
}

$objcambio = $this->db->where('camb_id_pais', $pais)
        ->join('moedas', 'moe_id=camb_id_moedas')
        ->join('pais', 'ps_id=camb_id_pais')
        ->get('moeda_cambio')
        ->row();
?>

<div class="row" style="margin-left: 20px;margin-top: -25px;">
    <div class="span">
        <h3>
            <i>
                <?php echo $this->lang->line('label_saladacao'); ?> : <?php echo get_user()->di_nome; ?> 
            </i>
        </h3>
        <strong><?php echo $this->lang->line('label_numero_pedido'); ?></strong>: <?php echo $compraNaoPaga->co_id; ?>
        <br/>
        <strong style="float: left;margin-right: 8px;">
            <?php echo $this->lang->line('label_valor_total'); ?>: 
        </strong>
        <table borde="0">
            <?php
            if (count($objcambio) > 0) {
                echo ' <tr><td>' . $this->lang->line('label_valor_cambio_dolar') . ' </td><td> ' . valor_plano_percetual_tx($plano->pa_id, $objcambio) . '</td></tr>';
                echo ' <tr><td> ' . $this->lang->line('label_valor_cambio_euro_dolar') . ' </td><td> ' . valor_plano_euro_relacao_dolar($plano->pa_id, $objcambio) . '</td></tr>';
                $moeda_local = valor_plano_relacao_dolar($plano->pa_id, $objcambio);
                if ($moeda_local != false) {
                    echo ' <tr><td> ' . $this->lang->line('label_valor_cambio_moeda_local') . ' </td><td> ' . $moeda_local . '</td></tr>';
                }
            } else {
                ?>
                <tr><td><?php echo $this->lang->line('label_valor_cambio_dolar'); ?></td><td>US$ <?php echo $plano->pa_valor; //($plano->pa_id != 99 ? $plano->pa_valor + $valor_taxa : ); ?> </td></tr>
                <tr><td><?php echo $this->lang->line('label_valor_cambio_euro_dolar'); ?></td><td> € <?php echo number_format($plano->pa_valor_euro, 2); ?> </td></tr>
            <?php } ?>

        </table>
        <strong>*<?php echo $this->lang->line('label_notificacao_taxa'); ?></strong><br/><br/>
        <p><?php echo $this->lang->line('label_info'); ?></p>
    </div>

    <div class="row">
        <div class="span8">
            <p>
                <?php echo $this->lang->line('label_descicao_intecash'); ?>
            </p>
        </div>
    </div>
    <table>
        <tr>
            <td width="280px" valign="top">
                <?php
                switch ($plano->pa_id) {
                    case 99:
                        $imagem = $this->lang->line('url_binario_membership');
                        break;
                    case 100:
                        $imagem = $this->lang->line('url_binario_fast_inativo');
                        break;
                    case 101:
                        $imagem = $this->lang->line('url_binario_esmeralda_inativo');
                        break;
                    case 102:
                        $imagem = $this->lang->line('url_binario_rubi_inativo');
                        break;
                    case 103:
                        $imagem = $this->lang->line('url_binario_diamante_inativo');
                        break;
                    case 104:
                        $imagem = $this->lang->line('url_binario_diamante_inativo');
                        break;
                }
                ?>
                <img src="<?php echo base_url('public/imagem/' . $imagem); ?>" />
                <?php
                $comboPacote = combopacoteModel::getComboPacotesPorPlano($plano->pa_id);
                if (count($comboPacote) > 0) {
                    ?>
                    <h4><stiong><?php echo $comboPacote->pn_descricao; ?></stiong>:</h4>
                <?php } ?>
                <div class="row">
                    <div class="span5">
                        <ol>
                            <?php
                            $produtos_combo = combopacoteModel::getProdutosCombo($comboPacote->pn_id);
                            if (count($produtos_combo) > 0) {
                                foreach ($produtos_combo as $key => $produto_combo) {
                                    ?>
                                    <li><?php echo $produto_combo->pr_nome; ?> </li>
                                    <?php
                                }
                            }
                            ?>
                        </ol>
                    </div>
                </div>
            </td>
            <td>
                <form name="pagamento-cartao" target="_self" method="post" 
                      action="<?php echo base_url('index.php/pedidos/confirmar_pagamento?id_pedido=' . $compraNaoPaga->co_id); ?>" 
                      id="pagamento-cartao"
                      >
                          <?php
//Gera as parcelas e do usuário. e retorna true se tem essa opção.
                          if (ComprasModel::pais_permiter_parcelamento(get_user()) && false) {
                              ?>
                        <h4><?php echo $this->lang->line("label_pagamento_parcelado"); ?></h4>
                        <i><?php echo $this->lang->line("label_consulter_termo_aqui"); ?></i>

                        <!-- Modal -->
                        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">Atenção</h3>
                            </div>
                            <div class="modal-body">
                                <b><?php echo $this->lang->line('texto_termo'); ?></b>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>

                        <br/>
                        <strong>
                            <?php $parcela = ComprasModel::get_entrada_e_parcela_com_tx(get_user()); ?>   
                            <input type="radio" name="co_parcelado" id="co_parcelado" checked value="0"/> 
                            <?php if (count($objcambio) > 0) { ?>
                                US$: (<?php echo valor_plano_percetual_tx($plano->pa_id, $objcambio); ?>)<br>
                            <?php } else { ?>
                                US$: (<?php echo number_format($plano->pa_valor, 2); //($plano->pa_id != 99 ? $plano->pa_valor + $valor_taxa : $plano->pa_valor);   ?>)<br>
                            <?php } ?>
                            <input type="radio" name="co_parcelado" id="co_parcelado" value="1"/>
                            US$: (<?php echo $parcela->co_valor_entrada; ?>) + <?php echo ComprasModel::pode_parcelar(get_user()); ?> X (<?php echo $parcela->cof_valor; ?>)
                        </strong> 

                    <?php } ?>
                    <br/>
                    <h4 >
                        <?php echo $this->lang->line('label_intercache'); ?>
                    </h4>
                    </div>
                    <br/>
                    <br/>
                    <div class="row">
                        <table>
                            <tr>
                                <td>
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
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4 style="margin-left: 21px;">
                                        <?php echo $this->lang->line('label_e_pay'); ?> 
                                    </h4>

                                    <?php if (!in_array($pais,array(253,48,168,61))) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod"  type="radio" name="opcao-cartao" value="0" />
                                            <?php echo $this->lang->line('label_saldo_descricao_saldo_empresa'); ?>
                                            <span style=" font-size: 12px; margin-top: -10px;width: 380px;color: red;">
                                                <?php echo $this->lang->line('label_nota_saldo_e_wallet'); ?>
                                            </span>
                                        </label>
                                    <?php } ?>


                                    <?php if (in_array($pais, $boleto)) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod"  type="radio" name="opcao-cartao" value="3" />
                                            <?php echo $this->lang->line('label_saldo_descricao_boleto'); ?> 
                                        </label>
                                    <?php } ?>
                                    <?php // if ($moeda !== 'Euro' && $pais != 61 && $pais != 48 && $pais != 168 && $pais != 63 && $pais != 1) { ?>
                                        <label class="radio">
                                            <input name="paymentMethod"  type="radio" name="opcao-cartao" value="13-0" />
                                            <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dorla'); ?> 
                                        </label>
                                    <?PHP //} ?>
                                    <?php  if ($moeda == 'Euro' && $pais != 1 || $pais==253 || $pais==63) { ?>
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
                                    <label class="radio">
                                        <input name="paymentMethod"  type="radio" value="1" />
                                        <?php echo $this->lang->line('label_pagamento_com_bonus'); ?> 
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>
<!--                                    <label class="radio">
                                        <input name="paymentMethod" type="radio" name="opcao-cartao" value="109" />
                                        <h4>
                                            <?php // echo $this->lang->line('label_evoucher'); ?>
                                        </h4>

                                        <p style=" font-size: 12px; float: left; margin-top: -10px;width: 380px;color: red;">
                                            <?php //echo $this->lang->line('label_nota_evouche'); ?>
                                        </p>
                                    </label>-->
                                </td>
                            </tr>
                            <tr>
                                <td> <button type="submit" class="btn btn-success pull-right">  <?php echo $this->lang->line('label_enviar_apagamento'); ?></button></td>
                            </tr>
                        </table>

                    </div>
                </form> 

            </td>
        </tr>
    </table>
    <script type="text/javascript">

        //inicializa a função do modal.
        $(document).on('click', '.abre_modal', function() {
            if ($('#myModal').hasClass('in')) {
                $('#myModal').modal('hide');
            } else {
                $('#myModal').modal('show');
            }

        });

    </script>