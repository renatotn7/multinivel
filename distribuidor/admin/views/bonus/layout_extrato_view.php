<?php $this->lang->load('distribuidor/bonus/layout_extrato_view'); ?>
<div class="page-title">
    <div class="title_left">
        <h3><?php echo $this->lang->line('label_financeiro'); ?></h3>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
            <?php
            function trim_value(&$value) {
                $value = trim($value);
            }

            $dados = isset($dados) ? $dados : 'meu_saldo';
            $this->load->view('bonus/menu_extrato_view', array('active' => $dados));
            echo "<br class='clearfix'>";

            if ($dados == "pagar_pedido")
            ?>


            <?php
            $this->load->view("bonus/{$dados}_view");
            ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {

        $(".moeda").maskMoney({symbol: "R$", decimal: ",", thousands: "."});

        /*ESTADOS AJAX*/
        $(".ajax-uf").change(function() {

            var uf_sel_id = $(this).val();
            $(".recebe-cidade").html("<option value=''><?php echo $this->lang->line('label_aguarde'); ?></option>");
            $.ajax({
                url: '<?php echo base_url('index.php/distribuidor/cidades') ?>',
                type: 'POST',
                data: {es_id: uf_sel_id},
                dataType: 'json',
                success: function(cidadesJson) {
                    var txt_cidades = "<option value=''>--<?php echo $this->lang->line('label_selecione_cidade'); ?>--</option>";
                    $.each(cidadesJson, function(index, cidade) {
                        txt_cidades += "<option value='" + cidade.ci_id + "'>" + cidade.ci_nome + "</option>";
                    });
                    $(".recebe-cidade").html(txt_cidades);
                    $(".recebe-cidade").removeAttr("disabled");
                }
            });

        });
        /*ESTADOS AJAX*/

        /*MASCARAS*/
        $(".mtel").mask("(99)9999-9999?9");
        $(".mcep").mask("99999-999");
        $(".mcpf").mask("999.999.999-99");
        $(".mcpf_number").mask("99999999999");
        $(".mdata").mask("99/99/9999");
        $(".mcnpj").mask("99.999.999/9999-99");
        $(".mhora").mask("99:99:99");
        $(".mdata_metade").mask("99/9999");
    });
</script>