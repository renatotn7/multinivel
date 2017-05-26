<?php $this->lang->load('distribuidor/confirmacao_recebimento/confirmacao_recebimento_view'); ?>
<form name="form" id="form" method="POST" action="<?php echo base_url('index.php/confirmacao_recebimento/notificar_nao_recebimento'); ?>">
    <h4><?php echo $this->lang->line('label_confirme_se_recebeu') ?>:</h4>
        <div class="row">
        <div class="span">
            <label class="radio">
                <input type="radio" name="forma_recebimento" id="optionsRadios1" value="1" checked>
                <?php echo $this->lang->line('produto_escolha_pin'); ?>
            </label>
            <label class="radio">
                <input type="radio" name="forma_recebimento" id="optionsRadios2" value="2">
                <?php echo $this->lang->line('produto_escolha_voucher'); ?>
            </label>
        </div>
    </div>
    <br/>

    <div class="row">
        <div class="span3">
            <label for="di_usuario" ><span style="color: red">*</span><?php echo $this->lang->line('label_usuario') ?>:</label>
            <input class="span3" name="di_usuario" id="di_usuario" value="<?php echo get_user()->di_usuario; ?>" type="text" onblur="show_formulario_reclamacao();"/>
        </div>
        <div class="span6">
            <label for="di_email" ><span style="color: red">*</span><?php echo $this->lang->line('label_email') ?>:</label>
            <input class="span6" name="di_email" id="di_email" value="<?php echo get_user()->di_email; ?>" type="text" onblur="show_formulario_reclamacao();"/>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="span">
            <strong>
                <?php echo $this->lang->line('notificacao_informe_telefone_endereco'); ?>            
            </strong>
        </div>
    </div>
    <div class="row">
        <div class="span2">
            <label for="di_celular">Celular:</label>
            <input type="text" name="di_celular" id="di_celular" value="<?php echo get_user()->di_fone1; ?>" class="span2"/>
        </div>

        <div class="span2">
            <label for="di_celular">Telefone:</label>
            <input type="text" name="di_telefone" id="di_telefone" value="<?php echo get_user()->di_fone2; ?>" class="span2"/>
        </div>
    </div>

    <div class="row">
        <div class="span4">
            <label for="di_pais">País:</label>
            <select type="text" name="di_pais" id="di_pais" onchange="atualizar_uf('di_pais', 'di_estado', '<?php echo base_url('index.php/distribuidor/estados') ?>');" id="di_pais">
                <option value="">--Selecione--</option>
                <?php
                $paises = paisModel::getPais();
                foreach ($paises as $key => $pais_value) {
                    ?>
                    <option <?php echo DistribuidorDAO::getPais(get_user()->di_cidade)->ps_id == $pais_value->ps_id ? 'selected' : '' ?> value="<?php echo $pais_value->ps_id ?>"><?php echo $pais_value->ps_nome ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="span4">
            <label for="di_estado">Estado:</label>
            <select type="text" name="di_estado" id="di_estado">
                <option value="">--Selecione--</option>
                <?php
                $estados = $this->db->get('estados')->result();
                foreach ($estados as $key => $estados_value) {
                    ?>
                    <option <?php echo DistribuidorDAO::getCidade(get_user()->di_cidade)->ci_estado == $estados_value->es_id ? 'selected' : '' ?> value="<?php echo $estados_value->es_id ?>"><?php echo $estados_value->es_nome ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="span4">
            <label for="di_cidade">Cidade:</label>
            <input type="text" name="di_cidade" id="di_cidade" value="<?php echo DistribuidorDAO::getCidade(get_user()->di_cidade)->ci_nome; ?>" class="span4"/>
        </div>

        <div class="span4">
            <label for="di_rua">Rua:</label>
            <input type="text" name="di_rua" id="di_rua" value="<?php echo get_user()->di_endereco; ?>" class="span4"/>
        </div>
    </div>

    <div class="row">
        <div class="span4">
            <label for="di_numero">Número:</label>
            <input type="text" name="di_numero" id="di_numero" value="<?php echo get_user()->di_numero; ?>" class="span4"/>
        </div>

        <div class="span4">
            <label for="di_complemento">Complemento:</label>
            <input type="text" name="di_complemento" id="di_bairro" value="<?php echo get_user()->di_complemento; ?>" class="span4"/>
        </div>
    </div>

    <div class="row">
        <div class="span4">
            <label for="di_cep">Código Postal:</label>
            <input type="text" name="di_cep" id="di_cep" value="<?php echo get_user()->di_cep; ?>" class="span4"/>
        </div>

        <div class="span4">
            <label for="di_bairro">Bairro:</label>
            <input type="text" name="di_bairro" id="di_bairro" value="<?php echo get_user()->di_bairro; ?>" class="span4"/>
        </div>
    </div>

    <hr/>
    <button class="btn btn-success"><?php echo $this->lang->line('label_botao_confirmar') ?></button>

    <br/>
    <div class="row">
        <div class="span">
            <strong>
                <?php echo $this->lang->line('notificacao_infomartivo_logistica'); ?>            
            </strong>
        </div>
    </div>
</form>
<script>
    function show_formulario_reclamacao() {
        if ($('#di_usuario').val() != "" && $('#di_email').val() != "") {
            $('#formulario_reclamacao').fadeIn(500);
        } else {
            $('#formulario_reclamacao').fadeOut(500);
        }
    }

    //Função para atualizar estado do endereço de entrega.
    function atualizar_uf(div_origem, div_destino, url, es_nome) {
        var divO = div_origem
        var divD = div_destino
        var elemento, elementoD;


        //Validando os elemento de destino se foi passado existe
        if ($("." + divD).length > 0) {
            $("." + divD).html("<option value=''>Aguarde...</option>");
            elementoD = "." + divD;
        }
        if ($("#" + divD).length > 0) {
            $("#" + divD).html("<option value=''>Aguarde...</option>");
            elementoD = "#" + divD;
        }
        if ($("#" + divD).length == 0 && $("." + divD).length == 0) {
            alert('O elemento da dom [' + divD + '] não exite.');
        }

        //Validado se o elemento de origem foi passado um elemento válido.
        if ($("." + divO).length > 0) {
            elemento = "." + divO;
        }
        if ($("#" + divO).length > 0) {
            elemento = "#" + divO;
        }
        if ($("#" + divO).length == 0 && $("." + divO).length == 0) {
            alert('O elemento da dom [' + divO + '] não exite.');
        }
        
        $(elementoD).attr("");

        $.ajax({
            url: url,
            type: 'POST',
            data: {es_pais: $(elemento + ' :selected').val()},
            dataType: 'json',
            success: function(cidadesJson) {
                var txt_cidades = "<option value=''>--Selecione--</option>";
                $.each(cidadesJson, function(index, cidade) {
                    txt_cidades += "<option class='cid-" + cidade.es_id + "' value='" + cidade.es_id + "'>" + cidade.es_nome + "</option>";
                });

                $(elementoD).html(txt_cidades);
                $(elementoD).removeAttr("");

                if (es_nome != "undefined") {
                    //Selecionando o select pelo texto.
                    /**
                     * Porque selecionar pelo texto? isto corre pelo seguinte 
                     * fato, sendo ele que na Plataforma de Pagamento não retorna o id e sim 
                     * nome do pais, selecionando pelo texto evita mais uma consulta 
                     * via ajax. 
                     */

                    $("#di_uf option").each(function() {
                        if ($(this).text() == es_nome) {
                            $(this).attr('selected', true);
                        }
                    });

                }
            }
        });

        delete_loading();
    }
    function delete_loading() {
        $(".loading-ajax").remove();
    }
</script>
