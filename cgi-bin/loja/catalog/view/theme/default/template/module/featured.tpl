
   <div class="mais-vendidos">

    <img style="margin-bottom: 10px;" src="<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/titulo-destaques.jpg'; ?>" />
  <div class="box-content">

     
    <?php foreach ($products as $product) { ?>
    <div class="box-product-mais-vendidos">
        
        <!-- box-product-mais-vendidos -->

        <?php if ($product['thumb']) { ?>
        <div class="div-image">
          <div class="image"><a href="<?php echo $product['href']; ?>"><img   src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        </div>
        <?php } ?>
         
        
         
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        
        <?php //if ($product['rating']) { ?>

          <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>

        <?php //} ?>

        <?php if ($product['price']) { ?>
        
        <div class="price" style="font-family:Arial;font-weight:bold;color:#8BAF2E;font-size:18px;">
            
          <?php if (!$product['special']) { ?>

          <span style="color:#999999;font-size: 12px;font-style: normal; font-family: 'Open Sans', sans-serif;">por :</span> <?php echo $product['price']; ?>

          <?php } else { ?>

          <span class="price-old" style="color:#888888; font-size: 12px;
text-decoration: line-through;"> de <?php echo $product['price']; ?></span> <span class="price-new"><br /><strong style="color:#8BAF2E !important;">Por <?php echo $product['special']; ?></strong></span>


          <?php } ?>

        </div>

        <?php } ?>

        <?php if ($product['rating']) { ?>

        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
        
        
        
        <?php } ?>

        <a style="display: none;" onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button comprar"><?php echo $button_cart; ?>
        </a>
    </div>
      
       

      <?php } ?>


  </div>

</div>