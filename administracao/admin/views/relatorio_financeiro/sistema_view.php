<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Relatório Geral Financeiro</title>
		<script src="<?php echo base_url()?>public/script/validar/js/jquery-1.6.min.js"></script>
        <link href="<?php echo base_url()?>public/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">
    </head>
	<body>
	<?php 
	$dataPrimeiraCompra = $this->db->select('co_data_compra')->where('co_pago',1)->order_by('co_data_compra','asc')->get('compras',1)->row();
	?>

		<table class="table table-bordered" style="width:800px;">
			<?php 
				error_reporting(1);
				$pontos = new CalculaPontosPagos();
				$dia = date('Y-m-d',strtotime($dataPrimeiraCompra->co_data_compra));
				$faturamentoTotal = 0;
				$lucroLiquidoTotal = 0;
				$BonusBinarioTotal = 0;
				while($dia <= date('Y-m-d')){
					
					$faturamento = $this->db
					->select('SUM(co_total_valor) as valor')
					->where('co_pago',1)
					->like('co_data_compra',$dia)	
					->get('compras')->row();
					
					$bonusPl = $this->db
					->select('SUM(rbpl_valor) as valor')
					->where('rbpl_data_fatura',$dia)
					->get('registro_bonus_pl')->row();
					
					
					$bonusIndicacao = $this->db
					->select('SUM(cb_credito) as valor')
					->where('cb_tipo',1)
					->like('cb_data_hora',$dia)
					->get('conta_bonus')->row();
					$bonusIndicacao  = (float)$bonusIndicacao->valor;
					
					$bonusVolumeVenda = $this->db
					->select('SUM(cb_credito) as valor')
					->where('cb_tipo',107)
					->like('cb_data_hora',$dia)
					->get('conta_bonus')->row();
					$bonusVolumeVenda = (float)$bonusVolumeVenda->valor;
					
					$bonusBinario = $pontos->getValorBonusBinario($dia);
					
					
					#---------------------Custo do Produto-----------------
							$produtosComprados = $this->db->query("
							 SELECT ( SUM( pm_quantidade ) * ( SELECT valor FROM config WHERE field = 'custo_base_produto' ) ) + (SUM( pm_quantidade ) * cp_valor_frete) AS totalComFrete, 
							 SUM(pm_valor_total) AS faturamentoTotal 
							 FROM produtos_comprados
							 JOIN compras ON co_id = pm_id_compra
							 JOIN custo_produto ON cp_uf = co_entrega_uf 
							 WHERE co_pago =1
							 AND co_data_compra LIKE '$dia%'
							 GROUP BY co_entrega_uf
							 ")->result();
							 
					$custoProduto  = 0;
					foreach($produtosComprados as $produto){
						 $custoProduto += $produto->totalComFrete;
					}
					
					if($faturamento->valor >0){
						$lucroLiquido = ($faturamento->valor - ($bonusIndicacao+$bonusVolumeVenda+$bonusPl->valor+$bonusBinario+$custoProduto));
						$faturamentoTotal += $faturamento->valor;
						$lucroLiquidoTotal += $lucroLiquido;
						$BonusBinarioTotal += $bonusBinario;
				?>
                        <tr>
                            <td bgcolor="#f0f0f0" colspan="2"><?php echo date('d M Y',strtotime($dia))?></td>
                        </tr>
                        <tr>
                            <td>Faturamento</td>
                            <td><?php echo number_format($faturamento->valor,2,',','.')?></td>
                        </tr>
                        <tr>
                            <td>Bônus Indicação</td>
                            <td><?php echo number_format($bonusIndicacao,2,',','.')?></td>
                        </tr>
                        <tr>
                            <td>Bônus Volume Venda</td>
                            <td><?php echo number_format($bonusVolumeVenda,2,',','.')?></td>
                        </tr>
                        <tr>
                            <td>Bônus Binário</td>
                            <td><?php echo number_format($bonusBinario,2,',','.')?></td>
                        </tr>
                        <tr>
                            <td>Bônus PL</td>
                            <td><?php echo number_format($bonusPl->valor,2,',','.')?></td>
                        </tr>
                        <tr>
                            <td>Custo do Produto</td>
                            <td><?php echo number_format($custoProduto,2,',','.')?></td>
                        </tr>
                        <tr>
                            <td>Lucro Liquido</td>
                            <td><?php echo number_format($lucroLiquido,2,',','.')?></td>
                        </tr>
			<?php
					}
				 //Adicionando um novo dia
				 $dia = date('Y-m-d',mktime(0,0,0,date('m',strtotime($dia)),date('d',strtotime($dia))+1,date('Y',strtotime($dia))));
			}
			?>
		</table>
        <table class="table" style="width:800px;">
         	<tr>
				<td bgcolor="#C9E9C0">
					Faturamento Total<br>
					<?php echo number_format($faturamentoTotal,2,',','.')?>
				</td>
				<td bgcolor="#C9E9C0">
					Total Bônus Binário<br>
					<?php echo number_format($BonusBinarioTotal,2,',','.')?>
				</td>
				<td bgcolor="#C9E9C0">
					Lucro Liquido Total<br>
					<?php echo number_format($lucroLiquidoTotal,2,',','.')?>
				</td>
			</tr>
        </table>
</body>
</html>