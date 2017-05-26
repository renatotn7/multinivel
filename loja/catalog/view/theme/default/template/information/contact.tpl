<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<!--CONTENT LOJA--> 
<div class="content-loja">

<div id="container" class="container-corpo">

    <table width="98.5%" align="center" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td>
<div id="content"><?php echo $content_top; ?>

  <div class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) {?>

       <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>

    <?php }?>

  </div>
<br /><br />
  <h1><?php echo $heading_title; ?></h1>

  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  
   <h2><?php echo $text_contact; ?></h2>
   
    <div class="content form">

    <b><?php echo $entry_name; ?></b><br />

    <input type="text" name="name" class="input-contact"  value="<?php echo $name; ?>" />

    <br />

    <?php if ($error_name) { ?>

    <span class="error"><?php echo $error_name; ?></span>

    <?php } ?>

    <br />

    <b><?php echo $entry_email; ?></b><br />

    <input type="text" class="input-contact" name="email"  value="<?php echo $email; ?>" />

    <br />

    <?php if ($error_email) { ?>

    <span class="error"><?php echo $error_email; ?></span>

    <?php } ?>

    <br />

    <b><?php echo $entry_enquiry; ?></b><br />

    <textarea name="enquiry" cols="40" class="textarea-contact" rows="10" style="width:50%"><?php echo $enquiry; ?></textarea>

    <br />

    <?php if ($error_enquiry) { ?>

    <span class="error"><?php echo $error_enquiry; ?></span>

    <?php } ?>

    <br />

    <b><?php echo $entry_captcha; ?></b><br />

    <input type="text" name="captcha" value="<?php echo $captcha; ?>" />

    <br />

    <img src="index.php?route=information/contact/captcha" alt="" />

    <?php if ($error_captcha) { ?>

    <span class="error"><?php echo $error_captcha; ?></span>

    <?php } ?>

    </div>

   

    <div class="contact-info">
    <h2><?php echo $text_location; ?></h2>

      <div class="content"><div class="left"><b><?php echo $text_address; ?></b><br />

        <?php echo $store; ?><br />

        <?php echo $address; ?></div>

      <div class="right">

        <?php if ($telephone) { ?>

        <b><?php echo $text_telephone; ?></b><br />

        <?php echo $telephone; ?><br />

        <br />

        <?php } ?>

        <?php if ($fax) { ?>

        <b><?php echo $text_fax; ?></b><br />

        <?php echo $fax; ?>

        <?php } ?>

      </div>

    </div>

    </div>


      <div style="padding:10px; clear:both;">
	  <input type="submit" value="Enviar" class="button" />
	  </div>

  <br />

<br />



  </form>
  <?php echo $content_bottom; ?></div>
</td>
</tr>
</table>
    
 </div>    
  <br>
   </div>    
    
<?php echo $footer; ?>