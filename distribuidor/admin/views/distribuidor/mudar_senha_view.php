<?php $this->lang->load ( 'distribuidor/distribuidor/mudar_senha_view'); ?>
<form name="formulario" method="post" action="">
	<table class="table table-hover">
		<tr>
			<td width="130px"><strong><?php echo $this->lang->line('label_senha_atual');?>:</strong></td>
			<td><input type="password" class="validate[required]" name="senha" /></td>
		</tr>
		<tr>
			<td><strong><?php echo $this->lang->line('label_senha_nova');?>:</strong></td>
			<td><input type="password" id="senha" class="validate[required,minSize[6]]" name="new_senha" /></td>
		</tr>
		<tr>
			<td><strong><?php echo $this->lang->line('label_repetir_senha');?>:</strong></td>
			<td><input type="password" class="validate[required,equals[senha],minSize[6]]" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" class="btn btn-success" value="<?php echo $this->lang->line('label_salvar');?>" />
			</td>
		</tr>
	</table>
</form>
