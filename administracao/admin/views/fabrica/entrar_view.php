<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Nossa Empresa - ENTAR</title>
<style>
 body{
	 background:#ddd;
	 margin:0;
	 padding:0;
	 }
 #login{
	 width:459px;
	 height:450px;
	 background:url(<?php echo base_url()?>public/imagem/bg-login.jpg) no-repeat;
	 margin:50px auto;
	 margin-bottom:0;
	 position:relative;
	 }
  input[type='text'],input[type='password']{
	  border:2px solid #CCC;
	  padding:4px 5px;
	  width:194px;
	  left:109px;
      position:absolute;
	  color:#999;
	  outline:none;
	  }	
  input[name='entrar1']{
      top:153px;
	  }	
	  
  input[name='entrar2']{
      top:192px;
	  }	
  input[type='image']{
	  position:absolute;
	  top:231px;
	  left:109px;
	  }	  	     	 
</style>
</head>

<body>

<div id="login">
  <form id="form1" name="form1" method="post" action="">
    <input type="text" value="E-mail" onfocus="if(this.value=='E-mail'){this.value=''}" onblur="if(this.value==''){this.value='E-mail'}" name="entrar1" />
    <input type="password" value="Senha" onfocus="if(this.value=='Senha'){this.value=''}" onblur="if(this.value==''){this.value='Senha'}" name="entrar2" />
    <input type="image" src="<?php echo base_url()?>public/imagem/entrar.png" id="button" />
  </form>
</div>


</body>
</html>