<?php $this->lang->load('distribuidor/bonus/transacoes_view');?>

<?php

	
$saldo = $this->db->query("
SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
WHERE cb_distribuidor = ".get_user()->di_id."
")->result();
 $de = data_to_usa((get_parameter('de')?get_parameter('de'):date('01/m/Y')));
 $ate = data_to_usa((get_parameter('ate')?get_parameter('ate'):date('d/m/Y'))); 
 
?>



<form id="form1" name="form1" method="get" action="">
  <?php echo $this->lang->line('label_de');?>: <input type="text" style="width:90px; margin:0;" class="mdata" value="<?php echo date('d/m/Y',strtotime($de))?>" name="de" />
  <?php echo $this->lang->line('label_ate');?>: <input type="text" style="width:90px; margin:0;" class="mdata" value="<?php echo date('d/m/Y',strtotime($ate))?>" name="ate" />
  <input type="submit" class="btn" value="<?php echo $this->lang->line('label_filtrar');?>" />
</form>
<table width="100%" class="table table-bordered" style="background:#FFF" border="0" cellspacing="0" cellpadding="2">
 
  <tr class="row-row">
    <th width="3%"><?php echo $this->lang->line('label_numero');?></th>
    <th width="28%"><?php echo $this->lang->line('label_data');?></th>
    <th width="50%"><?php echo $this->lang->line('label_descricao');?></th>
    <th width="19%"><?php echo $this->lang->line('label_valor');?></th>
  </tr>

<?php
foreach($mov as $m){
?>
  <tr class="row-row">
    <td width="3%"><?php echo $m->cb_id?></td>
    <td width="28%"><?php echo date('d/m/Y H:i:s',strtotime($m->cb_data_hora))?></td>
    <td width="50%">
	<?php echo str_ireplace('Nº '.$m->cb_compra,"<a target='_blank' href='".base_url("index.php/pedidos/pedido_imprimir/".$m->cb_compra)."'>Nº {$m->cb_compra}</a>",$m->cb_descricao)?>
    </td>
    <td width="19%"><?php echo $m->cb_debito!=0?"<span class='label label-important'>- ".$m->cb_debito."</span>":"<span class='label label-success'>+ ".$m->cb_credito."</span>";?></td>
  </tr>
<?php }?>
 <tr>
    <td colspan="3" align="right" style="text-align:right"><?php echo $this->lang->line('label_saldo');?>: </td>
    <td width="50%" style="font-size:18px"><strong><?php echo $this->lang->line('label_us$');?> <?php echo number_format($saldo[0]->saldo,2,',','.')?></strong></td>
  </tr> 
</table>

<?php 
echo $links;

?>

