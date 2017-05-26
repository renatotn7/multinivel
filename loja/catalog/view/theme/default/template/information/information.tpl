<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<!--CONTENT LOJA--> 
<div class="content-loja">

<div id="container" class="container-corpo">


 <table width="98.5%" align="center" border="0" cellpadding="0" cellspacing="0">
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

  <?php echo $description; ?>

  <div class="buttons">

    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>

  </div>

  <?php echo $content_bottom; ?>
   
   </div>
          
  </td>
   </tr>

   </table>

 </div>    
  <br>
   </div>

<?php echo $footer; ?>