<?php
class AtivacaoModel{
	
	 private $db;
	 private $data;
	 private $estaAtivo;
	 private $seisMesesAtras;
	 private $distribuidor;
	 
	 public function __construct($distribuidor){
		 $ci =& get_instance();
		 $this->db = $ci->db;
		 $this->estaAtivo = 0;
		 $this->distribuidor = $distribuidor;
		 $this->seisMesesAtras = date('Y-m-d H:i:s',mktime(0,0,0,date('m')-6,date('d'),date('Y')));
		 $this->estaAtivo();
		 }
	
	
	private function estaAtivo(){
		 $jaEstaAtivo = $this->db->where('at_data >',$this->seisMesesAtras)
		->where('at_distribuidor',$this->distribuidor->di_id)
		->get('registro_ativacao',1)->row();
		
		
		if(count($jaEstaAtivo) > 0){
			 $this->estaAtivo = 1;
			 $this->data = $jaEstaAtivo->at_data;
			}else{
				$this->estaAtivo = 0;
			}
			
		}
	
	public function getEstaAtivo(){
		return $this->estaAtivo;
		}

	public function getData(){
		return $this->data;
		}	

	public function getProximaAtivacao(){
		 $timeDataAtivo = strtotime($this->data);
		 $seisMesesDepoisAtivacao = date('Y-m-d H:i:s',mktime(0,0,0,date('m',$timeDataAtivo)+6,date('d',$timeDataAtivo),date('Y',$timeDataAtivo)));
		 return $seisMesesDepoisAtivacao;
		}		
	}