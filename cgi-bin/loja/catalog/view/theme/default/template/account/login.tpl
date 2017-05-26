<?php echo $header; ?>

<!--CONTENT LOJA--> 
<div class="content-loja">

<div id="container" class="container-corpo">

<?php if ($success) { ?>

<div class="success"><?php echo $success; ?></div>

<?php } ?>

<?php if ($error_warning) { ?>

<div class="warning"><?php echo $error_warning; ?></div>

<?php } ?>

<?php echo $column_left; ?> <?php echo $column_right; ?>

    <table width="99%" align="center" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td>

<div id="content"><?php echo $content_top; ?>

  <div class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>

    <?php } ?>

  </div>
  <br /><br />
  <h1><?php echo $heading_title; ?></h1>

  <div class="login-content">

      <div class="left" style="height: 314px;">

      <h2 class="h2-login-left"> <?php echo $text_new_customer; ?> </h2>
      

      <div class="content">

        <p><b><?php echo $text_register; ?></b></p>

        <p><?php echo $text_register_account; ?></p>

        <a href="<?php echo $register; ?>" class="button"><?php echo $button_continue; ?></a></div>

    </div>

    <div class="right">

      <h2 class="h2-login-right"><?php echo $text_returning_customer; ?></h2>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

        <div class="content">

          <p><?php echo $text_i_am_returning_customer; ?></p>

          <b><?php echo $entry_email; ?></b><br />

          <input type="text" class="input-login" name="email" value="<?php echo $email; ?>" />

          <br />

          <br />

          <b><?php echo $entry_password; ?></b><br />

          <input type="password" class="input-login" name="password" value="<?php echo $password; ?>" />

          <br />

          <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />

          <br />

          <input type="submit" value="<?php echo $button_login; ?>" class="button" />

          <?php if ($redirect) { ?>

          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />

          <?php } ?>

        </div>

      </form>

    </div>

  </div>

  <?php echo $content_bottom; ?>
  
  </div>
  </td>
  </tr>
  </table>
<script type="text/javascript"><!--

$('#login input').keydown(function(e) {

	if (e.keyCode == 13) {

		$('#login').submit();

	}

});

//--></script> 

 </div>    
  <br>
   </div>

<?php echo $footer; ?>