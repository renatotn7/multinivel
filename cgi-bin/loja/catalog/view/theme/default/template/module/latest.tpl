<div class="box radios" style="border:1px solid #0064A9;">

  <div class="box-heading radios" style="font-family:Dosis;font-size:22px;color:#fff;font-weight:600;text-align:center;background:#0A69AF !important;"><?php echo $heading_title; ?></div>

  <div class="box-content" >

    <div class="box-product" style="overflow:hidden !important;">

      <?php foreach ($products as $product) { ?>

      <div style="width:209px;text-align:center !important;height:160px;">

        <?php if ($product['thumb']) { ?>

        <div class="image" style="height:120px;margin-bottom:30px;"><a href="<?php echo $product['href']; ?>">
        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>

        <?php } ?>

        <div class="name"><a style="font-family:Dosis;font-size:14px;font-weight:600;" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>

        <?php if ($product['rating']) { ?>

        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>

        <?php } ?>

      </div>

      <?php } ?>

    </div>

  </div>

</div>




