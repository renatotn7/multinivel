<?php

/**
 * Description of SaldoVirtual
 *
 * @author Ronyldo12
 */
class SaldoVirtual {

    private $db;

    public function __construct() {
        set_time_limit(0);
        $this->db = get_instance()->db;
    }

    public static function getSaldo($idDistribuidor){
       $saldo = get_instance()->db->query("
                SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
                WHERE cb_distribuidor = " . $idDistribuidor . "
                ")->row(); 
       return (float) $saldo->saldo;
    }
    
}
