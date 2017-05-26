<?php 
$fabrica = $this->db->get('fabricas')->result();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fabrica[0]->fa_nome;?> - Relatório Vendas - <?php echo date('d-m-Y')?></title>
<style>
body{
	color:#000000;
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:12px;
	}
h1 {
  border-bottom-color:#CDDDDD;
  border-bottom-style:solid;
  border-bottom-width:1px;
  color:#CCCCCC;
  font-size:20px;
  font-weight:normal;
  margin-bottom:15px;
  margin-top:0;
  padding-bottom:5px;
  text-align:right;
  text-transform:uppercase;
}

.table {
  border-right-color:#CDDDDD;
  border-right-style:solid;
  border-right-width:1px;
  border-top-color:#CDDDDD;
  border-top-style:solid;
  border-top-width:1px;
  margin-bottom:20px;
  width:100%;
}
.title td {
  background-color:#E7EFEF;
  background-position:initial initial;
  background-repeat:initial initial;
}
.table th, .table td {
  border-bottom-color:#CDDDDD;
  border-bottom-style:solid;
  border-bottom-width:1px;
  border-left-color:#CDDDDD;
  border-left-style:solid;
  border-left-width:1px;
  padding:5px;
  vertical-align:text-bottom;
}
</style>
</head>
<body>
<h1>Relatório de repasse de bônus primeira compra - de 
    <?php echo get_parameter('de')?> até <?php echo get_parameter('ate')?></h1>

<?php

 $valor_kit_cd = 159;
 $valor_kit_distribuidor = 250;
 $valor_repasse = 51;

		$kits_cd = $this->db
		->join('produtos','pc_id_produto=pr_id')
		->join('compras_fabrica','cr_id=pc_id_compra')
		->where('cr_data_compra >=',data_usa(get_parameter('de'))." 00:00:00")
		->where('cr_data_compra <=',data_usa(get_parameter('ate'))." 23:59:59")
		->where('pr_kit_tipo',1)
		->where('cr_pago',1)
		->get('produtos_comprados_fabrica')->result();
		
		$num_kit_cd = 0;
		foreach($kits_cd as $kit){
			$num_kit_cd += $kit->pc_quantidade;
			}
		

		$kits_distribuidor = $this->db
		->join('produtos','pm_id_produto=pr_id')
		->join('compras','co_id=pm_id_compra')
		->select_sum('pm_quantidade')
		->where('co_data_compra >=',data_usa(get_parameter('de'))." 00:00:00")
		->where('co_data_compra <=',data_usa(get_parameter('ate'))." 23:59:59")
		->where('pr_kit_tipo',1)
		->where('co_id_cd',0)
		->where('co_entrega',1)
		->where('co_pago',1)
		->get('produtos_comprados')->result();
  

$total_cd = $num_kit_cd*$valor_kit_cd;
$total_distribuidor = $kits_distribuidor[0]->pm_quantidade * $valor_kit_distribuidor; 
?>


<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">

  <tr class="title">
    <td width="10%" bgcolor="#999999">&nbsp;</td>
    <td width="27%">CD</td>
    <td width="27%">Distribuidor</td>
    <td width="14%">Total</td>
    <td width="22%" align="center">Repasse</td>
  </tr>
  <tr>
    <td bgcolor="#ccc"><strong>Quantidade</strong></td>
    <td><?php echo $num_kit_cd+0?></td>
    <td><?php echo $kits_distribuidor[0]->pm_quantidade?></td>
    <td><?php echo $num_kit_cd+$kits_distribuidor[0]->pm_quantidade?></td>
    <td rowspan="2" align="center" style="font-size:24px; vertical-align:middle !important;">
    R$ <?php echo number_format(($num_kit_cd+$kits_distribuidor[0]->pm_quantidade)*$valor_repasse,2,',','.'); ?></td>
  </tr>
  <tr>
    <td bgcolor="#ccc"><strong>Valor</strong></td>
    <td>R$ <?php echo number_format(($num_kit_cd*$valor_kit_cd),2,',','.'); ?></td>
    <td>R$ <?php echo number_format(($kits_distribuidor[0]->pm_quantidade*$valor_kit_distribuidor),2,',','.'); ?></td>
    <td>R$ <?php echo number_format(($total_cd)+($total_distribuidor),2,',','.'); ?></td>
  </tr>
</table>

<a href="javascript:window.print()">Imprimir relatório</a>




</body>
</html>