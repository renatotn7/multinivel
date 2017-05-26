</div>

<div id="footer">
    
<div id="corpo-footer">


  <div id="content-footer-top">
      
  <?php if ($informations) { ?>
  <div class="column remove-margem" id="mega-motos">
    <h4 id="h4"><strong>INSTITUCIONAL</strong></h4>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  
  
  <div class="column" id="minha-conta">
    <h4 id="h4"><strong>Minha conta</strong></h4>
    <ul>
        <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>

    </ul> 
  </div>
    
  <div class="column" id="ajuda_suporte">
  <h4 id="h4">Ajuda e Suporte</h4>
     <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  
</div> 

    <div class="content-meio-footer" style="display: none;">
  <div id="telefones">
     <h4>CONTATO</h4> 
     
     <div id="chat-footer">
      <a style="display:none;" id="online-chat" onclick="window.open('<?php echo $this->url->link('chat/chat')?>','Page','width=500,height=400')">- Chat - Online</a>
      <a style="display:none;"  id="offline-chat" href="javascript:void(0)">Chat - Offline</a>
     </div>
     <div id="telefone-footer">
       <span>
          <?php echo $this->config->get('config_telephone') ?>
       </span>
     </div>
     
    <script type="text/javascript">
    $(function(){verifica_chat();setInterval('verifica_chat()',20000);});
     function verifica_chat(){$.ajax({url: 'index.php?route=chat/chat/verificar_status',dataType: 'json',success: function(json) {if(json.online==1){$('#online-chat').css('display','block');$('#offline-chat').css('display','none');}else{$('#online-chat').css('display','none');$('#offline-chat').css('display','block');}}});}
    </script>
  </div> 
    
    <div id="formas-pagamentos">
       <h4>FORMAS DE PAGAMENTO</h4>
       <img style="margin:8px 0 0 0;" src="<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/formas_pagamentos.jpg'; ?>" /> 
    </div>
    
    <div id="siganos">
      <h4>SIGA-NOS</h4>
        <a href=""><img src="<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/twitter.png'; ?>" /></a>
        <a href=""><img src="<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/facebook.png'; ?>" /></a>
        <a href=""><img src="<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/youtube.png'; ?>" /></a>
        <a href=""><img src="<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/instagram.png'; ?>" /></a>
        <a href="" style="float: right;margin:8px 0 0 0;"><img src="<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/google_mais.png'; ?>" /></a>
    </div>
    
</div> 

    
</div>    
 
    
</div>

</div>

<div class="content-direitos">
 <div style="width:1000px;margin:0 auto;position:relative;">
 <span style="position: absolute;top: 33px;color:#fff;font-size: 12px;font-family:'Open Sans', sans-serif; 
left: 0px;">Copyright © <?php echo date('Y');?> - <?php echo $this->config->get('config_name')?></span>
     <a target="_blank" style="position:absolute;top:29px;right:0;" href="<?php echo APP_BASE_URL?>">
         <img height="30px" src="<?php echo HTTP_IMAGE_TEMP.'logomarca.png'; ?>" /></a>
    </div>
</div>


<script>
  $("#login-user").click(function(){
      $(".dropdown-menu").toggle();
  });
</script>

</body></html>