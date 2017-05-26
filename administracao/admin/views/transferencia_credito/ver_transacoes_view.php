<div style="padding: 10px;"> 
    <a class="btn btn-success" href="<?php echo base_url('index.php/transferencia_credito/ver_compras_pagas')?>">Ver Compras Pagas</a>
<hr>
<b>Transferências relizadas por distribuidores da rede <?php echo $de->di_usuario?> para rede de <?php echo $para->di_usuario?></b>
<table class="table table-bordered">
    
    <tr>
        <th>Usuário Debito</th>
        <th>Usuário Crédito</th>
        <th>Data</th>
        <th>Valor</th>
    </tr>
    <?php foreach ($transacoes as $transacao){?>
     <tr>
        <td><?php echo $transacao->di_usuario?></td>
        <td><?php echo $transacao->usuario_receptor?></td>
        <td><?php echo date('d/m/Y H:i:s',  strtotime($transacao->cb_data_hora))?></td>
        <td><?php echo number_format($transacao->cb_debito,2,',','.')?></td>
    </tr>
    <?php }?>
</table>
</div>