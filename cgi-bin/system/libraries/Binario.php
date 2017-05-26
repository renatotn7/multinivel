<?php

//Usada em Planos.php
class Binario {

    private $ci;
    private $qtd_esquerda;
    private $qtd_direita;
    private $distribuidor;
    private $binario_ativo; 
    private $indicacao_direita;
    private $indicacao_esquerda;
    private $indicacao_direta_direita;
    private $indicacao_direta_esquerda;
    private $data_que_virou_prime;
    private $total_indicacoes_direta;
    private $total_indicacoes_indireta;

    public function __construct($dis) {
        $this->ci = & get_instance();
        $this->distribuidor = $dis;
        $this->carrega_qtd_pontos_direita();
        $this->carrega_qtd_pontos_esquerda();
        $this->init_binario_ativo();
        $this->verificar_binario_ativo();
        $this->carregar_indicacoes();

    }

    private function carrega_qtd_pontos_direita() {
        $qtd_direita = $this->ci->db->query("
		 SELECT sum(co_total_pontos) as qtd FROM `distribuidor_ligacao` 
                    JOIN distribuidores ON di_id = `li_id_distribuidor`
                    JOIN compras ON co_id_distribuidor = di_id
		 WHERE `li_no` = " . $this->distribuidor->di_direita . "
         AND di_ni_patrocinador = ".$this->distribuidor->di_id."
         AND co_pago = 1
         AND co_total_valor !=0")->row();

        $this->qtd_direita = (int) $qtd_direita->qtd;
    }

    private function carrega_qtd_pontos_esquerda() {
        $qtd_esquerda = $this->ci->db->query("
		 SELECT sum(co_total_pontos) as qtd FROM `distribuidor_ligacao` 
                    JOIN distribuidores ON di_id = `li_id_distribuidor`
                    JOIN compras ON co_id_distribuidor = di_id
		 WHERE `li_no` = " . $this->distribuidor->di_esquerda . "
         AND di_ni_patrocinador = ".$this->distribuidor->di_id."
         AND co_pago = 1
         AND co_total_valor !=0")->row();

        $this->qtd_esquerda = (int) $qtd_esquerda->qtd;
    }

    public function get_qtd_direita() {
        return $this->qtd_direita;
    }

    public function get_qtd_esquerda() {
        return $this->qtd_esquerda;
    }

    public function e_binario() {
        return $this->binario_ativo;
    }
    
    public function get_indicacoes_indireta_direita(){
    	return $this->indicacao_direita;
    }
    public function get_indicacoes_indireta_esquerda(){
    	return $this->indicacao_esquerda;
    }
    public function  get_indicacoes_diretas_direita(){
    	return $this->indicacao_direta_direita;
    }
    
    public function get_indicacoes_diretas_esquerda()
    {
    	return $this->indicacao_direta_esquerda;
    }
    
    public function get_total_inidicacoes(){
    	return $this->total_indicacoes_indireta;
    }
    public function get_total_inidicacoes_diretas(){
    	return $this->total_indicacoes_direta;
    }
    private function init_binario_ativo() {
    	$binarioAtivo = $this->ci->db->query("SELECT SQL_CACHE *
    									  FROM registro_distribuidor_binario
    									  WHERE `db_distribuidor` = ".$this->distribuidor->di_id)->row();
    	if(count($binarioAtivo)>0){
    		  $this->binario_ativo = true;
    	}else{
    		 $this->binario_ativo = false;
    	}
    }

    //Verifica se a quantidade de indicação na perna direita e na perna esquerda e maior que 1
    private function verificar_binario_ativo() {
    	$AT = new AtivacaoBinario();
    	$AT->verificarBinarioAtivo($this->distribuidor);
    }
    

    
    /**
     * is_upgrade
     * Verifica se fez upgrade e retorna a data do mesmo.
     */
    private function is_upgrade(){
    	return $is_upgrade = $this->ci->db->query("select
								    			IF(count(co_id) =1,'sem_upgrade','fez_upgrade') as up_grade,
								    			max(co_data_compra) as data
								    			from compras where co_eplano=1
								    			and co_pago=1
								    			and co_id_distribuidor={$this->distribuidor->di_id}
								    			")->row();
    }
    
    private function carregar_indicacoes(){
    	
    	$indicaoes_diretas_direita = $this->ci->db->query(
	                            "SELECT COUNT(di_id) AS quantidade
								FROM   distribuidor_ligacao
								       JOIN distribuidores
								       ON     di_id               = li_id_distribuidor								      
								WHERE  li_no                      =" .  $this->distribuidor->di_direita . "
								AND    di_ni_patrocinador         = " .  $this->distribuidor->di_id )->row();
    	
    	$this->indicacao_direta_direita = isset($indicaoes_diretas_direita->quantidade)?$indicaoes_diretas_direita->quantidade:0;
    	
    	$indicaoes_diretas_esquerda = $this->ci->db->query(
    			"SELECT COUNT(di_id) AS quantidade
								FROM   distribuidor_ligacao
								       JOIN distribuidores
								       ON     di_id               = li_id_distribuidor
								       AND    di_ni_patrocinador != li_id_distribuidor
								WHERE  li_no                      =" .  $this->distribuidor->di_esquerda. "
								AND    di_ni_patrocinador         = " .  $this->distribuidor->di_id)->row();

    	
    	$this->indicacao_direta_esquerda  = isset($indicaoes_diretas_esquerda->quantidade)?$indicaoes_diretas_esquerda->quantidade:0;
    	
    	$this->total_indicacoes_direta = $this->indicacao_direta_direita + $this->indicacao_direta_esquerda;
    	
    	
    	
       	$indicaoes_indireta_direita = $this->ci->db->query(
    			                           "SELECT COUNT(di_id) AS quantidade
											FROM   distribuidor_ligacao
											       JOIN distribuidores
											       ON     di_id               = li_id_distribuidor   
											  WHERE  li_no                      =".$this->distribuidor->di_direita
											 ." AND di_ni_patrocinador !={$this->distribuidor->di_id} ")->row();
       	
    	
    	$indicaoes_indireta_esquerda = $this->ci->db->query(
    									   "SELECT COUNT(di_id) AS quantidade
											FROM   distribuidor_ligacao
											       JOIN distribuidores
											       ON     di_id               = li_id_distribuidor					
											WHERE  li_no                      =".$this->distribuidor->di_esquerda
    			                                ." AND di_ni_patrocinador !={$this->distribuidor->di_id} "
											)->row();
  
    	
    	$this->total_indicacoes_indireta = $indicaoes_indireta_esquerda->quantidade + $indicaoes_indireta_direita->quantidade;
    	
    	
    	$this->indicacao_direita  = !empty($indicaoes_indireta_direita->quantidade)?$indicaoes_indireta_direita->quantidade:0;
    	$this->indicacao_esquerda  = !empty($indicaoes_indireta_esquerda->quantidade)?$indicaoes_indireta_esquerda->quantidade:0;
    }

}



?>