<style>
h1 {
	border-bottom-color: #CDDDDD;
	border-bottom-style: solid;
	border-bottom-width: 1px;
	color: #CCCCCC;
	font-size: 24px;
	font-weight: normal;
	margin-bottom: 15px;
	margin-top: 0;
	padding-bottom: 5px; 
	text-align: left;
	text-transform: uppercase;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="box-content min-height">
	<div class="box-content-header">Relatório Distribuidores</div>

	<div class="box-content-body">

		<div class="row-fluid">
			<div class="span">
				<form name="formulario" target="_blank"
					action="<?php echo base_url('index.php/relatorios/relatorio_distribuidores')?>" 
					method="post">
					<p>Data:</p>
					<p>
						de: <input type="text" class="mdata" style="width: 90px;"
							size="10" value="01/12/2013" name="de"> até: <input type="text"
							class="mdata" style="width: 90px;" size="10" value="18/12/2013"
							name="ate">
					</p>

					<input type="submit" class="btn btn-info" value="Gerar Relatário">
				</form>
			</div>
		</div>
	</div>
</div>
