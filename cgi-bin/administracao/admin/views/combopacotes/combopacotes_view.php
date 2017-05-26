<div class="box-content min-height">
    <div class="box-content-header">Combos e Pacotes</div>
    <div class="box-content-body">
        <form name="pacotes" id="pacotes" action="<?php echo $this->input->get('ident') ? base_url('index.php/combopacotes/editarCombo?ident=' . $this->input->get('ident')) : base_url('index.php/combopacotes/addCombo'); ?>"  method="post">

            <div class="row">
                <div class="span1">
                    <label for="pn_codigo"><strong>Código:</strong></label>
                    <input class="span1" type="text" name="pn_codigo" id='pn_codigo' value="<?php echo isset($combo->pn_codigo) ? $combo->pn_codigo : ''; ?>">
                </div>
                <div class="span2">
                    <label for="pn_descricao"><strong>Combo/Pacotes:</strong></label>
                    <input type="text" name="pn_descricao" id='pn_descricao' class="span2" value="<?php echo isset($combo->pn_descricao) ? $combo->pn_descricao : ''; ?>">
                </div>
                <div class="span2">
                    <label for="pn_valor"><strong>Desconto Combo:</strong></label>
                    <input type="text" name="pn_valor" id='pn_valor' class="span2 moeda" value="<?php echo isset($combo->pn_valor) ? $combo->pn_valor : ''; ?>">
                </div>
                <div class="span2">
                    <label for="pn_plano"><strong>Plano:</strong></label>

                    <select  class="span2" id="pn_plano" name="pn_plano" >
                        <option value="" >Selecione</option>
                        <?php foreach ($planos as $plano) { ?>
                            <option value="<?php echo $plano->pa_id; ?>" <?php echo isset($combo->pa_id) ? $combo->pa_id == $plano->pa_id ? 'selected' : '' : ''; ?> ><?php echo $plano->pa_descricao; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="span3">
                    <br/>
                    <button class="btn btn-success" type="submit"><?php echo $this->input->get('ident') ? "Atualizar Combo/Pacotes" : "Adcionar Combo/Pacotes"; ?></button>
                </div>
            </div>
        </form>
        <table width="100%" class="table table-bordered table-hover">
            <thead>
            <th width="8%"></th>
            <th>Código</th>
            <th>Combo/Pacotes</th>
            <th>Produtos</th>
            <th>Subtotal</th>
            <th>Desconto</th>
            <th>total</th>
            </thead>
            <?php
            if (count($comboPacotes) > 0) {
                $preco = 0;
                foreach ($comboPacotes as $comboPacote) {
                    ?>

                    <tr>
                        <td>
                            <div class="btn-group">
                                <button class="btn addProduto" rel="<?php echo $comboPacote->pn_id; ?> "><i class=" icon-plus-sign"></i>Add</button>
                                <button class="btn dropdown-toggle"  style="padding: 8px;" data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo base_url('/index.php/combopacotes/index?ident=' . $comboPacote->pn_id); ?>"><i class="icon-pencil"></i> Editar</a></li>
                                    <li><a href="<?php echo base_url('/index.php/combopacotes/removerCombo?ident=' . $comboPacote->pn_id); ?>"><i class="icon-trash"></i> Remover Combo/Produto</a></li>
                                </ul>
                            </div>
                        </td>
                        <td><?php echo $comboPacote->pn_codigo ?></td>
                        <td><?php echo $comboPacote->pn_descricao ?></td>
                        <td>
                            <ul>
                                <?php
                                $produtos = combopacoteModel::getProdutosCombo($comboPacote->pn_id);
                                if (count($produtos) > 0) {
                                    $preco = 0.00;
                                    foreach ($produtos as $produto) {
                                        $preco+=$produto->pr_valor;
                                        ?>
                                        <li><?php echo $produto->pr_codigo . ' - ' . $produto->pr_nome . " - US$: " . $produto->pr_valor; ?> 
                                            -  <a href="<?php echo base_url('/index.php/combopacotes/removerProdutoCombo?indeCombo=' . $produto->cbp_id); ?>" class="remover-icon">.</a></li>
                                    <?php }
                                } else {
                                    $preco = 0.00;
                                    ?>
                                    <li>Combo sem produto.</li>
                                <?php } ?>
                            </ul>
                        </td>
                        <td>
                            <?php echo "US$: " . number_format(($preco), 2); ?>
                        </td>
                        <td>
                            <?php echo "US$: " . number_format($comboPacote->pn_valor, 2); ?>
                        </td>
                        <td>
                            <?php echo "US$: " . number_format(($preco - $comboPacote->pn_valor), 2); ?>
                        </td>

                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="7" style="text-align: center">Nenhum Registro Encontrado.</td>
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
    <form name="produtos-form" action="<?php echo base_url('index.php/combopacotes/addprodutocombo'); ?>" method="post">
        <div class="modal-body">

            <input type="hidden" name="pn_id" id="pn_id-modal" value=""/>
            <table class="table">
                <tr>
                    <th></th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Valor</th>
                </tr>
                <?php
                $produtos_ = combopacoteModel::getProdutos();
                foreach ($produtos_ as $p) {
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