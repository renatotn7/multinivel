<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php

class CorrecaoPLEtapa1 extends CI_Controller {
    /*
     * ETAPA 1 DA CORREÇÃO PL
     * REGRAS:
     * DO DIA 27/02 AO DIA 07/03
     * DEVE PAGAR 14 REAIS, SE JA TIVER PAGO 2,30 OU 10,00 ATUALIZA O VALOR PARA QUEM SE QUALIFICOU NA NOVA REGRA
     * NOVA REGRA É: 
     *  - Ser qualificação 0 que é empreendedor
     *  - Estar na tabela de registro_distribuidor_binario
     * 
     * Quem já tiver recebido atualizar o valor
     */

    private $dataInicial;
    private $dataFinal;
    private $dataPeriodoAtivacao;
    private $limiteDiario;

    public function __construct() {
        parent::__construct();
        $this->dataInicial = date('Y-m-d', mktime(0, 0, 0, 02, 26, 2014));
        //HORA APROXIMADA QUE O NOVO SISTEMA ENTROU NO AR
        $this->dataFinal = date('Y-m-d H:i:s', mktime(23, 59, 59, 03, 14, 2014));
        //Data do periodo de ativação
        $this->dataPeriodoAtivacao = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 180, date('Y')));
        //PEGA O LIMITE DE GANHO DIARIO
        $limit = $this->db->where('field', 'valor_maximo_diario')->get('config')->row();
        $this->limiteDiario = (float) $limit->valor;
    }

    public function atualizaQualificacoes() {
        //TODOS DISTRIBUIDORES QUE TEM PLANO
        $distribuidores = $this->db
                        ->join('registro_planos_distribuidor', 'di_id = ps_distribuidor')
                        ->get('distribuidores')->result();

        //ATUALIZA QUALIFICAÇÃO DE 1 POR 1
        foreach ($distribuidores as $distribuidor) {
            $objQualificacoes = new QualificacaoModel();
            $objQualificacoes->setDistribuidor($distribuidor);
            $objQualificacoes->executar();
            $objQualificacoes->clear();
        }
        echo('OK');
    }

    public function atualizaBinario() {
        //TODOS DISTRIBUIDORES QUE TEM PLANO
        $distribuidores = $this->db
                        ->join('registro_planos_distribuidor', 'di_id = ps_distribuidor')
                        ->get('distribuidores')->result();

        //ATUALIZA BINARIO DE 1 POR 1
        foreach ($distribuidores as $distribuidor) {
            $objQualificacoes = new Binario($distribuidor);
        }
        echo('OK');
    }

    public function atualizaValorBonusPLErrados() {

        set_time_limit(0);

        //TODAS PLS PAGAS DE 27/02 A 07/03
        $recebeuBonusNoPeriodo = $this->db
                ->query('
                SELECT di_id,di_usuario,db_data,conta_bonus.* FROM distribuidores
                    JOIN conta_bonus ON cb_distribuidor = di_id
                    JOIN registro_ativacao ON at_distribuidor = di_id
                    JOIN registro_distribuidor_binario ON db_distribuidor = di_id
                WHERE cb_tipo = 106
                    AND di_binario = 1
                    AND di_qualificacao = 0
                    AND at_data >= "' . $this->dataPeriodoAtivacao . '"
                    AND cb_data_hora >= "' . $this->dataInicial . '"
                    AND cb_data_hora <= "' . $this->dataFinal . '"
                    AND cb_descricao != "Bônus PL <b>25/02/2014</b>"     
                   ORDER BY di_usuario, cb_data_hora ASC')
                ->result();


        $pagos = 0;
        $valorTotalPago = 0;
        //show_array($recebeuBonusNoPeriodo);exit;
        foreach ($recebeuBonusNoPeriodo as $bonusNoPeriodo) {

            $dataAtivacao = date('d/m/Y', strtotime($bonusNoPeriodo->db_data));
            $dataRecebimento = date('d/m/Y', strtotime($bonusNoPeriodo->cb_data_hora));

            //VERIFICA SE JÁ ERA BINARIO ANTES DA HORA DO BONUS
            if (date('Y-m-d 00:00:00', strtotime($bonusNoPeriodo->db_data)) <= $bonusNoPeriodo->cb_data_hora) {
                //VERIFICA SE NÃO E SABADO OU DOMINGO
                if (date('N', strtotime($bonusNoPeriodo->cb_data_hora)) != 6 && date('N', strtotime($bonusNoPeriodo->cb_data_hora)) != 7) {
                    //SE O VALOR FOR DIFERENTE DE 14 OU 30 REAIS
                    if ((double) $bonusNoPeriodo->cb_credito != 14.00 && (double) $bonusNoPeriodo->cb_credito != 30.00) {

                        $plano = DistribuidorDAO::getPlano($bonusNoPeriodo->di_id);

                        echo "<p><b>D:</b> {$bonusNoPeriodo->di_usuario} Data Plano: {$plano->ps_data} Plano: {$plano->pa_descricao}</p>";
                        $novoValor = 14;
                        if ($plano->pa_id == 104 && $plano->ps_data <= $bonusNoPeriodo->cb_data_hora) {
                            $novoValor = 30;
                        }

                        //ATUALIZA O VALOR DO BONUS PARA 14,00
                        
                          $this->db
                          ->where('cb_id', $bonusNoPeriodo->cb_id)
                          ->update('conta_bonus', array(
                          'cb_descricao' => $bonusNoPeriodo->cb_descricao . ' <b>CORRIGIDO EM: ' . date('d/m/Y H:i:s') . ' </b>',
                          'cb_credito' => $novoValor)
                          );

                          $this->db->insert('registro_bonus_pl_corrigido', array(
                          'rbpl_valor_old' => $bonusNoPeriodo->cb_credito,
                          'rbpl_valor_new' => $novoValor,
                          'rbpl_distribuidor' => $bonusNoPeriodo->cb_distribuidor,
                          'rbpl_id_conta_bonus' => $bonusNoPeriodo->cb_id,
                          )
                          );
                         
                        

                        $pagos++;
                        $valorTotalPago += $novoValor;

                        echo "<p>Alterou: valorAntigo: {$bonusNoPeriodo->cb_credito} <b>D:</b> {$bonusNoPeriodo->di_usuario} <b>DA:</b> {$dataAtivacao} <b>DR:</b> {$bonusNoPeriodo->cb_descricao} New: $novoValor</p>";
                    } else {
                        echo "<p style='color:#00F;'>NAO Alterou <b>D:</b> {$bonusNoPeriodo->di_usuario} <b>DR:</b> {$dataRecebimento}  Valor e R$ {$bonusNoPeriodo->cb_credito}</p>";
                    }
                } else {
                    echo "<p style='color:#e66;'>NAO Alterou FDS: <b>D:</b> {$bonusNoPeriodo->di_usuario} <b>DA:</b> {$dataAtivacao} <b>DR:</b> {$dataRecebimento} R$ {$bonusNoPeriodo->cb_credito}</p>";
                }
            } else {
                echo "<p style='color:#f00;'>NAO Alterou: <b>D:</b> {$bonusNoPeriodo->di_usuario} <b>DA:</b> {$dataAtivacao} <b>DR:</b> {$dataRecebimento} R$ {$bonusNoPeriodo->cb_credito}</p>";
            }
        }

        echo "<p>alterou {$pagos}</p>";
        echo "<p>alterou {$valorTotalPago}</p>";
    }

    public function pl_nao_paga_pq_era_qualificado() {
        set_time_limit(0);

        //TODAS PLS PAGAS DE 27/02 A 07/03
        $recebeuBonusNoPeriodo = $this->db
                ->query('
                SELECT di_id,di_cpf,di_usuario,db_data FROM distribuidores
                    JOIN registro_distribuidor_binario ON db_distribuidor = di_id
                WHERE
                    di_qualificacao = 0
                   ORDER BY di_usuario ASC')
                ->result();
        //show_array($recebeuBonusNoPeriodo,true);
        $dataCurrent = $this->dataInicial;
        $total = 0;
        
        while ($dataCurrent <= '2014-03-25') {
            foreach ($recebeuBonusNoPeriodo as $distribuidor) {
                $bonus = $this->db
                                ->where('cb_tipo', 106)
                                ->where('cb_distribuidor', $distribuidor->di_id)
                                ->like('cb_data_hora', date('Y-m-d', strtotime($dataCurrent)))
                                ->get('conta_bonus')->row();
                
                if ($distribuidor->db_data <= $dataCurrent) {
                    echo '<p>Dia: '.date('N', strtotime($dataCurrent)).'</p>';
                    if (date('N', strtotime($dataCurrent)) != 6 && date('N', strtotime($dataCurrent)) != 7) {

                        if (count($bonus) == 0) {
                            $total++;
                            $plano = DistribuidorDAO::getPlano($distribuidor->di_id);
                            
                            //Pagar PL registro_bonus_pl($valor, $percentual, $idDistribuidor, $cpf, $dataPl)
                            $this->registro_bonus_pl($plano->pa_pl,$this->getPercentual(),$distribuidor->di_id,$distribuidor->di_cpf,$dataCurrent);
                            
                            echo "<p style='color:#f00'>D: {$distribuidor->di_usuario} ID: {$distribuidor->di_id}  Ativacao: {$distribuidor->db_data} Data:  " . date('Y-m-d', strtotime($dataCurrent)) . "</p>";
                              
                        } else {
                            //echo "<p style='color:#006'>D: {$distribuidor->di_usuario} Data: " . date('d-m', strtotime($dataCurrent)) . "</p>";
                        }
                        
                    }else{
                        echo "<p>Data: ".date('d-m', strtotime($dataCurrent)) ." e FDS</p>";
                    }
                }
            }

            $t = strtotime($dataCurrent);
            $dataCurrent = date('Y-m-d H:i:s', mktime(date('H', $t), date('i', $t), date('s', $t), date('m', $t), date('d', $t) + 1, date('Y', $t)));
        }
        echo "<p>{$total}</p>";
    }
    
    public function getPercentual() {
    	//Percentual a pagar PL
    	return $this->getConfig('percentual_pl') / 100;
    }
    
    public function getConfig($field) {
    
    	//Despesas Operacionais dia anterior (5% do faturamento);
    	$config = $this->db->where('field', $field)->get('config')->row();
    
    	return isset($config->valor) ? $config->valor : false;
    }
    
    public function registro_bonus_pl($valor, $percentual, $idDistribuidor, $cpf, $dataPl) {

        $this->db->trans_begin();


        $valorBonusPl = LimiteGanho::paraCPF($cpf, $valor, $this->limiteDiario, $dataPl);


        //Montar a descrição do bônus
        if ($valor != $valorBonusPl) {
            $descricaoBonus = 'Bônus PL <b>' . date('d/m/Y', strtotime($dataPl)) . '</b><br>
			   Você atingiu o limite de <b>US$ ' . number_format($this->limiteDiario, 2, ',', '.') . '</b> de bonificação diária.';
        } else {
            $descricaoBonus = 'Bônus PL <b>' . date('d/m/Y', strtotime($dataPl)) . '</b>';
        }


        //Insere na tabela de conta bônus
        $dadosContaBonus = array(
            'cb_id' => NULL,
            'cb_distribuidor' => $idDistribuidor,
            'cb_compra' => 0,
            'cb_descricao' => $descricaoBonus,
            'cb_credito' => $valorBonusPl,
            'cb_debito' => 0,
            'cb_tipo' => 106,
            'cb_data_hora' => date('Y-m-d',strtotime($dataPl)) . " 23:59:59"
        );

        $this->db->insert('conta_bonus', $dadosContaBonus);

        $idBonus = $this->db->insert_id();

        if ($valor != $valorBonusPl) {

            $this->db->insert('registro_ganho_limite_diario', array(
                'gl_distribuidor' => $idDistribuidor,
                'gl_id_conta_bonus' => $idBonus,
                'gl_descricao' => "Ganhos de Bônus PL excedente.",
                'gl_valor' => ($valor - $valorBonusPl),
                'gl_tipo' => 106,
                'gl_data' => $dataPl . " 23:59:59"
            ));
        }


        //Valores a pagar	 
        //Insere na tabela de registro bonus pl
        $dadosBonusPl = array(
            'rbpl_id' => NULL,
            'rbpl_valor' => $valorBonusPl,
            'rbpl_distribuidor' => $idDistribuidor,
            'rbpl_percentual_pl' => $percentual,
            'rbpl_id_conta_bonus' => $idBonus,
            'rbpl_data' => date($this->dataFinal . " H:i:s"),
            'rbpl_data_fatura' => $dataPl,
            'rbpl_tipo' => 1
        );

        $this->db->insert('registro_bonus_pl', $dadosBonusPl);

        //Verifica se a transação foi executada com sucesso.
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $usuario = $this->db
                            ->select('di_usuario')
                            ->where('di_id', $idDistribuidor)
                            ->get('distribuidores')->row();

            echo('#### PAGOU DISTRIBUIDOR: ' . $usuario->di_usuario . ' ####<br> VALOR: ' . $valorBonusPl . '');
            $this->db->trans_commit();
        }
    }

    /*
      public function deleta_duplicado() {
      $dataAtual = '2014-03-07';
      $distribuidores = $this->db
      ->select('di_usuario,di_id')
      ->where('ps_plano', 104)
      ->join('registro_planos_distribuidor', 'di_id = ps_distribuidor')
      ->get('distribuidores')->result();


      while ($dataAtual < date('Y-m-d')) {

      foreach ($distribuidores as $distribuidor) {

      $plNova = $this->db
      ->where('cb_descricao', 'Bônus PL ' . date('d/m/Y', strtotime($dataAtual)))
      ->where('cb_tipo', 106)
      ->where('cb_distribuidor', $distribuidor->di_id)
      ->get('conta_bonus')->row();

      $plAntiga = $this->db
      ->where('cb_descricao', 'Bônus PL <b>' . date('d/m/Y', strtotime($dataAtual)) . '</b>')
      ->where('cb_tipo', 106)
      ->where('cb_distribuidor', $distribuidor->di_id)
      ->get('conta_bonus')->row();

      $qtd = 2;
      if (count($plAntiga) > 0 && count($plNova) > 0) {
      //show_array($plAntiga);
      //show_array($plNova);
      echo "<p>Distribuidor: {$distribuidor->di_usuario} : {$distribuidor->di_id}  Data: {$dataAtual} Quantidade: " . $qtd . "</p>";
      $this->db
      ->where('cb_id',$plAntiga->cb_id)
      ->delete('conta_bonus');
      }
      }

      $dataAtual = date('Y-m-d', strtotime('+1 days', strtotime($dataAtual)));
      }
      }
     * 
     */
}
