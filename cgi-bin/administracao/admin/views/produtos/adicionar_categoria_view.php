<div class="box-content min-height">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/produtos/categorias')?>">Categorias de produtos</a>
 &raquo;
 Cadastrar categoria</div>
 <div class="box-content-body">
 




<?php 
function mostrar_categoria($db,$pai,$traco=0){
	$cat = $db->where('ca_pai',$pai)->get('categorias_produtos')->result();
	if(count($cat)){
	 foreach($cat as $c){
		 echo "<option value='{$c->ca_id}'>".str_repeat('&nbsp;',$traco)." - {$c->ca_descricao}</option> ";
		 mostrar_categoria($db,$c->ca_id,$traco+1);
		 }
	}
	
	}
?>


     <form name="formulario" method="post" action="<?php echo base_url('index.php/produtos/salvarCategoria');?>">
 <p><label>Categoria pai:</label>
 <select name="ca_pai">
 <option value="0">Nenhuma</option>
 
 <?php mostrar_categoria($this->db,0,0) ?>
 
 </select>
 </p>
 <p>
  <label>Descrição:</label>
  <input type="text" size="50" name="ca_descricao" />
 </p>
 <p>
<input type="submit" class="btn btn-primary" value="Salvar">
</p>
</form>

</div>
</div>
