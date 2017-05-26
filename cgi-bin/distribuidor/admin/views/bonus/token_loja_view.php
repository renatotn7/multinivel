<?php $this->lang->load('distribuidor/bonus/codigo_promocional_view'); ?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th><?php echo $this->lang->line('label_codigo');?></th>
            <th><?php echo $this->lang->line('label_codigo');?></th>
            <th>Data Compra</th>
            <th><?php echo $this->lang->line('label_situacao');?></th>
            <th>Data Revenda</th>
            <th>Usuario Comprador</th>
        </tr>
        <?php foreach (ComprasModel::getTokenCodigoPromocionais(get_user()->di_id) as $tokenAtivacao){?>
        <tr <?php echo ($tokenAtivacao->token->prk_situacao==1?'style="color: #CCC;"':'');?>>
            <td><?php echo $tokenAtivacao->token->prk_token;?></td>
            <td><?php echo isset($tokenAtivacao->plano->pa_descricao)?$tokenAtivacao->plano->pa_descricao:'';?></td>
            <td><?php echo date('d/m/Y',  strtotime($tokenAtivacao->token->prk_data_aquisicao));?></td>
            <td><?php echo ($tokenAtivacao->token->prk_situacao==0?$this->lang->line('label_aguardando_ativacao'):'Inativa');?></td>
            <td><?php echo !empty($tokenAtivacao->token->prk_data_revenda)?date('d/m/Y',  strtotime($tokenAtivacao->token->prk_data_revenda)):'';?></td>
            <td><?php echo ($tokenAtivacao->usuarioativado !=false?$tokenAtivacao->usuarioativado->di_usuario:'-');?></td>
        </tr>
        <?php }?>
    </thead>
</table>



