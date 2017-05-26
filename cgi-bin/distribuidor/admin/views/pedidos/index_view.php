<?php $this->lang->load('distribuidor/pedidos/index_view'); ?>

<div class="box-content min-height border-radios">
    <div class="box-content-header">
        <a href="<?php echo base_url() ?>"><?php echo $this->lang->line('label_titulo'); ?></a>
    </div>
    <div class="box-content-body">

        <?php
        $para_compra = isset($_GET['para']) && $_GET['para'] == 'rede' ? "rede" : "min";
        ?>


        <form name="formulario" method="get" action="">
            <?php echo $this->lang->line('label_situacao'); ?>:
            <div class="input-append">
                <select name="situacao">
                    <option value="">--<?php echo $this->lang->line('label_indiferente'); ?>--</option>
                    <?php
                    $situacao_rs = $this->db->where_in('st_id',array(7,8,9,10,11,14,15))->get('compra_situacao')->result();
                    foreach ($situacao_rs as $s) {
                        ?>
                        <option <?php echo $situacao === $s->st_id ? "selected" : "" ?> value="<?php echo $s->st_id ?>"><?php echo $s->st_descricao ?></option>
                    <?php } ?>
                </select>
                <input type="submit" class="btn btn-primary" value="<?php echo $this->lang->line('label_filtrar'); ?>" />
            </div>

        </form>
        <table id="table-listagem" class="table-hover" width="100%" border="0" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <td width="11%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_dados'); ?></strong></td>
                    <td width="4%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_numero'); ?></strong></td>
                    <td width="9%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_data'); ?></strong></td>
                    <td width="6%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_pt'); ?></strong></td>
                    <td width="8%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_valor'); ?></strong></td>
                    <td width="21%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_form_pgt'); ?></strong></td>
                    <td width="15%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_situcao'); ?></strong></td>
                    <td width="15%" bgcolor="#f7f7f7"><strong><?php echo $this->lang->line('label_dados_logistica'); ?></strong></td>
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
                        <td >
                            <div class="btn-group">
                                <a target="_blank" class="btn" href="<?php echo base_url("index.php/pedidos/pedido_imprimir/$c->co_id") ?>">
                                    <i class="icon-search"></i> <?php echo $this->lang->line('label_ver'); ?>
                                </a>
                                <?php if ($c->co_pago == 0) { ?>
                                    <a class="btn dropdown-toggle" data-toggle="dropdown" style="padding-bottom: 12px;" href="#"><span class="caret"></span></a>
                                <?php } ?>
                                <?php if ($c->co_pago == 0) { ?>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?php echo base_url('index.php/pedidos/confirmar_pagamento?type=&id_pedido=' . $c->co_id) ?>">
                                                <i class="icon-tags"></i> 
                                                <?php echo $this->lang->line('label_pagar'); ?>
                                            </a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </td>
                        <td width="4%">
                            <span class="label <?php echo $c->co_pago == 1 ? "label-success" : "label-important" ?> ">
                                <?php echo $c->co_id ?>
                            </span>
                        </td>
                        <td width="9%">
                            <span class="label <?php echo $c->co_pago == 1 ? "label-success" : "label-important" ?> ">
                                <?php echo date('d/m/Y', strtotime($c->co_data_compra)) ?>
                            </span>
                        </td>
                        <td width="8%"><?php echo $c->co_total_pontos ?></td>
                        <td width="11%">US$ <?php echo number_format($c->co_total_valor + $c->co_frete_valor, 2, ',', '.') ?></td>
                        <td width="21%"><?php echo $c->co_pago == 1 ? "Paga com " : "" ?><?php echo $c->co_forma_pgt_txt ?>
                        </td>
                        <td width="15%">
                            <?php
                            if(count(ComprasModel::parcelas_pendentes(get_user()))==0){?>
                            <?php echo $c->st_descricao ?>
                            <?php }else{
                             echo $this->lang->line('label_aguardando_quitar_parcelas');   
                            }?>
                        </td>
                        <td width="20%"><?php if (!empty($c->co_frete_codigo)) { ?>
                                <ul class="unstyled">
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
                            ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php echo $links ?>

    </div>

</div>