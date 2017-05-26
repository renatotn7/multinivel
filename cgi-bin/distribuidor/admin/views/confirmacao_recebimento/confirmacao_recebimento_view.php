<?php $this->lang->load('distribuidor/confirmacao_recebimento/confirmacao_recebimento_view'); ?>
<?php
$nots = get_notificacao();

if (is_array($nots)) {
    foreach ($nots as $k => $n) {
        if ($n['tipo'] == 1) {
            ?>
            <div id="noti_<?php echo $k ?>" class="notificacao alert alert-success"><?php echo $n['mensagem'] ?></div>
            <script type="text/javascript">
                jQuery(function() {
                    setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                });</script>
        <?php } else if ($n['tipo'] == 2) { ?>
            <div id="noti_<?php echo $k ?>" class="notificacao alert alert-error"><?php echo $n['mensagem'] ?></div>
            <script type="text/javascript">
                jQuery(function() {
                    setTimeout('hide_notificacao("#noti_<?php echo $k ?>")', 6000)
                });</script>
            <?php
        }
    }
}
?>
<form name="form" id="form" method="POST" action="<?php echo base_url('index.php/confirmacao_recebimento/salvar_recebimento'); ?>">
    <h4><?php echo $this->lang->line('label_confirme_se_recebeu') ?>:</h4>
    <div class="row">
        <div class="span">
            <label class="radio">
                <input type="radio" name="forma_recebimento" id="optionsRadios1" value="1" checked>
                <?php echo $this->lang->line('produto_escolha_pin'); ?>
            </label>
            <label class="radio">
                <input type="radio" name="forma_recebimento" id="optionsRadios2" value="2">
                <?php echo $this->lang->line('produto_escolha_voucher'); ?>
            </label>
        </div>
    </div>
    <hr/>
    <button class="btn btn-success"><?php echo $this->lang->line('label_botao_salvar') ?></button><br/><br/>
    <div class="row">
        <div class="span">
            <strong>
                <?php echo $this->lang->line('notificacao_confirme_se_recebeu') ?> <br/><a href="<?php echo base_url('index.php/home/index?form_inconformidade_recebimento=sim'); ?>" class="btn btn-danger"><?php echo $this->lang->line('label_botao_nao_recebir_produto') ?></a>            
            </strong>
        </div>
    </div>
    
</form>
<script>
    function show_formulario_reclamacao() {
        if ($('#di_usuario').val() != "" && $('#di_email').val() != "") {
            $('#formulario_reclamacao').fadeIn(500);
        } else {
            $('#formulario_reclamacao').fadeOut(500);
        }
    }
</script>