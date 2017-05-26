<?php

class PontosParaRelatorio {

    private $distribuidor;
    private $pontos_esquerda;
    private $pontos_direita;
    private $pontos_perna_menor;
    private $total_pontos_pagos;
    private $pontos_pagos_array;
    private $pontos_direita_hoje;
    private $pontos_esquerda_hoje;
    private $ci;

    /**
     * Responsável por executar todos os metodos que garante que o objeto da class terá as informações necessaria.
     * @access public
     * @param $distribuidor deve ser infromado um objeto de distribuidor
     * @return void
     */
    public function __construct($distribuidor,$dia) {
        $this->ci = & get_instance();
        $this->distribuidor = $distribuidor;

        $this->verifica_distribuidor();
        $this->total_pontos_pagos = 0;

        $this->carrega_esquerda_hoje($dia);
        $this->carrega_direita_hoje($dia);
		$this->total_pontos_pagos();
        $this->carrega_perna_menor();
        

        $this->pontos_direita_hoje = NULL;
        $this->pontos_esquerda_hoje = NULL;
    }

    public function verifica_distribuidor() {
        if (
                !isset($this->distribuidor->di_id)
                || !isset($this->distribuidor->di_direita)
                || !isset($this->distribuidor->di_esquerda)
        ) {
            exit("Informe um distribuidor para que o objeto funcione. User $obj->carregar_distribuidor()");
        }
    }

   
 



    public function pontos_a_pagar() {
        return $this->pontos_perna_menor;
    }

    public function direita_hoje() {
        if ($this->pontos_direita_hoje === NULL) {
            $this->carrega_direita_hoje();
        }
        return $this->pontos_direita_hoje;
    }

    public function esquerda_hoje() {
        if ($this->pontos_esquerda_hoje === NULL) {
            $this->carrega_esquerda_hoje();
        }
        return $this->pontos_esquerda_hoje;
    }

    private function carrega_esquerda_hoje($dia) {


        if ($this->distribuidor->di_esquerda > 0) {

            //Atenção: Deve Buscar a QUantidade de Pontos Na tabela de compras 
            //Id distribuidor na tabela compras: co_id_distribuidor
            //Coluna pontos da compra: co_total_pontos 
            $pontos_esquerda_hoje = $this->ci->db->query("
				 SELECT SUM(co_total_pontos) pontos FROM `distribuidor_ligacao` 
				 JOIN distribuidores ON di_id = `li_id_distribuidor`
				 JOIN compras ON di_id = `co_id_distribuidor`
				 WHERE `li_no` =  " . $this->distribuidor->di_esquerda . "
				 AND co_pago = 1 AND co_data_compra LIKE '".$dia."%'
				")->row();
			
			//echo "<p>&nbsp;&nbsp; - Dia {$dia} distribuidor <b>".$this->distribuidor->di_id."</b> total de ".($pontos_esquerda_hoje->pontos+0)." pontos esquerda</p>";	

            $this->pontos_esquerda_hoje = (int) $pontos_esquerda_hoje->pontos;
        } else {
            $this->pontos_esquerda_hoje = 0;
        }
    }

    public function carrega_direita_hoje($dia) {

        if ($this->distribuidor->di_direita > 0) {
            $pontos_direita_hoje = $this->ci->db->query("
			 	SELECT SUM(co_total_pontos) pontos FROM `distribuidor_ligacao` 
				 JOIN distribuidores ON di_id = `li_id_distribuidor`
				 JOIN compras ON di_id = `co_id_distribuidor`
				 WHERE `li_no` =  " . $this->distribuidor->di_direita . "
				 AND co_pago = 1
				 AND co_data_compra LIKE '".$dia."%'
			")->row();
			
			//echo "<p>&nbsp;&nbsp; - Dia {$dia} distribuidor <b>".$this->distribuidor->di_id."</b> total de ".($pontos_direita_hoje->pontos+0)." pontos direita</p>";	

            $this->pontos_direita_hoje = (int) $pontos_direita_hoje->pontos;
        } else {
            $this->pontos_direita_hoje = 0;
        }
    }



    /**
     * Carregando o valor do atributo pontos_direita
     * @access private
     * @param $id_distribuidor_direita Variavel Int que recebe o id do usuario a direita do distribuidor
     * @return void
     */
    private function carrega_direita() {
        if ($this->distribuidor->di_direita > 0) {
            $pontos_direita = $this->ci->db->query("
			  SELECT SUM(co_total_pontos) as pontos FROM `distribuidor_ligacao` 
				 JOIN distribuidores ON di_id = `li_id_distribuidor`
				 JOIN compras ON di_id = `co_id_distribuidor`
				 WHERE `li_no` =  " . $this->distribuidor->di_direita . "
				 AND co_pago = 1
			")->row();

            $this->pontos_direita = (int) $pontos_direita->pontos;
        } else {
            $this->pontos_direita = 0;
        }
    }

    /**
     * Funcção que informa a class qual a perna com menor quantidades de pontos
     * @access private
     * @return void
     */
    private function carrega_perna_menor() {
        if ($this->pontos_esquerda_hoje < $this->pontos_direita_hoje) {
            $this->pontos_perna_menor = $this->pontos_esquerda_hoje;
        } else {
            $this->pontos_perna_menor = $this->pontos_direita_hoje;
        }
    }

    /**
     * Funcção que informa o atributo total_pontos_pagos qual o total de pontos pagos
     * @access private
     * @return void
     */
    private function total_pontos_pagos() {
        $p_pagos = $this->ci->db
                        ->select('SUM(pg_pontos) as pontos')
                        ->where('pg_distribuidor', $this->distribuidor->di_id)
                        ->get('registro_bonus_indireto_pagos')->row();

        $this->total_pontos_pagos = (int) $p_pagos->pontos;
    }

    /**
     * Funcção que informa o atributo total_pontos_pagos qual o total de pontos pagos
     * @access private
     * @return void
     */
    private function pontos_pagos_array($id_distribuidor) {
        $p_pagos = $this->ci->db
                        ->where('pg_distribuidor', $this->distribuidor->di_id)
                        ->get('pontos_pagos')->result();
        $this->pontos_pagos_array = $p_pagos;
    }

}