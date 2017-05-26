<?php $this->lang->load('distribuidor/bonus/codigo_promocional_view'); ?>
<table class="table table-bordered table-hover">
    <thead>
        <tr bgcolor="#f7f7f7">
            <th><?php echo $this->lang->line('label_codigo');?></th>
            <th><?php echo $this->lang->line('label_codigo');?></th>
            <th><?php echo $this->lang->line('label_lado_rede');?></th>
            <th><?php echo $this->lang->line('label_situacao');?></th>
            <th><?php echo $this->lang->line('label_login_ativacao');?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach (ComprasModel::getTokenAtivacao(get_user()->di_id) as $tokenAtivacao){?>
        <tr>
            <td><?php echo $tokenAtivacao->token->prk_token;?></td>
            <td><?php echo $tokenAtivacao->plano->pa_descricao;?></td>
            <td><?php echo ($tokenAtivacao->token->prk_perna_derramamento==1?$this->lang->line('label_derramamento'):$this->lang->line('label_livre_escolha'));?></td>
            <td><?php echo ($tokenAtivacao->token->prk_situacao==0?$this->lang->line('label_aguardando_ativacao'):$this->lang->line('label_codigo_ativacao_rede'));?></td>
            <td><?php echo ($tokenAtivacao->usuarioativado !=false?$tokenAtivacao->usuarioativado->di_usuario:'-');?></td>
        </tr>
    <?php }?>
    </tbody>
</table>



