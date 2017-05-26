<?php
$erros = isset($_SESSION['form_cad_error']) ? $_SESSION['form_cad_error'] : false;

function g_dados($key) {
    return isset($_SESSION['form_cad'][$key]) ? $_SESSION['form_cad'][$key] : '';
}

$this->lang->load('publico/distribuidor/distribuidor_confirmar_cadastro_view');
?>
<style>
    #localatendimento{
        display: none;
    }
    .estado{
        width: 287px
    }
    td p{
        margin-bottom:1px;
    }
    input[type='radio']{
        margin:0;
        padding:0;
    }	
    input[type='text'],select{
        margin-bottom:1px;
    }
    table{color:#004030;}
    fieldset{ 
        padding:6px;
    }
    .row-separator{
        display:block;
        padding:3px 0;
        margin:5px 0;
        color:#069;
        font-size:15px;
        border-bottom:1px dashed #ccc;
    }
    .indisponivel{
        font-size:14px;
        color:#F00 !important;
    }
    fieldset{
        width:590px;
        font-size:16px;
    }
    .alert-patrocinador{
        font-size:16px;
        font-weight:bold;
    }
    #condend{
        float: right;

    }
    #pais{
        position: absolute;
        right: 0px;
        top: -153px;
    }

    .no-select {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

</style>


<div class="box-content" style="margin:0px auto; width:1000px; position:relative;">
    <div id="pais" style="display:none;"> 
        <?php echo $this->lang->line('label_idioma'); ?><br>
        <?php
        echo CHtml::dropdow('idioma', array('pt' => 'Português', 'en' => 'Inglês'), array(
            'empty' => $this->lang->line('label_selecione'),
            'class' => "validate[required]",
            'onchange' => 'carregar_pagina_pais(this)',
            'selected' => isset($_SESSION['lang']) ? $_SESSION['lang'] : 'pt'
        ));
        ?>
    </div>
    <div style="line-height:140%; color:#333; font-size:100%; padding:13px; background:#def3c9;margin-top: 27PX;
     margin-bottom: 44PX; padding: 86px 81px 62px;">
        <?php if($sucess==1){?>
        <h4 class="alert alert-success"  style="text-align: center">
        <?php echo $this->lang->line('notificacao_sucesso'); ?><br>
        </h4>
        <div class="well" style="text-align: center">
            <span style="font-size: 16px;"><strong><?php echo $this->lang->line('label_nome'); ?>:</strong> <?php echo $distribuidor->di_nome; ?><br>
           <strong><?php echo $this->lang->line('label_usuario'); ?>:</strong> <?php echo $distribuidor->di_usuario; ?></span>
            <br>
            <br>
            <a class="btn g-btn type_green " href="<?php echo base_url();?>" class="btn btn-primary"><?php echo $this->lang->line('lable_login'); ?></a>
        </div>
      
        <?php }?>
        <?php
        if($sucess == '2'){?>
        <h4 class="alert alert-success"  style="text-align: center">
        <?php echo $this->lang->line('notificacao_ja_sucesso'); ?><br>
        </h4>
        <div class="well" style="text-align: center">
         <a href="<?php echo base_url();?>" class="btn btn-primary"><?php echo $this->lang->line('lable_login'); ?></a>
          </div>
        <?php }?>
        
        <?php if($sucess==0){?>
        <h4 class="alert alert-error" style="text-align: center">
        <?php echo $this->lang->line('notificacao_falha'); ?><br>
        </h4>
        <?php }?>
    </div>
</div>

