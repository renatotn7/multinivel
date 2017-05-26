<?php
$cambios = $this->db->join('moedas', 'moe_id=camb_id_moedas')
        ->join('pais', 'ps_id=camb_id_pais')
        ->get('moeda_cambio')
        ->result();

$dadosCambio = array();
if (get_parameter('id')) {

    $dadosCambio = $this->db->where('camb_id', get_parameter('id'))
            ->join('moedas', 'moe_id=camb_id_moedas')
            ->join('pais', 'ps_id=camb_id_pais')
            ->get('moeda_cambio')
            ->row();
}

$dados_editar_cambio = new stdClass();
$dados_editar_cambio->camb_id_pais = '';
$dados_editar_cambio->camb_id_moedas = '';
$dados_editar_cambio->camb_valor = '';
$dados_editar_cambio->camb_taxas = '';
$dados_editar_cambio->camb_frete = '';
$dados_editar_cambio->camb_impostos = '';
$dados_editar_cambio->camb_valor_euro = '';


if (count($dadosCambio) > 0) {
    $dados_editar_cambio = $dadosCambio;
}
?>

<div class="box-content min-height">
    <div class="box-content-header">
        Taxas/Câmbio/Fretes
    </div>
    <div class="box-content-body">
        <form name="form" method="post" action="
        <?php
        echo get_parameter('id') ?
                base_url('index.php/financeiro/update_taxas_cambio_frete') :
                base_url('index.php/financeiro/salvar_taxas_cambio_frete');
        ?>
              " >

            <?php if (get_parameter('id')) { ?>
                <input type="hidden" name="camb_id" value="<?php echo get_parameter('id') ?>">
            <?php } ?>
            <div class="row">
                <div class="span3">
                    <label for="camb_id_pais"><strong>Pais:</strong></label>
                    <select onchange="atualizar_pais();" id="camb_id_pais" name="camb_id_pais">
                        <option>--Selecione--</option>
                        <?php
                        $paises = $this->db->get('pais')->result();
                        foreach ($paises as $key => $pais) {
                            ?>
                            <option 
                            <?php echo $dados_editar_cambio->camb_id_pais == $pais->ps_id ? "selected" : ""; ?>  
                                <?php echo get_parameter('camb_camb_id_pais') ? "selected" : ""; ?> value="<?php echo $pais->ps_id; ?>"><?php echo $pais->ps_nome; ?></option>
                            <?php } ?>
                    </select>
                </div>
                <div class="span3">
                    <label for="Moeda"><strong>Moeda:</strong></label>
                    <select  id="moeda" name="camb_id_moedas">
                        <option>--Selecione--</option>
                        <?php
                        $moedas = $this->db->get('moedas')->result();
                        foreach ($moedas as $key => $moeda) {
                            ?>
                            <option 
                            <?php echo $dados_editar_cambio->camb_id_moedas == $moeda->moe_id ? "selected" : ""; ?>
                                <?php echo get_parameter('camb_id_moedas') ? "selected" : ""; ?>  value="<?php echo $moeda->moe_id; ?>"><?php echo $moeda->moe_nome . " ({$moeda->moe_sibolo})"; ?></option>
                            <?php } ?>
                    </select>
                </div>

                <div class="span4">
                    <label for="camb_valor"><strong>Valor do Câmbio(US$):</strong></label>
                    <input type="text" name="camb_valor" class="span2 moeda" id="camb_valor" 
                           value="<?php echo $dados_editar_cambio->camb_valor; ?>"/>
                </div>

            </div>
            <div class="row">
                <div class="span2">
                    <label for="camb_taxas"><strong>Taxa(%):</strong></label>
                    <input class="span2 moeda" type="text" name="camb_taxas" id="camb_taxas"
                           value="<?php echo $dados_editar_cambio->camb_taxas; ?>"/>
                </div>
                <div class="span2">
                    <label for="camb_frete"><strong>Frete(%):</strong></label>
                    <input class="span2 moeda" type="text" name="camb_frete" id="camb_frete" 
                           value="<?php echo $dados_editar_cambio->camb_frete; ?>"/>
                </div>
                <div class="span2">
                    <label for="camb_impostos"><strong>Imposto(%):</strong></label>
                    <input class="span2 moeda" type="text" name="camb_impostos" id="camb_impostos" 
                           value="<?php echo $dados_editar_cambio->camb_impostos; ?>"/>
                </div>
                <!--<div class="span3">
                    <label for="camb_valor_euro"><strong>Valor do Euro(US$):</strong></label>
                    <input type="text" name="camb_valor_euro" disabled class="span2 " id="camb_valor_euro" 
                           value="<?php echo conf()->cambio_euro; ?>"/>
                </div>-->
                <br/>
            </div>
            <div class="row">
                <div class="span">

                    <button type="submit" class="btn btn-primary" ><?php echo get_parameter('id') ? 'Editar' : 'Salvar'; ?></button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-hover">
            <tr>
            <thead>
            <th>Nº</th>
            <th>País</th>
            <th>Moeda</th>

            <th>Tx(%)</th>
            <th>Imposto.(%)</th>
            <th>Frete(%)</th>
            <th>total(%)</th>
            <th>Câmbio Local/$</th>

            <th>Diretor Internacional(%)</th>
            <th>Diretor Senior(%)</th>
            <th>Diretor(%)</th>
            <th>Supervisor(%)</th>
            <th>Membership(%)</th>

            <th></th>
            </thead>
            </tr>
            <?php if (count($cambios) == 0) { ?>
                <tr>
                    <td colspan="8" style="text-align: center;" ><strong>Nenhum registro encontrado</strong></td>
                </tr>
                <?php
            } else {
                foreach ($cambios as $cambio) {
                    ?>
                    <tr>
                        <td>
                            <?php if ($cambio->status == 100) { ?>
                                <a href="#"  class="teste" data-toggle="popover" data-placement="top" data-content="<p><img style='float: left;padding: 4px;' src='<?php echo base_url('public\imagem\config\warning.png');?>'/>
                                   Descrição: <?php echo $cambio->description ?></p>" title="" data-original-title="Status: <?php echo $cambio->status ?>"><img src="<?php echo base_url('public\imagem\config\warning.png');?>"/></a>
                            <?php }else{ ?>
                            <?php echo $cambio->camb_id; ?>
                            <?php }?>
                        </td>
                        <td>
                            <?php echo $cambio->ps_nome; ?>
                        </td>
                        <td>
                            <?php echo $cambio->moe_nome; ?> (<?php echo $cambio->moe_sibolo; ?>)
                        </td>
                        <td>
                            <?php echo number_format($cambio->camb_taxas, '2'); ?>
                        </td>
                        <td>
                            <?php echo number_format($cambio->camb_impostos, '2'); ?>
                        </td>
                        <td>
                            <?php echo number_format($cambio->camb_frete, '2'); ?>
                        </td>                
                        <td>
                            <?php echo number_format($cambio->camb_frete + $cambio->camb_impostos + $cambio->camb_taxas, '2'); ?>
                        </td>
                        <td>
                            <?php echo number_format($cambio->camb_valor, '2'); ?>
                        </td>
                        <td>
                            <?php echo valor_plano_percetual_tx(103, $cambio); ?><br/>
                            <?php echo valor_plano_euro_relacao_dolar(103, $cambio); ?><br/>
                            <?php echo valor_plano_relacao_dolar(103, $cambio); ?><br/>
                        </td>
                        <td>
                            <?php echo valor_plano_percetual_tx(102, $cambio); ?><br/>
                            <?php echo valor_plano_euro_relacao_dolar(102, $cambio); ?><br/>
                            <?php echo valor_plano_relacao_dolar(102, $cambio); ?><br/>
                        </td>
                        <td>
                            <?php echo valor_plano_percetual_tx(101, $cambio); ?><br/>
                            <?php echo valor_plano_euro_relacao_dolar(101, $cambio); ?><br/>
                            <?php echo valor_plano_relacao_dolar(101, $cambio); ?><br/>
                        </td>
                        <td>
                            <?php echo valor_plano_percetual_tx(100, $cambio); ?><br/>
                            <?php echo valor_plano_euro_relacao_dolar(100, $cambio); ?><br/>
                            <?php echo valor_plano_relacao_dolar(100, $cambio); ?><br/>
                        </td>
                        <td>
                            <?php echo valor_plano_percetual_tx(99, $cambio); ?><br/>
                            <?php echo valor_plano_euro_relacao_dolar(99, $cambio); ?><br/>
                            <?php echo valor_plano_relacao_dolar(99, $cambio); ?><br/>
                        </td>
                        <td>
                            <a href="<?php echo base_url('/index.php/financeiro/taxas_cambio_frete') . '?id=' . $cambio->camb_id; ?>" class="btn">Editar</a>
                            <a href="javascript:void()" onclick="excluir('<?php echo base_url("/index.php/financeiro/excluir_taxas_cambio_frete?camb=") . $cambio->camb_id; ?>');" class="btn">Excluir</a>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </div>
</div>

<script type="text/javascript">
    function excluir(url) {
        if (confirm("Deseja realmente excluir?", "Atenção")) {
            window.location = url;
        }
    }

    function atualizar_pais() {
        $("#moeda").prop('disabled', true);
        var html = "";
        $.ajax({
            url: '<?php echo base_url('index.php/financeiro/atualizar_moeda_ajax'); ?>',
            type: 'POST',
            data: {has_moed_id_pais: $("#camb_id_pais :selected").val()},
            dataType: 'json',
            success: function(cidadesJson) {
                $("#moeda").removeAttr("disabled");
                for (var x in cidadesJson) {
                    html += "<option value='" + cidadesJson[x].moe_id + "'>" + cidadesJson[x].moe_nome + "(" + cidadesJson[x].moe_sibolo + ")</option>";
                }
                $("#moeda").html(html);
            }
        });


    }

    $('.teste').each(function() {
        $(this).popover(
                {'trigger':'click','html':true});
    });

</script>