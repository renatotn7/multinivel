<?php $this->lang->load('distribuidor/loja/opcao_pagamento_view'); ?>
<div class="box-content min-height">
    <div class="box-content-header"><?php echo $this->lang->line('label_opcao_pagamento'); ?></div>
    <div class="box-content-body">
        
        <div class="row">
            <ul class="thumbnails" style="margin-left: 0px;">
                <li class="span4">
                    <div class="thumbnail" style="text-align: center;">
                        <legend><strong><?php echo $this->lang->line('label_empresa'); ?></strong></legend>
                        
                        <p><?php echo $this->lang->line('label_descricao_empresa_pay'); ?></p>
                        <a href="<?php echo base_url('index.php/bonus/pagar_pedido'); ?>" class="btn"><?php echo $this->lang->line('label_opcao_pagamento_empresa'); ?></a>
                    </div>        
                </li>
<!--                <li class="span4">
                    <div class="thumbnail" style="text-align: center;">
                        <legend><strong><?php echo $this->lang->line('label_bonus'); ?></strong></legend>
                        <p><?php echo $this->lang->line('label_descricao_bonus'); ?></p>
                        <a href="<?php echo base_url('index.php/bonus/pagar_parcelas_em_aberto'); ?>" class="btn"><?php echo $this->lang->line('label_opcao_pagamento_bonus'); ?></a>
                    </div>        
                </li>-->
            </ul>
            
        </div>
        
    </div>
</div>
