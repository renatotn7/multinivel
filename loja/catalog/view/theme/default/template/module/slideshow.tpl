
  <div class="slideshow">
  
  
    <div id="slideshow<?php echo $module; ?>" class="nivoSlider" style="height:390px;">
  
      <?php   
      foreach ($banners as $banner) { ?>
  
      <?php if ($banner['link']) { ?>
  
      <a href="<?php echo $banner['link']; ?>">
      <img height="390px" src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
  
      <?php } else { ?>
   
      <img height="390px" src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
  
      <?php } ?>
  
      <?php } ?>
  
    </div>
  
  </div>


<script type="text/javascript"><!--
  $(document).ready(function() {
	 $('#slideshow<?php echo $module;?>').nivoSlider({
	    controlNav: false	 
     });
  });
--></script>

