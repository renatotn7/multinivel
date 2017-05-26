<?php $this->lang->load('distribuidor/distribuidor/upgrade_view'); ?>
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3><?php echo $this->lang->line('label_titulo'); ?></h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php if (verificar_permissao_acesso(false)) { ?>
            <div class="alert alert-warning">
                <?php echo $this->lang->line('label_notificacao_bloqueio'); ?>
            </div>
            <?php
                exit;
            } ?>
        <?php
        $meuPlano = $this->db->where('co_pago', 1)
            ->where('co_id_distribuidor', get_user()->di_id)
            ->join('planos', 'pa_id=co_id_plano')
            ->order_by('co_id', 'desc')
            ->get('compras', 1)->row();

        //comprou mais não pagou nenhum o plano
        if (count($meuPlano) > 0) {
            //Pega o plano
            $planos = $this->db
                ->where('pa_id !=104')
                // ->where('pa_id >', $meuPlano->co_id_plano)
                ->where('pug_id_plano', $meuPlano->pa_id)
                ->where('pr_categoria', 9)
                ->join('planos_upgrades', 'pug_id_plano_upgrade=pa_id')
                ->join('produtos','pug_produto=pr_id')
                ->order_by('pa_valor', 'ASC')
                ->get('planos')->result();

            /*
             * Se o distribuidor for do plano fast ele só pode fazer
             * upgrade para o plano esmeralda.
             */

            // if($meuPlano->pa_id==100){
            //      $planos   = $this->db
            //         ->where('pa_id !=104')
            //         ->where('pa_id',101)
            //         ->order_by('pa_valor','ASC')
            //         ->get('planos')->result();
            // }

            /**
             * Se o distribuidor for do plano mambership
             * ele pode fazer upgrade so para o fast start
             */

            // if($meuPlano->pa_id==99){
            //      $planos   = $this->db
            //         ->where('pa_id !=104')
            //         ->where('pa_id',100)
            //         ->order_by('pa_valor','ASC')
            //         ->get('planos')->result();
            // }
            ?>
            <div class="x_panel">
                <div class="x_title">
                    <h2><?php echo $this->lang->line('label_plano_atual'); ?>: <strong><i><?php echo $meuPlano->pa_descricao ?></i></strong></h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form method="post" name="form" action="<?php echo base_url('index.php/distribuidor/salvar_upgrade_plano'); ?>">
                        <div class="row">
                            <?php
                            //Sabendo qual pais o usuário pertence
                            $pais = $this->db
                                ->where('ci_id', get_user()->di_cidade)
                                ->join('estados', 'ci_estado=es_id')
                                ->get('cidades')
                                ->row();

                            foreach ($planos as $k => $p) {
                                // $plano_upgrade = $this->db
                                //     ->where('pug_id_plano', $meuPlano->pa_id)
                                //     ->where('pug_id_plano_upgrade', $p->pa_id)
                                //     ->where('pr_categoria', 9)
                                //     ->join('produtos','pr_id=pug_produto')
                                //     ->get('planos_upgrades')
                                //     ->row();
                            ?>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="pricing">
                                    <div class="title">
                                        <h2><?php echo $p->pa_descricao; ?></h2>
                                        <h1>U$ <?php echo number_format($p->pr_valor, 2, ',', '.') ?></h1>
                                    </div>
                                    <div class="x_content">
                                        <div class="pricing_features">
                                            <?php echo "<strong>".$p->pr_nome."</strong><br />"; ?>
                                            <?php if(count($p->pr_descricao) > 10){ ?>
                                                <?php echo $plano_upgrade->pr_descricao."<br />"; ?>
                                            <?php } ?>
                                            <?php //echo "Indicação Direta: <strong>U$ ".$p->pa_indicacao_direta."</strong><br />"; ?>
                                            <?php echo "Pontos Upgrade: <strong>".$p->pr_pontos."</strong><br />"; ?>
                                            <!-- <?php var_dump($p); ?> -->
                                        </div>
                                        <div class="pricing_footer">
                                            <div class="radio">
                                                <label>
                                                    <input <?php echo $k == 0 ? 'checked' : ''; ?> type="radio" name="plano" value="<?php echo $p->pa_id ?>" />
                                                    <i class="fa fa-fw fa-2x fa-hand-o-left"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <br class="clearfix" />
                        <?php if (count($planos) > 0) { ?>
                        <button type="submit" class="btn btn-success btn-large text-center" id="enviar-cadastro">
                            <i class="fa fa-fw fa-thumbs-o-up"></i>
                            <?php echo $this->lang->line('label_fazer_upgrade'); ?>
                        </button>
                        <?php } ?>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-warning">
                <a href="<?php echo base_url('index.php/pacotes'); ?>"><?php echo $this->lang->line('label_pagar_plano'); ?></a>
            </div>
        <?php } ?>
        </div>
    </div>
</div>