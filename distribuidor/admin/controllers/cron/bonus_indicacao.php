<?php
class Bonus_indicacao extends CI_Controller
{
	public function __construct(){
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
			->where("at_data + INTERVAL 6 month >=",date('2013-04-2'))
			->get()->result();
			
		
		foreach ($distribuidores as $distribuidor) {
			//BUSCA AS INDICAÇÕES DO DISTRIBUIDOR
			//APENAS INDICAÇÕES ATIVAS
			$indicacoesNaoPagas = $this->db->query("
								SELECT di_id,di_usuario,di_nome
								FROM `distribuidores`
								JOIN `distribuidor_ligacao` ON `li_id_distribuidor` = `di_id`
								JOIN `registro_ativacao` ON `at_distribuidor` = `di_id`
								WHERE 	li_id_distribuidor <> " . $distribuidor->di_id . "
									AND di_id <> " . $distribuidor->di_id . "
									AND li_no = " . $distribuidor->di_id . "
                                                                        AND di_ni_patrocinador =  " . $distribuidor->di_id . "    
									AND di_id NOT IN (
										SELECT `rb_indicado` FROM `registro_bonus_indicacao_pagos`
											WHERE `rb_indicador` = " . $distribuidor->di_id . "
									)
								")->result();
	
	
			foreach($indicacoesNaoPagas as $indicacaoPagar){
				$compraDaAtivacaoDoIndicado = $this->db
													->join('registro_ativacao', 'co_id = at_compra')
													->where('at_distribuidor', $indicacaoPagar->di_id)
													->get('compras')->row();

				//Pegando o valor do plano do patrocinador
				$plano = $this->getPlano($indicacaoPagar->di_id);
                                $percentual_indicacao=1;
                                
                                if($plano->pa_indicacao_indireta >0){
                                   $percentual_indicacao= $percentual_indicacao/100; 
                                }
                                
     			 $valorBonus = ($compraDaAtivacaoDoIndicado->co_total_valor * $percentual_indicacao);
	                 $this->pagar_bonus_indicacao($distribuidor->di_id, $indicacaoPagar->di_id, $valorBonus,$indicacaoPagar->di_usuario);
			}
		}
	}
	
	public function getPlano($di_id=null){
		return $this->db->where('co_id_distribuidor',$di_id)
		->join('compras','co_id_plano=pa_id')
		->order_by('co_id_plano','desc')
		->get('planos',1)->row();
	}
	
	public function pagar_bonus_indicacao($di_indicador, $di_indicado, $valor, $usuario)
	{
		//Creditando a conta
		$this->db->insert('conta_bonus', array(
			'cb_distribuidor' => $di_indicador,
			'cb_descricao' => 'Bônus indicação: <b>'.$usuario.'</b>',
			'cb_credito' => $valor,
			'cb_tipo' => 1
		));
		$id_bonus = $this->db->insert_id();
		//Registrando o prime pago
		$this->db->insert('registro_bonus_indicacao_pagos', array(
			'rb_indicador' => $di_indicador,
			'rb_indicado' => $di_indicado,
			'rb_valor' => $valor
		));
	}
}
?>