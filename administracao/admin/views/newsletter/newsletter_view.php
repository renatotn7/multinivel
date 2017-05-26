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
<form id="form-r" name="formulario" method="post" action="<?php echo base_url('index.php/newsletter/index?processar=1')?>">

<p>
    Lista de usuário <i>(Lista de usuário separada Por ;)</i>:<br />
    <textarea name="lista_usuario" style="margin: 0px 0px 10px; height: 61px; width: 1036px;"></textarea>
 </p>
<p>
Usuário:<br />
 <input type="text" name="usuario" />
</p>

<p>
<!--País:<br />
 <select name="estado" class="ajax-uf">
 <option value="">--Todos---</option>
  <?php 
  $es = $this->db->get('pais')->result();
  foreach($es as $e){
  ?>
   <option value="<?php echo $e->ps_id?>"><?php echo $e->ps_nome?></option>
  <?php }?>
 </select>
</p>-->

<p>
Situação do cadastro:<br />
 <select name="situacao_cadastro" >
  <option value="">--Todas--</option>
  <option value="pendente">Pendentes</option>
  <option value="ativos">Ativos</option>
  <option value="finaciado">Financiados</option>
 </select>
</p>
<p>
Tipo de agência:<br />
 <select name="planos" >
  <option value="">--Todas--</option>
  <?php 
  $planos = $this->db->where('pa_id !=104')->get('planos')->result();
  foreach($planos as $plano){
  ?>
   <option value="<?php echo $plano->pa_id?>"><?php echo $plano->pa_descricao?></option>
  <?php }?>
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
  <textarea name="msg" id="editor" style="width:1000px; height:200px;"></textarea>
</p>

<p>
  <input type="submit" class="btn btn-primary" value="Enviar E-mail">
</p>

</form>

</div>
</div>
 