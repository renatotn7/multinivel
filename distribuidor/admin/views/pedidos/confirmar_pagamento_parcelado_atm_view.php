<?php
$this->lang->load('distribuidor/pedidos/confirmar_pagamento');

$parcelas = ComprasModel::parcelas_pendentes(get_user(),(isset($_REQUEST['c'])?$_REQUEST['c']:''));
$usuario  = ComprasModel::get_usuario_da_compra($parcelas[0]->cof_id_compra);

?>
<style type="text/css">
    .top-pedido{
        float: left;
        margin-top: -60px;
        margin-left: 41px;
        width: 234px;
        background: #FFF;
        padding-left: 13px;
        -webkit-border-bottom-right-radius: 5px;
        -webkit-border-bottom-left-radius: 5px;
        -moz-border-radius-bottomright: 5px;
        -moz-border-radius-bottomleft: 5px;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 5px;
    }
    .blok1{
        width: 541px;
        margin-left: -58px;
        position: absolute;
    }

    .blok2{
        width: 400px;
        margin-left: 0px;
        background: #FFF;
        position: absolute;
        right: 12px;
        top: 12px;
    }

    .table{
        font-size: 11px;
    }

</style>
<div class="box-content min-height">
    <div class="box-content-header"><?php echo $this->lang->line('label_comfirmar_pagmento_pedido'); ?></div>
    <div class="box-content-body">
        <div class="panel">
            <?php if (count($parcelas) == 0) { ?>
                <p class="alert alert-info"><?php echo $this->lang->line('label_nenhum_pedido_encontrado'); ?></p>
                <?php
                exit();
            }
            ?>
            <div class="hero-unit" style="overflow: hidden; height: 171px;width: 430px;">
                <div class="blok1">
                    <div class="top-pedido">
                        <h3><?php echo $this->lang->line('label_num_pedido');?>: <?php echo $parcelas[0]->cof_id_compra ?></h3>
                    </div>
                       <hr/>
                    <address>
                        <?php echo $this->lang->line('label_nome');?>:<?php echo $usuario->di_nome ?> (<?php echo $usuario->di_usuario ?>)<br/>
                        <?php echo $this->lang->line('label_agencia');?>: <?php echo  DistribuidorDAO::getPlano($usuario->di_id)->pa_descricao;?><br/>
                        
                         <br/>
                          <?php echo $usuario->di_endereco ?><br />
                          <?php echo $usuario->di_bairro ?>, <?php echo $this->lang->line('label_cep');?>: <?php echo $usuario->di_cep ?><br />
                          <?php echo $usuario->di_nome ?>-<?php echo $usuario->di_uf ?><br /> 
                    </address>

                        
                </div>
                <div class="blok2">
                    <h5 style="margin-left: 9px;"><?php echo $this->lang->line('label_resulmo_compra'); ?></h5>
                    <table class="table">
                        <tr>
                            <td><strong><?php echo $this->lang->line('label_descricao'); ?></strong></td>
                            <td><strong><?php echo $this->lang->line('label_valor_parcelas'); ?></strong></td>
                        </tr>
                        <?php
                        $total = 0;
                        foreach ($parcelas as $parcela) {
                            ?>
                            <tr>
                                <td><?php echo $this->lang->line('label_numero_parcelas'); ?> <?php echo $parcela->cof_numero_parcela; ?>º
                                   <?php echo $this->lang->line('label_data_vencimento'); ?>:<?php echo date('d/m/Y', strtotime($parcela->cof_data_vencimento)); ?></td>
                                <td>US$ <?php echo $parcela->cof_valor; ?></td>
                            </tr>
                            <?php
                            $total +=$parcela->cof_valor;
                        }
                        ?>
                        <tr style="font-size: 19px;">
                            <td style="text-align: right"><strong> <?php echo $this->lang->line('label_valor_total'); ?>:</strong></td>
                            <td><strong>US$: <?php echo $total; ?></strong></td>
                        </tr>
                    </table>         
                </div>
            </div>
              
            <form style="margin-left: 59%;
                  position: relative;
                  width: 324PX;
                  margin-top: -39PX;
                  " name="form1" method="post" action="<?php echo base_url('index.php/bonus/pagar_parcelas_em_aberto_plataform'); ?>">
                <?php echo $this->lang->line('label_senha_seguranca'); ?>:<br>
                <?php if(isset( $_REQUEST['c']) && !empty( $_REQUEST['c'])){?>
                 <input type="hidden" id="c" name="c" value="<?php echo $_REQUEST['c'];?>" >
                <?php }?>
                <input type="password" name="senha" />
                <br>
                <button class="btn btn-large btn-success" type="submit"> 
                    <?php echo $this->lang->line('label_confirmar_pagamento'); ?>
                </button>
                <a class="btn" href="<?php echo base_url() ?>">
                    <?php echo $this->lang->line('label_cancelar'); ?>
                </a>
            </form>
        </div> 
    </div>
</div> 