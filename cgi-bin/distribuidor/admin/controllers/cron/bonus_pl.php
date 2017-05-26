<?php
class bonus_pl extends CI_Controller{
	
	public function index(){
            
            set_time_limit(0);
            ini_set('max_execution_time',0);
            ini_set('memory_limit', '128M');
            
              
            CHtml::berginTime();
            ob_start();
	    $bonus_pl = new bonusPL();
            CHtml::endTime();
            $registro = ob_get_contents();
            ob_end_clean();   
            echo $registro;
            CHtml::logexec('bonus_pl_rodou_em_data_'.date('d_m_Y'),$registro.' em '.date('d_m_Y_H_s_i'),'bonus_pl');  
	}
}