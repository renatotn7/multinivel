<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sem título</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/bootstrap/css/bootstrap.css" />
</head>

<body>

<table width="100%" border="0" align="center" class="table" >
		<tr>
			<td align="center"><h3>Relatório de Distribuidores</h3></td>
		</tr>
		<tr>
			<td align="center">
			
			<table width="990"  cellpadding="0" cellspacing="0" class="table  table-bordered">
				<thead>
				      <tr style="font-size:12px;">
						<th width="2%">N°</th>
						<th width="8%">Nome</th>
                        <th width="4%">Usuário</th>
						<th width="8%">CPF</th>
						<th width="6%">Estado Civil</th>
						<th width="8%">Data Nascimento</th>
						<th width="10%">Email</th>
						<th width="8%">Telefones</th>
						<th width="8%">Sexo</th>
						<th width="10%">Endereço</th>
                        <th width="4s%">N°</th>
                        <th width="8%">CEP</th>
                        <th width="8%">Ip de cadastro</th>
					</tr>
				</thead>
  <?php foreach ($distribuidores as $distribuidor){?>    
                    <tr style="font-size:12px;">
						<td><?php echo $distribuidor->di_id;?></td>
						<td><?php echo $distribuidor->di_nome;?></td>
                        <td><?php echo $distribuidor->di_usuario;?></td>
						<td><?php echo mascaracpfcnpj($distribuidor->di_cpf); ?></td>
						<td><?php echo $distribuidor->di_estado_civil;?></td>
						<td><?php echo date('d/m/Y',strtotime($distribuidor->di_data_nascimento));?></td>
						<td width="8%"><?php echo $distribuidor->di_email;?></td>
						<td>
						 <abbr title="Phone">Fone:</abbr><?php echo $distribuidor->di_fone1."<br>";?>
						 <abbr title="Phone">Fone:</abbr><?php echo $distribuidor->di_fone2;?>
			            </td>
						<td><?php echo $distribuidor->di_sexo=="M"?"Masculino":"Feminino";?></td>
						<td>
                            <address>
                              <?php echo $distribuidor->di_endereco;?> , 
                                
                               <?php echo $distribuidor->di_complemento;?><br>
                               <?php echo $distribuidor->di_bairro." - ";?> 
                                
							 <br>
                               <?php echo $distribuidor->ci_nome;?> , <?php echo $distribuidor->ci_uf;?><br>
                             
                            </address>
                        </td>
                        <td><?php echo $distribuidor->di_numero;?></td>
                        <td><?php echo $distribuidor->di_cep;?></td>
                        <td><?php echo $distribuidor->di_ip_cadastro;?></td>
						
					</tr>
  <?php }?>    
          </table>
          </td>
       </tr>
			
	</table>
</body>
</html>
