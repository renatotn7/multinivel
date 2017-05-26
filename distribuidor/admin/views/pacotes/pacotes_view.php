<?php $this->lang->load('distribuidor/pacotes/pacotes_view');?>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3><?php echo $this->lang->line('title_page3'); ?></h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <?php
                    $pacotes = $this->db
                        ->join('planos','pa_id=co_id_plano')
                        ->where('co_id_distribuidor',get_user()->di_id)
                        ->order_by('co_data_compra','DESC')
                        ->get('compras')->result();
                    ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr bgcolor="#f7f7f7">
                                <th><?php echo $this->lang->line('title_col_numero'); ?></th>
                                <th><?php echo $this->lang->line('title_col_contas'); ?></th>
                                <th><?php echo $this->lang->line('title_col_situacao'); ?></th>
                                <th><?php echo $this->lang->line('title_col_data'); ?></th>
                                <?php /* ?>
                                <th><?php echo $this->lang->line('title_col_acao'); ?></th>
                                <?php */ ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($pacotes as $p){ ?>
                            <tr>
                                <td>
                                    <span class="label <?php echo $p->co_pago==1?'label-success':'label-danger'?>">
                                        <?php echo $p->co_id?>
                                    </span>
                                </td>
                                <td>
                                    <span class="label <?php echo $p->co_pago==1?'label-success':'label-danger'?>">
                                        <?php echo $p->pa_descricao?>
                                    </span>
                                </td>
                                <td><span class="label <?php echo $p->co_pago==1?'label-success':'label-danger'?>"><?php echo $p->co_pago==1?'ATIVO':'INATIVO'?></span></td>
                                <td><span class="label <?php echo $p->co_pago==1?'label-success':'label-danger'?>"><?php echo date('d/m/Y H:i:s',strtotime($p->co_data_compra)) ?></span></td>
                                <?php /* ?>
                                <td>
                                <?php if($p->co_pago==0){?>
                                    <a class="btn btn-info" href="<?php echo base_url('index.php/loja/pagamento?c='.$p->co_id)?>"><?php echo $this->lang->line('label_btn_pagar'); ?></a>
                                <?php }?>
                                </td>
                                <?php */ ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>