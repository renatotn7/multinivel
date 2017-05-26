<div id="content">
<h1>Atendimento - <?php echo $conversa['ch_nome']?></h1>
<div id="conversa">

   <?php echo $conversa['ch_conversa']?>
</div>

<form id="form1" name="form1" method="post" action="">
  <textarea id="txt-msg-new" style="width:99%; outline:none;" rows="3"></textarea>
  <br />
  <input type="button" onclick="nova_Mensagem()" id="send-msg" value="Enviar" />
</form>
</div>


<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>



<script type="text/javascript">

function nova_Mensagem(){
	
		 $.ajax({
			url: 'index.php?route=chat/chat/nova_conversa&ch_id=<?php echo $this->request->get['conversa'];?>',
			dataType: 'html',
			type:'POST',
			data:{msg:$('#txt-msg-new').val(),ch_nome:'<?php echo $conversa['ch_nome']?>'},
		    success: function(html) {
			$('#txt-msg-new').val('');
			$('#txt-msg-new').focus();	
			$("#conversa").html(html);	
			},
			error: function(xhr, ajaxOptions, thrownError) {
			 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			 }
		 });
	
	}


$(function(){
	
	
	$(window).unload(function() {	
      $.ajax({
			url: 'index.php?route=chat/chat/sair_chat&ch_id=<?php echo $this->request->get['conversa'];?>',
			dataType: 'html',
			type:'GET'
		 });
    });
	
	
	scroll_to("#conversa");	
	 setInterval('carrega_conversa()',20000);
	 
	 
   $("#txt-msg-new").keyup(function(e) {
      if(e.which ==13){
		  nova_Mensagem();
		  }
     });
	 
	 
	 
	});


function carrega_conversa(){
	
		$.ajax({
			url: 'index.php?route=chat/chat/so_conversa&ch_id=<?php echo $this->request->get['conversa'];?>&token=<?php echo $this->session->data['token']?>',
			dataType: 'html',
		    success: function(html) {
			$("#conversa").html(html);
			scroll_to("#conversa");	
			},
			error: function(xhr, ajaxOptions, thrownError) {
			 alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			 }
		});	
	}


function scroll_to(div){
$(div).animate({
scrollTop: 1000000
},100);
}



</script>



<style>
body{margin:0; padding:0; font-family:arial;}
h1{
	margin:0px;
	background:#ccc;
	color:#333;
	padding:10px;
	font-size:14px;
	}
#conversa{
	height:220px;
	overflow:auto;
	padding:5px;
	background:#f3f3f3;
	}

.atendido,.atendente{
	border-bottom:1px solid #e9e9e9;
	font-size:13px;
	padding:2px 0;
	}	
	
.atendido strong{
	color:#606;
	font-size:14px;
	}
.atendente strong{
	color:#F60;
	font-size:14px;
	}
.atendido strong i,.atendente strong i{
	font-size:12px;
	font-style:normal;
	}	
#send-msg{
	padding:5px 20px;
	cursor:pointer;
	color:#FFF;
	background:#069;
	border:none;
	
	}			
</style>