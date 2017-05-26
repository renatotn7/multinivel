<?php 
$this->lang->load ( 'distribuidor/distribuidor/dados_bancarios_view');
$distribuidor = $this->db->where('di_id',get_user()->di_id)->get('distribuidores')->row();
?>

<table width="800px" cellpadding="0" cellspacing="0" border="0">
  <tr>
   <td  align="left">
    <p id="subtitulo">
    <?php echo $this->lang->line('label_subtitulo');?>
    </p>
   </td>
  </tr>
</table>
<form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_conta_empresa')?>">

<table>
  <tr>
    <td>
    <div class="alert">
        <p style="color:#000;"><?php echo $this->lang->line('notification_e_pay');?></p>
        <h4><?php echo $distribuidor->di_email;?></h4>
        <!--<input disabled="true" type="text" class="validar-atm" name="di_email_atm" id="di_email" value="<?php echo $distribuidor->di_email_atm;?>">-->
             <span class="status" style="display: none"><?php echo $this->lang->line('label_aguarde_verificando_informacao');?></span>
         <br>
         <p style="color:#000;"> <?php echo $this->lang->line('label_plataform');?></p>
         <h4><?php echo $distribuidor->di_niv;?></h4>
        <!--<input readonly="true" type="text" name="di_niv" id="di_niv" value="<?php // echo $distribuidor->di_niv;?>">-->
	    <br>
	    <!--<button type="submit" class="btn btn-success"><?php //echo $this->lang->line('label_salvar_dados');?></button>-->
            <p style="color:#000;"> <?php echo $this->lang->line('label_plataform_cadastro');?>
                <a  style="color:red;" target="_blank" href="<?php echo ConfigSingleton::getValue("url_plataforma_pagamento").'/painel'; ?>" ><?php echo $this->lang->line('label_plataform_cadastro__cadastre_se');?></a>
            </p>
  </div> 
    </td>
  </tr>
 
</table>

</form>
   
   <table style="display: none;" id="form-editavel" width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
       <td width="45%" valign="top">
        <strong style="font-size:15px;color:#333333;">  <?php echo $this->lang->line('label_conta_bancaria_um');?></strong>
        <form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_info_banco')?>">
       
      <input type="hidden" name="url" value="<?php echo current_url()?>" />
       <div>
      <?php echo $this->lang->line('label_banco');?>:<br />
       <select name="di_conta_banco">
        <option <?php echo $distribuidor->di_conta_banco=='001 - Banco do Brasil'?'selected':''?>>001 - Banco do Brasil</option>
        <option <?php echo $distribuidor->di_conta_banco=='237 - Bradesco'?'selected':''?>>237 - Bradesco</option>
        <option <?php echo $distribuidor->di_conta_banco=='104 - Caixa Econômica Federal'?'selected':''?>>104 - Caixa Econômica Federal</option>
        <option <?php echo $distribuidor->di_conta_banco=='745 - Citibank'?'selected':''?>>745 - Citibank</option>
        <option <?php echo $distribuidor->di_conta_banco=='399 - HSBC'?'selected':''?>>399 - HSBC</option>
        <option <?php echo $distribuidor->di_conta_banco=='341 - Itaú'?'selected':''?>>341 - Itaú</option>
        <option <?php echo $distribuidor->di_conta_banco=='033 - Santander'?'selected':''?>>033 - Santander</option>
       </select>
       </div>
    <div><?php echo $this->lang->line('label_agencia');?> <br /><input type="text"  name="di_conta_agencia" value="<?php echo $distribuidor->di_conta_agencia?>" /><br />
    <?php echo $this->lang->line('label_numero_conta');?><br /> <input type="text" name="di_conta_numero" value="<?php echo $distribuidor->di_conta_numero?>" /> </div>

    <div><?php echo $this->lang->line('label_tipo_conta');?> <br />
     <select name="di_conta_tipo">
      <option <?php echo $distribuidor->di_conta_tipo==1?'selected':''?> value="1"><?php echo $this->lang->line('label_conta_corrente');?></option>
      <option <?php echo $distribuidor->di_conta_tipo==0?'selected':''?> value="0"><?php echo $this->lang->line('label_conta_poupanca');?></option>
     </select>
    </div>
    <div><?php echo $this->lang->line('label_tipo_operacao');?> <br />
     <input type="text" name="di_conta_operacao" value="<?php echo $distribuidor->di_conta_operacao;?>">
    </div>
    <div><?php echo $this->lang->line('label_cpf_titular_conta');?><br /><input type="text" disabled="disabled" size="60" name="di_conta_cpf" value="<?php echo $distribuidor->di_cpf?>" /></div>
    
    <div><?php echo $this->lang->line('label_nome_titular_conta');?><br /><input type="text" disabled="disabled" size="60" name="di_conta_nome" value="<?php echo $distribuidor->di_nome?>" /></div>

<div class="alert" style="margin-left:0;">
<?php echo $this->lang->line('notificacao_senha_seguranca');?>
<input type="password" class="validate[required]" name="senha_segurancao" />
</div>

<?php if($distribuidor->di_conta_verificada==1&&$distribuidor->di_contrato==1){?>
<p class="label label-info"><?php echo $this->lang->line('label_infor');?></p><br />
<button class="btn btn-primary" type="submit"><?php echo $this->lang->line('label_editar_dados');?></button>
<?php }else{?>
<button class="btn btn-success" type="submit"><?php echo $this->lang->line('label_salvar_dados');?></button>
<?php }?>
</form>          
       </td>
       <td width="55%"  valign="top">
<strong style="font-size:15px;color:#333333;"><?php echo $this->lang->line('label_conta_bancaria_dois');?></strong>
<form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_info_banco_alternativo')?>">

<input type="hidden" name="url" value="<?php echo current_url()?>" />
       <div>
       <?php echo $this->lang->line('label_banco');?>:<br />
       <select name="di_conta_banco2">
        <option <?php echo $distribuidor->di_conta_banco2=='001 - Banco do Brasil'?'selected':''?>>001 - Banco do Brasil</option>
        <option <?php echo $distribuidor->di_conta_banco2=='237 - Bradesco'?'selected':''?>>237 - Bradesco</option>
        <option <?php echo $distribuidor->di_conta_banco2=='104 - Caixa Econômica Federal'?'selected':''?>>104 - Caixa Econômica Federal</option>
        <option <?php echo $distribuidor->di_conta_banco2=='745 - Citibank'?'selected':''?>>745 - Citibank</option>
        <option <?php echo $distribuidor->di_conta_banco2=='399 - HSBC'?'selected':''?>>399 - HSBC</option>
        <option <?php echo $distribuidor->di_conta_banco2=='341 - Itaú'?'selected':''?>>341 - Itaú</option>
        <option <?php echo $distribuidor->di_conta_banco2=='033 - Santander'?'selected':''?>>033 - Santander</option>
       </select>
       </div>

    <div> <?php echo $this->lang->line('label_agencia');?> <br /><input type="text"  name="di_conta_agencia2" value="<?php echo $distribuidor->di_conta_agencia2?>" /><br />
      <?php echo $this->lang->line('label_numero_conta');?><br /> <input type="text" name="di_conta_numero2" value="<?php echo $distribuidor->di_conta_numero2?>" /> </div>

    <div><?php echo $this->lang->line('label_tipo_conta');?> <br />
     <select name="di_conta_tipo2">
      <option <?php echo $distribuidor->di_conta_tipo2==1?'selected':''?> value="1"><?php echo $this->lang->line('label_conta_corrente');?></option>
      <option <?php echo $distribuidor->di_conta_tipo2==0?'selected':''?> value="0"><?php echo $this->lang->line('label_conta_poupanca');?></option>
     </select>
    </div>
    <div><?php echo $this->lang->line('label_tipo_operacao');?> <br />
     <input type="text" name="di_conta_operacao2" value="<?php echo $distribuidor->di_conta_operacao2;?>">
    </div>
    <div><?php echo $this->lang->line('label_cpf_titular_conta');?> <br /><input type="text" class="mcpf" disabled="disabled" size="60" name="di_conta_cpf2" value="<?php echo $distribuidor->di_cpf?>" /></div>
    <div><?php echo $this->lang->line('label_nometitular_conta');?> <br /><input type="text" size="60" disabled="disabled" name="di_conta_titular2" value="<?php echo $distribuidor->di_nome?>" /></div>

<div class="alert" style="margin-left:0;">
<?php echo $this->lang->line('notificacao_senha_seguranca');?> 
<input type="password" class="validate[required]" name="senha_segurancao" />
</div>

<?php if($distribuidor->di_conta_verificada==1&&$distribuidor->di_contrato==1){?>
<p class="label label-info"><?php echo $this->lang->line('label_infor2');?></p><br />
<button class="btn btn-primary" type="submit"><?php echo $this->lang->line('label_editar_dados');?> </button>
<?php }else{?>
<button class="btn btn-success" type="submit"><?php echo $this->lang->line('label_salvar_dados');?> </button>
<?php }?>
</form>          
   </td>
     </tr>
   </table>







