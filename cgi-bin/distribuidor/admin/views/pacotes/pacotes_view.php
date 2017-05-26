<?php $this->lang->load('distribuidor/pacotes/pacotes_view');?>
<div class="box-content min-height">
 <div class="box-content-header">
  <a href="<?php echo base_url()?>"><?php echo $this->lang->line('title_page1'); ?></a> &raquo;
  <?php echo $this->lang->line('title_page2'); ?> &raquo; <?php echo $this->lang->line('title_page3'); ?>
 </div>
 <div class="box-content-body">

  
  <?php 
   $pacotes = $this->db
   ->join('planos','pa_id=co_id_plano')
   ->where('co_id_distribuidor',get_user()->di_id)
   ->order_by('co_data_compra','DESC')
   ->get('compras')->result();
  ?>
  
  <table width="100%" class="table table-hover table-bordered" style="background:#FFF" border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th width="6%"><?php echo $this->lang->line('title_col_numero'); ?></th>
      <th width="40%"><?php echo $this->lang->line('title_col_contas'); ?></th>
      <th width="29%"><?php echo $this->lang->line('title_col_situacao'); ?></th>
      <th width="29%"><?php echo $this->lang->line('title_col_data'); ?></th>
      <th width="25%"><?php echo $this->lang->line('title_col_acao'); ?></th>
    </tr>
  </thead> 
  <?php foreach($pacotes as $p){?> 
    <tr>
      <td>
	  <span class="label <?php echo $p->co_pago==1?'label-success':'label-important'?>">
	  <?php echo $p->co_id?>
      </span>
      </td>
      <td>
	  <span class="label <?php echo $p->co_pago==1?'label-success':'label-important'?>">
	  <?php echo $p->pa_descricao?>
      </span>
      </td>
      <td><span class="label <?php echo $p->co_pago==1?'label-success':'label-important'?>"><?php echo $p->co_pago==1?'ATIVO':'INATIVO'?></span></td>
      <td><span class="label <?php echo $p->co_pago==1?'label-success':'label-important'?>"><?php echo date('d/m/Y H:i:s',strtotime($p->co_data_compra)) ?></span></td>
     
      <td>
         
        <?php if($p->co_pago==0){?>
        <a class="btn btn-info" href="<?php echo base_url('index.php/loja/pagamento?c='.$p->co_id)?>"><?php echo $this->lang->line('label_btn_pagar'); ?></a>
        <?php }?>
      </td>
    </tr> 
    <?php }?>
  </table>
 </div>
 </div>