<script src="http://clickkin.com.br/public/util/editor/editor.js"></script>

<script type="text/javascript">

  bkLib.onDomLoaded(function() {
        new nicEditor({iconsPath : '<?php echo base_url()?>public/editor/nicEditorIcons.gif'}).panelInstance('id-editor');
  });
  
  </script>

<div class="box-content min-height">
 <div class="box-content-header">Newsletter</div>
 <div class="box-content-body">

<style>
p{
margin:1px;
padding:1px;
}
</style>
<form id="form-r" name="formulario" method="post" action="<?php echo base_url('index.php/newsletter/enviar')?>">

<p>
Usuário:<br />
 <input type="text" name="usuario" />
</p>

<p>
Estado:<br />
 <select name="estado" class="ajax-uf">
 <option value="">--Todos---</option>
  <?php 
  $es = $this->db->get('estados')->result();
  foreach($es as $e){
  ?>
   <option value="<?php echo $e->es_id?>"><?php echo $e->es_nome?></option>
  <?php }?>
 </select>
</p>


<p>
Cidade:<br />
 <select name="cidade" class="recebe-cidade">
  <option value="">--Todas--</option>
 </select>
</p>


<p>
Graduação:<br />
 <select name="graduacao">
 <option value="">--Todas--</option>
  <?php 
  $gra = $this->db->get('distribuidor_qualificacao')->result();
  foreach($gra as $e){
  ?>
  <option value="<?php echo $e->dq_id?>"><?php echo $e->dq_descricao?></option>
  <?php }?>
 </select>
</p>

<p>Assunto:<br>
<input name="assunto" size="70" style="width:500px;" type="text">
</p>

<p>Mensagem:<br>
  <textarea name="msg" id="id-editor" style="width:1000px; height:200px;"></textarea>
</p>

<p>
  <input type="submit" class="btn btn-primary" value="Enviar E-mail">
</p>

</form>

</div>
</div>