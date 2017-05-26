<ul>
     <?php 
		foreach ($categories as $category) {
		?>
     
    <li  <?php if ($category['category_id'] == $category_id){?> class="has-sub" <?php }?> >
      
          <?php if ($category['category_id'] == $category_id) { ?>

          <a class="tira-margim dropdow active" href="<?php echo $category['href']; ?>">
             <img src="<?php echo HTTP_IMAGE.$category['imagem']; ?>" />
             <?php echo $category['name']; ?>
          </a>

          <?php } else { ?>

          <a class="tira-margim dropdow" href="<?php echo $category['href']; ?>">
             <img src="<?php echo HTTP_IMAGE.$category['imagem']; ?>" />
             <?php echo $category['name']; ?>
          </a>

          <?php } ?> 
          
           <?php if ($category['children']) { ?>

          <ul class="teste">
           
           
            <?php foreach ($category['children'] as $child) { ?>

            <li>  
               
               
              <?php if ($child['category_id'] == $child_id) { ?>

              <a href="<?php echo $child['href']; ?>" class="active">  <?php echo $child['name']; ?></a>

              <?php } else { ?>

              <a href="<?php echo $child['href']; ?>">  <?php echo $child['name']; ?></a>

              <?php } ?>              

              <?php if ($child['subcats']) { ?>

               <ul id="sub-categoria">

               <?php foreach ($child['subcats'] as $subcats) { ?>

                  <li style="width:200px !important;">

                   <?php if ($subcats['category_id'] == $child_id) { ?>

                   <a href="<?php echo $subcats['href']; ?>" class="active">  <?php echo $subcats['name']; ?></a>

                   <?php } else { ?>

                   <a href="<?php echo $subcats['href']; ?>"> <?php echo $subcats['name']; ?></a>

                  <?php } ?>

                  </li> 

               <?php }?>   

               </ul>

              <?php }?>

              

              

            </li>

            <?php } ?>

          </ul>

          <?php } ?>

        </li>

        <?php } ?>

        </ul> 
		
<script type="text/javascript">

$(function(){

	$("#menu-categoria-pai li .expandir").click(function(){

		$("#menu-categoria-pai li ul").slideUp('slow');

		if($(this).html()=='<img src="<?php echo HTTP_IMAGE_TEMP;?>expandir-retono.png" />'){

		$("#menu-categoria-pai li .expandir").html('<img src="<?php echo HTTP_IMAGE_TEMP;?>expandir.png" />');	

		$(this).html('<img src="<?php echo HTTP_IMAGE_TEMP;?>expandir.png" />');

		}else{

		$("#menu-categoria-pai li .expandir").html('<img src="<?php echo HTTP_IMAGE_TEMP;?>expandir.png" />');	

		$(this).parent().find('ul').slideDown('slow');

		$(this).html('<img src="<?php echo HTTP_IMAGE_TEMP;?>expandir-retono.png" />');

		}

	
		return false;


		});

	

	});

</script>