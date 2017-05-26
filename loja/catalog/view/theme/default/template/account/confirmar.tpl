<?php echo $header; ?>
<!--CONTENT LOJA--> 
<div class="content-loja">
    <div id="container" class="container-corpo">
        <h2 style="text-align: center;background: green;color: #FFF"><?php echo $text_confirmado_com_sucesso; ?></h2>
        <a style="text-align: center" href="<?php echo $this->url->link('checkout/checkout');?>"><?php echo $text_voltar; ?></a>
    </div>    
    <br>
</div>   
<?php echo $footer; ?> 