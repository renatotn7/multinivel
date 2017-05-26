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
?>



<?php
$saldo = $this->db->query("
SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
WHERE cb_distribuidor = " . get_user()->di_id . "
")->row();

$recebido = $this->db->query("
SELECT SUM(cb_credito) AS saldo FROM conta_bonus
WHERE cb_distribuidor = " . get_user()->di_id . "
AND cb_tipo IN(
 SELECT tb_id FROM bonus_tipo
)
")->row();

$distribuidor = $this->db->where('di_id', get_user()->di_id)->get('distribuidores')->row();
?>
<form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/bonus/requisitar_saque') ?>">
    <?php if (!verificar_permissao_acesso(false)) { ?>
        <div class="alert alert-info" style="margin:0 auto;">
            <ul>
                <b><?php echo $this->lang->line('label_observacoes'); ?></b>
                <li><?php echo $this->lang->line('label_pedidos_de_saques_dia_ate_15'); ?></li>
            </ul>
        </div>
    <?php } ?>

    <table style="font-size:17px;" width="100%" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td width="76%"><strong><?php echo $this->lang->line('label_saldo_saque'); ?>:</strong></td>
            <td width="24%" align="right" style="font-weight:bold;"><?php echo $this->lang->line('label_us$'); ?> <?php echo number_format($saldo->saldo, 2, ',', '.') ?></td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('label_total_bonus_recebido'); ?>:</td>
            <td align="right"><?php echo $this->lang->line('label_us$'); ?> <?php echo number_format($recebido->saldo, 2, ',', '.') ?></td>
        </tr>

        <tr>
            <td colspan="2"><hr /></td>
        </tr>
        <?php if (!verificar_permissao_acesso(false)) { ?>
            <!--bloquear aqui para baixo caso o distribuidor não esteja ativo o binário--> 
            <?php
            $binario = new Binario(get_user());
            if (get_user()->di_data_cad > '2014-03-31 23:59:59') {
//            if ($binario->e_binario() != false ) {
                ?>

                <tr>
                    <td align="right">
                        <?php echo $this->lang->line('label_requisitar_deposito'); ?>:
                        <div style="color:#FF1717;font-size:12px;"><?php echo $this->lang->line('label_limit_minino_saque'); ?> <?php echo conf()->valor_minimo_saque ?></div>
                    </td>
                    <td align="right" height="28px">
                        <?php echo $this->lang->line('label_us$'); ?> <input style="width:100px; margin:0; text-align:right; font-size:18px;" type="text" value="0,00" class="moeda" name="valor" />
                    </td>
                </tr>  

                <tr>
                    <td align="right"><?php echo $this->lang->line('label_senha_seguranca'); ?>:</td>
                    <td align="right"><input style="width:150px;" type="password" name="senha" /></td>
                </tr>   
                <?php  if (!in_array(DistribuidorDAO::getPlano(get_user()->di_id)->pa_id,array(99,100))) { ?>
                    <tr>
                        <td align="right"></td>
                        <td align="right"><button class="btn btn-primary" type="submit"><?php echo $this->lang->line('label_requisitar_saque'); ?></button></td>
                    </tr>  
                    <?php
                }
            }
//            }
        }
        ?>

    </table>

</form>




