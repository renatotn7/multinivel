<?php
//Carregando o pacote de linguagem.
$this->lang->load('distribuidor/bonus/parcelas_view');

$parcelas = $this->db->where('co_id_distribuidor', get_user()->di_id)
                ->join('compras', 'co_id=cof_id_compra')
                ->get('compras_financiamento')->result();

$total_parcelas_pa = 0.00;
$total_parcelas_pe = 0.00;
?>

    <table width="100%" class="table table-bordered table-hover">
        <tr>
        <thead>
        <th><?php echo $this->lang->line('label_numero_parcela'); ?></th>
        <th><?php echo $this->lang->line('label_data_vencimento'); ?></th>
        <th><?php echo $this->lang->line('label_valor'); ?></th>
        <th><?php echo $this->lang->line('label_situacao'); ?></th>
        <th>Data do Pagamento</th>
        <th></th>
        </thead>
        </tr>
        <?php foreach ($parcelas as $parcela) { ?>
            <tr>
                <td><?php echo $parcela->cof_numero_parcela; ?></td>
                <td><?php echo date('d/m/Y', strtotime($parcela->cof_data_vencimento)); ?></td>
                <td><?php echo "US$: " . $parcela->cof_valor; ?></td>
                <td>
                    <?php
                    if($parcela->cof_situacao ==0){
                        echo "Em Aberto";
                    }
                    if($parcela->cof_situacao ==2){
                        echo "Em Atraso";
                    }
                    if($parcela->cof_situacao ==3){
                        echo "Pago";
                    }
                    ?>
                </td>
                <td><?php 
                 if($parcela->cof_pago ==1){
                     echo date('d/m/Y H:i:s',  strtotime($parcela->cof_data_pagamento));
                 }?></td>
                <td>
                   
                    <?php  if($parcela->cof_pago ==0){?>
                    <a href="<?php echo base_url('index.php/loja/opcao_pagamento_pacelado?c='.$parcela->cof_id);?>" class="btn btn-primary"><?php echo $this->lang->line('label_pagar'); ?></a>
                    <?php }?>
                </td>
            </tr>
            <?php
            $total_parcelas_pa += ($parcela->cof_pago == 1 ? $parcela->cof_valor : 0.00);
            $total_parcelas_pe += ($parcela->cof_pago == 0 ? $parcela->cof_valor : 0.00);
             }
            ?>
        <tr>
            <td colspan="2"><strong><?php echo $this->lang->line('label_total_pagas'); ?>:</strong></td>
            <td colspan="2"><strong>US$: <?php echo $total_parcelas_pa; ?></strong></td>
        </tr>
        <tr>
            <td colspan="2"><strong><?php echo $this->lang->line('label_total_pendentes'); ?>:</strong></td>
            <td colspan="2"><strong>US$: <?php echo $total_parcelas_pe; ?></strong></td>
        </tr>
     
    </table>
<br/>
   <?php if ($total_parcelas_pe) { ?>
    <div class="row">
        <div class="span3">
            <a href="<?php echo base_url('index.php/loja/opcao_pagamento_pacelado');?>" class="pull-right btn btn-primary"><?php echo $this->lang->line('label_pagar_parcelas_pententes'); ?></a>
        </div>
<!--        <div class="span9">
            <?php // echo $this->lang->line('label_descricao_pagar_todos'); ?>
        </div>-->
<!--        <div class="span10">
            <div class="alert" style="color: red;">
                 <?php // echo $this->lang->line('notificacao_pagar_todas_parcelas'); ?>
            </div>
        </div>-->
    </div>
     <?php } ?> 
    <br>

