<?php
$this->lang->load('distribuidor/pedidos/confirmar_pagamento');
$this->lang->load('distribuidor/home/infor_home_view');

$binario = new Binario(get_user());
$indiretos = $binario->get_total_inidicacoes();
$totalIndicacaoDireta = $binario->get_total_inidicacoes_diretas();
$saldo = $this->db->query("
    SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
    WHERE cb_distribuidor = " . get_user()->di_id . "
    ")->row();
$primeiraCompra = $this->db->where('co_pago', 1)->where('co_id_distribuidor', get_user()->di_id)->get('compras')->row();
?>
<style>
    .meta-default{
        color: #73879C !important;
    }
    .meta-default h3 {
        font-weight: bold;
    }
    .tile_count {
        margin-top: 0;
    }
</style>

<div class="row top_tiles">
    <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-user"></i></div>
            <div class="count"><?php echo $totalIndicacaoDireta ?></div>
            <p><?php echo $this->lang->line('info_total_diretos'); ?></p>
        </div>
    </div>
    <div class="col-lg-3 col-md-2 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-users"></i></div>
            <div class="count"><?php echo $indiretos ?></div>
            <p><?php echo $this->lang->line('info_total_indiretos'); ?></p>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-dollar"></i></div>
            <div class="count"><?php echo number_format($saldo->saldo, 2, '.', ',') ?></div>
            <p><?php echo $this->lang->line('info_saldo'); ?></p>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-cube"></i></div>
            <div class="count"><?php
                if (count($primeiraCompra) > 0) {
                    echo DistribuidorDAO::getPlano(get_user()->di_id)->pa_descricao;
                }
            ?></div>
            <p><?php echo $this->lang->line('label_plano_atual'); ?></p>
        </div>
    </div>
</div>

<?php
$planoValor = DistribuidorDAO::getPlano(get_user()->di_id)->pa_valor;
if($planoValor > 0):
?>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php
        $limiteValor = $planoValor * 5;
        $cbTotal = $this->db
            ->select('SUM(cb_credito) as saldo')
            ->where('cb_distribuidor',get_user()->di_id)
            ->where('cb_tipo IN( SELECT tb_id FROM bonus_tipo )')
            ->get('conta_bonus')->row();
        $porcAtual = ($cbTotal->saldo/$limiteValor)*100;

        $minWidth = "";
        if($porcAtual >= 0 && $porcAtual <= 10)  $minWidth = "color: #000;";
        elseif($porcAtual > 10)                  $minWidth = "color: #fff;";

        $progressColor = "";
        if ($porcAtual > 0 && $porcAtual <= 50)         $progressColor = "progress-bar-success";
        elseif ($porcAtual > 50 && $porcAtual < 100)    $progressColor = "progress-bar-warning";
        elseif ($porcAtual >= 100)                      $progressColor = "progress-bar-danger";
        ?>
        <div class="panel panel-default">
            <div class="panel-heading meta-default">
                <h3>US$ <?php echo number_format($cbTotal->saldo, 2, '.', ',') ?></h3>
                <p>GANHOS COM SEU PACOTE ATUAL</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped <?php echo $progressColor ?> active" role="progressbar" aria-valuenow="<?php echo round($porcAtual,2) ?>" style="<?php echo $minWidth ?> max-width: 100%; width: <?php echo round($porcAtual,2) ?>%;">
                        <?php if ($porcAtual != 0) { ?>
                            <strong><?php echo round($porcAtual,2) ?>%</strong>
                        <?php } ?>
                    </div>
                </div>
                <h4><i class="fa fa-fw fa-line-chart"></i> Ao atingir <strong>US$ <?php echo number_format($limiteValor, 2, '.', ',') ?></strong> você deve renovar seu pacote!</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <?php
        $limiteValor = $planoValor * 2;
        $cbPL = $this->db
            ->select('SUM(cb_credito) as saldo')
            ->where('cb_tipo',106)
            ->where('cb_distribuidor',get_user()->di_id)
            ->where('cb_tipo IN( SELECT tb_id FROM bonus_tipo )')
            ->get('conta_bonus')->row();
        $porcAtual = ($cbPL->saldo/$limiteValor)*100;

        $minWidth = "";
        if($porcAtual >= 0 && $porcAtual <= 10)  $minWidth = "color: #000;";
        elseif($porcAtual > 10)                  $minWidth = "color: #fff;";

        $progressColor = "";
        if ($porcAtual > 0 && $porcAtual <= 50)         $progressColor = "progress-bar-success";
        elseif ($porcAtual > 50 && $porcAtual < 100)    $progressColor = "progress-bar-warning";
        elseif ($porcAtual >= 100)                      $progressColor = "progress-bar-danger";
        ?>
        <div class="panel panel-default">
            <div class="panel-heading meta-default">
                <h3>US$ <?php echo number_format($cbPL->saldo, 2, '.', ',') ?></h3>
                <p>PARTICIPAÇÃO NOS LUCROS COM SEU PACOTE ATUAL</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped <?php echo $progressColor ?> active" role="progressbar" aria-valuenow="<?php echo round($porcAtual,2) ?>" style="<?php echo $minWidth ?> max-width: 100%; width: <?php echo round($porcAtual,2) ?>%;">
                        <?php if ($porcAtual != 0) { ?>
                            <strong><?php echo round($porcAtual,2) ?>%</strong>
                        <?php } ?>
                    </div>
                </div>
                <h4><i class="fa fa-fw fa-line-chart"></i> Ao atingir <strong>US$ <?php echo number_format($limiteValor, 2, '.', ',') ?></strong> você deve renovar seu pacote!</h4>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="x_panel tile_count">
    <div class="x_title">
        <h2>
            <!-- <code class="badge"><i class="fa fa-sitemap"></i></code> -->
            <label class="fa-stack">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-sitemap fa-stack-1x fa-inverse"></i>
            </label>
            <?php echo $this->lang->line('label_titulo_pontos'); ?>
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="row x_content">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tile_stats_count">
            <span class="count_top">
                <i class="fa fa-hand-o-left"></i>
                <?php echo $this->lang->line('label_pontos_disponivel_direita'); ?>
            </span>
            <div class="count green"><?php echo $pontosBonusBinario->esquerda() - $pontosBonusBinario->pontosPagos(); ?></div>
            <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tile_stats_count">
            <span class="count_top">
                <i class="fa fa-hand-o-right"></i>
                <?php echo $this->lang->line('label_pontos_disponivel_esquerda'); ?>
            </span>
            <div class="count green"><?php echo $pontosBonusBinario->direita() - $pontosBonusBinario->pontosPagos(); ?></div>
            <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span> -->
        </div>
        <?php
        //if ($binario->e_binario() == false) {
            $total_indicacoes_esquerda = $binario->get_indicacoes_diretas_esquerda() + $binario->get_indicacoes_indireta_esquerda();
            $total_indicacoes_direita = $binario->get_indicacoes_diretas_direita() + $binario->get_indicacoes_indireta_direita();
            ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tile_stats_count">
                <span class="count_top red">
                    <i class="fa fa-hand-o-left"></i>
                    Cadastros na Esquerda
                </span>
                <div class="count red"><?php echo $total_indicacoes_esquerda; ?></div>
                <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tile_stats_count">
                <span class="count_top red">
                    <i class="fa fa-hand-o-right"></i>
                    Cadastro na Direita
                </span>
                <div class="count red"><?php echo $total_indicacoes_direita; ?></div>
                <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span> -->
            </div>
        <?php //} ?>
        <div class="clearfix"></div>
    </div>
    <?php /* ?>
    <div class="x_title">
        <h2>
            <code class="badge"><i class="fa fa-dollar"></i></code>
            <?php echo $this->lang->line('label_agencia_financiada'); ?>
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="row x_content">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tile_stats_count">
            <span class="count_top red"><i class="fa fa-hand-o-left"></i>
            <?php echo $this->lang->line('label_pontos_receber_esquerda'); ?></span>
            <div class="count red"><?php echo $pontos_esquerda_financiados; ?></div>
            <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 tile_stats_count">
            <span class="count_top red"><i class="fa fa-hand-o-right"></i>
            <?php echo $this->lang->line('label_pontos_receber_direita'); ?></span>
            <div class="count red"><?php echo $pontos_direita_financiados; ?></div>
            <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span> -->
        </div>
        <div class="clearfix"></div>
    </div>
    <?php */ ?>
    <?php $this->load->view('home/infor_home_view'); ?>
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-fw fa-line-chart"></i>
                <?php echo $this->lang->line('label_titulo_botoes'); ?>
            </div>
            <div class="panel-body">
                <form id="form-escolher-perna" name="form1" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_perna') ?>">
                    <?php echo $this->lang->line('label_posicao_preferencial'); ?>
                    <input type="hidden" name="url" value="<?php echo current_url() ?>" />
                    <div class="radio">
                        <label>
                            <input type="radio" onclick="show_senha_seguranca()" <?php echo get_user()->di_lado_preferencial == 1 ? 'checked' : '' ?> name="di_lado_preferencial" value="1" />
                            <i class="fa fa-fw fa-arrow-left"></i>
                            <?php echo $this->lang->line('label_esquerda'); ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" onclick="show_senha_seguranca()" accept=""<?php echo get_user()->di_lado_preferencial == 2 ? 'checked' : '' ?> name="di_lado_preferencial" value="2" />
                            <i class="fa fa-fw fa-arrow-right"></i>
                            <?php echo $this->lang->line('label_direita'); ?>
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" onclick="show_senha_seguranca()" accept="" <?php echo get_user()->di_lado_preferencial == 3 ? 'checked' : '' ?> name="di_lado_preferencial" value="3" />
                            <i class="fa fa-fw fa-arrow-down"></i>
                            <?php echo $this->lang->line('label_menor'); ?>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-fw fa-save"></i>
                        <?php echo $this->lang->line('label_salvar') ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php
/**
 * Ativação mensal do usuário.
 */
$AtivacaoMensal = new AtivacaoMensal();
$AtivacaoMensal->setDistribuidor(get_user());

$pais = DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id;
$moeda = DistribuidorDAO::getMoeda($pais)->ps_moeda;

$boleto = array(1);
$cartao_execao = array('1');
$saldo_atm = array(1, 2);
if (!$AtivacaoMensal->checarAtivacao()) {
    ?>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo $this->lang->line('label_ativacao_mensal'); ?>
            </div>
            <div class="panel-body minizend">
                <form action="<?php echo base_url('index.php/distribuidor/reativar_distribuidor'); ?>" name="form" method="post">
                    <h5><?php echo $this->lang->line('label_opcao_pagamento_ativacao_mensal'); ?></h5>
                    <ul>
                        <?php if ($pais != 1) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod"  type="radio" value="0" />
                                    <?php echo $this->lang->line('label_saldo_descricao_saldo_empresa'); ?>
                                    <span class="red">
                                        <?php echo $this->lang->line('label_nota_saldo_e_wallet'); ?>
                                    </span>
                                </label>
                            </li>
                        <?php } ?>
                        <?php if (in_array($pais, $boleto)) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod"  type="radio" value="3" />
                                    <?php echo $this->lang->line('label_saldo_descricao_boleto'); ?>
                                </label>
                            </li>
                        <?php } ?>
                        <?php if ($moeda !== 'Euro' && $pais != 61 && $pais != 48 && $pais != 168 && $pais != 63 && $pais != 1) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod"  type="radio" value="13-0" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dorla'); ?>
                                </label>
                            </li>
                        <?php } ?>
                        <?php /* ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod"  type="radio" value="1" />
                                    <?php echo $this->lang->line('label_pagamento_com_bonus'); ?>
                                </label>
                            </li>
                        <?php */ ?>
                        <?php /*if ($moeda == 'Euro' && $pais != 1) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-1" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_euro'); ?>
                                </label>
                            </li>
                        <?php }*/ ?>
                        <?php /*if ($pais == 61) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-2" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso'); ?>
                                </label>
                            </li>
                        <?php }*/ ?>
                        <?php /*if ($pais == 48) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-3" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso_col'); ?>
                                </label>
                            </li>
                        <?php }*/ ?>
                        <?php /*if ($pais == 168) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-4" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_soles'); ?>
                                </label>
                            </li>
                        <?php }*/ ?>
                        <?php /*if ($pais == 63 or $pais == 253) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-5" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dolar_eq'); ?>
                                </label>
                            </li>
                        <?php }*/ ?>
                    </ul>

                    <button class="btn btn-success"  type="submit">
                        <?php echo $this->lang->line('label_aviso_ativacao_pagar'); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<?php /*if (!$AtivacaoMensal->checarAtivacao()) { ?>
    <div class="span4">
    </div>
<?php }*/ ?>
<?php
/**
 * Regra do bloqueio do painel de informar a token do usuário.
 */
//Para o usuári que já informou a token deve desaparecer a opção de token.
// 1 Verifica se o usuário ta com o loguin bloqueado
// 2 verifianco se o distribuidor ta com o status no financeiro bloqueado
// 3 Verificando se o distribuidor ta com a conta parcelada
// 4 situação da primeir compra. tem que ser igual a 7
//***************** NOVAS REGRAS *************************
// 1 Verifica se o usuário ta com o loguin bloqueado
// 2 verifianco se o distribuidor ta com o status no financeiro bloqueado
// 3 Verificando se o distribuidor ta com a conta parcelada
// 4 situação da primeir compra. tem que ser igual a 7
//Verificando de acordo com as regras acima.

if (ComprasModel::podeInformarToken(get_user())) {
    ?>
    <!--inicio do local da token-->
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo $this->lang->line('label_token_de_confirmacao_pagamento'); ?>
            </div>
            <div class="panel-body minizend">
                <form id="form-token-produto-comprado" name="form1" method="post"  action="<?php echo base_url('index.php/distribuidor/confirmar_envio') ?>" style="margin-top: -23px;">
                    <br>
                    <!--error ou success-->
                    <div class="control-group control-group-token ">
                        <label class="control-label" for="token_camp1"><?php echo $this->lang->line('label_tokem'); ?></label>
                        <div class="controls">
                            <input type="text" name="token_camp1" maxlength="4"  class="span1 verificar_token" placeholder="XXXX" value=""> -
                            <input type="text" name="token_camp2"  maxlength="4" class="span1 verificar_token" placeholder="XXXX" value=""> -
                            <input type="text" name="token_camp3" maxlength="4"  class="span1 verificar_token" placeholder="XXXX" value=""> -
                            <input type="text" name="token_camp4"  maxlength="5" class="span1 verificar_token" placeholder="198912638" value=""> .
                            <input type="text" name="token_camp5"  maxlength="4" class="span1 verificar_token" placeholder="1234" value="">
                            <img class="img-success" style="display: none" src="<?php echo base_url('public/imagem/checked.png'); ?>" >
                            <img class="img-error" style="display: none"  src="<?php echo base_url('public/imagem/error.png'); ?>" >
                            <br/>
                            <span class="descricao-error" style="display: none"  ><?php echo $this->lang->line('error_token_invalida'); ?></span>
                        </div>
                    </div>

                    <input type="submit" value="<?php echo $this->lang->line('label_enviar_token') ?>" class="botao-padrao">
                </form>
            </div>
        </div>
    </div>
    <?php
}
?>
</div>
