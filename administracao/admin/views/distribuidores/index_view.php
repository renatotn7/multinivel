<div class="box-content min-height">
    <div class="box-content-header">Empreendedores</div>
    <div class="box-content-body">

        <form name="formulario" action="<?php echo base_url('index.php/distribuidores') ?>" method="get">
            <div class="row">
                <div class="span3">
                    <label for="usuario">Usuário:</label>
                    <input type="text" name="usuario" style="width:220px;" size="10" value="<?php echo get_parameter('ni') ?>">
                </div>
                <div class="span3">
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" value="<?php echo get_parameter('nome') ?>">
                </div>
                <div class="span2">
                    <label for="cpf">CPF:</label>
                    <input type="text" name="cpf" style="width:110px;" value="<?php echo get_parameter('cpf') ?>">
                </div>
                <div class="span2">
                    <label for="niv">Niv:</label>
                    <input type="text" name="niv" id="niv" style="width:110px;" value="<?php echo get_parameter('niv') ?>">
                </div>
                <div class="span2">
                    <label for="email">E-mail:</label>
                    <input type="text" name="email" id="email" style="width:110px;" value="<?php echo get_parameter('email') ?>">
                </div>
            </div>
            <div class="row">
                <div class="span3">
                    <label for="pais">País:</label>
                    <select name="pais" class="ajax-pais" >
                        <option value="" selected="selected">--Indiferente--</option>
                        <?php
                        $paises = $this->db->group_by('ps_iso3')->get('pais')->result();
                        foreach ($paises as $pais) {
                            ?>
                        <option value="<?php echo $pais->ps_id; ?>" ><?php echo $pais->ps_nome; ?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="span2">
                    <label for="uf">Estado:</label>
                    <select name="uf" style="width:150px;"  class="ajax-uf recebe-uf">
                        <option value="">--Indiferente--</option>
                        <?php
                        $es = $this->db->get('estados')->result();
                        foreach ($es as $e) {
                            ?>
                            <option value="<?php echo $e->es_id ?>"><?php echo $e->es_uf ?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="span2">
                    <label for="cidade">Cidade:</label>
                    <select name="cidade" style="width:150px; margin:0;" disabled="disabled" class="recebe-cidade">
                        <option value="">--Indiferente--</option>
                    </select>
                </div>
                <div class="span2">
                    <label>Documentação:</label>
                    <select name="conta_verificada" style="width:150px; margin:0;" >
                        <option value="">--Indiferente--</option>
                        <option value="sim">OK</option>
                        <option value="nao">Pendente</option>
                    </select>
                </div>
                <div class="span2">
                    <label>Status login:</label>
                    <select name="di_login_status" style="width:150px; margin:0;" >
                        <option value="">--Indiferente--</option>
                        <option value="1">Bloqueados</option>
                        <option value="2">Desbloqueados</option>
                    </select>
                </div>
                <div  class="span2">
                    <label for="planos">Planos:</label>
                    <select  class="span2" name="planos" id="planos">
                        <option value="">--Indiferente--</option>
                        <?php
                        $planos = $this->db->where('pa_id !=104')->get('planos')->result();
                        foreach ($planos as $plano) {
                            ?>
                            <option value="<?php echo $plano->pa_id; ?>"><?php echo $plano->pa_descricao; ?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="span3">
                    <label for="empresa">Empresas</label>
                    <select name="empresa" id="empresa">
                        <option value="">--Selecione--</option>
                        <?php
                        $empresas = $this->db->get('empresas')
                            ->result();
                        foreach ($empresas as $key => $empresa_value) {
                            ?>
                            <option value="<?php echo $empresa_value->ep_id; ?>"><?php echo $empresa_value->ep_nome; ?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <input type="submit" class="btn btn-primary" value="Buscar">
        </form>
        <div id="fullscreen">
            <div class="panel-heading" style="padding: 8px 13px;">
                <div class="fullscreen-button">
                    <i class="icon-fullscreen"></i>
                </div>
            </div>
            <table width="100%" class="table table-hover table-bordered" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th width="8%"><i class="icon-cog"></i> Ação</th>
                        <th width="13%"><i class="icon-user"></i> Nome</th>
                        <th width="15%"><i class="icon-bookmark"></i>Patrocinador</th>
                        <th width="8%"><i class="icon-tasks"></i> Planos</th>
                        <th width="16%"><i class="icon-globe"></i> País</th>
                        <th width="5%"><i class="icon-book"></i>Documento</th>
                        <th width="15%"><i class="icon-barcode"></i> pagamento</th>
                        <th width="10%"><i class="icon-calendar"></i> Ativação</th>
                        <th width="10%"><i class="icon-calendar"></i>Cadastro</th>
                        <!--<th width="13%"></th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($distribuidores as $d) {
                        $pais = DistribuidorDAO::getPais($d->di_cidade);
                        ?>
                        <!-- Modal -->
                    <form name="bloquear-distribuidor" method="post" action="<?php echo base_url('index.php/distribuidores/alterar_status_login'); ?>" >
                        <input type="hidden" name="di_id" id="di_id" value="<?php echo $d->di_id; ?>"/>
                        <div id="myModal_<?php echo $d->di_id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close"  onclick="hideModal(<?php echo $d->di_id ?>);" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel"><?php echo ($d->di_login_status == 0 ? 'Desbloquear' : 'Bloquear') ?>: <I><?php echo $d->di_nome; ?></I></h3>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="di_id" id="di_id" value="<?php echo $d->di_id; ?>"/>
                                <?php if ($d->di_login_status == 1) {?>
                                    <div class="row">
                                        <div class="span">
                                            <p>Informe o motivo do bloqueio</p>
                                            <textarea class="span" name="di_mensagem"
                                                      style="margin: 0px 0px 10px; width: 521px; height: 66px;"></textarea>
                                        </div>
                                    </div>
                                <?php }?>

                            </div>
                            <div class="modal-footer">
                                <button class="btn" type="button" data-dismiss="modal" onclick="hideModal(<?php echo $d->di_id ?>);"  aria-hidden="true">Cancelar</button>
                                <button type="submit" class="btn <?php echo ($d->di_login_status == 0 ? 'btn-primary' : 'btn-danger') ?>"><?php echo ($d->di_login_status == 0 ? 'Desbloquear Usuário' : 'Bloquear Usuário') ?></button>
                            </div>
                        </div>
                    </form>
                    <tr>
                        <td>
                            <div class="btn-toolbar" style="margin: 0;">
                                <div class="btn-group">
                                    <?php if (permissao('arede', 'login', get_user())) {?>
                                        <button type="button" class="btn <?php echo (usuario_bloqueado($d->di_usuario) ? 'btn-danger' : ''); ?>" onclick="window.open('<?php echo base_url('index.php/distribuidores/login_distribuidor/' . $d->di_id) ?>', '_blank');">Login</button>
                                    <?php }?>
                                    <button style="padding: 8px;" class="btn dropdown-toggle <?php echo (usuario_bloqueado($d->di_usuario) ? 'btn-danger' : ''); ?>" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <?php if (permissao('arede', 'editar', get_user())) {?>
                                            <li><a href="<?php echo base_url('index.php/distribuidores/editar_distribuidor/' . $d->di_id) ?>" target="_self"><i class="icon-pencil"></i> Editar</a></li>
                                        <?php }?>
                                        <?php if (get_user()->rf_id == 5000 && $d->di_login_status == 1) {?>
                                            <li>
                                                <a href="javascript:void"  onclick="showModal(<?php echo $d->di_id ?>);" data-toggle="modal"><i class="icon-ban-circle"></i> Bloquear login</a>
                                            </li>
                                        <?php } else {?>
                                            <li>
                                                <a href="javascript:void"  onclick="showModal(<?php echo $d->di_id ?>);" data-toggle="modal"><i class="icon-ban-circle"></i> Desbloquear login</a>
                                            </li>
                                        <?php }?>

                                        <li>
                                            <a href="<?php echo base_url('index.php/distribuidores/historico_bloqueio/' . $d->di_id); ?>" target="_self"><i class="icon-th-list"></i> Histórico de bloqueio do usuário</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo base_url('index.php/historico_transacao/index/' . $d->di_id); ?>" target="_self"><i class="icon-th-list"></i> Histórico Dos Pagamento Plataforma de Pagamento</a>
                                        </li>
                                    </ul>
                                </div><!-- /btn-group -->
                            </div>
                        </td>
                        <td>
                            <?php echo $d->di_nome ?>   (<?php echo $d->di_usuario ?>)
                        </td>
                        <td>
                            <?php echo @DistribuidorDAO::getPatrocinador($d)->di_usuario ?>
                        </td>
                        <td>
                            <?php echo PlanosModel::getPlano($d->pa_id)->pa_descricao; ?>
                        </td>
                        <td>
                            <img src="<?php echo base_url('public/imagem/flags/' . $pais->ps_sigla . '.png'); ?>"/> -
                            <?php echo $pais->ps_nome ?></td>
                        <td>
                            <a
                                title="Ver documentação"
                                target="_blank" href="<?php echo base_url('index.php/documentos/editar/' . $d->di_id) ?>">
                                    <?php echo $d->di_contrato == 1 && $d->di_conta_verificada == 1 ? "<span class='label label-success'>ok | Ver.</span>" : "<span class='label'>pendente | Ver.</span>" ?>
                            </a>
                        </td>
                        <td><?php
                            if($d->co_forma_pgt == 20){
                                // echo $d->fp_descricao. "por " . $di_id_patrocinador;
                                echo $d->fp_descricao;
                            } else
                                echo $d->co_forma_pgt_txt;
                        ?></td>
                        <td>
                            <?php
                            $ativacao_mensal = $this->db->where('at_distribuidor', $d->di_id)
                                ->order_by('at_id', 'desc')
                                ->get('registro_ativacao')->row();

                            echo date('d/m/Y', strtotime($ativacao_mensal->at_data));
                            ?>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($d->di_data_cad)) ?></td>
                        <!--<td>
                        <?php if (permissao('arede', 'editar', get_user())) {?>
                            <a class="btn btn-info" href="<?php echo base_url('index.php/distribuidores/editar_distribuidor/' . $d->di_id) ?>">Editar</a>
                        <?php }?>
                        <?php if (permissao('arede', 'login', get_user())) {?>
                            <a class="btn btn-info" target="_blank" href="<?php echo base_url('index.php/distribuidores/login_distribuidor/' . $d->di_id) ?>">Login</a>
                        <?php }?>
                        </td>-->
                    </tr>
                <?php }?>

                </tbody>
            </table>

            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td><?php echo $links ?></td>
                    <td>Total de <b><?php echo $num_distribuidores ?></b> distribuidores</td>
                </tr>
            </table>
        </div>

    </div>
</div>
<script type="text/javascript">

    function showModal(id) {
        $('#myModal_' + id).removeClass('hide').addClass('in');
    }

    function hideModal(id) {
        $('#myModal_' + id).removeClass('in').addClass('hide');
    }
</script>
<!-- Button to trigger modal -->
