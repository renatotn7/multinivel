<div class="banners-meio<?php echo $module ?>">

  <?php

  foreach ($banners as $banner) { ?>
<div id="banners">


  <?php if ($banner['link']) { ?>

  <div><a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></a></div>

  <?php } else { ?>

  <div><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" title="<?php echo $banner['title']; ?>" /></div>
 </div>
  <?php } ?>

  <?php } ?>


</div>