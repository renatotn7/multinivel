<?php 
$dias = array();
$diasSemana = array();
$strDiasSemana = '';
$quantidadeDias = 12;
$dataTimes = mktime(0,0,0,date('m'),date('d')-$quantidadeDias,date('Y'));

$faturamento = '';
$bonusRecebido = '';
$saques = '';
$transferencia = '';
$saldo = '';


for($d=0;$d<$quantidadeDias;$d++){
	  
	  $dataTimes = mktime(0,0,0,date('m',$dataTimes),date('d',$dataTimes)+1,date('Y',$dataTimes));
	  
	  $diaAtual = date('Y-m-d',$dataTimes);
	  $dias[] = $diaAtual;
	  $diasSemana[] = dia_semana_sigla(date('N',$dataTimes));
	  $strDiasSemana .= "'".dia_semana_sigla(date('N',$dataTimes)).' '.date('d/m',$dataTimes)."',";
	  
	  //Faturamento
	  $stdFaturamento = $this->db
	    ->select('SUM(co_total_valor) as valor')
		->where("DATE_FORMAT(co_data_compra,'%Y-%m-%d')",$diaAtual)
		->where("co_pago",1)
		->get('compras')
		->row();
	  
	 $faturamento .=  (isset($stdFaturamento->valor)?$stdFaturamento->valor:0).",";
	 
	 
	$stdBonusRecebido = $this->db
	    ->join('bonus_tipo','cb_tipo=tb_id')
	    ->select('SUM(cb_credito) as valor')
		->where("DATE_FORMAT(cb_data_hora,'%Y-%m-%d')",$diaAtual)
		->get('conta_bonus')
		->row();
	
	$bonusRecebido .=  (isset($stdBonusRecebido->valor)?$stdBonusRecebido->valor:0).",";	
	
	
	$stdSaques = $this->db
	    ->select('SUM(cdp_valor) as valor')
		->where("DATE_FORMAT(cdp_datetime,'%Y-%m-%d')",$diaAtual)
		->get('conta_deposito')
		->row();
	
	$saques .=  (isset($stdSaques->valor)?$stdSaques->valor:0).",";	 

	$stdTransferencia = $this->db
	    ->where('cb_tipo',4)
		->where('cb_credito <>',0)
	    ->select('SUM(cb_credito) as valor')
		->where("DATE_FORMAT(cb_data_hora,'%Y-%m-%d')",$diaAtual)
		->get('conta_bonus')
		->row();
	
	$transferencia .=  (isset($stdTransferencia->valor)?$stdTransferencia->valor:0).",";
	

	$stdSaldo = $this->db
	    ->select('SUM(cb_credito)-SUM(cb_debito) as valor')
		->where("DATE_FORMAT(cb_data_hora,'%Y-%m-%d') <=",$diaAtual)
		->get('conta_bonus')
		->row();
	
	$saldo .=  (isset($stdSaldo->valor)?$stdSaldo->valor:0).",";	
	
	
	  
	}

?>
<script src="<?php echo base_url()?>public/highcharts/js/highcharts.js"></script>
<script>

$(function () {
	
        $('#grafico_geral').highcharts({
            title: {
                text: 'Gráfico Geral',
                x: -20 //center
            },
            subtitle: {
                text: 'Dados Gerais',
                x: -20
            },
            xAxis: {
                categories: [<?php echo rtrim($strDiasSemana,',')?>]
            },
            yAxis: {
                title: {
                    text: 'Valor em US$'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ''
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Faturamento',
                data: [<?php echo rtrim($faturamento,',')?>]
            },
             {
                name: 'Bônus Recebido',
                data: [<?php echo rtrim($bonusRecebido,',')?>]
            },
			{
                name: 'Saques',
                data: [<?php echo rtrim($saques,',')?>]
            },
			{
                name: 'Transferência',
                data: [<?php echo rtrim($transferencia,',')?>]
            }
			,
			{
                name: 'Saldo',
                data: [<?php echo rtrim($saldo,',')?>]
            }
			 
			]
        });
    });

</script>
 
 <div id="grafico_geral" style="width:1000px;"></div>

 