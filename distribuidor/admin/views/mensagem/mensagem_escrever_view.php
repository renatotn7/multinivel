<style>
input{outline:none; padding:3px;}
.mail-header{
 background-image:-webkit-gradient(linear, 0 0%, 0 100%, from(#FFFFFF), to(#F0F0F0));
  background-position:initial initial;
  background-repeat:initial initial;
  border-bottom-color:#DFDFDF;
  border-bottom-style:solid;
  border-bottom-width:1px;
  padding:3px 0;
  position:relative;
  padding:5px;
  border:1px solid #d9d9d9;
  border-bottom:none;
 }
 .mail-header h2{
	 margin:2px;
	 font-size:15px;
	 }
  .mail-header div{
	  font-size:11px;
	  padding:3px 0;
	   }	 
 .mais-body{
	 border:1px solid #d9d9d9;
	 min-height:100px;
	 padding:10px;
	 }
</style>

<form action="<?php echo base_url('index.php/mensagem/enviar')?>" method="post">
<input type="hidden" size="30" name="para" value="<?php echo $receptor?>" />
<input type="hidden" size="30" name="resposta" value="<?php echo $resposta?>" />

<div class="mail-controles">
<a href="javascript:history.go(-1)"><img src="<?php echo base_url()?>public/imagem/voltar-mail.png" /></a>

 <input type="image" src="<?php echo base_url()?>public/imagem/mail-enviar.png" />
</div>


<div class="mail-header">

<div>para/NI: <strong><?php echo $name?> /  <?php echo $receptor?></strong></div>
<div>assunto: <input class="validate[required]" type="text" size="60" name="assunto" value="<?php echo $assunto?>" /></div>

</div>
<div class="mais-body">
<textarea class="validate[required]" name="mensagem" style="width:100%; min-height:200px; outline:none;"></textarea>
</div>
</form>
