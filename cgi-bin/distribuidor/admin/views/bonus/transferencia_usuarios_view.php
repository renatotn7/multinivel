<?php $this->lang->load('distribuidor/bonus/transferencia_usuarios_view');?>
<?php

$saldo = $this->db->query("
SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
WHERE cb_distribuidor = ".get_user()->di_id."
")->result();
 
?>
<?php 
if(get_user()->di_data_cad <='2014-04-01 00:00:00'){?>
<p>
<div class="label label-info"><?php  echo $this->lang->line('label_transferencia');?></div>
</p>
<div>-  <?php echo $this->lang->line('label_limit_transferencia');?> 
<?php 
 $minimo = $this->db->where('field', 'minimo_transferencia_user')->get('config')->row();
 echo $minimo->valor;
?>
</div>

<div > <?php echo str_replace('{numero}', '<b>'.  conf()->numero_transferencia_diaria.'.</b>', str_replace('{taxa}','<b>'.  conf()->taxa_transferecia_entre_usuario.'%</b>' , $string =$this->lang->line('notificaton_transferencia')));?> </div>

<hr />
<form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/bonus/transferir_usuario')?>">

    
<table width="100%" border="0" cellspacing="0" cellpadding="2">
   <tr align="right" class=" nome-completo hide">
     <td width="22%" colspan="2" >
     <h4 id="nome-completo" class="alert" style="width:278px; text-align: left;"></h4 >
     </td>
 </tr> 
  <tr>
    <td width="78%"> <?php echo $this->lang->line('label_seu_saldo_atual');?></td>
    <td width="22%"><?php echo $this->lang->line('label_us$');?> <?php echo number_format($saldo[0]->saldo,2,',','.')?></td>
  </tr>

  <tr>
    <td align="right" valign="top"> 
     
    <?php echo $this->lang->line('label_usuario_receber_bonus');?>:
    </td>
    <td>
    <input name="user" id="user-receber" style="width:100px" type="text">
    <button style="display:none;" class="btn" type="button" onclick="verificar_patrocinador()"><?php echo $this->lang->line('label_verificar');?></button>
    </td>
  </tr>

  <tr>
    <td align="right"><?php echo $this->lang->line('label_valor_transferencia');?>:</td>
    <td><input type="text" name="valor" style="width:100px; text-align:right; font-size:18px;" class="moeda" value="0,00" /></td>
  </tr>
  <tr>
    <td align="right"><?php echo $this->lang->line('label_senha_seguranca');?>:</td>
    <td><input type="password" name="senha" style="width:100px"  /></td>
  </tr>

  <tr>
    <td align="right"></td>
    <td><button class="btn btn-success" type="submit"><?php echo $this->lang->line('label_salvar_dados');?></button></td>
  </tr>  

  <!--fim da trava do binário quando não ta ativo--> 
</table>

</form>
<?php }else{?>
<p>
<div class="label label-info">
    <?php echo $this->lang->line('label_transferencia_entre_usuarios');?>.
</div>
</p>
<?php }?>

<script>

$('#user-receber').blur(function(){
	if($(this).val() !=""){
		
	 $.ajax({
		  url:'<?php echo base_url()?>index.php/bonus/get_nome_usuario/',
		  data:{di_usuario:$(this).val()},
		  type:'post',
		  dataType:'json',
		  success:function(json){
			  $('.nome-completo').addClass('in').removeClass('hide');
			  $('#nome-completo').html(json.infor);
		  }
		 });
	}
});


function verificar_patrocinador(){
	
	$usuario = $("#user-receber").val();
	
	$(".alert-patrocinador").remove();
	if($usuario.length>3){
	 $.ajax({
		  url:'<?php echo base_url()?>index.php/bonus/usuario_verificar/'+$usuario,
		  dataType:'json',
		  success:function($json){
			  if($json.e_titular=='nao'){
				$("#user-receber").focus();
				$("#user-receber").val("");
			    $(".box-user").after("<span class='alert-patrocinador label label-warning'><?php echo $this->lang->line('notification_nao_tem_vinculo_titularidade');?></span>");
			  }else{
			   if($json.di_nome!=''){
				    $(".box-user").after("<span class='alert-patrocinador label label-success'><strong>"+$json.di_nome+"</strong><br><?php echo $this->lang->line('notification_usuario_selecionado_sucesso');?></span>");
				   }else{
					   $("#user-receber").focus();
					   $("#user-receber").val("");
					   $(".box-user").after("<span class='alert-patrocinador label label-warning'><?php echo $this->lang->line('notification_usuario_nao_cadastrado');?></span>");
					   }
			   }
			  }
		 });
	 }
	}
</script>

