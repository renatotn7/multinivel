<?php

/**
 * Description of plano_carreira
 *
 * @author Ronyldo12
 */
class plano_carreira extends CI_Controller {

    public function run() {
        ob_start();
        CHtml::berginTime();
        $bonusPlanocarreira = new BonusPlanoCarreira();
        $bonusPlanocarreira->pagarTodos();
        CHtml::endTime();
        $registro = ob_get_contents();
        ob_end_clean();
        echo $registro;
        CHtml::logexec('plano_de_carreira_em_data_' . date('d_m_Y'), $registro . ' em ' . date('d_m_Y_H_s_i'), 'plano_de_carreira');
    }

}
