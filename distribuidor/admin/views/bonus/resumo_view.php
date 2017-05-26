<h4>Tabela de Bônus <?php echo date('m/Y')?></h4>
    <table width="100%" class="table-bonus" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="81%" bgcolor="#f3f3f3"><strong>Descrição</strong></td>
        <td width="19%" bgcolor="#f3f3f3"><strong>Valor</strong></td>
      </tr>
      <?php
	  for($tipo=1;$tipo<9;$tipo++){ 
	  $bonus = $this->db
	  ->where('bo_tipo',$tipo)
	  ->where('bo_distribuidor',get_user()->di_id)
	  ->where('bo_fatura',date('Y-m-01'))
	  ->get('bonus')->row();
	  ?>
      <tr>
        <td><?php echo $tipo.'º Geração'?></td>
        <td>R$ <?php echo isset($bonus->bo_valor)?number_format($bonus->bo_valor,2,',','.'):'0,0';?></td>
      </tr>
      <?php }?>
     
      <?php 
	  $bonus = $this->db
	  ->where('bo_tipo',9)
	  ->where('bo_distribuidor',get_user()->di_id)
	  ->where('bo_fatura',date('Y-m-01'))
	  ->get('bonus')->row();
	  
	  $bonus_total = $this->db
	  ->select('SUM(bo_valor) as total')
	  ->where('bo_distribuidor',get_user()->di_id)
	  ->where('bo_fatura',date('Y-m-01'))
	  ->get('bonus')->row();	  
	  ?>    
      
      <tr>
        <td>Bônus Indicação</td>
        <td>R$ <?php echo isset($bonus->total)?number_format($bonus->bo_valor,2,',','.'):'0,0';?></td>
      </tr>
      
      <tr class="total-bonus">
        <td>Total</td>
        <td>R$ <?php echo number_format($bonus_total->total+0,2,',','.')?></td>
      </tr>
      
    </table>