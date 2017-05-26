<div class="box-content">
 <div class="box-content-header">Meu titulo</div>
 <div class="box-content-body">
 
 
 <?php 
 
$pontos_esquerda = $this->db->query("
 SELECT SUM(pd_pontos) pontos FROM `distribuidor_ligacao` 
 JOIN distribuidores ON di_id = `li_id_distribuidor`
JOIN pontos_distribuidor ON di_id = `pd_distribuidor`
 WHERE `li_no` =  ".get_user()->di_esquerda."
")->row();
$esquerda = $pontos_esquerda->pontos+0;

$pontos_direita = $this->db->query("
 SELECT SUM(pd_pontos) pontos FROM `distribuidor_ligacao` 
 JOIN distribuidores ON di_id = `li_id_distribuidor`
JOIN pontos_distribuidor ON di_id = `pd_distribuidor`
 WHERE `li_no` =  ".get_user()->di_direita."
")->row();

$direita = $pontos_direita->pontos+0;
		  
		  $pontos = 0;
		  
		  if($direita<=$esquerda){
			  $pontos = $direita;
			  }else{
				  $pontos = $esquerda;
				  } 
 
 ?>
 
 
 
 <div class="panel">
 
         <div class="info-home">
         <table style="width:300px;" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="37%" rowspan="2">Total de<br />
              Pontos</td>
            <td width="35%" align="center">Esquerda</td>
            <td width="28%" align="center">Direita</td>
          </tr>
          <tr>
            <td align="center"><?php echo $pontos_esquerda->pontos+0?></td>
            <td align="center"><?php echo $pontos_direita->pontos+0?></td>
          </tr>
        </table>
        </div>
 
 <table width="100%" class="table table-bordered table-hover" border="0" cellspacing="0" cellpadding="0">
 <thead>
  <tr>
    <th bgcolor="#f7f7f7">Qualificação</th>
    <th bgcolor="#f7f7f7">Pontos Necessários</th>
  </tr>
 </thead>
 
 <?php 
  $qs = $this->db->where('dq_id >',0)->get('distribuidor_qualificacao')->result();
  foreach($qs as $q){
 ?>
  <tr>
    <td><?php echo $q->dq_descricao?></td>
    <td>
     <?php 
	 if($pontos>$q->dq_pontos){
	  $atingida = $this->db
	  ->where('hi_distribuidor',get_user()->di_id)
	  ->where('hi_qualificacao',$q->dq_id)
	  ->get('historico_qualificacao')->row();
	 ?>
     <span class="label label-success">Atingida em <?php echo date('d/m/Y',strtotime($atingida->hi_data))?></span>
     <?php 
	 }else{
		 echo 'Faltam '.number_format($q->dq_pontos-$pontos,0,',','.').' pontos';
		 }
		 ?>
    </td>
  </tr>
 <?php }?> 
</table>

</div>
 
 
 </div>
 </div>