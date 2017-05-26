<div class="box-content min-height">
	<div class="box-content-header">
		<a href="<?php  echo base_url('index.php/financeiro')?>">Deposito em Conta</a>

	</div>
	<div class="box-content-body">

		<form name="formulario" method="post"
			action="<?php echo base_url('index.php/financeiro/salvar_conta_depoisto');?>">

			<table width="100%" border="0" cellspacing="0" cellpadding="7">

				<tr>
					<td width="153"><label>Administrar data Deposito em Conta :</label></td>
					<td width="1016"><input type="text"
						name="administrar_data_deposito_em_conta"
						class="validate[required]"
						value="<?php echo $ct_deposito->valor;?>" size="50" /></td>
				</tr>

				<tr>
					<td><input type="submit" value="Atualizar" class="btn btn-primary">

					</td>
				</tr>
			</table>

		</form>

	</div>
</div>