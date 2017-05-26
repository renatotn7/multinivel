<div class="box-content min-height">
    <div class="box-content-header">

        <a href="<?php echo base_url('index.php/produtos/') ?>">Produtos</a> &raquo; Categorias de produtos

    </div>
    <div class="box-content-body">





        <div class="menu-interno">
            <?php if (permissao('categoria_produtos', 'adicionar', get_user())) { ?>
                <a class="btn btn-success " href="<?php echo base_url('index.php/produtos/adicionar_categoria') ?>">Cadastrar categoria</a>
            <?php } ?>
        </div>
        <br /><br />
        <?php

        function mostrar_categoria($categoria) {
            $cat = $db->where('ca_pai', $pai)->get('categorias_produtos')->result();
            if (count($cat)) {
                echo "<ul>";
                foreach ($cat as $c) {
                    echo "<li><a href='" . base_url('index.php/produtos/editar_categoria/' . $c->ca_id) . "'>{$c->ca_descricao}</a>";
                    ?>
                    <?php if (permissao('categoria_produtos', 'excluir', get_user())) { ?>
                        <a class='remover-icon' href='<?php echo base_url("index.php/produtos/removerCategoria?ex={$c->ca_id}"); ?>'>&nbsp;</a>
                    <?php } ?>
                    <?php
                    mostrar_categoria($db, $c->ca_id);
                    echo "</li>";
                }
                echo "</ul>";
            }
        }
        ?>

        <form name="formulario" id="menu-cat">
            <table class="table table-bordered">
                <thead>
                <th width="8%"></th>
                <th width="8%">Código</th>
                <th>Nome da Categoria</th>
                <th>Categorias Filhas</th>
                </thead>

                <?php if (count($categorias) == 0) { ?>
                    <tr>
                        <td colspan="4">  Nenhuma registro encontrado.</td>
                    </tr>
                <?php } else {
                    foreach ($categorias as $categoria) {
                        ?>
                        <tr>
                            <td>
                                <div class="btn-toolbar" style="margin: 0;">
                                    <div class="btn-group">
                                        <?php if (permissao('categoria_produtos', 'editar', get_user())) { ?>
                                            <button type="button" class="btn"  onclick="window.open('<?php echo base_url('index.php/produtos/editar_categoria/' . $categoria->ca_id) ?>', '_self');" >Editar</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn" >Ação</button>
                                        <?php } ?>
                                        <button style="padding: 8px;" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <?php if (permissao('categoria_produtos', 'excluir', get_user())) { ?>
                                                    <a  onclick="return confirm('Deseja excluir esse produto?');" href="<?php echo base_url("index.php/produtos/removerCategoria?ex={$categoria->ca_id}"); ?>" target="_self"><i class="icon-trash"></i> Remover esse produto</a>
                                                <?php } ?>
                                            </li>
                                        </ul>
                                    </div><!-- /btn-group -->
                                </div>
                            </td>
                            <td><?php echo $categoria->ca_id; ?></td>
                            <td><?php echo $categoria->ca_descricao; ?></td>
                            <td>
                                <ul class="unstyled">
                                    <?php $categoriasF = categoriaModel::getCategoriaFilhas($categoria->ca_id);
                                    foreach ($categoriasF as $categoriaF) {
                                        ?>
                                        <li><?php echo $categoriaF->ca_descricao; ?></li>
        <?php } ?>
                                </ul>
                            </td>
                        </tr>
                    <?php }
                }
                ?>
            </table>


        </form>

    </div>
</div>
