<div class="box-content min-height">
 <div class="box-content-header">
 <a href="<?php echo base_url('index.php/produtos/categorias')?>">Categorias de produtos</a>
 &raquo;
 Editar categoria</div>
 <div class="box-content-body">


<?php $cat = $this->db->where('ca_id',$this->uri->segment(3))->get('categorias_produtos')->row();?>


<script type="text/javascript">
 $(function(){
	  $("#sel_pai").val(''+<?php echo $cat->ca_pai?>);
	 });
</script>

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

<form name="formulario" method="post" action="<?php echo base_url('index.php/produtos/salvarCategoria/'. $cat->ca_id);?>">
 <p><label>Categoria pai:</label>
 <select style="width:300px" id="sel_pai" name="ca_pai">
 <option value="0">Nenhuma</option>
<?php mostrar_categoria($this->db,0,0) ?>
 </select>
 </p>
 <p>
  <label>Descrição:</label>
  <input type="text" size="50" name="ca_descricao" value="<?php echo $cat->ca_descricao?>" />
 </p>
 <p>
<input type="submit" class="btn btn-primary" value="Salvar">
</p>
</form>

</div>
</div>
