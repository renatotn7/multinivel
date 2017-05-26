<?php
class BonusCiclo{
   
   private $db;
   private $valorBonusCiclo;
   private $info_dis_bonus_ciclo;
   
   function __construct(){
	   $ci =& get_instance();
	   $this->db = $ci->db;
	   $this->valorBonusCiclo = 40;  // $40 Dolares
	}	 
	 
   public function pagar_bonus_ciclo($indicador,$dis,$linha,$chegou_no1){
	
	// Conserva os dados do distribuidor que esta pagando o bonus à 10 Níveis acima
	$this->info_dis_bonus_ciclo = $indicador;
	 
	if($linha <= 10){
	 
	 ##-- Obtenho o dado do patrocinador para receber o bonus
	$dis = $this->db
		->select(array('di_id','di_usuario','di_ni_patrocinador','di_ativo'))
		->where('di_id',$dis->di_ni_patrocinador)
		->get('distribuidores')->row();
	 
	if(sizeof($dis)){
	 
	 #-- Valor que deve ser depositado
	 $valor_pagar = $this->valorBonusCiclo/10;
	 
		 ##-- Verifica se vai inserir o crédito para um distribuidor ou para a industria;
		 if($chegou_no1===false){
		 
		 			  $ativacao = $this->db
					  ->select(array('cl_data_inicio','cl_data_fim'))
					  ->join('registro_ativacao_ciclo','atc_distribuidor=cl_distribuidor')
					  ->where('cl_distribuidor',$this->info_dis_bonus_ciclo->di_id)
					  ->where('cl_data_inicio <', date('Y-m-d H:i:s'))
					  ->where('cl_data_fim >', date('Y-m-d H:i:s'))
					  ->like('atc_data', date('Y-m-'))
					  ->get('ciclos')->row();
				   
					  ##-- Inserir Bônus para o distribuidor 		  
					  $this->db->insert('conta_bonus',array(
					  'cb_distribuidor'=>$dis->di_id,
					  'cb_descricao'=>"Bonus de ciclo <b>".$this->info_dis_bonus_ciclo->di_usuario."</b>",
					  'cb_credito'=>$valor_pagar,
					  'cb_debito'=>0,
					  'cb_tipo'=>157
					  ));
					  
					  ##-- Inserir registro do pagamento de bonus
					  $this->db->insert("registro_bonus_ciclo_pagos",array(
						'rc_indicador'=>$this->info_dis_bonus_ciclo->di_id,
						'rc_indicado'=>$dis->di_id,
						'rc_data_indicado_ativou_se'=>date('Y-m-d'),
						'rc_data_inicio_ciclo'=>date('Y-m-d',strtotime($ativacao->cl_data_inicio)),
						'rc_data_fim_ciclo'=>date('Y-m-d',strtotime($ativacao->cl_data_fim))
					  )); 
			
			 
			}
		
		
			 if($dis->di_id == 1){
				$chegou_no1 = true;
			 }
			 
			 // Sobe um nível e atualiza o patrocinador 
			 $linha++;
	    	 self::pagar_bonus_ciclo($indicador,$dis,$linha,$chegou_no1); 
		
		 }//Fim verifica se o patricinador existe	
	 
	 }
		 
	} 
	 
}