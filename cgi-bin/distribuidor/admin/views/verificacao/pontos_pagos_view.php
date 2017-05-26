<div class="box-content min-height"  style="background:#FFF">
 <div class="box-content-header"></div>
 <div class="box-content-body">
 
 <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">



 <?php 
 $pp = $this->db->where('pg_distribuidor',$this->uri->segment(3))->get('pontos_pagos')->result();
 foreach($pp as $p){
 ?>
   <tr>
    <td>
	<a target="_blank" href="<?php echo base_url('index.php/verificacao/relatorio_dia/'.$this->uri->segment(3).'/?date='.$p->pg_data)?>">
	<?php echo date('d/m/Y',strtotime($p->pg_data))?>
    </a>
    </td>
    <td><?php echo $p->pg_pontos?></td>
  </tr>
 <?php }?>
 </table>
 </div>
 </div>