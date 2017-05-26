<?php
$this->lang->load('distribuidor/notificacao/notificacao_senhas_iguais');
?>
<div class="row in" id="texto">
    <div class="span">
        <h3>
            <?php echo $this->lang->line('senhas_nao_podem_iguais'); ?>
        </h3>
        <div class="alert">
            <?php echo $this->lang->line('altere_senha'); ?>
        </div>
        <a href="<?php echo base_url('index.php/distribuidor/mudar_senha2') ?>"><?php echo $this->lang->line('clique_alterar'); ?></a><br>

    </div>
</div>