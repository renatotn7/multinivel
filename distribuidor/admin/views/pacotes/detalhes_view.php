<div class="box-content min-height">
 <div class="box-content-header">
 
 <a href="<?php echo base_url()?>">Principal</a> &raquo;
 <a href="<?php echo base_url('index.php/pacotes')?>">Meus Pacotes</a> &raquo;
 Detalhes
 </div>
 <div class="box-content-body">

  
  <?php 
   $pacotes = $this->db
   ->where('pp_distribuidor',get_user()->di_id)
   ->where('pp_compra',$this->uri->segment(3))
   ->get('parcelas_plano')->result();
  ?>
  
  <table width="100%" class="table table-hover table-bordered" style="background:#FFF" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th width="10%">Parcela</th>
      <th width="32%">Data</th>
      <th width="32%">Valor</th>
      <th width="26%">Status</th>
    </tr>
  </thead> 
  <?php foreach($pacotes as $p){?> 
    <tr>
      <td>
	  
	  <?php echo $p->pp_parcela?>
    
      </td>
      <td><?php echo date('d/m/Y',strtotime($p->pp_data))?></td>
      <td><?php echo number_format($p->pp_valor,2,',','.')?></td>
      <td>
      <?php if( $p->pp_status==1){?>
      <span class="label label-success">
       Pago em <?php echo date('d/m/Y',strtotime($p->pp_data_pagamento))?>
      </span>
      <?php }?>
      </td>
    </tr> 
    <?php }?>
  </table>
 </div>
 </div>