<style type="text/css">
   #retornoTransacao{
      padding: 15px 0;
      text-align: center;
   }
</style>
<?php
echo $header;
echo "<div id=\"retornoTransacao\">";

echo "<h1> Transação ".$resultado."</h1>";

   if(isset($objResultado->cancelamento)){
      echo '<h2>'.$objResultado->cancelamento->mensagem.'</h2>';
   }

   if(isset($objResultado->autorizacao)){
      echo '<h2>'.$objResultado->autorizacao->mensagem.'</h2>';
   }

echo "</div>";

echo $footer;
?>