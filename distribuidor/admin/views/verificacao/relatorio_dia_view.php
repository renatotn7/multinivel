<div class="box-content min-height"  style="background:#FFF">
 <div class="box-content-header"></div>
 <div class="box-content-body">
 
 
 
 
 
 <table width="100%" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Pontos Gerados</td>
    <td>Bônus Pagos</td>
  </tr>
  <tr>
    <td>
     <?php 
	 $date_anterior = strtotime($_GET['date']);
	 $date_anterior = mktime(0,0,0,date('m',$date_anterior),date('d',$date_anterior)-1,date('Y'));
	 $date_anterior = date('Y-m-d',$date_anterior);
	 
	 $p_gerado = $this->db->where('pd_data',$_GET['date'])->get('pontos_distribuidor')->result();
	?>
    
        <strong><?php echo $date_anterior?></strong>
	<?php
	$total_anterior = 0;
     $p_gerado = $this->db->where('pd_data',$_GET['date'])->get('pontos_distribuidor')->result();
	 foreach($p_gerado as $pg){
	 $total_anterior += $pg->pd_pontos;	 
	 ?>
      <p><?php echo $pg->pd_pontos?></p>
     <?php }?>
     <strong class="label">Total: <?php echo number_format($total_anterior,0,',','.')?></strong>
     <br>
     
     <br>
    <strong><?php echo $_GET['date']?></strong>
     <?php
	 foreach($p_gerado as $pg){
	 ?>
      <p><?php echo $pg->pd_pontos?></p>
     <?php }?>
    
    </td>
    <td>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <?php 
	 $b_pagos = $this->db
	 ->where('cb_distribuidor',$this->uri->segment(3))
	 ->like('cb_data_hora',$_GET['date'])
	 ->where('cb_tipo',1)->get('conta_bonus')
	 ->result();
	 
	 foreach($b_pagos as $d){
	?>
      <tr>
        <td><?php echo $d->cb_descricao?></td>
        <td><?php echo $d->cb_credito?></td>
      </tr>
    <?php }?>
    
    </table>
    
    </td>
  </tr>  
  
</table>

 
 </div>
 </div>