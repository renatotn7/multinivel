<?php

class QualificacaoModel {

    private $distribuidor;
    private $quantidadePernaMenor;
    private $franquiasEsquerda;
    private $franquiasDireita;
    private $db;

    function setDistribuidor($distribuidor) {
        $this->distribuidor = $distribuidor;
        $ci = & get_instance();
        $this->db = $ci->db;

        $this->franquiasEsquerda();
        $this->franquiasDireita();
        $this->carregaPernaMenor();
    }

    function getQuantidadePernaMenor() {
        return $this->quantidadePernaMenor;
    }

    private function carregaPernaMenor() {
        if ($this->franquiasDireita < $this->franquiasEsquerda) {
            $this->quantidadePernaMenor = $this->franquiasDireita;
        } else {
            $this->quantidadePernaMenor = $this->franquiasEsquerda;
        }
    }

    public function franquiasDireita() {
        if ($this->franquiasDireita == NULL) {
            if ($this->distribuidor->di_direita > 0) {
                $sqlDireita = $this->db->query("
                                                SELECT count(li_id_distribuidor) as qtd_equipe_perna_direita FROM distribuidor_ligacao
                                                            JOIN compras ON co_id_distribuidor = li_id_distribuidor
                                                WHERE  li_no = " . get_user()->di_direita . "
                                                    and co_pago = 1
                                                    and co_eplano = 1
                                                    and li_id_distribuidor <> " . get_user()->di_id . " "
                        )->row();
                $this->franquiasDireita = (int) $sqlDireita->qtd_equipe_perna_direita;
            } else {
                $this->franquiasDireita = 0;
            }
        }
        return $this->franquiasDireita;
    }

    public function franquiasEsquerda() {
        if ($this->franquiasEsquerda == NULL) {
            if ($this->distribuidor->di_esquerda > 0) {
                $sqlEsquerda = $this->db->query("
                                                SELECT count(li_id_distribuidor) as qtd_equipe_perna_esquerda
                                                FROM distribuidor_ligacao
                                                    JOIN compras ON co_id_distribuidor = li_id_distribuidor
                                                WHERE   li_no = " . get_user()->di_esquerda . "
                                                        and co_pago = 1
                                                        and co_eplano = 1
                                                        and li_id_distribuidor <> " . get_user()->di_id . "
				")->row();
               //COLOCAR GROUP BY
                $this->franquiasEsquerda = (int) $sqlEsquerda->qtd_equipe_perna_esquerda;
            } else {
                $this->franquiasEsquerda = 0;
            }
        }
        return $this->franquiasEsquerda;
    }

    public function eQualificado($idQualificacao, $franquias = NULL) {
        if ($franquias == NULL) {
            $qualificacao = $this->db
                    ->where('dq_id', $idQualificacao)
                    ->get('distribuidor_qualificacao')
                    ->row();

            if (count($qualificacao) == 0) {
                $franquiasNecessarias = 100000;
                echo "<p>Qualificacao nao encontrada. ID: {$idQualificacao} DIS: {$this->distribuidor->di_usuario} </p>";
            } else {
                $franquiasNecessarias = (int) $qualificacao->dq_qtd_franquias;
            }
        } else {
            $franquiasNecessarias = $franquias;
        }

        $franquiasParaAtingirQualificacao = (int) $franquiasNecessarias - (int) $this->getQuantidadePernaMenor();
        return $franquiasParaAtingirQualificacao <= 0;
    }

    function executar() {

        // Lista todas as qualificações disponiveis para o associado
        $arrayQualificacoes = $this->db->query("
			SELECT * FROM distribuidor_qualificacao
                        WHERE dq_qtd_franquias <= " . $this->quantidadePernaMenor . "
                            AND dq_id NOT IN(
                                                SELECT hi_qualificacao FROM historico_qualificacao 
                                                WHERE hi_distribuidor = " . $this->distribuidor->di_id . "
                                            )
			")->result();


        foreach ($arrayQualificacoes as $stdQualificacao) {

            // Checa e registra upgrade de qualificação do associado
            if ($this->eQualificado($stdQualificacao->dq_id, $stdQualificacao->dq_qtd_franquias)) {
                // Registra upgrade
                $this->db->insert('historico_qualificacao', array(
                    'hi_data' => date('Y-m-d'),
                    'hi_distribuidor' => $this->distribuidor->di_id,
                    'hi_qualificacao' => $stdQualificacao->dq_id
                ));
                $this->db
                        ->where('di_id', $this->distribuidor->di_id)
                        ->update('distribuidores', array(
                            'di_qualificacao' => $stdQualificacao->dq_id));
            }//Fim verifica a qualificação
        }//Foreach que verifica a qualificação
    }

//Fim da função executar

    public function clear() {
        $this->franquiasDireita = NULL;
        $this->franquiasEsquerda = NULL;
        $this->quantidadePernaMenor = NULL;
    }

}
