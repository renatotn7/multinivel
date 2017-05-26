
<div class="painel">
<h5 style="margin:4px">VEJA TODOS OS PEDIDOS PENDENTES DE SUA REDE.</h5>

<div style="height:300px">
<table id="table-listagem"  width="100%" border="0" cellspacing="0" cellpadding="5">
  <thead>
  <tr>
    <td width="2%" bgcolor="#f7f7f7"><strong>Nº</strong></td>
    <td width="27%" bgcolor="#f7f7f7"><strong>Distribuidor</strong></td>
    <td width="8%" bgcolor="#f7f7f7"><strong>Data</strong></td>
    <td width="11%" bgcolor="#f7f7f7"><strong>Entrega</strong></td>
    <td width="3%" bgcolor="#f7f7f7"><strong>Pt.</strong></td>
    <td width="7%" bgcolor="#f7f7f7"><strong>Valor</strong></td>
    </tr>
  </thead>
  <tbody>
  <?php 
   
   
  
  $com = $this->db
       ->query("SELECT * FROM (`compras`) JOIN `compra_situacao` ON `co_situacao`=`st_id` 
	   JOIN     `distribuidores` ON `di_id`=`co_id_distribuidor` 
	   WHERE `co_situacao` <> -1 
	   AND co_pago = 0
	   AND di_id IN(SELECT `di_id` FROM (`distribuidor_ligacao`) JOIN 
     `distribuidores` ON `di_id` = `li_id_distribuidor` WHERE `li_no` = '".get_user()->di_id."')
	   ORDER BY `co_pago` ASC, `co_id` DESC")->result();
	   
	   
   foreach($com as $c){   	   
  ?>
  <tr <?php echo $c->co_pago==1?"class='pedido-pago'":"class='pedido-nao-pago'" ?>>
    <td width="2%"><?php echo $c->co_id?></td>
    <td width="27%"><?php echo $c->di_nome?></td>
    <td width="8%"><?php echo date('d/m/Y',strtotime($c->co_data_compra))?></td>
    <td width="11%"><?php echo $c->co_entrega==1?"Receber em casa":"Retirar no CD"?></td>
    <td width="3%"><?php echo $c->co_total_pontos?></td>
    <td width="7%">US$ <?php echo number_format($c->co_total_valor+$c->co_frete_valor,2,',','.')?></td>
    </tr>
  <?php }?>
  <?php 
  if(count($com)==0){
  ?>
    <tr>
    <td colspan="6">Nenhuma pedido pendente</td>
    </tr>
  <?php }?>
  </tbody>
 </table>
 </div>
 </div>
