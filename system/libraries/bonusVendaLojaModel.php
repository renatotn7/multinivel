<?php

class bonusVendaLojaModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    public static function pagar_bonus($USUARIO, $valor) {
        
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


        $distribuidores = self::db()->db
                        ->where('li_id_distribuidor', $di_id)
                        ->where('li_no !=li_id_distribuidor')
                        ->join('distribuidor_ligacao', 'di_id = li_id_distribuidor')
                        ->order_by('li_posicao', 'desc')
                        ->get('distribuidores', 1, $nivel)->row();

        //Verifica se o patrocinador existe.
        if (count($distribuidores) == 0) {
            return false;
        }

        //Pagando o valor do bonus para o patrocinador.
        contaBonusModel::addSaldoUsuario($distribuidores->li_no, '<b>Bonus Indireto Loja Interna</b>', 237, $valor);

        $nivel++;
        //Chama novamente a função para pagar o proximo patrocinador. 
        self::pagarNiveis($distribuidores->li_no, $valor, $nivel);
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
