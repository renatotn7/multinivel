<?php $this->lang->load('distribuidor/distribuidor/rede_linear_view');?>

<link rel="stylesheet" href="<?php echo base_url("public/script/tree")?>/css/jquery.treeview.css" />
<script src="<?php echo base_url("public/script/tree")?>/js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo base_url("public/script/tree")?>/js/jquery.treeview.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		$("#tree").treeview({
			collapsed: true,
			animated: "medium",
			control:"#sidetreecontrol"
		});
	})
</script>

<div class="">
	<div class="page-title">
		<div class="title_left">
			<h3><?php echo $this->lang->line('label_meu_titulo'); ?></h3>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					<?php
					function tree_distribuidor($ni_pai,$primeiro){
						$ci =& get_instance();

						$dis = $ci->db->select(array('di_id','di_nome','di_ativo','di_usuario'))
							->join('distribuidor_ligacao','li_no=di_id')
							->where('di_ni_patrocinador',$ni_pai)
							->group_by('di_id')
							->get('distribuidores')
							->result();

						if(count($dis)){
							echo $primeiro==1?"<ul id='tree' class='filetree'>":"<ul>\n";
							$primeiro++;

							foreach($dis as $d){

								$ativo = 1;

								$ladoCadastro = $ci->db
									->where('li_no',get_user()->di_esquerda)
									->where('li_id_distribuidor',$d->di_id)
									->get('distribuidor_ligacao')->row();

								$lado = "";
								if(count($ladoCadastro) > 0){
									$lado = "Esquerdo";
								}else{
									$lado = "Direito";
								}

								echo "<li><span class='folder'>\n
									<a onClick='mostra_info(\"$d->di_id\")' class='".((count($ativo)>0)?"ativo":"inativo")."' href='javascript:void(0)'>{$d->di_nome} [{$d->di_usuario}]=>[{$lado}]</a></span>\n";
								tree_distribuidor($d->di_id,$primeiro);
								echo "</li>\n";
							}

							echo "</ul>\n";
						}
					}
					?>
					<?php if(!$mobile){ ?>
					<style>
						#arvore-distribuidor{
							overflow:auto;
							height:400px;
						}
					</style>
					<?php } ?>
					<div class="painel">
						<table width="100%" border="0" cellspacing="0" cellpadding="4">
							<tr>
								<td width="50%" valign="top" style="border-right:3px solid #f3f3f3;">
									<div id="arvore-distribuidor">
										<?php
										tree_distribuidor(get_user()->di_id,1);
										?>
									</div>
								</td>
								<td valign="top">
									<div id="arvore-info-distribuidor">
									</div>
								</td>
							</tr>
						</table>
					</div>
					<div class="btn-group">
						<a class="btn btn-default" href="javascript:history.go(-1)"><?php echo $this->lang->line('label_voltar');?></a>
					</div>
					<style>
						.ativo{
							color:#090 !important;
						}
						.inativo{
							color:#F00 !important;
						}
					</style>
					<script type="text/javascript">
						function mostra_info(id){
							$("#arvore-info-distribuidor").html("<img src='<?php echo base_url("public/script/tree/css/images/ajax-loader.gif")?>' />");
							$.ajax({
								url:"<?php echo base_url("index.php/distribuidor/distribuidor_info_ajax")?>",
								type:"POST",
								data:{ni:id},
								dataType:"json",
								success:function(dataJson){
									var txt = "<h4>"+dataJson.di_nome+" / "+dataJson.di_usuario+"</h4>";
									txt += "<table width='100%' border='0' cellspacing='0' cellpadding='3'>";
									txt += "<tr><td  width='100px' align='right'><strong><?php echo $this->lang->line('label_cidade_uf');?>:</strong></td><td>"+dataJson.ci_nome+"-"+dataJson.ci_uf+"</td></tr>";
									txt += "<tr><td align='right'><strong><?php echo $this->lang->line('label_telefone');?>:</strong></td><td>"+dataJson.di_fone1+"</td></tr>";
									txt += "<tr><td align='right'><strong><?php echo $this->lang->line('label_email');?>:</strong></td><td>"+dataJson.di_email+"</td></tr>";

									txt += "<tr><td align='right'><strong><?php echo $this->lang->line('label_dt_cad');?>:</strong></td><td>"+dataJson.di_data_cad+"</td></tr>";
									txt += "<tr><td align='right'></td></tr>";
									txt += "<tr><td align='right'><strong><?php echo $this->lang->line('label_situacao');?>:</strong></td><td>"+(dataJson.di_ativo==0?"<?php echo $this->lang->line('label_inativo');?>":"<?php echo $this->lang->line('label_ativo');?>Ativo")+"</td></tr>";
									txt += "</table>";
									$("#arvore-info-distribuidor").html(txt);
								},
								error:function(erro){
									$("#arvore-info-distribuidor").html("<div><?php echo $this->lang->line('notification_erro');?>.</div>");
								}
							});
						}

						function enviar_msg(){
							var msg = $("#txt-msg").val();
							var $assunto = $("#txt-assunto").val();
							var ni_msg = $("#ni-msg").val();

							if(msg==''){alert("<?php echo $this->lang->line('notification_escreva_mensagem');?>");return false;}
							$("#form-send-msg").html('<?php echo $this->lang->line('notification_enviando');?>');
							$.ajax({
								url:'<?php echo base_url('index.php/mensagem/enviar?ajax=sim')?>',
								type:'POST',
								data:{mensagem:msg,para:ni_msg,assunto:$assunto},
								success:function(){
									show_notificacao('<?php echo $this->lang->line('notification_mensagem_enviada');?>',1,'not1_msg');
									setTimeout("hide_notificacao('#not1_msg')",4000);
									$("#form-send-msg").html("<?php echo $this->lang->line('notification_mensagem_com_sucesso');?>");
								},
								error:function(){
									show_notificacao('<?php echo $this->lang->line('notification_erro_ao_enviar_mensagem');?>',0,'not1_msg');
									setTimeout("hide_notificacao('#not1_msg')",4000);
									$("#form-send-msg").html("");
								}
							});
						}

						</script>
				</div>
			</div>
		</div>
	</div>
</div>
