<?php $this->lang->load('distribuidor/bonus/credito_view'); ?>
<?php
$saldo = $this->db->query("
SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
WHERE cb_distribuidor = " . get_user()->di_id . "
")->result();

$de = data_to_usa((get_parameter('de') ? get_parameter('de') : date('01/m/Y')));
$ate = data_to_usa((get_parameter('ate') ? get_parameter('ate') : date('d/m/Y')));
?>

<style>
    .table td{
        padding:2px !important;
    }
    .table strong{
        font-size:12px;
    }	
</style>



<form id="form1" name="form1" method="get" action="">
    <?php echo $this->lang->line('label_de'); ?> : <input type="text" style="width:90px; margin:0;" class="mdata" value="<?php echo date('d/m/Y', strtotime($de)) ?>" name="de" />
    <?php echo $this->lang->line('label_ate'); ?> : <input type="text" style="width:90px; margin:0;" class="mdata" value="<?php echo date('d/m/Y', strtotime($ate)) ?>" name="ate" />
    <?php echo $this->lang->line('label_tipo'); ?> : 
    <select name="tipo" style="margin:0; width:150px;">
        <option value="">--Todos--</option>

        <?php
        $tiposBonus = $this->db->get('bonus_tipo')->result();
        foreach ($tiposBonus as $tp) {
            ?>     
            <option <?php echo get_parameter('tipo') == $tp->tb_id ? 'selected' : '' ?> value="<?php echo $tp->tb_id ?>"><?php echo $tp->tb_descricao ?></option>
        <?php } ?>

    </select>

    <input type="submit" class="btn" value="<?php echo $this->lang->line('label_filtrar'); ?>" />
</form>

<h2 class="label label-info"><?php echo $this->lang->line('label_saldo_saque'); ?> <?php echo number_format($saldo[0]->saldo, 2, ',', '.') ?></h2>
<table width="100%" class="table table-bordered table-hover" style="background:#FFF" border="0" cellspacing="0" cellpadding="2">
    <?php
    $total_bonus = 0;
    foreach ($mov as $m) {
        $total_bonus += $m->cb_credito;
        ?>
        <tr class="row-row">
            <td width="6%"><?php echo $m->cb_id ?></td>
            <td width="77%">
                <span style="font-size:11px; display:block;"><?php echo date('d/m/Y H:i:s', strtotime($m->cb_data_hora)) ?></span>
                <?php echo str_ireplace('Nº ' . $m->cb_compra, "<a target='_blank' href='" . base_url("index.php/pedidos/pedido_imprimir/" . $m->cb_compra) . "'>Nº {$m->cb_compra}</a>", $m->cb_descricao) ?></td>
            <td width="17%"><?php echo $m->cb_debito != 0 ? "<span class='label label-important'>-" . $m->cb_debito . "</span>" : "<span class='label label-success'>+" . $m->cb_credito . "</span>"; ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="2" align="right" style="text-align:right">Total: </td>
        <td width="17%"><strong>US$ <?php echo number_format($total_bonus, 2, ',', '.') ?></strong></td>
    </tr> 
</table>

<?php echo $links ?>

