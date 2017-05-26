<?php
class LimiteGanho{
	
	public static function paraCPF($cpf,$valor,$maximoDiarioPermitido,$dia=NULL){
		 $ci =& get_instance();
		 
		 if($dia==NULL){
			  $dia = date('Y-m-d');
			 }
		 
		 $stdValorHoje = $ci->db->query("
		  SELECT SUM(cb_credito) as valor_pago FROM distribuidores
		   JOIN conta_bonus ON di_id = cb_distribuidor
		   JOIN bonus_tipo ON tb_id = cb_tipo
		  WHERE di_cpf = '".$cpf."'
		  AND cb_data_hora LIKE '".date('Y-m-d')."%'
		 ")->row();
		 
		 $valorPagoHoje = (float)$stdValorHoje->valor_pago;
		 
		if($valorPagoHoje > 0){
			if($valorPagoHoje > $maximoDiarioPermitido){
				return 0;
			}elseif($valorPagoHoje+$valor > $maximoDiarioPermitido){
				return $maximoDiarioPermitido-$valorPagoHoje;
			}else{
				return $valor;
			} 
			
		}else{
			if($valor > $maximoDiarioPermitido){
				return $maximoDiarioPermitido;
			}else{
				return $valor;
			}
		}
	}
}