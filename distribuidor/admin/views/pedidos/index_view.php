<?php $this->lang->load('distribuidor/pedidos/index_view'); ?>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3><?php echo $this->lang->line('label_titulo'); ?></h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <?php
                    $para_compra = isset($_GET['para']) && $_GET['para'] == 'rede' ? "rede" : "min";
                    ?>
                    <form name="formulario" method="get" action="" class="form-inline">
                        <div class="form-group">
                            <label for="situacao"><?php echo $this->lang->line('label_situacao'); ?>:</label>
                            <select name="situacao" id="situacao" class="form-control">
                                <option value="">--<?php echo $this->lang->line('label_indiferente'); ?>--</option>
                                <?php
                                $situacao_rs = $this->db->where_in('st_id',array(7,8,9,10,11,14,15))->get('compra_situacao')->result();
                                foreach ($situacao_rs as $s) {
                                    ?>
                                    <option <?php echo $situacao === $s->st_id ? "selected" : "" ?> value="<?php echo $s->st_id ?>"><?php echo $s->st_descricao ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-default" value="<?php echo $this->lang->line('label_filtrar'); ?>" />
                        </div>
                    </form>
                    <table id="table-listagem" class="table table-hover table-bordered">
                        <thead>
                            <tr bgcolor="#f7f7f7">
                                <th><?php echo $this->lang->line('label_dados'); ?></th>
                                <th><?php echo $this->lang->line('label_numero'); ?></th>
                                <th><?php echo $this->lang->line('label_data'); ?></th>
                                <th><?php echo $this->lang->line('label_pt'); ?></th>
                                <th><?php echo $this->lang->line('label_valor'); ?></th>
                                <th><?php echo $this->lang->line('label_form_pgt'); ?></th>
                                <th><?php echo $this->lang->line('label_situcao'); ?></th>
                                <th><?php echo $this->lang->line('label_dados_logistica'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($entrega !== FALSE) {
                            $this->db->where('co_entrega', $entrega);
                        }
                        if ($situacao !== FALSE) {
                            $this->db->where('co_situacao', $situacao);
                        }

                        if ($para_compra == 'rede') {
                            $this->db->where('co_id_comprou', get_user()->di_id);
                        } else {
                            $this->db->where('co_id_distribuidor', get_user()->di_id);
                        }

                        $inicio = $this->uri->segment(3) ? $this->uri->segment(3) : 0;

                        $com = $this->db
                                        ->where('co_situacao <>', -1)
                                        ->order_by('co_id', 'DESC')
                                        ->join('compra_situacao', 'co_situacao=st_id')
                                        ->join('distribuidores', 'di_id=co_id_distribuidor')
                                        ->get('compras', $per_page, $inicio)->result();


                        foreach ($com as $c) {
                            ?>
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <a target="_blank" class="btn btn-default" href="<?php echo base_url("index.php/pedidos/pedido_imprimir/$c->co_id") ?>">
                                            <i class="fa fa-search"></i> <?php echo $this->lang->line('label_ver'); ?>
                                        </a>
                                        <?php if ($c->co_pago == 0) { ?>
                                            <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" style="padding-bottom: 12px;" href="#">
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="<?php echo base_url('index.php/pedidos/confirmar_pagamento?type=&id_pedido=' . $c->co_id) ?>">
                                                        <i class="fa fa-tags"></i>
                                                        <?php echo $this->lang->line('label_pagar'); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="label <?php echo $c->co_pago == 1 ? "label-success" : "label-danger" ?> ">
                                        <?php echo $c->co_id ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="label <?php echo $c->co_pago == 1 ? "label-success" : "label-danger" ?> ">
                                        <?php echo date('d/m/Y', strtotime($c->co_data_compra)) ?>
                                    </span>
                                </td>
                                <td><?php echo $c->co_total_pontos ?></td>
                                <td>US$ <?php echo number_format($c->co_total_valor + $c->co_frete_valor, 2, ',', '.') ?></td>
                                <td><?php //echo $c->co_pago == 1 ? "Paga com " : "" ?><?php echo $c->co_forma_pgt_txt ?>
                                </td>
                                <td>
                                    <?php
                                    if(count(ComprasModel::parcelas_pendentes(get_user()))==0){?>
                                    <?php echo $c->st_descricao ?>
                                    <?php }else{
                                     echo $this->lang->line('label_aguardando_quitar_parcelas');
                                    }?>
                                </td>
                                <td>
                                    <?php if (!empty($c->co_frete_codigo)) { ?>
                                    <ul>
                                        <li>
                                            <strong><?php echo $this->lang->line('label_codigo_rastreio'); ?></strong>:
                                            <?php echo $c->co_frete_codigo; ?>
                                        </li>
                                        <li>
                                            <strong> <?php echo $this->lang->line('label_transportadora'); ?></strong>:
                                            <?php echo $c->co_frete_transportadora; ?>
                                        </li>
                                        <li>
                                            <strong> <?php echo $this->lang->line('label_link_transportadora'); ?></strong>:
                                            <a href="<?php echo $c->co_frete_link_transportadora; ?>" target="_blank"><?php echo $c->co_frete_link_transportadora; ?></a>
                                        </li>
                                    </ul>
                                    <?php
                                    } else {
                                        echo $this->lang->line('texto_codigo_envio');
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="100%"><?php echo $links; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>