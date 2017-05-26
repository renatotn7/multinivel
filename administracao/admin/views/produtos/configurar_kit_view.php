
<div class="box-content min-height">
 <div class="box-content-header">
 <a href="<?php echo base_url()?>">Principal</a>
<a href="<?php echo base_url('index.php/produtos/')?>">&raquo; Produtos</a> &raquo; Configurar Kit
 </div>
 <div class="box-content-body">



<form name="formulario" action="" method="post">
 Escolha o departamento: 
  <select name="pc_categoria" class="validate[required]">
    <option value="">--Selecione--</option>
     <?php $dp = $this->db->get('categorias_produtos')->result();
	 foreach($dp as $d){
	 ?>
     <option value="<?php echo $d->ca_id?>"><?php echo $d->ca_descricao?></option>
     <?php }?>
    </select> 
    pode selecionar até: <input type="text" class="validate[required]" size="2" value="1" name="pc_quantidade" /> poduto(s)
    
    <input type="submit" class="botao" style="cursor:pointer" value="Adicionar">
</form>


 <table id="table-listagem" width="100%" border="0" cellspacing="0" cellpadding="5">
 <thead>
  <tr>
    <td width="21%" bgcolor="#f7f7f7"><strong>Categoria</strong></td>
    <td width="6%" bgcolor="#f7f7f7"><strong>Quantidade</strong></td>
    <td width="15%" bgcolor="#f7f7f7"></td>
  </tr>
  </thead>
  <tbody>

<?php 
 $cats = $this->db->where('pc_id_kit',$this->uri->segment(3))
 ->join('categorias_produtos','ca_id = pc_categoria')
 ->get('produtos_kit_categoria')->result();
 foreach($cats as $c){
?>
  
  <tr>
    <td width="21%"><?php echo $c->ca_descricao?></td>
    <td width="6%"><?php echo $c->pc_quantidade?></td>
    <td width="15%"><a class="remover-icon" href="<?php echo current_url()."?ex=$c->pc_id"?>">.</a></td>
  </tr> 
<?php }?> 

<?php if(count($cats)==0){?> 
 <tr>
    <td width="21%" colspan="3">Nenhuma categoria adicionada</td>
  </tr> 
<?php }?> 
  </tbody> 
</table>

</div>
</div>