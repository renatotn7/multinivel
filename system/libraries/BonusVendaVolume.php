<?php

/**
 * BÔNUS VENDA INDIRETA E DIRETA
 *  Indireta = bonus Binário.
 * @author objeto
 *
 */
class BonusVendaVolume {

    private $db;
    private $valorVendasVolume;
    private $info_dis_bonus_volume;
    private $limiteDiario;

    function __construct() {
        $ci = & get_instance();
        $this->db = $ci->db;
        $this->valorVendasVolume = 0;
        $limit = $this->db->where('field', 'valor_maximo_diario')->get('config')->row();
        $this->limiteDiario = (float) $limit->valor;
    }

    public function pagar($compra) {

        $distribuidor = $this->db
                        ->select(array('di_id', 'di_usuario', 'di_ni_patrocinador', 'di_ativo', 'di_cpf'))
                        ->where('di_id', $compra->co_id_distribuidor)
                        ->get('distribuidores')->row();

        $this->info_dis_bonus_volume = $distribuidor;


        $planoComprado = $this->db->where('pa_id', $compra->co_id_plano)->get('planos')->row();

        //Pegando o patrocinador.
        $patrocinador = $this->db->where('di_id', $distribuidor->di_ni_patrocinador)->get('distribuidores')->row();


        if (count($patrocinador) > 0 && count($planoComprado) > 0) {

            //verificando se distribuidor e brasileiro.
            $ebrasileiro = $this->db->where('es_id', $patrocinador->di_uf)->get('estados')->row();

            $valorBonus = $planoComprado->pa_indicacao_direta;


            $cpfPatrociandor = $this->db->select('di_id,di_cpf')
                            ->where('di_id', $distribuidor->di_ni_patrocinador)
                            ->get('distribuidores')->row();

            //Limite de ganho
            $valorBonusIndicacao = LimiteGanho::paraCPF($cpfPatrociandor->di_cpf, $valorBonus, $this->limiteDiario);


            //Montar a  d do bônus
            if ($valorBonus != $valorBonusIndicacao) {
                $descicaoBonus = 'Bônus Indicação <b>' . $distribuidor->di_usuario . '</b><br>
			   Você atingiu o limite de <b>US$ ' . number_format($this->limiteDiario, 2, ',', '.') . '</b> de bonificação diária.
			  ';
            } else {
                $descicaoBonus = 'Bônus Indicação <b>' . $distribuidor->di_usuario . '</b>';
            }

            //VERIFICA SE NAO VAI PAGAR BONUS REPETIDO
            $registroPagamento = $this->db
                    ->where('rb_indicador', $distribuidor->di_ni_patrocinador)
                    ->where('rb_indicado', $distribuidor->di_id)
                    ->get('registro_bonus_indicacao_pagos')
                    ->row();


            //SE NAO HOUVER PAGAMENTO DAQUELA INDICAÇÃO
            if (count($registroPagamento) == 0) {



                //Se for brasileiro verifica se a documentação ta paga.
                if (!BonusPerdido::receberBonus($ebrasileiro->es_pais, $patrocinador->di_conta_verificada, $distribuidor->di_ni_patrocinador)) {

                    //Inserindo bônus de indicação para patrocinador
                    $this->db->insert('conta_bonus_perdido', array(
                        'cb_distribuidor' => $distribuidor->di_ni_patrocinador,
                        'cb_descricao' => $descicaoBonus,
                        'cb_credito' => $valorBonusIndicacao,
                        'cb_tipo' => 1
                    ));
                } else {

                    //Inserindo bônus de indicação para patrocinador
                    $this->db->insert('conta_bonus', array(
                        'cb_distribuidor' => $distribuidor->di_ni_patrocinador,
                        'cb_descricao' => $descicaoBonus,
                        'cb_credito' => $valorBonusIndicacao,
                        'cb_tipo' => 1
                    ));


                    $idContaBonus = $this->db->insert_id();
                    if ($valorBonus != $valorBonusIndicacao) {

                        $this->db->insert('registro_ganho_limite_diario', array(
                            'gl_distribuidor' => $distribuidor->di_ni_patrocinador,
                            'gl_id_conta_bonus' => $idContaBonus,
                            'gl_descricao' => "Ganhos de Bônus Indicação excedente.",
                            'gl_valor' => ($valorBonus - $valorBonusIndicacao),
                            'gl_tipo' => 1,
                            'gl_data' => date('Y-m-d H:i:s')
                        ));
                    }

                    $this->db->insert('registro_bonus_indicacao_pagos', array(
                        'rb_indicador' => $distribuidor->di_ni_patrocinador,
                        'rb_indicado' => $distribuidor->di_id,
                        'rb_valor' => $valorBonus
                    ));
                }//Fim - else que paga o bônus
            }//Fim - verifica se já foi pago 


            /**
             * 
             * Inicio do pagamento do Bônus de Indicação Indireta
             * 
             */
            $dist = $this->db
                            ->select(array('di_id', 'di_usuario', 'di_ni_patrocinador', 'di_ativo'))
                            ->where('di_id', $distribuidor->di_ni_patrocinador)
                            ->get('distribuidores')->row();

            $maximoGeracoes = $this->db->select('max(pa_qtd_niveis) as geracao_maxima')->get('planos')->row();
            $geracaoMaxima = isset($maximoGeracoes->geracao_maxima) ? $maximoGeracoes->geracao_maxima : 0;

            //echo "<div>Pagar no maximo {$geracaoMaxima} niveis</div>";
            $this->pagar_bonus_venda_volume($dist, 1, false, $geracaoMaxima, $planoComprado);
        }
    }

    private function pagar_bonus_venda_volume($dis, $linha, $chegou_no1, $qtdNiveis, $planoComprado) {



        if ($linha <= $qtdNiveis) {

            ##-- Obtenho o dado do sistribuidor
            $dis = $this->db
                            ->join('estados', 'di_uf = es_id')
                            ->where('di_id', $dis->di_ni_patrocinador)
                            ->get('distribuidores')->row();





            if (count($dis) > 0) {
                //echo "<h2>Pagando {$dis->di_usuario}:{$dis->di_id} na {$linha} geracao </h2>";

                $planoAtual = DistribuidorDAO::getPlano($dis->di_id);


                $geracaoMaximaDistribuidor = $planoAtual->pa_qtd_niveis;
                //echo "<div>Tem plano {$planoAtual->pa_descricao} com ganhos ate {$geracaoMaximaDistribuidor} geracao</div>";
                if ($linha <= $geracaoMaximaDistribuidor) {


                    #-- Valor que deve ser depositado
                    $valor_pagar = $planoComprado->pa_indicacao_indireta;

                    ##-- Verifica se vai inderir (?) "inserir ou aderir" o crédito para um distribuidor ou para a industria;
                    if ($chegou_no1 === false) {

                        $valorBonusVolumeVenda = LimiteGanho::paraCPF($dis->di_cpf, $valor_pagar, $this->limiteDiario);

                        //echo "<div>Valor do bonus {$valor_pagar} | valor a receber {$valorBonusVolumeVenda}</div>";

                        //Montar a descrição do bônus
                        if ($valor_pagar != $valorBonusVolumeVenda) {
                            $descicaoBonus = 'Bônus Residual <b>' . $this->info_dis_bonus_volume->di_usuario . '</b><br>
						   Você atingiu o limite de <b>US$ ' . number_format($this->limiteDiario, 2, ',', '.') . '</b> de bonificação diária.
						  ';
                        } else {
                            $descicaoBonus = 'Bônus Residual <b>' . $this->info_dis_bonus_volume->di_usuario . '</b>';
                        }

                        if ($valorBonusVolumeVenda > 0) {

                            //SE FOR BRASILEIRO E NÃO TIVER CONTA VERIFICADA
                            if (!BonusPerdido::receberBonus($dis->es_pais, $dis->di_conta_verificada, $dis->di_id)) {
                                //SALVA REGISTRO DO BONUS PERDIDO	  
                                //echo "<div>Bonus Perdido</div>";

                                $this->db->insert('conta_bonus_perdido', array(
                                    'cb_distribuidor' => $dis->di_id,
                                    'cb_descricao' => $descicaoBonus,
                                    'cb_credito' => $valorBonusVolumeVenda,
                                    'cb_debito' => 0,
                                    'cb_tipo' => 107
                                ));
                            } else {
                                //echo "<div>Pagando o bonus</div>";
                                ##-- Inserir Bônus para o distribuidor 		  
                                $this->db->insert('conta_bonus', array(
                                    'cb_distribuidor' => $dis->di_id,
                                    'cb_descricao' => $descicaoBonus,
                                    'cb_credito' => $valorBonusVolumeVenda,
                                    'cb_debito' => 0,
                                    'cb_tipo' => 107
                                ));

                                $idContaBonus = $this->db->insert_id();
                                if ($valor_pagar != $valorBonusVolumeVenda) {
                                    //echo "<div>Pagando o bonus com limite diario</div>";
                                    $this->db->insert('registro_ganho_limite_diario', array(
                                        'gl_distribuidor' => $dis->di_id,
                                        'gl_id_conta_bonus' => $idContaBonus,
                                        'gl_descricao' => "Ganhos de bônus volume venda excedente.",
                                        'gl_valor' => ($valor_pagar - $valorBonusVolumeVenda),
                                        'gl_tipo' => 107,
                                        'gl_data' => date('Y-m-d H:i:s')
                                    ));

                                    $idContaBonus = $this->db->insert_id();
                                }
                            }

                            ##-- Inserir registro do pagamento de bonus
                            $this->db->insert("bonus_venda_volume_pagos", array(
                                'bp_distribuidor' => $this->info_dis_bonus_volume->di_id,
                                'bp_distribuidor_recebeu' => $dis->di_id,
                                'bp_posicao' => $linha,
                                'bp_data' => date('Y-m-d')
                            ));
                        }
                    }
                    if ($dis->di_id == 1) {
                        $chegou_no1 = true;
                    }
                    $linha++;
                    self::pagar_bonus_venda_volume($dis, $linha, $chegou_no1, $qtdNiveis, $planoComprado);
                }//Fim verifica distribuidor existe
            }
        }
    }

    private function percentual($valor, $percentual) {
        return $valor * $percentual / 100;
    }

    public static function getRegistroBonusPago($idRecebedor, $usuarioComprador) {
        return get_instance()->db
                        ->where('cb_distribuidor', $idRecebedor)
                        ->like('cb_descricao', $usuarioComprador)
                        ->where('cb_tipo', 107)
                        ->get('conta_bonus')->row();

    }
    
     public static function getRegistroBonusPerdido($idRecebedor, $usuarioComprador) {

        return  get_instance()->db
                        ->where('cb_distribuidor', $idRecebedor)
                        ->where('cb_descricao', 'Bônus Residual <b>' . $usuarioComprador . '</b>')
                        ->where('cb_tipo', 107)
                        ->get('conta_bonus_perdido')->row();
        
    }

}
