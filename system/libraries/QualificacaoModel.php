<?php

class QualificacaoModel {

    private $distribuidor;
    private $quantidadePernaMenor;
    private $pontosEsquerda;
    private $pontosDireita;
    private $db;

    function setDistribuidor($distribuidor) {
        $this->distribuidor = $distribuidor;
        $ci = & get_instance();
        $this->db = $ci->db;

        $this->pontosEsquerda();
        $this->pontosDireita();
        $this->carregaPernaMenor();
    }

    function getQuantidadePernaMenor() {
        return $this->quantidadePernaMenor;
    }

    private function carregaPernaMenor() {
        if ($this->pontosDireita < $this->pontosEsquerda) {
            $this->quantidadePernaMenor = $this->pontosDireita;
        } else {
            $this->quantidadePernaMenor = $this->pontosEsquerda;
        }
    }

    public function pontosDireita() {
        if ($this->pontosDireita == NULL) {
            if ($this->distribuidor->di_direita > 0) {
                $sqlDireita = $this->db->query("
                        SELECT SQL_CACHE SUM(co_total_pontos) as pontos
                        FROM `distribuidor_ligacao` 
                            JOIN distribuidores ON di_id = `li_id_distribuidor`
                            JOIN compras ON di_id = `co_id_distribuidor`
                        WHERE `co_pago` = 1
                            AND `li_no` =  " . $this->distribuidor->di_direita . "
			")->row();
                $this->pontosDireita = (int) $sqlDireita->pontos;
            } else {
                $this->pontosDireita = 0;
            }
        }

        return $this->pontosDireita;
    }

    public function pontosEsquerda() {
        if ($this->pontosEsquerda == NULL) {
            if ($this->distribuidor->di_esquerda > 0) {
                $sqlEsquerda = $this->db->query("
                                SELECT SQL_CACHE SUM(co_total_pontos) pontos
                                FROM `distribuidor_ligacao` 
                                    JOIN distribuidores ON di_id = `li_id_distribuidor`
                                    JOIN compras ON di_id = `co_id_distribuidor`
                                WHERE `co_pago` = 1
                                    AND `li_no` =  " . $this->distribuidor->di_esquerda . "
				")->row();
                $this->pontosEsquerda = (int) $sqlEsquerda->pontos;
            } else {
                $this->pontosEsquerda = 0;
            }
        }
        return $this->pontosEsquerda;
    }
    //Ajustando a qualificacao para retirar os repetido
    public function ajustar_qualificacao(){
        $qualificacaos =  $this->db->get('distribuidor_qualificacao')->result();
        foreach ($qualificacaos as $qualificacao) {
           //Checa se tem mais de um registor de qualificacao repetido.
             $quantidade_qualificacao = $this->db->select('SQL_CACHE count(hi_qualificacao) as total',false)
                                            ->where('hi_qualificacao',$qualificacao->dq_id)
                                           ->where('hi_distribuidor',$this->distribuidor->di_id)
                                          ->get('historico_qualificacao')->row();
             
             if($quantidade_qualificacao->total>1){
                //Escluindo o repetido de acordo com a ultima data em decrecente.
                $total=($quantidade_qualificacao->total-1);
                $this->db->query("DELETE FROM historico_qualificacao "
                            . "WHERE hi_qualificacao ='{$qualificacao->dq_id}'"
                            . "and hi_distribuidor= {$this->distribuidor->di_id}"
                            . " ORDER BY hi_data desc LIMIT {$total};");
                
             }
        }
    }
    
    public function eQualificado($idQualificacao, $pontos = NULL) {
        if ($pontos == NULL) {

            $qualificacao = $this->db
                    ->where('dq_id', $idQualificacao)
                    ->get('distribuidor_qualificacao')
                    ->row();

            if (count($qualificacao) == 0) {
                echo "<p>Qualificacao nao encontrada. ID: {$idQualificacao} DIS: {$this->distribuidor->di_usuario} </p>";
            } else {
                $pontosNecessarios = (float) $qualificacao->dq_pontos;
            }

            //$pontoParaAtingirAQualificacao = (int)$pontosNecessarios - (int) $this->getQuantidadePernaMenor();
            //echo "<p>D: {$this->distribuidor->di_usuario}  ... Qualificacao: {$idQualificacao} ... Pt: {$pontosNecessarios} .... Menor: ".$this->getQuantidadePernaMenor()." ... Status:".((int)(($pontoParaAtingirAQualificacao <= 0)))."</p>";
        } else {
            $pontosNecessarios = $pontos;
        }

        $pontoParaAtingirAQualificacao = (int) $pontosNecessarios - (int) $this->getQuantidadePernaMenor();
        return $pontoParaAtingirAQualificacao <= 0;
    }

    function executar() {

        // Lista todas as qualificações disponiveis para o associado
        $arrayQualificacoes = $this->db->query("
                        SELECT SQL_CACHE * FROM distribuidor_qualificacao
			")->result();
        foreach ($arrayQualificacoes as $stdQualificacao) {
            // Checa e registra upgrade de qualificação do associado
            if ($this->eQualificado($stdQualificacao->dq_id, $stdQualificacao->dq_pontos)) {
                //Ajustando a qualificacao removendo os inumeros registros que tinha e deixando o mais antigo.
               // $this->ajustar_qualificacao();
                //Checa se a qualificação já existe para o usuario. 
                $qualificacao  = $this->db->select('SQL_CACHE hi_qualificacao',false)->where('hi_qualificacao',$stdQualificacao->dq_id)
                                          ->where('hi_distribuidor',$this->distribuidor->di_id)
                                          ->get('historico_qualificacao')->row();
                
               if(count($qualificacao)==0)
                { 
                    // Registra upgrade
                    $this->db->insert('historico_qualificacao', array(
                        'hi_data' => date('Y-m-d'),
                        'hi_distribuidor' => $this->distribuidor->di_id,
                        'hi_qualificacao' => $stdQualificacao->dq_id
                    ));

                    $this->db->where('di_id', $this->distribuidor->di_id)
                            ->update('distribuidores', array(
                                'di_qualificacao' => $stdQualificacao->dq_id));
               }
               
            }else{
                $this->db
                        ->where('hi_distribuidor',$this->distribuidor->di_id)
                        ->where('hi_qualificacao',$stdQualificacao->dq_id)
                        ->delete('historico_qualificacao');
            }
        }//Foreach que verifica a qualificação
        
        self::atualizaTabelaDistribuidor($this->distribuidor->di_id);
    }

//Fim da função executar

    public function atualizaTabelaDistribuidor($idDistribuidor){
        $qualificacao = get_instance()->db->select('SQL_CACHE historico_qualificacao.*',false)
                                         ->where('hi_distribuidor',$idDistribuidor)
                                          ->order_by('dq_pontos','DESC')
                                          ->join('distribuidor_qualificacao','dq_id = hi_qualificacao')
                                          ->get('historico_qualificacao')
                                          ->row();
       
        if(count($qualificacao) > 0){
            get_instance()->db->where('di_id',$idDistribuidor)->update('distribuidores',array(
                'di_qualificacao'=>$qualificacao->hi_qualificacao
            ));
        }
    }
    
    public function clear() {
        $this->pontosDireita = NULL;
        $this->pontosEsquerda = NULL;
        $this->quantidadePernaMenor = NULL;
    }
    

}
