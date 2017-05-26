<?php
class CalculaPontosPagos{
	private $valorTotalDia;
	private $db;
	public function __construct(){
		$ci =& get_instance();
		$this->db = $ci->db;
		}
	public function getValorBonusBinario($dia){
		$this->valorTotalDia = 0;
		//show_array($pagos,true);
		//Join binário
		$distribuidores = $this->db->query("
		 SELECT di_id,di_usuario,di_direita,di_esquerda  
		 FROM distribuidores		 
		 JOIN registro_distribuidor_binario ON di_id = db_distribuidor
		 WHERE db_data <= '{$dia} 23:59:59'
		")->result();		
		
		//echo "<p>Dia {$dia} encontrou ".count($distribuidores)."</p>";
		
		//show_array($dis,true);
		foreach($distribuidores as $k=> $distribuidor){            
			$obj_pontos = new PontosParaRelatorio($distribuidor,$dia);			
			$pontos_a_pagar  = $obj_pontos->pontos_a_pagar();
			if($pontos_a_pagar >0){
				
				$this->valorTotalDia += ($pontos_a_pagar*0.2);   
			}
			//echo "<p>\t\t Dia {$dia} total de ".$this->valorTotalDia." pontos</p>";
		 }
		 
		 return $this->valorTotalDia;	
		
		}
	
}