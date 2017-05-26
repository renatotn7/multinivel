<?php
get_instance()->lang->load('publico/distribuidor/cadastro_view');
if (!function_exists('g_dados')) {

    function g_dados($key) {
        return isset($_SESSION['form_cad'][$key]) ? $_SESSION['form_cad'][$key] : '';
    }

}

if (!function_exists('base_url')) {

    function base_url($uri = '') {
        $CI = & get_instance();
        return $CI->config->base_url($uri);
    }

}

/**
 * Cadastro planos
 */
$objcambio = array();
if (isset($id_pais) || g_dados('di_pais') != '') {

    if (g_dados('di_pais') != '') {
        $id = g_dados('di_pais');
    }

    if (isset($id_pais)) {
        $id = $id_pais;
    }

    $objcambio = $this->db->where('camb_id_pais', $id)
            ->join('moedas', 'moe_id=camb_id_moedas')
            ->join('pais', 'ps_id=camb_id_pais')
            ->get('moeda_cambio')
            ->row();
}

$procnetagem = false;
if ($this->input->get('pa_id')) {
    $this->db->where('pa_id', $this->input->get('pa_id'));
    $procnetagem = true;
}

$codigo_promocional = g_dados('codigo_promocional');
if (!empty($codigo_promocional)) {
    $this->db->where('pa_id', 99);
    $procnetagem = true;
}
?>
<div style="margin:0px auto; margin-top: 10px;">
    <div class="box-content-body border-radios">
        <div>
            <h4><?php echo $this->lang->line('info_escolha_plano_a_baixo'); ?></h4>
        </div>
        <table width="<?php echo ($procnetagem == false ? '95%' : ''); ?>">
            <tbody>
                <?php
                $planos = $this->db->where('pa_id !=104')
                                ->order_by('pa_valor', 'asc')
                                ->get('planos')->result();
                ?>
                <tr>
                    <td>&nbsp;</td>
                    <?php
                    // $valor_member_ship = 0;
                    foreach ($planos as $k => $p) {
                        // $style = '';
                        // if ($p->pa_id == 99) {
                        //     $valor_member_ship = $p->pa_valor;
                        //     $style = 'margin: 46px;margin-top: 0px;margin-bottom: 0px;';
                        // }
                        ?>
                        <td width="<?php echo ($procnetagem == false ? '15%' : ''); ?>" align="center" valign="top" class="planos plano-<?php echo $p->pa_id; ?>">
                            <img style="max-width: 130px !important; <?php // echo $style; ?>" alt="<?php echo $p->pa_descricao; ?> " title="<?php echo $p->pa_descricao; ?> " src="<?php echo base_url("public/imagem/" . get_instance()->lang->line('url_imagem_plano') . '/p-' . $p->pa_id . ".png") ?>" >
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td></td>
                    <?php foreach ($planos as $k => $p) { ?>
                        <td align="center" valign="top" class="planos plano-<?php echo $p->pa_id; ?>">
                            <input <?php echo (g_dados('plano') == $p->pa_id ? 'checked' : ''); ?> class="validate[required]" type="radio" name="plano" value="<?php echo $p->pa_id; ?>">
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>
                        <?php echo get_instance()->lang->line('label_valor_cambio_dolar'); ?><br/>
                        <?php //echo get_instance()->lang->line('label_valor_cambio_euro_dolar'); ?><br/>
                        <?php //echo get_instance()->lang->line('label_valor_cambio_euro_dolar'); ?><br/>

                        <?php
                        if (count($objcambio) > 0) {
                            echo get_instance()->lang->line('label_valor_cambio_moeda_local');
                        }
                        ?>
                    </td>
                    <?php foreach ($planos as $k => $p) { ?>
                        <td align="center" valign="top" class="planos valor-<?php echo $p->pa_id; ?>">
                            <?php /*if (count($objcambio) > 0) { ?>
                                <?php //echo valor_plano_percetual_tx($p->pa_id, $objcambio); ?>
                                <?php //echo valor_plano_euro_relacao_dolar($p->pa_id, $objcambio); ?>
                                <?php echo valor_plano_relacao_dolar($p->pa_id, $objcambio); ?>
                            <?php } else {*/ ?>
                                US$ <?php echo $p->pa_valor; //+ ($p->pa_id != 99 ? $valor_member_ship : 0); ?>
                                <?php /* € :<?php echo $p->pa_valor_euro; */ ?>
                            <?php //} ?>
                        </td>
                    <?php } ?>
                </tr>
            </tbody>
        </table>
        <strong>* <?php echo get_instance()->lang->line('label_notificacao_taxa'); ?></strong>
    </div>
</div>
