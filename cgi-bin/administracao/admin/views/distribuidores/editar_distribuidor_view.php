<?php
$distribuidor = $this->db
                ->join('cidades', 'ci_id=di_cidade', 'left')
                ->join('distribuidor_pessoa_juridica', 'di_id = dpj_id_distribuidor', 'left')
                ->where('di_id', $this->uri->segment(3))->get('distribuidores')->row();

$data = explode('-', $distribuidor->di_data_nascimento);
$dataNascimento = $data[2] . "/" . $data[1] . "/" . $data[0];

if (count($distribuidor) == 0) {
    ?> 
    <p>Nenhum usuário encontrado.</p>
<?php } else { ?>

    <div class="box-content min-height">
        <div class="box-content-header">
            <a href="<?php echo base_url('index.php/distribuidores/') ?>">Distribuidores</a> &raquo; Editar distribuidor
        </div>
        <div class="box-content-body">
            <form id="form" name="form" method="post" action="<?php echo base_url('index.php/distribuidores/editar_distribuidor/' . $this->uri->segment(3)); ?>">

                <table width="100%" border="0">
                    <!-- <tr>
                        <td>
                            <label for="sistema"><strong>Selecione o sistema</strong></label>
                                <select id="sistema" name="di_sistema">
                                    <?php 
                                    $empresas = $this->db->get('empresas')->result();
                                    foreach ($empresas as  $key => $empresas_Value) { ?>
                                        <option  value="<?php echo $empresas_Value->ep_id; ?>" <?php echo $distribuidor->di_empresa ==$empresas_Value->ep_id?'selected':''?>><?php echo $empresas_Value->ep_nome; ?></option>
                                    <?php } ?>
                                </select>
                            </label>
                        </td>
                    </tr> -->
                    <tr>
                        <td>
                            <label>
                                <?php echo $this->lang->line('label_tipo_pessoa_cadastro'); ?>  
                            </label>
                            <label class="radio" style="cursor: pointer;">
                                <input type="radio" name="tipopessoa" id="tipopessoa" value="0" onclick="ativar_pessoa_juridica(1);" checked="">
                                Individuos 
                            </label>

                            <label class="radio" style="cursor: pointer;">
                                <input type="radio" name="tipopessoa" <?php echo e_pessoa_juridica($distribuidor->di_id) ? 'checked' : ''; ?> id="tipopessoa" onclick="ativar_pessoa_juridica(2)" value="1">
                                Corporativo 
                            </label>
                        </td>
                    </tr>
                </table>


                <!--dados empresariais-->
                <div class="juridico" style="display: <?php echo e_pessoa_juridica($distribuidor->di_id) ? 'block' : 'none'; ?>">
                    <table borde="0"  width="100%" >
                        <tr>
                            <td>
                                <h3>Dados Jurídicos</h3>
                                <hr/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label for="dpj_nome_empresa">Nome da Empresa:</label>
                                <input style="width:95%;" type="text" name="dpj_nome_empresa" id="dpj_nome_empresa" placeholder="" class="validate[required]" value="<?php echo $distribuidor->dpj_nome_empresa; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td >
                                <label for="dpj_nome_empresa">Tx Identification Number:</label>
                                <input style="width:90%;" type="text" name="dpj_tx_identificacao" id="dpj_tx_identificacao" placeholder="" class="validate[required]" onkeyup="num(this);" value="<?php echo $distribuidor->dpj_tx_identificacao; ?>">
                            </td>
                            <td>
                                <label for="dpj_nome_empresa">Diregente Responsável:</label>
                                <input style="width:90%;" type="text" name="dpj_diregente_responsavel" id="dpj_diregente_responsavel" placeholder="" class="validate[required]" value="<?php echo $distribuidor->dpj_diregente_responsavel; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="dpj_nome_empresa">Diretor:</label>
                                <input style="width:90%;" type="text" name="dpj_diretor" id="dpj_diretor" placeholder="" class="validate[required]" value="<?php echo $distribuidor->dpj_diretor; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label for="dpj_nome_empresa">Endereço:</label>
                                <input style="width:95%;" type="text" name="dpj_endereco" id="dpj_endereco" placeholder="" class="validate[required]"  value="<?php echo $distribuidor->dpj_endereco; ?>" />
                            </td>
                        </tr>
                    </table>  

                    <!--fim dos dados empresariais-->
                </div>

                <!--inicio do formulário--> 
                <table width="100%"  border="0">
                    <?php if (in_array(get_user()->rf_id, array(5000, 5018))) { ?>
                        <tr>
                            <td colspan="2">
                                <a class="btn btn-danger"  style="float:right; font-size:18px;"
                                   onclick="return confirm('Deseja realmente excluir <?php echo $distribuidor->di_nome ?>?')"
                                   href="<?php echo base_url('index.php/excluir_distribuidor/excluir_ni/' . $this->uri->segment(3)) ?>">
                                    Excluir: <?php echo $distribuidor->di_nome ?> / <?php echo $distribuidor->di_usuario ?>
                                </a>

                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <div class="alert" >        
                                    <h4 >Alterar Login</h4>
        <?php if (in_array(get_user()->rf_id, array(5000, 5018))) { ?>
                                        <form action="<?php echo base_url('index.php/distribuidores/alterar_usuario') ?>" method="post">
                                            <label for="di_usuario"> Usuário: </label>
                                            <input type="hidden" name="di_id" value="<?php echo $distribuidor->di_id ?>"/>
                                            <input type="hidden" name="di_usuario_ant" value="<?php echo $distribuidor->di_usuario ?>"/>
                                            <input type="text" id="di_usuario" name="di_usuario" value="<?php echo $distribuidor->di_usuario ?>"/>
                                            <input type="submit" class="btn btn-danger" value="Salvar">
                                        </form>            
        <?php } ?>
                                </div>
                            </td>
                        </tr>
    <?php } ?>

                    <tr>
                        <td colspan="2">
                            <h3>Dados do usuário</h3>
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td width="304px">
                            <label for="di_email" >E-mail:</label>
                            <input name="di_email"  id="di_email" type="text" size="50" class="validar-atm" value="<?php echo $distribuidor->di_email ?>" />
                            <span class="status" style="display: none">Aquarde um  momento...</span>
                        </td>
                        <td>
                            <label for="di_niv" >Niv do Usuário:</label>
                            <input name="di_niv" id="di_niv" readonly type="text" size="50" value="<?php echo $distribuidor->di_niv ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_nome" >Nome:</label>
                            <input name="di_nome" id="di_nome" type="text" class="validate[required]" size="50" value="<?php echo $distribuidor->di_nome ?>" />
                        </td>
                        <td>
                            <label for="di_ultimo_nome" >último nome:</label>
                            <input name="di_ultimo_nome" id="di_ultimo_nome" type="text" class="validate[required]" size="50" value="<?php echo $distribuidor->di_ultimo_nome; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_tipo_documento" >Tipo documento:</label>
                            <input name="di_tipo_documento" id="di_tipo_documento" type="text" size="50" value="<?php echo $distribuidor->di_tipo_documento; ?>" class="validate[required]" />
                        </td>
                        <td>
                            <label for="di_rg" >Número do documento:</label>
                            <input name="di_rg" id="di_rg" type="text" size="50" value="<?php echo $distribuidor->di_rg; ?>" class="validate[required]" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_fone1" >Telefone:</label>
                            <input type="text" value="<?php echo $distribuidor->di_fone1; ?>" name="di_fone1" id="di_fone1" class="validate[required] fone">
                        </td>
                        <td>
                            <label for="di_fone2" >Celular:</label>
                            <input type="text" value="<?php echo $distribuidor->di_fone2; ?>" name="di_fone2" id="di_fone2" class="validate[required] fone">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_data_nascimento" >Data de Nascimento:</label>
                            <input type="text" name="di_data_nascimento" id="di_data_nascimento" value="<?php echo date('d/m/Y', strtotime($distribuidor->di_data_nascimento)); ?>" placeholder="dd/mm/aaaa" class="validate[required] mdata" size="20" >
                        </td>
                        <td>
                            <label for="di_cidade_nascimento" >Lugar do nascimento:</label>
                            <input type="text" name="di_cidade_nascimento" id="di_cidade_nascimento" value="<?php echo $distribuidor->di_cidade_nascimento; ?>" class="validate[required]">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_pais_nascimento" >País do nascimento:</label>

                            <select name="di_pais_nascimento" id="di_pais_nascimento"  class="verificar_dastrado_por_pais estado1 validate[required]"  >
                                <option value="0">--Selecione--</option>
    <?php
    $pais_nascimento = $this->db
            ->where_not_in('ps_id', array('173', '224', '232'))
            ->where('ps_id !=2')
            ->order_by('ps_nome', 'asc')
            ->get('pais')
            ->result();

    foreach ($pais_nascimento as $p) {
        ?>
                                    <option value="<?php echo $p->ps_id ?>" <?php echo $distribuidor->di_pais_nascimento == $p->ps_id ? 'selected' : ''; ?> ><?php echo $p->ps_nome ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <label for="di_sexo">Sexo:</label>
                            <select name="di_sexo" id="di_sexo">
                                <option value="">--Selecione--</option>
                                <option <?php echo $distribuidor->di_sexo == 'M' ? 'selected' : '' ?> value="M">Masculino</option>
                                <option <?php echo $distribuidor->di_sexo == 'F' ? 'selected' : '' ?> value="F">Feminino</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></hr></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h3>Local de Entrega</h3>
                            <hr>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <label for="di_pais">País:</label>
    <?php
    $pais_cadastro = $this->db
                    ->where_not_in('ps_id', array('173', '224', '232'))
                    ->where('ps_id !=2')
                    ->order_by('ps_nome', 'asc')
                    ->get('pais')->result();
    ?>

                            <select name="di_pais" id="di_pais" class="validate[required] pais1" onchange="atualizar_uf('pais1', 'di_uf', '<?php echo base_url('index.php/distribuidores/estados') ?>');">

                                <option value="0">--Selecione--</option>
    <?php foreach ($pais_cadastro as $p) { ?>
                                    <option value="<?php echo $p->ps_id ?>" 
                                    <?php
                                    if (count(DistribuidorDAO::getPais($distribuidor->di_cidade)) > 0) {
                                        echo DistribuidorDAO::getPais($distribuidor->di_cidade)->ps_id == $p->ps_id ? 'selected' : '';
                                    }
                                    ?>>
                                                <?php echo $p->ps_nome ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <label for="di_uf">Estado (UF):</label>
                            <?php
                            $estado = $this->db->get('estados')->result();
                            echo CHtml::dropdow('di_uf', CHtml::arrayDataOption($estado, 'es_id', 'es_nome'), array(
                                'empty' => $this->lang->line('label_estado'),
                                'class' => "estado1 di_uf recebe-estado validate[required]",
                                'selected' => $distribuidor->di_uf,
                                'id' => 'di_uf'));
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_cidade">Cidade:</label>
                            <input type="text" name="di_cidade" id="di_cidade"  autocomplete="off"  class=" validate[required]" value="<?php
                            if (count(DistribuidorDAO::getCidade($distribuidor->di_cidade)) > 0) {
                                echo DistribuidorDAO::getCidade($distribuidor->di_cidade)->ci_nome;
                            }
                            ?>" /></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label for="di_endereco">Rua:</label>
                            <input type="text"  name="di_endereco" id="di_endereco" value="<?php echo $distribuidor->di_endereco; ?>" size="50" style="width: 517px;" class="validate[required]" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_numero">Número:</label>
                            <input type="text" class="validate[required]" id="di_numero" value="<?php echo $distribuidor->di_numero; ?>"  name="di_numero" size="30" onkeyup="num(this);"  >
                        </td>
                        <td>
                            <label for="di_complemento">Complemento:</label>
                            <input type="text" id="di_complemento" name="di_complemento"  value="<?php echo $distribuidor->di_complemento; ?>" size="14">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_cep">CEP:</label>
                            <input type="text" name="di_cep" id="di_cep" value="<?php echo $distribuidor->di_cep; ?>" class="validate[required]" size="14">
                        </td>
                        <td>
                            <label for="di_bairro">Bairro:</label>
                            <input type="text" name="di_bairro" id="di_bairro" size="30" value="<?php echo $distribuidor->di_bairro; ?>" class="validate[required]" />
                        </td>
                    </tr>

    <!--                <tr>
                        <td colspan="2">
                            <h3>Dados Bancários</h3>
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label for="di_conta_banco">Banco:</label>
                            <input name="di_conta_banco" id="di_conta_banco" type="text" size="50" value="<?php echo $distribuidor->di_conta_banco ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="di_conta_agencia">Agência:</label>
                            <input name="di_conta_agencia" id="di_conta_agencia" type="text" size="50" value="<?php echo $distribuidor->di_conta_agencia ?>" />
                        </td>
                        <td>
                            <label for="di_conta_numero">Nº da Conta:</label>
                            <input name="di_conta_numero" id="di_conta_numero" type="text" size="50" value="<?php echo $distribuidor->di_conta_numero ?>" />
                        </td>
                    </tr>


                    <tr>
                        <td >
                            <label for="di_conta_nome">Nome do titular conta:</label>
                            <input name="di_conta_nome" id="di_conta_nome" type="text" size="50" value="<?php echo $distribuidor->di_conta_nome ?>" />
                        </td>

                        <td >
                            <label for="di_conta_cpf">CPF do titular Conta:</label>
                            <input name="di_conta_cpf" id="di_conta_cpf" type="text" size="20" value="<?php echo $distribuidor->di_conta_cpf ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <label>Tipo de conta:</label>
                            <input name="di_conta_tipo" <?php echo $distribuidor->di_conta_tipo == 1 ? "checked" : "" ?> type="radio" size="50" value="1" /> corrente
                            <input name="di_conta_tipo" <?php echo $distribuidor->di_conta_tipo == 0 ? "checked" : "" ?> type="radio" size="50" value="0" /> Poupança
                        </td>

                    </tr>-->

                    <tr>
                        <td colspan="2">
                            <h3>Altera senha</h3>
                            <i>Obs.: Só informe uma senha caso queira alterá-la.</i>
                            <hr>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <label for="di_senha">Senha de login:</label>
                            <?php echo $distribuidor->di_senha;?>
                            <br/>
                            <input id="di_senha" name="di_senha" type="password"  placeholder="***************" size="20" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"> 
                            <label for="di_pw">Nova senha financeiro:</label>
                            <?php echo $distribuidor->di_pw;?><br/>
                            <input id="di_pw" name="di_pw" type="password" size="20" placeholder="***************" />
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="alert">IP do Cadastro: <?php echo $distribuidor->di_ip_cadastro ?></div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <p><input type="submit" class="btn btn-success" value="Salvar Dados do Distribuidor"></p>
                        </td>
                    </tr>
                </table>
            </form>
            <!--fim do formulário--> 
        </div>
    </div>
<?php }
?>
<script type="text/javascript">
    $('.validar-atm').blur(function() {
        $('#di_niv').val('Aguarde um momento');
        var elemento = $(this);
        $.ajax({
            url: '<?php echo base_url('index.php/distribuidores/validar_conta_empresa'); ?>',
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
                    //Busca informações na plataform pay
                    get_dados_usuario(elemento.val());
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
        $(elementoD).attr("disabled");

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
                $(elementoD).removeAttr("disabled");

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
        $("#di_pais").prop('disabled', true);
        $.ajax({
            url: '<?php echo base_url('index.php/distribuidores/get_pais_ajax'); ?>',
            type: 'POST',
            data: {ps_nome: nome_pais},
            dataType: 'json',
            success: function(cidadesJson) {
                $("#di_pais").removeAttr("disabled");
                if (cidadesJson.status == 1) {

                    $('#di_pais option')
                            .removeAttr('selected')
                            .filter('[value=' + cidadesJson.data + ']')
                            .attr('selected', true);

                    atualizar_uf('pais1', 'di_uf', '<?php echo base_url('index.php/distribuidores/estados') ?>', es_nome);

                }

            }
        });
    }

    function delete_loading() {
        $(".loading-ajax").remove();
    }

    //Pegando dados da plataform pay.
    function get_dados_usuario(email) {

        if (email != "") {
            var texto_notificacao = 'Aguarde um momento...';
            $('input[name=di_nome]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_ultimo_nome]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_fone1]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_fone2]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_cidade_nascimento]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_complemento]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_rg]').prop('disabled', true).val(texto_notificacao);
            $('input[name=confirm_email]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_cep]').prop('disabled', true).val(texto_notificacao);
            $('input[name=cidade1]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_numero]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_endereco]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_data_nascimento]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_cidade]').prop('disabled', true).val(texto_notificacao);
            $('input[name=di_bairro]').prop('disabled', true).val(texto_notificacao);

            $.ajax({
                url: '<?php echo base_url('/index.php/distribuidores/get_dados_plataform'); ?>',
                data: {'email': email},
                dataType: 'json',
                success: function(data) {

                    //So altera se tiver mesmo voltar o cadastro.
                    if (data.status == 2) {

                        $('input[name=di_nome]').val(data.name).removeAttr("disabled");
                        $('input[name=di_ultimo_nome]').val(data.surname).removeAttr("disabled");
                        $('input[name=di_fone1]').val(data.phone).removeAttr("disabled");
                        $('input[name=di_fone2]').val(data.cellPhone).removeAttr("disabled");
                        $('input[name=di_cidade_nascimento]').val(data.cityOfBirth).removeAttr("disabled");
                        $('input[name=di_complemento]').val(data.completion).removeAttr("disabled");
                        $('input[name=di_rg]').val(data.taxIdNumber).removeAttr("disabled");
                        $('input[name=confirm_email]').val(data.email).removeAttr("disabled");
                        $('input[name=di_cep]').val(data.zipCode).removeAttr("disabled");
                        $('input[name=cidade1]').val(data.city).removeAttr("disabled");
                        $('input[name=di_numero]').val(data.number).removeAttr("disabled");
                        $('input[name=di_endereco]').val(data.street).removeAttr("disabled");
                        $('input[name=di_data_nascimento]').val(data.birthday).removeAttr("disabled");
                        $('input[name=di_cidade]').val(data.city).removeAttr("disabled");
                        $('input[name=di_bairro]').val(data.district).removeAttr("disabled");
                        //Atualizando o pais
                        atualizar_pais(data.country, data.state);

                    } else {
                        $('input[name=di_nome]').removeAttr("disabled");
                        $('input[name=di_ultimo_nome]').removeAttr("disabled");
                        $('input[name=di_fone1]').removeAttr("disabled");
                        $('input[name=di_fone2]').removeAttr("disabled");
                        $('input[name=di_cidade_nascimento]').removeAttr("disabled");
                        $('input[name=di_complemento]').removeAttr("disabled");
                        $('input[name=di_rg]').removeAttr("disabled");
                        $('input[name=confirm_email]').removeAttr("disabled");
                        $('input[name=di_cep]').removeAttr("disabled");
                        $('input[name=cidade1]').removeAttr("disabled");
                        $('input[name=di_numero]').removeAttr("disabled");
                        $('input[name=di_endereco]').removeAttr("disabled");
                        $('input[name=di_data_nascimento]').removeAttr("disabled");
                        $('input[name=di_cidade]').removeAttr("disabled");
                        $('input[name=di_bairro]').removeAttr("disabled");
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