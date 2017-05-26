<?php
$this->lang->load('publico/distribuidor/cadastro_view');
$erros = isset($_SESSION['form_cad_error']) ? $_SESSION['form_cad_error'] : false;

function g_dados($key) {
    return isset($_SESSION['form_cad'][$key]) ? $_SESSION['form_cad'][$key] : '';
}
?>
<style type="text/css">

    .disponivel{
        color:green;
    }

    .notification{
        position: absolute;
        display: none;
        width: 417px;
        z-index: 9999;
        margin-top: -164PX;
    }
    .label_usuario_cadastrado{
        display: none;
        line-height:140%; 
        color:#333; 
        font-size:100%; 
        padding:13px;
        margin-top: 10px;

    }
    .verde{
        background:#def3c9;
    }
    .vermelho{
        color: #FFF;
        background:red;
    }
</style>
<script>
   window.history.forward(1);
</script>
<div style="padding: 20px 0 0 0; text-align:center;">
    <h2 mar class="align_center"><strong style="margin-bottom: 0 !important;"><?php echo $this->lang->line('label_formulario_cadastro'); ?></strong></h2>
</div>

<div>
    <div class="box-content" style="margin:0px auto; width:940px; padding:0;">
        <div id="pais" style=" display:none;"> Idioma<br>
            <select name="idioma" class="validate[required]" onchange="carregar_pagina_pais(this)" value="">
                <option value="0"><?php echo $this->lang->line('select_titulo_padrao'); ?></option>
                <option selected="selected" value="pt">Português</option>
                <option value="en">Inglês</option>
            </select>
        </div>

        <?php
        if ($erros) {
            ?>
            <div style="margin:1px;width: 600px;color: red;" class="alert alert-danger">
                <?php
                foreach ($erros as $e) {
                    ?>
                    <div>- <?php echo $e ?></div>
                <?php } ?>
            </div>
        <?php } ?>

        <form method="post" id="form-distribuidor"
              onsubmit="desativaBtn()"
              action="<?php echo base_url('index.php/distribuidor/salvar_distribuidor') ?>">

            <div class="box-content-body border-radios">  
                <?php if (ConfigSingleton::getValue('ativar_ou_destivar_codigo_promocional') == 0) { ?>
                    <!--patrocinador-->
                    <div class="control-group error">
                        <strong>
                            <label class="control-label" for="inputError">
                                <?php echo $this->lang->line('label_codigo_promocional'); ?>
                            </label>
                        </strong><br>
                        <div class="controls">
                            <input style="width:50%;" type="text" value="<?php echo g_dados('codigo_promocional'); ?>" name="codigo_promocional" id="codigo_promocional" size="20">
                            <span id="resposta-token" class="help-inline"></span>
                        </div>
                    </div>
                <?php } ?>

                <fieldset>

                    <strong><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_patrocinador'); ?></strong><br>
                    <input style="width:50%;" type="text" value="<?php echo get_user_current_url() && g_dados('di_usuario_patrocinador') == false ? get_user_current_url()->di_usuario : g_dados('di_usuario_patrocinador') ?>" class="di_ni_patrocinador validate[required]" name="di_usuario_patrocinador" id="di_usuario_patrocinador" onblur="verificar_patrocinador(this.value)" size="20">
                    <?php if (get_user_current_url() && g_dados('di_usuario_patrocinador') == false) { ?>
                        <script>
                            $(function() {
                                verificar_patrocinador('<?php echo get_user_current_url()->di_usuario ?>');
                            });</script>
                    <?php } ?>
                </fieldset>

                <span class="row-separator"></span>
                <h3><?php echo $this->lang->line('label_novo_registro'); ?></h3>
                <!--fim di patrocinador--> 
                <!--inicio do dados do login-->
                <fieldset>
                    <table width="70%" border="0" cellspacing="0" cellpadding="0">
                        <tbody><tr>
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_login'); ?>:<br>
                                    <input placeholder="" type="text" style="width:90%" name="di_usuario" id="usuario_new" value="<?php echo g_dados('di_usuario') ?>" onblur="usuario_disponivel(this.value)" class="di_usuario validate[required,minSize[4]]"></td>
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_repetir_login'); ?>:<br>
                                    <input placeholder="" type="text" style="width:90%" name="" value="<?php echo g_dados('di_usuario') ?>" class="validate[required,minSize[4],equals[usuario_new]]"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table width="70%" border="0" cellspacing="0" cellpadding="2">
                        <tbody><tr>
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_senha'); ?>:<br>
                                    <input placeholder="" type="password" value="<?php echo g_dados('senha') ?>" name="senha" id="senha" class="width1 validate[required,minSize[8]]"></td>
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_confirm_senha'); ?>:<br>
                                    <input placeholder=" " type="password" value="<?php echo g_dados('senha') ?>" class="width1 validate[required,equals[senha],minSize[8]]"></td>
                            </tr>
                            <tr>
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_senha_secundaria'); ?>:<br>
                                    <input placeholder=" " type="password" value="<?php echo g_dados('senha_finaceira') ?>" name="senha_finaceira" id="senha_finaceira" class="width1 validate[required,minSize[8]]"></td>
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_confirme_senha_secundaria'); ?>:<br>
                                    <input placeholder="" type="password" value="<?php echo g_dados('senha_finaceira') ?>" class="width1 validate[required,equals[senha_finaceira],minSize[8]]"></td>
                            </tr>
                        </tbody>
                    </table>
                    <span style="color:#F00; padding-bottom:12px; padding-top:7px;"> <?php echo $this->lang->line('inf_nao_pode_mudar_usuario_pos_cadasto'); ?></span>
                </fieldset>
                <!--fim do dados do login--> 
                <span class="row-separator"></span>

                <!--iníco do dados pessoais-->
                <fieldset>

                    <table width="100%">
                        <tbody>
                            <!--Início da escolha do tipo de cadastro no formulário--> 
                            <tr>
                                <td>
                                    <div class="row" style="margin-top: 22px;margin-bottom: 22px; ">
                                        <div class="span">
                                            <label class="radio" style="cursor: pointer;">
                                                <input type="radio" name="tipopessoa" id="tipopessoa" value="0" onclick="ativar_pessoa_juridica(1);" checked>
                                                <?php echo $this->lang->line('label_pessoa_fisica'); ?>
                                            </label>
                                            <label class="radio" style="cursor: pointer;">
                                                <input type="radio" name="tipopessoa" id="tipopessoa" onclick="ativar_pessoa_juridica(2)" value="1">
                                                <?php echo $this->lang->line('label_pessoa_juridica'); ?>
                                            </label>

                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!--Fim da escolha do tipo de cadastro no formulário-->
                    </table>
                    <div class="juridico" style="display: none;">
                        <h3 ><?php echo $this->lang->line('label_dados_empresarial'); ?></h3>
                        <table width="100%"  >
                            <!--Início do cadastro pessoa jurídica-->

                            <tr>
                                <td colspan="2">  <?php echo $this->lang->line('label_nome_empresa'); ?>:<br>
                                    <input style="width:95%;" type="text" name="dpj_nome_empresa" id="dpj_nome_empresa" placeholder="" class="validate[required]" onblur="get_dados_usuario(this)" value="<?php echo g_dados('dpj_nome_empresa') ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>  <?php echo $this->lang->line('label_tx_id_empresa'); ?>:<br>
                                    <input style="width:90%;" type="text" name="dpj_tx_identificacao" id="dpj_tx_identificacao" placeholder="" class="validate[required]" onblur="get_dados_usuario(this)" value="<?php echo g_dados('dpj_tx_identificacao') ?>">
                                </td>
                                <td>  <?php echo $this->lang->line('label_diregente_responsavel'); ?>:<br>
                                    <input style="width:90%;" type="text" name="dpj_diregente_responsavel" id="dpj_diregente_responsavel" placeholder="" class="validate[required]" onblur="get_dados_usuario(this)" value="<?php echo g_dados('dpj_diregente_responsavel') ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>  <?php echo $this->lang->line('label_diretor'); ?>:<br>
                                    <input style="width:90%;" type="text" name="dpj_diretor" id="dpj_diretor" placeholder="" class="validate[required]" onblur="get_dados_usuario(this)" value="<?php echo g_dados('dpj_tx_identificacao') ?>">
                                </td>                      
                            </tr>
                            <tr >
                                <td  colspan="2">  <?php echo $this->lang->line('label_endereco'); ?>:<br>
                                    <input style="width:95%;" type="text" name="dpj_endereco" id="dpj_endereco" placeholder="" class="validate[required]" onblur="get_dados_usuario(this)" value="<?php echo g_dados('dpj_tx_identificacao') ?>">
                                </td>                      
                            </tr>
                        </table>
                    </div>

                    <h3><?php echo $this->lang->line('label_dados_pessoais'); ?></h3>
                    <table width="100%">
                        <!--Fim inicio do cadastro pessoa jurídica-->
                        <!--<tr>
                            <td colspan="2">
                                <div style="line-height:140%; color:#333; font-size:100%; padding:13px; background:#def3c9;">
                                    <?php echo $this->lang->line('label_aviso_email'); ?>
                                </div>
                            </td>
                        </tr>-->

                        <tr>
                            <td colspan="10" height="10">
                                <div class="label_usuario_cadastrado">

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_email'); ?>:<br>
                                <input style="width:90%;" type="text" name="di_email" id="di_email" placeholder="" class="validate[required,custom[email]]" onblur="get_dados_usuario(this)" value="<?php echo g_dados('di_email') ?>"></td>
                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_repetir_email'); ?>:<br>
                                <input id="confirm_email" style="width:90%;" type="text" placeholder="" value="<?php echo g_dados('di_email') ?>" class="validate[required,custom[email],equals[di_email]]" size="50"></td>
                        </tr>

                        <tr>
                            <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_nome'); ?>:<br>
                                <input type="text" name="di_nome" id="di_nome" value="<?php echo g_dados('di_nome'); ?>" 
                                       class="verificar_dastrado_por_pais so_letra_ecaractere_espercial" placeholder=" " style="width:90%;"></td>
                            <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_ultimo_nome'); ?>:<br>
                                <input type="text" name="di_ultimo_nome" value="<?php echo g_dados('di_ultimo_nome'); ?>" class="verificar_dastrado_por_pais so_letra_ecaractere_espercial" id="di_ultimo_nome" placeholder="" style="width:90%;"></td>
                        </tr>
                        <tr>
                            <td height="5px"></td>
                        </tr>

                        <tr>
                            <td height="10px"></td>
                        </tr>

                        <tr>
                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_celular'); ?>:<br>
                                <input type="text" value="<?php echo g_dados('di_fone2') ?>" name="di_fone2" id="di_fone2" placeholder=" "></td>
                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_telefone'); ?>:<br>
                                <input type="text" value="<?php echo g_dados('di_fone1') ?>" name="di_fone1" id="di_fone1" placeholder=" "></td>
                        </tr>
                        <td> 
                            <div class="notification" style="position: absolute;">
                                <div  style="line-height:140%; color:#333; font-size:100%; padding:13px; background:#def3c9;">
                                    <?php echo $this->lang->line('notificacao_texto_documento'); ?>
                                </div>
                            </div>
                            <i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_tipo_documento'); ?>

                        </td>
                        <tr>

                            <td>    
                                <input type="text" name="di_tipo_documento" value="<?php echo g_dados('di_tipo_documento') ?>" id="di_tipo_documento" class="so_letras"></td>

                            <td> 
                                <i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_rg'); ?>:<br>
                                <input type="text" style="text-transform:uppercase;"   name="di_rg" value="<?php echo g_dados('di_rg') ?>" id="di_rg" class="verificar_dastrado_por_pais so_letras_numeros">
                            </td>
                        </tr>
                        <tr>
                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_data_nascimento'); ?>:<br>
                                <input type="text" name="di_data_nascimento" value="<?php echo g_dados('di_data_nascimento') ?>" placeholder="<?php echo $this->lang->line('label_nota_input_datanascimento'); ?>" class="validate[required] mdata" size="20" id="form-validation-field-0"></td>
                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_cidade_nascimento'); ?>:<br>
                                <input type="text" name="di_cidade_nascimento" value="<?php echo g_dados('di_cidade_nascimento') ?>"></td>
                        </tr>
                        <tr>

                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_pais_nascimento'); ?>:<br>
                                <select name="di_pais_nascimento" class="verificar_dastrado_por_pais estado1 validate[required]"  id="di_pais_nascimento" >
                                    <option value="0"><?php echo $this->lang->line('label_selecione'); ?></option>
                                    <?php
                                    $pais_nascimento = $this->db
                                            ->where_not_in('ps_id', array('173', '224', '232'))
                                            ->where('ps_id !=2')
                                            ->order_by('ps_nome', 'asc')
                                            ->group_by('ps_sigla')
                                            ->get('pais')
                                            ->result();

                                    foreach ($pais_nascimento as $p) {
                                        ?>
                                        <option <?php echo g_dados('di_pais_nascimento') == $p->ps_id ? 'selected' : ''; ?> value="<?php echo $p->ps_id ?>"><?php echo $p->ps_nome ?></option>
                                    <?php } ?>

                                </select>
                            </td>
                            <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_sexo'); ?>:<br>
                                <select style="width:192px;" class="validate[required]" name="di_sexo" id="form-validation-field-1">
                                    <option value="">--<?php echo $this->lang->line('label_selecione'); ?>--</option>
                                    <option <?php echo g_dados('di_sexo') == 'M' ? 'selected' : '' ?> value="M"><?php echo $this->lang->line('label_masculino'); ?></option>
                                    <option <?php echo g_dados('di_sexo') == 'F' ? 'selected' : '' ?> value="F"><?php echo $this->lang->line('label_feminino'); ?></option>
                                </select>
                            </td>
                        </tr>

                        </tbody></table>
                </fieldset>
                <!--fim do dados pessoais--> 
                <!--incio do dados para entrega-->
                <fieldset>
                    <span class="row-separator"></span>
                    <h3><?php echo $this->lang->line('label_dados_entrega'); ?></h3>
                    <table width="100%">
                        <tbody><tr colspan="2">
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_pais'); ?>:<br>
                                    <select name="di_pais" class="estado1 validate[required]" onchange="atualizar_estados();
                                            get_cartao(this.value);" id="pais1">
                                            <?php
                                            $pais_cadastro = $this->db
                                                            ->where_not_in('ps_id', array('173', '224', '232'))
                                                            ->where('ps_id !=2')
                                                            ->group_by('ps_sigla')
                                                            ->order_by('ps_nome', 'asc')
                                                            ->get('pais')->result();
                                            ?>

                                        <option value="0"><?php echo $this->lang->line('label_selecione'); ?></option>
                                        <?php foreach ($pais_cadastro as $p) { ?>
                                            <option  <?php echo g_dados('di_pais') == $p->ps_id ? 'selected' : ''; ?> value="<?php echo $p->ps_id ?>"><?php echo $p->ps_nome ?></option>
                                        <?php } ?>

                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_estado'); ?>:<br>
                                    <?php
                                    $estado = $this->db->get('estados')->result();
                                    echo CHtml::dropdow('di_uf', CHtml::arrayDataOption($estado, 'es_id', 'es_nome'), array(
                                        'empty' => $this->lang->line('label_estado'),
                                        'class' => "estado1 di_uf recebe-estado validate[required]",
                                        'selected' => g_dados('di_uf'),
                                        'id' => 'form-validation-field-1'));
                                    ?>
                                </td>
                                <td>
                                    <i style='color:#f00;'><strong>*</strong></i> 
                                    <?php echo $this->lang->line('label_cidade'); ?>:<br>
                                    <?php
                                    $cidade_nome = '';

                                    if (g_dados('di_cidade') != "" && is_numeric(g_dados('di_cidade'))) {
                                        $cidade_nome = $this->db->where('ci_id', g_dados('di_cidade'))
                                                        ->get('cidades')->row();

                                        //Pega o valor.
                                        if (count($cidade_nome) > 0) {
                                            $cidade_nome = $cidade_nome->ci_nome;
                                        }
                                    }
                                    ?>
                                    <input type="text" name="di_cidade" id="cidade1"  autocomplete="off"  class=" validate[required]" value="<?php echo $cidade_nome; ?>" />
                            </tr>
                            <tr>
                                <td colspan="2"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_rua'); ?>:<br>
                                    <input placeholder="" type="text" id="di_endereco" name="di_endereco" value="<?php echo g_dados('di_endereco') ?>" size="50" style="width:95%" class="validate[required]" id="form-validation-field-0">
                                </td>
                            </tr>
                            <tr>
                                <td width="50%"><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_numero'); ?>:<br>
                                    <input type="text"  id="di_numero"  placeholder="<?php //echo $this->lang->line('label_numero');                                                           ?>" value="<?php echo g_dados('di_numero') ?>"  name="di_numero" size="30" onkeyup="num(this);"  >
                                </td>
                                <td width="50%"> 
                                    <?php echo $this->lang->line('label_complemento'); ?>:<br>
                                    <input type="text" id="di_complemento" name="di_complemento"  value="<?php echo g_dados('di_complemento') ?>" placeholder="" size="14">
                                </td>
                            </tr>
                            <tr>
                                <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_cep'); ?>:<br>
                                    <input type="text" name="di_cep" id="di_cep" value="<?php echo g_dados('di_cep') ?>" size="14"></td>
                                <td><i style='color:#f00;'><strong>*</strong></i> <?php echo $this->lang->line('label_bairro'); ?>:<br>
                                    <input type="text" name="di_bairro" size="30" value="<?php echo g_dados('di_bairro') ?>" id="form-validation-field-0"></td>
                            </tr>
                        </tbody></table>
                    <!--fim do local de cadastro endereço fim--> 

                    <strong class="row-separator"></strong>
                </fieldset>
                <fieldset>
                    <input type="checkbox" name="usar_cad_in_entr" id="usar_cad_in_entr" onclick="duplicar_endereco();" />
                    <label for="usar_cad_in_entr" >usar o endereço de cadastro para entrega</label>
                </fieldset>
                <fieldset>
                    <h3> <?php echo $this->lang->line('label_endereco_entrega'); ?></h3>
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <label for="end_pais">
                                    <i style='color:#f00;'><strong>*</strong></i>
                                    <?php echo $this->lang->line('label_pais'); ?>:
                                </label>
                                <br>
                                <select name="end_pais" class="validate[required]" onchange="atualizar_estados_entrega();" id="pais2">
                                    <?php
                                    $pais_cadastro = $this->db
//                                                            ->where_not_in('ps_id', array('173', '224', '232'))
//                                                            ->where('ps_id !=2')
                                                    ->group_by('ps_sigla')
                                                    ->order_by('ps_nome', 'asc')
                                                    ->get('pais')->result();
                                    ?>

                                    <option value="0"><?php echo $this->lang->line('label_selecione'); ?></option>
                                    <?php foreach ($pais_cadastro as $p) { ?>
                                        <option  <?php echo g_dados('end_pais') == $p->ps_id ? 'selected' : ''; ?> value="<?php echo $p->ps_id ?>"><?php echo $p->ps_nome ?></option>
                                    <?php } ?>

                                </select>

                            </td>
                        </tr>

                        <tr>
                            <td width="50%">
                                <label for="end_estado">
                                    <i style='color:#f00;'><strong>*</strong></i>
                                    <?php echo $this->lang->line('label_estado'); ?>:
                                </label>
                                <?php
                                $estado = $this->db->get('estados')->result();
                                echo CHtml::dropdow('end_estado', CHtml::arrayDataOption($estado, 'es_id', 'es_nome'), array(
                                    'empty' => $this->lang->line('label_estado'),
                                    'class' => "recebe-estado2 validate[required]",
                                    'selected' => g_dados('end_estado'),
                                    'id' => 'form-validation-field-1'));
                                ?>

                            </td>
                            <td>
                                <i style='color:#f00;'><strong>*</strong></i> 
                                <?php echo $this->lang->line('label_cidade'); ?>:<br>
                                <?php
                                $cidade_nome = '';

                                if (g_dados('end_cidade') != "" && is_numeric(g_dados('end_cidade'))) {
                                    $cidade_nome = $this->db->where('ci_id', g_dados('end_cidade'))
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
                            <td width="100%" colspan="2">
                                <label for="end_endereco">
                                    <i style='color:#f00;'><strong>*</strong></i>
                                    <?php echo $this->lang->line('label_endereco'); ?>:
                                </label><br/>
                                <input type="text" style="width: 95%;" name="end_endereco" id="end_endereco" value="<?php echo g_dados('end_endereco') ?>"/>
                            </td>
                        </tr>

                        <tr>
                            <td width="50%">
                                <label for="end_numero">
                                    <i style='color:#f00;'><strong>*</strong></i>
                                    <?php echo $this->lang->line('label_numero'); ?>:
                                </label>
                                <input type="text" name="end_numero" id="end_numero" onkeyup="num(this);" value="<?php echo g_dados('end_numero') ?>"/>
                            </td>

                            <td width="50%">
                                <label for="end_conplemento">
                                    <i style='color:#f00;'><strong>*</strong></i>
                                    <?php echo $this->lang->line('label_complemento'); ?>:
                                </label>
                                <br>
                                <input type="text" name="end_complemento" id="end_complemento" value="<?php echo g_dados('end_complemento'); ?>"/>  
                            </td>

                        </tr>


                        <tr>
                            <td width="50%">
                                <label for="end_cep">
                                    <i style='color:#f00;'><strong>*</strong></i>
                                    <?php echo $this->lang->line('label_cep'); ?>:
                                </label>
                                <br>
                                <input type="text" name="end_cep" id="end_cep" value="<?php echo g_dados('end_cep'); ?>"/>  
                            </td>
                            <td width="50%">
                                <label for="end_bairro">
                                    <i style='color:#f00;'><strong>*</strong></i>
                                    <?php echo $this->lang->line('label_bairro'); ?>:
                                </label>
                                <input type="text" name="end_bairro" id="end_bairro" value="<?php echo g_dados('end_bairro'); ?>"/>  
                            </td>
                        </tr>
                    </table>
                </fieldset>
                <strong class="row-separator"></strong>
                <!--início do termo de uso-->

                <h3> <?php echo $this->lang->line('label_termo_uso'); ?></h3>
                <table>
                    <tr>
 
                            <input type="checkbox" class="validate[required]" name="li" value="sim" /> 
                            <span style="font-size:14px; line-height:18px; font-weight:bold;"> 
                                <?php echo $this->lang->line('nota_termo_condicao'); ?>
                                <a target="_blank" href="<?php echo base_url('index.php/url/redirecionar/?uri=' . $this->lang->line('link_termo')) ?>"> <?php echo $this->lang->line('label_clique_aqui_para_ler'); ?></a>
                            </span>                     
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div id="esconder_cartoes" style="display: none;">
                                <h5 style="text-decoration: underline;">
                                    <b><?php echo $this->lang->line('label_escolha_cartao') ?></b>
                                </h5>
                                <?php
                                $cartoes = $this->db->where('cm_id !=1')
                                        ->order_by('cm_id', 'desc')
                                        ->get("cartoes_membership")
                                        ->result();

                                foreach ($cartoes as $cartao) {
                                    ?>
                                    <div id="cartao-<?php echo $cartao->cm_id ?>">
                                        <input <?php echo (g_dados('di_cartao_membership') == $cartao->cm_id ? 'checked' : ''); ?> value="<?php echo $cartao->cm_id; ?>" type="radio" name="di_cartao_membership" class="validate[required]">
                                        <span style="font-size: 16px;"><b><?php echo $cartao->cm_nome; ?> </span><i style="color:green">
                                            <?php echo $this->lang->line("label_descricao_cartao_{$cartao->cm_id}") ?>
                                        </i><br/>

                                        <span style="font-size: 12px;margin-left: 18px;">
                                            <?php echo $this->lang->line("label_descricao_cartao{$cartao->cm_id}") ?>
                                        </span>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <strong class="row-separator"></strong>
                <div id="suck_planos">
                    <?php $this->load->view('/distribuidor/distribuidor_cadastro_planos_view'); ?>
                </div>
                <strong class="row-separator"></strong>


                <!--fim do termo de uso--> 

                <input type="hidden" name="di_niv" id="di_niv"/>

                <button type="submit" class="btn g-btn type_green " id="enviar-cadastro"><?php echo $this->lang->line('label_formulario_cadastro'); ?></button>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
            </div>

        </form>
        <script>
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

            //função auto completar
            $(document).ready(function() {

                verificar_patrocinador($(".di_usuario_patrocinador").val());

                //Validando so para aceitar número
                $("#cidade1").autocomplete({
                    source: "http://Nossa Empresa-office.net/publico/index.php/distribuidor/cidade_by_name",
                    minLength: 3, //search after two characters
                    select: function(event, ui) {
                    },
                    _renderItem: function(ul, item) {
                        return $("<li>")
                                .attr("data-value", item.id)
                                .append($("<a>").text(item.name))
                                .appendTo(ul);
                    },
                });
            });

            function verifica_pais(id) {
                if ($(id).val() == 225) {
                    $('#localatendimento').show();
                    preecher_campos();
                }
                if ($(id).val() != 225) {
                    $('#localatendimento').hide();
                    limpar_campos();
                }
            }

            function alerta() {
                alert('Contrato temporariamente indisponivel');
            }

            $(function() {
                $(".formError").live('hover', function() {
                    $(".formError").remove();
                });
            });

            function get_loading() {
                return "<span class='loading-ajax'><img src='http://Nossa Empresa-office.net/publico/public/imagem/loading.gif' /> aguarde...</span>";
            }

            function delete_loading() {
                $(".loading-ajax").remove();
            }

            function set_pessoa($tipo) {
                if ($tipo == 1) {
                    $(".recebe-rg").html("RG:");
                    $(".recebe-cpf").html("CPF:");
                    $(".mcpf_number").mask("99999999999");
                } else {
                    $(".recebe-rg").html("IE:");
                    $(".recebe-cpf").html("CNPJ:");
                    $(".mcpf_number").mask("99.999.999/9999-99");
                }
            }

            //Preencher os campos em caso de uruguai
            function preecher_campos() {
                $('input[name=di_endereco]').val("Rua 25 de Maio");
                $('input[name=di_endereco]').attr('readonly', true);
                $('input[name=di_numero]').val("713");
                $('input[name=di_numero]').attr('readonly', true);
                $('input[name=di_complemento]').val("Edifício Imperium Building, sala 510.");
                $('input[name=di_complemento]').attr('readonly', true);
                $('input[name=di_cep]').val("");
                $('input[name=di_cep]').attr('readonly', true);
                $('input[name=di_cidade]').val("Montevidéu");
                $('input[name=di_cidade]').attr('readonly', true);
                $('input[name=di_bairro]').val('Centro');
                $('input[name=di_bairro]').attr('readonly', true);
            }

            function duplicar_endereco() {

                if ($("#usar_cad_in_entr").is(":checked")) {

                    $("select[name=end_pais]").val($("select[name=di_pais] :selected").val());
                    atualizar_estados_entrega($("select[name=di_uf] :selected").val());

                    $('input[name=end_bairro]').val($('input[name=di_bairro]').val());
                    $('input[name=end_complemento]').val($('input[name=di_complemento]').val());
                    $('input[name=end_estado]').val($('input[name=di_estado]').val());
                    $('input[name=end_cidade]').val($('input[name=di_cidade]').val());
                    $('input[name=end_endereco]').val($('input[name=di_endereco]').val());
                    $('input[name=end_numero]').val($('input[name=di_numero]').val());
                    $('input[name=end_cep]').val($('input[name=di_cep]').val());

                } else {

                    $("select[name=end_pais]").val('');
                    $("select[name=end_estado]").val('');
                    $('input[name=end_bairro]').val('');
                    $('input[name=end_complemento]').val('');
                    $('input[name=end_estado]').val('');
                    $('input[name=end_cidade]').val('');
                    $('input[name=end_endereco]').val('');
                    $('input[name=end_numero]').val('');
                    $('input[name=end_cep]').val('');

                }
            }

            //Limpra campos.
            function limpar_campos() {

                if ($('input[name=di_uf]').attr('readonly') != undefined) {
                    $('input[name=di_uf]').val("");
                    $('input[name=di_uf]').attr('readonly', false)
                }
                if ($('input[name=di_endereco]').attr('readonly') != undefined) {
                    $('input[name=di_endereco]').val("");
                    $('input[name=di_endereco]').attr('readonly', false)
                }
                if ($('input[name=di_numero]').attr('readonly') != undefined) {
                    $('input[name=di_numero]').val("");
                    $('input[name=di_numero]').attr('readonly', false)
                }
                if ($('input[name=di_complemento]').attr('readonly') != undefined) {
                    $('input[name=di_complemento]').val("");
                    $('input[name=di_complemento]').attr('readonly', false)
                }
                if ($('input[name=di_cep]').attr('readonly') != undefined) {
                    $('input[name=di_cep]').val("");
                    $('input[name=di_cep]').attr('readonly', false)
                }
                if ($('input[name=di_cidade]').attr('readonly') != undefined) {
                    $('input[name=di_cidade]').val("");
                    $('input[name=di_cidade]').attr('readonly', false)
                }
                if ($('input[name=di_bairro]').attr('readonly') != undefined) {
                    $('input[name=di_bairro]').val("");
                    $('input[name=di_bairro]').attr('readonly', false)
                }
            }

            function atualizar_cidade(uf_sel_id, $cidade) {

                $(".recebe-cidade").html("<option value=''>Aguarde...</option>");
                $.ajax({
                    url: 'http://Nossa Empresa-office.net/publico/index.php/distribuidor/estado',
                    type: 'POST',
                    data: {es_id: uf_sel_id},
                    dataType: 'json',
                    success: function(cidadesJson) {
                        var txt_cidades = "<option value=''>--<?php echo $this->lang->line('select_a_cidade'); ?>--</option>";
                        $.each(cidadesJson, function(index, cidade) {
                            txt_cidades += "<option class='cid-" + cidade.ci_id + "' value='" + cidade.ci_id + "'>" + cidade.ci_nome + "</option>";
                        });
                        $(".recebe-cidade").html(txt_cidades);
                        $(".recebe-cidade").removeAttr("disabled");
                        $("#di_numero").focus();
                        marcar_cidade_selecionada($cidade);
                    }
                });
                delete_loading();
            }

            function carrega_uf_cidade($json) {

                $(".ajax-uf option").removeAttr('selected');
                $(".uf-" + $json.uf).attr('selected', 'selected');
                $id_uf = $(".uf-" + $json.uf).val();
                atualizar_cidade($id_uf, $json);
            }

            function marcar_cidade_selecionada($json) {
                $.ajax({
                    url: "http://Nossa Empresa-office.net/publico/index.php/distribuidor/cidade_by_name",
                    type: 'post',
                    data: {city: $json.localidade, uf: $id_uf},
                    dataType: 'json',
                    success: function($json) {
                        $(".cid-" + $json.ci_id + "").attr('selected', 'selected');
                    }
                });
            }


            function usuario_disponivel(usuario) {

                $(".di_usuario").after(get_loading());
                $(".alert-usuario").remove();
                var alphaExp = /^[a-zA-Z-0-9]+$/;
                if (!usuario.match(alphaExp)) {
                    $(".di_usuario").val("");
                    $(".di_usuario").focus();
                    $(".di_usuario").after("<span class='alert-usuario indisponivel'><br>Usuário inválido, use apenas letras(sem acentuação) e/ou números. Ex: usuario10, usuario</span>");
                    delete_loading();
                    return false;
                }

                $(".di_usuario").after("<span class='alert-usuario'> Verificando usurio...</div>");
                if (usuario.length > 3) {
                    $.ajax({
                        url: 'http://Nossa Empresa-office.net/publico/index.php/distribuidor/usuario_disponivel/' + usuario,
                        dataType: 'json',
                        success: function($json) {
                            if ($json.usuarios == 0) {
                                $(".alert-usuario").html("<span class='alert-usuario disponivel'>Usuário disponível</span>");
                            } else {
                                $(".di_usuario").val("");
                                $(".di_usuario").focus();
                                $(".alert-usuario").html("<span class='alert-usuario indisponivel'>Usuário indiponível</span>");
                            }
                            delete_loading();
                        }
                    });
                }
            }


            function verificar_patrocinador(usuario) {
                if (usuario.length > 3) {

                    $(".di_ni_patrocinador").after(get_loading());
                    $(".alert-patrocinador").remove();
                    $.ajax({
                        url: '<?php echo base_url("index.php/distribuidor/patrocinador_existe/") ?>' + usuario,
                        dataType: 'json',
                        success: function($json) {
                            if ($json.usuarios != 0) {
                                console.debug($json);
                                if ($json.usuarios == 'inativo') {
                                    $(".di_ni_patrocinador").val("");
                                    $(".di_ni_patrocinador").after("<span class='alert-patrocinador indisponivel'><br>O Patrocinador <b>" + usuario + "</b> está inativo</span>");
                                    //Distribuidor existe
                                } else {
                                    $(".di_ni_patrocinador").after("<span class='alert-patrocinador disponivel'><br><strong>" + $json.usuarios.di_nome + "</strong>");
                                }
                            } else {
                                $(".di_ni_patrocinador").val("");
                                $(".di_ni_patrocinador").after("<span class='alert-patrocinador indisponivel'><br><?php echo $this->lang->line('error_patrocinador_inexistente'); ?></span>");
                            }

                            delete_loading();
                        }
                    });
                }
            }


            function desativaBtn() {
                $('#enviar-cadastro').attr('disabled', 'disabled');
                setTimeout('ativarbtn()', 8000);
            }

            function ativarbtn() {
                $('#enviar-cadastro').removeAttr('disabled');
            }

            function VerificaCPF(elemento) {

                elemento.value = elemento.value.replace('.', '');
                elemento.value = elemento.value.replace('.', '');
                elemento.value = elemento.value.replace('-', '');
                if (elemento.value.search("/") != -1) {
                    return false;
                }

                if (!vercpf(elemento.value))
                {
                    alert('CPF que você informou não é válido');
                    elemento.value = "";
                }
            }

            function vercpf(cpf)
            {
                if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
                    return false;
                add = 0;
                for (i = 0; i < 9; i ++)
                    add += parseInt(cpf.charAt(i)) * (10 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(9)))
                    return false;
                add = 0;
                for (i = 0; i < 10; i ++)
                    add += parseInt(cpf.charAt(i)) * (11 - i);
                rev = 11 - (add % 11);
                if (rev == 10 || rev == 11)
                    rev = 0;
                if (rev != parseInt(cpf.charAt(10))) {
                    return false;
                } else {
                    return true;
                }

            }
            var win = null;
            function NovaJanela(pagina, nome, w, h, scroll) {
                LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
                TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
                settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
                win = window.open(pagina, nome, settings);
            }
            function carregar_pagina_pais(pais) {
                window.location = "http://Nossa Empresa-office.net/publico/index.php/distribuidor/cadastro?idioma=" + $(pais).val() + "&lang=721542f74479856ba94711dc2724e057";
            }
        </script>				<script>
            function hide_notificacao(id) {
                $(id).fadeOut(1000);
            }

            $(function() {
                $(".remover-icon").click(function() {
                    return confirm('Deseja realmente remover?');
                });
                $(".moeda").maskMoney({symbol: "R$", decimal: ".", thousands: ""});
                /*ESTADOS AJAX*/
                $(".ajax-uf").change(function() {

                    var uf_sel_id = $(this).val();
                    $(".recebe-cidade").html("<option value=''>Aguarde...</option>");
                    $.ajax({
                        url: 'http://Nossa Empresa-office.net/publico/index.php/distribuidor/cidades',
                        type: 'POST',
                        data: {es_id: uf_sel_id},
                        dataType: 'json',
                        success: function(cidadesJson) {
                            var txt_cidades = "<option value=''>--<?php echo $this->lang->line('select_a_cidade'); ?>--</option>";
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
                $(".mtel").mask("(99)?999999999");
                $(".mcep").mask("99999-999");
                $(".mcpf").mask("999.999.999-99");
                $(".mcpf_number").mask("?99999999999");
                $(".mdata").mask("99/99/9999");
                $(".mcnpj").mask("99.999.999/9999-99");
                $(".mhora").mask("99:99:99");
                $(".mdata_metade").mask("99/9999");
            });</script>

    </div>



</div>

<script>

    function num(dom) {
        dom.value = dom.value.replace(/\D/g, '');
    }
//    função auto completar
    $(document).ready(function() {
        //Validando so para aceitar munero

        $("#cidade1").autocomplete({
            source: "<?PHP echo base_url('index.php/distribuidor/cidade_by_name'); ?>",
            minLength: 3, //search after two characters
            select: function(event, ui) {
            },
            _renderItem: function(ul, item) {
                console.debug(item);
                return $("<li>")
                        .attr("data-value", item.id)
                        .append($("<a>").text(item.name))
                        .appendTo(ul);
            },
        });
    });
    function alerta() {
        alert('Contrato temporariamente indisponivel');
    }
    $(function() {
        $(".formError").live('hover', function() {
            $(".formError").remove();
        });
    });
    function get_loading() {
        return "<span class='loading-ajax'><img src='<?php echo base_url('public/imagem/loading.gif') ?>' /> aguarde...</span>";
    }

    function delete_loading() {
        $(".loading-ajax").remove();
    }

    function set_pessoa($tipo) {
        if ($tipo == 1) {
            $(".recebe-rg").html("RG:");
            $(".recebe-cpf").html("CPF:");
            $(".mcpf_number").mask("99999999999");
        } else {
            $(".recebe-rg").html("IE:");
            $(".recebe-cpf").html("CNPJ:");
            $(".mcpf_number").mask("99.999.999/9999-99");
        }
    }

    function atualizar_estados(es_nome) {
        $(".recebe-estado").html("<option value=''>Aguarde...</option>");
        $.ajax({
            url: '<?php echo base_url() ?>index.php/distribuidor/estados',
            type: 'POST',
            data: {es_pais: $('#pais1 :selected').val()},
            dataType: 'json',
            success: function(cidadesJson) {
                //Mostar os cartões.
                $('#esconder_cartoes').show();
                var txt_cidades = "<option value=''>-- <?php echo $this->lang->line('label_selecione'); ?> --</option>";
                $.each(cidadesJson, function(index, cidade) {
                    txt_cidades += "<option value='" + cidade.es_id + "'>" + cidade.es_nome + "</option>";
                });
                $(".recebe-estado").html(txt_cidades);
                $(".recebe-estado").removeAttr("disabled");
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

    function atualizar_estados_entrega(selecione) {
        var selecione = selecione;
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

                //selecionando o objeto esperando.
                if (selecione != "undefined") {
                    $("select[name=end_estado]").val(selecione);
                }
            }
        });
        delete_loading();
    }

    //Preencher os campos em caso de uruguai
    function preecher_campos() {
        $('input[name=di_endereco]').val("Rua 25 de Maio");
        $('input[name=di_endereco]').attr('readonly', true);
        $('input[name=di_numero]').val("713");
        $('input[name=di_numero]').attr('readonly', true);
        $('input[name=di_complemento]').val("Edifício Imperium Building, sala 510.");
        $('input[name=di_complemento]').attr('readonly', true);
        $('input[name=di_cep]').val("");
        $('input[name=di_cep]').attr('readonly', true);
        $('input[name=di_cidade]').val("Montevidéu");
        $('input[name=di_cidade]').attr('readonly', true);
        $('input[name=di_bairro]').val('Centro');
        $('input[name=di_bairro]').attr('readonly', true);
    }

    //Limpra campos.
    function limpar_campos() {

        if ($('input[name=di_uf]').attr('readonly') != undefined) {
            $('input[name=di_uf]').val("");
            $('input[name=di_uf]').attr('readonly', false)
        }
        if ($('input[name=di_endereco]').attr('readonly') != undefined) {
            $('input[name=di_endereco]').val("");
            $('input[name=di_endereco]').attr('readonly', false)
        }
        if ($('input[name=di_numero]').attr('readonly') != undefined) {
            $('input[name=di_numero]').val("");
            $('input[name=di_numero]').attr('readonly', false)
        }
        if ($('input[name=di_complemento]').attr('readonly') != undefined) {
            $('input[name=di_complemento]').val("");
            $('input[name=di_complemento]').attr('readonly', false)
        }
        if ($('input[name=di_cep]').attr('readonly') != undefined) {
            $('input[name=di_cep]').val("");
            $('input[name=di_cep]').attr('readonly', false)
        }
        if ($('input[name=di_cidade]').attr('readonly') != undefined) {
            $('input[name=di_cidade]').val("");
            $('input[name=di_cidade]').attr('readonly', false)
        }
        if ($('input[name=di_bairro]').attr('readonly') != undefined) {
            $('input[name=di_bairro]').val("");
            $('input[name=di_bairro]').attr('readonly', false)
        }
    }

    function atualizar_estados2() {
        $(".recebe2-estado").html("<option value=''>Aguarde...</option>");
        $.ajax({
            url: '<?php echo base_url() ?>index.php/distribuidor/estados',
            type: 'POST',
            data: {es_pais: $('#pais2 :selected').val()},
            dataType: 'json',
            success: function(cidadesJson) {
                var txt_cidades = "<option value=''>-- <?php echo $this->lang->line('label_selecione'); ?> --</option>";
                $.each(cidadesJson, function(index, cidade) {
                    txt_cidades += "<option class='cid-" + cidade.es_id + "' value='" + cidade.es_id + "'>" + cidade.es_nome + "</option>";
                });
                $(".recebe2-estado").html(txt_cidades);
                $(".recebe2-estado").removeAttr("disabled");
                $("#di_numero").focus();
            }
        });
        delete_loading();
    }


    function atualizar_cidade(uf_sel_id, $cidade) {

        $(".recebe-cidade").html("<option value=''>Aguarde...</option>");
        $.ajax({
            url: '<?php echo base_url() ?>index.php/distribuidor/estado',
            type: 'POST',
            data: {es_id: uf_sel_id},
            dataType: 'json',
            success: function(cidadesJson) {
                var txt_cidades = "<option value=''>--<?php echo $this->lang->line('select_a_cidade'); ?>--</option>";
                $.each(cidadesJson, function(index, cidade) {
                    txt_cidades += "<option class='cid-" + cidade.ci_id + "' value='" + cidade.ci_id + "'>" + cidade.ci_nome + "</option>";
                });
                $(".recebe-cidade").html(txt_cidades);
                $(".recebe-cidade").removeAttr("disabled");
                $("#di_numero").focus();
                marcar_cidade_selecionada($cidade);
            }
        });
        delete_loading();
    }

    function carrega_uf_cidade($json) {

        $(".ajax-uf option").removeAttr('selected');
        $(".uf-" + $json.uf).attr('selected', 'selected');
        $id_uf = $(".uf-" + $json.uf).val();
        atualizar_cidade($id_uf, $json);
    }


    function marcar_cidade_selecionada($json) {
        $.ajax({
            url: "<?php echo base_url('index.php/distribuidor/cidade_by_name') ?>",
            type: 'post',
            data: {city: $json.localidade, uf: $id_uf},
            dataType: 'json',
            success: function($json) {
                $(".cid-" + $json.ci_id + "").attr('selected', 'selected');
            }
        });
    }


    function usuario_disponivel(usuario) {

        $(".di_usuario").after(get_loading());
        $(".alert-usuario").remove();
        var alphaExp = /^[a-zA-Z-0-9]+$/;
        if (!usuario.match(alphaExp)) {
            $(".di_usuario").val("");
            $(".di_usuario").focus();
            $(".di_usuario").after("<span class='alert-usuario indisponivel'><br><?php echo $this->lang->line('error_usuario_invalido'); ?>senha");
            delete_loading();
            return false;
        }

        $(".di_usuario").after("<span class='alert-usuario'> Verificando usurio...</div>");
        if (usuario.length > 3) {
            $.ajax({
                url: '<?php echo base_url('index.php/distribuidor/usuario_disponivel') ?>/' + usuario,
                dataType: 'json',
                success: function($json) {
                    if ($json.usuarios == 0) {
                        $(".alert-usuario").html("<span class='alert-usuario disponivel'>Usuário disponível</span>");
                    } else {
                        $(".di_usuario").val("");
                        $(".di_usuario").focus();
                        $(".alert-usuario").html("<span class='alert-usuario indisponivel'>Usuário indiponível</span>");
                    }
                    delete_loading();
                }
            });
        }
    }


    function verificar_patrocinador(usuario) {

        if (usuario.length > 3) {

            $(".di_ni_patrocinador").after(get_loading());
            $(".alert-patrocinador").remove();
            $.ajax({
                url: '<?php echo base_url('index.php/distribuidor/patrocinador_existe') ?>/' + usuario,
                dataType: 'json',
                success: function($json) {
                    if ($json.usuarios != 0) {

                        if ($json.usuarios == 'inativado') {
                            $(".di_ni_patrocinador").val("");
                            $(".di_ni_patrocinador").after("<span class='alert-patrocinador indisponivel'><br><?php echo $this->lang->line('error_patrocinador_nao_encontrado'); ?><b></span>");
                        } else {

                            if ($json.usuarios == 'inativo') {
                                $(".di_ni_patrocinador").val("");
                                $(".di_ni_patrocinador").after("<span class='alert-patrocinador indisponivel'><br>O Patrocinador <b>" + usuario + "</b> está inativo</span>");
                                //Distribuidor existe
                            } else {
                                if ($json.usuarios.plano === 100 || $json.usuarios.plano === "100") {
//                                    $(".planos").show();
//                                    $(".plano-100").hide();
//                                    $(".plano-99").hide();
//                                    $(".valor-100").hide();
//                                    $(".valor-99").hide();
//                                    setar_radio("plano", 100, false);
                                } else {
                                    $(".planos").show();
                                }
                                $(".di_ni_patrocinador").after("<span class='alert-patrocinador disponivel'><br><strong>" + $json.usuarios.di_nome + "</strong>");
                            }
                        }
                    } else {
                        $(".di_ni_patrocinador").val("");
                        $(".di_ni_patrocinador").after("<span class='alert-patrocinador indisponivel'><br><?php echo $this->lang->line('error_patrocinador_inexistente'); ?></span>");
                    }
                    delete_loading();
                }
            });
        }
    }


    function desativaBtn() {
        $('#enviar-cadastro').attr('disabled', 'disabled');
        setTimeout('ativarbtn()', 8000);
    }
    function ativarbtn() {
        $('#enviar-cadastro').removeAttr('disabled');
    }

    function VerificaCPF(elemento) {

        elemento.value = elemento.value.replace('.', '');
        elemento.value = elemento.value.replace('.', '');
        elemento.value = elemento.value.replace('-', '');
        if (elemento.value.search("/") != -1) {
            return false;
        }

        if (!vercpf(elemento.value))
        {
            alert('CPF que você informou não é válido');
            elemento.value = "";
        }
    }

    function vercpf(cpf)
    {
        if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
            return false;
        add = 0;
        for (i = 0; i < 9; i ++)
            add += parseInt(cpf.charAt(i)) * (10 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(9)))
            return false;
        add = 0;
        for (i = 0; i < 10; i ++)
            add += parseInt(cpf.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(10))) {
            return false;
        } else {
            return true;
        }

    }
    var win = null;
    function NovaJanela(pagina, nome, w, h, scroll) {
        LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
        TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
        settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
        win = window.open(pagina, nome, settings);
    }
    function carregar_pagina_pais(pais) {
        window.location = "<?php echo base_url("index.php/distribuidor/cadastro"); ?>?idioma=" + $(pais).val() + "&lang=<?php echo md5(time()); ?>";
    }

    function get_dados_usuario(input) {
        if (input.value != "") {
            $.ajax({
                url: '<?php echo base_url('/index.php/distribuidor/get_dados_plataform'); ?>',
                data: {'email': input.value},
                dataType: 'json',
                success: function(data) {

                    //So altera se tiver mesmo voltar o cadastro.
                    if (data.status != 1) {

                        $('#di_nome').val(data.name);
                        $('#di_ultimo_nome').val(data.surname);
                        $('#di_fone1').val(data.phone);
                        $('#di_fone2').val(data.cellPhone);
                        $('#di_cidade_nascimento').val(data.cityOfBirth);
                        $('#di_complemento').val(data.completion);
                        $('#di_rg').val(data.taxIdNumber);
                        $('#confirm_email').val(data.email);
                        $('#di_cep').val(data.zipCode);
                        $('#cidade1').val(data.city);
                        $('#di_numero').val(data.number);
                        $('#di_endereco').val(data.street);
                        $('#di_niv').val(data.niv);
                        $('input[name=di_data_nascimento]').val(data.birthday);
                        $('input[name=di_bairro]').val(data.district);
                        atualizar_pais(data.country, data.state);
                    }

                }
            });
        }
    }

    function atualizar_pais(nome_pais, es_nome) {
        $("#di_pais").prop('disabled', true)
        $.ajax({
            url: '<?php echo base_url('index.php/distribuidor/get_pais_ajax'); ?>',
            type: 'POST',
            data: {ps_nome: nome_pais},
            dataType: 'json',
            success: function(cidadesJson) {
                if (cidadesJson.status == 1) {
                    console.debug(cidadesJson.data);
                    $('#pais1 option')
                            .removeAttr('selected')
                            .filter('[value=' + cidadesJson.data + ']')
                            .attr('selected', true);
                    //Atualiza a regra do cartão.  
                    get_cartao(cidadesJson.ps_id);
                    atualizar_estados(es_nome);
                    if ($("#codigo_promocional").val() == "") {
                        $('#suck_planos').load('<?php echo base_url('/index.php/distribuidor/get_plano_cambio_ajax?id_pais='); ?>' + cidadesJson.ps_id);
                    }

                }

                $("#di_pais").removeAttr("disabled");
            }
        });
    }

    function get_cartao(pais) {
        if ( (pais == 169) || (pais == 100) || (pais == 2) || (pais == 1) ) {
            $('#cartao-2').show();
            $('#cartao-3').hide();
        }

        if ( (pais != 169) && (pais != 100) && (pais != 2) && (pais != 1) ) { 
            $('#cartao-2').hide();
            $('#cartao-3').show();
        }
    }

    $('.verificar_dastrado_por_pais').blur(function() {

        var di_nome = $('#di_nome').val();
        var di_ultimo_nome = $('#di_ultimo_nome').val();
        var di_pais_nascimento = $('#di_pais_nascimento').val();
        var di_rg = $('#di_rg').val();
        if (di_nome != "" &&
                di_ultimo_nome != "" &&
                di_pais_nascimento != 0 &&
                di_rg != ""
                ) {

            $('.label_usuario_cadastrado').hide().html('').removeClass('verde');
            $('.label_usuario_cadastrado').hide().html('').removeClass('vermelho');
            $.ajax({
                url: '<?php echo base_url('index.php/distribuidor/verificar_usuario_cadastrado_ajax'); ?>',
                type: 'post',
                data: {'di_nome': di_nome, 'di_ultimo_nome': di_ultimo_nome, 'di_pais_nascimento': di_pais_nascimento, 'di_rg': di_rg},
                dataType: 'json',
                success: function(data) {
                    if (data.response == "ok") {
                        $('.label_usuario_cadastrado').show().html(data.information).addClass('verde');
                    } else {
                        $('.label_usuario_cadastrado').show().html(data.information).addClass('vermelho');
                    }
                }
            });
        }// fim da verificação 

    });
    function mostrar() {
        $('.notification').css('display', 'block');
    }

    function nao_mostrar() {
        $('.notification').css('display', 'none');
    }

    function setar_radio(name, value, checked) {
        $('input:radio[name="' + name + '"][value="' + value + '"]').attr("checked", checked);
    }

    //colocar mascara nos compos tipo data.
    $('.mdata').mask('99/99/9999');
    //Selecionando o primeiro radio button.
    $('.membershipHide').attr("checked", true);
    //$('.plano-99').hide();

    //Verificando se ja existe o cartão memberShip
    $('.di_email_virificar_cartao').blur(function() {
        $(this).attr('placeholder', 'aguarde carregando...');
        $.ajax({
            'url': '<?php echo base_url('index.php/distribuidor/verificar_membership_ajax'); ?>',
            'data': {'email': $(this).val()},
            'type': 'post',
            'dataType': 'json',
            'success': function(data)
            {
                console.log(data);
                $('.di_email_virificar_cartao').attr('placeholder', '');
                if (data.resposta == 0) {
                    $('.plano-99').show();
                    $('.membershipShow').attr("checked", true);
                } else {
                    $('.plano-99').hide();
                    $('.membershipHide').attr("checked", true);
                }
            }
        });
    });
    //memberShipShow
    $('.membershipShow').click(function() {
        $('.plano-99').show();
        $('.di_email_virificar_cartao').val('');
    });
    //Hide membership
    $('.membershipHide').click(function() {
        $('.plano-99').hide();
    });
//Permite somente letras e numero.
    $(".so_letra_ecaractere_espercial").keyup(function() {
        $(this).val($(this).val().match(/[a-zA-Z\u00C0-\u00FF " " ]+/g));
    });
//Permite somente numero e letra miuscula
    $(".so_letras_numeros").keyup(function() {
        $(this).val($(this).val().match(/[a-zA-Z0-9]+/g));
    });
//Permite somente letra miuscula
    $(".so_letras").keyup(function() {
        $(this).val($(this).val().match(/[a-zA-Z]+/g));
    });
    // $("#codigo_promocional").mask('****.****.****-*****.****-*');
    $("#codigo_promocional").click(function() {
        $('#resposta-token').html('');
        $('#di_usuario_patrocinador').attr('readonly', false);
    });

    $("#codigo_promocional").blur(function() {
        $('#di_usuario_patrocinador').attr('readonly', true);
        $.ajax({
            url: '<?php echo base_url("index.php/distribuidor/verificar_codigo_promocional_ajax"); ?>',
            data: {codigo_promocional: $(this).val()},
            type: 'post',
            dataType: 'json',
            success: function(data) {
                if (data.resposta == 'ok') {

                    $('#di_usuario_patrocinador').attr('readonly', true);
                    $('#di_usuario_patrocinador').val(data.usuario);
                    verificar_patrocinador(data.usuario);
                    $('#suck_planos').load('<?php echo base_url('index.php/distribuidor/get_plano_cambio_ajax?pa_id='); ?>' + data.plano);
                    $('#resposta-token').html('<br><span id="resposta-token" class="help-inline" style="color: GREEN;font-weight: bold;"><?php echo $this->lang->line('label_resposta_codigo_promocional_valido'); ?></span>');
                } else {
                    $('#suck_planos').load('<?php echo base_url('index.php/distribuidor/get_plano_cambio_ajax?id_pais='); ?>' + $('#pais1 :selected').val());
                    $('#resposta-token').html('<br><span id="resposta-token" class="help-inline" style="color: red;font-weight: bold;"><?php echo $this->lang->line('label_resposta_codigo_promocional_invalido'); ?></span>');
                    $('#di_usuario_patrocinador').attr('readonly', false);
                }
            }
        });
    });
</script>
<a href="distribuidor_cadastro_view.php_bkp"></a>

<?php
unset($_SESSION['form_cad']);
unset($_SESSION['form_cad_error']);
?>
