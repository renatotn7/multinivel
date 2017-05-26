<?php echo $header; ?>





<!--CONTENT LOJA--> 
 <div class="content-loja">  
   <div id="container" class="container-corpo">
       <div style="padding:10px;">
            <h1>Compre seu plano</h1>
    
              <div class="product-grid">

    <?php foreach ($products as $product) { ?>

    <div>
   
      <?php if ($product['thumb']) { ?>

      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>

      <?php } ?>

      <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>

      <div class="description"><?php echo $product['description']; ?></div>
     

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

        <input type="button" value="Comprar" onclick="addToCart('<?php echo $product['product_id']; ?>',1);" class="button" />

      </div>

   
    </div>

    <?php } ?>

  </div>
            
       </div>
   
</div>
</div>
<!--END CONTENT LOJA--> 
        

<?php echo $footer; ?>