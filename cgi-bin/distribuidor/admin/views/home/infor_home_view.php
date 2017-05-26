<?php
$this->lang->load('distribuidor/home/infor_home_view');
$AtivacaoMensal = new AtivacaoMensal();
$AtivacaoMensal->setDistribuidor(get_user());
$dataProximaPl = $this->db->where('field', 'proxima_pl')->get('config')->row();
$dataDepositoConta = $this->db->where('field', 'data_deposito_conta')->get('config')->row();
$vendaPelaLoja = $this->db->where('field', 'venda_pela_loja')->get('config')->row();
$divulgacaoRedes = $this->db->where('field', 'divulgacao_redes_sociais')->get('config')->row();
$leiloesCentavos = $this->db->where('field', 'leiloes_centavos')->get('config')->row();
$participacaoAcoes = $this->db->where('field', 'participacao_acoes')->get('config')->row();
$participacaoLeiloes = $this->db->where('field', 'participacao_leiloes')->get('config')->row();
$sorteiosMensais = $this->db->where('field', 'sorteios_mensais')->get('config')->row();
$residualImperfeito = $this->db->where('field', 'residual_binario_imperfeito')->get('config')->row();
$dataLiberacaoSaque = $this->db->where('field', 'data_liberacao_saque')->get('config')->row();
$primeiroPlano = $this->db->query("select * from registro_planos_distribuidor 
                                   join planos on pa_id = ps_plano where
                                  ps_distribuidor=" . get_user()->di_id . " limit 1")->row();
$saldo = $this->db->query("
	SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
	WHERE cb_distribuidor = " . get_user()->di_id . "
	")->row();

$recebido = $this->db->query("
	SELECT SUM(cb_credito) AS saldo FROM conta_bonus
	WHERE cb_distribuidor = " . get_user()->di_id . "
	AND cb_tipo IN(1,2,5,105)
	")->row();

$ativacao_mensal = $this->db->where('at_distribuidor',  get_user()->di_id)
                            ->order_by('at_id','desc')
                            ->get('registro_ativacao')->row();

        if(count($ativacao_mensal)>0){
           $ativacao_mensal  = $ativacao_mensal->at_data;
        }

$ativacao_m_exist = $this->db->where('am_distribuidor',  get_user()->di_id)
                            ->order_by('am_id','desc')
                            ->get('registro_ativacao_mensal')->row();

       //Se houver uma ativação mensal 
        if(count($ativacao_m_exist)>0){
         $ativacao_mensal  = $ativacao_m_exist->am_data;
        }

$saldo_atm = atm::consultarSaldo(get_user());

$ativacaoModel = new AtivacaoModel(get_user());
//Recompra
$dataRecompra = date('d/m/Y', strtotime($ativacaoModel->getProximaAtivacao()));


$cicloAtual = $this->db
                ->where('cl_data_inicio >=', date('Y-m-d'))
                ->order_by('cl_ciclo', 'ASC')
                ->get('ciclos')->row();

if (count($cicloAtual) > 0) {
    $dataCiclo = date('d/m/Y', strtotime($cicloAtual->cl_data_fim));
} else {
    $dataCiclo = '';
}

//Ouro Comprados
$ouroComprados = $this->db
                ->join('produtos_comprados', 'pm_id_compra = co_id')
                ->select('SUM(pm_quantidade) as quantidade')
                ->where('pm_tipo', 1)
                ->where('co_pago', 1)
                ->where('co_id_distribuidor', get_user()->di_id)
                ->get('compras')->row();

$totalOuroComprados = $ouroComprados->quantidade + 0;

$binario = new Binario(get_user());

$indiretos = $binario->get_total_inidicacoes();

$indiretosEsquerda = $binario->get_indicacoes_indireta_esquerda();
$indiretosDireita = $binario->get_indicacoes_indireta_direita();

$diretosEsquerda = $binario->get_indicacoes_diretas_esquerda();
$diretosDireita = $binario->get_indicacoes_diretas_direita();

$totalIndicacaoDireta = $binario->get_total_inidicacoes_diretas();

$pontos = new Pontos(get_user());

$binarioAtivo = $this->db->where('db_distribuidor', get_user()->di_id)->get('registro_distribuidor_binario')->row();

if (count($binarioAtivo) > 0) {
    $dataAtivacaoBinario = date('d/m/Y', strtotime($binarioAtivo->db_data));
} else {
    $dataAtivacaoBinario = 'Não está qualificado.';
}

$dolar = $this->db->where('field', 'cotacao_dolar')->get('config')->row();

$valorPl = $this->db
                ->order_by('rpl_id', 'DESC')
                ->get('registro_pl')->row();

$plOntem = $this->db
                ->like('rbpl_data', date('Y-m-d'))
                ->where('rbpl_distribuidor', get_user()->di_id)
                ->get('registro_bonus_pl')->row();

$valorPlOtem = count($plOntem) ? $plOntem->rbpl_valor : 0;
$primeiraCompra = $this->db->where('co_pago', 1)->where('co_id_distribuidor', get_user()->di_id)->get('compras')->row();

//Carreira 
$plano_carreira = $this->db->where('dq_id', get_user()->di_qualificacao)
        ->get('distribuidor_qualificacao')
        ->row();



//Pegando a proxima gradução.
$prooximo_plano = $this->db->where('dq_id > ' . $plano_carreira->dq_id)
        ->get('distribuidor_qualificacao', 1)
        ->row();
$pontosProximoPlano = isset($prooximo_plano->dq_pontos) ? $prooximo_plano->dq_pontos : 0;
?>
<div class="info-home">
    <?php
    $dadosEstadoUsuario = $this->db->where('es_id', get_user()->di_uf)->get('estados')->row();
    if ($dadosEstadoUsuario->es_pais == 1) {
        ?>
        <!--        <b><?php // echo $this->lang->line('info_titulo');   ?></b>-->
                <!--<div><?php //echo $this->lang->line('info_dolar');   ?><strong><?php echo $dolar->valor ?></strong></div>-->
        <?php
    }
    ?>

    <div class="list-group" style="margin-left: -13px; width: 246px;">
        <a href="#" class="list-group-item" style="background: #122A3A;color: #FFF;font-size: 14px;">
            <strong>
            <?php echo $this->lang->line('info_titulo_geral'); ?> </a>
        </strong>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('label_plano'); ?>:
            <strong>
                <?php
                if (count($primeiraCompra) > 0) {

                    echo $primeiroPlano->pa_descricao;
                }
                ?> 
            </strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('label_data_cadastro'); ?>:
            <strong>
                <?php echo date('d/m/Y',strtotime(get_user()->di_data_cad));?> 
            </strong>
        </a>
            
             <a href="#" class="list-group-item">
            <?php echo $this->lang->line('label_data_ativacao'); ?>:
            <strong>
                <?php
                   if(!empty($ativacao_mensal)){
                   echo date('d/m/Y',  strtotime($ativacao_mensal));
                   }else{
                     echo  'xx/xx/xxxx';
                   }
                ?> 
            </strong>
        </a>
            
            
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('label_plano_atual'); ?>:
            <strong>
                <?php
                if (count($primeiraCompra) > 0) {
                    echo DistribuidorDAO::getPlano(get_user()->di_id)->pa_descricao;
                }
                ?> 
            </strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('label_graduaco_atual'); ?>:
            <strong><?php echo $plano_carreira->dq_descricao; ?></strong>
        </a>
        <?php if (count($prooximo_plano) > 0) { ?>
            <!--        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('label_pontos_necessario_proxima_graduacao'); ?>  <?php echo " -<strong> (" . $prooximo_plano->dq_descricao . ")</strong>"; ?>:
                        <strong>
            <?php echo abs($pontosProximoPlano - $pontos->get_pontos_perna_menor()); ?> 
                        </strong>
                    </a>-->
        <?php } ?>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_data_cad'); ?>
            <strong><?php echo $ativacaoModel->getData() != null ? date('d/m/Y', strtotime($ativacaoModel->getData())) : 'xx/xx/xxxx' ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_saldo'); ?>
            <strong>US$<?php echo number_format($saldo->saldo, 2, ',', '.') ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_saldo_atm'); ?>
            
            <strong>US$ <?php  echo $saldo_atm != false ? number_format((double)converte_moeda($saldo_atm), 2, ',', '.') : '0.00'; ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_data_quali'); ?>
            <strong><?php echo $dataAtivacaoBinario ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_data_recompra'); ?>
            <strong><?php echo $ativacaoModel->getData() != null ? $dataRecompra : 'xx/xx/xxxx'; ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_data_prox_atv'); ?>
            <strong><?php echo $AtivacaoMensal->checarAtivacao() ? $dataCiclo : "xx/xx/xxxx"; ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_quali_receber'); ?> 
            <strong style="color: <?php echo $binario->e_binario() == 1 ? '' : 'red'; ?>"><?php echo $binario->e_binario() == 1 ? $this->lang->line('label_qualificado_sim') : $this->lang->line('label_qualificado_nao') ?></strong>
        </a>

        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_diretos'); ?>
            <strong><?php echo $totalIndicacaoDireta ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_indiretos'); ?>
            <strong><?php echo $indiretos ?></strong>
        </a>
        <a href="#" class="list-group-item" style="background: #122A3A;color: #FFF;font-size: 14px;">
            <strong>
            <?php echo $this->lang->line('infor_titulo_rede_esquerda'); ?> </a>
        </strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_esq'); ?>
            <strong><?php echo $pontos->get_pontos_esquerda_formatado() ?></strong>
        </a>

        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_diretos_esq'); ?><?php echo $diretosEsquerda ?>
        </a>

        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_indiretos_esq'); ?><?php echo $indiretosEsquerda ?>
        </a>
        <a href="#" class="list-group-item" style="background: #122A3A;color: #FFF;font-size: 14px;">
            <strong>
            <?php echo $this->lang->line('infor_titulo_rede_direita'); ?> </a>
        </strong>

        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_dir'); ?>
            <strong><?php echo $pontos->get_pontos_direita_formatado() ?></strong>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_diretos_dir'); ?><?php echo $diretosDireita ?>
        </a>
        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_total_indiretos_dir'); ?><?php echo $indiretosDireita ?>
        </a>
        </a>

        <a href="#" class="list-group-item">
            <?php echo $this->lang->line('info_pl_ontem'); ?><strong><?php echo number_format($valorPlOtem, 2, ',', '.') ?></strong>
        </a>
    </div>

    <?php //if( $binario->e_binario()==1 || get_user()->di_data_cad < '2014-03-07 23:59:59'){ ?>
    <div></div>
    <?php // }else{ ?>
    <!--inicio da informação quando o distribuidor estiver bloqueado-->
<!--    <div><?php echo $this->lang->line('info_saldo_bloquedo'); ?><strong>US$<?php echo number_format($saldo->saldo, 2, ',', '.') ?></strong></div>
    <div style="color: red"><?php echo $this->lang->line('info_saldo_bloquedo_info'); ?></div>-->
    <!--fim da informação quando o distribuidor estiber bloqueado-->
    <?php // } ?>

    <!--
        PEDIU PARA RETIRAR
        <div><?php echo $this->lang->line('info_ligotes_env'); ?><strong><?php echo $totalOuroComprados ?></strong></div>
    -->

    <div style="display:none;"><?php echo $this->lang->line('info_atv_fixo'); ?><strong></strong></div>


    <div></div>
<!--    <div><?php echo $this->lang->line('info_data_lib_saque'); ?><strong><?php echo $dataLiberacaoSaque->valor ?></strong></div>
        <div><?php echo $this->lang->line('info_data_dep'); ?><strong><?php echo $dataDepositoConta->valor ?></strong></div>-->
<!--    <div><?php echo $this->lang->line('info_div_rede_soc'); ?><strong><?php echo $divulgacaoRedes->valor; ?></strong></div>-->
<!--    <div><?php echo $this->lang->line('info_vendas_loja'); ?><strong><?php echo $vendaPelaLoja->valor; ?></strong></div>-->
<!--    <div><?php echo $this->lang->line('info_pl'); ?><strong><?php echo $dataProximaPl->valor; ?></strong></div>-->
<!--    <div><?php echo $this->lang->line('info_acoes'); ?><strong><?php echo $participacaoAcoes->valor; ?></strong></div>-->
</div>
<style>
    .info-home-separator{
        border-bottom:1px dashed #CCCCCC;
        margin:5px 0;
    }
    .info-home{
        font-size:11px;
        padding-left:10px;
    }
    .info-home b{
        font-size:12px;
        display:block;
        background:#122A3A;
        color:#fff;
    }	
</style>