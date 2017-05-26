<!DOCTYPE html>
<html dir="ltr" lang="pt-br">
<head>
<meta charset="UTF-8" />
</head>
<body>
<div id="header">
Bem vindo ao chat online, preencha o formulário para iniciar a conversa.
</div>
<form id="form1" name="form1" method="post" action="<?php echo $this->url->link('chat/chat/iniciar_conversa')?>">
<p>
Nome:<br />
  <input type="text" required style="width:97%" name="ch_nome" />
</p>
<p>
E-mail:<br />
<input type=email  required style="width:97%" name="ch_email" />
</p> 
<p>
Assunto:<br />
<input type="text" required style="width:97%" name="ch_assunto" />
</p>

<p>
  <textarea name="ch_conversa" required style="width:97%" rows="5"></textarea>
</p>
 <p>
   <input type="submit" id="button" value="Iniciar conversa" />
 </p>
</form>
<style>
body{
	margin:0; padding:0;
	}
p{
	font:bold 13px arial;
	margin:2px 0;
	}
form{padding:4px;}	
 form input, form textarea{
	 padding:4px;
	 background:#f3f3f3;
	 border:1px solid #999;
	 outline:none;
	 }
 form input[type='submit']{
	 background:#069;
	 color:#FFF;
	 font-size:14px;
	 cursor:pointer;
	 }
 form input[type='submit']:hover{
	 box-shadow:0 0 14px #069;
	 }	
#header{
	background:#f3f3f3;
	font-size:13px;
	margin-bottom:10px;
	padding:10px;
	font-weight:bold;
	color:#060;
	}	  	 
</style>
</body>

</html>

