<div class="box-content min-height">
    <div class="box-content-header">Relátorio Financeiro</div>
    <div class="box-content-body">

        <?php
                $de  = date('01/m/Y');
                $ate = date('d/m/Y');
                        
                if(isset($_REQUEST['de'])){			 
		 $de = get_parameter('de');
                }
                
               if(isset($_REQUEST['ate'])){	
	         $ate = get_parameter('ate');
                }

         $bonusGerado->total = (count($bonusGerado) >0 ?$bonusGerado->total:0) - (isset($bonusEstornado->total)?$bonusEstornado->total:0);

        //Lucro Líquido
        $despesas = $despesaOperacional + $despesaQualificacao + $despesaPublicidade + $despesaProduto + $bonusGerado->total+$custoFinanceiro;


        $BonusGeradosDescriminado = $this->db->query("
           SELECT SUM(cb_credito) as valor,cb_tipo FROM conta_bonus		   
           WHERE cb_tipo IN(SELECT tb_id FROM bonus_tipo)
		   AND cb_data_hora >= '" . data_to_usa($de). " 00:00:00'
		   AND cb_data_hora <= '" .data_to_usa($ate). " 23:59:59'
		   GROUP BY cb_tipo
		   "
                )->result();
        
        $totalLiquido = $totalFaturamento - $despesas  - $reservaFinaceira;
        ?>

        <form name="formulario" action="<?php echo base_url('index.php/relatorio_financeiro/relatorio') ?>" method="get">

            De: <input type="text" style="width:90px; margin:0;" class="mdata date-filtro" value="<?php echo $de; ?>" name="de" />
            Até: <input type="text" style="width:90px; margin:0;" class="mdata date-filtro" value="<?php echo $ate; ?>" name="ate" />

            <input type="submit" class="btn btn-primary" value="Buscar">
        </form>


        <table width="80%" class="table table-hover table-bordered" border="0" cellspacing="0" cellpadding="0">
            <thead>

                <tr>
                    <th width="20%">Faturamento</th>
                    <td width="20%">US$ <?php echo number_format($totalFaturamento, 2, ',', '.') ?></td>
                    <td style="border:none !important;"></td>
                </tr>
                <tr>
                    <th width="20%">Despesas com Bônus</th>
                    <td width="20%">US$ <?php echo number_format($bonusGerado->total, 2, ',', '.') ?></td>
                    <td style="border-left:none !important;"></td>
                </tr> 

                <tr>
                    <th width="20%" valign="top">Bônus Gerados:</th>
                    <td colspan="2">
                        <?php
                        $totalB = 0;
                        foreach ($BonusGeradosDescriminado as $bonus) {
                            $BonusDesc = $this->db->where('tb_id', $bonus->cb_tipo)->get('bonus_tipo')->row();
                            ?>
                            <div> <strong><?php echo $BonusDesc->tb_descricao ?></strong>: US$ <?php echo number_format($bonus->valor, 2, ',', '.') ?></div> 
                        <?php } ?>
                    </td>
                </tr>   


                <tr>
                    <th width="20%">Despesas Produto</th>
                    <td width="20%">US$ <?php echo number_format($despesaProduto, 2, ',', '.') ?></td>
                    <td style="border-left:none !important;"></td>
                </tr> 
                <tr> 
                    <th width="20%">Despesas Operacionais</th>
                    <td width="20%">US$ <?php echo number_format($despesaOperacional, 2, ',', '.') ?></td>
                    <td style="border-left:none !important;"></td>
                </tr>
                <tr>
                    <th width="8%">Despesas Qualificações</th>
                    <td width="20%">US$ <?php echo number_format($despesaQualificacao, 2, ',', '.') ?></td>
                    <td style="border-left:none !important;"></td>
                </tr>
                <tr>
                    <th width="20%">Despesas Publicidade</th>
                    <td width="20%">US$ <?php echo number_format($despesaPublicidade, 2, ',', '.') ?></td>
                    <td style="border-left:none !important;"></td>
                </tr>
                <tr>
                    <th width="20%">Custo Financeiro</th>
                    <td width="20%">US$ <?php echo number_format($custoFinanceiro, 2, ',', '.') ?></td>
                    <td style="border-left:none !important;"></td>
                </tr>
                <tr>
                    <th width="20%">Reserva Financeira</th>
                    <td width="20%">US$ <?php echo number_format($reservaFinaceira, 2, ',', '.') ?></td>
                    <td style="border-left:none !important;"></td>
                </tr>
                <tr>
                    <th width="20%">Total de Agência Ativas</th>
                    <td width="20%"><?php echo $lingotes ?></td>
                    <td style="border-left:none !important;"></td>
                </tr>

                <tr>
                    <th width="20%">Lucro Líquido Total</th>
                    <td width="20%"><strong style="font-size:16px;">US$ <?php echo number_format($totalLiquido, 2, ',', '.') ?></strong></td>
                    <td style="border-left:none !important;"></td>
                </tr>
            </thead>

        </table>



    </div>
</div>
