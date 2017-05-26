<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/css/layout.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>public/util/bootstrap/css/bootstrap.css" />
		<link rel="shortcut icon" href="<?php echo base_url()?>public/imagem/faficon.png" />
		<?php
        	$this->lang->load('publico/entrar/manutencao');
		?>
		<title><?php echo $this->lang->line('janela_titulo'); ?></title>
		<style>
			#ouro {
			position:absolute;
			right:-75px;
			top:452px;
			width:200px;
			}
			.radios{
			-moz-border-radius: 10px !important;
			-webkit-border-radius: 10px !important;
			-khtml-border-radius: 10px !important;
			border-radius: 10px !important;
			}
			.mask{
			left:-2px;
			position:absolute;
			top:-1px;
			z-index:10;	
			}
			.div{
			width:150px;position:relative;float:right;margin-right:40px; 	
			}
			.div img{border:none !important;}
			#infor-manutencao{
			width:750px;
			height:200px;
			margin:250px auto; 
			text-align:center;	
			}
			#infor-manutencao span{
			display:block;
			margin:5px 0 50px 0;
			color:#333;
			font-size:30px;
			text-align:center;	
			}
			#infor-manutencao{
			width:750px;
			height:200px;
			margin:100px auto; 
			text-align:center;	
			}
			#infor-manutencao span{
			display:block;
			margin:5px 0 0 0;
			color:#333;
			font-size:30px;
			text-align:center;	
			}
		</style>
	</head>
	<body>
		<div class="body">
			<div id="conteudo-login">
				<div id="infor-manutencao" style="display:none;">
					<span>
					<?php echo $this->lang->line('info_manutencao'); ?>
					</span>
					<a style="text-align:center;" href="http://Nossa Empresa.net/loja/"><img src="<?php echo base_url('public/imagem/layout/'.$this->lang->line('url_voltar').'');?>" />
					</a> 
				</div>
				<center>
					<img style="padding:10px 0px 0px 25px; height:150px;"  src="<?php echo base_url('public/imagem/layout/'.$this->lang->line('url_logo').'') ?>" />
				</center>
				<div id="infor-manutencao">
					<span><?php echo $this->lang->line('info_manutencao'); ?></span>
					<a style="text-align: center;" href="http://Nossa Empresa.net/loja/">
					<img src="<?php echo base_url()?>/public/imagem/layout/<?php echo base_url('public/imagem/layout/'.$this->lang->line('url_voltar').'') ?>" />
					</a>
				</div>
			</div>
		</div>
	</body>
</html>