<?php $this->lang->load('distribuidor/distribuidor/upgrade_view'); ?>
<div class="box-content min-height">
    <div class="box-content-header"><?php echo $this->lang->line('label_titulo'); ?></div>
    <div class="box-content-body">
        <div class="panel">
            <?php
            if (verificar_permissao_acesso(false)) {
                ?>   
                <div class="alert alert-warning">
                    <?php echo $this->lang->line('label_notificacao_bloqueio'); ?>

                </div> 
                <?php
                exit;
            }
            ?>
            <form method="post" name="form" action="<?php echo base_url('index.php/distribuidor/salvar_upgrade_plano'); ?>">

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
                                    ->where('pa_id >', $meuPlano->co_id_plano)
                                    ->order_by('pa_valor', 'ASC')
                                    ->get('planos')->result();

                    /*
                     * Se o distribuidor for do plano fast ele só pode fazer 
                     * upgrade para o plano esmeralda.
                     */

//                      if($meuPlano->pa_id==100){
//                           $planos   = $this->db
//                              ->where('pa_id !=104')
//                              ->where('pa_id',101)
//                              ->order_by('pa_valor','ASC')
//                              ->get('planos')->result();
//                      }
                    /**
                     * Se o distribuidor for do plano mambership 
                     * ele pode fazer upgrade so para o fast start
                     */
//                      if($meuPlano->pa_id==99){
//                           $planos   = $this->db
//                              ->where('pa_id !=104')
//                              ->where('pa_id',100)
//                              ->order_by('pa_valor','ASC')
//                              ->get('planos')->result();
//                      }
                    ?>
                    <div class="row">
                        <div class="span3">
                            <h4><?php echo $this->lang->line('label_plano_atual'); ?>: <strong><i><?php echo $meuPlano->pa_descricao ?></i></strong></h4>
                        </div>
                    </div>

                    <div class="row">			      
    <?php
    //Sabendo qual pais o usuário pertence
    $pais = $this->db->where('ci_id', get_user()->di_cidade)
            ->join('estados', 'ci_estado=es_id')
            ->get('cidades')
            ->row();


    $plano_atual = $meuPlano;

    foreach ($planos as $k => $p) {
        $valor_upgrade = $this->db->where('pug_id_plano', $plano_atual->pa_id)
                        ->where('pug_id_plano_upgrade', $p->pa_id)
                        ->join('produtos','pr_id=pug_produto')
                        ->get('planos_upgrades')
                        ->row()->pr_valor;
        ?>

                            <div class="span3">
                                <div class="well" style="text-align:center;">
                                    <h4><?php echo $this->lang->line('label_plano_' . $p->pa_id); ?></h4>
                                    <div>U$ <?php echo number_format($valor_upgrade, 2, ',', '.') ?></div>
                                    <br>
                                    <input <?php echo $k == 0 ? 'checked' : '' ?> type="radio" name="plano" value="<?php echo $p->pa_id ?>"  />
                                </div>
                            </div>
    <?php } ?>
                    </div>

    <?php if (count($planos) > 0) { ?>
                        <button type="submit" class="btn btn-success btn-large" id="enviar-cadastro"><?php echo $this->lang->line('label_fazer_upgrade'); ?></button>  
                        <?php }
                    } else {
                        ?>
                    <div class="alert">
                        <a href="<?php echo base_url('index.php/pacotes'); ?>"><?php echo $this->lang->line('label_pagar_plano'); ?></a>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>
</div>