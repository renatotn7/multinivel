<?php

class Planos {

    private $ci;
    private $id;
    private $descricao;
    private $valorAnual;
    private $quantidadeCombo;
    private $idCombo;
    private $idKit;
    private $valorMensalidade;
    private $quantidadeComboMensal;
    private $valorIndicacao;
    private $valorResidual;
    private $quantidadePrimes;

    function __construct() {
        $this->ci = & get_instance();
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getValorAnual() {
        return $this->valorAnual;
    }

    public function setValorAnual($valorAnual) {
        $this->valorAnual = $valorAnual;
    }

    public function getQuantidadeCombo() {
        return $this->quantidadeCombo;
    }

    public function setQuantidadeCombo($quantidadeCombo) {
        $this->quantidadeCombo = $quantidadeCombo;
    }

    public function getIdCombo() {
        return $this->idCombo;
    }

    public function setIdCombo($idCombo) {
        $this->idCombo = $idCombo;
    }

    public function getIdKit() {
        return $this->idKit;
    }

    public function setIdKit($idKit) {
        $this->idKit = $idKit;
    }

    public function getValorMensalidade() {
        return $this->valorMensalidade;
    }

    public function setValorMensalidade($valorMensalidade) {
        $this->valorMensalidade = $valorMensalidade;
    }

    public function getQuantidadeComboMensal() {
        return $this->quantidadeComboMensal;
    }

    public function setQuantidadeComboMensal($quantidadeComboMensal) {
        $this->quantidadeComboMensal = $quantidadeComboMensal;
    }

    public function getValorIndicacao() {
        return $this->valorIndicacao;
    }

    public function setValorIndicacao($valorIndicacao) {
        $this->valorIndicacao = $valorIndicacao;
    }

    public function getValorResidual() {
        return $this->valorResidual;
    }

    public function setValorResidual($valorResidual) {
        $this->valorResidual = $valorResidual;
    }

    public function getQuantidadePrimes() {
        return $this->quantidadePrimes;
    }

    public function setQuantidadePrimes($quantidadePrimes) {
        $this->quantidadePrimes = $quantidadePrimes;
    }

    public function lancar($compra, $beneficios = true) {

        #Dados do Distribuidor  
        $distribuidor = $this->ci->db
                        ->select(array('di_id', 'di_usuario', 'di_ni_patrocinador'))
                        ->where('di_id', $compra->co_id_distribuidor)
                        ->get('distribuidores')->row();

        #Dados do Patrocinador	
        $patrocinador = $this->ci->db
                        ->join('registro_distribuidor_binario', 'db_distribuidor=di_id', 'left')
                        ->select(array('di_id', 'di_binario', 'di_usuario', 'di_esquerda', 'di_direita', 'db_distribuidor'))
                        ->where('di_id', $distribuidor->di_ni_patrocinador)
                        ->get('distribuidores')->row();

        #Obter o plano
        $plano = $this->ci->db
                        ->where('pa_id', $compra->co_id_plano)
                        ->get('planos')->row();


        if($compra->co_id_plano != 0){
        //Registrando a compra do plano
        $this->ci->db->insert('registro_planos_distribuidor', array(
            'ps_distribuidor' => $compra->co_id_distribuidor,
            'ps_plano' => $compra->co_id_plano,
            'ps_valor' => $compra->co_total_valor,
            'ps_compra' => $compra->co_id
        ));
        }


        //Verifica se ativou o binário do patrocinador
        if (count($patrocinador) > 0) {
            if ($patrocinador->db_distribuidor == '' || ($patrocinador->db_distribuidor + 0) == 0) {
                $this->se_tornou_prime($patrocinador);
            }
        }
    }

    /*
     * Função verifica se o distribuidor se ativou o prime para recebimento dos bônus
     * @param StdClass $patrocinador, Objeto de Patrocinador
     * @return void
     */

    private function se_tornou_prime($patrocinador) {
        $objBinario = new Binario($patrocinador);
    }

}
