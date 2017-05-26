<?php $this->lang->load('distribuidor/loja/opcao_pagamento_view'); ?>
<?php $compra = $this->db->where('co_id_distribuidor',  get_user()->di_id)
                         ->where('co_eplano',1)  
                         ->get('compras')->row(); ?>
<div class="box-content min-height">
    <div class="box-content-header"><?php echo $this->lang->line('label_opcao_pagamento'); ?></div>
    <div class="box-content-body">
        
        <div class="row">
            <ul class="thumbnails" style="margin-left: 0px;">
                <li class="span4">
                    <div class="thumbnail" style="text-align: center;">
                        <legend><strong><?php echo $this->lang->line('label_empresa'); ?></strong></legend>
                        
                        <p><?php echo $this->lang->line('label_descricao_empresa_pay'); ?></p>
                        <a href="<?php echo base_url('/index.php/pedidos/confirmar_pagamento_parcelado_atm'.(isset($_REQUEST['c'])?'?c='.$_REQUEST['c']:'')); ?>" class="btn"><?php echo $this->lang->line('label_opcao_pagamento_empresa'); ?></a>
                        <!--<a href="<?php // echo base_url('index.php/bonus/pagar_parcelas_em_aberto_plataform'); ?>" class="btn"><?php echo $this->lang->line('label_opcao_pagamento_empresa'); ?></a>-->
                    </div>        
                </li>
<!--                <li class="span4">
                    <div class="thumbnail" style="text-align: center;">
                        <legend><strong><?php // echo $this->lang->line('label_bonus'); ?></strong></legend>
                        <p><?php // echo $this->lang->line('label_descricao_bonus'); ?></p>
                        <a href="<?php // echo base_url('/index.php/pedidos/confirmar_pagamento_parcelado_bonus'.(isset($_REQUEST['c'])?'?c='.$_REQUEST['c']:'')); ?>" class="btn"><?php echo $this->lang->line('label_opcao_pagamento_bonus'); ?></a>
                        <a href="<?php // echo base_url('index.php/bonus/pagar_parcelas_em_aberto_bonus'); ?>" class="btn"><?php echo $this->lang->line('label_opcao_pagamento_bonus'); ?></a>
                    </div>        
                </li>-->
            </ul>
            
        </div>
        
    </div>
</div>
