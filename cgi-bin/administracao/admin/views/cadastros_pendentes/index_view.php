<div class="box-content min-height">
    <div class="box-content-body">

        <form name="formulario" method="get" action="<?php echo base_url('index.php/cadastros_pendentes/index/') ?>">
            <fieldset>
                <legend>Cadastros Pendentes</legend>
                <div class="row">
                    <div class="span3">
                        <label for="usuario">Usuário:</label>
                        <input type="text" name="usuario" id="usuario" style="margin:0;" />
                    </div>
                    <div class="span3">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" style="margin:0;" />
                    </div>
                    <div class="span3">
                        <label for="cpf">CPF:</label>
                        <input type="text" name="rg" id="cpf" style="margin:0;" />  
                    </div>
                    <div class="span3">
                        <label for="planos">Planos:</label>
                        <select name="plano" id="planos">
                            <option value="">--Indiferente--</option>
                            <?php
                            $planos = $this->db->where('pa_id !=104')->get('planos')->result();
                            foreach ($planos as $plano) {
                                ?>
                                <option value="<?php echo $plano->pa_id; ?>"><?php echo $plano->pa_descricao; ?></option>
                            <?php } ?>
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
                                <option value="<?php echo $empresa_value->ep_id; ?>">
                                    <?php echo $empresa_value->ep_nome; ?>
                                </option>                      
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <input type="submit" class="btn btn-info" value="Enviar" />      
            </fieldset>
        </form>

        <table id="table-listagem" class="table table-hover table-bordered" width="100%" border="0" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <td width="8%" bgcolor="#f7f7f7"><strong>Ações</strong><br /></td>
                    <td width="21%" bgcolor="#f7f7f7"><strong>Distribuidor - (usuario)</strong><br /></td>
                    <td width="8%" bgcolor="#f7f7f7"><strong>Data</strong></td>
                    <td width="8%" valign="top" bgcolor="#f7f7f7"><strong>Patrocinador</strong></td>
                    <td width="17%" valign="top" bgcolor="#f7f7f7"><strong>País</strong></td>
                    <td width="11%" valign="top" bgcolor="#f7f7f7"><strong>Planos</strong></td>
                    <td width="8%" valign="top" bgcolor="#f7f7f7"><strong>Pedido Pago</strong></td>
                    <td width="10" valign="top" bgcolor="#f7f7f7"><strong>Pedidos Pendentes</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($distribuidoresPendentes as $distribuidor) {
                    $pais = DistribuidorDAO::getPais($distribuidor->di_cidade);

                    $pedidosPagos = $this->db
                                    ->where('co_id_distribuidor', $distribuidor->di_id)
                                    ->where('co_pago', 1)
                                    ->get('compras')->result();

                    $pedidosNaoPagos = $this->db
                                    ->where('co_id_distribuidor', $distribuidor->di_id)
                                    ->where('co_pago', 0)
                                    ->where('co_situacao <>', -1)
                                    ->get('compras')->result();
                    ?>

                    <tr>
                        <td>
                            <div class="btn-toolbar" style="margin: 0;">
                                <div class="btn-group">
                                    <?php if (permissao('arede', 'login', get_user())) { ?> 
                                        <button type="button" class="btn" onclick="window.open('<?php echo base_url('index.php/distribuidores/login_distribuidor/' . $distribuidor->di_id) ?>', '_blank');">Login</button>
                                    <?php } ?>
                                    <button style="padding: 8px;" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <?php if (permissao('arede', 'editar', get_user())) { ?>
                                            <li><a href="<?php echo base_url('index.php/distribuidores/editar_distribuidor/' . $distribuidor->di_id) ?>" target="_self"><i class="icon-pencil"></i> Editar</a></li>
                                        <?php } ?>
                                        <?php if (permissao('cadastro_pendente', 'excluir', get_user())) { ?>
                                            <li><a onclick="return confirm('Deseja Realmente Excluir o Cadastro do Distribuidor?\nEssa operação não tem volta.');" 
                                                   href="<?php echo base_url("index.php/distribuidores/remover_distribuidor/$distribuidor->di_id") ?>" target="_self"><i class="icon-trash"></i> remover Cadastro</a></li>
                                        <?php } ?>
                                        <?php if (get_user()->rf_id == 5000) { ?>
                                            <li><a href="<?php echo base_url('index.php/pedidos_distribuidor/editar_pedido/' . $distribuidor->co_id) ?>" target="_self"><i class="icon-shopping-cart"></i> Alterar situação da compra</a></li>
                                        <?php } ?>
                                    </ul>
                                </div><!-- /btn-group -->
                            </div>
                        </td>
                        <td>
                            <strong><?php echo $distribuidor->di_nome . " (" . $distribuidor->di_usuario ?>)</strong>
                        </td>

                        <td><?php echo cadastrado_quando($distribuidor->di_data_cad); ?></td>
                        <td><?php echo DistribuidorDAO::getPatrocinador($distribuidor)->di_usuario; ?></td>
                        <td>
                            <img src="<?php echo base_url('public/imagem/flags/' . $pais->ps_sigla . '.png'); ?>"/> -
                            <?php echo DistribuidorDAO::getPais($distribuidor->di_cidade)->ps_nome; ?>
                            <?php // echo $distribuidor->ci_nome."-".$distribuidor->ci_uf  ?>
                        </td>
                        <td><?php echo $distribuidor->pa_descricao; ?> </td>
                        <td><?php echo count($pedidosPagos) ?></td>
                        <td><?php echo count($pedidosNaoPagos) ?></td>

                    </tr>
                <?php } ?>

            </tbody>
        </table>    
        <?php echo $links; ?>
        <i><strong>Total de cadastros pendêntes:<?php echo $total; ?></strong></i>
    </div>
</div>