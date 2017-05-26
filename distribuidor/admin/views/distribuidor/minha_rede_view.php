<?php
$this->lang->load('distribuidor/home/meio_view');
$this->lang->load('distribuidor/home/infor_home_view');

$compraPaga = $this->db->where('co_pago',1)->where('co_id_distribuidor',get_user()->di_id)->get('compras')->row();
if(count($compraPaga) > 0){

	if(get_parameter('info_user')){
		$pai = (int)base64_decode(get_parameter('info_user'));
	} else {
		$pai = get_user()->di_id;
	}

	$atual = $this->db->query("
			SELECT `di_id`, `di_nome`, `di_ni_patrocinador`, `di_data_cad`, `di_usuario`, `di_esquerda`, `di_direita`
			FROM (`distribuidores`)
			WHERE `di_id` = {$pai}
			AND di_id IN(SELECT li_id_distribuidor FROM distribuidor_ligacao WHERE li_no = ".get_user()->di_id.")")
		->row();

	if($atual->di_id==''){
		$pai = get_user()->di_id;

		$atual = $this->db
			->select(array('di_id','di_nome','di_ni_patrocinador','di_data_cad','di_usuario','di_esquerda','di_direita'))
			->where('di_id',get_user()->di_id)
			->get('distribuidores')->row();
	}

	$esta_alocado = $this->db->select('di_id')
		->where('di_esquerda',$atual->di_id)
		->or_where('di_direita',$atual->di_id)
		->get('distribuidores')->row();
	?>

	<div class="block-rede-binaria">
	    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="hidden">
	        <!--fim dos quantos pontos falta-->
	        <tr>
	            <td height="5px"></td>
	        </tr>
	        <tr>
	            <td colspan="10">
	                <!--
	                <a style="margin-left: 0px; margin-top: 8px;" href="javascript:void()" id="helpline">
	                    <img src="<?php //echo base_url('public/imagem/' . $this->lang->line('url_help_up_line')); ?>" />
	                </a>
	                -->
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php if (!verificar_permissao_acesso(false)) { ?>
	                <!--
	                <a style="margin-left: 0px; margin-top: 8px;" href="javascript:void()" onclick="window.open('<?php // echo base_url('index.php/comprar_dolar') ?>', 'Page', 'width=600,height=300,left=350')">
	                    <img src="<?php //echo base_url('public/imagem/' . $this->lang->line('url_comprar_dorla')); ?>" />
	                </a>
	                -->
	                <?php } ?>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php
	                // PEDIU PARA OCULTAR ESTE BOTAO
	                /*if (!verificar_permissao_acesso(false)) {
	                    ?>
	                    <a
	                        style="margin-left: 0px; margin-top: 8px;"
	                        href="javascript:void()"
	                        onclick="window.open('<?php echo base_url('index.php/comprar_cruzeiro') ?>', 'Page', 'width=600,height=300,left=350')">
	                        <img
	                            src="<?php echo base_url('public/imagem/' . $this->lang->line('url_comprar_cruzeiro')); ?>" />
	                    </a>
	                    <?php
	                }*/
	                ?>
	            </td>
	        </tr>
	    </table>

	    <form name="form1" method="post" action="<?php echo base_url('index.php/home/help_uplines') ?>">
	        <div class="modal fade">
	            <div class="modal-dialog" role="document">
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h4>Helper UpLines</h4>
	                    </div>
	                    <div class="modal-body">
	                        <p>
	                            <?php echo $this->lang->line('label_descricao') ?>:<br />
	                            <textarea name="descricao" class="span" style="margin: 0px 0px 10px; width: 505px; height: 71px;" rows="3"></textarea>
	                        </p>
	                    </div>
	                    <div class="modal-footer">
	                        <a href="#" class="btn cancela"> <?php echo $this->lang->line('label_cancelar'); ?></a>
	                        <button class="btn btn-primary" type="submit"><?php echo $this->lang->line('label_enviar'); ?></button>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </form>

	    <?php if ($atual->di_esquerda > 0 || $atual->di_direita > 0) { ?>
	    <!--botões de navegações-->
	    <div class="btns_nav text-center">
	        <span class="badge">
	            <strong><?php echo $this->lang->line('label_title_navegacao'); ?></strong>
	            <div class="btn-group">
	                <?php if ($atual->di_esquerda != 0) { ?>
	                <a href="<?php echo current_url() . '?info_user=' . base64_encode($atual->di_esquerda); ?>" class="btn btn-default">
	                    <i class="fa fa-chevron-down"></i> <?php echo $this->lang->line('label_descer_nivel_esquerda'); ?>
	                </a>
	                <?php } ?>
	                <?php if ($atual->di_id != get_user()->di_id) { ?>
	                <a href="<?php echo current_url(); ?>"  class="btn btn-default">
	                    <i class="fa fa-home"></i> <?php echo $this->lang->line('label_voltar_inicio'); ?>
	                </a>
	                <a  href="<?php echo current_url() . '?info_user=' . base64_encode($esta_alocado->di_id); ?>"  class="btn btn-default">
	                    <i class="fa fa-chevron-up"></i> <?php echo $this->lang->line('label_subir_nivel'); ?>
	                </a>
	                <?php } ?>
	                <?php if ($atual->di_esquerda != 0) { ?>
	                <a href="<?php echo current_url() . '?info_user=' . base64_encode($atual->di_direita); ?>" class="btn btn-default">
	                    <i class="fa fa-chevron-down"></i>
	                    <?php echo $this->lang->line('label_descer_nivel_direita'); ?>
	                </a>
	                <?php } ?>
	            </div>
	        </span>
	    </div>
	    <?php } ?>

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

	            if ( !empty($dis2_1) ) {
	                $dis3_1 = get_no($dis2_1->di_esquerda, 'dis3-1', $this, 1, 'top');
	                $dis3_2 = get_no($dis2_1->di_direita, 'dis3-2', $this, 1, 'top');
	            }

	            if ( !empty($dis2_2) ) {
	                $dis3_3 = get_no($dis2_2->di_esquerda, 'dis3-3', $this, 1, 'top');
	                $dis3_4 = get_no($dis2_2->di_direita, 'dis3-4', $this, 1, 'top');
	            }

	        if ( !empty($dis1_2) ) {
	            $dis2_3 = get_no($dis1_2->di_esquerda, 'dis2-3', $this, 1, 'top');
	            $dis2_4 = get_no($dis1_2->di_direita, 'dis2-4', $this, 1, 'top');
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
	    </div>
	    <div class="clearfix"></div>
	</div>

	<div class="row hidden">
	    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
	        <div class="tree">
	            <ul>
	                <li>
	                    <a href="javascript:;">
	                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                        1
	                    </a>
	                    <ul>
	                        <li>
	                            <a href="javascript:;">
	                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                2
	                            </a>
	                            <ul>
	                                <li>
	                                    <a href="javascript:;">
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                        4
	                                    </a>
	                                    <ul>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                8
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                9
	                                            </a>
	                                        </li>
	                                    </ul>
	                                </li>
	                                <li>
	                                    <a href="javascript:;">
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                        5
	                                    </a>
	                                    <ul>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                10
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                11
	                                            </a>
	                                        </li>
	                                    </ul>
	                                </li>
	                            </ul>
	                        </li>
	                        <li>
	                            <a href="javascript:;">
	                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                3
	                            </a>
	                            <ul>
	                                <li>
	                                    <a href="javascript:;">
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                        6
	                                    </a>
	                                    <ul>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                12
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                13
	                                            </a>
	                                        </li>
	                                    </ul>
	                                </li>
	                                <li>
	                                    <a href="javascript:;">
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                        <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                        7
	                                    </a>
	                                    <ul>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                14
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="javascript:;">
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/pt/binario_membership2.png"><br>
	                                                <img src="http://localhost/YouBR/mwg/distribuidor/public/imagem/flags/BR.png"><br>
	                                                15
	                                            </a>
	                                        </li>
	                                    </ul>
	                                </li>
	                            </ul>
	                        </li>
	                    </ul>
	                </li>
	            </ul>
	        </div>
	    </div>
	    <div class="clearfix"></div>
	</div>
<?php
}

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
        // 'di_usuario_patrocinador',
        'di_esquerda',
        'di_direita',
        'di_usuario',
        'di_nome',
        // 'di_fone1',
        'di_fone2',
        // 'di_email',
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
        $pontos = new Pontos($dis);
        $binario = new Binario($dis);
        ?>
        <?php if ($primero == 0) { ?>
        <div>
            <label class="label label-info pull-left" style="position: relative;">
                <h4>
                    <i class="fa fa-fw fa-arrow-left"></i>
                    <?php // echo $lang->lang->line('label_esdruxula_esquerda'); ?>
                    <strong>
                        <?php echo ($binario->get_indicacoes_diretas_esquerda() + $binario->get_indicacoes_indireta_esquerda()); ?>
                    </strong>
                </h4>
            </label>
            <label class="label label-info pull-right" style="position: relative;">
                <h4>
                    <?php // echo $lang->lang->line('label_esdruxula_direita'); ?>
                    <strong>
                        <?php echo ($binario->get_indicacoes_diretas_direita() + $binario->get_indicacoes_indireta_direita()); ?>
                    </strong>
                    <i class="fa fa-fw fa-arrow-right"></i>
                </h4>
            </label>
        </div>
        <?php } ?>
        <div class="dis <?php echo $id_css ?>">
            <a href="<?php echo current_url() . '?info_user=' . base64_encode($dis->di_id) ?>">
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
                        $imagem = $lang->lang->line('url_binario_rubi2');
                        break;
                        case 102:
                        $imagem = $lang->lang->line('url_binario_esmeralda2');
                        break;
                        case 103:
                        $imagem = $lang->lang->line('url_binario_diamante2');
                        break;
                        case 104:
                        $imagem = $lang->lang->line('url_binario_diamante2');
                        break;
	                    case 105:
	                    $imagem = "en/p-105.png";
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
                        $imagem = $lang->lang->line('url_binario_rubi');
                        break;
                        case 102:
                        $imagem = $lang->lang->line('url_binario_esmeralda');
                        break;
                        case 103:
                        $imagem = $lang->lang->line('url_binario_diamante');
                        break;
                        case 104:
                        $imagem = $lang->lang->line('url_binario_diamante');
                        break;
	                    case 105:
	                    $imagem = "en/p-105.png";
	                    break;
                    }
                }
                ?>
                <img src="<?php echo base_url() ?>public/imagem/<?php echo $imagem; ?>" width="125px" /><br />
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

?>

