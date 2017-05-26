<?php
class com_extornar_bonus_indevido extends CI_Controller
{
	public function execulta()
	{
		 //Pessoas que recebeu indevidamente.
		$registro_pl = $this->db->query('
				select rbpl_id_conta_bonus from registro_bonus_pl where registro_bonus_pl.rbpl_distribuidor 
				not in (select db_distribuidor from registro_distribuidor_binario) and rbpl_tipo=2
				')->result();
		
		foreach ($registro_pl as $pl){
                
                echo "<p>: {$pl->rbpl_id_conta_bonus}</p>";
		//Extornado o bonus na conta bonus.
		$conta_bonus  = $this->db->where('cb_id',$pl->rbpl_id_conta_bonus)->delete('conta_bonus');
		}
		
		//limpando o regitro ativação 
		$this->db->query('delete from registro_bonus_pl where registro_bonus_pl.rbpl_distribuidor 
				not in (select db_distribuidor from registro_distribuidor_binario) and rbpl_tipo=2');
	}
}