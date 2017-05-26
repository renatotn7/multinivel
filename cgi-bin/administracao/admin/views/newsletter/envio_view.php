<div class="box-content min-height">
 <div class="box-content-header">Newsletter</div>
 <div class="box-content-body">

<h1>Não feche a página antes de concluir o envio</h1>
 <div><strong><?php echo "<p>Inicio do envio ".date('d/m/Y H:i:s')."<p>";?></strong></div>
  <div class="progress progress-striped active">
    <div class="bar" style="width: 1%;"></div>
  </div>
 <div class="row">
     <div class="span8">
         <strong>Total a enviar</strong>: <?php echo count($_SESSION['dis_newsletter']);?>
     </div>
     <div class="span8">
        <strong>Total enviado</strong>: <i class="enviado">0</i>
     </div>
     <div class="span8">
        <strong>Total não enviados</strong>: <i class="pendente">0</i>
     </div>
 </div>
 <iframe width="100%" frameborder="0" height="400px" src="<?php echo base_url('index.php/newsletter/enviando') ?>"></iframe>
 
 </div>
 </div>