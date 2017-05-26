<div class="box-content min-height">
    <div class="box-content-header">Planos</div>
    <div class="box-content-body">
        <form action="<?php echo base_url('index.php/planos/salvar'); ?>" method="post">
            <ul id="myTab" class="nav nav-tabs">
                <?php foreach ($planos as $key => $plano) { ?>
                    <li <?php echo $key == 0 ? 'class="active"' : ''; ?>><a href="#<?php echo $key; ?>" data-toggle="tab"><?php echo $plano->pa_descricao; ?></a></li>
                <?php } ?>
            </ul>

            <div id="myTabContent" class="tab-content">
                <?php foreach ($planos as $key => $plano) { ?>
                    <input type="hidden" name="pa_id[]" value="<?php echo $plano->pa_id; ?>"/>
                    <div class="tab-pane fade in <?php echo $key == 0 ? 'active' : ''; ?> " id="<?php echo $key ?>">
                        <div class="row">
                            <div class="span6">
                                Nome do Plano: 
                                <input type="text" name="pa_descricao_<?php echo $key; ?>" value="<?php echo $plano->pa_descricao; ?>" class="span6 " />
                            </div>
                        </div>
                        <div class="row">
                            <div class="span3">
                                Valor Plano: 
                                <input type="text" name="pa_valor_<?php echo $key; ?>" value="<?php echo $plano->pa_valor; ?>"
                                       class="span3 moeda" />
                            </div>
                            <div class="span3">
                                Indicação Direta (U$):
                                <input type="text"
                                       name="pa_indicacao_direta_<?php echo $key; ?>" value="<?php echo $plano->pa_indicacao_direta; ?>" class="span3 moeda" />
                            </div>
                            <div class="span3">
                                Indicação Indireta (U$): 
                                <input type="text"
                                       name="pa_indicacao_indireta_<?php echo $key; ?>" value="<?php echo $plano->pa_indicacao_indireta; ?>" class="span3 moeda" />
                            </div>
                            <div class="span3">
                                PL (U$): 
                                <input type="text" name="pa_pl_<?php echo $key; ?>" value="<?php echo $plano->pa_pl; ?>" class="span3" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="span3">
                                Avanço de Titulo (U$): 
                                <input type="text" name="pa_avanco_titulo_<?php echo $key; ?>"
                                       value="<?php echo $plano->pa_avanco_titulo; ?>" class="span3" />
                            </div>
                            <div class="span3">
                                Binário (%): 
                                <input type="text" name="pa_binario_<?php echo $key; ?>" value="<?php echo $plano->pa_binario; ?>"
                                       class="span3" />
                            </div>
                            <div class="span3">
                                Bônus Liderança: 
                                <input type="text" name="pa_bonus_lideranca_<?php echo $key; ?>"
                                       value="<?php echo $plano->pa_bonus_lideranca; ?>" class="span3" />
                            </div>
                            <div class="span3">
                                Total: <input type="text" name="pa_total_<?php echo $key; ?>" value="<?php echo $plano->pa_total; ?>" class="span3" />
                            </div>

                        </div>
                        <div class="row">
                            <div class="span3">
                                Pontos:
                                <input type="text" name="pa_pontos_<?php echo $key; ?>" value="<?php echo $plano->pa_pontos; ?>" class="span3" />
                            </div>
                            <div class="span3">
                                Quantidade Níveis:
                                <input type="text" name="pa_qtd_niveis_<?php echo $key; ?>" value="<?php echo $plano->pa_qtd_niveis; ?>" class="span3" />
                            </div>
                            <div class="span3">
                                Produto:
                                <input type="text" name="pa_produto_<?php echo $key; ?>" value="<?php echo $plano->pa_produto; ?>" class="span3" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="span3">
                                Bônus UniLevel Valor (U$):
                                <input type="text" name="pa_bonus_unilevel_valor_<?php echo $key; ?>" value="<?php echo $plano->pa_bonus_unilevel_valor; ?>" class="span3 moeda" />
                            </div>
                            <div class="span3">
                                Bônus UniLevel Gerações:
                                <input type="text" name="pa_bonus_unilevel_geracoes_<?php echo $key; ?>" value="<?php echo $plano->pa_bonus_unilevel_geracoes; ?>" class="span3" />
                            </div>

                            <div class="span3">
                                Taxa de manutenção Pagamento ATM (empresaePay) (U$):
                                <input type="text" name="pa_taxa_manutencao_<?php echo $key; ?>" value="<?php echo $plano->pa_taxa_manutencao; ?>" class="span3 moeda" />
                            </div>
                            <div class="span3">
                                Link Bônus: 
                                <input type="text" name="pa_link_bonus_<?php echo $key; ?>" value="<?php echo $plano->pa_link_bonus; ?>" class="span3" />
                            </div>
                            <div class="span3">
                                Valor Euro(€): 
                                <input type="text" name="pa_valor_euro_<?php echo $key; ?>" value="<?php echo $plano->pa_valor_euro; ?>" class="span3" />
                            </div>

                        </div>
                        <div class="row">
                            <div class="span3">
                                Número de Código No derramamento:
                                <input type="text" name="pa_numero_token_derramamento_<?php echo $key; ?>" value="<?php echo $plano->pa_numero_token_derramamento; ?>" class="span3" />
                            </div>
                            <div class="span3">
                                Número de Código da Ativação do Binário:
                                <input type="text" name="pa_numero_token_ativacao_binario_<?php echo $key; ?>" value="<?php echo $plano->pa_numero_token_ativacao_binario; ?>" class="span3" />
                            </div>
                        </div>

                    </div>
                <?php } ?>

            </div>

            <button class="btn" type="submit">Salvar</button>
        </form>
        <formulário de upgrade-->
        <form name="form-upgrade" id="form-upgrad" action="<?php echo base_url('index.php/planos/upgrade'); ?>" method="post">
            <h3>Tabela de Upgrade</h3>
            <hr>

            <table class="table table-bordered table-hover">
                <tr>
                    <th>Nº</th>
                    <th>Plano</th>
                    <th>Plano Upgrade</th>
                    <th>Valor US$ do Upgrade</th>
                    <th>Pontos Upgrade</th>
                    <th>Produto</th>
                </tr>
                <?php
                $upgrades = $this->db
                                ->join('planos', 'pa_id=pug_id_plano')
                                ->get('planos_upgrades')->result();
                if (count($upgrades) > 0) {
                    foreach ($upgrades as $key => $upgrade) {
                        $plano_up = $this->db->where('pa_id', $upgrade->pug_id_plano_upgrade)
                                        ->get('planos')->row();
                        ?>
                        <tr>
                            <td><?php echo $upgrade->pug_id; ?></td>
                            <td><?php echo $upgrade->pa_descricao; ?></td>
                            <td><?php echo $plano_up->pa_descricao; ?></td>
                            <td>
                                <input type="hidden" name="pug_id[]"
                                       value="<?php echo $upgrade->pug_id; ?>"/>
                                <input type="text" name="pug_valor_<?php echo $upgrade->pug_id; ?>" id="pug_valor" 
                                       value="<?php echo $upgrade->pug_valor; ?>"/>
                            </td>
                            <td>
                                <input type="text" name="pug_pontos_<?php echo $upgrade->pug_id; ?>" id="pug_valor" 
                                       value="<?php echo $upgrade->pug_pontos; ?>"/>
                            </td>
                            <td>
                                <select name="pug_produto_<?php echo $upgrade->pug_id; ?>">
                                    <option value="" >--Selecione--</option>
                                    <?php foreach (produtoModel::getProdutoCategoria(9) as $key => $produto_value) { ?>
                                        <option <?php echo $produto_value->pr_id == $upgrade->pug_produto ? 'selected' : ''; ?> value="<?php echo $produto_value->pr_id; ?>" >
                                            <?php echo $produto_value->pr_nome; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>   
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="4" style="text-align: center">sem registro</td>
                    </tr>  
                <?php } ?>                 
            </table>
            <button class="btn" type="submit">Salvar</button>
        </form>
    </div>
</div>