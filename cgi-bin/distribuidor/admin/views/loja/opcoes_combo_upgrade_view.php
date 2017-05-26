<?php 
 $arrayPlanos = $planos = $this->db
      ->where('pa_id', $idPlano)
	  ->join('produtos','pr_id=pa_kit','left')
	  ->get('planos')->row();	  
?>
<?php 
  for($combo=1; $combo <= $planos->pa_combo; $combo++){
 ?>

<div> <strong style="color:#666">Selecione o <?php echo $combo?>º combo:</strong><br>
  <select class="validate[required]" name="combo[<?php echo $planos->pa_id?>][<?php echo $combo?>]">
    <option value="">Selecione</option>
    <?php 
   
   $arrayProdutos = $this->db
   ->where('pr_kit',1)
   ->where('pr_estoque >',1)
   ->get('produtos')->result();
   
   foreach($arrayProdutos as $produtos){
   ?>
    <option value="<?php echo $produtos->pr_id?>"><?php echo $produtos->pr_nome?></option>
    <?php }?>
  </select>
</div>
<?php }?>
