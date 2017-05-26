<?php

/**
 * Script para Pagamento da PL
 * REGRA NOVA OU REGRA ANTIGA.
 */
class bonusPL {

    private $db;
    private $timeDia;
    private $data_pagar_pl;
    private static $distribuidor_instance;
    private $distribuidor_set;
    private $distribuidor_apt;
    private $ponto_direita;
    private $ponto_esquerda;
    private $instanceVerificacaoBonusPl;

    public function __construct($distribuidor = null, $data = '') {
        $this->db = get_instance()->db;
        $this->instanceVerificacaoBonusPl = new VerificacaoBonusPl();
        $this->set_data_pl($data);
        $this->set_data_GAMBIARRA($data);

         $this->run($distribuidor);
    }

     public function set_data_GAMBIARRA($data)
    {
        $this->data = $data;
    }
    public function get_data_GAMBIARRA()
    {
        return $this->data;
    }

    /**
     * singleton para carregar os distribuidores.
     * @param type $idDistribuidorOrUsuario tanto o id ou usuário.
     * @return type
     */
    public function getDistribuidorInstance($idDistribuidorOrUsuario = 0) {

        $dataPL = $this->data;
        
        $dezesseisDiasAtras = date('Y-m-d', strtotime('-15 days', strtotime($dataPL)));

        // $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 6, date('d'), date('Y')));
        //$dezesseisDiasAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 16, date('Y')));
        
                if (!isset(self::$distribuidor_instance)) {
                    //Carrega distribuidor pelo id do
                    if (!empty($idDistribuidorOrUsuario) && is_numeric($idDistribuidorOrUsuario)) {
                        get_instance()->db->where('di_id', $idDistribuidorOrUsuario);
                    }
                    //Carrega distribuidor pelo usuário
                    if (!empty($idDistribuidorOrUsuario) && is_string($idDistribuidorOrUsuario)) {
        
                        get_instance()->db->where('di_usuario', $idDistribuidorOrUsuario);
                    }
        
                    self::$distribuidor_instance = get_instance()->db
                        ->where('di_excluido', 0)
                        ->where('co_pago', 1)
                        // ->where_in('di_qualificacao', array(0))
                        // ->where("at_data >='{$seisMesesAtras}'")
                        // ->where("di_data_cad <='{$dezesseisDiasAtras}'")
                        ->where("at_data <='{$dezesseisDiasAtras}'")
                        ->group_by('di_id')
                        ->join('compras', 'co_id_distribuidor=di_id')
                        ->join('planos', 'co_id_plano=pa_id')
                        ->join('registro_ativacao', 'at_distribuidor=di_id', 'left')
                        ->get('distribuidores')->result();
        
                }
          
        return self::$distribuidor_instance;
    }

    private function regra_universidade($distribuidor = array()) {
        if (count($distribuidor) == 0) {
            return FALSE;
        }

        if ($distribuidor->pa_id == 99) {
            return false;
        }

        $receber_pl_universidade = $this->db->where('apt_semana', date('W') - 1)
                        ->where('apt_ano', date("Y"))
                        ->where('apt_id_distribuidor', $distribuidor->di_id)
                        ->where('apt_status', 0)
                        ->where('apt_apto_receber_pl', 1)
                        ->get('aptos_receber_pl')->row();

        if (count($receber_pl_universidade) == 0) {
            return false;
        }

        return true;
    }

    /**
     * Filta todos os distribuidores que iram receber a pl
     * e coloca no array o distribuidor e valores a ser pagos
     */
    public function run($distribuidor = array()) {
        @header('Content-Type: text/html; charset=utf-8');
        $di_id = 0;
        if (count($distribuidor)) {
            $di_id = $distribuidor->di_id;
        }

        //echo "<pre>";
        //Verificando regra antiga para distribuidores antigos
        foreach (self::getDistribuidorInstance($di_id)as $distribuidor) {

            try {

                $plano = $this->getPlano($distribuidor);

                echo "Usuário: $distribuidor->di_usuario - Data: " . $this->data_pl() . "\n";
                echo "Plano Atual: $distribuidor->pa_descricao\n";

                //Ativando ou desativando a regra da universidade.
                if (ConfigSingleton::getValue('ativar_regra_universidade') == 0) {

                    //Regra da universidade.
                    if (!$this->regra_universidade($distribuidor)) {
                        echo "Não recebeu a PL Porque não foi aprovado na universidade\n";
                        continue;
                    }
                }

                // if (count($plano) == 0) {
                //     echo ("Usuário não conseguiu se qualificar em nenhum plano até o momento.\n");
                // }

                // echo "Plano Qualificado: " . PlanosModel::getPlanoDistribuidor($distribuidor->di_id)->pa_descricao . "\n";

                //Saldo do usuário não pode ficar negativo.
                if (!SaldoVirtual::getSaldo($distribuidor->di_id) < 0) {
                    throw new Exception("Usuário ta com saldo negativo.\n");
                }

                //Usuário já recebeu a PL da data do pagamento
                if (!$this->pagarBonus($distribuidor)) {
                    throw new Exception("Usuário já recebeu a Pl referente a data: " . $this->getData_pagar_pl() . "\n");
                }

                $distribuidor_apto = array();
                //Plano que o distribuidor ta qualificado
                //Verifica se ta com o binário ativo ou fez
                // if (AtivacaoBinario::binarioAtivo($distribuidor, $this->data_pl()) || $this->is_upgrade($distribuidor)) {

                    //Pega os distribuidores aptos de acordo com a nova regra.
                    $distribuidor_apto = $this->regra_nova($distribuidor, $plano);
                // } else {
                //     echo "Receber a PL na regra antiga.\n";
                //     $distribuidor_apto = $this->regra_antiga($distribuidor);
                // }

                //Verifica se o distribuidor ta apto.
                if (count($distribuidor_apto) == 0) {
                    throw new Exception("Usuário não está apto a receber a PL.\n");
                }

                //Verificando a data do pagamento se é final de semana e se o usuario tem direito de receber final de semana.
                if ($distribuidor_apto->nao_recebe_fds) {
                    //Final de semana (Sabado).
                    if (date("N", strtotime($this->data_pl())) == 6) {
                        continue;
                    }
                    //Final de semana (Domingo).
                    if (date("N", strtotime($this->data_pl())) == 7) {
                        continue;
                    }
                }

                //Se tiver apt a receber a PL então inicia o processo de pagamento.
                $this->pagar($distribuidor_apto);
            } catch (Exception $exc) {
                echo $exc->getMessage() . "\n";
            }
            echo "------------------------------------------------------\n";
        }
    }

    /**
     * Retorna a menor perna do distribuidor
     * @param unknown $distribuidor
     */
    public function perna_penor($distribuidor) {
        $data_pl = $this->data_pl();
        $sql_ponto_esquerda = "SELECT SQL_CACHE SUM(co_total_pontos) pontos
                         FROM   `distribuidor_ligacao`
                                   JOIN distribuidores
                                   ON     di_id = `li_id_distribuidor`
                                   JOIN compras
                                   ON     di_id       = `co_id_distribuidor`
                        WHERE  `li_no`            = {$distribuidor->di_esquerda}
                        AND    co_pago            = 1
                        AND    co_eupgrade       != 1
                        AND   co_data_compra  <= '{$data_pl} 23:59:59'
                        AND    di_ni_patrocinador = {$distribuidor->di_id}";

        $this->ponto_esquerda = $this->db->query($sql_ponto_esquerda)->row();
        $this->ponto_esquerda = (int) $this->ponto_esquerda->pontos;

        $sql_ponto_direita = "SELECT SQL_CACHE SUM(co_total_pontos) pontos
                            FROM   `distribuidor_ligacao`
                                   JOIN distribuidores
                                   ON     di_id = `li_id_distribuidor`
                                   JOIN compras
                                   ON     di_id       = `co_id_distribuidor`
                            WHERE  `li_no`            = {$distribuidor->di_direita}
                            AND    co_pago            = 1
                            AND    co_eupgrade       != 1
                            AND   co_data_compra  <= '{$data_pl} 23:59:59'
                            AND    di_ni_patrocinador = {$distribuidor->di_id}";

        $this->ponto_direita = $this->db->query($sql_ponto_direita)->row();
        $this->ponto_direita = (int) $this->ponto_direita->pontos;

        //Retorna a menor perna.
        if ($this->ponto_direita < $this->ponto_esquerda) {
            return $this->ponto_direita;
        } else {
            return $this->ponto_esquerda;
        }
    }

    /**
     * Nova regra da pl
     * @param type $distribuidor
     * @return \stdClass|ArrayObject
     */
    public function regra_nova($distribuidor, $plano) {

        if (count($plano) == 0) {
            return $this->regra_antiga($distribuidor);
        }

        $valor_pl = $plano->pa_pl;
        if (empty($valor_pl)) {
            return array();
        }

        $descricao_pl = '';
        $valor_bonus_pl = 0;
        $limit_diario = false;
        $valor_bonus_pl = LimiteGanho::paraCPF($distribuidor->di_cpf, number_format($valor_pl, 2, '.', ''), $this->getConfig('valor_maximo_diario'), $this->data_pl());

        //Descricao da PL.
        $descricao_pl = 'Bônus Estimulo <b>' . date('d/m/Y', strtotime($this->data_pl())) . '</b>';

        //Montar a descrição do bônus
        if (number_format($valor_pl, 2, '.', '') != $valor_bonus_pl) {
            $descricao_pl = 'Bônus Estimulo <b>' .
                    date('d/m/Y', strtotime($this->data_pl())) . '</b><br>
                        Você atingiu o limite de <b>US$ ' .
                    number_format($this->getConfig('valor_maximo_diario'), 2, ',', '.')
                    . '</b> de bonificação diária.';
            $limit_diario = true;
        }

        return funcoesdb::arrayToObject(array(
                    'di_id' => $distribuidor->di_id,
                    'valor_pl' => $valor_bonus_pl,
                    'descricao_pl' => $descricao_pl,
                    'limit_diario' => $limit_diario,
                    'nao_recebe_fds' => true
        ));
    }

    /**
     * Regra antiga da pl
     * @param type $distribuidor
     * @return \stdClass|ArrayObject
     */
    public function regra_antiga($distribuidor) {

        $limit_diario = false;
        $valor_bonus_pl = LimiteGanho::paraCPF($distribuidor->di_cpf, $this->getConfig('valor_pl_hoje'), $this->getConfig('valor_maximo_diario'), $this->data_pl());

        //Descricao do bonus
        $descricao_pl = 'Bônus Estimulo <b>' . date('d/m/Y', strtotime($this->data_pl())) . '</b>';

        //Montar a descrição do bônus
        if ($this->getConfig('valor_pl_hoje') != $valor_bonus_pl) {
            $descricao_pl = 'Bônus Estimulo <b>' . date('d/m/Y', strtotime($this->data_pl())) . '</b><br>
			   Você atingiu o limite de <b>US$ ' .
                    number_format($this->getConfig('valor_maximo_diario'), 2, ',', '.') . '</b> de bonificação diária.';

            $limit_diario = true;
        }

        //Verifica se o cadastro é antigo e não fez upgrade.
        if (!$this->cadastro_antigo($distribuidor)) {
            return array();
        }

        return funcoesdb::arrayToObject(array(
                    'di_id' => $distribuidor->di_id,
                    'valor_pl' => str_replace(',', '.', $valor_bonus_pl),
                    'descricao_pl' => $descricao_pl,
                    'limit_diario' => $limit_diario,
                    'nao_recebe_fds' => false
        ));
    }

    /**
     * Paga o bônus pl
     * @param type $dados_pl
     * @return boolean
     */
    public function pagar($dados_pl) {

        //Verifica se e diferente de 0
        //Pagando a pl
        //Insere na tabela de conta bônus
        $dadosContaBonus = array(
            'cb_id' => NULL,
            'cb_distribuidor' => $dados_pl->di_id,
            'cb_compra' => 0,
            'cb_descricao' => $dados_pl->descricao_pl,
            'cb_credito' => $dados_pl->valor_pl,
            'cb_debito' => 0,
            'cb_tipo' => 106,
            'cb_data_hora' => $this->getData_pagar_pl() . " 23:59:59"
        );

        $this->db->insert('conta_bonus', $dadosContaBonus);

        $idBonus = $this->db->insert_id();

        if ($dados_pl->limit_diario) {

            $this->db->insert('registro_ganho_limite_diario', array(
                'gl_distribuidor' => $dados_pl->di_id,
                'gl_id_conta_bonus' => $idBonus,
                'gl_descricao' => "Ganhos de Bônus PL excedente.",
                'gl_valor' => ($valor - $dados_pl->valor_pl),
                'gl_tipo' => 106,
                'gl_data' => $this->getData_pagar_pl() . " 23:59:59"
            ));
        }
        //Valores a pagar
        //Insere na tabela de registro bonus pl
        $dadosBonusPl = array(
            'rbpl_id' => NULL,
            'rbpl_valor' => $dados_pl->valor_pl,
            'rbpl_distribuidor' => $dados_pl->di_id,
            'rbpl_percentual_pl' => ($dados_pl->valor_pl / 100),
            'rbpl_id_conta_bonus' => $idBonus,
            'rbpl_data_fatura' => $this->getData_pagar_pl() . " 23:59:59",
            'rbpl_tipo' => 1
        );

        $this->db->insert('registro_bonus_pl', $dadosBonusPl);
        echo "Pagou a PL data:" . $this->getData_pagar_pl() . " Valor:{$dados_pl->valor_pl} \n";
//            echo "<h4> valor da pl :{$dados_pl->valor_pl} data:{" . $this->data_pl() . "} data do registro:" . $this->getData_pagar_pl() . "</h4>";
    }

    public function set_data_pl($data = '') {
       
               if (empty($data)) {
                   $timeDia = strtotime(date('Y-m-d'));
                   $this->timeDia = mktime(0, 0, 0, date('m', $timeDia), date('d', $timeDia) - 1, date('Y', $timeDia));
                   $this->data_pagar_pl = strtotime(date('Y-m-d'));
               } else {
                   $this->data_pagar_pl = strtotime($data);
                   $this->timeDia = strtotime($data);
               }
      

      
    }

    public function getData_pagar_pl() {
        return date('Y-m-d', $this->data_pagar_pl);
    }

    public function data_pl() {
        return date('Y-m-d', $this->timeDia);
    }

    public function getConfig($field) {
        //Despesas Operacionais dia anterior (5% do faturamento);
        $config = $this->db->where('field', $field)
                        ->get('config')->row();
        return isset($config->valor) ? $config->valor : false;
    }

    /**
     *
     * @param type $distribuidor
     * @return \boolean
     */
    public function pagarBonus($distribuidor) {
        //Recebeu bônus PL
        $paga = $this->plFoiPaga($this->data_pl(), $distribuidor->di_id);

        return $paga == false ? true : false;
    }

    private function cadastro_antigo($distribuidor) {
        $antigo = $this->db->query("select compras.* from distribuidores
		join compras on co_id_distribuidor = di_id
		where
		co_eplano=1 and co_pago=1
		and  di_id = {$distribuidor->di_id}
		and co_data_compra <='2014-03-07';
		")->row();

        return count($antigo) > 0 ? true : false;
    }

    /**
     * Verifica se o usuário fez upgrade
     * @param objeto $distribuidor
     * @return \stdClass
     */
    private function is_upgrade($distribuidor) {
        $upgrade = $this->db->query("SELECT   IF(COUNT(co_id_plano)>1,
                                             true,false) AS upgrade,
					     MAX(co_id_plano)  AS plano
				             FROM     compras
                                             WHERE    co_pago           = 1
                                             AND      co_eplano         = 1
					     AND      co_id_distribuidor=
                                            {$distribuidor->di_id}
                                             ORDER BY co_id_plano ASC")->row();

        return $upgrade->upgrade;
    }

    /**
     * Retorna o plano que o usuário ta qualificado.
     * @param objeto $distribuidor
     * @return \stdClas|ArrayObject
     */
    public function getPlano($distribuidor) {
        $planos = get_instance()->db->where('ps_distribuidor', $distribuidor->di_id)
                ->join('planos', 'pa_id=ps_plano')
                ->order_by('ps_plano', 'DESC')
                ->get('registro_planos_distribuidor')
                ->result();

        // $perna_menor = $this->perna_penor($distribuidor);
        foreach ($planos as $plano) {
        //     if ($perna_menor >= $plano->pa_pontos) {
                return $plano;
            // }
        }
        return array();
    }

    public function getProvavelDiaFaturado($pl) {

        $data = $this->conteudoTagB($pl->cb_descricao);

        $dataValida = $this->dataValida($data);
        if ($dataValida) {
            return $dataValida;
        }

        return date('Y-m-d', strtotime($pl->cb_data_hora));
    }

    public function getRegistrosPl($idDistribuidor = null) {
        if ($idDistribuidor == null) {
            $this->db->where('cb_distribuidor', $this->distribuidor->di_id);
        } else {
            $this->db->where('cb_distribuidor', $idDistribuidor);
        }
        return $this->db
                        ->where('cb_data_hora >=', '2014-02-22')
                        ->where('cb_tipo', 106)
                        ->get('conta_bonus')->result();
    }

    public function plFoiPaga($data, $idDistribuidor) {
        $pls = $this->getRegistrosPl($idDistribuidor);
        foreach ($pls as $pl) {
            if ($this->getProvavelDiaFaturado($pl) == $data) {
                return $pl;
            }
        }
        return false;
    }

    public function conteudoTagB($html) {
        $ent = $html;
        if (preg_match('/(\d{1,2}\/\d{1,2}\/\d{4})/i', $html, $result)) {
            return $result[1];
        }
        if (preg_match("{<b>}", $ent)) {
            $a = explode("<b>", $ent);
            if (preg_match("{</b>}", $a[1])) {
                $b = explode("</b>", $a[1]);
                return $b[0];
            }
        } else {
            return trim(str_ireplace('Bônus PL ', '', $ent));
        }
    }

    public function dataValida($data) {
        list($d, $m, $y) = @explode('/', $data);
        if (checkdate($m, $d, $y)) {
            return $y . '-' . $m . '-' . $d;
        }
        return false;
    }

}
