<?php
/**
 * Classe de bonus de indicação usada
 * @author objeto
 *
 */
class BonusIndicacao extends CI_Controller implements registry
{
	private $valorCompra;
	private $usuario;
	/*
	 * Bonus pela entrada de um distribuidor indicado diretamente
	 */
	public function pagarBonus($Objcompra)
	{
		
		if(count($Objcompra) ==0){
			return false;
			exit;
		}
		
		//Pega o distribuuidor da compra
		$distribuidor=$this->getDistribuidor($Objcompra->co_id_distribuidor);
		
          //Seta o valor da compra.
		 $this->valorCompra=$Objcompra->co_total_valor;
		 $this->usuario=$distribuidor->di_usuario;

        $this->pagarGreacoes($distribuidor->di_id,9,0);

        //Para se retonar vazio.
        if(count($distribuidor)==0) return false;
        //pegando a geração.
  
  
        
	}
	
	
	public function pagarGreacoes($di_id=null,$configNivel=9,$nivel=0)
	{
		//pega o patrocinador
		$patrocinador = $this->getDistribuidor($di_id);

		//Pegando o valor do plano do patrocinador
		$plano = $this->getPlano($patrocinador->di_ni_patrocinador);
		
		$valor=$this->valorCompra * ($plano->pa_indicacao_direta/100);

		//inserindo valor para quele patrocinador.
		$this->db->insert('conta_bonus',array(
			'cb_credito'=>$valor,
			'cb_descricao'=>'Bonus de indicação do usuário: <b>'. $this->usuario.'</b>',
			'cb_data_hora'=>date('Y-m-d H:i:s'),
			'cb_tipo'=>'1',
			'cb_distribuidor'=>$patrocinador->di_ni_patrocinador
		));
		
		//pega o patrocinador
		$patrocinador = $this->getDistribuidor($patrocinador->di_ni_patrocinador);
		$nivel=$nivel+1;
		if(count($patrocinador) > 0 && $nivel !=$configNivel)
		   $this->pagarGreacoes($patrocinador->di_id,$configNivel,$nivel);
	} 
	
    public function getPlano($di_id=null){
      return $this->db->where('co_id_distribuidor',$di_id)
					      ->join('compras','co_id_plano=pa_id')
					      ->order_by('co_id_plano','desc')
					      ->get('planos',1)->row();
    }
    
	public function getGeracao($di_id=null)
	{
		return $this->db->where('li_no',$di_id)
							 ->join('distribuidor_ligacao',' di_id=li_no and li_no !=li_id_distribuidor')
							 ->get('distribuidores')->result();
	}
	/**
	 * Retorna o distristribuidor que fez a compra.
	 */
	public function getDistribuidor($di_id=null){
		return $this->db->where('di_id', $di_id, false)
		                ->get('distribuidores')
						->row();
	}
	
	/**
	 * Dever passar o objeto do distribuidor.
	 * status de pogamento se foi pago RETORNA FALSE SE NÃO RETORNA TRUE.
	 * @return boolean
	 */
	public function statusPagamentoBonus($Objcompra){
		
		$ObjDistribuidor=$this->getDistribuidor($Objcompra->co_id_distribuidor);
		$registroBonusPagos = $this->db
							->where('bp_distribuidor', $ObjDistribuidor->di_id)
							->where('bp_patrocinador', $ObjDistribuidor->di_ni_patrocinador)
							->get('registro_bonus_indicacao_pago')->row();
	  
	  if(count($registroBonusPagos)>0)
	   return false;
	   else 
	   	return true;	
	}
	
	/**
	 * Verifica se o patrocinador ta apto.
	 * @param unknown $di_id
	 */
	public function isApto($di_id){
		$registroAtivacao = $this->db
							->where('at_distribuidor', $di_id)
						//	->where('at_data >=', date('Y-m-01'))
							->get('registro_ativacao')->row();
		
		if(count($registroAtivacao)>0)
			return true;
		else
			return false;
			
	}

}

/**
 * Interfaces 
 * @author objeto
 *
 */
interface registry {
	public function pagarBonus($compra);
	public function statusPagamentoBonus($distribuidor);
}

/**
 * Class bonus que implementas as interfaces.
 * @author objeto
 *
 */

class Bonus extends CI_Controller {
	
	public function pagarBonus(Registry $class,$Objeto=null){
		$class->pagarBonus($Objeto);
	}
	
	public function statusPagamentoBonus(Registry $class,$Objeto=null){
		return $class->statusPagamentoBonus($Objeto);
	}
	
		
	//Implementando o metodo statico IndicacaoDireta.
	static public function IndicacaoDireta($Objcompra=null){
    	if($Objcompra !=null)
    		if(self::statusPagamentoBonus(new BonusIndicacao(),$Objcompra))
         	      self::pagarBonus(new BonusIndicacao(),$Objcompra);
	}
}
?>