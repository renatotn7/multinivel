<?php 
$fabrica = $this->db->get('fabricas')->result();
$c = $this->db->where('co_id',$this->uri->segment(3))
     ->join('distribuidores','di_id=co_id_distribuidor')
	 ->join('cidades','di_cidade=ci_id')
	 ->get('compras')->result();
if(isset($c[0])){$c = $c[0];}else{redirect(base_url('index.php/pedidos'));}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fabrica[0]->fa_nome;?> - PEDIDO Nº <?php echo $c->co_id;?></title>
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
<h1><?php echo $fabrica[0]->fa_nome;?> - PEDIDO Nº <?php echo $c->co_id;?> </h1>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
     <strong><?php echo $c->di_nome?></strong><br />
     <?php echo $c->di_endereco?><br />
     <?php echo $c->di_bairro?>, CEP: <?php echo $c->di_cep?><br />
     <?php echo $c->ci_nome?>-<?php echo $c->ci_uf?><br /> 
     Fone: <?php echo $c->di_fone1?><br />
     <?php echo $c->di_email?>
    </td>
    <td width="30%">
     <b>Data:</b> <?php echo date('d/m/Y H:i:s',strtotime($c->co_data_compra))?><br />
     <b>Nº Pedido:</b> <?php echo $c->co_id;?><br />
     <b>Forma Pag.:</b> <?php echo $c->co_forma_pgt_txt;?><br />
     <b>Entrega:</b> <?php echo $c->co_entrega==1?"Entregar - ".$c->co_frete_tipo:"Retirar no CD";?> 
     <?php if($c->co_frete_gratis==1){echo " - Frete grátis";}?>
     <br />
     <b>Situação:</b> <?php echo compra_status($c->co_situacao);?>
    </td>
  </tr>
</table>
<br />

<?php if($c->co_entrega==0){
	$cd = $this->db->where('cd_id',$c->co_id_cd)->join('cidades','ci_id=cd_cidade')->get('cd')->result();
	?>
<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
  <tr class="title">
    <td><strong>Retirar produto no CD</strong>(<?php echo $cd[0]->cd_nome?>)</td>
  </tr>
  <tr>
    <td>
      <b><?php echo $cd[0]->cd_nome?></b><br />
      <?php echo $cd[0]->cd_endereco?><br />
      <?php echo $cd[0]->cd_bairro?>, <?php echo $cd[0]->cd_cep?><br />
      <?php echo $cd[0]->ci_nome?> - <?php echo $cd[0]->ci_uf?><br />
     Fone: <?php echo $cd[0]->cd_fone1?> - <?php echo $cd[0]->cd_fone2?><br />
     <?php echo $cd[0]->cd_email?>
    </td>
  </tr>
</table>
<?php }?>


<br />



<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">
    <td width="6%">Codigo</td>
    <td width="35%">Produto</td>
    <td width="9%">Quantidade</td>
    <td width="11%">Pontos unitário</td>
    <td width="12%">Total pontos</td>
    <td width="14%">Valor unitário</td>
    <td width="13%">Total</td>
  </tr>
</thead>
<tbody>


<?php 
$compra = $this->db->where('co_id',$c->co_id)->get('compras')->result();
$comp = $this->db
->join('produtos','pr_id = pm_id_produto')
->where('pm_id_compra',$c->co_id)->get('produtos_comprados')->result();

$valor_total = $pontos_total = 0;


foreach($comp as $c){
?>

  <tr>
    <td><?php echo $c->pr_codigo?></td>
    <td><strong><?php echo $c->pr_nome?></strong> 
	
	<?php 
	if($c->pr_kit==1){
	?>
    <br />
    <?php	
	$kit = $this->db
	->select(array('pr_nome','pr_codigo'))
	->join('produtos','pk_produto=pr_id')
	->where('pk_kit_comprado',$c->pm_id)->get('produtos_kit_opcoes')->result();
	foreach($kit as $k){
	?>
    <span style="font-size:10px; text-transform:uppercase;"> &not; <?php echo $k->pr_codigo?> - <?php echo $k->pr_nome?></span><br />
    <?php }}?> 
    </td>
    <td><?php echo $c->pm_quantidade?></td>
    <td><?php echo $c->pm_pontos?></td>
    <td><?php $pontos_total += $c->pm_pontos*$c->pm_quantidade;echo $c->pm_pontos*$c->pm_quantidade?></td>
    <td>R$ <?php echo number_format($c->pm_valor,2,',','.')?></td>
    <td>R$ <?php $valor_total += $c->pm_valor*$c->pm_quantidade;echo number_format(($c->pm_valor*$c->pm_quantidade),2,',','.')?></td>
  </tr>
<?php }?>

<?php if($pontos_total>0){?>
 <tr>
    <td colspan="6" align="right"><strong>Total Pontos:</strong></td>
    <td><?php echo $pontos_total?></strong></td>
  </tr>
<?php }?>  
 <tr>
    <td colspan="6" align="right"><strong>Total Produtos:</strong></td>
    <td>R$ <?php echo number_format(($valor_total),2,',','.')?></td>
  </tr>  
 <?php if($compra[0]->co_frete_valor>0){?> 
 <tr>
    <td colspan="6" align="right"><strong>Frete <?php echo $compra[0]->co_frete_tipo?>:</strong></td>
    <td>R$ <?php echo number_format($compra[0]->co_frete_valor,2,',','.')?></strong></td>
  </tr>  
 <?php }?>
 <tr>
    <td colspan="6" align="right"><strong>Total compra:</strong></td>
    <td><strong>R$ <?php echo number_format(($valor_total+$compra[0]->co_frete_valor),2,',','.')?></strong></td>
  </tr>

</tbody>
</table>






</body>
</html>