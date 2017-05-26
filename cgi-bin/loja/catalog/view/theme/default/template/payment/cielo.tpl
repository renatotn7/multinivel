<?php 

function parcela($C,$J,$N){
	
	if($J == "0" || $J==0){
	 return $C/$N;
	}else{ 
		
		$J1 = $J/100;
		$J2 = $J/100 + 1;
		$J2 = 1/$J2;
			$Potencia = $J2;
			$PotenciaBase = $J2;
		for($contador=1;$contador< $N; $contador++){
			$Potencia = $Potencia * $PotenciaBase;
		}
			$J2 = 1-$Potencia;
			$J1 = $J1/$J2;
			return $C*$J1;
	}
			
		
	}
?>


<div class="buttons">
<h6 style="padding:2px; font-size:20px; color:#069; margin:0;">Pagamento com cielo</h6>

<style>
.pgto{
	padding:0;
	margin:0;
	}
.pgto li{list-style:none;}	
</style>
<strong>Escolha o cartão de crédito:</strong>
<form style="background:#fff; padding:10px; min-height:400px; font-family:arial;" method="post" action="<?php echo $action?>">
<script type="text/javascript">
 $(function(){
	 $($(".pgto input")[1]).attr('checked',true);
	 });
</script>
<table width="100%" align="center" border="0" id="cielo" cellspacing="3" cellpadding="6">
  <tr>
<!--VISA ELECTRON-->
<?php if($cielo_usar_visa_electron == 1){?> 
   <td valign="top">
     <p><img  width="50" src="image/cielo/visa_electron.gif" /></p>
     <ul class="pgto">
      <li> <span><input type="radio" name="pg" value="cielo/visa/A"> À Vista <strong>R$ <?php echo number_format($dados_compra['total'],2,",",".")?></strong></span></li>
     </ul>
    </td>
    <?php }?>
 <!--VISA ELECTRON -->
    
<!--VISA -->
<?php if($cielo_usar_visa == 1){?>    
    
    <td valign="top">
    <p><img style="position:relative;top:7px;right:9px;" src="image/cielo/visa.png" /></p>  
    <ul class="pgto">
     <?php 
     for($i=1;$i<=$parcelas;$i++){
      ?>
      <li><input type="radio" name="pg" value="cielo/visa/<?php echo $i?>"> <?php echo $i?>x de R$ <?php echo number_format(parcela($dados_compra['total'],$cielo_juros,$i),2,",",".")?></li> 
     <?php
     }
     ?>
    </ul>
   </td>
   <?php }?>
  <!-- VISA--> 
   
<!--MASTER -->
<?php if($cielo_usar_mastercard == 1){?>
    <td valign="top">
    <p><img style="position:relative;top:7px;right:9px;" src="image/cielo/master.png" /></p>  
    <ul class="pgto">
     <?php 
     for($i=1;$i<=$parcelas;$i++){
      ?>
      <li><input type="radio" name="pg" value="cielo/mastercard/<?php echo $i?>"> <?php echo $i?>x de R$ <?php echo number_format(parcela($dados_compra['total'],$cielo_juros,$i),2,",",".")?></li> 
     <?php
     }
     ?>
    </ul>
   </td>
   <?php }?>
<!--MASTER -->

<!--ELO -->
<?php if($cielo_usar_elo == 1){?>
    <td valign="top">
    <p><img style="position:relative;top:7px;right:9px;" src="image/cielo/elo.png" /></p>  
    <ul class="pgto">
     <?php 
     for($i=1;$i<=$parcelas;$i++){
      ?>
      <li><input type="radio" name="pg" value="cielo/elo/<?php echo $i?>"> <?php echo $i?>x de R$ <?php echo number_format(parcela($dados_compra['total'],$cielo_juros,$i),2,",",".")?></li> 
     <?php
     }
     ?>
    </ul>
   </td>
<?php }?>
<!--ELO -->

  </tr>
</table>
<div class="buttons">
  <div align="right">
  <input type="submit" id="button-confirm" class="button"  value="<?php echo $button_continue; ?>"/>
 </div>
</div>
</form>
</div>

