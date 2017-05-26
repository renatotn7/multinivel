<?php $this->lang->load('publico/fixo/topo_view');
$_SESSION['pagina_atual'] = base64_encode(current_url().(count($_REQUEST)>0?'?'.http_build_query($_REQUEST):'/'));
?>
<div class="l-header">
    <div class="l-header-h">
        <div class="l-subheader at_top">
            <div class="l-subheader-h i-cf">
                <div class="w-lang layout_dropdown show_onclick has_title">
                    <div class="w-lang-h">
                        <div class="w-lang-list"> 
                            <?php if($this->uri->segment(2)=='login'){?>
                            
                            <a class="w-lang-item lang_en" href="<?php echo base_url('index.php/entrar/login/pt')?>"> 
                                <span class="w-lang-item-icon"></span> 
                                <span class="w-lang-item-title">Português</span> 
                            </a> 
                            <a class="w-lang-item lang_de" href="<?php echo base_url('index.php/entrar/login/en')?>"> 
                                <span class="w-lang-item-icon"></span> 
                                <span class="w-lang-item-title">English</span> 
                            </a> 
                            <a class="w-lang-item lang_en" href="<?php echo base_url('index.php/entrar/login/es')?>"> 
                                <span class="w-lang-item-icon"></span> 
                                <span class="w-lang-item-title">Español</span> 
                            </a>
                            
                            <?php }else{?>
                    
                            <a class="w-lang-item lang_en" href="<?php echo base_url('index.php/distribuidor/cadastro_lang/pt/')?>"> 
                                <span class="w-lang-item-icon"></span> 
                                <span class="w-lang-item-title">Português</span> 
                            </a> 
                            <a class="w-lang-item lang_de" href="<?php echo base_url('index.php/distribuidor/cadastro_lang/en')?>"> 
                                <span class="w-lang-item-icon"></span> 
                                <span class="w-lang-item-title">English</span> 
                            </a> 
                            <a class="w-lang-item lang_en" href="<?php echo base_url('index.php/distribuidor/cadastro_lang/es')?>"> 
                                <span class="w-lang-item-icon"></span> 
                                <span class="w-lang-item-title">Español</span> 
                            </a>
                            
                            <?php }?>
                            
                        </div>
                        <div class="w-lang-current"> <span class="w-lang-item"> <span class="w-lang-item-icon"></span> <span class="w-lang-item-title"><?php echo $this->lang->line('label_idioma');?></span> </span> </div>
                    </div>
                </div>
                <div class="w-socials">
                    <div class="w-socials-h">
                        <div class="w-socials-list">
                            <div class="w-socials-item facebook" style="padding-right:7px;">
                                <div class="w-contacts-item"> 
                                    <span class="w-contacts-item-value">
                                        <i class="fa fa-key"></i> 
                                        <a href="<?php echo APP_BASE_URL . APP_PUBLICO ?>"><?php echo $this->lang->line('label_entrar');?></a></span> 
                                    <span class="w-contacts-item-value">
                                        <i class="fa fa-user"></i> 
                                        <a href="<?php echo APP_BASE_URL . APP_PUBLICO . '/index.php/distribuidor/cadastro' ?>"> <?php echo $this->lang->line('label_cadastro');?></a>
                                    </span> 
                                </div>
                            </div>
<!--                            <div class="w-socials-item facebook"> <a class="w-socials-item-link" target="_blank" href="<?php echo $this->lang->line('url_facebook') ?>"><i class="fa fa-facebook"></i> </a>
                                <div class="w-socials-item-popup">
                                    <div class="w-socials-item-popup-h"> <span class="w-socials-item-popup-text">Facebook</span> </div>
                                </div>
                            </div>
                            <div class="w-socials-item twitter"> <a class="w-socials-item-link" target="_blank" href="<?php echo $this->lang->line('url_twitter') ?>"> <i class="fa fa-twitter"></i> </a>
                                <div class="w-socials-item-popup">
                                    <div class="w-socials-item-popup-h"> <span class="w-socials-item-popup-text">Twitter</span> </div>
                                </div>
                            </div>
                            <div class="w-socials-item youtube"> <a class="w-socials-item-link" target="_blank" href="<?php echo $this->lang->line('url_youtube') ?>"> <i class="fa fa-youtube"></i> </a>
                                <div class="w-socials-item-popup">
                                    <div class="w-socials-item-popup-h"> <span class="w-socials-item-popup-text">You Tube</span></div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="l-subheader at_middle">
            <div class="l-subheader-h i-cf"> 

                 LOGO 
                
                <div class="w-logo">
                 <div class="w-logo-h"> <a class="w-logo-link" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('url_home'));?>"> <img class="w-logo-img" src="<?php echo $this->lang->line('url_img_logo');?>" alt="empresa"> <span class="w-logo-title"> <span class="w-logo-title-h">empresa</span> </span> </a> </div>
                </div>
                
                <nav class="w-nav layout_hor">
                  <div class="w-nav-control"><i class="fa fa-bars"></i></div>
                    <ul class="w-nav-list level_1">
                        <li class="w-nav-item level_1 has_sublevel"> <a class="w-nav-anchor level_1" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_empresa'));?>"><span class="w-nav-icon"><i class="fa fa-home"></i></span> <span class="w-nav-title"><?php echo $this->lang->line('label_empresa');?></span> <span class="w-nav-arrow"></span> </a>
                            <ul class="w-nav-list level_2">
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_empresa'))?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_quem_somos');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_missao_visao'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_missao_visao');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_eventos'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_eventos');?></span> <span class="w-nav-arrow"></span> </a> </li>
                            </ul>
                        </li>
                        <li class="w-nav-item level_1 has_sublevel"> <a class="w-nav-anchor level_1" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_produtos_servicos'));?>"> <span class="w-nav-icon"><i class="fa fa-home"></i></span> <span class="w-nav-title"><?php echo $this->lang->line('label_produtos_servicos');?></span> <span class="w-nav-arrow"></span> </a>
                            <ul class="w-nav-list level_2">
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_oportunidade'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_oportunidade');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_mercado'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_mercado');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_plano_compensacao'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_plano_compensacao');?></span> <span class="w_plano_co-nav-arrow"></span> </a> </li>
                            </ul>
                        </li>
                        <li class="w-nav-item level_1 has_sublevel"> <a class="w-nav-anchor level_1" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_loja'));?>"> <span class="w-nav-icon"><i class="fa fa-home"></i></span> <span class="w-nav-title"><?php echo $this->lang->line('label_loja');?></span> <span class="w-nav-arrow"></span> </a>
                            <ul class="w-nav-list level_2">
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri=//gemminni.com')?>" target="_blank"> <span class="w-nav-title"><?php echo $this->lang->line('label_gemminni');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri=//e-gemstone.com')?>" target="_blank"> <span class="w-nav-title"><?php echo $this->lang->line('label_egemstone');?></span> <span class="w-nav-arrow"></span> </a> </li>
                            </ul>
                        </li>
                        <li class="w-nav-item level_1 has_sublevel"> <a class="w-nav-anchor level_1" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_apresentacao'));?>"> <span class="w-nav-icon"><i class="fa fa-home"></i></span> <span class="w-nav-title"><?php echo $this->lang->line('label_apresentacao');?></span> <span class="w-nav-arrow"></span> </a>
                            <ul class="w-nav-list level_2">
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_apresentacao'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_apresentacao');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_materiais'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_materiais');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_videos'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_videos');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_apresentacao'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_downloads');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_conferencia'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_conferencia');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_noticias'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_noticias');?></span> <span class="w-nav-arrow"></span> </a> </li>
                            </ul>
                        </li>
                        <li class="w-nav-item level_1 has_sublevel"> <a class="w-nav-anchor level_1" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_suporte'));?>"> <span class="w-nav-icon"><i class="fa fa-home"></i></span> <span class="w-nav-title"><?php echo $this->lang->line('label_suporte');?></span> <span class="w-nav-arrow"></span> </a>
                            <ul class="w-nav-list level_2">
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri=//empresa.com/ticket')?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_ticket');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_perguntas'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_perguntas');?></span> <span class="w-nav-arrow"></span> </a> </li>
                                <li class="w-nav-item level_2"> <a class="w-nav-anchor level_2" href="<?php echo base_url('index.php/url/redirecionar?uri='.$this->lang->line('label_url_contatos'));?>"> <span class="w-nav-title"><?php echo $this->lang->line('label_contatos');?></span> <span class="w-nav-arrow"></span> </a> </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>-->
    </div>
</div>