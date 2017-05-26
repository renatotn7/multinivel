<div class="span4" >
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo $this->lang->line('label_titulo_pontos'); ?>
            <div class="button-min">
                <i class="icon-minus"></i>
            </div>
        </div>
        <ul class="minizend list-group" style="margin-left: 0px;">
            <li class="list-group-item">
                <strong> <?php echo $this->lang->line('label_pontos_disponivel_direita'); ?> : </strong> 
                <?php echo number_format($pontosBonusBinario->esquerda() - $pontosBonusBinario->pontosPagos(), 2); ?>
            </li>
            <li class="list-group-item">
                <strong>  <?php echo $this->lang->line('label_pontos_disponivel_esquerda'); ?>:</strong>
                <?php echo number_format($pontosBonusBinario->direita() - $pontosBonusBinario->pontosPagos(), 2); ?>

            </li>
            <?php
            $binario = new Binario(get_user());
            if ($binario->e_binario() == false) {
                ?>
                <li class="list-group-item" style="color:red">
                    <strong><?php echo $this->lang->line('label_pontos_esquerda_qualificar'); ?> :</strong>
                    <?php echo $pontosEsquerdaFalta < 0 ? abs($pontosEsquerdaFalta) : 0; ?>
                </li>
                <li class="list-group-item" style="color:red">
                    <strong><?php echo $this->lang->line('label_pontos_direita_qualificar'); ?> :</strong>
                    <?php echo $pontosDireitaFalta < 0 ? abs($pontosDireitaFalta) : 0; ?>
                </li>
            <?php } ?>
        </ul>


        <ul class="minizend list-group" style="margin-left: 0px;">
            <div class="panel-heading"><strong><?php echo $this->lang->line('label_agencia_financiada'); ?></strong></div>
            <li class="list-group-item" style="color:red">
                <strong><?php echo $this->lang->line('label_pontos_receber_esquerda'); ?> :</strong>
                <?php echo $pontos_esquerda_financiados; ?>
            </li>
            <li class="list-group-item" style="color:red">
                <strong><?php echo $this->lang->line('label_pontos_receber_direita'); ?> :</strong>
                <?php echo $pontos_direita_financiados; ?>
            </li>

        </ul>
    </div>   

</div>   

<div class="span6">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo $this->lang->line('label_titulo_botoes'); ?>
            <div class="button-min">
                <i class="icon-minus"></i>
            </div>
        </div>
        <div class="panel-body minizend">

            <form id="form-escolher-perna" name="form1" method="post"  action="<?php echo base_url('index.php/distribuidor/salvar_perna') ?>" style="margin-top: -23px;">
                <?php echo $this->lang->line('label_posicao_preferencial'); ?> <br />
                <input type="hidden" name="url"
                       value="<?php echo current_url() ?>" /> <input type="radio"
                       onclick="show_senha_seguranca()" style="margin: 0;"
                       <?php echo get_user()->di_lado_preferencial == 1 ? 'checked' : '' ?>
                       name="di_lado_preferencial" value="1" />
                       <?php echo $this->lang->line('label_esquerda'); ?>
                &nbsp;&nbsp;
                <input type="radio"
                       onclick="show_senha_seguranca()" style="margin: 0;"
                       accept=""<?php echo get_user()->di_lado_preferencial == 2 ? 'checked' : '' ?>
                       name="di_lado_preferencial" value="2" /> <?php echo $this->lang->line('label_direita'); ?> &nbsp;&nbsp; <input
                       type="radio" onclick="show_senha_seguranca()"
                       style="margin: 0;"
                       accept="" <?php echo get_user()->di_lado_preferencial == 3 ? 'checked' : '' ?>
                       name="di_lado_preferencial" value="3" /> <?php echo $this->lang->line('label_menor'); ?>   &nbsp;&nbsp; 
                <input type="submit" value="<?php echo $this->lang->line('label_salvar') ?>" class="botao-padrao">
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
    <div class="span6">
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $this->lang->line('label_ativacao_mensal'); ?>
                <div class="button-min">
                    <i class="icon-minus"></i>
                </div>
            </div>
            <div class="panel-body minizend">
                <form action="<?php echo base_url('index.php/distribuidor/reativar_distribuidor'); ?>" name="form" method="post">
                    <h5><?php echo $this->lang->line('label_opcao_pagamento_ativacao_mensal'); ?></h5>     
                    <ul class="unstyled">
                        <?php if ($pais != 1) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod"  type="radio" name="opcao-cartao" value="0" />
                                    <?php echo $this->lang->line('label_saldo_descricao_saldo_empresa'); ?>
                                    <span style=" font-size: 12px; margin-top: -10px;width: 380px;color: red;">
                                        <?php echo $this->lang->line('label_nota_saldo_e_wallet'); ?>
                                    </span>
                                </label>
                            </li>
                        <?php } ?>   
                        <?php if (in_array($pais, $boleto)) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod"  type="radio" name="opcao-cartao" value="3" />
                                    <?php echo $this->lang->line('label_saldo_descricao_boleto'); ?> 
                                </label>
                            </li>
                        <?php } ?>
                        <?php if ($moeda !== 'Euro' && $pais != 61 && $pais != 48 && $pais != 168 && $pais != 63 && $pais != 1) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod"  type="radio" name="opcao-cartao" value="13-0" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dorla'); ?> 
                                </label>
                            </li>
                        <?PHP } ?>
                        <?php if ($moeda == 'Euro' && $pais != 1) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-1" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_euro'); ?> 
                                </label>
                            </li>
                        <?php } ?>
                        <?php if ($pais == 61) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-2" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso'); ?> 
                                </label>
                            </li>
                        <?php } ?>
                        <?php if ($pais == 48) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-3" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_peso_col'); ?> 
                                </label>
                            </li>
                        <?php } ?>
                        <?php if ($pais == 168) { ?>
                            <li> 
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-4" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_soles'); ?> 
                                </label> 
                            </li>
                        <?php } ?>
                        <?php if ($pais == 63 or $pais == 253) { ?>
                            <li>
                                <label class="radio">
                                    <input name="paymentMethod" type="radio" name="opcao-cartao" value="13-5" />
                                    <?php echo $this->lang->line('label_saldo_descricao_tranferecia_dolar_eq'); ?> 
                                </label>    

                            </li>
                        <?php } ?>
                    </ul>

                    <button class="btn"  type="submit">
                        <?php echo $this->lang->line('label_aviso_ativacao_pagar'); ?>
                    </button>
                </form>

            </div>
        </div>
    </div>
<?php } ?>
<?php if (!$AtivacaoMensal->checarAtivacao()) { ?>
    <div class="span4">

    </div>
<?php } ?>
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
    <div class="span6">
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $this->lang->line('label_token_de_confirmacao_pagamento'); ?> 
                <div class="button-min">
                    <i class="icon-minus"></i>
                </div>
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
<div class="span3 " >
    <!--espaço sobrando--> 
</div>
</div>

<div class="box-content2" style="margin-top: 0;">

    <table width="926px" border="0" cellspacing="0" cellpadding="4">
        <tr>
            <td valign="top" width="70%">
                <!-- Lado A Minha Rede-->
                <table width="100%" border="0" cellspacing="0" cellpadding="5"
                       style="color: #EEEEEE;">
                    <tr>
                        <td style="width: 209px;" align="left" valign="top">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">

                                <!--fim dos quantos pontos falta-->
                                <tr>
                                    <td height="5px"></td>
                                </tr>

                                <tr>
                                    <td colspan="10">
                                        <!--                                            <a style="margin-left: 0px; margin-top: 8px;"
                                                                                                    href="javascript:void()" id="helpline"> <img
                                                                                            src="<?php //echo base_url('public/imagem/' . $this->lang->line('url_help_up_line'));                                        ?>" />
                                                                                    </a>-->
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php if (!verificar_permissao_acesso(false)) { ?>
                                            <!--                                                <a
                                                                                                style="margin-left: 0px; margin-top: 8px;"
                                                                                                href="javascript:void()"
                                                                                                onclick="window.open('<?php // echo base_url('index.php/comprar_dolar')                                       ?>', 'Page', 'width=600,height=300,left=350')">
                                                                                                <img
                                                                                                    src="<?php //echo base_url('public/imagem/' . $this->lang->line('url_comprar_dorla'));                                        ?>" />
                                                                                            </a>        -->
                                        <?php } ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <?php
// PEDIU PARA OCULTAR ESTE BOTAO
                                        if (1 == 2) {
                                            if (!verificar_permissao_acesso(false)) {
                                                ?>
                                                <a
                                                    style="margin-left: 0px; margin-top: 8px;"
                                                    href="javascript:void()"
                                                    onclick="window.open('<?php echo base_url('index.php/comprar_cruzeiro') ?>', 'Page', 'width=600,height=300,left=350')">
                                                    <img
                                                        src="<?php echo base_url('public/imagem/' . $this->lang->line('url_comprar_cruzeiro')); ?>" />
                                                </a>        
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>

                                <tr style="color: #000;">
                                    <td colspan="2"></td>
                                </tr>

                            </table>

                        </td>
                    </tr>
                </table>

                <form name="form1" method="post"
                      action="<?php echo base_url('index.php/home/help_uplines') ?>">
                    <div class="modal hide fade">
                        <div class="modal-header">
                            <h4>Helper UpLines</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                <?php echo $this->lang->line('label_descricao') ?>:<br />
                                <textarea name="descricao" class="span"
                                          style="margin: 0px 0px 10px; width: 505px; height: 71px;"
                                          rows="3"></textarea>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <a href="#" class="btn cancela"> <?php echo $this->lang->line('label_cancelar'); ?></a>
                            <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('label_enviar'); ?></button>
                        </div>
                    </div>
                </form>
                <!--botões de navegações-->
                <div class="row" style="margin-left: -10px;">

                    <div class="span">
                        <?php if ($atual->di_esquerda > 0 || $atual->di_direita > 0) { ?>
                            <span class="label"><strong><?php echo $this->lang->line('label_title_navegacao'); ?></strong>
                                <div class="btn-group">
                                    <?php if ($atual->di_esquerda != 0) { ?>
                                        <a href="<?php echo current_url() . '?info_user=' . base64_encode($atual->di_esquerda); ?>" class="btn btn-default">
                                            <i class="icon icon-chevron-down"></i> <?php echo $this->lang->line('label_descer_nivel_esquerda'); ?>
                                        </a>
                                    <?php } ?>
                                    <?php if ($atual->di_id != get_user()->di_id) { ?>
                                        <a href="<?php echo current_url(); ?>"  class="btn btn-default">
                                            <i class="icon icon-home"></i> <?php echo $this->lang->line('label_voltar_inicio'); ?>
                                        </a>
                                        <a  href="<?php echo current_url() . '?info_user=' . base64_encode($esta_alocado->di_id); ?>"  class="btn btn-default">
                                            <i class="icon icon-chevron-up"></i> <?php echo $this->lang->line('label_subir_nivel'); ?>
                                        </a>
                                    <?php } ?>
                                    <?php if ($atual->di_esquerda != 0) { ?>
                                        <a href="<?php echo current_url() . '?info_user=' . base64_encode($atual->di_direita); ?>" class="btn btn-default">
                                            <i class="icon icon-chevron-down"></i> 
                                            <?php echo $this->lang->line('label_descer_nivel_direita'); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </span>   
                        </div>
                    <?php } ?>
                </div>
                </div>
                <div class="rede-binaria">
                    <?php
                    $dis1 = get_no($pai, 'dis1', $this, 0);

                    if ( !empty($dis1) ) {
                        $dis1_1 = get_no($dis1->di_esquerda, 'dis1-1', $this, 1, 'top');
                        $dis1_2 = get_no($dis1->di_direita, 'dis1-2', $this, 1, 'top');
                    }

                    if ( !empty($dis1_1) ) {
                        $dis2_1 = get_no($dis1_1->di_esquerda, 'dis2-1', $this, 1, 'top');
                        $dis2_2 = get_no($dis1_1->di_direita, 'dis2-2', $this, 1, 'top');
                    }

                    if ( !empty($dis1_2) ) {
                        $dis2_3 = get_no($dis1_2->di_esquerda, 'dis2-3', $this, 1, 'top');
                        $dis2_4 = get_no($dis1_2->di_direita, 'dis2-4', $this, 1, 'top');
                    }

                    if ( !empty($dis2_1) ) {
                        $dis3_1 = get_no($dis2_1->di_esquerda, 'dis3-1', $this, 1, 'top');
                        $dis3_2 = get_no($dis2_1->di_direita, 'dis3-2', $this, 1, 'top');
                    }

                    if ( !empty($dis2_2) ) {
                        $dis3_3 = get_no($dis2_2->di_esquerda, 'dis3-3', $this, 1, 'top');
                        $dis3_4 = get_no($dis2_2->di_direita, 'dis3-4', $this, 1, 'top');
                    }

                    if ( !empty($dis2_3) ) {
                        $dis3_5 = get_no($dis2_3->di_esquerda, 'dis3-5', $this, 1, 'top');
                        $dis3_6 = get_no($dis2_3->di_direita, 'dis3-6', $this, 1, 'top');
                    }

                    if ( !empty($dis2_4) ) {
                        $dis3_7 = get_no($dis2_4->di_esquerda, 'dis3-7', $this, 1, 'top');
                        $dis3_8 = get_no($dis2_4->di_direita, 'dis3-8', $this, 1, 'top');
                    }
                    ?>
                </div> <br /> <br /> <!-- END Lado A Minha Rede-->
            </td>

            <td width="20%" valign="top">
                <!-- Lado B-->
                <div style="position: relative;">
                    <div id="momenclatura"></div>
                </div> <!-- END Lado B-->
            </td>
        </tr>
    </table>
</div>

