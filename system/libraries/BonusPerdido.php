<?php

/**
 *  BonusPerdido
 *
 * @author Ronildo Souza <ronyldo12@hotmail.com>
 */
class BonusPerdido {

    private static $DATA_LIMITE_SEM_CONTA_VERIFICADA;

    public function getDataCadastro($idDistribuidor) {
        $primeiraCompra = get_instance()->db->select('co_data_compra')
                        ->where('co_id_distribuidor', $idDistribuidor)
                        ->where('co_eplano', 1)
                        ->where('co_pago', 1)
                        ->get('compras')->row();
        if (count($primeiraCompra) > 0) {
            return $primeiraCompra->co_data_compra;
        } else {
            return date('Y-m-d H:i:s');
        }
    }

    public function dataToUsa($data) {
        list($d, $m, $y) = explode('/', $data);
        return "$y-$m-$d";
    }

    /*
     * Retorna a data limite para recebimento de bônus
     */

    public static function getDataLimite() {

        if (self::$DATA_LIMITE_SEM_CONTA_VERIFICADA == NULL) {

            $dataConfig = get_instance()->db->where('field', 'data_limit_recebimento_sem_verificar_conta')->get('config')->row();
            $dataLimit = date('Y-m-d');
            if (isset($dataConfig->valor)) {
                $dataLimit = self::dataToUsa($dataConfig->valor);
            } else {

                get_instance()->db->insert('config', array(
                    'field' => 'data_limit_recebimento_sem_verificar_conta',
                    'valor' => date('d/m/Y'),
                    'descricao' => 'Data limite para recebimento de bônus sem verificar a conta',
                    'id_tab' => 1,
                    'ordem' => 1
                ));
                return self::getDataLimite();
            }
            self::$DATA_LIMITE_SEM_CONTA_VERIFICADA = $dataLimit;
        }
        return self::$DATA_LIMITE_SEM_CONTA_VERIFICADA;
    }

    /*
     * Retorna a data limite para recebimento de bônus no formato Brasileiro
     */

    public static function getDataLimiteBrasil() {
        return date('d/m/Y', strtotime(self::getDataLimite()));
    }

    /*
     * Verifica se o distribuidor verificou a conta e está dentro do prazo
     * determinando Pelo administrador.
     * @return boolean
     */

    public static function receberBonus($pais, $contaVerificada, $idDIstribuidor = 0) {

        if(SaldoVirtual::getSaldo($idDIstribuidor) < 0){
            return false;
        }
        return true;
    }

    /**
     * Verifica ser o Binário ta ativo, apto a receber o bônus.
     * na tabela Regitro ativação
     */
    public function binarioAtivo($di_id) {
        $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 180, date('Y')));

        $dis = get_instance()->db
                        ->where('at_distribuidor', $di_id)
                        ->where('at_data > "' . $seisMesesAtras . '"')->get('registro_ativacao')->row();

        $binarioAtivo = get_instance()->db->query("SELECT SQL_CACHE *
                         FROM registro_distribuidor_binario 
                         WHERE `db_distribuidor` = " . $di_id . "
                         ")->row();

        if (count($dis) > 0 && count($binarioAtivo) > 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>
