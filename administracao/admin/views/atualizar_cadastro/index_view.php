<div class="box-content min-height">
    <div class="box-content-body">
        <div class="row">
            <div class="span">
                <a class="btn btn-primary" href="<?php echo base_url('index.php/atualizar_cadastro?run=1'); ?>">Atualizar Dados</a>
            </div>
        </div>
        <?php if ($this->input->get('run')) { ?>
             <h3>Não feche a página antes de concluír o Processo em andamento</h3>
            <br>
            <div class="progress progress-striped active">
                <div class="bar" style="width: 1%;"></div>
            </div>
            <iframe id="frame-dados" width="100%" frameborder="0" height="600px" src="<?php echo base_url('index.php/atualizar_cadastro/run'); ?>"></iframe>
        <?php } ?>
    </div>
</div>

