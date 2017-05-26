<?php

class Bonus_ciclo extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Verificar o Ciclo(primeiro ciclo tem bonus especificos)
     * Pagar indicação de acordo com o ciclo
     * @autor Natanael
     */

    function executar_primeiro_ciclo() {

        //Distribuidores qualificados a receber bonus de ciclo
        $distribuidores = $this->db
                        //APENAS BINARIOS ATIVOS
                        ->join('registro_distribuidor_binario', 'db_distribuidor = di_id')
                        //APENAS ATIVOS
                        ->join('registro_ativacao', 'at_distribuidor = di_id')
                        //BUSCA CICLOS
                        ->join('ciclos', 'cl_distribuidor = di_id')
                        //QUE FECHAM CICLO NESTE DIA
                        ->where('cl_data_fim >=', date('Y-m-d'))
                        ->where('cl_data_inicio <=', date('Y-m-d'))
                        ->where('cl_ciclo', 1)
                        ->get('distribuidores')->result();

        foreach ($distribuidores as $distribuidor) {
          
           
                //BUSCA AS INDICAÇÕES DO DISTRIBUIDOR
                $indicacoesNaoPagas = $this->db->query("
									SELECT *
									FROM `distribuidores`
									JOIN `distribuidor_ligacao` ON `li_id_distribuidor` = `di_id`
									JOIN `registro_ativacao` ON `at_distribuidor` = `di_id`
									WHERE 	di_ni_patrocinador = " . $distribuidor->di_id . "
										AND li_no = " . $distribuidor->di_id . "
										AND di_id <> " . $distribuidor->di_id . "
										AND at_data >= '" . $distribuidor->cl_data_inicio . "'
										AND at_data <= '" . $distribuidor->cl_data_fim . "'
										AND di_id NOT IN (
											SELECT `rc_indicado` FROM registro_bonus_ciclo_pagos
												WHERE `rc_indicador` = " . $distribuidor->di_id . "
										)
									")->result();

                //SE HOUVER ACIMA DE 4 INDICAÇÕES NA QUELE CICLO
                if (count($indicacoesNaoPagas) >= 4) {
                    /*
                      GERA UM REGISTRO PARA CADA INDICAÇÃO NA TABELA
                      POIS ASSIM SABE QUAIS INDICAÇÕES JÁ FORAM PAGAS
                     */

                    //INICIA TRANSAÇÃO
                    $this->db->trans_start();

                    //VERIFICA A QUANTIDADE DE PAGAMENTO DE BONUS A SER FEITAS
                    $qtdQuatroIndicacoes = intval(count($indicacoesNaoPagas) / 4);

                    $qtdExataIndicacoes = $qtdQuatroIndicacoes * 4;
                    /*
                      PEGA A QUANTIDADE DE INDICAÇÕES EXATAS QUE DEVEM SER GRAVADAS
                      EVITA QUE EM CASO DE TER INDICADO 7 PESSOAS GRAVAR AS 7 COMO PAGO
                      IRA GRAVAR APENAS 4 AS OUTRAS 3 FICARAM PARA O PROXIMO CICLO
                     */
                    for ($j = 0; $j < $qtdExataIndicacoes; $j++) {
                        $indicacaoPagar = $indicacoesNaoPagas[$j];
                        $this->registra_bonus_ciclo_pago($distribuidor->di_id, $indicacaoPagar->di_id, $distribuidor->at_data, $indicacaoPagar->at_data, date('Y-m-d'));
                    }

                    /*
                      PAGA O BONUS A QUANTIDADE DE VEZES QUE O DISTRIBUIDOR INDICOU 4 PESSOAS

                      EXEMPLO INDICOU 8 PAGA 2 BONUS, INDICOU 16 PAGA 4 BONUS
                     */
                    for ($i = 1; $i <= $qtdQuatroIndicacoes; $i++) {
                        $this->pagar_bonus_ciclo($distribuidor, $i);
                    }

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }
                }
            }
        
    }



    public function registra_bonus_ciclo_pago($diIndicador, $diIndicado, $dataInicioCiclo, $dataIndicadoAtivouSe, $dataFimCiclo) {
        $this->db->insert('registro_bonus_ciclo_pagos', array(
            'rc_indicador' => $diIndicador,
            'rc_indicado' => $diIndicado,
            'rc_data_inicio_ciclo' => $dataInicioCiclo,
            'rc_data_indicado_ativou_se' => $dataIndicadoAtivouSe,
            'rc_data_fim_ciclo' => $dataFimCiclo
        ));
    }

    public function pagar_bonus_ciclo($dadosDistribuidor, $qtdQuatroIndicacoes) {
        $valorBonus = 130.00;

        //INICIO TRANSACAO
        $this->db->trans_start();

        //INDICOU APENAS 4 PESSOAS NÃO RECEBE BARRA DE OURO SO BONUS
        if ($qtdQuatroIndicacoes == 1) {
            //Creditando a conta
            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => $dadosDistribuidor->di_id,
                'cb_descricao' => 'Bônus de Ciclo completado com 4 indicações diretas',
                'cb_credito' => $valorBonus,
                'cb_tipo' => 105 //Bônus Ciclagem
            ));
        }

        //INDICOU MAIS QUE 4 PESSOAS GANHA BONUS + BARRA DE OURO
        if ($qtdQuatroIndicacoes > 1) {
            $quantidadeIndicacoes = ($qtdQuatroIndicacoes * 4);
            //Creditando a conta
            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => $dadosDistribuidor->di_id,
                'cb_descricao' => 'Bônus de Ciclo completado com ' . $quantidadeIndicacoes . ' indicações diretas',
                'cb_credito' => $valorBonus,
                'cb_tipo' => 105 //Bônus Ciclagem
            ));

            //PEGA OS DADOS DA BARRA DE OURO
            $dadosBarraOuro = $this->db->limit(1)->get('produtos')->row();

            //GERA COMPRA DA BARRA DE OURO
            $dadosCompra = array(
                'co_tipo ' => 1,
                'co_entrega ' => 1,
                'co_entrega_uf ' => $dadosDistribuidor->di_uf,
                'co_entrega_cidade ' => $dadosDistribuidor->di_cidade,
                'co_entrega_bairro ' => $dadosDistribuidor->di_bairro,
                'co_entrega_cep ' => $dadosDistribuidor->di_cep,
                'co_entrega_complemento ' => $dadosDistribuidor->di_complemento,
                'co_entrega_numero ' => $dadosDistribuidor->di_numero,
                'co_entrega_logradouro ' => $dadosDistribuidor->di_endereco,
                'co_total_valor ' => ($dadosBarraOuro->pr_valor - $dadosBarraOuro->pr_desconto_distribuidor),
                'co_frete_gratis ' => 0,
                'co_peso_total ' => $dadosBarraOuro->pr_peso,
                'co_id_distribuidor ' => $dadosDistribuidor->di_id,
                'co_id_comprou ' => $dadosDistribuidor->di_id,
                'co_situacao ' => 6, //aguardando envio
                'co_pago ' => 1, //compra já paga
                'co_forma_pgt ' => 7, //cortesia
                'co_forma_pgt_txt ' => 'Cortesia',
                'co_data_compra' => date('Y-m-d'),
                'co_data_insert ' => date('Y-m-d')
            );

            $this->db->insert('compras', $dadosCompra);
            $idCompra = $this->db->insert_id();

            $this->db->insert('produtos_comprados', array(
                'pm_id_compra' => $idCompra,
                'pm_id_produto' => $dadosBarraOuro->pr_id,
                'pm_quantidade' => 1,
                'pm_pontos' => $dadosBarraOuro->pr_pontos,
                'pm_valor' => ($dadosBarraOuro->pr_id - $dadosBarraOuro->pr_desconto_distribuidor),
                'pm_valor_total' => ($dadosBarraOuro->pr_id - $dadosBarraOuro->pr_desconto_distribuidor),
                'pm_tipo' => 1
            ));
            //FINAL IF QUANTIDADE INDICAÇÕES MAIOR QUE 4
        }
        //FINAL TRANSACAO
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

}

?>