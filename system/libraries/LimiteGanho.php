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

	public static function paraPL($di_id,$valor){
		$ci =& get_instance();

		$stdValorTotal = $ci->db
			->select('SUM(cb_credito) as saldo, di_id')
			->where('di_id', $di_id)
			->where('cb_tipo', 106) // apenas para PL
			->join('conta_bonus', 'di_id = cb_distribuidor')
			->join('bonus_tipo', 'tb_id = cb_tipo')
			->get('distribuidores')->row();
			// ->query("
			// 	SELECT SUM(cb_credito) as saldo FROM distribuidores
			// 	JOIN conta_bonus ON di_id = cb_distribuidor
			// 	JOIN bonus_tipo ON tb_id = cb_tipo
			// 	WHERE di_cpf = '".$di_id."'
			// 	AND cb_data_hora LIKE '".date('Y-m-d')."%'
			// ")->row();

		$planoValor = DistribuidorDAO::getPlano($stdValorTotal->di_id)->pa_valor;
        $limiteValor = $planoValor * 2;

		$valorTotalPago = (float)$stdValorTotal->saldo;
		if($valorTotalPago > 0){
			if($valorTotalPago >= $limiteValor)
				return 0;
			elseif($valorTotalPago+$valor >= $limiteValor)
				return $limiteValor-$valorTotalPago;
			else
				return $valor;
		}else{
			if($valor > $limiteValor)
				return 0;
			else
				return $valor;
		}
	}
}