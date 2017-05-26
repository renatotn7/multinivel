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
<h1><?php echo $fabrica[0]->fa_nome;?> - Relatório Vendas CD - <?php echo date('d/m/Y')?></h1>


<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">
    <td width="2%">Nº</td>
    <td width="10%">Distribuidor</td>
    <?php if(get_parameter('onde')==2){?>
    <td width="10%">CD</td>
    <?php }?>
   
    <td width="22%">Forma de pagamento</td>
   
     <?php if(!get_parameter('agrupar')){?>
     <td width="12%">Data</td>
     <?php }?>
    <td width="5%">Valor</td>
    
  </tr>
</thead>
<tbody>

<?php
$total = 0; 
foreach($produtos as $p){
	$total+=$p->cr_total_valor;
?>
  <tr>
    <td width="2%"><?php echo $p->cr_id?></td>
    <td width="30%"><?php echo $p->cd_nome?></td>
    <?php if(get_parameter('onde')==2){?>
    <td width="29%"><?php echo $p->cd_nome?></td>
    <?php }?>
    
    
    <td><?php echo $p->fp_descricao?></td>
  
    <?php if(!get_parameter('agrupar')){?>
    <td width="12%"><?php echo date('d/m/Y',strtotime($p->cr_data_compra))?></td>
    <?php }?>
    <td width="5%">$ <?php echo number_format($p->cr_total_valor,2,',','.')?></td>
  </tr>
<?php }?>


</tbody>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <div ><strong>Vendas encontradas:</strong> <?php echo $produtos_encontrados?></div>
    <div style="font-size:17px;"><strong>Valor total:</strong> R$ <?php echo number_format($total,2,',','.')?></div>
    </td>
    <td width="30%">
  <div>Vendas:</div>
  <div>de <strong><?php echo get_parameter('de')?></strong> até <strong><?php echo get_parameter('ate')?></strong></div>
  <a href="javascript:window.print()">Imprimir relatório</a>
  </td>
  </tr>
</table>
<br />


<?php echo $links?>


</body>
</html>