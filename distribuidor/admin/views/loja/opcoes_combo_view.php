<?php 
 $arrayPlanos = $planos = $this->db
      ->where('pa_id <',4)
	  ->join('produtos','pr_id=pa_kit','left')
	  ->get('planos')->result();
	  
  foreach($arrayPlanos as $p){	  
?>
<div class="combo combo-plano<?php echo $p->pa_id?>" >
 <div style="font-size:22px; color:#069; font-weight:bold;">Combos do Plano <?php echo $p->pa_descricao?></div>
 
 <?php 
  for($combo=1;$combo<=$p->pa_combo;$combo++){
 ?>
 <div>
  <strong style="color:#666">Selecione o <?php echo $combo?>º combo:</strong><br>
  <select class="validate[required]" name="combo[<?php echo $p->pa_id?>][<?php echo $combo?>]">
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

 
</div>
<?php }?>

   <br />
      
<style>
.combo{
	display:none;
	}
</style>

<script>
	$(function(){
		$(".combo-plano<?php echo isset($arrayPlanos[0]->pa_id)?$arrayPlanos[0]->pa_id:''?>").css('display','block');
	});
	 
	function show_escolher_combo($plano){
	  $(".combo").css('display','none');
	  $(".combo-plano"+$plano).css('display','block');
	 }
</script>