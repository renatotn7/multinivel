<div class="box-content" style="margin:20px;">
    <?php
    $this->lang->load('publico/distribuidor/distribuidor_cadastro_sucesso_view');
    ?>

    <div style="line-height:140%; color:#333; font-size:100%; padding:13px; background:#def3c9; padding: 86px 81px 62px;">
        <div class="alert alert-success">
            <strong><?php echo $d[0]->di_nome ?><?php echo $this->lang->line('info_cadastro_1'); ?></strong>
        </div>
        <div class="well">
            <h4>
                <?php echo $this->lang->line('info_usuario'); ?><strong style="color:#F00"><?php echo $d[0]->di_usuario ?></strong><br />
            </h4>
            <?php echo $this->lang->line('info_email'); ?><strong style="font-size:23px; color:#666;"><?php echo $d[0]->di_email ?></strong><?php echo $this->lang->line('info_email2'); ?>
            <br />
             <br>
            <br>
            <a class="btn g-btn type_green " href="<?php echo base_url('index.php/distribuidor/cadastro');?>" class="btn btn-primary"><?php echo $this->lang->line('label_voltar'); ?></a>
            <a class="btn g-btn type_green " href="<?php echo base_url();?>" class="btn btn-primary"><?php echo $this->lang->line('label_login'); ?></a>

        </div>
    </div>
