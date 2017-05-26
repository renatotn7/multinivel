<?php
	$produtosEstoque = $this->db->where('pr_kit', 1)->where('pr_estoque >', 1)->get('produtos')->result();
	$centrosDistribuicao = $this->db->join('cidades', 'ci_id = cd_cidade')->get('cd')->result();
	
	if (!empty($registroAtivacao)) {
	    $produtosSelecionados = $this->db->where('id_auto_atv', $registroAtivacao->auto_atv_id)->order_by('produto_auto_data', 'desc')->limit(get_user()->distribuidor->getPlano()->getQuantidadeComboMensal())->get('produtos_auto_ativacao')->result();
	}
	?>

<div style="font-size:22px; color:#069; font-weight:bold; margin-top:20px; margin-bottom:20px;">Seu plano atual:
  <?php
	echo (get_user()->distribuidor->getPlano()->getDescricao());
	?>
</div>
<div class="alert alert-info"> Este plano exige que você selecione no minimo
  <?php
	echo (get_user()->distribuidor->getPlano()->getQuantidadeComboMensal());
	?>
  combo(s) para sua ativação!!! </div>
Confira abaixo os produtos atualmente escolhidos em sua auto-ativação
<div>
<strong style="color:#666;">Produtos Selecionados:</strong><br>
<form method="post" action="<?php
		echo base_url('index.php/ativacao/ativar_auto_ativacao');
		?>">
  <input id="registroAtivacao" name="registroAtivacao" type="hidden" value="<?php
			if (!empty($registroAtivacao)) {
			    echo ($registroAtivacao->auto_atv_id);
			}
			?>"/>
  <?php
			for ($combo = 0; $combo < get_user()->distribuidor->getPlano()->getQuantidadeComboMensal(); $combo++) {
			?>
  <select name="combo[]" class="validate[required]" style="margin-top:10px;">
    <option value="">Selecione</option>
    <?php
				foreach ($produtosEstoque as $produto) {
				    $idSelecionado = isset($produtosSelecionados[$combo]) ? $produtosSelecionados[$combo]->id_produto : '';
				?>
    <option <?php
				echo ($idSelecionado == $produto->pr_id) ? 'selected' : '';
				?> value="<?php
				echo $produto->pr_id;
				?>">
    <?php
				echo $produto->pr_nome;
				?>
    </option>
    <?php
				}
				?>
  </select>
  <br />
  <?php
    }
	?>
    
  <div id="">

        <?php if(count($registroAtivacao) == 0){?>
        
        <input type="radio" checked="checked" onclick="showComboCd(this.value)" name="co_tipo" value="1" /> Indústria
        <input type="radio" name="co_tipo" onclick="showComboCd(this.value)" value="2" /> Centro de Distribuição(CD)
        
        <?php }else{?>
            <input type="radio"
		<?php echo $registroAtivacao->auto_atv_co_tipo==1?'checked':''?> onclick="showComboCd(this.value)" name="co_tipo" value="1" /> Indústria
        <input <?php echo $registroAtivacao->auto_atv_co_tipo==2?'checked':''?> onclick="showComboCd(this.value)" type="radio" name="co_tipo" value="2" /> Centro de Distribuição(CD)
        
        <?php }?>
        
        <br />
        <select id="co_id_cd" name="co_id_cd"  class="validate[required]"
        <?php 
			if (!empty($registroAtivacao)) {
				if($registroAtivacao->auto_atv_co_tipo == 2){
					echo('style="display:;block;"');
				}else{
					echo('style="display:none;"');
				}
			}else{
				echo('style="display:none;"');
				}
		?>>
            <option value="">Selecione</option>
                <?php
				foreach ($centrosDistribuicao as $cd){
				?>
			<option value="<?php echo $cd->cd_id; ?>"
            <?php 
            	if (!empty($registroAtivacao)) {
					if($registroAtivacao->auto_atv_cd_id == $cd->cd_id){
						echo(' selected="selected"');
					}
				}
			?>>
			<?php
					echo $cd->ci_nome.' - '.$cd->cd_endereco;
			?>
			</option>
			<?php
				}
				?>
          </select>
          
      <br />    
  </div>
  <button type="submit" id="btn_salvar" class="btn btn-success">Salvar</button>
  <button type="button" id="btn_cancelar" class="btn">Cancelar</button>
</form>
<style>
	.combo{
	display:none;
	}
</style>
<script>
	
	function showComboCd($valor){
			if($valor == 1){
				$('#co_id_cd').fadeOut(200);	
			}
			if($valor == 2){
				$('#co_id_cd').fadeIn(200);	
			}
		}
	
	$('#btn_cancelar').click(
		function(){
			$('#recebe_load').fadeOut(200);
			$('#ativar_auto').fadeIn(200);
		}	
	);
	$('#ativar_auto').click(
		function(){
			$('#ativar_auto').fadeOut(200);
			$('#recebe_load').fadeIn(200);
		}
	);
</script>