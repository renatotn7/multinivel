<?php echo $header; ?>
<!--CONTENT LOJA--> 
 <div class="content-loja">
   <div id="container" class="container-corpo">

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?><img src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>


<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
    
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post">
      
      <div class="cart-module">
  
   <!-- FRETE -->

    
    <div id="shipping" class="content" style="display: block;">
      <h2><?php echo $text_shipping_detail; ?></h2>
      <table>
        
        <tr>
          <td><span class="required">*</span> <?php echo $text_zone; ?></td>
          <td>
              <select name="estados">
                <option value="0"><?php echo $entry_select_zone; ?></option>
                <?php foreach ($estados as $estado) { ?>
                  <?php if ($estado['es_id'] == $estado_id) { ?>
                      <option value="<?php echo $estado['es_id']; ?>" selected="selected">
                          <?php echo $estado['es_nome']; ?>
                      </option>
                  <?php } else { ?>
                      <option value="<?php echo $estado['es_id']; ?>">
                          <?php echo $estado['es_nome']; ?>
                      </option>
                  <?php } ?>
                <?php } ?>
              </select>
            </td>
            <td></td>
        </tr>
        
        <!-- lista de cidades -->
        <tr>
            <td><span class="required">*</span> <?php echo $text_city; ?></td>
            <td>
                <select name="cidades">
                    <option value="0"><?php echo $entry_select_city; ?></option>
                </select>
            </td>
            <td></td>
        </tr>
        
        <!-- lista bairros -->
        <tr>
            <td><span class="required">*</span> <?php echo $text_neighborhood; ?></td>
            <td>
                <select name="bairros">
                    <option value="0"><?php echo $entry_select_neighborhood; ?></option>
                </select>
            </td>
            <td></td>
        </tr>
        
        <!-- lista consultor -->
        <tr>
            <td><span class="required">*</span> <?php echo $text_consultant; ?></td>
            <td>
                <select name="consultor">
                    <option value="0"><?php echo $entry_select_consultant; ?></option>
                </select>
                <div id="nomeConsultor">
                </div>
            </td>
            <td><input type="button" value="<?php echo $button_confirm; ?>" id="button-quote" class="button" /></td>
        </tr>
            
      </table>
      
    </div>
  </div>
      
  </form>
  
   <?php echo $content_bottom; ?>
</div>
<script type="text/javascript">
<!--    
//buscando as cidades
$('select[name=\'estados\']').bind('change', function() {
	$.ajax({
		url: '<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=checkout/consultant/cidades&estado_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'estados\']').after('<span class="wait">&nbsp;<img src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			$('.wait').remove();
                        if (json != '') {
                            html = '<option value="0" selected="selected"><?php echo $entry_select_city_ok; ?></option>';
                            for (i = 0; i < json.length; i++) {
                                html += '<option value="' + json[i]["di_cidade"] + '">' + json[i]["ci_nome"] + '</option>';
                            }
                        }else{
                            html += '<option value="0" selected="selected"><?php echo $entry_select_city; ?></option>';
                        }
			
			$('select[name=\'cidades\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//buscando os bairros
$('select[name=\'cidades\']').bind('change', function() {
	$.ajax({
		url: '<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=checkout/consultant/bairros&cidade_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'cidades\']').after('<span class="wait">&nbsp;<img src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			$('.wait').remove();
                        if (json != '') {
                            html = '<option value="0" selected="selected"><?php echo $entry_select_neighborhood_ok; ?></option>';
                            for (i = 0; i < json.length; i++) {
                                html += '<option value="'+json[i]["di_bairro"]+'">'+json[i]["di_bairro"]+'</option>';
                            }
                        }else{
                            html += '<option value="0" selected="selected"><?php echo $entry_select_neighborhood; ?></option>';
                        }
			
			$('select[name=\'bairros\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

//buscando os consultores
$('select[name=\'bairros\']').bind('change', function() {
        var id_estado = $('select[name=\'estados\']').val();
	$.ajax({
		url: '<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=checkout/consultant/consultores&bairro_nome=' + this.value + '&estado_id=' + id_estado,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'bairros\']').after('<span class="wait">&nbsp;<img src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			$('.wait').remove();
                        if (json != '') {
                            html = '';
                            html2 = '';
                            for (i = 0; i < json.length; i++) {
                                html += '<option value="'+json[i]["di_id"]+'">'+json[i]["di_usuario"]+'</option>';
                                html2 += '<input type="hidden" name="nome_consultor['+json[i]["di_id"]+']" value="'+json[i]["di_usuario"]+'"/>';
                            }
                        }else{
                            html += '<option value="0" selected="selected"><?php echo $entry_select_consultant; ?></option>';
                        }
			
			$('select[name=\'consultor\']').html(html);
                        $('#nomeConsultor').html(html2);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});


$('#button-quote').bind('click',function (){
    /* $('form[name=\'formConfirma\']').submit(); */
    $('#content > form').submit();
});
//-->
</script>
<!-- $('select[name=\'estados\']').trigger('change'); -->
<br>
</div>
<!--END CONTENT COPO-->
<br>
</div>
<!--END CONTENT LOJA--> 
<?php echo $footer; ?>