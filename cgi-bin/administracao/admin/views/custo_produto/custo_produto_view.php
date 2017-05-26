<div class="box-content min-height">
 <div class="box-content-header">&raquo; Custo Produto</div>
 <div class="box-content-body">

<form name="formulario" method="post" action="<?php echo base_url('index.php/custo_produto/salvar');?>">

<table width="100%" border="0" cellspacing="0" cellpadding="7">
 <tr>
  <td>
  <label>Digite o percentual de custo para produtos Ex:(+10 aumentar , -10 diminuir)</label>
  <input type="text" name="percentual_custo_produto" value="<?php if(isset($percentual->valor)){echo $percentual->valor;}?>" >
  <label>Data de vigência dos produtos</label>
  <input type="text" name="data_vigencia" value="" class="mdata" >
  <input type="submit" style="margin-top:-10px;" value="Atualizar" class="btn btn-primary"> 
  </td>
 </tr> 
 <tr>
 <td></td>
 </tr>
         
</table>

</form>

</div>
</div>
