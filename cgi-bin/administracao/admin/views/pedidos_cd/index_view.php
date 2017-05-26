<div class="box-content min-height">
 <div class="box-content-header">Vendas</div>
 <div class="box-content-body">

<form name="formulario" method="get" action="">
Situação:
  
  <select style="margin:0;" name="situacao">
  <option value="">--Indiferente--</option>
      <?php 
	  $situacao_rs = $this->db->get('compra_situacao')->result();
	  foreach($situacao_rs as $s){
	  ?>
      <option <?php echo $situacao===$s->st_id?"selected":""?> value="<?php echo $s->st_id?>"><?php echo $s->st_descricao?></option>
      <?php }?>
 </select>

<input type="submit" class="btn btn-primary" value="Filtrar" />
</form>
<table class="table table-hover table-bordered" width="100%" border="0" cellspacing="0" cellpadding="5">
  <thead>
  <tr>
    <td width="5%" bgcolor="#f7f7f7"><strong>Nº</strong></td>
    <td width="26%" bgcolor="#f7f7f7"><strong>CD</strong></td>
    <td width="11%" bgcolor="#f7f7f7"><strong>Cidade-UF</strong></td>
    <td width="13%" bgcolor="#f7f7f7"><strong>Data</strong></td>
    <td width="12%" bgcolor="#f7f7f7"><strong>Valor</strong></td>
    <td width="11%" bgcolor="#f7f7f7"><strong>Situação</strong></td>
    <td width="22%" bgcolor="#f7f7f7"></td>
  </tr>
  </thead>
  <tbody>
  <?php 
   if($situacao!==FALSE){$this->db->where('cr_situacao', $situacao);}
   
   
   $inicio = $this->uri->segment(3)?$this->uri->segment(3):0;
  
  $com = $this->db
       ->where('cr_situacao <>',-1)
	   ->order_by('cr_pago','ASC')
	   ->order_by('cr_id','DESC')
	   ->join('compra_situacao','cr_situacao=st_id')
	   ->join('cidades','ci_id=cr_entrega_cidade')
	   ->join('cd','cd_id=cr_id_cd')
       ->get('compras_fabrica',$per_page,$inicio)->result();
   foreach($com as $c){ 
   $hoje = date('Y-m-d',strtotime($c->cr_data_compra))==date('Y-m-d');  	   
  ?>
  <tr <?php echo $c->cr_pago==1?"class='pedido-pago'":"class='pedido-nao-pago'" ?>>
    <td width="5%"><?php echo $c->cr_id?></td>
    <td width="26%"><?php echo $c->cd_nome?></td>
    <td width="11%"><?php echo $c->ci_nome."-".$c->ci_uf?></td>
    <td width="13%"><?php echo $hoje?"Hoje":date('d/m/Y',strtotime($c->cr_data_compra));?></td>
    <td width="12%">US$ <?php echo number_format($c->cr_frete_valor+$c->cr_total_valor,2,',','.')?></td>
    <td width="11%">
      <?php echo $c->st_descricao?>
      
    </td>
    <td>
    <?php if(permissao('vendas','editar',get_user())){?>
    <a class="btn" href="<?php echo base_url("index.php/pedidos_cd/editar_pedido/$c->cr_id")?>">Alterar</a>
    <?php }?>
    <a class="btn" target="_blank" href="<?php echo base_url("index.php/pedidos_cd/pedido_imprimir/$c->cr_id")?>">Ver</a>
    <?php if($c->cr_pago==0){?>
    <?php if(permissao('vendas','excluir',get_user())){?>
     <a class="btn btn-danger" onclick="return confirm('Deseja cancelar esse pedido?')" href="<?php echo base_url("index.php/pedidos_cd/cancelar_pedido/$c->cr_id")?>">Cancelar</a>
    <?php }?>
	<?php }?>
    </td>
  </tr>
  <?php }?>
  </tbody>
 </table>
 
 <?php echo $links?>
 
 </div>
 </div>