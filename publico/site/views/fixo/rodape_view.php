<?php $this->lang->load('publico/fixo/rodape_view');?>
<div class="l-footer">
  <div class="l-footer-h"> 
    
    <!-- subfooter: top -->
    <div class="l-subfooter at_top">
      <div class="l-subfooter-h g-cols">
        <div class="one-third">
          <div class="widget">
            <h4><?php echo $this->lang->line('label_institucional');?></h4>
            <p><a href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_empresa'));?>"><?php echo $this->lang->line('label_empresa');?></a></p>
            <p><a href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_apresentacao'));?>"><?php echo $this->lang->line('label_downloads');?></a></p>
            <p><a href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_apresentacao'));?>"><?php echo $this->lang->line('label_apresentacao');?></a></p>
            <p><a href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_apresentacao'));?>"><?php echo $this->lang->line('label_conferencia');?></a></p>
            <p><a href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_noticias'));?>"><?php echo $this->lang->line('label_noticias');?></a></p>
          </div>
        </div>
        <div class="one-third">
          <div class="widget">
            <h4><?php echo $this->lang->line('label_ajuda_suporte');?></h4>
            <div class="w-bloglist date_atbottom">
              <div class="w-bloglist-list">
                <div class="w-bloglist-entry"> <a class="w-bloglist-entry-link" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_contatos'));?>"><?php echo $this->lang->line('label_contato');?></a> <br>
                  <a class="w-bloglist-entry-link" href="<?php echo base_url('index.php/url/redirecionar?uri=//www.empresa.com/ticket/')?>"><?php echo $this->lang->line('label_suporte_ticket');?></a> </div>
              </div>
            </div>
            <h4><?php echo $this->lang->line('label_forma_pagamento_recebimento');?></h4>
            <div class="w-bloglist date_atbottom">
              <div class="w-bloglist-list">
                <div class="w-bloglist-entry">Pay In - Pay Out Proprietary Systems<br>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="one-third">
          <div class="widget">
            <h4><?php echo $this->lang->line('label_contatos');?></h4>
            <div class="w-contacts">
              <div class="w-contacts-h">
                <div class="w-contacts-list">
                  <div class="w-contacts-item"> <i class="fa fa-envelope-o"></i> <span class="w-contacts-item-value"><a href="mailto:cs@empresa.com">cs@empresa.com</a></span> </div>
                </div>
              </div>
            </div>
            <br>
            <h4><?php echo $this->lang->line('label_denuncia');?></h4>
            <div class="w-contacts">
              <div class="w-contacts-h">
                <div class="w-contacts-list">
                  <div class="w-contacts-item"> <i class="fa fa-envelope-o"></i> <span class="w-contacts-item-value"><a href="mailto:denuncia@empresa.com">denuncia@empresa.com</a></span> </div>
                </div>
              </div>
            </div>
          </div>
          <div class="widget">
            <div class="w-socials size_normal">
              <div class="w-socials-h">
                <div class="w-socials-list">
                  <div class="w-socials-item facebook"> <a class="w-socials-item-link" target="_blank" href="<?php echo $this->lang->line('url_facebook') ?>"> <i class="fa fa-facebook"></i> </a>
                    <div class="w-socials-item-popup">
                      <div class="w-socials-item-popup-h"> <span class="w-socials-item-popup-text">Facebook</span> </div>
                    </div>
                  </div>
                  <div class="w-socials-item twitter"> <a class="w-socials-item-link" target="_blank" href="https://twitter.com/empresaoff"> <i class="fa fa-twitter"></i> </a>
                    <div class="w-socials-item-popup">
                      <div class="w-socials-item-popup-h"> <span class="w-socials-item-popup-text">Twitter</span> </div>
                    </div>
                  </div>
                  <div class="w-socials-item youtube"> <a class="w-socials-item-link" target="_blank" href="https://www.youtube.com/user/empresaOfficial"> <i class="fa fa-youtube"></i> </a>
                    <div class="w-socials-item-popup">
                      <div class="w-socials-item-popup-h"> <span class="w-socials-item-popup-text">You Tube</span> </div>
                    </div>
                  </div>
                    
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="l-subfooter at_bottom">
      <div class="l-subfooter-h i-cf">
        <p align="center">Copyright © 2014 - nossa empresa</p>
        <img src="<?php echo base_url('public/imagem/ssl.gif'); ?>" style="width: 62px; margin-top: -57px;">
      </div>
    </div>
  </div>
</div>