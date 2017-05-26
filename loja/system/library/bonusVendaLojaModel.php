<?php

class bonusVendaLojaModel {

    private static $db;

    public function __construct($registry) {
        self::$db = $registry->get('db');
    }

    public function db() {
        return self::$db;
    }

    public static function pagar_bonus($usuario, $valor) {


        $valorCompraIndireto = $valor * 0.01;
        $valorCompraDireto = $valor * 0.10;

        //Pagando o bonus para os indireto que comprou na loja
        self::pagarNiveis($USUARIO, $valorCompraIndireto);

        //Pagando o bonus para os diretos que comprou na loja.
        self::pagarSemNivel($USUARIO, $valorCompraDireto);
    }

    /**
     * Realiza o pagamento dos distribuidores em cima das vendas 
     * indiretas.
     */
    protected function pagarNiveis($di_id = 0, $valor = 0.00, $nivel = 0) {

        //Se o nível for maior que 10 para a execulção do script
        if ($nivel > 9) {
            return false;
        }


        $distribuidores = self::db()->query("select * from distribuidores"
                                . "join distribuidor_ligacao on di_id = li_id_distribuidor "
                                . " where li_id_distribuidor= {$di_id} li_no !=li_id_distribuidor"
                                . "order by li_posicao desc"
                                . "limit 1 , {$nivel}");
                        
        //Verifica se o patrocinador existe.
        if ($distribuidores->num_rows) {
            return false;
        }

        //Pagando o valor do bonus para o patrocinador.
//        contaBonusModel::addSaldoUsuario($distribuidores->row['li_no'], '<b>Bonus Indireto Loja Interna</b>', 237, $valor);
var_dump($distribuidores->row);
        $nivel++;
        //Chama novamente a função para pagar o proximo patrocinador. 
        self::pagarNiveis($distribuidores->row['li_no'], $valor, $nivel);
    }

    /**
     * Realiza o pagamento dos distribuidores em cima das vendas 
     * diretas na na loja.
     */
    protected function pagarSemNivel($di_id = 0, $valor = 0.00) {

        $distribuidores = self::db()->db
                        ->where('di_id', $di_id)
                        ->join('distribuidor_ligacao', 'di_id = li_id_distribuidor')
                        ->get('distribuidores')->row();

        //Verifica se o patrocinador existe.
        if (count($distribuidores) == 0) {
            return false;
        }

        //Pagando o valor do bonus para o patrocinador.
        contaBonusModel::addSaldoUsuario($distribuidores->di_id, '<b>Bonus Direto Loja Interna</b>', 238, $valor);
    }

}
