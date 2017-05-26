<div class="buttons">
  <div align="right"><a id="button-confirm" class="button"><span><?php echo $button_continue; ?></span></a></div>
</div>
<script type="text/javascript">

<!--
jQuery.noConflict();
jQuery(document).ready(function() {
	var url = "index.php?route=payment/boletocef/confirmorder&order_id=<?php echo $idboleto; ?>";
	var callorder = "index.php?route=payment/boletocef/callbackorder&order_id=<?php echo $idboleto; ?>";
	
	jQuery.colorbox({ title: "Boleto Caixa E. Federal",
				 iframe:true, 
				 href: callorder, 
				 innerWidth:'700px',
				 innerHeight:'90%',
				 onClosed:function(){ 
							location = 'index.php?route=payment/boletocef/confirmorder&order_id=<?php echo $idboleto; ?>';
		 				  }
			   });
	
});

--></script>
