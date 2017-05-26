<?php 
	$produtosValidos = $this->db
		->where('pr_kit',1)
		->where('pr_estoque >',1)
		->get('produtos')->result();
	
	$UltimosProdutosEscolhidos = $this->db
		->where('id_auto_atv', $distribuidor_auto->auto_atv_id)
		->order_by('produto_auto_data', 'desc')
		->limit(get_user()->distribuidor->getPlano()->getQuantidadeComboMensal())
		->get('produtos_auto_ativacao')->result();
?>

<div style="font-size:22px; color:#069; font-weight:bold; margin-top:20px; margin-bottom:20px;">Seu plano atual: <?php echo(get_user()->distribuidor->getPlano()->getDescricao()); ?></div>
<div class="alert alert-info"> Este plano exige que você selecione no minimo <?php echo(get_user()->distribuidor->getPlano()->getQuantidadeComboMensal()); ?> combo(s) para sua ativação!!! </div>
Confira abaixo os produtos atualmente escolhidos em sua auto-ativação
<div> <strong style="color:#666;">Produtos Selecionados:</strong><br>
  <?php 
	for($combo = 0; $combo < get_user()->distribuidor->getPlano()->getQuantidadeComboMensal(); $combo++){
?>

	<select style="margin-top:10px;" class="validate[required] produtos" name="combos[]" disabled="disabled">
    <option value="">Selecione</option>
    <?php 
        foreach($produtosValidos as $pr_val){
    ?>
    <option <?php
    			if($pr_val->pr_id == $UltimosProdutosEscolhidos[$combo]->id_produto){
					echo('selected="selected"');
				}
			?> value="<?php echo $pr_val->pr_id?>"><?php echo $pr_val->pr_nome?></option>
    <?php
        }
    ?>
  </select>
  <br />
  <?php 
	}
?>
</div>
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