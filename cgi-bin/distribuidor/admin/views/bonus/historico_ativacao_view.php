<?php $this->lang->load('distribuidor/bonus/historico_ativacao_view'); ?>
<div class="alert"><?php echo $this->lang->line('label_notificacao'); ?></div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th><?php echo $this->lang->line('label_numero'); ?></th>
            <th><?php echo $this->lang->line('label_data_ativacao'); ?></th>
            <th></th>
        </tr>
    </thead>
    <?php
    $historicos = $this->db->where('at_distribuidor', get_user()->di_id)
            ->get('registro_ativacao')
            ->result();
    if (count($historicos) > 0) {
        foreach ($historicos as $key => $historico_value) {
            ?>
            <tr>
                <td><?php echo $key + 1; ?> </td>
                <td><i class="icon-time"></i> <?php echo date('d/m/Y H:i:s', strtotime($historico_value->at_data)); ?> </td>
                <td></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="3" style="text-align: center"><strong><?php echo $this->lang->line('label_data_ativacao'); ?></strong></td>
        </tr>
    <?php } ?>
</table>
