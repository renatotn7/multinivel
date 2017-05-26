<?php

class AtivacaoMensal {

    private $distribuidor;
    private $db;

    function __construct($distribuidor = null) {
        $this->distribuidor = $distribuidor;
        $this->db = get_instance()->db;
    }

    /**
     * StdClass do banco de dados retornado pelo active record do CI
     * 
     * @param StdClass $distribuidor
     */
    public function setDistribuidor($distribuidor) {
        $this->distribuidor = $distribuidor;
    }    
    
    /**
     * Função checa se o distribuidor está ativo ou inativo.
     * 
     * @return boolean true para ativo e false para inativo
     */
    public function checarAtivacao($dias = 0) {
          $dataAtivacao = self::getDiaAtivacao($this->distribuidor->di_id);

        $ativacao = get_instance()->db
                        ->where('at_distribuidor', $this->distribuidor->di_id)
                        ->where('at_data >=', $dataAtivacao.' 00:00:00')
                        ->get('registro_ativacao')->row();

        if (count($ativacao) > 0) {
            return (int) 1;
        } else {
            return (int) 0;
        }
    }

    /**
     * Retorna a data de vencimento da ativação do distribuidor
     * 
     * @param string $format Informe um formato para o retorno da data Ex: d/m/Y ou Y-m-d. 
     * Se nenhum formato for adicionado o padrão é Y-m-d
     * @return string date no formato Y-m-d
     */
    public function getDataVencimentoAtivacao($format=null) {
        $format = $format==null?'Y-m-d':$format;
        $dia = $this->getDiaLimiteAtivacao(); //dia limite
        return date($format,strtotime(date("Y-m-{$dia}"))); //data limite para ativacao
    }

    /**
     * Retorna o dia que vence a ativação do distribuidor
     * 
     * @return int dia que vence a ativação do distribuidor
     */
    public function getDiaLimiteAtivacao() {
        $qtde_meses = $this->db
                        ->select("TIMESTAMPDIFF(MONTH , di_data_cad, now()) AS qtde_meses")
                        ->where("di_id", $this->distribuidor->di_id)
                        ->get("distribuidores")->row()->qtde_meses;

        $qtde_dias = $qtde_meses * 30;

        //retorna dia
        return date('d', strtotime("+{$qtde_dias} days", strtotime($this->distribuidor->di_data_cad)));
    }
    
    /**
     * Informe o ID do distribuidor para carregar na classe.
     * 
     * @param int $diDistribuidor
     */
   public function setDistribuidorPorId($diDistribuidor){
       $this->distribuidor = get_instance()->db->where('di_id',$diDistribuidor)->get('distribuidores')->row();
   } 
   
   /**
    * Retorna a data do fechamento do clico de ativação anterior.
    * 
    * @return string date no formato Y-m-d
    */
   public function getDataVencimentoAnterior(){
        $data_limite = $this->getDataVencimentoAtivacao();
        return date('Y-m-d',  strtotime('-1 month',  strtotime($data_limite)));
   }
   
   
       public function getDataCadastro($idDistribuidor) {

        $dataCadastro = get_instance()->db
                        ->select('co_data_compra')
                        ->where('co_pago', 1)
                        ->where('co_eplano', 1)
                        ->where('co_id_distribuidor', $idDistribuidor)
                        ->get('compras')->row();
        if (count($dataCadastro)) {
            return $dataCadastro->co_data_compra;
        } else {
            return false;
        }
    }

    public static function dataCadastro($id_distribuidor) {
        return self::getDataCadastro($id_distribuidor);
    }

    public static function getDiaAtivacao($id_distribuidor) {
        $dataCadastro = self::getDataCadastro($id_distribuidor);


        $ativacoesMensal = $dataCadastro;
        $anterior = $dataCadastro;
        while ($ativacoesMensal <= date('Y-m-d')) {
            $time = strtotime($ativacoesMensal);
            $anterior = $ativacoesMensal;
            $ativacoesMensal = date('Y-m-d', mktime(0, 0, 0, date('m', $time) + 1, date('d', $time), date('Y', $time)));
        }

        $timeDataAtivacao = strtotime($anterior);
        return self::data_ativacao_valida(date('d', $timeDataAtivacao), date('m', $timeDataAtivacao), date('Y', $timeDataAtivacao));
    }
    
        //Retorna a data valida de uma parcela no mês	 
    public function data_ativacao_valida($d, $_mes, $_ano) {
        for ($i = $d; $i >= 1; $i--) {
            $dia_d = $i <= 9 ? '0' . ($i + 0) : $i;
            if (checkdate($_mes, $dia_d, $_ano)) {
                return "{$_ano}-{$_mes}-{$dia_d}";
            }
        }
    }

}
