<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Relatório Geral Financeiro</title>
		<script src="<?php echo base_url()?>public/script/validar/js/jquery-1.6.min.js"></script>
        <link href="<?php echo base_url()?>public/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">
    </head>
<body>
 <?php 
 $distribuidores = $this->db->query("
  SELECT di_id, di_usuario,di_nome,di_esquerda,di_direita,di_fone1,di_fone2 FROM distribuidores WHERE 
   di_id IN(
    SELECT cb_distribuidor FROM conta_bonus WHERE cb_tipo = 2 GROUP BY cb_distribuidor
   )
 ")->result();
 ?>
 
 <table width="100%" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#f3f3f3"><b>Usuário</b></td>
    <td bgcolor="#f3f3f3"><b>Nome</b></td>
    <td bgcolor="#f3f3f3"><b>Fone</b></td>
    <td bgcolor="#f3f3f3"><b>Fone</b></td>
    <td bgcolor="#f3f3f3"><b>Valor Estorno</b></td>
  </tr>
 <?php
  $totalErrado = 0;
  foreach($distribuidores as $distribuidor){
   $dataBinario = $this->db->where('db_distribuidor',$distribuidor->di_id)->get('registro_distribuidor_binario')->row(); 
  $jaDevolveu = $this->db
		  ->where('db_distribuidor',$distribuidor->di_id)
		  ->get('devolucao_bonus_indevido')->row();
  if(count($jaDevolveu) >0){
  ?> 
  <tr>
    <td><?php echo $distribuidor->di_usuario?></td>
    <td><?php echo $distribuidor->di_nome?></td>
    <td><?php echo $distribuidor->di_fone1?></td>
    <td><?php echo $distribuidor->di_fone2?></td>
   <td><?php
		  
		   echo number_format($jaDevolveu->db_valor,2,',','.');;
		   $totalErrado += $jaDevolveu->db_valor;
			 
	?></td>
  </tr>
 <?php }}?> 
</table>

 Total com erro: <?php echo number_format($totalErrado,2,',','.');?>
 
 
</body>
</html>