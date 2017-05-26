<?php echo $header; ?>

<!--CONTENT LOJA--> 
<div class="content-loja">
  <div id="container" class="container-corpo">

<?php if ($success) { ?>

<div class="success"><?php echo $success; ?></div>

<?php } ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
    <table width="98.5%" align="center" border="0" cellpadding="0" cellspacing="0">
     <tr>
      <td>
<div id="content"><?php echo $content_top; ?>

  <div class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>

    <?php } ?>
    
    <a style="float: right;font-weight: 600;" href="index.php?route=account/logout">SAIR</a>

  </div>

  <h1><?php echo $heading_title; ?></h1>
  
  <table width="100%" border="0">
      <tr>
          <td valign="top" style="text-align: left;">              
                <h2><?php echo $text_my_account; ?></h2>

                <div class="content" style="margin:0;padding: 0;">

                  <ul style="margin:0;padding: 5px;">
                    <?php if($customer_group_id != 3 && $consultor_id == 0){ ?>
                    <li> - <a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>

                    <li> - <a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>

                    <li> - <a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
                    <?php } ?>
                    <li> - <a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>

                  </ul>

                </div>  
          </td>
          <td valign="top">              
            <h2><?php echo $text_my_orders; ?></h2>

            <div class="content" style="margin:0;padding: 0;">

              <ul style="margin:0;padding: 5px;">

                <li> - <a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>

                <li> - <a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>

                <?php if ($reward) { ?>

                <li> - <a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>

                <?php } ?>

                <li> - <a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>

                <li> - <a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>

              </ul>

            </div>    
          </td>
          <td valign="top">
            <h2><?php echo $text_my_newsletter; ?></h2>

                <div class="content" style="margin:0;padding: 0;">

                  <ul style="margin:0;padding: 5px;">

                    <li> - <a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>

                  </ul>

                </div>  
          </td>
      </tr>
      
  </table>

  

  

  

  <?php echo $content_bottom; ?></div>
</td>
</tr>
</table>

 </div>    
  <br>
   </div>   
<?php echo $footer; ?> 