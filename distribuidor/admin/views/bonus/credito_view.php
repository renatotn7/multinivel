<?php $this->lang->load('distribuidor/bonus/transacoes_view');?>
<?php $this->lang->load('distribuidor/bonus/credito_view'); ?>
<?php
$saldo = $this->db->query("
        SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
        WHERE cb_distribuidor = " . get_user()->di_id . "
    ")->result();
$de = data_to_usa((get_parameter('de') ? get_parameter('de') : date('01/m/Y')));
$ate = data_to_usa((get_parameter('ate') ? get_parameter('ate') : date('d/m/Y')));
?>
<form id="form1" name="form1" method="get" action="" class="form-inline">
    <div class="form-group">
        <label for="de"><?php echo $this->lang->line('label_de'); ?>:</label>
        <input type="text" class="mdata form-control" value="<?php echo date('d/m/Y', strtotime($de)); ?>" name="de" id="de" />
    </div>
    <div class="form-group">
        <label for="ate"><?php echo $this->lang->line('label_ate'); ?>:</label>
        <input type="text" class="mdata form-control" value="<?php echo date('d/m/Y', strtotime($ate)); ?>" name="ate" id="ate" />
    </div>
    <div class="form-group">
        <label for="tipo"><?php echo $this->lang->line('label_tipo'); ?>:</label>
        <select name="tipo" id="tipo" class="form-control">
            <option value="">--Todos--</option>
            <?php
            $tiposBonus = $this->db->get('bonus_tipo')->result();
            foreach ($tiposBonus as $tp) {
            ?>
            <option <?php echo get_parameter('tipo') == $tp->tb_id ? 'selected' : ''; ?> value="<?php echo $tp->tb_id; ?>"><?php echo $tp->tb_descricao; ?></option>
            <?php } ?>
        </select>
    </div>
    <input type="submit" class="btn btn-default" value="<?php echo $this->lang->line('label_filtrar'); ?>" />
</form>

<h2>
    <div class="label label-info">
        <?php echo $this->lang->line('label_saldo_saque'); ?> <?php echo number_format($saldo[0]->saldo, 2, ',', '.'); ?>
    </div>
</h2>

<table class="table table-bordered table-hover">
    <thead>
        <tr bgcolor="#f7f7f7">
            <!-- <th width="10%"><?php echo $this->lang->line('label_numero'); ?></th> -->
            <th width="20%"><?php echo $this->lang->line('label_data'); ?></th>
            <th width="50%"><?php echo $this->lang->line('label_descricao'); ?></th>
            <th width="20%"><?php echo $this->lang->line('label_valor'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $total_bonus = 0;
    foreach ($mov as $m) {
        $total_bonus += $m->cb_credito;
        ?>
        <tr>
            <!-- <td><?php echo $m->cb_id ?></td> -->
            <td><?php echo date('d/m/Y H:i:s', strtotime($m->cb_data_hora)) ?></td>
            <td>
                <?php echo str_ireplace('Nº ' . $m->cb_compra, "<a target='_blank' href='" . base_url("index.php/pedidos/pedido_imprimir/" . $m->cb_compra) . "'>Nº {$m->cb_compra}</a>", $m->cb_descricao) ?>
            </td>
            <td>
                <?php echo $m->cb_debito != 0 ? "<span class='label label-danger'>-" . $m->cb_debito . "</span>" : "<span class='label label-success'>+" . $m->cb_credito . "</span>"; ?>
            </td>
        </tr>
    <?php } ?>
        <tr>
            <td colspan="2" align="right">
                <h4><strong>Total</strong></h4>
            </td>
            <td>
                <h4><strong>US$ <?php echo number_format($total_bonus, 2, ',', '.') ?></strong></h4>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="100%" align="center" class="text-center"><?php echo $links; ?></td>
        </tr>
    </tfoot>
</table>
