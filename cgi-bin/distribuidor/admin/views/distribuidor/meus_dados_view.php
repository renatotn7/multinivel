<?php
$this->lang->load('distribuidor/distribuidor/meus_dados_view');
$erros = isset($_SESSION['form_cad_error']) ? $_SESSION['form_cad_error'] : false;

$d = $this->db
                ->join('cidades', 'ci_id=di_cidade')
                ->join('distribuidores_endereco', 'end_id_distribuidor=di_id', 'left')
                ->join('distribuidor_pessoa_juridica', 'di_id = dpj_id_distribuidor', 'left')
                ->where('di_id', get_user()->di_id)->get('distribuidores')->row();

$cv = $d->di_conta_verificada == 1 && $d->di_contrato == 1;
?>

<?php
if ($erros) {
    ?>
    <div style="margin:1px;margin-bottom:5px;" class="alert alert-danger">
        <?php
        foreach ($erros as $e) {
            ?>
            <div>- <?php echo $e ?></div>
        <?php } ?>
    </div>
<?php }
?>

<style>.table input, .table select{margin-bottom:1px;}</style>
<form name="formulario" method="post" action="<?php echo base_url('index.php/distribuidor/salvar_info') ?>">
<input  type="hidden" name="url" value="<?php echo current_url() ?>" />  
<table width="100%" border="0">
    <tr>
        <td>
            <label>
                <?php echo $this->lang->line('label_tipo_pessoa_cadastro'); ?>  
            </label>
            <label class="radio" style="cursor: pointer;">
                <input  type="radio" name="tipopessoa" id="tipopessoa" value="0" onclick="ativar_pessoa_juridica(1);" checked="">
                Individuos 
            </label>

            <label class="radio" style="cursor: pointer;">
                <input  type="radio" name="tipopessoa" <?php echo e_pessoa_juridica(get_user()->di_id) ? 'checked' : ''; ?> id="tipopessoa" onclick="ativar_pessoa_juridica(2)" value="1">
                Corporativo 
            </label>
        </td>
    </tr>
</table>


<!--dados empresariais-->
<div class="juridico" style="display: <?php echo e_pessoa_juridica(get_user()->di_id) ? 'block' : 'none'; ?>">
    <table borde="0"  width="100%" >
        <tr>
            <td>
                <h3><?php echo $this->lang->line('titulo_dados_comporativos'); ?></h3>
                <hr/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="dpj_nome_empresa"><?php echo $this->lang->line('label_nome_empresa'); ?></label>
                <input  style="width:95%;" type="text" name="dpj_nome_empresa" id="dpj_nome_empresa" placeholder="" class="validate[required]" value="<?php echo $d->dpj_nome_empresa; ?>">
            </td>
        </tr>
        <tr>
            <td >
                <label for="dpj_nome_empresa"><?php echo $this->lang->line('label_tx_empresa'); ?></label>
                <input  style="width:90%;" type="text" name="dpj_tx_identificacao" id="dpj_tx_identificacao" placeholder="" class="validate[required]" onkeyup="num(this);" value="<?php echo ($d->dpj_tx_identificacao); ?>">
            </td>
            <td>
                <label for="dpj_nome_empresa"><?php echo $this->lang->line('label_diregente_responsavel_empresa'); ?></label>
                <input  style="width:90%;" type="text" name="dpj_diregente_responsavel" id="dpj_diregente_responsavel" placeholder="" class="validate[required]" value="<?php echo $d->dpj_diregente_responsavel; ?>">
            </td>
        </tr>
        <tr>
            <td>
                <label for="dpj_nome_empresa"><?php echo $this->lang->line('label_diretor_empresa'); ?></label>
                <input  style="width:90%;" type="text" name="dpj_diretor" id="dpj_diretor" placeholder="" class="validate[required]" value="<?php echo $d->dpj_diretor; ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="dpj_nome_empresa"><?php echo $this->lang->line('label_endereco_empresa'); ?></label>
                <input  style="width:95%;" type="text" name="dpj_endereco" id="dpj_endereco" placeholder="" class="validate[required]"  value="<?php echo $d->dpj_endereco; ?>">
            </td>
        </tr>
    </table> 
    <!--fim dos dados empresariais-->
</div>

<table width="100%"  border="0">
    <tr>
        <td colspan="2">
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h3><?php echo $this->lang->line('label_dados_usuario'); ?></h3>
            <hr>
        </td>
    </tr>

    <tr>
        <td width="304px">
            <label for="di_email" ><?php echo $this->lang->line('label_email'); ?>:</label>
            <input  name="di_email"  id="di_email" type="text" size="50" class="validar-atm" value="<?php echo $d->di_email ?>" />
        </td>
        <td>
            <label for="di_niv" ><?php echo $this->lang->line('label_niv'); ?>:</label>
            <input  name="di_niv" id="di_niv" type="text" size="50" value="<?php echo $d->di_niv ?>" />
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_nome" ><?php echo $this->lang->line('label_nome'); ?>:</label>
            <input  name="di_nome" id="di_nome" type="text" class="validate[required]" size="50" value="<?php echo $d->di_nome; ?>" />
        </td>
        <td>
            <label for="di_ultimo_nome" ><?php echo $this->lang->line('label_nome'); ?>:</label>
            <input  name="di_ultimo_nome" id="di_ultimo_nome" type="text" class="validate[required]" size="50" value="<?php echo ($d->di_ultimo_nome); ?>" />
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_tipo_documento" ><?php echo $this->lang->line('label_tipo_documento'); ?>:</label>
            <input  name="di_tipo_documento" id="di_tipo_documento" type="text" size="50" value="<?php echo $d->di_tipo_documento; ?>" class="validate[required]" />
        </td>
        <td>
            <label for="di_rg" ><?php echo $this->lang->line('label_numero_documento'); ?>:</label>
            <input  name="di_rg" id="di_rg" type="text" size="50" value="<?php echo ($d->di_rg); ?>" class="validate[required]" />
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_fone1" ><?php echo $this->lang->line('label_telefone'); ?>:</label>
            <input  type="text" value="<?php echo ($d->di_fone1); ?>" name="di_fone1" id="di_fone1" class="validate[required] fone">
        </td>
        <td>
            <label for="di_fone2" ><?php echo $this->lang->line('label_celular'); ?>:</label>
            <input  type="text" value="<?php echo ($d->di_fone2); ?>" name="di_fone2" id="di_fone2" class="validate[required] fone">
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_data_nascimento" ><?php echo $this->lang->line('label_data_nascimento'); ?>:</label>
            <input  type="text" name="di_data_nascimento" id="di_data_nascimento" value="<?php echo '**/**/' . date('Y', strtotime($d->di_data_nascimento)); ?>" placeholder="dd/mm/aaaa" class="validate[required]" size="20" >
        </td>
        <td>
            <label for="di_cidade_nascimento" ><?php echo $this->lang->line('label_lugar_nascimento'); ?>:</label>
            <input  type="text" name="di_cidade_nascimento" id="di_cidade_nascimento" value="<?php echo $d->di_cidade_nascimento; ?>" class="validate[required]">
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_pais_nascimento" ><?php echo $this->lang->line('label_pais_nascimento'); ?>:</label>

            <select  name="di_pais_nascimento" id="di_pais_nascimento"  class="verificar_dastrado_por_pais estado1 validate[required]"  >
                <option value="0">--<?php echo $this->lang->line('label_selecione'); ?>--</option>
                <?php
                $pais_nascimento = $this->db
                        ->where_not_in('ps_id', array('173', '224', '232'))
                        ->where('ps_id !=2')
                        ->order_by('ps_nome', 'asc')
                        ->get('pais')
                        ->result();

                foreach ($pais_nascimento as $p) {
                    ?>
                    <option value="<?php echo $p->ps_id ?>" <?php echo $d->di_pais_nascimento == $p->ps_id ? 'selected' : ''; ?> ><?php echo $p->ps_nome ?></option>
                <?php } ?>
            </select>
        </td>
        <td>
            <label for="di_sexo"><?php echo $this->lang->line('label_sexo'); ?>:</label>
            <select  name="di_sexo" id="di_sexo">
                <option value="">--<?php echo $this->lang->line('label_selecione'); ?>--</option>
                <option <?php echo $d->di_sexo == 'M' ? 'selected' : '' ?> value="M">Masculino</option>
                <option <?php echo $d->di_sexo == 'F' ? 'selected' : '' ?> value="F">Feminino</option>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2"></hr></td>
    </tr>
    <tr>
        <td colspan="2">
            <h3><?php echo $this->lang->line('label_endereco'); ?></h3>
            <hr>
        </td>

    </tr>
    <tr>
        <td>
            <label for="di_pais"><?php echo $this->lang->line('label_pais'); ?>:</label>
            <?php
            $pais_cadastro = $this->db
                            ->where_not_in('ps_id', array('173', '224', '232'))
                            ->where('ps_id !=2')
                            ->order_by('ps_nome', 'asc')
                            ->get('pais')->result();
            ?>

            <select  name="di_pais" id="di_pais" class="validate[required] pais1" onchange="atualizar_uf('pais1', 'di_uf', '<?php echo base_url('index.php/distribuidor/estados') ?>');">

                <option value="0">--<?php echo $this->lang->line('label_selecione'); ?>--</option>
                <?php foreach ($pais_cadastro as $p) { ?>
                    <option value="<?php echo $p->ps_id ?>" 
                    <?php
                    if (count(DistribuidorDAO::getPais($d->di_cidade)) > 0) {
                        echo DistribuidorDAO::getPais($d->di_cidade)->ps_id == $p->ps_id ? 'selected' : '';
                    }
                    ?>>
                                <?php echo $p->ps_nome ?>
                    </option>
                <?php } ?>
            </select>
        </td>
        <td>
            <label for="di_uf"><?php echo $this->lang->line('label_estado'); ?> (<?php echo $this->lang->line('label_uf'); ?>):</label>
            <?php
            $estado = $this->db->get('estados')->result();
            echo CHtml::dropdow('di_uf', CHtml::arrayDataOption($estado, 'es_id', 'es_nome'), array(
                'empty' => $this->lang->line('label_estado'),
                'class' => "estado1 di_uf recebe-estado validate[required]",
                'selected' => $d->di_uf,
                '' => 'true',
                'id' => 'di_uf'));
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_cidade"><?php echo $this->lang->line('label_cidade'); ?>:</label>
            <input  type="text" name="di_cidade" id="di_cidade"  autocomplete="off"  class=" validate[required]" value="<?php
            if (count(DistribuidorDAO::getCidade($d->di_cidade)) > 0) {
                echo DistribuidorDAO::getCidade($d->di_cidade)->ci_nome;
            }
            ?>" /></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">
            <label for="di_endereco"><?php echo $this->lang->line('label_rua'); ?>:</label>
            <input  type="text"  name="di_endereco" id="di_endereco" value="<?php echo ($d->di_endereco); ?>" size="50" style="width: 517px;" class="validate[required]" />
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_numero"><?php echo $this->lang->line('label_numero'); ?>:</label>
            <input  type="text" class="validate[required]" id="di_numero" value="<?php echo ($d->di_numero); ?>"  name="di_numero" size="30" onkeyup="num(this);"  >
        </td>
        <td>
            <label for="di_complemento">Complemento:</label>
            <input  type="text" id="di_complemento" name="di_complemento"  value="<?php echo $d->di_complemento; ?>" class="validate[required]" size="14">
        </td>
    </tr>
    <tr>
        <td>
            <label for="di_cep"><?php echo $this->lang->line('label_cep'); ?>:</label>
            <input  type="text" name="di_cep" id="di_cep" value="<?php echo ($d->di_cep); ?>" class="validate[required]" size="14">
        </td>
        <td>
            <label for="di_bairro"><?php echo $this->lang->line('label_bairro'); ?>:</label>
            <input  type="text" name="di_bairro" id="di_bairro" size="30" value="<?php echo $d->di_bairro; ?>" class="validate[required]" />
        </td>
    </tr>
    <!--inicio do local de entrega-->
    <tr>
        <td colspan="2">
            <h3><?php echo $this->lang->line('label_local_entrega'); ?></h3>
            <hr>
        </td>
    </tr>
    <tr>
        <td>
            <label><?php echo $this->lang->line('label_pais'); ?></label>
            <select name="end_pais" class="validate[required]" onchange="atualizar_estados_entrega();" id="pais2">
                <?php
                $pais_cadastro = $this->db
//                                ->where_not_in('ps_id', array('173', '224', '232'))
                                ->group_by('ps_sigla')
                                ->order_by('ps_nome', 'asc')
                                ->get('pais')->result();
                ?>

                <option value="0"><?php echo $this->lang->line('label_selecione'); ?></option>
                <?php foreach ($pais_cadastro as $p) { ?>
                    <option  <?php echo $d->end_pais == $p->ps_id ? 'selected' : ''; ?> value="<?php echo $p->ps_id ?>"><?php echo $p->ps_nome ?></option>
                <?php } ?>

            </select>
        </td>
    </tr>
    <tr>
        <td>
            <label for="end_estado"><?php echo $this->lang->line('label_estado'); ?></label>
            <?php
            $estado = $this->db->get('estados')->result();
            echo CHtml::dropdow('end_estado', CHtml::arrayDataOption($estado, 'es_id', 'es_nome'), array(
                'empty' => $this->lang->line('label_estado'),
                'class' => "recebe-estado2 validate[required]",
                'selected' => $d->end_estado,
                'id' => 'form-validation-field-1'));
            ?>
        </td>
        <td>
            <label for="end_cidade"><?php echo $this->lang->line('label_cidade'); ?></label>
            <?php
            $cidade_nome = '';
            if ($d->end_cidade != "" && is_numeric($d->end_cidade)) {
                $cidade_nome = $this->db->where('ci_id', $d->end_cidade)
                                ->get('cidades')->row();
                //Pega o valor.
                if (count($cidade_nome) > 0) {
                    $cidade_nome = $cidade_nome->ci_nome;
                }
            }
            ?>
            <input type="text" name="end_cidade" id="end_cidade"  autocomplete="off"  class=" validate[required]" value="<?php echo $cidade_nome; ?>" />
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <label for="end_endereco"><?php echo $this->lang->line('label_endereco'); ?></label>
            <input type="text" style="width: 517px;" name="end_endereco" id="end_endereco" value="<?php echo $d->end_endereco; ?>"/>
        </td>
    </tr>
    <tr>
        <td>
            <label for="end_numero"><?php echo $this->lang->line('label_numero'); ?></label>
            <input type="text" name="end_numero" id="end_numero" onkeyup="return num(this);" value="<?php echo $d->end_numero; ?>"/>
        </td>
        <td>
            <label><?php echo $this->lang->line('label_complemento'); ?></label>
            <input type="text" name="end_complemento" id="end_complemento" value="<?php echo $d->end_complemento; ?>"/>  
        </td>
    </tr>
    <tr>
        <td>
            <label for="end_numero"><?php echo $this->lang->line('label_cep'); ?></label>
            <input type="text" name="end_cep" id="end_cep" value="<?php echo $d->end_cep; ?>"/>  
        </td>
        <td>
            <label><?php echo $this->lang->line('label_bairro'); ?></label>
            <input type="text" name="end_bairro" id="end_bairro" value="<?php echo $d->end_bairro; ?>"/>  
        </td>
    </tr>

    <!--inicio da senha-->
<!--        <tr>
        <td colspan="2">
            <h3><?php echo $this->lang->line('label_altera_senha'); ?></h3>
            <hr>
        </td>
    </tr>


    <tr >
        <td colspan="2">
            <div class="alert">
                <label for="di_senha"><?php echo $this->lang->line('label_senha_atual'); ?>:</label>
                <input  id="di_senha" name="di_senha" type="password"  placeholder="***************" size="20" />

                <br/>
                <label for="di_senha_novo"><?php echo $this->lang->line('label_senha_nova'); ?>:</label>
                <input  id="di_senha_novo" name="di_senha_novo" type="password"  placeholder="***************" size="20" /> 
    <?php echo $this->lang->line('label_notificacao_senha'); ?>
            </div>
        </td>
    </tr>-->

    <!--   
       <tr>
           <td colspan="2"> 
               <label for="di_pw"><?php //echo $this->lang->line('label_senha_seguranca');       ?>:</label>
               <input  id="di_pw" name="di_pw" type="password" size="20" placeholder="***************" />
           </td>
       </tr>-->
    <!--fim da senha-->

<!--                <tr>
                    <td>
                        <div class="alert">IP do Cadastro: <?php //echo $d->di_ip_cadastro        ?></div>
                    </td>
                </tr>-->
    <tr>
        <td colspan="2">
            <br/>
            <br/>
        </td>
    </tr>
        <tr>
        <td colspan="2">
            <p><input  type="submit" class="btn btn-success" value="Salvar Dados do Distribuidor"></p>
        </td>
    </tr>
</table>

</form>
<script type="text/javascript">
    $('.validar-atm').blur(function() {
        $('#di_niv').prop('', true);
        $('#di_niv').val('Aguarde um momento');
        var elemento = $(this);
        $.ajax({
            url: '<?php echo base_url('index.php/distribuidor/validar_conta_empresa'); ?>',
            type: 'post',
            data: {'di_email_atm': $(this).val()},
            dataType: 'json',
            success: function(data) {
                $('.status').hide();
                //Usuario não cadastrado.   
                if (data.status == "1") {
                    //Cadastro não exite na ATM
                    $('.semcadastro').addClass('in').removeClass('hide');
                }

                //Usuário cadastrado. 
                if (data.status == "2") {
                    $('#di_niv').val(data.niv);
                    $('#di_niv').removeAttr('');
                    //Busca informações na plataform pay
                    get_dados_usuario(elemento.val());
                } else {
                    $('#di_niv').removeAttr('');
                }

            }
        });

    });

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
            elementoD = "." + divD;
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

    function atualizar_pais(nome_pais, es_nome) {
        $("#di_pais").prop('', true);
        $.ajax({
            url: '<?php echo base_url('index.php/distribuidor/get_pais_ajax'); ?>',
            type: 'POST',
            data: {ps_nome: nome_pais},
            dataType: 'json',
            success: function(cidadesJson) {
                if (cidadesJson.status == 1) {

                    $('#di_pais option')
                            .removeAttr('selected')
                            .filter('[value=' + cidadesJson.data + ']')
                            .attr('selected', true);

                    atualizar_uf('pais1', 'di_uf', '<?php echo base_url('index.php/distribuidor/estados') ?>', es_nome);

                }

                $("#di_pais").removeAttr("");
            }
        });
    }
    
    function atualizar_estados_entrega() {
        $(".recebe-estado2").html("<option value=''>Aguarde...</option>");
        $.ajax({
            url: '<?php echo base_url() ?>index.php/distribuidor/estados',
            type: 'POST',
            data: {es_pais: $('#pais2 :selected').val()},
            dataType: 'json',
            success: function(cidadesJson) {
                //Mostar os cartões.
                $('#esconder_cartoes').show();
                var txt_cidades = "<option value=''>-- <?php echo $this->lang->line('label_selecione'); ?> --</option>";
                $.each(cidadesJson, function(index, cidade) {
                    txt_cidades += "<option value='" + cidade.es_id + "'>" + cidade.es_nome + "</option>";
                });

                $(".recebe-estado2").html(txt_cidades);
                $(".recebe-estado2").removeAttr("disabled");
                $("#di_numero").focus();


                //Recarregando o plano de acordo como pais para colocar o novo plano.
                if ($("#codigo_promocional").val() == "") {
                    $('#suck_planos').load('<?php echo base_url('index.php/distribuidor/get_plano_cambio_ajax?id_pais='); ?>' + $('#pais1 :selected').val());
                }

                if (es_nome != "undefined") {
                    //Selecionando o select pelo texto.
                    /**
                     * Porque selecionar pelo texto? isto corre pelo seguinte 
                     * fato, sendo ele que na Plataforma de Pagamento não retorna o id e sim 
                     * nome do pais, selecionando pelo texto evita mais uma consulta 
                     * via ajax. 
                     */

                    $(".di_uf option").each(function() {
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

    //Pegando dados da plataform pay.
    function get_dados_usuario(email) {

        if (email != "") {
            var texto_notificacao = 'Aguarde um momento...';
            $('input[name=di_nome]').prop('', true).val(texto_notificacao);
            $('input[name=di_ultimo_nome]').prop('', true).val(texto_notificacao);
            $('input[name=di_fone1]').prop('', true).val(texto_notificacao);
            $('input[name=di_fone2]').prop('', true).val(texto_notificacao);
            $('input[name=di_cidade_nascimento]').prop('', true).val(texto_notificacao);
            $('input[name=di_complemento]').prop('', true).val(texto_notificacao);
            $('input[name=di_rg]').prop('', true).val(texto_notificacao);
            $('input[name=confirm_email]').prop('', true).val(texto_notificacao);
            $('input[name=di_cep]').prop('', true).val(texto_notificacao);
            $('input[name=cidade1]').prop('', true).val(texto_notificacao);
            $('input[name=di_numero]').prop('', true).val(texto_notificacao);
            $('input[name=di_endereco]').prop('', true).val(texto_notificacao);
            $('input[name=di_data_nascimento]').prop('', true).val(texto_notificacao);
            $('input[name=di_cidade]').prop('', true).val(texto_notificacao);
            $('input[name=di_bairro]').prop('', true).val(texto_notificacao);

            $.ajax({
                url: '<?php echo base_url('/index.php/distribuidor/get_dados_plataform'); ?>',
                data: {'email': email},
                dataType: 'json',
                success: function(data) {

                    //So altera se tiver mesmo voltar o cadastro.
                    if (data.status == 2) {

                        $('input[name=di_nome]').val(data.name).removeAttr("");
                        $('input[name=di_ultimo_nome]').val(data.surname).removeAttr("");
                        $('input[name=di_fone1]').val(data.phone).removeAttr("");
                        $('input[name=di_fone2]').val(data.cellPhone).removeAttr("");
                        $('input[name=di_cidade_nascimento]').val(data.cityOfBirth).removeAttr("");
                        $('input[name=di_complemento]').val(data.completion).removeAttr("");
                        $('input[name=di_rg]').val(data.taxIdNumber).removeAttr("");
                        $('input[name=confirm_email]').val(data.email).removeAttr("");
                        $('input[name=di_cep]').val(data.zipCode).removeAttr("");
                        $('input[name=cidade1]').val(data.city).removeAttr("");
                        $('input[name=di_numero]').val(data.number).removeAttr("");
                        $('input[name=di_endereco]').val(data.street).removeAttr("");
                        $('input[name=di_data_nascimento]').val(data.birthday).removeAttr("");
                        $('input[name=di_cidade]').val(data.city).removeAttr("");
                        $('input[name=di_bairro]').val(data.district).removeAttr("");
                        //Atualizando o pais
                        atualizar_pais(data.country, data.state);

                    } else {
                        $('input[name=di_nome]').removeAttr("");
                        $('input[name=di_ultimo_nome]').removeAttr("");
                        $('input[name=di_fone1]').removeAttr("");
                        $('input[name=di_fone2]').removeAttr("");
                        $('input[name=di_cidade_nascimento]').removeAttr("");
                        $('input[name=di_complemento]').removeAttr("");
                        $('input[name=di_rg]').removeAttr("");
                        $('input[name=confirm_email]').removeAttr("");
                        $('input[name=di_cep]').removeAttr("");
                        $('input[name=cidade1]').removeAttr("");
                        $('input[name=di_numero]').removeAttr("");
                        $('input[name=di_endereco]').removeAttr("");
                        $('input[name=di_data_nascimento]').removeAttr("");
                        $('input[name=di_cidade]').removeAttr("");
                        $('input[name=di_bairro]').removeAttr("");
                    }

                }
            });
        }

    }

    function ativar_pessoa_juridica(tipo) {
        if (tipo == 1) {
            $('.juridico').css('display', 'none');
        }
        if (tipo == 2) {
            $('.juridico').css('display', 'block');
        }


    }

    function num(dom) {
        dom.value = dom.value.replace(/\D/g, '');
    }
</script>
<?php
//descarregando a sessão 
$_SESSION['form_cad_error'] = '';
?>
