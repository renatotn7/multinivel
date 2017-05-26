
<div class="box-content" style="margin:1%; width:98%;">
 <div class="box-content-header">Configurar combo</div>
  <div class="box-content-body">

<form name="formulario" action="" method="post" >

<table width="100%" id="table-listagem" border="0" cellspacing="0" cellpadding="0">
<?php 
foreach($kits as $num_combo => $k){
?>
  <tr>
    <td width="73%">
     <?php echo $num_combo+1?>º) Escolha os produtos do Combo:<br />
    
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    
  


     <?php 
	  $op = $this->db
	  ->join('categorias_produtos','pc_categoria = ca_id')
	  ->where('pc_id_kit',$k->pr_id)->get('produtos_kit_categoria')->result();
	  
	  foreach($op as $o){ 
	  
	  for($co=0; $co < $o->pc_quantidade; $co++){		  
	 ?>
     <td>
      <strong><?php echo $o->ca_descricao?></strong><br>
      <select style="width:200px;" name="kit[<?php echo $k->pm_id?>][]" class="validate[required]">
       <option value="">--Selecione--</option>
       <?php 
	   if($compra->co_id_cd!=0){
		   $prod =  $prod = $this->db->query("SELECT * FROM produtos_do_cd
			 JOIN produtos ON pr_id = pc_id_produto
			 WHERE pc_id_cd = ".$compra->co_id_cd."
			 AND pr_categoria = {$o->pc_categoria} OR pr_categoria IN(SELECT ca_id FROM categorias_produtos WHERE ca_pai = {$o->pc_categoria})
			 GROUP BY pr_id
			 HAVING SUM(pc_entrada) - SUM(pc_saida) > 0
		     ")
	        ->result();
		   }else{
	        $prod = $this->db->query("SELECT * FROM produtos WHERE pr_categoria = {$o->pc_categoria} OR pr_categoria IN(SELECT ca_id FROM categorias_produtos WHERE ca_pai = {$o->pc_categoria})")
	        ->result();
		   }
	   foreach($prod as $p){
	   ?>
       <option value="<?php echo $p->pr_id?>"><?php echo $p->pr_nome?></option>
       <?php }?>
      </select>
      </td>
     <?php }}?>
     </tr>
     </table>
    </td>
  </tr>
<?php }?>
</tbody>
</table>
<input type="submit" class="btn btn-success" value="CONFIRMAR"  />
</form>

</div>
</div>