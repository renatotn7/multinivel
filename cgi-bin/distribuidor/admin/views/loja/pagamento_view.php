<?php $this->lang->load('distribuidor/loja/loja_view'); ?>
<style>
    .forma-pagamento{
		  margin-top: 25px;
		padding: 10px;
		min-height: 303px;
		background: #f0f0f0;
		text-align: center;
		position: relative;
		border: 2px solid #DFDFDF;
    }

    .forma-pagamento input[type="submit"]{
        position: absolute;
        bottom: 20px;
        left: 10px;
    }
</style>
<div class="box-content min-height">
    <div class="box-content-header">Formas de pagamento</div>
    <div class="box-content-body">

        <?php
        if (count($compra) == 0) {
            ?>
            <h3 class="painel">
                Compra invalida, favor verifique o código do pedido
            </h3>
            <?php
        } elseif ($compra->co_total_valor < 1) {
            ?>
            <h3 class="painel">
                Compra invalida, favor verifique o código do pedido
            </h3>
            <?php
        } else {
            if ($compra->co_pago == 0) {

                $bonus = $this->db->query(" SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
											WHERE cb_distribuidor = " . get_user()->di_id)->row();

                $valor_compra = $valorTotalCompra;

                $formas_pagamento = array('bonus');
                $quantidadeDeFormaPagamento = 0;

                $dolarPagamentoBonus = $this->db->where('field', 'cotacao_dolar')->get('config')->row();
                ?>

                <div> <?php echo $this->lang->line('label_escolha_form_pagamento'); ?></div>


                <div style="font-size:23px;">

                    <div style="padding:4px 0"><strong> <?php echo $this->lang->line('label_voucher'); ?>: </strong> <b style="color:#36F;"><?php echo $compra->co_id ?></b></div>

                    <strong> <?php echo $this->lang->line('label_valor'); ?>: </strong> <span style="color:#090"><?php echo $this->lang->line('label_us$'); ?> <?php echo number_format($valorTotalCompra, 2, ',', '.') ?></span>

                </div>
                <div class="row">
                <?php 
                 $config=explode(',',conf()->forma_pagamentos);
                 
                if(in_array('upline', $config)){?>
                    <div class="forma-pagamento span3">
                        <h4><?php echo $this->lang->line('label_upline_franqueado'); ?></h4>
                        <i><?php echo $this->lang->line('label_desc_upline_franqueado'); ?></i>
                        <form method="post" action="<?php echo base_url('index.php/loja/pagamento?c=' . $compra->co_id); ?>">
                            <input type="hidden" value="11" name="forma_pag" />
                            <input type="submit" style="margin:0 0 0 30px;" class="btn" value="<?php echo $this->lang->line('label_iniciar_pagamento'); ?>" />
                        </form>
                       
                    </div>
                    <?php }?>
                   <?php if(in_array('empresa', $config)){?>
                    <div class="forma-pagamento span3">
                        <h4><?php echo $this->lang->line('label_e_wallet'); ?></h4>
                        <i style="font-size:15px;">
                            <?php echo $this->lang->line('label_nota');?>
                        </i>
                       
                        <?php
                        $atm = new atm($compra);
                        echo($atm->criarBotao_pagamento_atm());
                        ?>
                    </div>
                   <?php }?>
                    <?php 
                    if ($bonus->saldo >= $valor_compra && in_array('bonus', $formas_pagamento)) {
                        $quantidadeDeFormaPagamento++;
                        ?>
                       <?php if(in_array('bonus', $config)){?>
                        <div class="forma-pagamento span3"> 
                            <h4><?php echo $this->lang->line('label_bonus'); ?></h4>
                            <i>
                                <?php echo $this->lang->line('label_desc_bonus'); ?>
                                <br>
                                <?php echo $this->lang->line('label_desc_bonus_saldo'); ?>
                                <br>
                                (<?php echo number_format($bonus->saldo, 2, ',', '.') ?>)
                            </i>
                            <form method="post" action="<?php echo base_url('index.php/loja/pagamento?c=' . $compra->co_id); ?>">
                                <input type="hidden" value="3" name="forma_pag" />
                                <input type="submit" style="margin:0 0 0 30px;" class="btn" value="<?php echo $this->lang->line('label_iniciar_pagamento'); ?>" />
                            </form>
                        </div>
     					<?php }?>
                    <?php } ?>
                </div>
           
                <br>

            </div>
        </div>
    <?php } else { ?>
        <h3 class="painel">
            A compra nº <?php echo $compra->co_id ?> já foi paga. Obrigado!
        </h3>
        <?php
    }
}
?>

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