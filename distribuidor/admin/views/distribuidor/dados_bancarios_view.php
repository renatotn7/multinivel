<div class="clearfix"></div>
<?php
$this->lang->load ( 'distribuidor/distribuidor/dados_bancarios_view');
$distribuidor = $this->db->where('di_id',get_user()->di_id)->get('distribuidores')->row();
?>
<?php /* ?>
<p id="subtitulo">
  <?php echo $this->lang->line('label_subtitulo');?>
</p>
<form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_conta_empresa')?>">
    <div class="alert alert-warning">
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
</form>
<?php */ ?>
<div class="row" id="form-editavel">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h4><?php echo $this->lang->line('label_conta_bancaria_um');?></h4>
        <form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_info_banco')?>">
            <input type="hidden" name="url" value="<?php echo current_url()?>" />
            <div class="form-group">
                <?php echo $this->lang->line('label_banco');?>

                <input type="text"  name="di_conta_banco" value="<?php echo $distribuidor->di_conta_banco ?>"  class="form-control" />


            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_agencia');?>
                <input type="text"  name="di_conta_agencia" value="<?php echo $distribuidor->di_conta_agencia?>"  class="form-control" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_numero_conta');?>
                <input type="text" name="di_conta_numero" value="<?php echo $distribuidor->di_conta_numero?>"  class="form-control" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_tipo_conta');?>
                <select name="di_conta_tipo" class="form-control" >
                    <option <?php echo $distribuidor->di_conta_tipo==1?'selected':''?> value="1"><?php echo $this->lang->line('label_conta_corrente');?></option>
                    <option <?php echo $distribuidor->di_conta_tipo==0?'selected':''?> value="0"><?php echo $this->lang->line('label_conta_poupanca');?></option>
                </select>
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_tipo_operacao');?>
                <input type="text" name="di_conta_operacao" value="<?php echo $distribuidor->di_conta_operacao;?>" class="form-control" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_cpf_titular_conta');?>
                <input type="text" disabled="disabled" size="60" name="di_conta_cpf" value="<?php echo $distribuidor->di_rg ?>"  class="form-control" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_nome_titular_conta');?>
                <input type="text" disabled="disabled" size="60" name="di_conta_nome" value="<?php echo $distribuidor->di_nome . " " . $distribuidor->di_ultimo_nome ?>"  class="form-control" />
            </div>
            <div class="alert alert-warning">
                <?php echo $this->lang->line('notificacao_senha_seguranca');?>
                <input type="password" class="validate[required] form-control" name="senha_segurancao" />
            </div>
            <?php if($distribuidor->di_conta_verificada==1&&$distribuidor->di_contrato==1){ ?>
                <p class="label label-info"><?php echo $this->lang->line('label_infor');?></p><br />
                <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('label_editar_dados');?></button>
            <?php }else{ ?>
                <button class="btn btn-success" type="submit"><?php echo $this->lang->line('label_salvar_dados');?></button>
            <?php } ?>
        </form>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <h4><?php echo $this->lang->line('label_conta_bancaria_dois');?></h4>
        <form id="form1" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_info_banco_alternativo')?>">
            <input type="hidden" name="url" value="<?php echo current_url()?>" />
            <div class="form-group">
                <?php echo $this->lang->line('label_banco');?>

                <input type="text"  name="di_conta_banco2" value="<?php echo $distribuidor->di_conta_banco2 ?>"  class="form-control" />


            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_agencia');?>
                <input type="text"  name="di_conta_agencia2" value="<?php echo $distribuidor->di_conta_agencia2?>"  class="form-control" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_numero_conta');?>
                <input type="text" name="di_conta_numero2" value="<?php echo $distribuidor->di_conta_numero2?>"  class="form-control" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_tipo_conta');?>
                <select name="di_conta_tipo2" class="form-control" >
                    <option <?php echo $distribuidor->di_conta_tipo2==1?'selected':''?> value="1"><?php echo $this->lang->line('label_conta_corrente');?></option>
                    <option <?php echo $distribuidor->di_conta_tipo2==0?'selected':''?> value="0"><?php echo $this->lang->line('label_conta_poupanca');?></option>
                </select>
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_tipo_operacao');?>
                <input type="text" name="di_conta_operacao2" value="<?php echo $distribuidor->di_conta_operacao2;?>" class="form-control" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_cpf_titular_conta');?>
                <input type="text" class="mcpf form-control" disabled="disabled" size="60" name="di_conta_cpf2" value="<?php echo $distribuidor->di_rg?>" />
            </div>
            <div class="form-group">
                <?php echo $this->lang->line('label_nometitular_conta');?>
                <input type="text" size="60" disabled="disabled" name="di_conta_titular2" value="<?php echo $distribuidor->di_nome . " " . $distribuidor->di_ultimo_nome?>"  class="form-control" />
            </div>
            <div class="alert alert-warning">
                <?php echo $this->lang->line('notificacao_senha_seguranca');?>
                <input type="password" class="validate[required] form-control" name="senha_segurancao" />
            </div>
            <?php if($distribuidor->di_conta_verificada==1&&$distribuidor->di_contrato==1){?>
                <p class="label label-info"><?php echo $this->lang->line('label_infor2');?></p>
                <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('label_editar_dados');?></button>
            <?php }else{?>
                <button class="btn btn-success" type="submit"><?php echo $this->lang->line('label_salvar_dados');?></button>
            <?php }?>
        </form>
    </div>
</div>
