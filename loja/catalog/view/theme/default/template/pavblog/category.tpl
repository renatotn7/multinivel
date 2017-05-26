<?php echo $header; ?>
	
<?php echo $banner_principal; //slideshow ?>


<div class="marcas">  
   <?php echo $content_bottom; ?>
</div>

<div id="container" >

   <div class="content-saude">
       <h1 class="titulo-content">
          
          <?php echo $leading_blogs[0]['title']; ?>
       </h1>
       
       <hr class="linha-titulo" /><br>
       
       <div class="texto">
          <?php echo $leading_blogs[0]['description'];?>
       </div>
       
       <?php 
   
      if($this->request->get['route'] == 'pavblog/category' && $_GET['id'] == 25)
      {   
   ?>
   <div class="content-mapa">
      <p class="titulo-mapa"> Veja no mapa </p>
      
      <iframe class="mapa-border" width="625" height="205" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com.br/?ie=UTF8&amp;ll=-16.713223,-49.310042&amp;spn=0.006258,0.010568&amp;t=m&amp;z=16&amp;output=embed"></iframe>
   </div>
   
   <?php } ?>
       
   </div>
   
   
   
   
   <div class="veja-tambem">
      <p class="tutolu-vejatambem">Veja também</p>
      
      
      <div class="menu-vejatambem">
	      
         <?php
		 // echo "<div><pre>";
         // print_r(get_defined_vars()); die; 
         foreach($secondary_blogs as $l){ ?>
		  
			  <a href="<?php echo $l['link']; ?>">
				<?php echo $l['title']; ?>
			  </a>
      <?php } ?>
      </div>
      <img src="<?php echo HTTP_IMAGE_TEMP.'layout/foto-saude-animal.png'; ?>" />
      
      <div  class="email">
          <b>E-mail :</b><?php echo $email; ?>
      </div>
      
      <div  class="fone">
         <b>Fone :</b><?php echo $telephone; ?>
      </div>
      
      <div  class="endereco">
          <b>Endereço :</b><?php echo $address; ?>
      </div>
      
   </div>
    
   
    
</div>

<!-- buscar  -->
<div class="buscar-footer">
    <div class="central-busca">
       
    <div id="form-procura-footer" class="posicao-procura">

     <?php if ($filter_name) { ?>
        <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="busca"  />
     <?php } else { ?>
         <input type="text" name="filter_name" value="O que você procura?" class="busca-footer" onBlur="if(this.value==''){this.value='O que você procura?'}" onFocus="if(this.value=='O que você procura?'){this.value=''}" />
     <?php } ?>

     <input type="image" class="button-search" src="<?php echo HTTP_IMAGE_TEMP ?>layout/buscar.png" id="img_buttom" value="Buscar"/>

   </div>
       
    </div>
</div>
<!-- /buscar  -->

<?php echo $footer; ?>