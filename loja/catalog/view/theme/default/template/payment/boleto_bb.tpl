<!--/*
* Módulo de Pagamento Boleto Bancário Banco do Brasil
* Feito sobre OpenCart 1.5.1.3
* Autor Guilherme Desimon - http://www.desimon.net
* @01/2012
* Sob licença GPL.
*/-->
<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $text_instruction; ?><br />
  <br />
  <a href="index.php?route=payment/boleto_bb/callback&order_id=<?php echo $idboleto; ?>" target="_blank">Gerar Boleto Banco do Brasil</a><br />
  <br />
  <?php echo $text_payment; ?></div>
<div class="buttons">
  <div align="right"><a id="button-confirm" class="button"><span><?php echo $button_continue; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
  $.ajax({ 
    type: 'GET',
    url: 'index.php?route=payment/boleto_bb/confirm',
    success: function() {
      location = '<?php echo $continue; ?>';
    }   
  });
});
--></script>
