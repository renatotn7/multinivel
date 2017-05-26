<?php 
$fabrica = $this->db->get('fabricas')->result();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fabrica[0]->fa_nome;?> - Relatório produtos - <?php echo date('d-m-Y')?></title>
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
<h1>Relatório produtos vendidos - CD - <?php echo date('d/m/Y')?></h1>



<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">
    <td width="6%">Codigo</td>
    <td width="35%">Produto</td>
     <?php if(get_parameter('forma_pagamento')){?>
    <td width="20%">Forma de pagamento</td>
    <?php }?>
    <td width="9%">Quantidade</td>
    </tr>
</thead>
<tbody>

<?php
$total = 0; 
foreach($produtos as $p){
	$total+=($p->pc_quantidade*$p->pc_valor);
?>
  <tr>
    <td width="6%"><?php echo $p->pr_codigo?></td>
    <td width="35%"><?php echo $p->pr_nome?> <?php echo $p->pc_kit==1?" (Produto KIT)":""?></td>
    <?php if(get_parameter('forma_pagamento')){?>
    <td width="20%"><?php echo $p->fp_descricao?></td>
    <?php }?>
    <td width="9%"><?php echo $p->pc_quantidade?></td>
    </tr>
<?php }?>


</tbody>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <div ><strong>Produtos encontrados:</strong> <?php echo $produtos_encontrados?></div>
    <div style="font-size:17px;"></div>
    </td>
    <td width="30%">
  <div>Produtos mais vendidos:</div>
  <div>de <strong><?php echo get_parameter('de')?></strong> até <strong><?php echo get_parameter('ate')?></strong></div>
   <a href="javascript:window.print()">Imprimir relatório</a> 
    </td>
  </tr>
</table>
<br />


<?php echo $links?>


</body>
</html>