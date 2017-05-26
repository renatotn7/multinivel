<?php

/**
 * Trabalha com os pontos a serem pagos no Bônus Binário.
 * Os pontos diponíveis para o bônus binário é a soma de todos os pontos até a 8ª geração
 * da perna menor.
 * A perna menor é definida pela perna que soma a menor pontuação.
 * 
 * @author Ronyldo110120
 */
class PontosBonusBinario {

    private $db;
    private $distribuidor;
    private $esquerda;
    private $direita;
    private $pernaMenor;
    private $posicao;
    private $posicaoLimite;
    private $pontosPagos;

    public function __construct() {
        $this->db = get_instance()->db;
        $this->posicaoLimite = 8;
    }

    /**
     * Informa o distribuidor
     * @param stdClass $distribuidor Distribuidor retornado pela class DB do Code Igniter
     */
    public function setDistribuidor($distribuidor) {
        $this->distribuidor = $distribuidor;
        $this->direita = 0;
        $this->esquerda = 0;
        $this->posicao = 0;
        $this->pernaMenor = 0;
        $this->pontosPagos = 0;
        $this->posicao = 0;
        $this->esquerda();
        $this->direita();
        $this->pontosPagos();
        $this->pernaMenor();
    }

    /**
     * Calcula e retorna a quantidade de pontos na perna esquerda do distribuidor
     * até a 8ª geração.
     * 
     * @return int Soma dos pontos da perna esquerda até a 8ª geração
     */
    public function esquerda() {
        if ($this->distribuidor->di_esquerda == 0) {
            $this->esquerda = 0;
        }
        if ($this->esquerda == 0 && $this->distribuidor->di_esquerda > 0) {
            $rs = $this->db
                            ->query("SELECT SQL_CACHE SUM(co_total_pontos) pontos
                          FROM distribuidor_ligacao 
                          JOIN distribuidores ON di_id = li_id_distribuidor
                          JOIN compras ON di_id = `co_id_distribuidor`
                          WHERE li_no =  " . $this->distribuidor->di_esquerda . "
                          AND co_pago = 1
                          ")->row();

            $this->esquerda = $rs->pontos;
        }
        return $this->esquerda;
    }

    /**
     * Calcula e retorna a quantidade de pontos na perna direita do distribuidor
     * até a 8ª geração.
     * 
     * @return int Soma dos pontos da perna direita até a 8ª geração
     */
    public function direita() {

        if ($this->distribuidor->di_direita == 0) {
            $this->direita = 0;
        }
        if ($this->direita == 0 && $this->distribuidor->di_direita > 0) {
            $rs = $this->db
                            ->query("SELECT SQL_CACHE SUM(co_total_pontos) pontos
                          FROM distribuidor_ligacao 
                          JOIN distribuidores ON di_id = li_id_distribuidor
                          JOIN compras ON di_id = `co_id_distribuidor`
                          WHERE li_no =  " . $this->distribuidor->di_direita . "
                          AND co_pago = 1
                          ")->row();

            $this->direita = $rs->pontos;
        }
        return $this->direita;
    }

    /**
     * A perna menor e sempre a perna que soma a menor pontuação.
     * 
     * @return int perna menor
     */
    public function pernaMenor() {
        $this->pernaMenor = $this->direita <= $this->esquerda ? $this->direita : $this->esquerda;
        return $this->pernaMenor;
    }

    /**
     * Carrega a em qual nivel da rede em que o distribuidor está alocado
     */
    private function posicaoDistribuidorNaRede() {
        $rede = $this->db
                        ->select('li_posicao')
                        ->where('li_id_distribuidor', $this->distribuidor->di_id)
                        ->where('li_no', $this->distribuidor->di_id)
                        ->get('distribuidor_ligacao')->row();

        return isset($rede->li_posicao) ? $rede->li_posicao : 0;
    }

    /**
     * Funcção que informa o atributo total_pontos_pagos qual o total de pontos pagos
     * @access private
     * @return void
     */
    public function pontosPagos() {
        $rs = $this->db
                        ->select('SUM(pg_pontos) as pontos')
                        ->where('pg_distribuidor', $this->distribuidor->di_id)
                        ->get('registro_bonus_indireto_pagos')->row();

        $this->pontosPagos = (int) $rs->pontos;
        return $this->pontosPagos;
    }

    public function pontosAPagar() {
        return $this->pernaMenor - $this->pontosPagos;
    }

    /**
     * Retorna um array com a rede do distribuidor até a 8 geração
     */
    public function getRede() {
        $rede = array();
        $rede['E'] = array();
        $rede['D'] = array();
        $geracao = 1;
        $this->posicao = $this->posicaoDistribuidorNaRede();
        $this->posicaoLimite = isset($_GET['p'])?$_GET['p']:8;
        while ($geracao <= $this->posicaoLimite) {

            $distribuidoresEsquerda = $this->db
                            ->select('di_id,di_usuario,di_nome,SUM(co_total_pontos) as pontos')
                            ->join('distribuidores', 'di_id=dl1.li_id_distribuidor')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->where('dl1.li_no', $this->distribuidor->di_esquerda)
                            ->where('co_pago', 1)
                            ->where("(
                                SELECT li_posicao FROM distribuidor_ligacao dl
                                WHERE dl.li_id_distribuidor = dl1.li_id_distribuidor AND dl.li_no = dl1.li_id_distribuidor
                            ) = ", $this->posicao + $geracao, false)
                            ->group_by('di_id')
                            ->get('distribuidor_ligacao dl1')->result();

            $distribuidoresDireita = $this->db
                            ->select('di_id,di_usuario,di_nome,co_id,SUM(co_total_pontos) as pontos')
                            ->join('distribuidores', 'di_id=dl1.li_id_distribuidor')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->where('dl1.li_no', $this->distribuidor->di_direita)
                            ->where('co_pago', 1)
                            ->where("(
                                SELECT li_posicao FROM distribuidor_ligacao dl
                                WHERE dl.li_id_distribuidor = dl1.li_id_distribuidor AND dl.li_no = dl1.li_id_distribuidor
                            ) = ", $this->posicao + $geracao, false)
                            ->group_by('di_id')
                            ->get('distribuidor_ligacao dl1')->result();



            $rede['E'][$geracao]['distribuidores'] = $distribuidoresEsquerda;
            $rede['D'][$geracao]['distribuidores'] = $distribuidoresDireita;

            $geracao++;
        }

        return $rede;
    }

    /**
     * Função auxiliar que imprime os dados do distribuidor
     */
    public function imprimirDados() {
        echo "<br><table border=1 width=50% bordercolor=#f3f3f3 cellspacing=0 cellpadding=5 >";
        echo "<th><td colpan='4'>" . $this->distribuidor->di_usuario . "</td></th>";
        echo "<tr>";
        echo "<td><b>Esquerda</b></td>";
        echo "<td><b>Direita</b></td>";
        echo "<td><b>Menor</b></td>";
        echo "<td><b>A pagar</b></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>" . $this->esquerda() . "</td>";
        echo "<td>" . $this->direita() . "</td>";
        echo "<td>" . $this->pernaMenor() . "</td>";
        echo "<td>" . $this->pontosAPagar() . "</td>";
        echo "</tr>";
        echo "</table>";
    }

}
