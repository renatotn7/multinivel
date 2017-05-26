<div class="box-content min-height">
    <div class="box-content-header">
        <a href="<?php echo base_url('index.php/usuario') ?>">
            Usuários
        </a>
        &raquo; Criar Novo </div>
    <div class="box-content-body">

        <form method="post" action="<?php echo base_url('index.php/usuario/editar_usuario') ?>">

            <input type="hidden" name="rf_id" value="<?php echo $user->rf_id ?>" />
            Nome:<br />
            <input type="text" class="validate[required]" value="<?php echo $user->rf_nome ?>" name="rf_nome" />
            <br />
            E-mail:<br />
            <input type="text" class="validate[required, custom[email]]" value="<?php echo $user->rf_email ?>" name="rf_email" />
            <br />
            Nova Senha:<br />
            <input type="password" name="senha" /> 
            <br />

            <div style="margin:7px 0;" class="alert alert-info"><h4>Dica de Senha</h4>No minimo 8 caracteres, não pode ser sequêncial.</div>



            Permissão:<br />
            <div style="margin-bottom:15px;">
                <?php
                $modulos = get_modulos();
                foreach ($modulos as $k => $m) {
                    ?>
                    <div style="padding:10px 0; width:400px; border-bottom:1px solid #f0f0f0">
                        <strong><?php echo $m['name'] ?></strong><br />
                        <?php if (isset($m['option'][0]) && $m['option'][0] == 'combo') { ?>
                            <input type="checkbox" <?php echo permissao($k, 'visualizar', $user) ? 'checked' : ''; ?> name="permissao[<?php echo $k ?>][visualizar]" value="1" /> Visualizar
                            <input type="checkbox" <?php echo permissao($k, 'adicionar', $user) ? 'checked' : ''; ?> name="permissao[<?php echo $k ?>][adicionar]" value="1" /> Adicionar
                            <input type="checkbox" <?php echo permissao($k, 'editar', $user) ? 'checked' : ''; ?> name="permissao[<?php echo $k ?>][editar]" value="1" /> Editar
                            <input type="checkbox" <?php echo permissao($k, 'excluir', $user) ? 'checked' : ''; ?> name="permissao[<?php echo $k ?>][excluir]" value="1" /> Excluir
                            <?php
                        } else {
                            foreach ($m['option'] as $op) {
                                ?>
                                <input type="checkbox" <?php echo permissao($k, $op, $user) ? 'checked' : ''; ?> name="permissao[<?php echo $k ?>][<?php echo $op ?>]" value="1" /> <?php echo $op ?>
                                <?php
                            }
                        }
                        ?>
                    </div>  
                <?php } ?>
            </div>


            <input type="submit" class="btn btn-primary" value="Salvar Usuário" />
            <a class="btn" href="<?php echo base_url('index.php/usuario') ?>">Cancelar</a>

        </form>
        <hr/>
        <form name="permissao_empresa"  method="POST" action="<?php echo base_url('index.php/usuario/permissao_empresas/'.$this->uri->segment(3)); ?>">
            <a name="permissao"></a>
            <div class="row">
                <div class="span3">
                    <label for="empresa"></label>
                    <select name="empresa" id="empresa" class="span3">
                        <option value="">--Todas Empresa--</option>
                        <?php
                        $empresas = $this->db->get('empresas')->result();
                        foreach ($empresas as $key => $empresa_value) {
                            ?>
                            <option value="<?php echo $empresa_value->ep_id; ?>"><?php echo $empresa_value->ep_nome; ?></option>
                        <?php }
                        ?>
                    </select> 
                </div>
                <div class="span3">
                    <button class="btn"><i class="icon-plus"></i> Incluir Permissão</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered">
            <tr>
                <th>Empresa</th>
                <th>Ação</th>
            </tr>
            <?php
            $permissao = $this->db
                            ->join('responsaveis_fabrica', 'rf_id=per_id_usuario')
                            ->join('empresas', 'ep_id=per_id_empresa')
                            ->get('permissao_empresas_usuario')->result();
            
            foreach ($permissao as $key => $permissao_value) {
                ?>
                <tr>
                    <td><?php echo $permissao_value->ep_nome; ?></td>
                    <td><a href="<?php echo base_url('index.php/usuario/remover_permissao_empresas/'.$this->uri->segment(3).'?per_id='.$permissao_value->per_id);?>">Remover Permissão</a></td>
                </tr>
            <?php } ?>
        </table>

        <hr/>
        <form name="permissao_pais" id="permissao_pais" method="post" action="<?php echo base_url('index.php/usuario/permissao_pais'); ?>">
            Premissao Por País:<br/>
            <input type="hidden" name="rf_id" value="<?php echo $user->rf_id; ?>" />
            <select id="rfp_id_pais" name="rfp_id_pais" onchange="submit();">
                <option value="">--Todos os países--</option>
                <?php
                $paises = $this->db->get('pais')->result();
                foreach ($paises as $p) {
                    ?>
                    <option value="<?php echo $p->ps_id; ?>"><?php echo $p->ps_nome; ?></option>
                <?php } ?>
            </select><br/>
        </form>
        <a name="permissao-pais"></a> 
        <table class="table table-hover table-bordered">
            <tr>
            <thead>
            <th>Nº Permissão</th>
            <th>Nome País</th>
            <th></th>
            </thead>
            </tr>
            <?php
            $premissao_pais = $this->db->where('rfp_id_responsavel_fabrica', $user->rf_id)
                            ->join('pais', 'ps_id=rfp_id_pais')
                            ->get('responsaveis_fabrica_paises')->result();
            if (count($premissao_pais) > 0) {
                foreach ($premissao_pais as $p) {
                    ?>
                    <tr>
                        <td><?php echo $p->rfp_id; ?></td>
                        <td><?php echo $p->ps_nome; ?></td>
                        <td>
                            <a href="<?php echo base_url('index.php/usuario/remover_permissao_pais?rfp_id=' . $p->rfp_id . '&rf_id=' . $user->rf_id); ?>">Remover Permissão</a>
                        </td>
                    </tr>

                    <?php
                }
            } else {
                ?>
                <tr>
                    <td style="text-align: center" colspan="3">Nenhuma Permissão de País foi Definida Ainda.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>