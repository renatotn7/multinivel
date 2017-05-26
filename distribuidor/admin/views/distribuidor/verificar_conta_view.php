<?php $this->lang->load('distribuidor/distribuidor/verificar_conta_view');?>

<div class="alert alert-warning">
<?php echo $this->lang->line('texto_notificacao')?>
</div>

<div class="well" style="padding:10px;">
    
<strong><?php echo $this->lang->line('label_adicionar_documento')?>:</strong>




<form method="post" enctype="multipart/form-data" action="<?php echo base_url('index.php/distribuidor/enviar_documento')?>">
<div>
<?php echo $this->lang->line('label_tipo_documento')?>:<br>
<select name="do_categoria" class="validate[required]" style="width:400px;">
 <option value=""><?php echo $this->lang->line('label_selecione')?></option>
 <option value="Pessoais"><?php echo $this->lang->line('label_documento_foto')?></option>
 <option value="Endereço"><?php echo $this->lang->line('label_documento_comprovante_endereco')?></option>
<!-- <option value="Pessoais"><?php echo $this->lang->line('label_pessoa_rg_cpf')?></option>-->
<!-- <option value="Endereço"><?php echo $this->lang->line('label_endereco')?></option>
 <option value="Bancários"><?php echo $this->lang->line('label_bancarios1')?></option>
  <option value="Bancários"><?php echo $this->lang->line('label_bancarios2')?></option>-->
</select>
</div>
<div>
<?php echo $this->lang->line('label_selecione_arquivo')?>:<br>
<input type="file" name="file" class="validate[required]">
</div>
<input class="btn btn-success" type="submit" value="<?php echo $this->lang->line('label_enviar')?>">
</form>
</div>

<table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="21%"><?php echo $this->lang->line('label_data')?></th>
    <th width="27%"><?php echo $this->lang->line('label_status')?></th>
    <th width="27%"><?php echo $this->lang->line('label_documento')?></th>
    <th width="25%"><?php echo $this->lang->line('label_arquivo')?></th>
  </tr>
<?php 
$docs = $this->db->where('do_distribuidor',get_user()->di_id)->get('documentos')->result();
foreach($docs as $d){
?>
  <tr>
    <td><?php echo date('d/m/Y | H:i',strtotime($d->do_data))?></td>
    <td><?php echo status_doc($d->do_status,$this)?></td>
    <td><?php echo $d->do_categoria?></td>
    <td><a  href="javascript:void();" onclick="modal('<?php echo $d->do_id;?>');"><?php echo $this->lang->line('label_ver_arquivo')?></a></td>
  </tr>
 <?php }?> 
</table>

<div id="myModal" class="modal hide fade">
<form action="<?php echo base_url('index.php/distribuidor/ver_doc/');?>" method="post" name="form-modal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Confirma Senha</h3>
  </div>
  <div class="modal-body">
     <div class="alert" style="margin: 0;">
    <p>
      Para visualizar esse documento é necessário informar a senha de segurança.
     <input type="password" name="senha" id="senha" class="span2 validate[required]" placeholder="Senha" value="">
     <input type="hidden" name="di_doc" id="di_doc" value="">
       </p>
     </div>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" onclick="closeModal();">Cancela</a>
    <button class="btn btn-primary" type="submit">Confirmar</button>
</div>
</form>
</div>
<?php 

	function status_doc($s,$lang){
		switch($s){
			case 0:echo "<span class='label label-important'>".$lang->lang->line('label_reprovado')."</span>";break;
			case 1:echo "<span class='label label-warning'>".$lang->lang->line('label_em_analise')."</span>";break;
			case 2:echo "<span class='label label-success'>".$lang->lang->line('label_aprovado')."</span>";break;
		}
	}
?>
<script type="text/javascript">

 function modal(id){
	 $('#myModal').modal('show');
	 $('#di_doc').val(id);
 }
 function closeModal()
 {
	 $('#myModal').modal('hide');
	 $('#di_doc').val('');
	 $('#senha').val('');
 }

</script>
