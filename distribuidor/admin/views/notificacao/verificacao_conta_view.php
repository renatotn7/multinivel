<?php 
$this->lang->load('distribuidor/notificacao/verificacao_conta_view');
?>
<div class="row in" id="texto">
    <div class="span">
        <b style='color:#666'>
            <hr>
             <?php echo $this->lang->line('informacao_regulamento'); ?>
            <hr>
        </b>
        <div style='font-size:22px; color:#f00;'>
             <?php echo $this->lang->line('informacao_regulamento_part1'); ?> <?php echo BonusPerdido::getDataLimiteBrasil()?> 
            <?php echo $this->lang->line('informacao_regulamento_part2'); ?>
        </div>
        <hr>
        <a class="btn btn-primary" href="<?php echo base_url() ?>index.php/distribuidor/verificar_conta"><?php echo $this->lang->line('btn_enviar_documentacao'); ?></a>
        <a class="btn btn-info" id="abreExplicacao"><?php echo $this->lang->line('informacao_nao_estou_conseguindo'); ?></a>
    </div>
</div>

<div class="row" id="formularioExplicacao" style="display: none;">
    <div class="span">
        <form name="form" id="form" method="post" action="<?php echo base_url('index.php/notificacao/enviar'); ?>">

            <input name="di_nome" type="hidden" value="<?php echo get_user()->di_nome ?>">

            <input name="di_usuario"  type="hidden" value="<?php echo get_user()->di_usuario ?>" >

            <input name="di_email" id="di_email" type="hidden" value="<?php echo get_user()->di_email ?>" >

            <input name="di_fone1" id="di_fone1" type="hidden" value="<?php echo get_user()->di_fone1 ?>" >

            <input name="di_fone2" id="di_fone2" type="hidden" value="<?php echo get_user()->di_fone2 ?>" >

            <div class="row">
                <div class="span10">
                    <?php echo $this->lang->line('lb_explicacao'); ?>:
                    <textarea id="di_explicacao" name="di_explicacao" rows="12" cols="" class="span10 validate[required]"></textarea>
                </div>
            </div>
            <div class="row">
                <span class="span">  <button class="btn btn-info" type="submit"><?php echo $this->lang->line('btn_enviar'); ?></button>
                    <button class="btn" id="fechaExplicacao" type="button"><?php echo $this->lang->line('btn_voltar'); ?></button></span>
            </div>

        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#abreExplicacao').click(
                function() {
                    $('#formularioExplicacao').show();
                }
        );
        $('#fechaExplicacao').click(
                function() {
                    $('#formularioExplicacao').hide();
                }
        );
    });

    $('#di_fone1').mask('?(99) 9999-9999');
    $('#di_fone2').mask('?(99) 9999-9999');

    jQuery("#form").validationEngine();
</script>