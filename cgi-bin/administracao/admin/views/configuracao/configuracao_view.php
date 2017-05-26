<div class="box-content min-height">
	<div class="box-content-header">
		<a href="<?php  echo base_url('index.php/configuracao')?>">Configurações Gerais</a>
	</div>
	<div class="box-content-body">

		<form name="formulario" method="post"
			action="<?php echo base_url('index.php/configuracao/salvar_config');?>">
			  <fieldset>
  				<?php echo $html;?>
  				<br/>
			    <button type="submit" class="btn">Atualizar</button>
			  </fieldset>
			
		</form>

	</div>
</div>
