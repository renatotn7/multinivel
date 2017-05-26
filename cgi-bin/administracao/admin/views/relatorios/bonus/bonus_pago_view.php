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
<?php $mes = get_parameter('mes')?get_parameter('mes'):date('m');
$ano = get_parameter('ano')?get_parameter('ano'):date('Y');
?>
<form  style="padding:10px; background:#f3f3f3;" id="form1" name="form1" method="get" action="">
  Mês:
<select name="mes">
<option <?php echo $mes==01?'selected':''?> value="01">01</option>
<option <?php echo $mes==02?'selected':''?> value="02">02</option>
<option <?php echo $mes==03?'selected':''?> value="03">03</option>
<option <?php echo $mes==04?'selected':''?> value="04">04</option>
<option <?php echo $mes==05?'selected':''?> value="05">05</option>
<option <?php echo $mes==06?'selected':''?> value="06">06</option>
<option <?php echo $mes==07?'selected':''?> value="07">07</option>
<option <?php echo $mes==08?'selected':''?> value="08">08</option>
<option <?php echo $mes==09?'selected':''?> value="09">09</option>
<option <?php echo $mes==10?'selected':''?> value="10">10</option>
<option <?php echo $mes==11?'selected':''?> value="11">11</option>
<option <?php echo $mes==12?'selected':''?> value="12">12</option>
</select>
  Ano:
  <input type="text" size="4" name="ano" value="<?php echo $ano?>" />
  <input type="submit" value="ver" />
</form>
<h1><?php echo $fabrica[0]->fa_nome;?> - Relatório Bônus Pago - 
<?php echo date('m/Y',mktime(0,0,0,date('m')-1,01,date('Y')))?></h1>



<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">
    <td width="2%">NI</td>
    <td width="30%">Distribuidor</td>

    <td width="5%">Valor</td>
  </tr>
</thead>
<tbody>

<?php
$total = 0; 
foreach($bonus as $c){
	$total+=$c->cb_credito;
?>
  <tr>
    <td width="2%"><?php echo $c->di_id?></td>
    <td width="30%"><?php echo $c->di_nome?></td>
    <td width="5%"><?php echo number_format($c->cb_credito,2,',','.')?></td>
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





</body>
</html>