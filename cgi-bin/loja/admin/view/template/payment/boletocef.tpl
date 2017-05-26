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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">* </span><?php echo $entry_name_key; ?><br /></td>
            <td><input type="text" name="boletocef_name_key" value="<?php echo $boletocef_name_key; ?>" />
              <?php if ($error_name_key) { ?>
              <span class="error"><?php echo $error_name_key; ?></span>
              <?php } ?></td>
          </tr>	
	        <tr>
	            <td><span class="required">* </span><?php echo $entry_cnpj_key; ?><br /></td>
	            <td><input type="text" name="boletocef_cnpj_key" value="<?php echo $boletocef_cnpj_key; ?>" />
	              <?php if ($error_cnpj_key) { ?>
	              <span class="error"><?php echo $error_cnpj_key; ?></span>
	              <?php } ?></td>
	          </tr>	
	       
	       <tr>
	            <td><span class="required">* </span><?php echo $entry_adress_key; ?><br /></td>
	            <td><input type="text" name="boletocef_adress_key" value="<?php echo $boletocef_adress_key; ?>" />
	              <?php if ($error_adress_key) { ?>
	              <span class="error"><?php echo $error_adress_key; ?></span>
	              <?php } ?></td>
	          </tr>
	          
	             <tr>
	            <td><span class="required">* </span><?php echo $entry_uf_key; ?><br /></td>
	            <td><input type="text" name="boletocef_uf_key" value="<?php echo $boletocef_uf_key; ?>" />
	              <?php if ($error_uf_key) { ?>
	              <span class="error"><?php echo $error_uf_key; ?></span>
	              <?php } ?></td>
	          </tr>
	       	
          <tr>
            <td><span class="required">*</span> Num da agencia, sem digito<br /></td>
            <td><input type="text" name="boletocef_num_agencia_key" value="<?php echo $boletocef_num_agencia_key; ?>" />
              <?php if ($error_num_agencia_key) { ?>
              <span class="error"><?php echo $error_num_agencia_key; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> Num da conta, sem digito<br /></td>
            <td><input type="text" name="boletocef_num_conta_key" value="<?php echo $boletocef_num_conta_key; ?>" />
              
  			  <?php if ($error_num_conta_key) { ?>
              <span class="error"><?php echo $error_num_conta_key; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> Digito do Num da conta<br /></td>
            <td><input type="text" name="boletocef_num_conta_dig_key" value="<?php echo $boletocef_num_conta_dig_key; ?>" />
              
  			  <?php if ($error_num_conta_dig_key) { ?>
              <span class="error"><?php echo $error_num_conta_dig_key; ?></span>
              <?php } ?></td>
          </tr>
          
          <tr>
            <td><span class="required">*</span> Código Cedente<br /></td>
            <td><input type="text" name="boletocef_cod_ced_key" value="<?php echo $boletocef_cod_ced_key; ?>" />
              <?php if ($error_cod_ced_key) { ?>
              <span class="error"><?php echo $error_cod_ced_key; ?></span>
              <?php } ?></td>
          </tr>
          
          <tr>
            <td><span class="required">*</span> Dias de prazo para Pagamento<br /></td>
            <td><input type="text" name="boletocef_dias_pag_key" value="<?php echo $boletocef_dias_pag_key; ?>" />
              <?php if ($error_dias_pag_key) { ?>
              <span class="error"><?php echo $error_dias_pag_key; ?></span>
              <?php } ?></td>
          </tr>
           <tr>
            <td><span class="required">*</span> Digito Verificador<br /></td>
            <td><input type="text" name="dig_ret" value="<?php echo $dig_ret; ?>" />
              <?php if ($error_dig_ret) { ?>
              <span class="error"><?php echo $error_dig_ret; ?></span>
              <?php } ?></td>
          </tr>
          
           <tr>
            <td><span class=""></span> Taxa Boleto<br /></td>
            <td><input type="text" name="boletocef_value_taxa" value="<?php echo $boletocef_value_taxa; ?>" /> Ex.: 2.50
            </td>
          </tr>
          
          
          <tr>
            <td><span class="required">*</span> Url da Logo <br /></td>
            <td><input type="text" name="logo_url" value="<?php echo $logo_url; ?>" />
              <?php if ($error_logo_url) { ?>
              <span class="error"><?php echo $error_logo_url; ?></span>
              <?php } ?></td>
          </tr>
                 
          <tr>
            <td><?php echo $entry_test; ?></td>
            <td><?php if ($boletocef_test) { ?>
              <input type="radio" name="boletocef_test" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="boletocef_test" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
              <?php if (!$boletocef_test) { ?>
              <input type="radio" name="boletocef_test" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="boletocef_test" value="0" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
        <tr>
            <td><?php echo $entry_completed_status; ?></td>
            <td><select name="boletocef_completed_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $boletocef_completed_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
         </tr>
         <tr>
            <td><?php echo $entry_failed_status; ?></td>
            <td><select name="boletocef_failed_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $boletocef_failed_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_pending_status; ?></td>
            <td><select name="boletocef_pending_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $boletocef_pending_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="boletocef_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $boletocef_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="boletocef_status">
                <?php if ($boletocef_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="boletocef_sort_order" value="<?php echo $boletocef_sort_order; ?>" size="1" /></td>
          </tr>
          
          <tr>
            <td><?php echo $text_boletocef_instrucao1; ?></td>
            <td><input type="text" name="boletocef_instrucao1" value="<?php echo $boletocef_instrucao1; ?>" /></td>
          </tr>
          
          <tr>
            <td><?php echo $text_boletocef_instrucao2; ?></td>
            <td><input type="text" name="boletocef_instrucao2" value="<?php echo $boletocef_instrucao2; ?>" /></td>
          </tr>
          
          <tr>
            <td><?php echo $text_boletocef_instrucao3; ?></td>
            <td><input type="text" name="boletocef_instrucao3" value="<?php echo $boletocef_instrucao3; ?>" /></td>
          </tr>
          
           <tr>
            <td><?php echo $text_boletocef_demo1; ?></td>
            <td><input type="text" name="boletocef_demo1" value="<?php echo $boletocef_demo1; ?>" /></td>
          </tr>
          
           <tr>
            <td><?php echo $text_boletocef_demo2; ?></td>
            <td><input type="text" name="boletocef_demo2" value="<?php echo $boletocef_demo2; ?>" /></td>
          </tr>
          
           <tr>
            <td><?php echo $text_boletocef_demo3; ?></td>
            <td><input type="text" name="boletocef_demo3" value="<?php echo $boletocef_demo3; ?>" /></td>
          </tr>
          
          
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 