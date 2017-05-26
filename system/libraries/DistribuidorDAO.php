<?php

/**
 * Description of DistribuidorDAO
 *
 * @author Ronildo
 */
class DistribuidorDAO {

    private $db;

    function __construct() {
        $ci = & get_instance();
        $this->db = $ci->db;
    }

    public function getById($idDistribuidor) {
        $objDistribuidorBanco = $this->db->where('di_id', $idDistribuidor)->get('distribuidores')->row();
        if (count($objDistribuidorBanco)) {
            return $this->_initDados($objDistribuidorBanco);
        } else {
            return new DistribuidorModel();
        }
    }

    /**
     *
     * @param type $id_distribuidor
     * @param Distribuidor $d
     */
    private function _initDados($objetoBancoDados) {
        //Criando o Objeto que será retornado
        $objDistribuidor = new DistribuidorModel();


        //Dados do Banco
        $distribuidor = $objetoBancoDados;

        $objDistribuidor->setUsuario(0);

        //Passando os dados para o Objeto
        $objDistribuidor->setId($distribuidor->di_id);
        $objDistribuidor->setUsuario($distribuidor->di_usuario);
        $objDistribuidor->setPatrocinador($this->_getUsuarioSemPatrocinador($distribuidor->di_ni_patrocinador));
        $objDistribuidor->setEsquerda($this->_getUsuarioSemPatrocinador($distribuidor->di_esquerda));
        $objDistribuidor->setDireita($this->_getUsuarioSemPatrocinador($distribuidor->di_direita));
        $objDistribuidor->setLadoPreferencial($distribuidor->di_lado_preferencial);
        $objDistribuidor->setNome($distribuidor->di_nome);
        $objDistribuidor->setSexo($distribuidor->di_sexo);
        $objDistribuidor->setTipoPessoa($distribuidor->di_pessoa);
        $objDistribuidor->setRg($distribuidor->di_rg);
        $objDistribuidor->setCpf($distribuidor->di_cpf);
        $objDistribuidor->setEstadoCivil($distribuidor->di_estado_civil);
        $objDistribuidor->setPiss($distribuidor->di_inss_pis);
        $objDistribuidor->setDataNascimento($distribuidor->di_data_nascimento);
        $objDistribuidor->setAtivo($this->_estaAtivo($distribuidor->di_id));
        $objDistribuidor->setBinario($this->_getBinario($distribuidor->di_id));
        $objDistribuidor->setEmail($distribuidor->di_email);

        return $objDistribuidor;
    }

    /**
     * Essa função traz um usuáro sem os aoutros usuários agregados
     * fazendo com que não traga toda a rede.
     */
    private function _getUsuarioSemPatrocinador($id_patrocinador) {

        //Criando Objeto que será retornado
        $objPatrocinador = new DistribuidorModel();

        //Obtendo os dados do patrocinador
        $distribuidor = $this->db->where('di_id', $id_patrocinador)->get('distribuidores')->row();

        if (count($distribuidor) == 0) {
            return $objPatrocinador;
        }

        //Passando os dados para o Objeto
        $objPatrocinador->setId($distribuidor->di_id);
        $objPatrocinador->setUsuario($distribuidor->di_usuario);
        $objPatrocinador->setPatrocinador(0);
        $objPatrocinador->setEsquerda(0);
        $objPatrocinador->setDireita(0);
        $objPatrocinador->setLadoPreferencial($distribuidor->di_lado_preferencial);
        $objPatrocinador->setNome($distribuidor->di_nome);
        $objPatrocinador->setSexo($distribuidor->di_sexo);
        $objPatrocinador->setTipoPessoa($distribuidor->di_pessoa);
        $objPatrocinador->setRg($distribuidor->di_rg);
        $objPatrocinador->setCpf($distribuidor->di_cpf);
        $objPatrocinador->setEstadoCivil($distribuidor->di_estado_civil);
        $objPatrocinador->setPiss($distribuidor->di_inss_pis);
        $objPatrocinador->setDataNascimento($distribuidor->di_data_nascimento);
        $objPatrocinador->setAtivo($this->_estaAtivo($distribuidor->di_id));
        $objPatrocinador->setBinario(0);
        $objPatrocinador->setEmail($distribuidor->di_email);

        return $objPatrocinador;
    }

    /**
     * Retorna se o Distribuidor Ativou O binário, para receber bônus prime
     * @param int $id_distribuidor
     * @return boolean
     */
    private function _getBinario($id_distribuidor) {
        $ativou_binario = $this->db
                        ->where('db_distribuidor', $id_distribuidor)
                        ->get('registro_distribuidor_binario')->row();
        if (count($ativou_binario) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function getCartao($idDistribuidor) {
        $cartes_r = get_instance()->db
                        ->query("select (cartoes_membership.cm_nome) cartoes, count(cm_id) as total from cartoes_distribuidor
                                join cartoes_membership on cd_id_cartao=cm_id
                                join compras on cd_compra=co_id
                                where cd_distribuidor = {$idDistribuidor}
                                group by cm_id")->result();
        $cartões = '';
        foreach ($cartes_r as $c) {
            $cartões.=$c->cartoes . ' total:' . $c->total;
            $cartões.='<br>';
        }

        return $cartões;
    }

    public static function getProdutoEscolhido($compra_id_produto_escolhido) {

        if ($compra_id_produto_escolhido == 0) {
            return "Pendente";
        }

        $produto_escolhido = get_instance()->db
                        ->where('pe_id', $compra_id_produto_escolhido)
                        ->get('produtos_escolha_entrega')->row();

        return $produto_escolhido->pe_descricao;
    }

    public static function getPlanoNaoPago($idDistribuidor) {
        $plano = get_instance()->db
                        ->query("select planos.* from distribuidores
                            join compras on co_id_distribuidor  = di_id  and co_eplano=1
                            join planos  on co_id_plano = pa_id
                            where co_id_distribuidor={$idDistribuidor} and co_eupgrade =0")->row();

        return $plano;
    }

    public static function getPlano($idDistribuidor) {
        $plano = get_instance()->db
                        ->select(array('sql_cache planos.*'), false)
                        ->where('ps_distribuidor', $idDistribuidor)
                        ->join('planos', 'pa_id=ps_plano')
                        ->order_by('ps_data', 'DESC')
                        ->get('registro_planos_distribuidor', 1)->row();

        return $plano;
    }

    public static function getPlanoPorUsuario($usuario) {
        $plano = get_instance()->db
                ->join('registro_planos_distribuidor', 'pa_id = ps_plano')
                ->join('distribuidores', 'di_id = ps_distribuidor')
                ->where('di_usuario', $usuario)
                ->get('planos')
                ->row();
        return $plano;
    }

    public static function getDataCadastro($idDistribuidor) {

        $compra = get_instance()->db->where('co_id_distribuidor', $idDistribuidor)
                        ->order_by('co_data_compra', 'asc')
                        ->get('compras', 1)->row();

        return count($compra) > 0 ? $compra->co_data_compra : null;
    }

    public static function getEstado($idestado) {
        $pais = get_instance()->db
                        ->where('es_id', $idestado)
                        ->get('estados', 1)->row();

        return $pais;
    }

    public static function getCidade($idcidade) {
        $pais = get_instance()->db
                        ->where('ci_id', $idcidade)
                        ->get('cidades', 1)->row();

        return $pais;
    }

    public static function getPais($idcidade) {
        $pais = get_instance()->db->
                        select(array(' sql_cache pais.*'), false)
                        ->where('ci_id', $idcidade)
                        ->join('pais', 'ps_id=ci_pais')
                        ->get('cidades', 1)->row();

        return $pais;
    }

    public static function getMoeda($idpais) {
        $moeda = get_instance()->db->
                        select('ps_moeda')
                        ->where('ps_id', $idpais)
                        ->get('pais', 1)->row();

        return $moeda;
    }

    public static function getPatrocinador($distribuidor = array()) {
        $ci = get_instance();

        if (count($distribuidor) == 0) {
            return array();
        }

        $patrocinador = $ci->db->query("select * from distribuidores where
                                       di_id=" . $distribuidor->di_ni_patrocinador)->row();

        return $patrocinador;
    }

    public static function getAgenciaAdquiridaNaoAtivo($distribuidor = array()) {
        $ci = get_instance();

        if (count($distribuidor) == 0) {
            return array();
        }

        $primeiroPlano = $ci->db->query("select * from planos "
                        . "JOIN compras ON pa_id = co_id_plano "
                        . "where co_id_distribuidor = " . $distribuidor->di_id
                        . " limit 1")->row();

        return $primeiroPlano;
    }

    public static function getAgenciaAdquirida($distribuidor = array()) {
        $ci = get_instance();

        if (count($distribuidor) == 0) {
            return array();
        }

        $primeiroPlano = $ci->db->query("select * from registro_planos_distribuidor
                                       join planos on pa_id = ps_plano where
                                       ps_distribuidor=" . $distribuidor->di_id . " limit 1")->row();

        return $primeiroPlano;
    }

    public static function getUpgrades($distribuidor = array()) {
        $ci = get_instance();

        if (count($distribuidor) == 0) {
            return array();
        }

        $upgrades = $ci->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_eupgrade', 1)
                        ->join('planos', 'pa_id =co_id_plano')
                        ->get('compras')->result();

        return $upgrades;
    }

    public function getTitularidade($idDistribuidor) {
        $titularidade = get_instance()->db
                        ->where('hi_distribuidor', $idDistribuidor)
                        ->join('distribuidor_qualificacao', 'hi_qualificacao=dq_id')
                        ->get('historico_qualificacao')->result();

        return $titularidade;
    }

    /**
     * Retorna se o distribuidor está ativo ou não
     * @param int $id_distribuidor
     * @return Planos $objPlano
     */
    private function _estaAtivo($id_distribuidor) {
        $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 6, date('d'), date('Y')));

        $comprou = $this->db
                        ->where('at_data >=', $seisMesesAtras)
                        ->where('at_distribuidor', $id_distribuidor)
                        ->get('registro_ativacao', 1)->row();

        if (count($comprou) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function status_financeiro_bloqueado($di_usuario) {

        if (!empty($di_usuario)) {

            if (in_array($di_usuario, explode(',', conf()->grupo_usuarios))) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function situacaoPrimeiraCompra($di_id = 0) {

        if (empty($di_id)) {
            return array();
        }

        //Verifica se fez upgrade verifica a situação do upgrade
         $compra_situcao = get_instance()->db->select('compra_situacao.*,co_id_produto_escolha_entrega')
                ->where('di_id', $di_id)
                ->where('co_eplano', 1)
                ->where('co_eupgrade', 1)
                ->where('co_id_plano',103)
                ->join('compras', 'co_id_distribuidor = di_id')
                ->join('compra_situacao', 'st_id = co_situacao')
                ->get('distribuidores')
                ->row();

         if(count($compra_situcao)>0){
          return $compra_situcao;
         }


        $compra_situcao = get_instance()->db->select('compra_situacao.*, co_id_produto_escolha_entrega')
                ->where('di_id', $di_id)
                ->where('co_eplano', 1)
                ->where('co_eupgrade', 0)
                ->join('compras', 'co_id_distribuidor = di_id')
                ->join('compra_situacao', 'st_id = co_situacao')
                ->get('distribuidores')
                ->row();

        return $compra_situcao;
    }

    public static function contaverificada($distribuidor = array()) {

        if(ConfigSingleton::getValue('ativar_desativar_verificacao_conta')==0){
            return false;
        }

        if (count($distribuidor) == 0) {
            return false;
        }
         /**
          * Falta incluí as regras.
          */
        $ci = & get_instance();
        $contaVerificada = $ci->db->query("
            select * from distribuidores
					  join cidades on ci_id=di_cidade
					  where
                                          (di_conta_verificada =0 OR di_contrato = 0)
                                          and
					  di_id=" . $distribuidor->di_id . "
					  ")->row();

        if (count(ComprasModel::compra($distribuidor, true, false, true)) > 0) {
            return count($contaVerificada) > 0 ? true : false;
        }

        return false;
    }

}

?>
