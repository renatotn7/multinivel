<?php echo $header; ?>
<!--CONTENT LOJA--> 
<div class="content-loja">
    <div id="container" class="container-corpo">
        <h2 style="text-align: center;background:rgb(255, 174, 150);color: #FFF"><?php echo $text_usuario_ja_foi_confirmado;?></h2>
        <a style="text-align: center" href="<?php echo $this->url->link('checkout/checkout');?>"><?php echo $text_voltar;?></a>
    </div>    
    <br>
</div>   
<?php echo $footer; ?> 