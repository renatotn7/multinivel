<div class="box menu-lateral" style="position: relative;">
  
  <div class="box-content menu-lateral-conteudo">
    <div class="box-category">
        <br>

        <ul id="menu-categoria-pai">

        <?php foreach ($categories as $category) {?>

          <li>

          <?php if ($category['children']) { ?>

          <a style='display:none;' class="expandir"> + </a>

          <?php }?>

          

          <?php if ($category['category_id'] == $category_id) { ?>

          <a href="<?php echo $category['href']; ?>" class="active"><strong><?php echo $category['name']; ?></strong></a>

          <?php } else { ?>

          <a href="<?php echo $category['href']; ?>"><strong><?php echo $category['name']; ?></strong></a>

          <?php } ?>

          <?php if ($category['children']) { ?>

          <ul>

            <?php foreach ($category['children'] as $child) { ?>

            <li>



            

              <?php if ($child['category_id'] == $child_id) { ?>

              <a href="<?php echo $child['href']; ?>" class="active">  <?php echo $child['name']; ?></a>

              <?php } else { ?>

              <a href="<?php echo $child['href']; ?>">  <?php echo $child['name']; ?></a>

              <?php } ?>

              

              <?php if ($child['subcats']) { ?>

               <ul class="sub-cats">

               <?php foreach ($child['subcats'] as $subcats) { ?>

                  <li>

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

    </div>
          <ul style="margin: 0;padding: 0;">
            <li class="botao-oferta" style="list-style: none;">
                <a  style="background: url(<?php echo HTTP_IMAGE_TEMP.'layout_hobbz/'.$btn_ofertas; ?>) no-repeat;" id="bt-ofertas"  href="javascript:void(0);"></a>
          <!--ofertas flutuante-->
            <div id="oferta-hover"> 
              <a id="fechar_janela_oferta" href="javascript:void(0);"><img src="<?php echo HTTP_IMAGE_TEMP ?>remove.png" /></a>
              <?php echo $specialhome;?>              
            </div>
          <!--end ofertas flutuante-->
          </li>
          <li class="botao-correio"><a id="bt-correios" href="javascript:void(0);"></a>
           <!--Rastreio-Correios-->
            <div id="rastreio-correios"> 
                <a id="fechar_janela_rastreio" href="javascript:void(0);"><img src="<?php echo HTTP_IMAGE_TEMP ?>remove.png" /></a>
              <span><?php echo $text_reatrei_seu_pedido;?></span>
                <form action="http://websro.correios.com.br/sro_bin/txect01$.QueryList?" method="get" target="_black">
                 <input type="hidden" name="P_LINGUA" value="001"/>
                 <input type="hidden" name="P_TIPO" value="001"/>
                 <input id="codigo" name="P_COD_UNI" placeholder="Número do pedido" type="text" /> 
                 <button id="enviar"></button>
                </form>
            </div>
          <!--End Rastreio-Correios-->
          </li>
          <li class="botao-newsletter"><a id="bt-newsletter"  href="javascript:void(0);"></a>
           <!--Newsletter-->
            <div id="box-newsletter"> 
                <a id="fechar_janela_news" href="javascript:void(0);"><img src="<?php echo HTTP_IMAGE_TEMP ?>remove.png" /></a>
                <span><?php echo $text_receba_nossas_novidades;?></span>
                <label style="display: none;" class="label label-success" id="subscribe_result"></label>
                <form name="subscribe" id="subscribe" action="" method="post">
                 <input name="subscribe_email" id="subscribe_email" placeholder="E-mail" type="text" /> 
                 <input name="subscribe_name" id="subscribe_name" placeholder="Nome" type="text" />
                 <a href="javascript:void(0);" onclick="email_subscribe();" id="enviar"></a>
                </form>
            </div>
          <!--End Newsletter-->
          </li>
        </ul>
  </div>


           

</div>

<script type="text/javascript">

$(function(){

	$("#menu-categoria-pai li .expandir").click(function(){

		

		$("#menu-categoria-pai li ul").slideUp('slow');

		if($(this).html()=='-'){

		$("#menu-categoria-pai li .expandir").html('+');	

		$(this).html('+');

		}else{

		$("#menu-categoria-pai li .expandir").html('+');	

		$(this).parent().find('ul').slideDown('slow');

		$(this).html('-');

		}

		

		

		return false;

		

		});

	

	});
        

function email_subscribe(){
	$.ajax({
			type: 'post',
			url: 'index.php?route=module/newslettersubscribe/subscribe',
			dataType: 'html',
            data:$("#subscribe").serialize(),
			success: function (html) {
                                $('#subscribe_result').fadeIn();
				eval(html);
			}}); 
}

function email_unsubscribe(){
	$.ajax({
			type: 'post',
			url: 'index.php?route=module/newslettersubscribe/unsubscribe',
			dataType: 'html',
            data:$("#subscribe").serialize(),
			success: function (html) {
                                $('#subscribe_result').fadeIn();
				eval(html);
			}}); 
}
   


</script>