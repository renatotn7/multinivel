
<?php foreach ($products as $product) {?>               
<!--SPECIAL-->
    <div id="box-product-oferta">
        <div class="box-top">
            <div class="image-oferta"> 
             <?php if ($product['thumb']) {?>
                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a>
             <?php }?>                         
            </div>
            <div class="description-oferta"> 
              <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
<br> <br>              
<!--Valor do Produto em oferta-->
                <?php if ($product['price']) { ?>

                    <div class="price">

                      <?php if (!$product['special']) { ?>

                       <?php echo $product['price']; ?>

                      <?php } else {?>

                          <span class="price-old" style="color:#658020;font-family:'Open Sans', sans-serif;font-size: 14px;font-weight:bold;"><strong><strike><b>de: <?php echo $product['price']; ?></b></strike></strong></span><br> 
                          <span class="price-new" style="color:#fff;font-family:'Open Sans', sans-serif;font-size: 18px;font-weight:bold;">Por: <?php echo $product['special']; ?></span>

                      <?php }?>

                    </div>
                <?php } ?>
              <!--End-->                         
            </div>
            <a id="botao-comprar-oferta" onclick="addToCart('<?php echo $product['product_id']; ?>');" href="">                
            </a>
        </div>
        <div class="box-bottom"> 
            <h2><a href="">VER TODOS OS PRODUTOS EM OFERTA</a></h2>
        </div>                    
    </div>
<!--END SPECIAL-->
<?php }?>




