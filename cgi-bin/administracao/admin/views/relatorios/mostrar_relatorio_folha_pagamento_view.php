<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Relatório de Folha de Pagamento</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/bootstrap/css/bootstrap.css" />
</head>

<body>
<style>
	.folha_pagamento thead tr th{
		background:#f6f6f6;
		text-align:center;
	}
	.folha_pagamento tr td{
		vertical-align:middle;
		text-transform:capitalize;
		text-align:center;
	}	
</style>
<script type="text/javascript">
	function imprimir(){
		document.getElementById("imprimir").style.display="none";
		window.print();
	}
</script>
<center><h3>Relatório de Folha de Pagamento</h3></center>
<table id="folha_pagamento" width="100%" align="center" class="table table-bordered folha_pagamento" >
				<thead>
				      <tr style="font-size:12px;">
						<th width="15%">Nome</th>
                        <th width="10%">Usuário</th>
						<th width="10%">CPF</th>
						<th width="10%">Data Solicitação</th>
                        <th width="10%">Nome/Cpf</th>
						<th width="10%">Banco</th>
						<th width="5%">Agencia</th>
						<th width="5%">Conta</th>
						<th width="5%">Operação</th>
                        <th width="10%">Telefones</th>
                        <th width="10%">Valor (US $)</th>
					</tr>
				</thead>
                <tbody>
  					<?php
					if(count($depositos) > 0){
						$total = 0.00;
						foreach ($depositos as $deposito){
							$total += $deposito->cdp_valor;
					?>  
                        	  
							<tr style="font-size:12px;">
								<td style="text-align:left;"><?php echo $deposito->di_nome;?></td>
                                <td style="text-transform:none !important">
								<?php echo $deposito->di_usuario;?>
                                </td>
								<td>
									<?php
                                		if($deposito->di_cpf != '' && $deposito->di_cpf != 0){
											echo mascaracpfcnpj($deposito->di_cpf);
										}else{
											echo 'Não Informado';
										}
									 ?>
								</td>
								<td><?php echo date('d/m/Y',strtotime($deposito->cdp_data));?></td>
                                <td><?php 
								if($deposito->cpd_conta_distribuidor == 1){
											
												echo $deposito->di_conta_nome."<br>".$deposito->di_conta_cpf;
																						
										}else{
											
												echo $deposito->di_conta_titular2."<br>".$deposito->di_conta_cpf2;
											
										}
								?></td>
								<td>
									<?php 
										if($deposito->ba_nome != ''){
											echo $deposito->ba_nome;
										}else{
											echo 'Não Informado';
										}
									?>
								</td>
								<td>
									<?php 
										if($deposito->cpd_conta_distribuidor == 1){
											if($deposito->di_conta_agencia != '' && $deposito->di_conta_agencia != 0){
												echo $deposito->di_conta_agencia;
											}else{
												echo 'Não Informado';
											}											
										}else{
											if($deposito->di_conta_agencia2 != '' && $deposito->di_conta_agencia2 != 0){
												echo $deposito->di_conta_agencia2;
											}else{
												echo 'Não Informado';
											}
										}
									?>
								</td>
								<td>
									<?php 
										if($deposito->cpd_conta_distribuidor == 1){
											if($deposito->di_conta_numero != '' && $deposito->di_conta_numero != 0){
												echo $deposito->di_conta_numero;
											}else{
												echo 'Não Informado';
											}
										}else{
											if($deposito->di_conta_numero2 != '' && $deposito->di_conta_numero2 != 0){
												echo $deposito->di_conta_numero2;
											}else{
												echo 'Não Informado';
											}
										}
									?>
								</td>
								<td>
									<?php 
										if($deposito->cpd_conta_distribuidor == 1){
											if($deposito->di_conta_operacao != ''){
												echo $deposito->di_conta_operacao;
											}else{
												echo 'Não Informado';
											}
										}else{
											if($deposito->di_conta_operacao2 != '' && $deposito->di_conta_operacao2 != 0){
												echo $deposito->di_conta_operacao2;
											}else{
												echo 'Não Informado';
											}
										}
									?>
								</td>	
								<td>
									<?php 
										echo $deposito->di_fone1.'<br />'.$deposito->di_fone2;
									?>
								</td>
								<td>
									<?php 
										echo(number_format($deposito->cdp_valor, 2, ',', '.'));
									?>
								</td>				
							</tr>
						<?php
                        	}
						}else{
							echo('<center><h3>Não foram encontrados depositos para esta pesquisa!</h3></center>');
						}
						?>
  				</tbody>
          </table>
          <center>
              <table width="50%">
                <tr>
                    <td><strong>Depositos a fazer:</strong></td>
                    <td><?php echo count($depositos); ?></td>
                </tr>
                <tr>
                    <td><strong>Total em depositos (US $):</strong></td>
                    <td><?php echo number_format($total, 2, ',', '.'); ?></td>
                </tr>
              </table>
          </center>
          <br><br>
          <a href="javascript:imprimir();" id="imprimir" class="btn btn-success">Imprimir</a>
</body>
</html>
