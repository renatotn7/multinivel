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
<h1><?php echo $fabrica[0]->fa_nome;?> - Relatório Vendas 
<?php if(get_parameter('onde')==1){?> CD
<?php }else{?>Distribuidor<?php }?> - <?php echo date('d/m/Y')?></h1>



<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">
    <td width="2%">Nº</td>
    <td width="40%">Distribuidor</td>
    <?php if(get_parameter('onde')==2){?>
    <td width="10%">CD</td>
    <?php }?>
    
    <td width="40%">Pagamento</td>
   
    <td width="10%">Forma de pagamento</td>
   
     <?php if(!get_parameter('agrupar')){?>
     <td width="12%">Data</td>
     <?php }?>
    <td width="5%">Pg. Voucher</td>
    <td width="5%">Valor</td>
    <td width="5%"></td>
    
  </tr>
</thead>
<tbody>

<?php
$total = 0; 
foreach($produtos as $p){
	$total+=$p->co_total_valor;
?>
  <tr>
    <td width="2%"><?php echo $p->co_id?></td>
    <td width="30%"><?php echo $p->di_nome?> / <b><?php echo $p->di_usuario?></b></td>
    <?php if(get_parameter('onde')==2){?>
    <td width="25%"><?php echo $p->cd_nome?></td>
    <?php }?>
    <td width="25%"><?php 
	$pagamentoTerceito = $this->db
		->where('rc_compra',$p->co_id)
		->join('distribuidores','di_id=rc_pagante')
		->get('registro_pagamento_compra_terceiro')->row();

		if($pagamentoTerceito){
			echo $pagamentoTerceito->di_nome.' / <b>'.$pagamentoTerceito->di_usuario.'</b>';
			}else{
				echo '-';
				}
	?></td>
    
    <td><?php echo $p->fp_descricao?></td>
  
    <?php if(!get_parameter('agrupar')){?>
    <td width="12%"><?php echo date('d/m/Y',strtotime($p->co_data_compra))?></td>
    <?php }?>
    <td width="10%"> <?php echo '0,00'?></td>
    <td width="10%"> <?php echo number_format($p->co_total_valor,2,',','.')?></td>
    <td width="5%" align="center">
<a  class="btn btn-primary" onclick="window.open('<?php echo base_url("index.php/relatorios/ver_relatorio_vendas/{$p->co_id}")?>','Relatório','width=900,height=650,left=100');" href="javascript:void(0);">Relatório</a>   
    </td>
  </tr>
<?php }?>


</tbody>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
    <div ><strong>Vendas encontradas:</strong> <?php echo $produtos_encontrados?></div>
    <div style="font-size:17px;"><strong>Valor total:</strong>$ <?php echo number_format($total,2,',','.')?></div>
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