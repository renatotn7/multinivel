<?php

class Pontos {

    private $distribuidor;
    private $pontos_esquerda;
    private $pontos_esquerda_diretos;
    private $pontos_direita_diretos;
    private $pontos_direita;
    private $pontos_perna_maior;
    private $pontos_perna_menor;
    private $total_pontos_pagos;
    private $pontos_pagos_array;
    private $pontos_direita_hoje;
    private $pontos_esquerda_hoje;
    private $pontos_esquerda_financiados;
    private $pontos_direita_financiados;
    private $ci;
    private $config;

    /**
     * Responsável por executar todos os metodos que garante que o objeto da class terá as informações necessaria.
     * @access public
     * @param $distribuidor deve ser infromado um objeto de distribuidor
     * @return void
     */
    public function __construct($distribuidor) {
        $this->ci = & get_instance();
        $this->distribuidor = $distribuidor;

        $this->verifica_distribuidor();
        $this->total_pontos_pagos = 0;

        $this->carrega_esquerda();
        $this->carrega_direita();
        $this->carrega_esquerda_hoje();
        $this->carrega_direita_hoje();
        $this->carrega_direita_diretos();
        $this->carrega_esquerda_diretos();

        $this->carrega_direita_financiados();
        $this->carrega_esquerda_financiados();

        $this->carrega_perna_menor();
        $this->carrega_perna_maior();
        $this->total_pontos_pagos();
        $this->pontos_direita_hoje = NULL;
        $this->pontos_esquerda_hoje = NULL;
    }

    public function verifica_distribuidor() {
        if (
                !isset($this->distribuidor->di_id) || !isset($this->distribuidor->di_direita) || !isset($this->distribuidor->di_esquerda)
        ) {
            exit("Informe um distribuidor para que o objeto funcione. User $obj->carregar_distribuidor()");
        }
    }

    public function get_pontos_esquerda_financiados() {
        return number_format($this->pontos_esquerda_financiados, 2);
    }

    public function get_pontos_direita_financiados() {
        return number_format($this->pontos_direita_financiados, 2);
    }

    public function get_pontos_esquerda() {
        return $this->pontos_esquerda;
    }

    public function get_pontos_esquerda_diretos() {
        return $this->pontos_esquerda_diretos;
    }

    public function get_pontos_esquerda_diretos_formatado() {
        return number_format($this->pontos_esquerda_diretos, 2);
    }

    public function get_pontos_direita() {
        return $this->pontos_direita;
    }

    public function get_pontos_direita_diretos() {
        return $this->pontos_direita_diretos;
    }

    public function get_pontos_direita_diretos_formatado() {
        return number_format($this->pontos_direita_diretos, 2);
    }

    public function get_pontos_esquerda_formatado() {
        return number_format($this->pontos_esquerda, 0, ',', '.');
    }

    public function get_pontos_direita_formatado() {
        return number_format($this->pontos_direita, 0, ',', '.');
    }

    public function get_pontos_perna_menor() {
        return $this->pontos_perna_menor;
    }

    public function get_pontos_perna_maior() {
        return $this->pontos_perna_maior;
    }

    public function get_pontos_perna_menor_formatado() {
        return number_format($this->pontos_perna_menor, 0, ',', '.');
    }

    public function get_pontos_pagos() {
        return $this->total_pontos_pagos + 0;
    }

    public function get_pontos_pagos_formatado() {
        return number_format($this->total_pontos_pagos, 0, ',', '.');
    }

    public function pontos_a_pagar() {
        $pontosEsquerda = $this->get_pontos_esquerda() - $this->pontos_esquerda_hoje;
        $pontosDireita = $this->get_pontos_direita() - $this->pontos_direita_hoje;
        if ($pontosEsquerda < $pontosDireita) {
            $pernaMenor = $pontosEsquerda;
        } else {
            $pernaMenor = $pontosDireita;
        }

        return $pernaMenor - $this->total_pontos_pagos;
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

    private function carrega_esquerda_hoje() {

        if ($this->distribuidor->di_esquerda > 0) {
            $pontos_esquerda_hoje = $this->ci->db->query("
				 SELECT SQL_CACHE SUM(co_total_pontos) pontos FROM `distribuidor_ligacao` 
				 JOIN distribuidores ON di_id = `li_id_distribuidor`
				 JOIN compras ON di_id = `co_id_distribuidor`
				 WHERE `li_no` =  " . $this->distribuidor->di_esquerda . "
				 AND co_pago = 1
				 AND co_data_compra LIKE '" . date('Y-m-d') . "%'
				")->row();

            $this->pontos_esquerda_hoje = $pontos_esquerda_hoje->pontos;
        } else {
            $this->pontos_esquerda_hoje = 0;
        }
    }

    public function carrega_direita_hoje() {

        if ($this->distribuidor->di_direita > 0) {
            $pontos_direita_hoje = $this->ci->db->query("
			 SELECT SQL_CACHE SUM(co_total_pontos) pontos FROM `distribuidor_ligacao` 
				 JOIN distribuidores ON di_id = `li_id_distribuidor`
				 JOIN compras ON di_id = `co_id_distribuidor`
				 WHERE `li_no` =  " . $this->distribuidor->di_direita . "
				 AND co_pago = 1
				 AND co_data_compra = '" . date('Y-m-d') . "'
			")->row();

            $this->pontos_direita_hoje = $pontos_direita_hoje->pontos;
        } else {
            $this->pontos_direita_hoje = 0;
        }
    }

    /**
     * Carregando o valor do atributo pontos_esquerda
     * @access private
     * @return void
     */
    public function carrega_esquerda() {

        if ($this->distribuidor->di_esquerda > 0) {
            $pontos_esquerda = $this->ci->db->query("
                                                    SELECT SQL_CACHE SUM(co_total_pontos) pontos
                                                    FROM `distribuidor_ligacao` 
                                                        JOIN distribuidores ON di_id = `li_id_distribuidor`
                                                        JOIN compras ON di_id = `co_id_distribuidor`
                                                    WHERE `li_no` =  " . $this->distribuidor->di_esquerda . "
                                                        AND co_pago = 1
                                                      
                                                    ")->row();

            $this->pontos_esquerda = $pontos_esquerda->pontos;
        } else {
            $this->pontos_esquerda = 0;
        }
    }

    /**
     * Carregando o valor do atributo pontos_esquerda
     * @access private
     * @return void
     */
    public function carrega_direita_financiados() {

        if ($this->distribuidor->di_direita > 0) {
            $pontos_esquerda = $this->ci->db->query("
                                                    SELECT SQL_CACHE 
                                                       SUM((select count(cof_pontos) from compras_financiamento where cof_pago=0 and cof_id_compra=co_id)
                                                    *(select cof_pontos from compras_financiamento where cof_pago=0 and cof_id_compra=co_id limit 1) )  
                                                    as pontos
                                                    FROM `distribuidor_ligacao` 
                                                        JOIN distribuidores ON di_id = `li_id_distribuidor`
                                                        JOIN compras ON di_id = `co_id_distribuidor`
                                                    WHERE `li_no` =  " . $this->distribuidor->di_direita . "
                                                        AND co_pago = 1
                                                        AND co_parcelado =1
                                                      
                                                    ")->row();

            $this->pontos_direita_financiados = $pontos_esquerda->pontos;
        } else {
            $this->pontos_direita_financiados = 0;
        }
    }

    /**
     * Carregando o valor do atributo pontos_esquerda
     * @access private
     * @return void
     */
    public function carrega_esquerda_financiados() {

        if ($this->distribuidor->di_esquerda > 0) {
            $pontos_esquerda = $this->ci->db->query("
                                                    SELECT SQL_CACHE
                                                    SUM((select count(cof_pontos) from compras_financiamento where cof_pago=1 and cof_id_compra=co_id)
                                                    *(select cof_pontos from compras_financiamento where cof_pago=1 and cof_id_compra=co_id limit 1) ) 
                                                    as pontos
                                                    FROM `distribuidor_ligacao` 
                                                        JOIN distribuidores ON di_id = `li_id_distribuidor`
                                                        JOIN compras ON di_id = `co_id_distribuidor`
                                                    WHERE `li_no` =  " . $this->distribuidor->di_esquerda . "
                                                        AND co_pago = 1
                                                        AND co_parcelado =1
                                                      ")->row();

            $this->pontos_esquerda_financiados = $pontos_esquerda->pontos;
        } else {
            $this->pontos_esquerda_financiados = 0;
        }
    }

    /**
     * Carrga os pontos diretos da pena esquerda
     */
    public function carrega_esquerda_diretos() {

        if ($this->distribuidor->di_esquerda > 0) {
            $pontos_esquerda = $this->ci->db->query("
                                                    SELECT SQL_CACHE SUM(co_total_pontos) pontos
                                                    FROM `distribuidor_ligacao` 
                                                        JOIN distribuidores ON di_id = `li_id_distribuidor`
                                                        JOIN compras ON di_id = `co_id_distribuidor`
                                                    WHERE `li_no` =  " . $this->distribuidor->di_esquerda . "
                                                         AND di_ni_patrocinador = " . $this->distribuidor->di_id . "
                                                         AND co_pago = 1
                                                         and di_excluido=0
                                                         and co_total_valor !=0.00
                                                         
                                                      
                                                    ")->row();


            $this->pontos_esquerda_diretos = (INT)$pontos_esquerda->pontos;
        } else {
            $this->pontos_esquerda_diretos = (INT)0;
        }
    }

    /**
     * Carregando o valor do atributo pontos_direita
     * @access private
     * @return void
     */
    private function carrega_direita_diretos() {

        if ($this->distribuidor->di_direita > 0) {
            $pontos_direita = $this->ci->db->query("
                                                    SELECT SQL_CACHE SUM(co_total_pontos) pontos
                                                    FROM `distribuidor_ligacao` 
                                                        JOIN distribuidores ON di_id = `li_id_distribuidor`
                                                        JOIN compras ON di_id = `co_id_distribuidor`
                                                    WHERE `li_no` =  " . $this->distribuidor->di_direita .
                                                    " AND di_ni_patrocinador = " . $this->distribuidor->di_id . "
                                                     AND co_pago = 1
                                                     and di_excluido=0
                                                     and co_total_valor!=0.00
                                                    
                                                      
                                                    ")->row();


            $this->pontos_direita_diretos = (INT)$pontos_direita->pontos;
        } else {
            $this->pontos_direita_diretos = (INT) 0;
        }
    }

    /**
     * Carregando o valor do atributo pontos_direita
     * @access private
     * @return void
     */
    private function carrega_direita() {

        if ($this->distribuidor->di_direita > 0) {
            $pontos_direita = $this->ci->db->query("
                                                    SELECT SQL_CACHE SUM(co_total_pontos) pontos
                                                    FROM `distribuidor_ligacao` 
                                                        JOIN distribuidores ON di_id = `li_id_distribuidor`
                                                        JOIN compras ON di_id = `co_id_distribuidor`
                                                    WHERE `li_no` =  " . $this->distribuidor->di_direita . "
                                                        AND co_pago = 1
                                                      
                                                    ")->row();


            $this->pontos_direita = $pontos_direita->pontos;
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
        
        if ($this->pontos_esquerda < $this->pontos_direita) {
            $this->pontos_perna_menor = $this->pontos_esquerda;
        } else {
            $this->pontos_perna_menor = $this->pontos_direita;
        }
    }

    /**
     * Funcção que informa a class qual a perna com maior quantidades de pontos
     * @access private
     * @return void
     */
    private function carrega_perna_maior() {
        if ($this->pontos_esquerda > $this->pontos_direita) {
            $this->pontos_perna_maior = $this->pontos_esquerda;
        } else {
            $this->pontos_perna_maior = $this->pontos_direita;
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

        $this->total_pontos_pagos = $p_pagos->pontos;
    }

    /**
     * Funcção que informa o atributo total_pontos_pagos qual o total de pontos pagos
     * @access private
     * @return void
     */
    private function pontos_pagos_array() {
        $p_pagos = $this->ci->db
                        ->where('pg_distribuidor', $this->distribuidor->di_id)
                        ->get('pontos_pagos')->result();
        $this->pontos_pagos_array = $p_pagos;
    }

}
