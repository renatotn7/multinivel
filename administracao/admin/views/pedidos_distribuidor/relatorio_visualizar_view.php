<?php 
$fabrica = $this->db->get('fabricas')->result();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fabrica[0]->fa_nome;?> - Relatório Compra - <?php echo date('d-m-Y')?></title>
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
  font-size:15px;
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
<h1>Relatório de Compra - <?php echo date('d/m/Y')?></h1>


<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>

<table width="30%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>  <tr class="title">
   <td width="220px">
    Distribuidor :  
   </td>
  </tr>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Cidade :  
   </td>
  </tr>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Endereço :  
   </td>
  </tr>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Compra n° : 
   </td>
  </tr>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Valor da Compra n° : 
   </td>
  </tr>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Situação: 
   </td>
  </tr> 
  
  <td><td height="5px"></td></td>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Forma de Pagamento: 
   </td>
  </tr>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Valor total da compra: 
   </td>
  </tr>
  
  <td><td height="5px"></td></td>  <tr class="title">
   <td width="220px">
    Data da compra: 
   </td>
  </tr>

</thead>
</table>

</td>


<td>

<table width="70%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>  <tr class="title">
   <td width="220px">
   
   </td>
  </tr>
</thead>
</table>

</td>

</tr>
</table>
</body>
</html>