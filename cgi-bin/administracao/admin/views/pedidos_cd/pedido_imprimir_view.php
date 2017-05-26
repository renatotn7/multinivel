<?php 
$fabrica = $this->db->get('fabricas')->result();
$c = $this->db->where('cr_id',$this->uri->segment(3))
     ->join('cd','cd_id=cr_id_cd')
	 ->join('compra_situacao','st_id=cr_situacao')
	 ->join('cidades','cr_entrega_cidade=ci_id')
	 ->get('compras_fabrica')->result();
	 
if(isset($c[0])){$c = $c[0];}else{redirect(base_url('index.php/pedidos'));}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $fabrica[0]->fa_nome;?> - PEDIDO Nº <?php echo $c->cr_id;?></title>
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
<h1><?php echo $fabrica[0]->fa_nome;?> - PEDIDO Nº <?php echo $c->cr_id;?> </h1>

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td>
     <strong><?php echo $c->cd_nome?></strong><br />
     <?php echo $c->cd_endereco?><br />
     <?php echo $c->ci_nome?>-<?php echo $c->ci_uf?><br /> 
     Fone: <?php echo $c->cd_fone1?><br />
     <?php echo $c->cd_email?>
    </td>
    <td width="30%">
     <b>Data:</b> <?php echo date('d/m/Y H:i:s',strtotime($c->cr_data_compra))?><br />
     <b>Nº Pedido:</b> <?php echo $c->cr_id;?><br />
     <b>Forma Pag.:</b> <?php echo $c->cr_forma_pgt_txt;?><br />
     <b>Situação:</b> <?php echo $c->st_descricao;?>
    </td>
  </tr>
</table>
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
$compra[0] = $c;
$comp = $this->db
->join('produtos','pr_id = pc_id_produto')
->where('pc_id_compra',$c->cr_id)->get('produtos_comprados_fabrica')->result();

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
	->where('pk_kit_comprado',$c->pc_id)->get('produtos_kit_opcoes')->result();
	foreach($kit as $k){
	?>
    <span style="font-size:10px; text-transform:uppercase;"> &not; <?php echo $k->pr_codigo?> - <?php echo $k->pr_nome?></span><br />
    <?php }}?> 
    </td>
    <td><?php echo $c->pc_quantidade?></td>
    <td><?php echo $c->pc_pontos?></td>
    <td><?php $pontos_total += $c->pc_pontos*$c->pc_quantidade;echo $c->pc_pontos*$c->pc_quantidade?></td>
    <td>R$ <?php echo number_format($c->pc_valor,2,',','.')?></td>
    <td>R$ <?php $valor_total += $c->pc_valor*$c->pc_quantidade;echo number_format(($c->pc_valor*$c->pc_quantidade),2,',','.')?></td>
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
 <?php if($compra[0]->cr_frete_valor>0){?> 
 <tr>
    <td colspan="6" align="right"><strong>Frete <?php echo $compra[0]->cr_frete_tipo?>:</strong></td>
    <td>R$ <?php echo number_format($compra[0]->cr_frete_valor,2,',','.')?></strong></td>
  </tr>  
 <?php }?>
 <tr>
    <td colspan="6" align="right"><strong>Total compra:</strong></td>
    <td><strong>R$ <?php echo number_format(($valor_total+$compra[0]->cr_frete_valor),2,',','.')?></strong></td>
  </tr>

</tbody>
</table>






</body>
</html>