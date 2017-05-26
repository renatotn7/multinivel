<div class="box-content min-height">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/pedidos/')?>">Cadastros Pendentes</a> &raquo; Editar
 </div>
 <div class="box-content-body">
 


<?php 
$c = $this->db
->join('distribuidores','di_id=co_id_distribuidor')
->join('cidades','ci_id=di_cidade')
->where('co_id',$this->uri->segment(3))->get('compras')->row();
?>
<div class="alert alert-info">
<h4>ATENÇÃO</h4> Os pedidos que forem pagas com a opção abaixo disponível não geram pontos para o empreendedor.<br /> Gerando apenas as vitrines virtuais.
</div>
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

  <table width="100%" border="0" cellspacing="0" cellpadding="5">
  
   <?php if($c->co_pago==0){?> 
   <tr>
      <td width="9%"><label>Pagamento:</label></td>
      <td width="91%">
      
      <select name="pago">
      <option <?php echo $c->co_pago==1?"selected":""?> value="1">Compra paga</option>
      <option <?php echo $c->co_pago==0?"selected":""?> value="0">Não paga</option>
      </select>
      
     
      </td>
    </tr>    
    <?php }else{?>
     <input type="hidden" name="pago" value="1" />
      <?php }?>
    
  </table>
  <p>
<input type="submit" onclick="return confirm('Deseja salvar alterações?')" class="btn btn-primary" value="Salvar dados">
<a class="btn" href="<?php echo base_url('index.php/pedidos')?>">Cancelar</a>
</p>
</form>

</div>
</div>