<?php
class Bonus_indicacao extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	/*
		Bonus pela entrada de um distribuidor indicado diretamente
	*/
	public function executar_bonus()
	{
		/*
		if($this->uri->segment(3) != conf()->token_seguranca){
		echo "acesso negado";
		exit;   
		}*/
		//Distribuidores qualificados a receber bonus de indicação
		$distribuidores = $this->db->select(array(
			'di_id',
			'di_usuario',
			'di_direita',
			'di_esquerda'
		))->from('distribuidores')
		//APENAS BINARIOS ATIVOS
			->join('registro_distribuidor_binario', 'db_distribuidor = di_id')
		//APENAS ATIVOS
			->join('registro_ativacao', 'at_distribuidor = di_id')
		//DESTE MES
			->where("DATE_FORMAT(at_data,'%Y-%m-')", date('Y-m-'))->get()->result();
		echo ('<b>Aptos</b><br><pre>');
		print_r($distribuidores);
		echo ('</pre>');
		foreach ($distribuidores as $distribuidor) {
			//BUSCA AS INDICAÇÕES DO DISTRIBUIDOR
			//APENAS INDICAÇÕES ATIVAS
			$indicacoesNaoPagas = $this->db->query("
								SELECT *
								FROM `distribuidores`
								JOIN `distribuidor_ligacao` ON `li_id_distribuidor` = `di_id`
								JOIN `registro_ativacao` ON `at_distribuidor` = `di_id`
								WHERE 	li_id_distribuidor <> " . $distribuidor->di_id . "
									AND di_id <> " . $distribuidor->di_id . "
									AND li_no = " . $distribuidor->di_id . "
									AND di_id NOT IN (
										SELECT `rb_indicado` FROM `registro_bonus_indicacao_pagos`
											WHERE `rb_indicador` = " . $distribuidor->di_id . "
									)
								")->result();
								
			echo ('<b>Indicados</b><br><pre>');
			print_r($indicacoesNaoPagas);
			echo ('</pre>');
			
			$qtdIndicacoes = count($indicacoesNaoPagas);
			
			switch($qtdIndicacoes){
				
				//CASO TENHA INDICADO + 1 USUARIO A REDE
				case 1:
					$indicacaoPagar = $indicacoesNaoPagas[0];
						//BONUS DE 170 SOMADO AO 130 DE INDICAÇÃO = 300.00
						$valorBonus = 170.00;
						$this->registra_bonus_fixo_pago();
						$this->pagar_bonus_fixo($distribuidor->di_id, $indicacaoPagar->di_id, $valorBonus);
				break;
				
				//CASO TENHA INDICADO +3 USUARIOS A REDE
				case 3:
					foreach($indicacoesNaoPagas as $indicacaoPagar){
						
					}
					$valorBonus = 90.00;
					$this->pagar_bonus_fixo($distribuidor->di_id, $indicacaoPagar->di_id, $valorBonus);
				break;
				default:
				break;
			}
		}
	}
	
	public function registra_bonus_fixo_pago(){
		$this->db->insert('registro_bonus_indicacao_pagos', array(
			'rb_indicador' => $di_indicador,
			'rb_indicado' => $di_indicado,
			'rb_valor' => $valor
		));
	}
	
	public function pagar_bonus_fixo($di_indicador, $qtdIndicacoes, $valor)
	{
		//Creditando a conta
		$this->db->insert('conta_bonus', array(
			'cb_distribuidor' => $di_indicador,
			'cb_descricao' => 'Bônus fixo por indicação de '.$qtdIndicacoes.' novos distribuidores no periodo de',
			'cb_credito' => $valor,
			'cb_tipo' => 1
		));
	}
}
?>