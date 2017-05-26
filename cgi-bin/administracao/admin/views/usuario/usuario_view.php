<div class="box-content min-height">
    <div class="box-content-header">Usuários</div>
    <div class="box-content-body">

        <a class="btn btn-success" href="<?php echo base_url('index.php/usuario/add') ?>">Criar Novo</a>
        <br>
        <br>
        <table width="100%" class="table table-bordered table-hover" border="0" cellspacing="0" cellpadding="0">

            <thead>
                <tr>
                    <td width="9%" bgcolor="#f0f0f0">Login</td>
                    <td width="39%" bgcolor="#f0f0f0">Nome</td>
                    <td width="31%" bgcolor="#f0f0f0">E-mail</td>
                    <td width="21%" bgcolor="#f0f0f0">Ação</td>
                </tr>
            </thead>

            <?php
            foreach ($usuarios as $u) {
  ?>
                <tr>
                    <td><?php echo $u->rf_id ?>ind</td>
                    <td><?php echo $u->rf_nome ?></td>
                    <td><?php echo $u->rf_email ?></td>
                    <td>
                        <div class="btn-group">
                          
                            <button type="button" class="btn <?php echo ($u->rf_bloqueio==0?'':'btn-danger'); ?>" onclick="window.open('<?php echo base_url('index.php/usuario/editar/' . $u->rf_id) ?>', '_self');">Editar</button>
                            <button style="padding: 8px;" class="btn dropdown-toggle <?php echo ($u->rf_bloqueio==0?'':'btn-danger'); ?>" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                    <li> <a    onClick="return confirm('Deseja remover o usuário?')"
                                                href="<?php echo base_url('index.php/usuario/remover/' . $u->rf_id) ?>">Remover</a>
                                    </li>
                                    <li>
                                        <a   onClick="return confirm('<?php echo ($u->rf_bloqueio==0?'Deseja Bloquear esse usuário?':'Desbloquear esse usuário?'); ?>')" 
                                             href="<?php echo base_url('index.php/usuario/bloquear_usuario/' . $u->rf_id) ?>"  ><i class="icon-ban-circle"></i> <?php echo $u->rf_bloqueio==0?'Bloquear Usuário':'Desbloquear Usuário'; ?></a>
                                    </li>
                            </ul>
                        </div><!-- /btn-group -->
                     </td>
                </tr>  
            <?php } ?>

        </table>


    </div>
</div>