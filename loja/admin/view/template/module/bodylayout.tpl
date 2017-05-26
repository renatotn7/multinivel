<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>

    <?php } ?>

  </div>

  <?php if ($error_warning) { ?>

  <div class="warning"><?php echo $error_warning; ?></div>

  <?php } ?>

  <div class="box">

    <div class="heading">

      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>

      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>

    </div>

    <div class="content">

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

        <table id="module" class="list">
          <thead>

            <tr>         
              <td width='15%' class="left">Imagem</td>

              <td width='15%' class="left">Cor</td>

              <td  width='35%' class="left">Repetir fundo</td>

              <td class="left">Posição</td>
           

            </tr>

          </thead>


          <tbody id="module-row">

            <tr>

              <td class="left">
                  <div class="image" style="margin:0 auto;display: block;text-align: center;">
                      
                      <img id="thumb0" alt=""  src="<?php if(isset($blackgrounds['bl_image'])){ echo .$blackgrounds['bl_image'];?>  <?php }else{?>http://www.hobbz.com.br/loja/image/cache/no_image-100x100.jpg<?php }?>">
                        <input id="image0" type="hidden" value="no_image.jpg" name="image">
                        <br>
                        <a onclick="image_upload('image0', 'thumb0');">Localizar</a>
                         |  
                        <a onclick="$('#thumb0').attr('src', 'http://www.hobbz.com.br/loja/image/cache/no_image-100x100.jpg'); $('#image0').attr('value', '');">Apagar</a>
                  </div> 
              </td>

              <td class="left" style="text-align: center;">
                 
                  <input type="text" id="bk_cor" value="<?php if(isset($blackgrounds['bl_cor'])){ echo $blackgrounds['bl_cor'];?> <?php }?>" name="cor">

              </td>

              <td class="left" width='35%'>
                  <table width="100%" border="0">
                      <tr style="border: none;">
                        <td style="border: none;">
                            <input style="margin-top: 2px;" <?php if(isset($blackgrounds['bl_repeat']) && $blackgrounds['bl_repeat']==1){?> checked="checked" <?php }?> type="radio" name="repeat" value="1"> &nbsp;Repetir   
                        </td>
                        <td style="border: none;">
                            <input style="margin-top: 2px;" type="radio" <?php if(isset($blackgrounds['bl_repeat']) && $blackgrounds['bl_repeat']==2){?> checked="checked" <?php }?> name="repeat" value="2"> &nbsp;R. Vertical 
                          </td>
                          <td style="border: none;">
                             <input style="margin-top: 2px;" type="radio" <?php if(isset($blackgrounds['bl_repeat']) && $blackgrounds['bl_repeat']==3){?> checked="checked" <?php }?> name="repeat" value="3"> &nbsp;R. Horizontal  
                          </td>
                          <td style="border: none;">
                             <input style="margin-top: 2px;" type="radio" <?php if(isset($blackgrounds['bl_repeat']) && $blackgrounds['bl_repeat']==4){?> checked="checked" <?php }?> name="repeat" value="4"> &nbsp;Não Repetir
                          </td>
                      </tr>
                  </table>

              </td>

              <td class="left">
                    <table width="100%" border="0">
                        
                      <tr style="border: none;">
                        <td style="border: none; text-align: center;">
                            <img width="50px" src="http://www.hobbz.com.br/loja/catalog/view/theme/default/image/layout_hobbz/center.jpg"/><br>
                            <input <?php if(isset($blackgrounds['bl_position']) && $blackgrounds['bl_position']==1){?> checked="checked" <?php }?> type="radio" name="position" value="1">  
                        </td>
                        <td style="border: none; text-align: center;">
                            <img width="50px" src="http://www.hobbz.com.br/loja/catalog/view/theme/default/image/layout_hobbz/top.jpg"/><br>
                            <input <?php if(isset($blackgrounds['bl_position']) && $blackgrounds['bl_position']==2){?> checked="checked" <?php }?> type="radio" name="position" value="2">
                          </td>
                        <td style="border: none; text-align: center;">
                            <img width="50px" src="http://www.hobbz.com.br/loja/catalog/view/theme/default/image/layout_hobbz/bottom.jpg"/><br>
                            <input <?php if(isset($blackgrounds['bl_position']) && $blackgrounds['bl_position']==3){?> checked="checked" <?php }?> type="radio" name="position" value="3">
                          </td>
                          <td style="border: none; text-align: center;">
                              <img width="50px" src="http://www.hobbz.com.br/loja/catalog/view/theme/default/image/layout_hobbz/left.jpg"/><br>
                             <input <?php if(isset($blackgrounds['bl_position']) && $blackgrounds['bl_position']==4){?> checked="checked" <?php }?> type="radio" name="position" value="4">
                          </td>
                          <td style="border: none; text-align: center;">
                             <img width="50px" src="http://www.hobbz.com.br/loja/catalog/view/theme/default/image/layout_hobbz/left.jpg"/><br>
                             <input <?php if(isset($blackgrounds['bl_position']) && $blackgrounds['bl_position']==5){?> checked="checked" <?php }?> type="radio" name="position" value="5">
                          </td>
                      </tr>
                      
                  </table> 
              </td>



            </tr>

          </tbody>



        </table>

      </form>

    </div>

  </div>

</div>

<style>
  #bk_cor{
    width: 92%;
    height: 60px;
    border:1px solid #EEEEEE;
    display: block;
    margin:0px 0 0 0;
  }
</style>

<script type="text/javascript">
   $(document).ready(function(){  
       $("#bk_cor").ColorPicker({
	  onSubmit:function(hsb, hex, rgb, el) {
                $(el).val("#"+hex);
		$(el).css("background-color","#"+hex);
		$(el).ColorPickerHide();
	  },
        
	  onBeforeShow: function () {
                $(this).ColorPickerSetColor(this.value);
	  }
       });
   }); 
   
  
function image_upload(field, thumb) {
 
	$('#dialog').remove();	

	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

	$('#dialog').dialog({

		title: '<?php echo $text_image_manager; ?>',

		close: function (event, ui) {

			if ($('#' + field).attr('value')) {

				$.ajax({

					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),

					dataType: 'text',

					success: function(data) {

						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');

					}

				});

			}

		},	

		bgiframe: false,

		width: 700,

		height: 400,

		resizable: false,

		modal: false

	});

};

</script> 

<?php echo $footer; ?>