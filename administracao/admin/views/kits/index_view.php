<div class="box-content min-height">
    <div class="box-content-header">
        <a href="<?php echo base_url('index.php/kits') ?>">Kits</a>
    </div>
    <div class="box-content-body">
        <form action="<?php echo base_url('index.php/kits/addKits'); ?>" name="form-kits" method="post">
            <div class="row">
                <div class="span4">
                    <label for="pr_nome">Nome do kit</label>
                    <input type="text" name="pr_nome" id="pr_nome" class="span4" value=""/>
                </div>
                <div class="span2">
                    <label for="pr_valor">Valor do kit</label>
                    <input type="text" name="pr_valor" id="pr_valor" class="span2 moeda" value=""/>
                </div>
                <br>
                <div class="span4">
                    <button type="submit" class="btn btn-primary">Criar kit</button>
                </div>
            </div>
        </form>
        <table class="talbe-hover table-bordered table">
            <thead>
            <th width="3%"></th>
            <th width="2%">Código</th>
            <th width="15%">Nome</th>
            <th width="25%">Produto</th>
            <th width="3%">Valor</th>
            </thead>
            <?php
            if (count($kits) > 0) {
                foreach ($kits as $kit) {
                    ?>
                    <tr>
                        <td>
                            <div class="btn-toolbar" style="margin: 0;">
                                <div class="btn-group">
                                    <button type="button" class="btn addProduto"  rel="<?php echo $kit->pr_id; ?>"><i class="icon-plus-sign"></i> Add </button>
                                    <button style="padding: 8px;" class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <?php if (permissao('produtos', 'editar', get_user())) { ?>
                                                <a href="<?php echo base_url('index.php/kits/editarkits/' . $kit->pr_id) ?>" target="_self"><i class="icon-pencil"></i> Editar</a>
                                            <?php } ?>
                                        </li>
                                        <li>
                                            <?php if (permissao('produtos', 'excluir', get_user())) { ?>
                                                <a onclick="return confirm('Deseja excluir esse kit?');" href="<?php echo base_url('index.php/kits/removerkits/' . $kit->pr_id); ?>" target="_self"><i class="icon-trash"></i> Remover esse produto</a>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div><!-- /btn-group -->
                            </div>
                        </td>
                        <td><?php echo $kit->pr_id; ?></td>
                        <td><?php echo $kit->pr_nome; ?></td>
                        <td>
                            <ul>
                                <?php
                                $produtos = kitModel::getProdutoskits($kit->pr_id);
                                if (count($produtos) > 0) {
                                    $preco = 0.00;
                                    foreach ($produtos as $produto) {
                                        $preco+=$produto->pr_valor * $produto->pc_quantidade;
                                        ?>
                                        <li><?php echo $produto->pr_codigo . ' - ' . $produto->pr_nome . " - US$: " . $produto->pr_valor; ?> X <?php echo $produto->pc_quantidade ?> 
                                            -  <a href="<?php echo base_url('/index.php/kits/removerProdutokits?indeCombo=' . $produto->pc_id.'&indeKit='.$kit->pr_id); ?>" class="remover-icon">.</a> </li>
                                        <?php
                                    }
                                } else {
                                    $preco = 0.00;
                                    ?>
                                    <li>Kit sem produto.</li>
                                <?php } ?>
                            </ul>
                        </td>
                        <td>US$:<?php echo number_format($preco,2); ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5" style="text-align: center">Nenhum registro encontrado</td>
                </tr>
            <?php } ?>
        </table>

    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.addProduto', function() {
        $('#pn_id-modal').val($(this).attr('rel'));
        $('#myModal').modal('show');
    });

</script>
<!--modal da escolha do produtos-->
<div id="myModal" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Escola de produtos</h3>
    </div>
    <form name="produtos-form" action="<?php echo base_url('index.php/kits/addProdutopKits'); ?>" method="post">
        <div class="modal-body">

            <input type="hidden" name="pr_id_kit" id="pn_id-modal" value=""/>
            <table class="table">
                <tr>
                    <th></th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                </tr>
                <?php
                $produtos_ = combopacoteModel::getProdutos();
                foreach ($produtos_ as $p) {
                    if(!empty($p->pr_kit)){
                        continue;
                    }
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="pr_id[]" id="pr_id-<?php echo $p->pr_id; ?>" value="<?php echo $p->pr_id; ?>"/>
                        </td>
                        <td>
                            <label for="pr_id-<?php echo $p->pr_id; ?>">
                                <?php echo $p->pr_nome; ?>
                            </label>
                        </td>
                        <td>
                            <?php
                            $categoria = categoriaModel::getCategorias($p->pr_categoria);
                            echo count($categoria) > 0 ? $categoria->ca_descricao : 'Nenhuma categoria';
                            ?>
                        </td>
                        <td>
                            <input type="text" name="pc_quantidade_<?php echo $p->pr_id; ?>" id="pc_quantidade" class="span1" value="1"/>
                        </td>
                        <td><?php echo $p->pr_valor; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="modal-footer">
            <a href="<?php echo base_url('/index.php/combopacotes/'); ?>" class="btn">Cancela</a>
            <button class="btn btn-primary" type="submit" ><i class=" icon-plus-sign  icon-white"></i> Adicionar Produtos </button>
        </div>
    </form>
</div>