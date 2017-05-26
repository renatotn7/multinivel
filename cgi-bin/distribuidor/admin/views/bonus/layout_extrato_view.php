<?php $this->lang->load('distribuidor/bonus/layout_extrato_view'); ?>
<div class="box-content min-height">
    <div class="box-content-header"><?php echo $this->lang->line('label_financeiro'); ?></div>
    <div class="box-content-body">

        <div class="panel">

            <?php

            function trim_value(&$value) {
                $value = trim($value);
            }

            $dados = isset($dados) ? $dados : 'meu_saldo';
            $this->load->view('bonus/menu_extrato_view', array('active' => $dados));

            if ($dados == "pagar_pedido")
                
                ?>


            <?php
            $this->load->view("bonus/{$dados}_view");
            ?>

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

