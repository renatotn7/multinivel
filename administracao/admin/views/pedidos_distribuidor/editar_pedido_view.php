<div class="box-content min-height">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/pedidos_distribuidor/')?>">Cadastros Pendentes</a> &raquo; Editar
 </div>
 <div class="box-content-body">
 


<?php 
$c = $this->db
->join('distribuidores','di_id=co_id_distribuidor')
->join('cidades','ci_id=di_cidade')
->where('co_id',$this->uri->segment(3))->get('compras')->row();
?>
<div class="well">
  <table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
     <strong><?php echo $c->di_nome?> (<?php echo $c->di_usuario?>)</strong><br />
     <?php echo $c->di_endereco?><br />
     <?php echo $c->di_bairro?>, CEP: <?php echo $c->di_cep?><br />
     <?php echo $c->ci_nome?>-<?php echo $c->ci_uf?><br /> 
    </td>
    <td width="30%">
     <b>Data:</b> <?php echo date('d/m/Y H:i:s',strtotime($c->co_data_compra))?><br />
     <b>Nº Pedido:</b> <?php echo $c->co_id;?><br />
     <b>Valor:</b> R$ <?php echo number_format($c->co_total_valor,2,',','.');?><br />
    </td>
  </tr>
</table>
</div>
<form name="formulario" method="post" action="">
   
<!--   <label>Situação do Pedido:</label>
    <select name="situacao">
     <?php 
	  $compra_situacao = $this->db
                                  ->where_in('st_id',array(12,9,10,11,8,12,13,14,5))
                                  ->group_by('st_descricao')
                                  ->get('compra_situacao')
                                  ->result();
          
	  foreach($compra_situacao as $cs){
	 ?>
      <option <?php echo $c->co_situacao==$cs->st_id?"selected":""?> value="<?php echo $cs->st_id?>"><?php echo $cs->st_descricao?></option>
      <?php }?>
    </select>-->
  
   <?php if($c->co_pago==0){?>    
      <label>Situação de Pagamento:</label>
      <select name="pago">
      <option <?php echo $c->co_pago==0?"selected":""?> value="0">Não paga</option>
      <option <?php echo $c->co_pago==1?"selected":""?> value="1">Compra paga</option>
      </select>
    <?php }?>

  <label>Forma de pagamento:</label>
  <select <?php echo $c->co_pago==1?'disabled':'';?>  name="forma_pagamento">
     <?php 
	  $formas_pagamentos = $this->db
                    ->where('fp_id > 11')
                   ->get('formas_pagamento')->result();
	  foreach($formas_pagamentos as $formas_pagamento){
	 ?>
      <option <?php echo $c->co_forma_pgt==$formas_pagamento->fp_id?"selected":""?> value="<?php echo $formas_pagamento->fp_id?>"><?php echo $formas_pagamento->fp_descricao?></option>
      <?php }?>
    </select>
      
<!--    <p>
    <label>Código de Rastreio:</label>
	<input name="co_frete_codigo" maxlength="100" value="<?php echo $c->co_frete_codigo; ?>" >
	</p>-->
  <p>
<input type="submit" onclick="return confirm('Deseja salvar alterações?')" class="btn btn-primary" value="Salvar dados">
<a class="btn" href="<?php echo base_url('index.php/pedidos_distribuidor')?>">Cancelar</a>
</p>
</form>

</div>
</div>