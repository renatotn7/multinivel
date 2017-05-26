<?php echo $header; ?>





<!--CONTENT LOJA--> 
 <div class="content-loja">  
   <div id="container" class="container-corpo">
       <div style="padding:10px;">
            <h1>Franquia</h1>
    
              <div class="product-grid">

    <?php foreach ($products as $product) { ?>

    <div style="width: 209px">
   
      <?php if ($product['thumb']) { ?>

      <div class="image"><a href="<?php echo $product['href']; ?>">
              <img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>

      <?php } ?>

      <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>

      <?php if ($product['price']) { ?>

      <div class="price">

        <?php if (!$product['special']) { ?>

        <?php echo $product['price']; ?>

        <?php } else { ?>

        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>

        <?php } ?>

        <?php if ($product['tax']) { ?>

        <br />

        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>

        <?php } ?>

      </div>

      <?php } ?>

      <?php if ($product['rating']) { ?>

      <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>

      <?php } ?>

      <div class="cart">
        <?php if($product['stock'] > 0){?>
        <input type="button" value="Comprar" onclick="addToCartPlano('<?php echo $product['product_id']; ?>',1);" class="button" />
        <?php }else{?>
          <strong style="padding: 4px 10px 4px 33px;display:block;width:75%;color:#9D3133;" class="attention">EM BREVE ! </strong>
        <?php }?>
      </div>

   
    </div>

    <?php } ?>

  </div>
            
       </div>
   
</div>
</div>
<!--END CONTENT LOJA--> 
<script type="text/javascript">
 function addToCartPlano(product_id, quantity) {
	quantity = typeof(quantity) != 'undefined' ? quantity : 1;

	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, .information, .error').remove();
			
			if (json['redirect']) {
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
}
</script>

<?php echo $footer; ?>