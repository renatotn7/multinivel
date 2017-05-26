
<?php 
$nots = get_notificacao();

foreach($nots as $k=> $n){
if($n['tipo']==1){
?>
<div id="noti_<?php echo $k?>" class="notificacao true"><?php echo $n['mensagem']?></div>
<script type="text/javascript">
jQuery(function(){setTimeout('hide_notificacao("#noti_<?php echo $k?>")',6000)});
</script>
<?php }else if($n['tipo']==2){?>
<div id="noti_<?php echo $k?>" class="notificacao false"><?php echo $n['mensagem']?></div>
<script type="text/javascript">
jQuery(function(){setTimeout('hide_notificacao("#noti_<?php echo $k?>")',6000)});
</script>
<?php }}?>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
    

<table width="100%" id="table-listagem" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr>
    <td>Codigo</td>
    <td>Produto</td>
    <td>Quantidade</td>
    <td>Desconto</td>
    <td>Valor unitário</td>
    <td>Total</td>
  </tr>
</thead>
<tbody>


<?php 
$compra = $this->db->where('co_id',get_compra()->co_id)->get('compras')->result();
$comp = $this->db
->join('produtos','pr_id = pm_id_produto')
->where('pm_id_compra',get_compra()->co_id)->get('produtos_comprados')->result();

$valor_total = $pontos_total = 0;
$kit = 0;
$peso_total = 0;
$produto_ativacao =0;

foreach($comp as $c){
 if($c->pr_kit==1){$kit++;}	
 $peso_total += $c->pr_peso*$c->pm_quantidade;
 $pontos_total += $c->pm_pontos*$c->pm_quantidade;
 $valor_total += $c->pm_valor*$c->pm_quantidade;
 if($c->pm_tipo==1){
	 $produto_ativacao += $c->pm_quantidade;
	 }
?>

  <tr>
    <td><?php echo $c->pr_codigo?></td>
    <td><?php echo $c->pr_nome?> 
    &nbsp;&nbsp;&nbsp;
    <a title="Remover produto do pedido" onClick="remover_do_carrinho(<?php echo $c->pm_id?>)" class="remover-icon" href="javascript:void(0)">&nbsp;</a></td>
    <td><?php echo $c->pm_quantidade?></td>
    <td><?php echo number_format($c->pr_valor - $c->pm_valor,2,',','.')?></td>
    <td>R$ <?php echo number_format($c->pm_valor,2,',','.')?></td>
    <td>R$ <?php echo number_format(($c->pm_valor*$c->pm_quantidade),2,',','.')?></td>
  </tr>
<?php }?>


</tbody>
</table>
    
<!--- FIM PRODUTOS --> 
    </td>
    <td valign="top" style="border-left:15px solid #f7f7f7; width:300px;">

<table width="100%" id="table-listagem" border="0" cellspacing="0" cellpadding="0">
<thead>
 <tr><td colspan="2">Dados da compra</td></tr>
</thead>
<tbody>
<?php if($pontos_total>0){?>
 <tr>
    <td align="right"><strong>Pontos:</strong></td>
    <td style="color:#F90 !important;font-size:18px;"><?php echo $pontos_total?></strong></td>
  </tr>
<?php }?>  
 <tr>
    <td align="right"><strong>Total Produtos:</strong></td>
    <td style="color:#F90 !important;font-size:18px;">R$ <?php echo number_format(($valor_total),2,',','.')?></td>
  </tr>  
 <?php if($compra[0]->co_frete_valor>0){?> 
 <tr>
    <td align="right"><strong>Frete <?php echo $compra[0]->co_frete_tipo?>:</strong></td>
    <td style="color:#F90 !important;font-size:18px;">R$ <?php echo number_format($compra[0]->co_frete_valor,2,',','.')?></strong></td>
  </tr>  
 <?php }?>
 
 <tr>
    <td align="right"><strong>Total compra:</strong></td>
    <td style="color:#F90 !important;font-size:18px;"><strong>R$ <?php echo number_format(($valor_total+$compra[0]->co_frete_valor),2,',','.')?></strong></td>
  </tr>
 
  <?php if(count($comp)){?>
   
  
   <tr><td colspan="2">
   <?php 
   $show_botao = true;
   ?>
   
   <?php
    if($show_botao){
	$action_button = $kit>0?'finalizar_compra':'finalizar_compra';   
	?>
    <a class="btn btn-success" href="<?php echo base_url("index.php/loja/{$action_button}/")?>">Confirmar compra</a>
   <?php }?>
   </td></tr>
   <?php }?>
 </tbody>
 </table> 
   
    
    </td>
  </tr>
</table>

<?php 

$this->db->where('co_id',get_compra()->co_id)->update('compras',array(
'co_total_valor'=>$valor_total,
'co_total_pontos'=>$pontos_total,
'co_peso_total'=>$peso_total
));
?>

<?php set_notificacao(array());?>

