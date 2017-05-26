<div class="box-content min-height" style="background:#FFF">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/produtos/')?>">Produtos</a> &raquo;
 Produtos Padrão do Combo
 
 </div>
 <div class="box-content-body">
  <?php if(count($produtosSelecionado) < $plano->pa_combo){?>
  
   <form name="form1" method="post" action="<?php echo base_url('index.php/produtos/salvar_combo_padrao')?>">
     
     <input type="hidden" name="idplano" value="<?php echo $this->uri->segment(3)?>" />
     
     <select name="produto" style="margin:0;">
      <option value="">--Selecionar--</option>
      <?php 
	   foreach($produtosCombo as $produtos){
	   ?>
		<option value="<?php echo $produtos->pr_id?>"><?php echo $produtos->pr_nome?></option>
	   <?php }?>
     </select>
     <input type="submit" class="btn btn-primary" value="Adicionar">
   </form>
   
   <?php }?>
   <hr />
   
<table width="100%" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#f3f3f3">Cod</td>
    <td bgcolor="#f3f3f3">Produto</td>
    <td bgcolor="#f3f3f3">Valor</td>
    <td bgcolor="#f3f3f3">Ação</td>
  </tr>
<?php foreach($produtosSelecionado as $produto){?>
  <tr>
    <td><?php echo $produto->pr_codigo?></td>
    <td><?php echo $produto->pr_nome?></td>
    <td>R$ <?php echo number_format($produto->pr_valor,2,',','.')?></td>
    <td><a href="<?php echo base_url('index.php/produtos/remover_produto_padrao/'.$produto->pn_id.'/'.$plano->pa_id)?>">Remover</a></td>
  </tr> 
 <?php }?> 
  
</table>

   
   
 </div>
 </div>