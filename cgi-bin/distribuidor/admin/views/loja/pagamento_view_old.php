<?php $this->lang->load('distribuidor/loja/loja_view'); ?>
<div class="box-content min-height">
    <div class="box-content-header">Formas de pagamento</div>
    <div class="box-content-body">

        <?php
        if ($compra->co_pago == 0) {

            $bonus = $this->db->query("
SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
WHERE cb_distribuidor = " . get_user()->di_id . "
")->row();


            $valor_compra = $valorTotalCompra;

            $formas_pagamento = array('bonus');
            $quantidadeDeFormaPagamento = 0;

            $dolarPagamentoBonus = $this->db->where('field', 'cotacao_dolar')->get('config')->row();
            ?>

            <style>
                .foma-pagamento{
                    margin-top: 15px;
                    background: #F1F1F1;
                    border-top: 2px solid #DDDDDD;
                    border-left: 2px solid #DDDDDD;
                    padding: 10px;
                    height: 250px;
                }
            </style>
            <form class="panel" method="POST" name="formulario" action="">


                <div> <?php echo $this->lang->line('label_escolha_form_pagamento'); ?></div>


                <div style="font-size: 23px;">

                    <div style="padding: 4px 0">
                        <strong> <?php echo $this->lang->line('label_voucher'); ?>: </strong>
                        <b style="color: #36F;"><?php echo $compra->co_id ?></b>
                    </div>

                    <strong> <?php echo $this->lang->line('label_valor'); ?>: </strong> <span
                        style="color: #090"><?php echo $this->lang->line('label_us$'); ?> <?php echo number_format($valorTotalCompra, 2, ',', '.') ?></span>

                </div>

                <div class="foma-pagamento span4">
                    <label class="radio">
                        <strong style="font-size: 18px;"><?php echo $this->lang->line('label_ativacao_imediata'); ?></strong><br>
                        <strong style="font-size: 18px;"><?php echo $this->lang->line('label_com_desconto_rapidez'); ?></strong><br>
                        <strong style="font-size: 15px; display: block;">( US$ = <?php echo $dolarPagamentoBonus->valor; ?> )</strong>
                        <strong><?php echo $this->lang->line('label_pagamento_voucher_upline_franqueado'); ?></strong>
                        <strong style="font-size: 15px; display: block;"><?php echo $this->lang->line('label_total_voucher'); ?></strong>
                    </label>
                    <input type="submit" value="<?php echo $this->lang->line('label_iniciar_pagamento'); ?>" />
                </div>

                <div class="foma-pagamento span4">
                    <strong
                        style="font-size: 20px; display: block; margin-left: -4px;"><?php echo $this->lang->line('label_boleto_bancario'); ?></strong>
                    <strong
                        style="font-size: 15px; display: block; margin-left: 22px;"> (US$
                        = R$ 2,55 ) </strong> <strong
                        style="font-size: 15px; display: block; margin-left: 37px;"><?php echo $this->lang->line('label_total_boleto'); ?></strong>
                    <input type="submit" style="margin: 5px 0 0 30px; display: none;"
                           value="<?php echo $this->lang->line('label_iniciar_pagamento'); ?>" /><br>
                    <strong style="display: block; margin-left: 38px; color: #06C;"><?php echo $this->lang->line('label_ativaocao_5_dias'); ?>.</strong>
                    <strong style="display: block; margin-left: 38px;"><?php echo $this->lang->line('label_manutencao'); ?>.</strong>
                </div>

                <?php
                if (1 == 2) {
                    $quantidadeDeFormaPagamento++;
                    ?>

                    <div class="foma-pagamento">
                        <div class="btn btn-info">
                            <label class="radio"> <input type="radio" selected="selected"
                                                         style="margin-top: 5px;" name="forma_pag" value="8" checked /> <i
                                                         class="icon-tasks icon-white"></i>
                                                         <?php echo $this->lang->line('label_deposito_empresarial_identificador'); ?>
                                <br />
                            </label>
                        </div>
                    </div>
                <?php } ?> 

                <?php
                if (1 == 2) {
                    $quantidadeDeFormaPagamento++;
                    ?>
                    <div class="foma-pagamento .col-xs-6 .col-md-4">
                        <div class="btn btn-info">
                            <label class="radio"> <input type="radio" style="margin-top: 5px;"
                                                         name="forma_pag" value="9" checked /> <i
                                                         class="icon-tasks icon-white"></i>
                                <?php echo $this->lang->line('label_transferencia_doc'); ?> <br />
                            </label>
                        </div>
                    </div> 
                <?php } ?>


                <?php
                if ($bonus->saldo >= $valor_compra && in_array('bonus', $formas_pagamento)) {
                    $quantidadeDeFormaPagamento++;
                    ?>

                    <div class="foma-pagamento">
                        <div class="btn btn-info">
                            <label class="radio"> <input type="radio" style="margin-top: 5px;"
                                                         name="forma_pag" value="3" checked /> <i
                                                         class="icon-retweet icon-white"></i><?php echo $this->lang->line('label_bonus'); ?> <?php echo number_format($bonus->saldo, 2, ',', '.') ?>)<br />
                            </label>
                        </div>
                    </div>

                <?php } ?>



                <?php
                $boletoConfig = $this->db
                                ->where('bo_situacao', 1)
                                ->where('bo_id', 1)
                                ->get('boleto_config')->row();

                if (count($boletoConfig) > 0 && $boletoConfig->bo_situacao == 1) {
                    $quantidadeDeFormaPagamento++;
                    ?>

                    <div class="foma-pagamento">
                        <div class="btn btn-info">
                            <label class="radio"> <input type="radio" style="margin-top: 5px;"
                                                         name="forma_pag" value="1" checked /> <i
                                                         class="icon-tasks icon-white"></i>
                                <?php echo $this->lang->line('label_boleto_bancario'); ?> <br />
                            </label>
                        </div>
                    </div>     
                <?php } ?>     


                <?php
                if (in_array('cielo', $formas_pagamento)) {
                    $quantidadeDeFormaPagamento++;
                    ?>    

                    <div class="cielo">

                        <table width="500px" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <p>
                                        <strong><?php echo $this->lang->line('label_escolha_bandeira'); ?>:</strong>
                                    </p> <input type="radio" checked="checked" name="bandeira"
                                                value="visa" /> <img
                                                src="<?php echo base_url() ?>public/imagem/cielo/visa.jpg" /><br />
                                    <input type="radio" name="bandeira" value="mastercard" /> <img
                                        src="<?php echo base_url() ?>public/imagem/cielo/master-card.jpg" /><br />
                                    <input type="radio" name="bandeira" value="elo" /> <img
                                        src="<?php echo base_url() ?>public/imagem/cielo/elo.jpg" />

                                </td>
                                <td>
                                    <p>
                                        <strong><?php echo $this->lang->line('label_parcelas'); ?>:</strong>
                                    </p> <select name="parcelas">
                                        <option value="1"><?php echo $this->lang->line('label_1_x'); ?> <?php echo number_format($valor_compra, 2, ',', '.') ?></option>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>
                <?php } ?>

                <?php if ($quantidadeDeFormaPagamento > 0) { ?>  
                    <br />
                    <p>
                        <input type="submit" class="btn btn-success btn-large"
                               value="INICIAR PAGAMENTO" />
                    </p>
                <?php } else { ?>
                    <p style="display: none;"><?php echo $this->lang->line('label_momento'); ?>.</p>
                <?php } ?>
            </form>
            <br>

            <!-- metodo de pagamento atma -->
            <div class='row'>
                <div class='span3 ' style="margin: 0 0 0 118px;text-align: center;">
                    <strong>Inicia pagamento com ATM</strong>
                    <p><?php echo $this->lang->line('label_1_x'); ?> <?php echo number_format($valor_compra, 2, ',', '.') ?></p>
                </div>
            </div>
            <div class="row">
                <div class="span" style="margin: 0 0 0 104px;" >
                    <?php
                    $atm = new atm($compra);
                    echo $atm->criarBotao_pagamento_atm();
                    ?>
                </div>
            </div>

        </div>
    </div>

<?php } else { ?>
    <h3 class="painel">
        A compra nº <?php echo $compra->co_id ?> já foi paga. Obrigado!
    </h3>
<?php } ?>


<style>
    .foma-pagamento {
        padding: 3px 0;
    }
</style>

<script type="text/javascript">
    $(function() {
        $("input[name='forma_pag']").click(function() {
            if ($(this).val() == 4) {
                $(".cielo").slideDown(1000);
            } else {
                $(".cielo").hide();
            }
        });
    });
</script>