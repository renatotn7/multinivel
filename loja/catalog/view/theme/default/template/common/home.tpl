





<?php echo $header; ?>

<div class="content-loja">
    
    <div id="container" style="padding-top:0 !important;" class="container-corpo"> 
        <div class="barra-top-loja">
         <div class="titulo-category">Categoria</div>
        </div>
        
    <table width="1000px" border="0" style="padding: 0;margin: 0;">
     <tr>
       <td style="padding: 0;margin: 0;" valign="top" width="181px">
        
        <!--Conteúdo CATEGORIA-->
        <div id="coluna-categoria">
         <?php echo $column_left;?>
        </div>
      </td> 
      
      <td style="padding: 0;margin: 0;" width="819px">
        <!--Conteúdo MEIO-->
        <div id="coluna-conteudo" >

          
          <?php echo $content_top; ?>   
          
          
               <h1>DESTAQUES</h1>
        <div class="box">
          <div class="box-content">
          <div class="product-grid">
        <?php foreach ($products as $product) { ?>

      <div>

        <?php if ($product['thumb']) { ?>

        <div class="image">
        	<a href="<?php echo $product['href']; ?>" style="display:block;position:relative;">
                <img class="img-first" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
            </a>
        </div>

        <?php } ?>
        
        <span class="backblack">

            <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
    
            
    
              <?php if ($product['rating']) { ?>
    
            <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>
    
            <?php } ?>
    
            
    
            <?php if ($product['price']) { ?>
    
            <div class="price">
    
              <?php if (!$product['special']) { ?>
    
              <?php echo $product['price']; ?>
    
              <?php } else { ?>
    
              <span class="price-old">de <?php echo $product['price']; ?></span> 
              <span class="price-new"> Por <strong class="special"><?php echo $product['special']; ?></strong></span>
    
              <?php } ?>
    
            </div>
    
            <?php } ?>
			
         </span>
        

      </div>

       

      <?php } ?>
      </div>
              <div class='pagination'><?php echo $pagination?></div>
      </div>
        </div>
          

        </div>  
      </td>

    <!--end-->
        
           
    </tr>
  </table>
        
    </div>                

 <br>   
</div>

<?php echo $footer; ?>