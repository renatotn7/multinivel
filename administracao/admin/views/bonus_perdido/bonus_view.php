<?php $this->lang->load('distribuidor/bonus_perdido/bonus_perdido_view'); ?>
<div class="box-content min-height border-radios">
    <div class="box-content-header">
        <a href="<?php echo base_url() ?>"><?php echo $this->lang->line('label_titulo'); ?></a>

    </div>
    <div class="box-content-body">
        <table  class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Descrição</th>
                    <th>Pontos</th>
                    <th>Tipo</th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach ($bonus_perdidos as $bonus_perdido) { 
                $pontos =($bonus_perdido->cb_credito/DistribuidorDAO::getPlano($bonus_perdido->cb_distribuidor)->pa_binario)*100;
                ?>
            <tr>
                <td><?php echo $bonus_perdido->di_usuario;?></td>
                <td><?php echo date('d/m/Y', strtotime($bonus_perdido->cb_data_hora));?></td>
                <td><?php echo $bonus_perdido->cb_credito;?></td>
                <td><?php echo $bonus_perdido->cb_descricao;?></td>
                <td><?php echo $bonus_perdido->cb_tipo==2?$pontos:'';?></td>
                <td><?php echo $bonus_perdido->cbt_descricao;?></td>
                <td> <a href="<?php echo base_url('/index.php/bonus_perdido/liberar_bonus?cb_id='.$bonus_perdido->cb_id);?>"  class="btn btn-warning" type="button">Liberar Pagamento</a></td>
            </tr>
            <?php }?>
        </table>
    </div>
</div>
