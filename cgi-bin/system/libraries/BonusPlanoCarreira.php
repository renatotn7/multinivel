<?php

/**
 * Description of BonusPlanoCarreira
 *
 * @author Ronyldo12
 */
class BonusPlanoCarreira {

    private $db;

    public function __construct() {
        $this->db = & get_instance()->db;
    }

    /**
     * 
     * Verifica se o distribuidor deve ganhar os bônus de plano de carreira
     * 
     * @param int $idDistribuidor
     * @return boolean
     */
    public function pagarBonus($idDistribuidor) {
        $distribuidor = $this->getDistribuidor($idDistribuidor);

        if (count($distribuidor) == 0) {
            return false;
        }

        $qulificacoes = $this->getQualificacaoAPagar($distribuidor->di_id);
        //show_array($qulificacoes);
        foreach ($qulificacoes as $qualificacao) {
            $this->registrarBonus($distribuidor, $qualificacao);
        }
        return true;
    }

    private function registrarBonus($distribuidor, $qualificacao) {
        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => $distribuidor->di_id,
            'cb_compra' => 0,
            'cb_descricao' => 'Bônus de avanço de titulo: <b>' . $qualificacao->dq_descricao . '</b>',
            'cb_credito' => $qualificacao->dq_premiacoes,
            'cb_debito' => 0,
            'cb_tipo' => 236,
            'cb_data_hora' => date('Y-m-d H:i:s')
        ));
        $idContaBonus = $this->db->insert_id();
        $this->db->insert('registro_bonus_plano_carreira', array(
            'bc_distribuidor' => $distribuidor->di_id,
            'bc_qualificacao' => $qualificacao->dq_id,
            'bc_valor' => $qualificacao->dq_premiacoes,
            'bc_conta_bonus' => $idContaBonus,
            'bc_data' => date('Y-m-d H:i:s')
        ));
    }

    private function getQualificacaoAPagar($idDistribuidor) {

        return $this->db->query("
            SELECT * FROM historico_qualificacao 
            LEFT JOIN registro_bonus_plano_carreira ON (bc_qualificacao = hi_qualificacao AND bc_distribuidor = hi_distribuidor) 
            JOIN `distribuidor_qualificacao` ON `dq_id`=`hi_qualificacao` 
            WHERE `hi_qualificacao` != 0 
            AND `hi_distribuidor` = '".$idDistribuidor."' AND bc_id IS NULL
                ")->result();
    }

    public function pagarTodos() {

        $distribuidores = $this->db->query("
            SELECT `di_id`, `di_usuario` FROM historico_qualificacao 
            JOIN `distribuidores` ON `di_id`=`hi_distribuidor` 
            LEFT JOIN `registro_bonus_plano_carreira` ON (bc_qualificacao = hi_qualificacao AND bc_distribuidor=hi_distribuidor) 
            JOIN `distribuidor_qualificacao` ON `dq_id`=`hi_qualificacao` 
            WHERE `hi_qualificacao` > 0 AND bc_id IS NULL AND `di_excluido` = 0 
            GROUP BY `di_id`
            ")->result();
        
        foreach($distribuidores as $distribuidor){
            $this->pagarBonus($distribuidor->di_id);
        }
    }

    private function getDistribuidor($idDistribuidor) {
        return $this->db
                        ->select('di_id,di_usuario,di_nome')
                        ->where('di_id', $idDistribuidor)
                        ->get('distribuidores')->row();
    }

}
