<h1>
<a href="<?php echo base_url('index.php/relatorios')?>">Relatórios</a> &rsaquo;
Crédito de ativação vendidos</h1>

<form id="form1" name="form1" method="get" action="">

<p>
  Data:
</p>
<p>
de:  
<input type="text" class="mdata" size="10" value="<?php echo get_parameter('de')?>" name="de" />
até: <input type="text" class="mdata" size="10" value="<?php echo get_parameter('ate')?>" name="ate" />
<input type="submit" value="ir" />
</p>
</form>
<table border="0" width="100%" class="relatorios" cellpadding="0" cellspacing="0">
 <thead>
  <tr>
   <td>Nº Compra</td>
   <td>CD</td>
   <td>Data</td>
   <td>Valor</td>
  </tr>
 </thead>
 <tbody>
 <?php 
 if(get_parameter('de')){
 $this->db->where('cr_data_compra >=',data_usa(get_parameter('de'))." 00:00:00");
 }
 
 if(get_parameter('ate')){
 $this->db->where('cr_data_compra <=',data_usa(get_parameter('ate'))." 23:59:59");
 }
 
 $compras = $this->db
 ->where('cr_credito_repasse',1)
 ->join('cd','cd_id=cr_id_cd')
 ->where('cr_pago',1)
 ->get('compras_fabrica')->result();
 $total = 0;
 foreach( $compras as $c){
  $total += $c->cr_total_valor;
 ?>
 
   <tr>
   <td><?php echo $c->cr_id?></td>
   <td><?php echo $c->cd_nome?></td>
   <td><?php echo date('d/m/Y',strtotime($c->cr_data_compra))?></td>
   <td><?php echo number_format($c->cr_total_valor,2,',','.')?></td>
  </tr>
  <?php }?>
     <tr>
   <td colspan="3" align="right">Total</td>
   <td><?php echo number_format($total,2,',','.')?></td>
  </tr>
 </tbody>
</table>

<a class="botao" href="<?php echo base_url('index.php/relatorios')?>">Voltar</a>
<a href="javascript:window.print()">Imprimir</a>