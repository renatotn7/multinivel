<?php echo $header; ?>




<script src="catalog/view/javascript/jqzoom_ev-2.3/js/jquery.jqzoom-core.js" type="text/javascript"></script>

<link rel="stylesheet" href="catalog/view/javascript/jqzoom_ev-2.3/css/jquery.jqzoom.css" type="text/css">

<link rel="stylesheet" href="catalog/view/javascript/fancyapps/source/jquery.fancybox.css" type="text/css">

<script src="catalog/view/javascript/fancyapps/source/jquery.fancybox.js" type="text/javascript"></script>

<script type="text/javascript">

	$(document).ready(function() {

		$('.jqzoom').jqzoom({

				zoomType: 'standard',

				preloadImages: false

			});
			
			$('.fancybox').fancybox();

			

		$("#expandir").click(function(){

			

			$("body").append('<div id="modal-objeto-fundo"></div>');

			$("#modal-objeto").css('display','block');

			  var w = 700;

			  var h = 500;

			  var x = (screen.width) ? (screen.width-w)/2 : 0;

			  $("#modal-objeto").css('left',x);

			  $("#modal-objeto").animate({

				  height:h,

				  width:w

				  });  

			});	

	  $("#modal-objeto-fundo").live('click',function(){

		  $(this).remove();

		  $("#modal-objeto").animate({

				  height:0,

				  width:0

				  });

		  $("#modal-objeto").css('display','none');

		  });		

	

	 $("#modal-img-miniatura img").click(function(){

		  $("#modal-recebe-img").attr('src',$(this).attr('alt'));

		 });

				

	});

</script>

<style>
#button-calc{
  border-bottom-left-radius:4px;
  border-bottom-right-radius:4px;
  border-top-left-radius:4px;
  border-top-right-radius:4px;
  box-shadow:#DDDDDD 0 2px 2px;	
}
</style>

<div id="modal-objeto">

<span><?php echo $heading_title; ?></span>

 <table width="100%" border="0" cellspacing="0"  cellpadding="10">
  
  <tr>

    <td>

    <img src="<?php echo $popup; ?>" height="450px" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="modal-recebe-img" />

    </td>

    <td valign="top">

    <div id="modal-img-miniatura">

     <?php foreach ($images as $image) { ?>

        <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $image['popup']; ?>" />

        <?php } ?>

    </div>    

    </td>

  </tr>

</table>



</div>





    <table width="98.5%" align="center" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td>

<div id="content" class="content-center"><?php echo $content_top; ?>

  <div class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>

    <?php } ?>

  </div>

  

  <!-- Comentarios -->

   <?php if ($review_status) { ?>

   <div id="comentarios"><img src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;

   <a onclick="$('a[href=\'#tab-review\']').trigger('click');scroll_to('#tab-review');"><?php echo $reviews; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');scroll_to('#tab-review');"><?php echo $text_write; ?></a></div>

  <?php }?>

  

  

  <div class="product-info">

    <?php if ($thumb || $images) { ?>

    <div class="left">

      <?php if ($thumb) { ?>

      <div class="image">

      <a href="<?php echo $popup; ?>" id="expandir" class="jqzoom" rel='gal1'  title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>

      <?php } ?>

      <?php if ($images) { ?>

      <div class="image-additional">

        <?php foreach ($images as $image) { ?>

        <a href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo $image['normal']; ?>',largeimage: '<?php echo $image['popup']; ?>'}"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>

        <?php } ?>

      </div>

      <?php } ?>

    </div>

    <?php } ?>

    <div class="right">

      <div class="description">

      

       <h1><?php echo $heading_title; ?></h1>

      

      

        <span style="padding-left:0;">(<?php echo $text_model; ?></span> <?php echo $model; ?>)

        <?php if ($reward) { ?>

        <span>(<?php echo $text_reward; ?></span> <?php echo $reward; ?>)

        <?php } ?>

        

        <span>(<?php echo $text_stock; ?></span> <?php echo $stock; ?>)

        

         <?php if ($manufacturer) { ?>

        <span>Mais produtos </span> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a>

        <?php } ?>

        

        </div>

        

      <?php if ($price) { ?>

      <div class="price"> 

        <?php if (!$special) { ?>

        Por <?php echo $price; ?>

        <?php } else { ?>

        <span class="price-old">De <?php echo $price; ?></span> <span class="price-new"> Por <?php echo $special; ?></span>

        <?php } ?>

        <br />

        <?php if ($tax) { ?>

        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span><br />

        <?php } ?>

        <?php if ($points) { ?>

        <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />

        <?php } ?>

        <?php if ($discounts) { ?>

        <br />

        <div class="discount">

          <?php foreach ($discounts as $discount) { ?>

          <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />

          <?php } ?>

        </div>

        <?php } ?>

      </div>

      <?php } ?>

      <?php if ($options) { ?>

      <div class="options">

        <h2><?php echo $text_option; ?></h2>

        <br />

        <?php foreach ($options as $option) { ?>

        <?php if ($option['type'] == 'select') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <select name="option[<?php echo $option['product_option_id']; ?>]">

            <option value=""><?php echo $text_select; ?></option>

            <?php foreach ($option['option_value'] as $option_value) { ?>

            <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

            <?php if ($option_value['price']) { ?>

            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

            <?php } ?>

            </option>

            <?php } ?>

          </select>

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'radio') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <?php foreach ($option['option_value'] as $option_value) { ?>

          <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />

          <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

            <?php if ($option_value['price']) { ?>

            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

            <?php } ?>

          </label>

          <br />

          <?php } ?>

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'checkbox') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <?php foreach ($option['option_value'] as $option_value) { ?>

          <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />

          <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

            <?php if ($option_value['price']) { ?>

            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

            <?php } ?>

          </label>

          <br />

          <?php } ?>

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'image') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <table class="option-image">

            <?php foreach ($option['option_value'] as $option_value) { ?>

            <tr>

              <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>

              <td>
              
               <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>">
                
                <a class="fancybox" href="<?php echo $option_value['image']; ?>" id="expandir" >
                   <img src="<?php echo $option_value['image']; ?>" height="50px" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" />
                </a>
                
               </label></td>

              <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

                  <?php if ($option_value['price']) { ?>

                  (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                  <?php } ?>

                </label></td>

            </tr>

            <?php } ?>

          </table>

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'text') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" class="<?php echo $option['option_value']!=''?'max_value':'data_largura'?>" id="<?php echo $option['name']; ?>" alt="<?php echo $option['option_value']; ?>" /> 
          
          <?php echo $option['option_value']!=''?'Máximo permitido de '.$option['option_value'].' metros':''?>

        </div>

        <br />
         
        <?php } ?>

         
        
        <?php if ($option['type'] == 'textarea') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'file') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">

          <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'date') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'datetime') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />

        </div>

        <br />

        <?php } ?>

        <?php if ($option['type'] == 'time') { ?>

        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

          <?php if ($option['required']) { ?>

          <span class="required">*</span>

          <?php } ?>

          <b><?php echo $option['name']; ?>:</b><br />

          <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />

        </div>

        <br />

        <?php } ?>

        <?php } ?>

      </div>

      <?php } ?>

      
      <div class="cart">

        <div>Qtd em m² :

          <input type="text" id="total_m2" name="quantity" size="2" value="<?php echo $minimum; ?>" />

          <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />

          &nbsp;
          
          <button id="button-calc" style="padding:5px;background:#1F313D !important;color:#fff;border:none;width:100px;cursor:pointer;">Simular </button>
          &nbsp;<input type="button" value="<?php echo $button_cart; ?>" id="button-cart" class="button" />
          
        </div>

     



        <?php if ($minimum > 1) { ?>

        <div class="minimum"><?php echo $text_minimum; ?></div>

        <?php } ?>

      </div>

     

     <div id="btn-desejo-comparar"> 

    <!-- LISTA DE DESEJO -->

    <a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>

     | <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>

    </div>

      

      <?php if ($review_status) { ?>

      <div class="review">

       <div class="share"><!-- AddThis Button BEGIN -->

          <div class="addthis_default_style"> 

          

          <a class="addthis_button_email"></a><a class="addthis_button_print"></a> 

          <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a>

          

          

   

          </div>

          <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script> 

          <!-- AddThis Button END --> 

        </div>

      </div>

      <?php } ?>

    </div>

  </div>

  <div id="tabs" class="htabs">

  <a href="#tab-description"><?php echo $tab_description; ?></a>

   

    <?php if ($attribute_groups) { ?>

    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>

    <?php } ?>

    <?php if ($review_status) { ?>

    <a href="#tab-review"><?php echo $tab_review; ?></a>

    <?php } ?>

    <?php if ($products) { ?>

    <a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a>

    <?php } ?>

    

  </div>

  <div id="tab-description" class="tab-content"><?php echo $description; ?></div>

  <?php if ($attribute_groups) { ?>

  <div id="tab-attribute" class="tab-content">

    <table class="attribute">

      <?php foreach ($attribute_groups as $attribute_group) { ?>

      <thead>

        <tr>

          <td colspan="2"><?php echo $attribute_group['name']; ?></td>

        </tr>

      </thead>

      <tbody>

        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>

        <tr>

          <td><?php echo $attribute['name']; ?></td>

          <td><?php echo $attribute['text']; ?></td>

        </tr>

        <?php } ?>

      </tbody>

      <?php } ?>

    </table>

  </div>

  <?php } ?>

  <?php if ($review_status) { ?>

  <div id="tab-review" class="tab-content">

    <div id="review"></div>

    <h2 id="review-title"><?php echo $text_write; ?></h2>

    <b><?php echo $entry_name; ?></b><br />

    <input type="text" style="width: 50%;" name="name" value="" />

    <br />

    <br />

    <b><?php echo $entry_review; ?></b><br />

    <textarea name="text" cols="40" rows="4" style="width: 50%;"></textarea><br />

    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />

    <br />

    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;

    <input type="radio" name="rating" value="1" />

    &nbsp;

    <input type="radio" name="rating" value="2" />

    &nbsp;

    <input type="radio" name="rating" value="3" />

    &nbsp;

    <input type="radio" name="rating" value="4" />

    &nbsp;

    <input type="radio" name="rating" value="5" />

    &nbsp;<span><?php echo $entry_good; ?></span><br />

    <br />

    <b><?php echo $entry_captcha; ?></b><br />

    

    <table width="100px" border="0" cellspacing="0" cellpadding="4">

  <tr>

    <td><input type="text" style="padding:8px;" name="captcha" value="" /></td>

    <td><img src="index.php?route=product/product/captcha" alt="" id="captcha" /></td>

  </tr>

</table>



   

    <br />

      <div class="left"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>



  </div>

  <?php } ?>

  <?php if ($products) { ?>

  <div id="tab-related" class="tab-content">

    <div class="box-product">

      <?php foreach ($products as $product) { ?>

      <div>

        <?php if ($product['thumb']) { ?>

        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>

        <?php } ?>

        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>

        <?php if ($product['price']) { ?>

        <div class="price">

          <?php if (!$product['special']) { ?>

          <?php echo $product['price']; ?>

          <?php } else { ?>

          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>

          <?php } ?>

        </div>

        <?php } ?>

        <?php if ($product['rating']) { ?>

        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>

        <?php } ?>

        <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><?php echo $button_cart; ?></a></div>

      <?php } ?>

    </div>

  </div>

  <?php } ?>

  <?php if ($tags) { ?>

  <div class="tags"><b><?php echo $text_tags; ?></b>

    <?php for ($i = 0; $i < count($tags); $i++) { ?>

    <?php if ($i < (count($tags) - 1)) { ?>

    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,

    <?php } else { ?>

    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>

    <?php } ?>

    <?php } ?>

  </div>

  <?php } ?>

  <?php echo $content_bottom; ?></div>

</td>
</tr>
</table>





<script type="text/javascript"><!--

 $('#button-calc').bind('click',function(){
	 
	 var altura  = $(".max_value").val();
	 var largura = $(".data_largura").val();
	 var m2 = 0;
	 
	 m2 = altura*largura;
	 
	 document.getElementById('total_m2').value = m2;

	});

$('#button-cart').bind('click', function(){
   
   var error_max_permitido = false;
   var max_value = $(".max_value").length;
   if(max_value>0){
	   
	   $.each($(".max_value"),function(index,input_txt){
		    var max_permitido = $(input_txt).attr('alt');
			var value_input = parseFloat($(input_txt).val());
		    if(value_input>max_permitido){
				alert("Não é permitido mais de "+max_permitido+" metros no campo "+$(input_txt).attr('id'));
				error_max_permitido = true;
				}
		   });
	   }
     
	 if(error_max_permitido){
		  return false;
		 }
    
	$.ajax({

		url: 'index.php?route=checkout/cart/add',

		type: 'post',

		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),

		dataType: 'json',

		success: function(json) {

			$('.success, .warning, .attention, information, .error').remove();

			

			if (json['error']) {

				if (json['error']['option']) {

					for (i in json['error']['option']) {

						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');

					}

				}

			} 

			

			if (json['redirect'] && !json['error']) {	

			 location = json['redirect'];

			}

			if (json['success']) {

				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

					

				$('.success').fadeIn('slow');

					

				$('#cart-total').html(json['total']);

				

				$('html, body').animate({ scrollTop: 0 }, 'slow'); 

			}	

		}

	});

});







//--></script>

<?php if ($options) { ?>

<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>

<?php foreach ($options as $option) { ?>

<?php if ($option['type'] == 'file') { ?>

<script type="text/javascript"><!--

new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {

	action: 'index.php?route=product/product/upload',

	name: 'file',

	autoSubmit: true,

	responseType: 'json',

	onSubmit: function(file, extension) {

		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');

		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);

	},

	onComplete: function(file, json) {

		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);

		

		$('.error').remove();

		

		if (json['success']) {

			alert(json['success']);

			

			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);

		}

		

		if (json['error']) {

			$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');

		}

		

		$('.loading').remove();	

	}

});

//--></script>

<?php } ?>

<?php } ?>

<?php } ?>

<script type="text/javascript"><!--

$('#review .pagination a').live('click', function() {

	$('#review').fadeOut('slow');

		

	$('#review').load(this.href);

	

	$('#review').fadeIn('slow');

	

	return false;

});			



$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');



$('#button-review').bind('click', function() {

	$.ajax({

		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',

		type: 'post',

		dataType: 'json',

		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),

		beforeSend: function() {

			$('.success, .warning').remove();

			$('#button-review').attr('disabled', true);

			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');

		},

		complete: function() {

			$('#button-review').attr('disabled', false);

			$('.attention').remove();

		},

		success: function(data) {

			if (data['error']) {

				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');

			}

			

			if (data['success']) {

				$('#review-title').after('<div class="success">' + data['success'] + '</div>');

								

				$('input[name=\'name\']').val('');

				$('textarea[name=\'text\']').val('');

				$('input[name=\'rating\']:checked').attr('checked', '');

				$('input[name=\'captcha\']').val('');

			}

		}

	});

});



/*SCROLLL*/

function scroll_to(div){

$('html, body').animate({

scrollTop: $(div).offset().top

},2000);

}







//--></script> 

<script type="text/javascript"><!--

$('#tabs a').tabs();

//--></script> 

<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 

<script type="text/javascript"><!--

if ($.browser.msie && $.browser.version == 6) {

	$('.date, .datetime, .time').bgIframe();

}



$('.date').datepicker({dateFormat: 'yy-mm-dd'});

$('.datetime').datetimepicker({

	dateFormat: 'yy-mm-dd',

	timeFormat: 'h:m'

});

$('.time').timepicker({timeFormat: 'h:m'});

//--></script> 

<?php echo $footer; ?>