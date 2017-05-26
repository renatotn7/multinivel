<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/layout.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/util/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/script/validar/css/validationEngine.jquery.css" />
		<script src="<?php echo base_url()?>public/script/jquery.min.js" language="javascript" ></script>
		<script type="text/javascript" src="<?php echo base_url()?>public/script/validar/js/jquery.validationEngine.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>public/script/validar/js/languages/jquery.validationEngine-pt_BR.js"></script>
		<script type="text/javascript" src="<?php echo base_url()?>public/script/mascara.js"></script>
        <?php
        	$this->lang->load('publico/suporte/formulario_view');
		?>
		<title><?php echo $this->lang->line('janela_titulo'); ?></title>
	</head>
	<body>
		<div class="corpo">
			<div class="header" style="height: 155px;
				margin: 10px auto 0px;
				position: relative;
				width: 200px;">
				<img src="<?php echo base_url()?>public/imagem/layout/logomarca.png" />
			</div>
			<div class="box-content" style="margin:0 auto; width:460px;">
				<div class="box-content-header" style="color:#333;margin:0;padding:0;text-align: center;"><?php echo $this->lang->line('conteudo_titulo'); ?></div>
				<div class="box-content-body" style="background:none;border:none;padding:0;">
					<form id="form-suporte" name="form1" method="post" action="" style="width: 390px;margin: 10px auto;" >
						<?php if(isset($error)){?> 
						<div class="alert" style="position: absolute;
							top: 20px;
							width: 350px;"><?php echo $error?></div>
						<?php }?>
						<label><?php echo $this->lang->line('label_nome'); ?></label>
						<input class="input-login validate[required]" type="text" name="nome" data-prompt-position="topLeft:70" />
						<label><?php echo $this->lang->line('label_email'); ?></label>
						<input class="input-login validate[required,custom[email]]" type="text" data-prompt-position="topLeft:70" name="email" />
						<label><?php echo $this->lang->line('label_telefone'); ?></label>
						<input class="mtel input-login validate[required]" type="text" data-prompt-position="topLeft:70" name="telefone" />
						<label><?php echo $this->lang->line('label_assunto'); ?></label>
						<input class="input-login validate[required]" type="text" data-prompt-position="topLeft:70" name="assunto" />
						<label><?php echo $this->lang->line('label_mensagem'); ?></label>
						<textarea class="textarea-suporte validate[required]" name="mensagem" data-prompt-position="topLeft:70" ></textarea>
						<input name="submit-suporte" type="hidden" value="1" />
						<button type="submit" id="enviar-suporte" ><?php echo $this->lang->line('btn_enviar'); ?></button>
					</form>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(function(){
				
				 jQuery("form").validationEngine();
				
				 //class='validate[required]'
				 $(".alert").on('click',function(){
				 	$(this).slideUp('fast'); 
				 });
				 $(".mtel").mask("(99) 9999-9999");
				
				});
				 
			</script>
		</div>
	</body>
</html>