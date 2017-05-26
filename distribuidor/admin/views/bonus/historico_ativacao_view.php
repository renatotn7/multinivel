<?php $this->lang->load('distribuidor/bonus/historico_ativacao_view'); ?>
<div class="alert alert-warning">
    <?php echo $this->lang->line('label_notificacao'); ?>
</div>
<table class="table table-bordered table-hover">
    <thead>
        <tr bgcolor="#f7f7f7">
            <th><?php echo $this->lang->line('label_numero'); ?></th>
            <th><?php echo $this->lang->line('label_data_ativacao'); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    $historicos = $this->db->where('at_distribuidor', get_user()->di_id)
        ->get('registro_ativacao')
        ->result();
    if (count($historicos) > 0) {
        foreach ($historicos as $key => $historico_value) {
        ?>
        <tr>
            <td><?php echo $key + 1; ?> </td>
            <td>
                <i class="fa fa-fw fa-clock-o"></i> <?php echo date('d/m/Y H:i:s', strtotime($historico_value->at_data)); ?>
            </td>
        </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="100%" align="center">
                <strong><?php echo $this->lang->line('label_data_ativacao'); ?></strong>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
