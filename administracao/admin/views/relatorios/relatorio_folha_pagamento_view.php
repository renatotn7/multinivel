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
	<div class="box-content-header">Relatório de Folha de Pagamento</div>

	<div class="box-content-body">

		<div class="row-fluid">
			<div class="span">
				<form name="formulario" target="_blank"
					action="<?php echo base_url('index.php/relatorios/mostrar_relatorio_folha_pagamento')?>" 
					method="post">
					<p>Data:</p>
					<p>
						de: <input type="text" class="mdata" style="width: 90px;"
							size="10" value="01/<?php echo date('m/Y'); ?>" name="de"> até: <input type="text"
							class="mdata" style="width: 90px;" size="10" value="<?php echo date('d/m/Y'); ?>"
							name="ate">
					</p>
                    <p>Usuário:</p>
					<p>
						<input type="text" name="di_usuario" />
					</p>
                    
                    <p>Banco:</p>
					<p>
						<?php
							$bancos = $this->db->get('bancos')->result();
						?>
						<select name="ba_id">
							<option value="">Selecione</option>
							<?php
								foreach ($bancos as $banco) {
							?>
									<option value="<?php echo $banco->ba_id;?>">
							<?php
									echo $banco->ba_nome;
							?>
									</option>
							<?php
								}
							?>
						</select>
					</p>

					<input type="submit" class="btn btn-info" value="Gerar Relatário">
				</form>
			</div>
		</div>
	</div>
</div>
