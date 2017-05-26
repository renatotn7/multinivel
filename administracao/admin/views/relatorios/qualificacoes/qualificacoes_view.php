<?php 
$mes_c = get_parameter('mes')?get_parameter('mes'):date('m');
$ano_c = get_parameter('ano')?get_parameter('ano'):date('Y');
?>

<div class="box-content min-height">
 <div class="box-content-header">Relatório de qualificação</div>
 <div class="box-content-body">
 
 <form name="form1" method="get" action="">
   <select name="mes" style="margin:0; width:80px;">
    <?php for($mes=1;$mes <= 12;$mes++){?>
     <option <?php echo $mes==$mes_c?'selected':''?> ><?php echo $mes<=9?'0'.$mes:$mes?></option>
    <?php }?>
   </select>
   <select name="ano" style="margin:0; width:80px;">
       <?php for($ano=date('Y');$ano >= 2013; $ano--){?>
     <option <?php echo $ano==$ano_c?'selected':''?> ><?php echo $ano<=9?'0'.$ano:$ano?></option>
    <?php }?>
   </select>
   <input type="submit" class="btn btn-info" value="Ok">
 </form>
<table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">

<?php 
$qualificacao = $this->db->where('dq_id >',0)->get('distribuidor_qualificacao')->result();
foreach($qualificacao as $q){
?>
  <tr>
    <td bgcolor="#f7f7f7" style="font-size:22px; color:#06C;"><?php echo $q->dq_descricao?></td>
  </tr>
  
    <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <?php 
	   $dis = $this->db
	   ->like('hi_data',$ano_c."-".$mes_c."-")
	   ->join('distribuidores','di_id=hi_distribuidor')
	   ->where('hi_qualificacao',$q->dq_id)
	   ->get('historico_qualificacao')->result();
	   
	   foreach($dis as $d){
	  ?>
      <tr>
        <td><?php echo $d->di_nome?>(<?php echo $d->di_usuario?>)</td>
        <td width="150px"><?php echo date('d/m/Y',strtotime($d->hi_data))?></td>
      </tr>
      <?php }?> 
    </table>
       
    </td>
  </tr>
  
<?php }?>

</table>

 
 </div>
 </div>