<div class="box-content min-height">
 <div class="box-content-header">Pagamento com boleto bancário</div>
 <div class="box-content-body">

<?php 
$compra = $this->db->select(array('co_hash_boleto','co_id'))
->where('co_id_distribuidor',get_user()->di_id)
->where('co_id',$_GET['c'])->get('compras')->row();?>

<div class="panel">
 
 <div><strong>Pagamento do pedidos Nº <?php echo $compra->co_id?></strong></div>
 <div>Você escolheu a pagar o pedido com boleto bancário, para gerar o boleto <a target="_blank" href="<?php echo base_url('index.php/boleto/gerar_boleto?c='.$compra->co_id.'&seg='.$compra->co_hash_boleto)?>">clique aqui</a></div>
 
  <div>Ou você pode  <a href="<?php echo base_url('index.php/pedidos')?>">vizualizar seus pedidos</a></div>
  
</div>

</div>
</div>