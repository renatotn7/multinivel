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
  SELECT di_id, di_usuario,di_nome,di_esquerda,di_direita,di_fone1,di_fone2 FROM distribuidores
  WHERE di_id IN(
   SELECT pg_distribuidor FROM registro_bonus_indireto_pagos GROUP BY pg_distribuidor
  )
 ")->result();
 ?>
 
 <table width="100%" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#f3f3f3"><b>Usuário</b></td>
    <td bgcolor="#f3f3f3"><b>Nome</b></td>
    <td bgcolor="#f3f3f3"><b>Esquerda</b></td>
    <td bgcolor="#f3f3f3"><b>Direita</b></td>
    <td bgcolor="#f3f3f3"><b>Menor</b></td>
    <td bgcolor="#f3f3f3"><b>Pago</b></td>
    <td bgcolor="#f3f3f3"><b>Valor Estorno</b></td>
  </tr>
 <?php
  $totalErrado = 0;
  $quantidadePessoa = 0;
  foreach($distribuidores as $distribuidor){
   
  
    $pontos  = new Pontos($distribuidor);
    $pontosEsquerda = $pontos->get_pontos_esquerda();
	$dataBinario = $pontos->get_data_binario();
  
    $pontosDireita = $pontos->get_pontos_direita();
			
    $pontosPagos = $pontos->get_pontos_pagos();
    $pernaMenor = $pontosEsquerda < $pontosDireita?$pontosEsquerda:$pontosDireita;
	
	if($pontosPagos > $pernaMenor){
		 $valorEstorno = ($pontosPagos-$pernaMenor)*0.2;
		 $pontosParaEstorno = ($pontosPagos-$pernaMenor);
		}else{
			$valorEstorno = 0;
			$pontosParaEstorno = 0;
			}
	if($valorEstorno >0 ){
		
	$totalErrado += $valorEstorno;		
    $quantidadePessoa++;
	
	
		 $jaDevolveu = $this->db
		  ->where('db_distribuidor',$distribuidor->di_id)
		  ->get('devolucao_bonus_indevido')->row();
	
      
		  if(count($jaDevolveu) == 0){
			   
			   if(isset($dataBinario->db_data)){
				   $descricaoPay = "
				   Estorno de bônus pago indevidamente. 
				   O responsável pelo financeiro da Nossa Empresa entrará em contato para averiguação.
				   <br>
				   <div style='font-size:12px;'>Você ativou o binário (indicou um direto no lado direito e um direto no lado esquerdo)
				   em <b>".date('d/m/Y H:i:s',strtotime($dataBinario->db_data))."</b>, se qualificando a receber
				   pontos do binário. Foi pago indevidamente <b>".number_format($pontosParaEstorno,0,'','.')."</b> pontos. Os pontos foram gerados
				   antes da data em que você ativou o binário.<div>
				   ";
			   }else{
				   $descricaoPay = "
				   Estorno de bônus pago indevidamente. 
				   O responsável pelo financeiro da Nossa Empresa entrará em contato para averiguação.
				   ";
				   }
			   
			   $this->db->insert('conta_bonus',array(
			    'cb_distribuidor'=>$distribuidor->di_id,
				'cb_compra'=>0,
				'cb_descricao'=>$descricaoPay,
				'cb_credito'=>0,
				'cb_debito'=>$valorEstorno,
				'cb_tipo'=>234
			   ));
			   
			   $idContaBonus = $this->db->insert_id();
			   
			   $this->db->insert('devolucao_bonus_indevido',array(
			    'db_distribuidor'=>$distribuidor->di_id,
				'db_pontos'=>$pontosParaEstorno,
				'db_valor'=>$valorEstorno,
				'db_id_conta_bonus'=>$idContaBonus,
				'db_data'=>date('Y-m-d'),
			   ));
			   
			   $this->db->insert('registro_bonus_indireto_pagos',array(
			    'pg_distribuidor'=>$distribuidor->di_id,
				'pg_pontos'=>(-$pontosParaEstorno),
				'pg_data'=>date('Y-m-d')
			   ));
			   
			  }
		  
	
	
  ?> 
  <tr>
    <td><?php echo $distribuidor->di_usuario?></td>
    <td><?php echo $distribuidor->di_nome?></td>
    <td><?php echo number_format($pontosEsquerda,0,',','.')?></td>
    <td><?php echo number_format($pontosDireita,0,',','.')?></td>
    <td><?php echo number_format($pernaMenor,0,',','.')?></td>
    <td><?php echo number_format($pontosPagos,0,',','.')?></td>
   <td><?php echo number_format( $valorEstorno,2,',','.')?></td>
  </tr>
 <?php }}?> 
</table>

 <strong>Total com erro:</strong> <?php echo number_format($totalErrado,2,',','.');?><br>
 <strong>Quantidade:</strong> <?php echo number_format($quantidadePessoa,0,',','.');?>
 
 
</body>
</html>