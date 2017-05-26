<?php

class Ciclos {

    private $db;

    /*
     * Contrutor inicializa a variavel ci
     * @return void
     */

    function __construct() {
        $ci = & get_instance();
		$this->db = $ci->db;
    }

	/*
     * TRIGGER chamada para verificar se o distribuidor esta ativo no mês atual.
     * @return boolean
     */


	public function checa_ativacao($id_distribuidor,$data){
		// busca o distribuidor dentro do ciclo atual é trás seu registro de ativação
		$ativacao = $this->db
		->join('registro_ativacao_ciclo','atc_distribuidor=cl_distribuidor')
		->where('cl_distribuidor',$id_distribuidor)
		->where('cl_data_inicio <', date('Y-m-d H:i:s',strtotime($data)))
		->where('cl_data_fim >', date('Y-m-d H:i:s',strtotime($data)))
		->like('atc_data', date('Y-m-',strtotime($data)))
		->get('ciclos')->row();
	
		if(count($ativacao)){
			//Caso encontre, retonar o ciclo em que ele foi ativado
			echo $ativacao->cl_ciclo;	
		}else{
			echo 0;
		}
		
	}
	
	
	/*
     * CRON rodada diariamente que ativa mensalmente o distribuidor e debita $50 do saldo.
     * @return boolean
     */


	public function lanca_ativacao_ciclo(){
		
		// Traz todos os distribuidores que estão dentro da data de ativação mensal em até 2 meses anteriores (para reduzir o numero de registros retornados é ainda pegar quem esta dentro do ciclo...)
		$inativosAtual = $this->db
		->select(array('cl_distribuidor','cl_data_inicio','cl_data_fim','di_id','di_usuario','di_ni_patrocinador'))
		->join('distribuidores','di_id = cl_distribuidor')
		->where('cl_data_inicio >=',date('Y-m-d H:i:s',strtotime('-2 months')))
		->where('cl_data_fim <=',date('Y-m-d H:i:s'))
		->get('ciclos')->result();
		
		echo "<table>";
		echo "<tr>";
			echo "<td width='150'><strong>Distribuidor</strong></td>";
			echo "<td width='150'><strong>Data ciclo</strong></td>";
			echo "<td width='150'><strong>Situacao</strong></td>";
		echo "</tr>";	
		
		if(sizeof($inativosAtual)){
				
			foreach($inativosAtual as $dis){
				
				// Busca uma ativação do distribuidor do mês atual em registros
				$ativacao = $this->db
				->select(array('atc_data','atc_distribuidor'))
				->where('atc_distribuidor',$dis->cl_distribuidor)
				->like('atc_data',date('Y-m-'))
				->get('registro_ativacao_ciclo')->row();
				
				if(!sizeof($ativacao)){						
						
						$banco = $this->db
						->select(array('SUM(cb_credito) - SUM(cb_debito) as saldo'))
						->where('cb_distribuidor',$dis->cl_distribuidor)
						->get('conta_bonus')->row();
						
						//Checa se possui saldo suficiente para debitar é continuar com o mínimo de $50 
						if($banco->saldo >= 100){
							//Somente distribuidores que não possui registro de ativação no mês atual
							
							$conta_bonus = array(
								'cb_distribuidor' => $dis->cl_distribuidor,
								'cb_compra'		=> 0,
								'cb_descricao'  => 'Pagamento do Ciclo Mensal $50',
								'cb_credito'	 => 0,
								'cb_debito'	 => 50,
								'cb_tipo' => 50
							);
							
							$this->db->insert('conta_bonus', $conta_bonus);
							
							//Obtendo o numero do bonus
							$id_bonus = $this->db->insert_id();
							
							$registro_ativacao_ciclo = array(
								'atc_distribuidor' => $dis->cl_distribuidor,
								'atc_bonus'		=> $id_bonus
							);
					
							$this->db->insert('registro_ativacao_ciclo', $registro_ativacao_ciclo);
							
							///////// Paga Bônus Unilevel system/library ////////////////
							$bonusciclo = new bonusciclo;
							$bonusciclo->pagar_bonus_ciclo($dis,$dis,1,false);
							/////////////////////////////////////////////							
							
							echo "<tr>";
								echo "<td>".$dis->di_usuario." (".$dis->cl_distribuidor.")</td>";
								echo "<td>".date('d/m/Y',strtotime($dis->cl_data_fim))."</td></td>";
								echo "<td style='color:green;'>Foi ativado</td></td>";
							echo "</tr>";
						}else{
							echo "<tr>";
								echo "<td>".$dis->di_usuario." (".$dis->cl_distribuidor.")</td>";
								echo "<td>".date('d/m/Y',strtotime($dis->cl_data_fim))."</td></td>";
								echo "<td style='color:red;'>Falta de saldo ($".number_format($banco->saldo,2,'.',',').")</td></td>";
							echo "</tr>";
						}
				}
			}
		
		}
		
		echo "</table>";
	}

    /*
     * Verifica se essa compra vai ativar o distribuidor.
     * @return boolean
     */

    public function lanca_ciclos($di_id){
		
		$jaLancado = $this->db
		->where('cl_distribuidor',$di_id)
		->where('cl_data_fim >',date('Y-m-d'))
		->order_by('cl_data_fim','DESC')
		->get('ciclos')->row();
		
		$ativacaoBinario = $this->db->where('db_distribuidor',$di_id)->get('registro_distribuidor_binario')->row();
		
		
		if(count($jaLancado) == 0 && count($ativacaoBinario) > 0){
		
		
		
		
		$diasFatura = date('d', strtotime($ativacaoBinario->db_data));
		$timeDataDaAtivacao = strtotime($ativacaoBinario->db_data);
		
		//ciclo se renovam de 6 em 6 meses
		$dataInicio = strtotime($ativacaoBinario->db_data);
		$dataInicio = date('Y-m-d', mktime(0,0,0,date('m',$dataInicio),date('d',$dataInicio),date('Y',$dataInicio)));
		$dataFinal = '';
		
		for($i =1; $i <= 6; $i++){
			
			$timeDataParcelaAtual  = mktime(0,0,0,date('m',$timeDataDaAtivacao)+$i,01,date('Y',$timeDataDaAtivacao));
			
			$dataFinal = self::data_parcela_valida($diasFatura,date('m',$timeDataParcelaAtual),date('Y',$timeDataParcelaAtual));
			
			$timeFinal = strtotime($dataFinal); 
			$dataFinal =  date('Y-m-d',mktime(0,0,0,date('m',$timeFinal),date('d',$timeFinal),date('Y',$timeFinal)));
			$dados_ciclo = array(
				'cl_distribuidor' => $di_id,
				'cl_ciclo'		=> $i,
				'cl_data_inicio'  => $dataInicio.' 00:00:00',
				'cl_data_fim'	 => $dataFinal.' 00:00:00'
			);
			
			$this->db->insert('ciclos', $dados_ciclo);
			$dataInicio = $dataFinal;			
		}
		
		
	  }
		
	}
	
		public function data_parcela_valida($d,$_mes,$_ano){
		for($i=$d;$i>=1;$i--){
			$dia_d = $i<=9?'0'.($i+0):$i;
			if(checkdate($_mes,$dia_d,$_ano)){
			  return "{$_ano}-{$_mes}-{$dia_d}";
			}
		}
		
	} 

}