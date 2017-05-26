<?php 
$fabrica = $this->db->get('fabricas')->result();
  $apuracao = get_parameter("apuracao");
  $di_id = $this->uri->segment(3);
  $dis_dados = $this->db->where('di_id',$di_id)->get('distribuidores')->result();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Relatorio de bônus - <?php echo date('d-m-Y')?></title>
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
<h1><?php echo $dis_dados[0]->di_nome?> - <?php echo date('d/m/Y',strtotime($apuracao))?> - Detalhes de bônus</h1>


  <?php 
  
  $d = date('d',strtotime($apuracao));
  $m = date('m',strtotime($apuracao));
  $y = date('Y',strtotime($apuracao));
  
  //Três meses atras
  $meses = mktime(0,0,0,$m-3,$d,$y);
  $periodo = date("Y-m-01 00:00:00",$meses);
  
  
  $mes_anterior1 = date('Y-m-d',mktime(0,0,0,$m-1,01,$y));
  
  $qualificacao_passada = $this->db
		->where('hi_data',date('Y',strtotime($mes_anterior1))."-".date('m',strtotime($mes_anterior1))."-01")
		->where('hi_distribuidor',$di_id)
		->get('historico_qualificacao')->result();
  
  $qualificacao_passada = isset($qualificacao_passada[0]->hi_qualificacao)?$qualificacao_passada[0]->qualificacao_passada:1;

 
		 
  if($qualificacao_passada==1){

  $bonus_ind_anterior = $this->db
  ->where('bo_distribuidor',$di_id)
  ->select_sum('bo_valor')
  ->select_sum('bo_downline')
  ->where('bo_tipo',1)
  ->where('bo_atualizacao > ',$periodo)
  ->where('bo_deposito',0)
  ->get('bonus')->result();
  
  print_r($bonus_primeira_compra_anterior);exit;
  
  }else{
	  $bonus_primeira_compra_anterior = array();
	  }
  
  
  $bonus_primeira_compra = $this->db
  ->where('bo_distribuidor',$di_id)
  ->select_sum('bo_valor')
  ->select_sum('bo_downline')
    ->where('bo_mes',$m)
  ->where('bo_ano',$y)
  ->where('bo_tipo',1)
  ->where('bo_atualizacao > ',$periodo)
  ->get('bonus')->result();
  
  if(isset($bonus_primeira_compra_anterior[0]->bo_valor)){
	   $bonus_primeira_compra[0]->bo_valor += $bonus_primeira_compra_anterior[0]->bo_valor;
	   $bonus_primeira_compra[0]->bo_downline += $bonus_primeira_compra_anterior[0]->bo_downline;  
	  }


  $bonus_ativacao = $this->db
  ->where('bo_distribuidor',$di_id)
  ->select_sum('bo_valor')
  ->select_sum('bo_downline')
  ->where('bo_tipo',2)
  ->where('bo_mes',$m)
  ->where('bo_ano',$y)
  ->get('bonus')->result();
  

  $bonus_premio = $this->db
  ->where('bo_distribuidor',$di_id)
  ->select_sum('bo_valor')
  ->select_sum('bo_downline')
  ->where_in('bo_tipo',array(3,50,51,52,53,54,55,56))
  ->where('bo_deposito',0)
  ->where('bo_mes',$m)
  ->where('bo_ano',$y)
  ->get('bonus')->result();  

  $bonus_consumo = $this->db
  ->where('bo_distribuidor',$di_id)
  ->select_sum('bo_valor')
  ->select_sum('bo_downline')
    ->where_in('bo_tipo',array(4,5,6,7,8,9))
  ->where('bo_deposito',0)
  ->where('bo_mes',$m)
  ->where('bo_ano',$y)
  ->get('bonus')->result();  
  
     $total = 0;
  
	  $total += $bonus_primeira_compra[0]->bo_valor; 
	  $total += $bonus_ativacao[0]->bo_valor; 
	  $total += $bonus_consumo[0]->bo_valor;
	  $total += $bonus_premio[0]->bo_valor; 
  ?>

<table width="100%" class="table" border="0" cellspacing="0" cellpadding="0">
<thead>
  <tr class="title">
    <td width="2%">Tipo de bônus</td>
    <td width="30%">Downline</td>
    <td width="5%">Valor</td>
  </tr>
</thead>
<tbody>


  <tr>
    <td width="37%">Bônus de primeira compra</td>
    <td width="38%"><?php echo $bonus_primeira_compra[0]->bo_downline+0?></td>
    <td width="25%">R$ <?php echo number_format($bonus_primeira_compra[0]->bo_valor,2,',','.')?></td>
  </tr>

  <tr>
    <td width="37%">Bônus de Ativação</td>
    <td width="38%"><?php echo $bonus_ativacao[0]->bo_downline+0?></td>
    <td width="25%">R$ <?php echo number_format($bonus_ativacao[0]->bo_valor,2,',','.')?></td>
  </tr>

  <tr>
    <td width="37%">Bônus de Prêmio</td>
    <td width="38%"><?php echo $bonus_premio[0]->bo_downline+0?></td>
    <td width="25%">R$ <?php echo number_format($bonus_premio[0]->bo_valor,2,',','.')?></td>
  </tr>

  <tr>
    <td width="37%">Bônus de Consumo</td>
    <td width="38%"><?php echo $bonus_consumo[0]->bo_downline+0?></td>
    <td width="25%">R$ <?php echo number_format($bonus_consumo[0]->bo_valor,2,',','.')?></td>
  </tr>
  
    <tr>
    <td width="37%" colspan="2" align="right" style="font-size:20px;">Total: </td>
    <td width="25%" style="font-size:20px;">R$ <?php echo number_format($total,2,',','.')?></td>
  </tr>


</tbody>
</table>



</body>
</html>