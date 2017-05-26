<div class="box-content min-height">
    <div class="box-content-header">Produto Por País</div>
    <div class="box-content-body">
        <form name="form_pais_produto" action="<?php echo base_url('index.php/produto_pais/add_combo_pais/'); ?>" method="post" >
            <div class="row">
                <div class="span3">
                    <label for="prv_id_produto">
                        <strong>Produtos</strong>
                    </label>
                    <select name="prv_id_produto" id="prv_id_produto">
                        <option value="">--Selecione Produto--</option>
                        <?php foreach (produtoModel::getProduto() as $produto) { ?>
                            <option value="<?php echo $produto->pr_id; ?>"><?php echo $produto->pr_codigo . " - " . $produto->pr_nome; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="span3">
                    <label for="prv_id_pais">
                        <strong>País</strong>
                    </label>
                    <select name="prv_id_pais" id="prv_id_pais">
                        <option value="">--Selecione País--</option>
                        <?php foreach (paisModel::getPais() as $pais) { ?>
                            <option value="<?php echo $pais->ps_id; ?>"><?php echo $pais->ps_nome; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="span3">
                    <label for="prv_valor">
                        <strong>Valor</strong>
                    </label>
                    <input type="text" name="prv_valor" id="prv_valor" class="moeda span2" value="">
                </div>
                <br>
                <button class="btn btn-primary" type="submit" >Salvar</button>
            </div>
        </form>
        <table class="table table-bordered">
            <thead>
            <th width="8%"></th>
            <th>Código ISO3</th>
            <th>País</th>
            <th>Produto</th>
            <th>Valor</th>
            </thead>
            <?php foreach ($produtos as $produto) { ?>
                <tr>
                    <td>
                        <div class="btn-group">
                            <button class="btn">Ação</button>
                            <button class="btn dropdown-toggle"  style="padding: 8px;" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo base_url('index.php/produto_pais/index?ident=' . $produto->prv_id); ?>"><i class="icon-pencil"></i> Editar</a></li>
                                <li><a href="<?php echo base_url('/index.php/produto_pais/remover_combo_pais?ident=' . $produto->prv_id); ?>"><i class="icon-trash"></i> Remover</a></li>
                            </ul>
                        </div>
                    </td>
                    <td><?php echo $produto->ps_iso3; ?></td>
                    <td><img src="<?php echo base_url('public/imagem/flags/'.$produto->ps_sigla.'.png');?>"/> - <?php echo $produto->ps_nome; ?></td>
                    <td><?php echo $produto->pr_nome; ?></td>
                    <td><?php echo number_format($produto->prv_valor, 2); ?></td>
                </tr>
            <?php } ?>
            <?php if (count($produtos) == 0) { ?>
                <tr>
                    <td colspan="5" style="text-align: center">Nenhum registro encontrado</td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>