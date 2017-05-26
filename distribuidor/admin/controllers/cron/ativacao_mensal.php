<?php

/**
 * Description of ativacao_mensal
 *
 * @author Ronyldo12
 */
class ativacao_mensal extends CI_Controller {
    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function run() {
        CHtml::berginTime();
        ob_start();        
        $AtivacaoMensalAutomatica = new AtivacaoMensalAutomatica();
        $AtivacaoMensalAutomatica->run();

        CHtml::endTime();
        $registro = ob_get_contents();
        ob_end_clean();
        echo $registro;
        CHtml::logexec('ativacao_mensal_em_data_' . date('d_m_Y'), $registro . ' em ' . date('d_m_Y_H_s_i'), 'ativacao_mensal');
    }

}
