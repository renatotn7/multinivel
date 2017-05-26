<?php echo $header; ?>

<script src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jqzoom_ev-2.3/js/jquery.jqzoom-core.js" type="text/javascript"></script>

<link rel="stylesheet" href="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jqzoom_ev-2.3/css/jquery.jqzoom.css" type="text/css">
<?php if($model == 'plano' || $model == 'franquia'){ echo '<style> .cart{display:none;}</style>';}?>


<script type="text/javascript">

    $(document).ready(function() {

    $('.jqzoom').jqzoom({

    zoomType: 'standard',
            preloadImages: false

    });
            $("#expandir").click(function(){



    $("body").append('<div id="modal-objeto-fundo"></div>');
            $("#modal-objeto").css('display', 'block');
            var w = 700;
            var h = 500;
            var x = (screen.width) ? (screen.width - w) / 2 : 0;
            $("#modal-objeto").css('left', x);
            $("#modal-objeto").animate({

    height:h,
            width:w

    });
    });
            $("#modal-objeto-fundo").live('click', function(){

    $(this).remove();
            $("#modal-objeto").animate({

    height:0,
            width:0

    });
            $("#modal-objeto").css('display', 'none');
    });
            $("#modal-img-miniatura img").click(function(){

    $("#modal-recebe-img").attr('src', $(this).attr('alt'));
    });
    });</script>


<!----JANELA MODAL PARA VISUALIZAR IMAGEM PRODUTO-->

<div id="modal-objeto">

    <span><?php echo $heading_title; ?></span>

    <table border="0" cellspacing="0" cellpadding="10">

        <tr>

            <td>

                <img src="<?php echo $popup; ?>" height="450px" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="modal-recebe-img" />

            </td>

            <td valign="top">

                <div id="modal-img-miniatura">

                    <?php foreach ($images as $image) { ?>

                    <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $image['popup']; ?>" />

                    <?php } ?>

                </div>    

            </td>

        </tr>

    </table>
</div>

<!--END-->



<!--CONTENT LOJA--> 
<div class="content-loja">
    <div id="container" class="container-corpo">

        <div id="notification"></div>

        <table width="1000px" border="0" style="padding: 0;margin: 0;">

            <tr>

                <td style="padding: 0;margin: 0;">

                    <!--Conteúdo MEIO-->
                    <div id="coluna-conteudo">

                        <!--AQUI TODO O CONTEÚDO DA PÁGINA VER PRODUTO-->


                        <?php echo $content_top; ?>



                        <div class="product-info">

                            <?php if ($thumb || $images) {?>

    <div class="left">

      <?php if ($thumb) { ?>

      <div class="image">

      <a href="<?php echo $popup; ?>" id="expandir" class="jqzoom" rel='gal1'  title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>

      <?php } ?>

                            <?php if ($images) { ?>

                            <div class="image-additional">

                                <?php foreach ($images as $image) { ?>

                                <a href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '<?php echo $image['normal']; ?>',largeimage: '<?php echo $image['popup']; ?>'}"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>

                                <?php } ?>

                            </div>

                            <?php } ?>

                        </div>

                        <?php } ?>

                        <div class="right">

                            <div class="description">      

                                <h1><?php echo $heading_title; ?></h1>

                                <!--COMENTÁRIOS AQUI-->


                                <?php if ($review_status) {?>

                                    <div id="comentarios"><img src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;
                                    <a style='color:#000000;cursor:pointer;' onclick="$('a[href=\'#tab-review\']').trigger('click');scroll_to('#tab-review');">em <?php echo $reviews; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a style='color:#005080' onclick="$('a[href=\'#tab-review\']').trigger('click');scroll_to('#tab-review');"><?php echo $text_write; ?></a></div>

   <?php }?>


                                <!--END-->
                                <?php if ($manufacturer) { ?>

                                <span>Mais produtos </span> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a>

                                <?php } ?>

                            </div>


                            <div class="content-price">   

                                <?php if ($price) { ?>

                                <div class="price"> 

                                    <?php if (!$special) { ?>

                                    <?php echo $price; ?>

                                    <?php } else { ?>

                                    <span class="price-old">De <?php echo $price; ?></span> <span class="price-new"> Por <?php echo $special; ?></span>

                                    <?php } ?>

                                    <br />

                                    <?php if ($tax) { ?>

                                    <span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span><br />

                                    <?php } ?>

                                    <?php if ($points) { ?>

                                    <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span><br />

                                    <?php } ?>

                                    <?php if ($discounts) { ?>

                                    <br />

                                    <div class="discount">

                                        <?php foreach ($discounts as $discount) { ?>

                                        <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />

                                        <?php } ?>

                                    </div>

                                    <?php } ?>

                                </div>

                                <?php } ?>

                                <!--ESTOQUE E MODELO-->

                                <div id="estoque-modelo">

                                    <strong style="padding-left:0;font-size: 11px !important;color:#999999;font-family: 'Open Sans', sans-serif;"><?php echo $text_stock;?></strong> 

                                    <?php echo $stock; ?>
                                    <br>
                                    <strong style="padding-left:0;font-size: 11px !important;color:#999999;font-family: 'Open Sans', sans-serif;"><?php echo $text_model; ?></strong> <?php echo $model; ?>

                                    <?php if ($reward) {?>

               <span><?php echo $text_reward; ?></span> <?php echo $reward; ?>

            <?php }?>

                                </div>

                                <!--END ESTOQUE E MODELO-->

                            </div>

                            <?php if ($options) { ?>

                            <div class="options">

                                <h2><?php echo $text_option; ?></h2>

                                <br />

                                <?php foreach ($options as $option) { ?>

                                <?php if ($option['type'] == 'select') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <select name="option[<?php echo $option['product_option_id']; ?>]">

                                        <option value=""><?php echo $text_select; ?></option>

                                        <?php foreach ($option['option_value'] as $option_value) { ?>

                                        <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

                                            <?php if ($option_value['price']) { ?>

                                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                                            <?php } ?>

                                        </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'radio') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <?php foreach ($option['option_value'] as $option_value) { ?>

                                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />

                                    <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

                                        <?php if ($option_value['price']) { ?>

                                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                                        <?php } ?>

                                    </label>

                                    <br />

                                    <?php } ?>

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'checkbox') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <?php foreach ($option['option_value'] as $option_value) { ?>

                                    <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />

                                    <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

                                        <?php if ($option_value['price']) { ?>

                                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                                        <?php } ?>

                                    </label>

                                    <br />

                                    <?php } ?>

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'image') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <table class="option-image">

                                        <?php foreach ($option['option_value'] as $option_value) { ?>

                                        <tr>

                                            <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>

                                            <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>

                                            <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>

                                                    <?php if ($option_value['price']) { ?>

                                                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)

                                                    <?php } ?>

                                                </label></td>

                                        </tr>

                                        <?php } ?>

                                    </table>

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'text') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'textarea') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'file') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">

                                    <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'date') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'datetime') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />

                                </div>

                                <br />

                                <?php } ?>

                                <?php if ($option['type'] == 'time') { ?>

                                <div id="option-<?php echo $option['product_option_id']; ?>" class="option">

                                    <?php if ($option['required']) { ?>

                                    <span class="required">*</span>

                                    <?php } ?>

                                    <b><?php echo $option['name']; ?>:</b><br />

                                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />

                                </div>

                                <br />

                                <?php } ?>

                                <?php } ?>

                            </div>

                            <?php } ?>    

                            <div class="cart">
                                <div>

                                    <strong style="color:#999999;font-size: 12px;font-weight: bold;">QTD.</strong>
                                    <input type="text" id="bg_quantity" name="quantity" size="2" value="<?php echo $minimum; ?>" />
                                    <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />

                                </div>

                                <?php if ($minimum > 1) { ?>

                                <div class="minimum"><?php echo $text_minimum; ?></div>

                                <?php } ?>

                            </div>

                            <div id="ver-produto-btns">   
                                <input type="button" id="button-cart" style="background: url(<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/'.$button_comprar; ?>)" class="button" />
                                <div id="ou"><?php echo $text_ou;?></div>
                                <a id="button-desejo" style="background: url(<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/'.$button_lista_desejos; ?>) " onclick="addToWishList('<?php echo $product_id; ?>');"></a>
                            </div>     

                            <div id="btn-desejo-comparar"> 

                                <!-- LISTA DE DESEJO -->



                                <div style="display: none;">| <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a></div>

                                <div id="indique-redes">
                                    <a id="indique-btn" href="javascript:void();"  style="background: url(<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/'.$imagem_indique_um_amigo; ?>) center no-repeat;"></a>

                                    <?php if ($review_status) {?>

      <div class="review rvw">

       <div class="share"  style='padding: 4px;width:120px;margin-left:8px;'><!-- AddThis Button BEGIN -->

          <div class="addthis_default_style" style='padding: 0;'> 

          <a class="addthis_button_email"></a><a class="addthis_button_print"></a> 

          <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a>

        </div>

          <script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script> 

          <!-- AddThis Button END --> 

        </div>

      </div>

      <?php }?>

                                </div>

                            </div>





                        </div>

                    </div>

                    <div id="descricao-produto">
                        <h2><?php echo $text_descricao;?></h2>   
                        <table width="100%" style="padding: 0; margin:0;">
                            <tr style="border: none;">
                                <td style="border: none;">
                                    <?php echo $description;?>
                                </td>  
                            </tr>
                        </table>
                    </div>

                    <div id="conteudo-relacionados">
                        <h2 style="background: url(<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/'.$titulo_relacionados; ?>) no-repeat;"></h2>    
                        <table width="100%" style="padding: 0; margin:0;">
                            <tr style="border: none;">
                                <td style="border: none;">
                                    <?php echo $description;?>
                                </td>  
                            </tr>
                        </table>
                    </div>

                    <div id="conteudo-avaliacao">
                        <h4 style="background: url(<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/'.$titulo_avalie; ?>) no-repeat;"></h4>    
                        <table width="100%" style="padding: 0; margin:0;">

                            <tr style="border: none;">

                                <td style="border: none;">

                                    <div id="review" style="display: none;"></div>

                                    <div id="div-esquerda">

                                        <input type="text" placeholder="Nome" style="width: 100%;border: 1px solid #CCCCCC;background: #fff;font-family: 'Open Sans', sans-serif;font-weight: 300;font-size:14px;" name="name" value="" />


                                        <textarea  placeholder="Comente sobre o produto" name="text" cols="40" rows="4" style="width: 100%;font-size:14px;background: #fff;margin-top:10px;font-family: 'Open Sans', sans-serif;font-weight: 300;"></textarea><br />

                                    </div>


                                    <div id="div-direita">       

                                        <div id="corpo-avaliar">
                                            <b style="color:#fff;"><?php echo $entry_rating; ?></b> <span style="display: none;"><?php echo $entry_bad; ?></span>&nbsp;

                                            <input type="radio" name="rating" value="1" />

                                            &nbsp;

                                            <input type="radio" name="rating" value="2" />

                                            &nbsp;

                                            <input type="radio" name="rating" value="3" />

                                            &nbsp;

                                            <input type="radio" name="rating" value="4" />

                                            &nbsp;

                                            <input type="radio" name="rating" value="5" />

                                            &nbsp;<span style="color:#fff;"><?php echo $entry_good; ?></span>     

                                        </div>

                                        <div id="corpo-capcha">

                                            <table width="100px" border="0" cellspacing="0" cellpadding="4">

                                                <tr>

                                                    <td><input type="text" style="padding:12px;border: 1px solid #999;background: none;" name="captcha" value="" /></td>

                                                    <td><img style="margin-top: 2px;margin-left: 2px;" src="<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=product/product/captcha" height="42px" alt="" id="captcha" /></td>

                                                </tr>

                                            </table>

                                        </div>


                                        <b style="display: none;"><?php echo $entry_captcha; ?></b>




                                        <div class="left" style="float: right;position: relative;">
                                            <a id="button-review" style="position: absolute;top:12px;right: -16px;" class="button bt-avaliar"></a>
                                        </div>

                                    </div>

                                </td>  
                            </tr>
                        </table>
                    </div>

                    <div style="display: none;" id="tabs" class="htabs">

                        <a style="display: none;" href="#tab-description"><?php echo $tab_description; ?></a>

                        <?php if($attribute_groups){?>

    <a style="display: none;" href="#tab-attribute"><?php echo $tab_attribute; ?></a>

    <?php }?>

                        <?php //if ($review_status) {?>

    <a style="display: none;" href="#tab-review"><?php echo $tab_review; ?></a>

    <?php //} ?>

                        <?php if ($products) { ?>

                        <a style="display: none;" href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a>

                        <?php } ?>



                    </div>



                    <?php if ($attribute_groups) { ?>

                    <div style="display: none;" id="tab-attribute" class="tab-content">

                        <table class="attribute">

                            <?php foreach ($attribute_groups as $attribute_group) { ?>

                            <thead>

                                <tr>

                                    <td colspan="2"><?php echo $attribute_group['name']; ?></td>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($attribute_group['attribute'] as $attribute) { ?>

                                <tr>

                                    <td><?php echo $attribute['name']; ?></td>

                                    <td><?php echo $attribute['text']; ?></td>

                                </tr>

                                <?php } ?>

                            </tbody>

                            <?php } ?>

                        </table>

                    </div>

                    <?php } ?>

                    <?php if ($review_status=='') { ?>

                    <div style="display: none;" id="tab-review" class="tab-content">

                        <div id="review"></div>

                        <h2 id="review-title"><?php echo $text_write; ?></h2>

                        <b><?php echo $entry_name; ?></b><br />

                        <input type="text" style="width: 50%;" name="name" value="" />

                        <br />

                        <br />

                        <b><?php echo $entry_review; ?></b><br />

                        <textarea name="text" cols="40" rows="4" style="width: 50%;"></textarea><br />

                        <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />

                        <br />

                        <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;

                        <input type="radio" name="rating" value="1" />

                        &nbsp;

                        <input type="radio" name="rating" value="2" />

                        &nbsp;

                        <input type="radio" name="rating" value="3" />

                        &nbsp;

                        <input type="radio" name="rating" value="4" />

                        &nbsp;

                        <input type="radio" name="rating" value="5" />

                        &nbsp;<span><?php echo $entry_good; ?></span><br />

                        <br />

                        <b><?php echo $entry_captcha; ?></b><br />



                        <table width="100px" border="0" cellspacing="0" cellpadding="4">

                            <tr>

                                <td><input type="text" style="padding:8px;" name="captcha" value="" /></td>

                                <td><img src="index.php?route=product/product/captcha" alt="" id="captcha" /></td>

                            </tr>

                        </table>





                        <br />

                        <div class="left"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>



                    </div>

                    <?php } ?>

                    <?php if ($products) { ?>

                    <div id="tab-related" class="tab-content">

                        <div class="box-product">

                            <?php foreach ($products as $product) { ?>

                            <div>

                                <?php if ($product['thumb']) { ?>

                                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>

                                <?php } ?>

                                <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>

                                <?php if ($product['price']) { ?>

                                <div class="price">

                                    <?php if (!$product['special']) { ?>

                                    <?php echo $product['price']; ?>

                                    <?php } else { ?>

                                    <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new" style="color: #a8cf45;"><?php echo $product['special']; ?></span>

                                    <?php } ?>

                                </div>

                                <?php } ?>

                                <?php if ($product['rating']) { ?>

                                <div class="rating"><img src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" /></div>

                                <?php } ?>

                                <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button"><?php echo $button_cart; ?></a></div>

                            <?php } ?>

                        </div>

                    </div>

                    <?php } ?>


                    <?php if ($tags=='') { ?>

                    <div class="tags"><b><?php echo $text_tags; ?></b>

                        <?php for ($i = 0; $i < count($tags); $i++) { ?>

                        <?php if ($i < (count($tags) - 1)) { ?>

                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,

                        <?php } else { ?>

                        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>

                        <?php } ?>

                        <?php } ?>

                    </div>

                    <?php } ?>

                    <?php echo $content_bottom; ?>


                    <!--END CONTEÚDO PRODUTO--> 
                    <!--END-->


                    </div>  

                </td>

                <!--end-->     

            </tr>
        </table>

    </div>
    <!--END CONTENT COPO-->
    <br>
</div>
<!--END CONTENT LOJA--> 


z
<script type="text/javascript"><!--



            $('#button-cart').bind('click', function() {

    $.ajax({

    url: '<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=checkout/cart/add',
            type: 'post',
            data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
            dataType: 'json',
            success: function(json) {

            $('.success, .warning, .attention, information, .error').remove();
                    if (json['error']) {

            if (json['error']['option']) {

            for (i in json['error']['option']) {

            $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
            }

            }

            }

            if (json['redirect'] && !json['error']) {
            location = json['redirect'];
            }

            if (json['success']) {

            $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                    $('.success').fadeIn('slow');
                    $('#cart-total').html(json['total']);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
            }

            }

    });
    });
//--></script>

<?php if ($options) { ?>

<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>

<?php foreach ($options as $option) { ?>

<?php if ($option['type'] == 'file') { ?>

<script type="text/javascript"><!--

            new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {

            action: 'index.php?route=product/product/upload',
                    name: 'file',
                    autoSubmit: true,
                    responseType: 'json',
                    onSubmit: function(file, extension) {

                    $('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
                            $('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
                    },
                    onComplete: function(file, json) {

                    $('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);
                            $('.error').remove();
                            if (json['success']) {

                    alert(json['success']);
                            $('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
                    }



                    if (json['error']) {

                    $('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
                    }



                    $('.loading').remove();
                    }

            });
//--></script>

<?php } ?>

<?php } ?>

<?php } ?>

<script type="text/javascript"><!--

            $('#review .pagination a').live('click', function() {

    $('#review').fadeOut('slow');
            $('#review').load(this.href);
            $('#review').fadeIn('slow');
            return false;
    });
            $('#review').load('<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');
            $('#button-review').bind('click', function() {

    $.ajax({

    url: '<?php echo APP_BASE_URL.APP_LOJA;?>/index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
            type: 'post',
            dataType: 'json',
            data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
            beforeSend: function() {

            $('.success, .warning').remove();
                    $('#button-review').attr('disabled', true);
                    $('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
            },
            complete: function() {

            $('#button-review').attr('disabled', false);
                    $('.attention').remove();
            },
            success: function(data) {
            if (data['error']) {
            alert(data['error']);
                    $('#review-title').after('<div class="warning">' + data['error'] + '</div>');
            }



            if (data['success']) {

            $('#review-title').after('<div class="success">' + data['success'] + '</div>');
                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').attr('checked', '');
                    $('input[name=\'captcha\']').val('');
            }

            }

    });
    });
            /*SCROLLL*/

                    function scroll_to(div){

                    $('html, body').animate({

                    scrollTop: $(div).offset().top

                    }, 2000);
                    }







//--></script> 

<script type="text/javascript"><!--

            $('#tabs a').tabs();
//--></script> 

<script type="text/javascript" src="<?php echo APP_BASE_URL.APP_LOJA;?>/catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 

<script type="text/javascript"><!--

                    if ($.browser.msie && $.browser.version == 6) {

            $('.date, .datetime, .time').bgIframe();
            }



            $('.date').datepicker({dateFormat: 'yy-mm-dd'});
                    $('.datetime').datetimepicker({

            dateFormat: 'yy-mm-dd',
                    timeFormat: 'h:m'

            });
                    $('.time').timepicker({timeFormat: 'h:m'});
//--></script> 

<?php echo $footer; ?>