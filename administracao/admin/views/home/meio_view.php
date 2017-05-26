<div class="box-content min-height">
    <div class="box-content-header">Página Inicial</div>
    <div class="box-content-body">

        <?php
        if (permissao('info_home', 'visualizar', get_user())) {
            /* Faturamento do dia Anterior */

            //Dia anterior do faturamento
            $data = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
            $anterior = date('Y-m-d', $data);

            //Total Faturamento dia anterior 
            $faturamentoDiaAnterior = $this->db->query("
	SELECT COALESCE(SUM( co_total_valor ), 0.00) AS total
	FROM `compras`	
	WHERE `co_pago_industria` = 0
	AND `co_pago` = 1
	AND DATE_FORMAT( co_data_compra, '%Y-%m-%d' ) = '" . $anterior . "'
  ")->row();

            /* End */
            /* Total Despesas com produtos(tabela custo_produto) */
            /* End */
            /* Total Lingotes Enviados(num) */

            $lingotes = $this->db
                            ->join('produtos_comprados', 'pm_id_compra = co_id')
                            ->select('SUM(pm_quantidade) as quantidade')
                            ->where('pm_tipo', 1)
                            ->where('co_pago', 1)
                            ->get('compras')->row();

            $totalLingotes = $lingotes->quantidade + 0;

            /* End */

            /* Reserva Financeira(10% do faturamento) */
            $reserva = $this->db->where('field', 'reserva_financeira')->get('config')->row();
            $reservaFinaceira = $faturamentoDiaAnterior->total * $reserva->valor;
            /* End */

            /* Despesas Operacionais(5% do faturamento) */
            $despesaOp = $this->db->where('field', 'despesas_operacionais')->get('config')->row();
            $despesaOperacional = $faturamentoDiaAnterior->total * $despesaOp->valor;
            /* End */

            /* Despesas Qualificações(5% do faturamento) */
            $despesaQ = $this->db->where('field', 'despesas_qualificacao')->get('config')->row();
            $despesaQualificacao = $faturamentoDiaAnterior->total * $despesaQ->valor;
            /* End */

            /* Despesas Publicidade(5% do faturamento) */
            $despesaP = $this->db->where('field', 'despesas_publicidade')->get('config')->row();
            $despesaPublicidade = $faturamentoDiaAnterior->total * $despesaP->valor;
            /* End */

            /* Despesas com Bônus(Discriminadas) */

            /* End */

            /* Custo Financeiro(6% do faturamento) */
            $custoF = $this->db->where('field', 'custo_financeiro')->get('config')->row();
            $custoFinanceiro = $faturamentoDiaAnterior->total * $custoF->valor;
            /* End */


            $faturamento = $this->db
                            ->select('SUM(co_total_valor) as total')
                            ->where('co_pago_industria', 0)
                            ->where('co_pago', 1)
                            ->get('compras')->row();

            $faturamento_boleto = $this->db
                            ->select('SUM(co_total_valor) as total')
                            ->where('co_pago_industria', 0)
                            ->where('co_forma_pgt', 1)
                            ->where('co_pago', 1)
                            ->get('compras')->row();

            $faturamento_deposito = $this->db
                            ->select('SUM(co_total_valor) as total')
                            ->where('co_pago_industria', 0)
                            ->where('co_forma_pgt', 2)
                            ->where('co_pago', 1)
                            ->get('compras')->row();

            $faturamento_atm = $this->db
                            ->select('SUM(co_total_valor) as total')
                            ->where('co_pago_industria', 0)
                            ->where('co_forma_pgt', 12)
                            ->where('co_pago', 1)
                            ->get('compras')->row();

            $faturamento_bonus = $this->db
                            ->select('SUM(co_total_valor) as total')
                            ->where('co_pago_industria', 0)
                            ->where('co_forma_pgt', 3)
                            ->where('co_pago', 1)
                            ->get('compras')->row();

            $planos_vendido = $this->db
                            ->select(array('co_id_plano', 'pa_descricao', 'COUNT(co_id_plano) as total'))
                            ->join('planos', 'pa_id=co_id_plano')
                            ->where('co_pago_industria', 0)
                            ->where('co_eplano', 1)
                            ->where('co_pago', 1)
                            ->group_by('co_id_plano')
                            ->order_by('COUNT(co_id_plano)', 'DESC')
                            ->get('compras', 3)->result();

            $produto_mais_vendidos = $this->db
                            ->select(array('SUM(pm_quantidade) as qtd', 'pr_nome'))
                            ->join('produtos_comprados', 'co_id=pm_id_compra')
                            ->join('produtos', 'pr_id=pm_id_produto')
                            ->where('co_pago', 1)
                            ->group_by('pm_id_produto')
                            ->order_by('SUM(pm_id_produto)', 'DESC')
                            ->get('compras', 5)->result();

            $bonus = $this->db->query("
 SELECT SUM(cb_credito) as total FROM conta_bonus
 WHERE cb_tipo IN(SELECT tb_id FROM bonus_tipo)
")->row();

            $top_5 = $this->db
                            ->select(array('di_id', 'di_nome', 'di_usuario', 'SUM(cb_credito) as total'))
                            ->join('distribuidores', 'cb_distribuidor=di_id')
                            ->group_by('cb_distribuidor')
                            ->order_by('SUM(cb_credito)', 'DESC')
                            ->where_in('cb_tipo', array(1, 2, 105,106,107,238,237,236))
                            ->get('conta_bonus', 30)->result();

            $meses = array();
            $meses[] = date('Y-m-', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
            $meses[] = date('Y-m-', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y')));
            $meses[] = date('Y-m-', mktime(0, 0, 0, date('m') + 2, date('d'), date('Y')));
            $meses[] = date('Y-m-', mktime(0, 0, 0, date('m') + 3, date('d'), date('Y')));
            ?>

            <table width="100%" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="23%"><strong>Faturamento</strong></td>
                    <td width="77%">

                        <table width="400px" class="no-table" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>Faturamento Geral</td>
                                <td>US$ <?php echo number_format($faturamento->total, 2, ',', '.') ?></td>
                            </tr>

                            <tr>
                                <td>Boleto</td>
                                <td>US$ <?php echo number_format($faturamento_boleto->total, 2, ',', '.') ?></td>
                            </tr>

                            <tr>
                                <td>ATM</td>
                                <td>US$ <?php echo number_format($faturamento_atm->total, 2, ',', '.') ?></td>
                            </tr>

                            <tr>
                                <td>Depósito/Dinheiro</td>
                                <td>US$ <?php echo number_format($faturamento_deposito->total, 2, ',', '.') ?></td>
                            </tr> 

                            <tr>
                                <td>Bônus</td>
                                <td>US$ <?php echo number_format($faturamento_bonus->total, 2, ',', '.') ?></td>
                            </tr>
                            <tr><td height="2px"></td></tr>
                             
                            <tr><td height="2px"></td></tr>
                        </table>


                    </td>
                </tr>

                <tr>
                    <td><strong>Valor do Bônus em Geral</strong></td>
                    <td>US$ <?php echo number_format($bonus->total, 2, ',', '.') ?></td>
                </tr>

                <tr>
                    <td><strong>Planos vendidos</strong></td>
                    <td>
                        <?php foreach ($planos_vendido as $k => $pl) { ?>
                            <?php echo $k + 1 ?>º - <?php echo $pl->pa_descricao ?> (<?php echo $pl->total ?> vendidos)<br />
                        <?php } ?>
                    </td>
                </tr>


                <tr>
                    <td><strong>Produto Mais Vendido</strong></td>
                    <td>
                        <?php foreach ($produto_mais_vendidos as $k => $pr) { ?>

                            <div><?php echo ($k + 1) . 'ª - ' . $pr->pr_nome ?>(<?php echo $pr->qtd ?> vendidos)</div>
                        <?php } ?>
                    </td>
                </tr> 


                <tr>
                    <td><strong>Top 10 Melhores Resultados</strong></td>
                    <td>
                        <?php foreach ($top_5 as $k => $a) { ?>
                            <div><?php echo ($k + 1) ?> - <?php echo $a->di_nome . '(' . $a->di_usuario . ')' ?> - US$ <?php echo number_format($a->total, 2, ',', '.') ?></div>
                        <?php } ?>
                    </td>
                </tr>  

            </table>

            <?php $this->load->view('home/grafico_geral_view') ?>

            <style>
                .no-table td{
                    padding:2px;
                    border:none;
                }
            </style>

        <?php } ?>

    </div>
</div>