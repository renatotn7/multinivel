<div class="box-content">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/pedidos_cd')?>">Pedidos</a> &raquo; Editar pedido
 </div>
 <div class="box-content-body">



<?php $c = $this->db->where('cr_id',$this->uri->segment(3))->get('compras_fabrica')->result(); 
?>

<form name="formulario" method="post" action="">

  <table width="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="300px"><label>Situação:</label>
      <span>Gerencie o status da compra, serve para você saber em que etapa está o pedido</span>
      </td>
      <td>
      <select name="situacao">
      <?php 
	  $situacao = $this->db->get('compra_situacao')->result();
	  foreach($situacao as $s){
	  ?>
      <option <?php echo $s->st_id==$c[0]->cr_situacao?"selected":""?> value="<?php echo $s->st_id?>"><?php echo $s->st_descricao?></option>
      <?php }?>
      </select>
      </td>
    </tr>
    <?php if($c[0]->cr_pago==0){?>
   <tr>
      <td><label>Pagamento:</label>
      <span>Essa opção serve para informar se a compra foi paga 
      ou está pendente. Se marcar como paga o Distribuidor 
      tem o direito de receber os pontos e os bonús refente ao pedido.</span>
      </td>
      <td>
      <select name="pago">
      <option <?php echo $c[0]->cr_pago==1?"selected":""?> value="1">Compra paga</option>
      <option <?php echo $c[0]->cr_pago==0?"selected":""?> value="0">Não paga</option>
      </select>
      </td>
    </tr>    
    <?php }?>
    
  </table>
  <p>
<input type="submit" onclick="return confirm('Essa operação pode colocar a compra como paga\n\nVocê tem certeza disso?')" class="btn btn-success" value="Salvar dados" />


</p>
</form>

</div>
</div>