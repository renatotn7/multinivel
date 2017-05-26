<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	    <table class="form">
        
        
        
	      <tr>
	        <td><?php echo $entry_cielo_numero; ?></td>
	        <td><input type="text" name="cielo_numero" value="<?php echo $cielo_numero; ?>" size="50%" /></td>
	      </tr>
 
 
 	      <tr>
	        <td><?php echo $entry_cielo_chave; ?></td>
	        <td><input type="text" name="cielo_chave" value="<?php echo $cielo_chave; ?>" size="90%" /></td>
	      </tr>     	      

 	      <tr>
	        <td><?php echo $entry_cielo_endereco_base; ?></td>
	        <td><input type="text" name="cielo_endereco_base" value="<?php echo $cielo_endereco_base; ?>" size="50%" /></td>
	      </tr> 	      


 	      <tr>
	        <td><strong><?php echo $entry_cielo_bandeiras; ?></strong><br />
             <span style="font-size:11px;"><?php echo $entry_cielo_bandeiras_info; ?></span>
            </td>
	        <td>
             Visa: 
             <select name="cielo_usar_visa">
              <option <?php echo $cielo_usar_visa==0?"selected":""?> value="0">Não</option>
              <option <?php echo $cielo_usar_visa==1?"selected":""?> value="1">Sim</option>
             </select>
             &nbsp;&nbsp;&nbsp;
             
             Visa Electron: 
             <select name="cielo_usar_visa_electron">
              <option <?php echo $cielo_usar_visa_electron==0?"selected":""?> value="0">Não</option>
              <option <?php echo $cielo_usar_visa_electron==1?"selected":""?> value="1">Sim</option>
             </select> 
             &nbsp;&nbsp;&nbsp;           
 

              Master-Card: 
             <select name="cielo_usar_mastercard">
              <option <?php echo $cielo_usar_mastercard==0?"selected":""?> value="0">Não</option>
              <option <?php echo $cielo_usar_mastercard==1?"selected":""?> value="1">Sim</option>
             </select> 
             &nbsp;&nbsp;&nbsp;   
 
               Elo: 
             <select name="cielo_usar_elo">
              <option <?php echo $cielo_usar_elo==0?"selected":""?> value="0">Não</option>
              <option <?php echo $cielo_usar_elo==1?"selected":""?> value="1">Sim</option>
             </select> 
 
             
            </td>
	      </tr> 

 	      <tr>
	        <td><?php echo $entry_cielo_parcelas; ?></td>
	        <td>
            
            <select name="cielo_parcelas">
             <?php for($i=1;$i<60;$i++){?>
              <option <?php echo $cielo_parcelas==$i?"selected":""?> value="<?php echo $i?>" ><?php echo $i?> vezes</option>
             <?php }?>
            </select>
            </td>
	      </tr> 

	      <tr>
	        <td><strong><?php echo $status_compra?></strong><br />
            <span style="font-size:11px;"><?php echo $status_compra_info; ?></span>
             
            </td>
	        <td>
            <select name="cielo_order">
	          <?php foreach ($order_statuses as $order_status) { ?>
	          <?php if ($order_status['order_status_id'] == $cielo_order) { ?>
	          <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
	          <?php } else { ?>
	          <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
	          <?php } ?>
	          <?php } ?>
	        </select>
	         </td>
	      </tr>


 	      <tr>
	        <td><strong><?php echo $entry_cielo_juros; ?></strong><br />
            <span style="font-size:11px;"><?php echo $entry_cielo_juros_info; ?></span>
            </td>
	        <td><input type="text" name="cielo_juros" value="<?php echo $cielo_juros; ?>" size="10%" /></td>
	      </tr> 

 	      <tr>
	        <td><?php echo $entry_cielo_parcelamento; ?></td>
	        <td>
            <select name="cielo_parcelamento">
            <option <?php echo $cielo_parcelamento==2?"selected":""?> value="2">Loja</option>
            <option <?php echo $cielo_parcelamento==3?"selected":""?> value="3">Administradora</option>
            </select>
            </td>
	      </tr> 


 	      <tr>
	        <td><?php echo $entry_cielo_autorizacao; ?></td>
	        <td>
			<select name="cielo_autorizacao">
            <option <?php echo $cielo_autorizacao==3?"selected":""?> value="3">Autorizar Direto</option>
            <option <?php echo $cielo_autorizacao==2?"selected":""?> value="2">Autorizar transação autenticada e não-autenticada</option>
            <option <?php echo $cielo_autorizacao==0?"selected":""?> value="0">Somente autenticar a transação</option>
            <option <?php echo $cielo_autorizacao==1?"selected":""?> value="1">Autorizar transação somente se autenticada</option>
            </select>
            </td>
	      </tr> 

 	      <tr>
	        <td><?php echo $entry_cielo_captura; ?></td>
	        <td>
			<select name="cielo_captura">
            <option <?php echo $cielo_captura=='true'?"selected":""?> value="true">Sim</option>
            <option <?php echo $cielo_captura=='false'?"selected":""?> value="false">Não</option>
            </select>
            </td>
	      </tr> 


          <tr>
	        <td><?php echo $entry_cielo_status; ?></td>
	        <td>
              <select name="cielo_status">
              <?php if ($cielo_status) { ?>
	            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
	            <option value="0"><?php echo $text_disabled; ?></option>
	            <?php } else { ?>
	            <option value="1"><?php echo $text_enabled; ?></option>
	            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
	            <?php } ?>
            </select>
            </td>
	      </tr>

	      <tr>
	        <td><?php echo $entry_sort_order; ?></td>
	        <td><input type="text" name="cielo_sort_order" value="<?php echo $cielo_sort_order; ?>" size="1" /></td>
	      </tr>
	    </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 