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

$saldo = $this->db->query("
        SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
        WHERE cb_distribuidor = " . get_user()->di_id . "
    ")->row();

$recebido = $this->db->query("
        SELECT SUM(cb_credito) AS saldo FROM conta_bonus
        WHERE cb_distribuidor = " . get_user()->di_id . "
        AND cb_tipo IN( SELECT tb_id FROM bonus_tipo )
    ")->row();

$distribuidor = $this->db->where('di_id', get_user()->di_id)->get('distribuidores')->row();
?>
<form class="form-inline" id="form1" name="form1" method="post" action="javascript:void(0)">
    <?php if (!verificar_permissao_acesso(false)) { ?>
    <div class="alert alert-info">
        <h5>
            <strong><?php echo $this->lang->line('label_observacoes'); ?></strong>
        </h5>
        <span><?php echo $this->lang->line('label_pedidos_de_saques_dia_ate_15'); ?></span>
    </div>
    <?php } ?>

    <table class="table table-hover">
        <tr>
            <td colspan="3">
                <strong><?php echo $this->lang->line('label_saldo_saque'); ?>:</strong>
            </td>
            <td align="right">
                <strong><?php echo $this->lang->line('label_us$'); ?> <?php echo number_format($saldo->saldo, 2, ',', '.') ?></strong>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <?php echo $this->lang->line('label_total_bonus_recebido'); ?>:
            </td>
            <td align="right">
                <?php echo $this->lang->line('label_us$'); ?> <?php echo number_format($recebido->saldo, 2, ',', '.') ?>
            </td>
        </tr>
        <?php
        if (!verificar_permissao_acesso(false)) {
            // bloquear aqui para baixo caso o distribuidor não esteja ativo o binário
            // $binario = new Binario(get_user());
            // if (get_user()->di_data_cad > '2014-03-31 23:59:59' && $binario->e_binario() != false ) {
            // if (!in_array(DistribuidorDAO::getPlano(get_user()->di_id)->pa_id,array(99,100))) {
            if (date("d") == 10) {
                ?>
                <tr>
                    <td class="row" colspan="100%">
                        <div class="col-lg-3 col-md-2 col-sm-2 col-xs-12 ">
                            <h4><strong><?php echo $this->lang->line('label_requisitar_deposito'); ?></strong></h4>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 text-right">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('label_us$'); ?></label>
                                <input type="number" min="<?php echo conf()->valor_minimo_saque ?>" value="" class="validate[required] form-control" name="valor" required />
                            </div>
                            <div class="form-group">
                                <label><?php echo $this->lang->line('label_senha_seguranca'); ?></label>
                                <input class="validate[required] form-control" type="password" name="senha" required />
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 text-right pull-right">
                            <button class="btn btn-primary" data-toggle="confirmation"
                                    data-popout="true" data-singleton="true"
                                    data-btn-ok-label="<?php echo $this->lang->line('label_requisitar_saque'); ?>!"
                                    data-btn-ok-icon="fa fa-dollar" data-btn-ok-class="btn-success"
                                    data-btn-cancel-label="Cancel"
                                    data-btn-cancel-icon="fa fa-ban" data-btn-cancel-class="btn-danger"
                                    data-title="<?php echo $this->lang->line('label_requisitar_deposito'); ?>?"
                                    data-content="<?php echo $this->lang->line('label_taxa_saque'); ?>"
                            >
                              <?php echo $this->lang->line('label_requisitar_saque'); ?>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</form>
<script>
    $(document).ready(function() {

        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function () {
                if(document.form1.checkValidity()){
                    document.form1.action = "<?php echo base_url('index.php/bonus/requisitar_saque') ?>";
                    document.form1.submit();
                    return true;
                } else {
                    document.form1.action = "javascript:void(0)";
                    return false;
                }
            },
            onCancel: function () {
                document.form1.reset();
            }
        });

    });
</script>