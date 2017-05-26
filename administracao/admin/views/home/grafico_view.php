
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/graficos')?>/jquery.jqplot.min.css" />
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo base_url('public/graficos')?>/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('public/graficos')?>/jqplot.pieRenderer.js"></script>

<script type="text/javascript" src="<?php echo base_url('public/graficos')?>/plugins/jqplot.barRenderer.js"></script>

<?php 
 $dis_tivos = $this->db->where('di_ativo',1)->get('distribuidores')->num_rows;
 $dis_inativos = $this->db->where('di_ativo',0)->get('distribuidores')->num_rows;
 
 $compra_pagas = $this->db->like('co_data_compra',date('Y-m-'))
                 ->where('co_situacao <>',-1)
                 ->where('co_pago',1)->get('compras')->num_rows;
 $compra_pendentes = $this->db->like('co_data_compra',date('Y-m-'))
                 ->where('co_situacao <>',-1)
                 ->where('co_pago',0)->get('compras')->num_rows;				 

 $pagos = $this->db->select_sum('co_total_valor')
                   ->like('co_data_compra',date('Y-m-'))
				   ->where('co_situacao <>',-1)
				   ->where('co_pago',1)
				   ->get('compras')->result();
				   
 $nao_pagos = $this->db->select_sum('co_total_valor')
                       ->like('co_data_compra',date('Y-m-'))
					   ->where('co_situacao <>',-1)
				       ->where('co_pago',0)
                      ->get('compras')->result();				 
?>

<script type="text/javascript">

$(document).ready(function(){
 
 	
  jQuery.jqplot('grafico-pedidos', 
    [[['Concluidas',<?php echo $compra_pagas?>],['Pendentes',<?php echo $compra_pendentes?>]]], 
    {
      title: 'Compras', 
      seriesDefaults: {
        shadow: false, 
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: { 
          startAngle: 180, 
          sliceMargin: 4,
		  showDataLabels: true, 
		  dataLabels: 'value'
          } 
      }, 
      legend: { show:true, location: 'w' }
    }
  ); 
 
   jQuery.jqplot('grafico-faturamento', 
    [[['Concluidas',<?php echo $pagos[0]->co_total_valor?>],['Pendentes',<?php echo $nao_pagos[0]->co_total_valor?>]]], 
    {
      title: 'Faturamento geral', 
      seriesDefaults: {
        shadow: false, 
        renderer: jQuery.jqplot.PieRenderer, 
        rendererOptions: { 
          startAngle: 180, 
          sliceMargin: 4,
		  showDataLabels: true, 
		  dataLabels: 'value'
          } 
      }, 
      legend: { show:true, location: 'w' }
    }
  ); 
  
});

</script>

<style>
.dia-grid{
	height:182px;
	width:97%;
	position:relative;
	border:1px solid #FFF0BB;
	text-align:center;
	font-size:12px;
	background:#fff;
	}
.dia-grid .dia{
	position:absolute;
	bottom:0;
	width:100%;
	background:#4BB2C5;
	color:#FFF;
	}
.dia-grid:hover .dia{
	background:#639;
	}		
</style>

<div class="painel">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="grafico-pedidos" style="height:250px;width:300px; "></div></td>
    <td><div id="grafico-faturamento" style="height:250px;width:300px; "></div></td>
    <td>
    <strong>Cadastros por dia</strong>
    <table width="300px" style="background:#FFFDF6; border:2px solid #999;" 
    class="table-calendario" border="0" cellspacing="0" cellpadding="2">
  <tr>
  <?php 
  function semana(){
	
	  $d =  date('d',mktime(0,0,0,date('m'),date('d')-4,date('Y')));
	  $m =  date('m',mktime(0,0,0,date('m'),date('d')-4,date('Y')));	
	  $y =  date('Y',mktime(0,0,0,date('m'),date('d')-4,date('Y')));	 
	  
	  $return = array();
	  for($i=0;$i<5;$i++){
		  $return[] = date('Y-m-d',mktime(0,0,0,$m,($d+$i),$y));
		  } 
	  return $return;	  
	  }
  

  $dias = semana();
  $primeiro_dia = current($dias);
  $ultimo_dia = end($dias);
  
  $dis_total = $this->db
  ->where('di_data_cad >=',$primeiro_dia)
  ->where('di_data_cad <=',$ultimo_dia." 23:59:59")
  ->get('distribuidores')
  ->num_rows;

  foreach($dias as $d){
   $dia_atual = 'Domingo';
   	switch(date('N',strtotime($d))){
		case 1:$dia_atual= 'Segunda';break;
		case 2:$dia_atual= 'Terça';break;
		case 3:$dia_atual= 'Quarta';break;
		case 4:$dia_atual= 'Quinta';break;
		case 5:$dia_atual= 'Sexta';break;
		case 6:$dia_atual= 'Sábado';break;
		case 7:$dia_atual= 'Domingo';break;
		} 
	$dis = $this->db->select('count(*) todos')->like('di_data_cad',$d)->get('distribuidores')->row();	
	$crescimento = $dis->todos*100/$dis_total;
  ?>
  <td valign="bottom" width="60px">
  <div class="dia-grid">
  <?php if($dis->todos>0){?>
  <div class="dia" title="<?php echo $dis->todos?> distribuidor(es) cadastrado(s)" style="height:<?php echo $crescimento?>%">
    <?php echo $dis->todos?>
   <?php echo $crescimento?>%<br />
  </div>
  <?php }?>
   <?php echo $dia_atual?><br />
   <?php echo date('d/m',strtotime($d))?>
  </div>
  </td>
  <?php }?>
  
  
  
  </tr>       
</table>
    
    
    </td>
    
  </tr>
</table>

</div>


<?php $this->load->view('home/menu_baixo_view')?>