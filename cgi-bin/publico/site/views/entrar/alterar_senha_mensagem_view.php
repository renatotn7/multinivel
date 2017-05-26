<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/administracao.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/layout.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/util/bootstrap/css/bootstrap.css" />
		<?php
			$this->lang->load('publico/entrar/alterar_senha_mensagem_view');
			?>
		<title><?php echo $this->lang->line('titulo_janela');?></title>
	</head>
	<body>
		<div class="corpo">
			<div class="header" style="height: 155px;
				margin: 30px auto 20px;
				position: relative;
				width: 200px;">
				<img src="<?php echo base_url()?>public/imagem/layout/<?php echo $this->lang->line('url_logo');?>" />
			</div>
			<div class="box-content" style="margin:0 auto; width:380px;">
				<div class="box-content-header" style="color:#333;"><?php echo $this->lang->line('titulo_conteudo');?></div>
				<div class="box-content-body" style="background:none;border:none;">
					<div id="login">
						<form id="form-recuperar" name="form1" method="post" action="" style="border:2px solid #C49643;-webkit-border-radius: 8px;
							-moz-border-radius: 8px;
							border-radius: 8px;background:#FFF;">
							<?php if(isset($error)){?> 
							<div style="text-align:left; padding:5px; margin-bottom:4px; font-size:20px !important; width:285px; color:#f00; background:#666;" id="noti_" class="notificacao false"><?php echo $error?></div>
							<script type="text/javascript">
								jQuery(function(){setTimeout('hide_notificacao("#noti_")',6000)});
							</script>
							<?php }?>
							<br /><br />
							<label style="font-size:17px;">
							<?php echo $this->lang->line('label_sucesso'); ?>
							</label>
							<br />
							<a class="btn-login-recupera btn-site" href="<?php echo base_url('index.php/entrar');?>" style="font-size:12px;"><?php echo $this->lang->line('btn_login'); ?></a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>