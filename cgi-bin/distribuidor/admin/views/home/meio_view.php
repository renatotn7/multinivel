
<?php
$AtivacaoMensal = new AtivacaoMensal();
$AtivacaoMensal->setDistribuidor(get_user());

$not = $this->db->where('status', 1)->order_by('ordem', 'DESC')->order_by('id_no', 'DESC')->get('noticias', 3)->result();
//Declarando a variavel que vai funcionar na rede.
$dadosRede = array();
$cartao_atm = 0;
//Verificando se é a primeira compra do usuário.
$primeiraCompra = $this->db->where('co_pago', 1)->where('co_id_distribuidor', get_user()->di_id)->get('compras')->row();

//Verificando se a conta ta paga.
$compraNaoPaga = $this->db->where('co_pago', 0)->where('co_situacao <>', - 1)->where('co_id_distribuidor', get_user()->di_id)->get('compras')->row();
$dadosRede['compraNaoPaga'] = $compraNaoPaga;

//Pegando a compra Paga.
$compraPaga = $this->db->where('co_pago', 1)->where('co_id_distribuidor', get_user()->di_id)->get('compras')->row();
$dadosRede['compraPaga'] = $compraPaga;

// Pontos da perna direita e esquerda
$pontos = new Pontos(get_user());
$pontosDireita = ($pontos->get_pontos_direita() - $pontos->get_pontos_pagos());
$pontosEsquerda = ($pontos->get_pontos_esquerda() - $pontos->get_pontos_pagos());

//Atigiu a pontuação necessária.
if (@$pontos->get_pontos_perna_maior() >= @PlanosModel::getPlano(get_user()->di_id)->pa_pontos) {

}

$dadosRede['pontosDireita'] = $pontosDireita;
$dadosRede['pontosEsquerda'] = $pontosEsquerda;

//Pontos financiados na rede.
$dadosRede['pontos_direita_financiados'] = $pontos->get_pontos_direita_financiados();
$dadosRede['pontos_esquerda_financiados'] = $pontos->get_pontos_esquerda_financiados();

//Inicializando as Variáveis para o calculo da variáção de pontos nessários, para qualificacao.
$pontosDireitaFalta = 0;
$pontosEsquerdaFalta = 0;

//Calculando a quantidade de pontos necessárias para o plano.
if (count($primeiraCompra) > 0) {
    $pontosNecessarios = DistribuidorDAO::getPlano(get_user()->di_id)->pa_pontos;
    $pontosDireitaFalta = number_format(($pontos->get_pontos_direita_diretos() - $pontosNecessarios), 2);
    $pontosEsquerdaFalta = number_format(($pontos->get_pontos_esquerda_diretos() - $pontosNecessarios), 2);

    $dadosRede['pontosDireitaFalta'] = $pontosDireitaFalta;
    $dadosRede['pontosEsquerdaFalta'] = $pontosEsquerdaFalta;
}


$franquiaEsquerda = $this->db->select("COUNT(*) as membros")->where('li_no', get_user()->di_esquerda)->get('distribuidor_ligacao')->row();
$franquiaDireita = $this->db->select("COUNT(*) as membros")->where('li_no', get_user()->di_direita)->get('distribuidor_ligacao')->row();

$dadosRede['franquiaEsquerda'] = $franquiaEsquerda;
$dadosRede['franquiaDireita'] = $franquiaDireita;

$pontosBonusBinario = new PontosBonusBinario();
$pontosBonusBinario->setDistribuidor(get_user());

$dadosRede['pontosBonusBinario'] = $pontosBonusBinario;
if (count($compraPaga) > 0) {
    //Pegando o link do bônus de acordo com o plano.
    $link_click_friend = DistribuidorDAO::getPlano(get_user()->di_id)->pa_link_bonus;
}

if (count($compraPaga) > 0) {
    if (get_parameter('info_user')) {
        $pai = (int) base64_decode(get_parameter('info_user'));
        $dadosRede['pai'] = $pai;
    } else {
        $pai = get_user()->di_id;
        $dadosRede['pai'] = $pai;
    }

    $atual = $this->db->query("
        SELECT `di_id`, `di_nome`, `di_ni_patrocinador`, `di_data_cad`, `di_usuario`,
        `di_esquerda`, `di_direita`
        FROM (`distribuidores`)
        WHERE `di_id` = {$pai}
        AND di_id IN(
        SELECT li_id_distribuidor FROM distribuidor_ligacao WHERE li_no = " . get_user()->di_id . "
        )
        ")->row();

    $dadosRede['atual'] = $atual;

    if ($atual->di_id == '') {
        $pai = get_user()->di_id;

        $atual = $this->db->select(array(
                    'di_id',
                    'di_nome',
                    'di_ni_patrocinador',
                    'di_data_cad',
                    'di_usuario',
                    'di_esquerda',
                    'di_direita'
                ))->where('di_id', get_user()->di_id)->get('distribuidores')->row();

        $dadosRede['atual'] = $atual;
    }

    $esta_alocado = $this->db->select(array(
                'di_id',
                'di_ni_patrocinador'
            ))->where('di_esquerda', $atual->di_id)->or_where('di_direita', $atual->di_id)->get('distribuidores')->row();

    $dadosRede['esta_alocado'] = $esta_alocado;
}





$this->lang->load('distribuidor/home/meio_view');
$ativo = new Ativacao ();
$url_chat = $this->lang->line('url_chat');

foreach ($not as $n) {
    ?>
    <div class="noticias-home alert <?php echo $n->cor ?>">
        <h4><?php echo $n->titulo ?></h4>
        <?php echo $n->texto ?>
        <a class="close-noticia" href="javascript:void(0)">X</a>
    </div>
<?php } ?>
<!--
*
*Menu de icone
*
*
-->
<style>
    .menu_option{
        float: left;
        width:86px;
    }
    .menu_option span{
        font-size: 12px;
        color: black;
    }
    .menu_option a{
        text-decoration: none;
    }
</style>

<div class="row" style="margin-left: 7px">
    <div class="panel panel-default">
        <div class="panel-body" style="height: 90px;">

            <?php if (get_user()->distribuidor->getAtivo() == 0) { ?>

                <div class="menu_option" align="center">
                    <a href="#" target="_self">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_banco')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_banco'); ?>
                        </span>
                    </a>
                </div>

                <div class="menu_option" align="center">
                    <a href="#">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_saque')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_saque'); ?>
                        </span>
                    </a>
                </div>

               <!--  <div class="menu_option" align="center">
                    <a href="#" target="_self">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_trainamentos')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_trainamentos'); ?>
                        </span>
                    </a>
                </div>

                <div class="menu_option" align="center">
                    <a  href="#" target="_self">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_download')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_download'); ?>
                        </span>
                    </a>
                </div>
                <div class="menu_option" align="center">
                    <a href="#" target="_self">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_suporte')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_suporte'); ?>
                        </span>
                    </a>
                </div> -->
                <div class="menu_option" align="center" style="width: 114px;">
                    <a href="#" target="_self" >
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $url_chat); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_comprar_evooucher'); ?>
                        </span>
                    </a>
                </div>

                <?php
                $pais = $this->db->where('ci_id', get_user()->di_cidade)->get('cidades')->row();
                $cartao_atm = $this->db->where('ccm_niv', get_user()->di_niv)
                                ->get('compra_cartao_memberships')->row();

                if (count($cartao_atm) == 0 && !empty(get_user()->di_niv) && get_user()->di_data_cad > '2014-04-01 23:59:59') {
                    ?>
                    <!--                    <div class="menu_option" align="center">
                                            <a href="<?php echo base_url('index.php/comprar_cartao'); ?>" target="_self" >
                                                <img src="<?php echo base_url('public/imagem/links-home/png/' . $url_chat); ?>" /><br/>
                                                <span>
                    <?php echo $this->lang->line('label_comprar_evooucher'); ?>
                                                </span>
                                            </a>
                                        </div>-->
                <?php } ?>
                <!-- <div class="menu_option" align="center">
                    <a href="http://www.empresashop.com" target="_blank">
                        <img src="<?php echo base_url('public/imagem/links-home/carrinho-ew.jpg'); ?>" /><br/>
                        <span>
                            <?php // echo $this->lang->line('label_chat');  ?>
                        </span>
                    </a>
                </div> -->
            <?php } else { ?>
                <div class="menu_option" align="center">
                    <a href="<?php echo base_url('index.php/distribuidor/meus_dados') ?>">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_minha_conta')); ?>"/><br/>
                        <span>
                            <?php echo $this->lang->line('label_minha_conta'); ?>
                        </span>
                    </a>
                </div>
                <!--CONTINUAR APARTIR DAQUI-->
                <div class="menu_option" align="center">
                    <a href="<?php echo base_url('index.php/bonus/extrato') ?>">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_banco')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_banco'); ?>
                        </span>
                    </a>
                </div>

                <?php if (!verificar_permissao_acesso(false)) { ?>
                    <div class="menu_option" align="center">
                        <a href="<?php echo base_url("index.php/bonus/extrato"); ?>">
                            <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_saque')); ?>" /><br/>
                            <span>
                                <?php echo $this->lang->line('label_saque'); ?>
                            </span>
                        </a>
                    </div>

                <?php } ?>
                <div class="menu_option" align="center">
                    <a href="<?php echo base_url('index.php/loja/opcao_pagamento') ?>">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_pagar')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_pagar'); ?>
                        </span>
                    </a>
                </div>
    <!--    <a href="#"> <img
    src="<?php // echo base_url('public/imagem/links-home/' . $this->lang->line('url_att_bonus'));                       ?>" />
    </a>-->
    <!--    <a href="<?php // echo base_url('index.php/distribuidor/construcao');                       ?>">
    <img src="<?php //echo base_url('public/imagem/links-home/' . $this->lang->line('url_noticias'));                       ?>" /></a>-->
               <!--  <div class="menu_option" align="center">
                    <a href="<?php echo $this->lang->line('url_noticias_treinamentos'); ?>" target="_blank">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_trainamentos')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_trainamentos'); ?>
                        </span>
                    </a>
                </div>
                <div class="menu_option" align="center">
                    <a  href="<?php echo $this->lang->line('url_pagina_download'); ?>" target="_blank">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_download')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_download'); ?>
                        </span>
                    </a>
                </div>
                <div class="menu_option" align="center">
                    <a href="http://www.empresa.com/ticket/" target="_blank">
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_suporte')); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_suporte'); ?>
                        </span>
                    </a>
                </div> -->
                <?php
//                $pais = $this->db->where('ci_id', get_user()->di_cidade)->get('cidades')->row();
//                $cartao_atm = $this->db->where('ccm_niv', get_user()->di_niv)
//                                ->get('compra_cartao_memberships')->row();
//
//                if (!empty(get_user()->di_niv) && get_user()->di_data_cad > '2014-04-01 23:59:59') {
                ?>
                <div class="menu_option" align="center" style="width: 114px;">
                    <a href="<?php echo base_url('index.php/comprar_cartao'); ?>" target="_self" >
                        <img src="<?php echo base_url('public/imagem/links-home/png/' . $url_chat); ?>" /><br/>
                        <span>
                            <?php echo $this->lang->line('label_comprar_evooucher'); ?>
                        </span>
                    </a>
                </div>
                <?php // }  ?>

                <?php if (!empty($link_click_friend) && get_user()->di_data_cad >= '2014-04-01 23:59:59') { ?>
                    <div class="menu_option" align="center" align="center">
                        <a href="<?php echo $link_click_friend; ?>" target="_blank">
                            <img style="float:left;" src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_comprar_click_friends')); ?>" /><br/>
                            <span style="font-size: 12px;float:left;">
                                <?php echo $this->lang->line('label_comprar_click_friends'); ?>
                            </span>
                        </a>
                    </div>
                <?php } ?>

                <!-- <div class="menu_option" align="center">
                    <a href="http://www.empresashop.com" target="_blank">
                        <img src="<?php echo base_url('public/imagem/links-home/carrinho-ew.jpg'); ?>" /><br/>
                        <span>
                            <?php // echo $this->lang->line('label_chat');  ?>
                        </span>
                    </a>
                </div> -->
                <?php if (count(ComprasModel::parcelas_pendentes(get_user())) == 0) { ?>
                    <div class="menu_option" align="center">
                        <a href="<?php echo base_url('index.php/distribuidor/upgrade_plano'); ?>" target="_self">
                            <img src="<?php echo base_url('public/imagem/links-home/png/' . $this->lang->line('url_upgrade')); ?>" /><br/>
                            <span>
                                <?php echo $this->lang->line('label_upgrade'); ?>
                            </span>
                        </a>
                    </div>
                <?php } ?>

            <?php } ?>
        </div>
    </div>
</div>

<!--
*
*Menu de icone
*
*
-->


<?php
//Ativação mensal.
if (!$AtivacaoMensal->checarAtivacao() && count($primeiraCompra) > 0) {
    ?>
    <div class="noticias-home alert alert-error">
        <h4><?php echo $this->lang->line('label_aviso_ativacao_titulo'); ?></h4>
        <?php echo $this->lang->line('label_aviso_ativacao_texto'); ?> - <?php echo $AtivacaoMensal->getDataVencimentoAtivacao('d/m/Y') ?>
    </div>
<?php } ?>



<div class="row" style="margin-left: -12px;width: 1041px;">
    <!--Carregando o conteúdo do meio do backoffice-->
    <?php
    if (count($primeiraCompra) == 0) {
        if (count($compraNaoPaga) > 0) {

            $this->load->view('home/informacao_cartao_intercash_view', array(
                'compraNaoPaga' => $compraNaoPaga
            ));
        } else {
            //Todas informações passada são selecinanda no início desse mesmo
            $this->load->view('home/rede_meio_view', $dadosRede);
        }
    } else {
        //Todas informações passada são selecinanda no início desse mesmo
        $this->load->view('home/rede_meio_view', $dadosRede);
    }
    ?>

    <?php

    function get_no($di_id, $id_css, $lang = '', $primero = 1, $position = 'bottom') {
        if ($di_id == 0) {
            $dis_vazio = new stdClass ();
            $dis_vazio->di_esquerda = 0;
            $dis_vazio->di_direita = 0;
            return $dis_vazio;
        }

        $ci = & get_instance();

        $dis = $ci->db->select(array(
                    'di_id',
                    'di_cidade',
                    'di_ni_patrocinador',
                    'di_binario',
                    'di_usuario_patrocinador',
                    'di_esquerda',
                    'di_usuario',
                    'di_nome',
                    'di_fone1',
                    'di_fone2',
                    'di_email',
                    'di_direita',
                    'pa_descricao'
                ))->where('di_id', $di_id)->join('compras', 'co_id_distribuidor=di_id')->join('registro_planos_distribuidor', 'ps_distribuidor = di_id', 'left')->join('planos', 'pa_id = ps_plano', 'left')->get('distribuidores')->row();

        if (count($dis) > 0) {

            $pat = $ci->db->select(array(
                        'di_nome',
                        'di_usuario'
                    ))->where('di_id', $dis->di_ni_patrocinador)->get('distribuidores')->row();

            $ativou_binario = $dis->di_binario == 1 ? true : false;
            $planoDistribuidor = DistribuidorDAO::getPlano($dis->di_id);
            $pais = DistribuidorDAO::getPais($dis->di_cidade);
            ?>
            <?php
            $pontos = new Pontos($dis);
            $binario = new Binario($dis);
            ?>
            <div class="dis <?php echo $id_css ?>">
            <?php if ($primero == 0) { ?>
                    <span class="badge" id='esquerda' style="position: absolute;margin-left: -245PX;">
                        <h4><?php echo $lang->lang->line('label_esdruxula_esquerda'); ?> <?php echo ($binario->get_indicacoes_diretas_esquerda() + $binario->get_indicacoes_indireta_esquerda()); ?></h4>
                    </span>
                    <span class="badge" style="float: left;margin-left: 154PX;">
                        <h4><?php echo $lang->lang->line('label_esdruxula_direita'); ?> <?php echo ($binario->get_indicacoes_diretas_direita() + $binario->get_indicacoes_indireta_direita()); ?></h4>
                    </span>
        <?php } ?>
                <a
                    href="<?php echo current_url() . '?info_user=' . base64_encode($dis->di_id) ?>">
        <?php
        if (count($pat) > 0 && $primero == 1) {
            switch ($planoDistribuidor->pa_id) {
                case 99:
                    $imagem = $lang->lang->line('url_binario_membership2');
                    break;
                case 100:
                    $imagem = $lang->lang->line('url_binario_fast2');
                    break;
                case 101:
                    $imagem = $lang->lang->line('url_binario_esmeralda2');
                    break;
                case 102:
                    $imagem = $lang->lang->line('url_binario_rubi2');
                    break;
                case 103:
                    $imagem = $lang->lang->line('url_binario_diamante2');
                    break;
                case 104:
                    $imagem = $lang->lang->line('url_binario_diamante2');
                    break;
            }
        } else {
            switch ($planoDistribuidor->pa_id) {
                case 99:
                    $imagem = $lang->lang->line('url_binario_membership');
                    break;
                case 100:
                    $imagem = $lang->lang->line('url_binario_fast');
                    break;
                case 101:
                    $imagem = $lang->lang->line('url_binario_esmeralda');
                    break;
                case 102:

                    $imagem = $lang->lang->line('url_binario_rubi');
                    break;
                case 103:
                    $imagem = $lang->lang->line('url_binario_diamante');
                    break;
                case 104:
                    $imagem = $lang->lang->line('url_binario_diamante');
                    break;
            }
        }

        ?>

                    <img src="<?php echo base_url() ?>public/imagem/<?php echo $imagem; ?>" /><br />
                    <img src="<?php echo base_url("public/imagem/flags/".@DistribuidorDAO::getPais($dis->di_cidade)->ps_bandeira); ?>" /><br/>
        <?php echo $dis->di_usuario ?>(<?php echo $planoDistribuidor->pa_id == 104 ? "DI." : substr($planoDistribuidor->pa_descricao, 0, 2) ?>)
                </a>


                <div class="popover <?php echo $position; ?> in">
                    <div class="arrow"></div>
                    <h3 class="popover-title"><?php echo $lang->lang->line('label_title_info') ?> - (<?php echo $dis->di_usuario ?>) </h3>
                    <div class="popover-content">
                        <b><?php echo $lang->lang->line('label_usuario'); ?>:</b> <?php echo $dis->di_usuario ?><br>
                        <b><?php echo $lang->lang->line('label_nome'); ?>: </b> <?php echo $dis->di_nome ?><br>

                        <?php if ($dis->di_fone2 != '' && $dis->di_fone2 != '0') { ?>
                        <!--<b><?php echo $lang->lang->line('label_fone_alternativo'); ?>: </b> <?php echo $dis->di_fone2 ?><br>-->
                        <?php } ?>
                        <b><?php echo $lang->lang->line('label_plano'); ?>: </b> <?php echo $planoDistribuidor->pa_descricao ?><br>

                        <?php if ($primero == 0) { ?>
                            <b><?php echo $lang->lang->line('label_esquerda_diretos'); ?>: </b> <?php echo $pontos->get_pontos_esquerda_diretos_formatado(); ?>
                            <b><?php echo $lang->lang->line('label_direita_diretos'); ?>: </b>  <?php echo $pontos->get_pontos_direita_diretos_formatado(); ?><br>
                        <?php } ?>

                        <b><?php echo $lang->lang->line('label_esquerda'); ?>: </b> <?php echo $pontos->get_pontos_esquerda_formatado(); ?>
                        <b><?php echo $lang->lang->line('label_direita'); ?>: </b>  <?php echo $pontos->get_pontos_direita_formatado(); ?><br>
                        <?php if (count($pat) > 0) { ?>
                            <b><?php echo $lang->lang->line('label_patrocinador'); ?>:</b> <?php echo $pat->di_nome ?> - (<?php echo $pat->di_usuario ?>)
                        <?php } ?>
                    </div>
                </div>
            </div>

        <?php
        return $dis;
    } else {
        return false;
    }
}

$pais = true;
//testando o pais
if (DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id == 1) {
    $pais = false;
}
if (DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id == 100) {
    $pais = false;
}
if (DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id == 169) {
    $pais = false;
}
if (DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id == 225) {
    $pais = false;
}
?>



<?php if (get_user()->di_data_cad >= "2014-04-01 00:00:00" && $pais && count($cartao_atm) == 0) { ?>
        <script type="text/javascript">
            $(function() {
                //grid('<?php echo base_url('index.php/notificacao/cartao_intercash') ?>', 'Atenção', '400', '450');
            });
        </script>
<?php } ?>


<?php if (DistribuidorDAO::contaverificada(get_user())) { ?>
        <script type="text/javascript">
            $(function() {
                $(document).grid({
                    url: '<?php echo base_url('index.php/notificacao/verificacao_conta') ?>',
                    title: 'Atenção',
                    width: '820',
                    higth: '380'
                });
            });
        </script>
<?php }
?>
    <script type="text/javascript">

        $(function() {

            $(".close-noticia").click(function() {
                $(this).parent().remove();
            });

        });

        var win = null;
        function NovaJanela(pagina, nome, w, h, scroll) {
            LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
            TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
            settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
            win = window.open(pagina, nome, settings);
        }

        if (navigator.userAgent.indexOf("Firefox") != -1) {
            $('#esquerda').css('margin-left', '-655px');
        }
    </script>
