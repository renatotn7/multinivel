<?php echo $header; ?>

<div class="content-loja">
    
    <div id="container" class="container-corpo"> 
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
          <!--banners-->
          <div id="content-banners">
            <div id="banner-principal-home">
              <?php echo $banner_principal;?>    
            </div>  
             
          </div>
          <!--end-->       

          
          <?php echo $content_top; ?>        

        </div>  
      </td>

    <!--end-->
        
           
    </tr>
  </table>
        
    </div>                

 <br>   
</div>

<?php echo $footer; ?>