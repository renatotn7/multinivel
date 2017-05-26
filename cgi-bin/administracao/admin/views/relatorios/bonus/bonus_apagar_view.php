<?php 
$fabrica = $this->db->get('fabricas')->result();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fabrica[0]->fa_nome;?> - Relatório Vendas - <?php echo date('d-m-Y')?></title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/bootstrap/css/bootstrap.css" />
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
  font-size:24px;
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
<h1><?php echo $fabrica[0]->fa_nome;?> - Relatório Bônus a Pagar - 
<?php echo date('m/Y',mktime(0,0,0,date('m')-1,01,date('Y')))?></h1>

<style>
.pago td{color:#090;}
.pago td a{color:#090;}
</style>

<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">
    <td width="10%">NI</td>
    <td width="40%">Distribuidor</td>
    <td width="20%">Bônus</td>
    <td width="10%">Apuração</td>
    <td width="10%">Valor</td>
    <td></td>
  </tr>
</thead>
<tbody>

<?php
$total = 0; 
foreach($creditos as $c){
	$total += $c->cb_credito-$c->cb_debito;
	$totalaPagar = $c->cb_credito-$c->cb_debito;
?>
  <tr class=''>
    <td width="10%"><?php echo $c->di_id?></td>
    <td width="40%"><?php echo $c->di_nome;//anchor('relatorios/bonus_apagar_detalhes/'.$c->di_id.'?apuracao='.$c->cb_data_hora,$c->di_nome)?> </td>
    <td width="20%"><?php echo $c->tb_descricao?></td>
    <td width="10%"><?php echo date('m/Y',strtotime($c->cb_data_hora))?></td>
    <td width="10%"><?php echo number_format($totalaPagar,2,',','.')?></td>
    <td align="center" style="text-align:center;"><a  class="btn btn-info" onclick="window.open('<?php echo base_url("/index.php/relatorios/detalhes_distribuidor_bonus_apagar/{$c->di_id}")?>','Relatório','width=900,height=650,left=100');" href="javascript:void(0);">Detalhes</a></td>
  </tr>
<?php }?>


</tbody>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <div ><strong>Bônus a pagar:</strong> <?php echo $encontrados?></div>
    <div style="font-size:17px;"><strong>Valor total:</strong> R$ <?php echo number_format($total,2,',','.')?></div>
    </td>
    <td width="30%">

  <a href="javascript:window.print()">Imprimir relatório</a>
  </td>
  </tr>
</table>
<br />


<?php echo $links?>


</body>
</html>