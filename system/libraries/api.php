<?php

class api {

    private static $token;
    private static $secretKey;
    private static $appID;
    private static $urlOrigem;
    private static $emailLog='system@empresa.com';
    private static $erroCodigo = 0;

    public static function validarMethod() {
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                throw new Exception('Metodo de envio não suportado pela API.');
            }
        } catch (Exception $ex) {
            self::$erroCodigo = 3;
            self::log($ex->getTrace(), $ex->getMessage(), $_REQUEST);
            throw new Exception($ex->getMessage());
        }
    }

    public static function setChaveAcesso($token = '', $secretKey = 0, $appID = 0) {

        try {
            if (empty($token)) {
                self::$erroCodigo = 9;
                throw new Exception('Token não foi encotrada.');
            }

            if (empty($secretKey)) {
                self::$erroCodigo = 8;
                throw new Exception('SecretKey não foi encotrada.');
            }

            if (empty($appID)) {
                self::$erroCodigo = 7;
                throw new Exception('AppID não foi encotrada.');
            }

            self::$token = $token;
            self::$secretKey = $secretKey;
            self::$appID = $appID;
            self::$urlOrigem = '';
        } catch (Exception $exc) {
            self::log($exc->getTrace(), $exc->getMessage(), $_REQUEST);
            throw new Exception($exc->getMessage());
        }
    }

    public static function validarAcesso() {
        try {

            if (!self::validar_token()) {
                self::$erroCodigo = 4;
                throw new Exception('Token inválida.');
            }

            if (!self::validar_secretKey()) {
                self::$erroCodigo = 5;
                throw new Exception('Secret Key inválido.');
            }

            if (!self::validar_AppID()) {
                self::$erroCodigo = 6;
                throw new Exception('AppID inválido.');
            }
        } catch (Exception $exc) {
            self::log($exc->getTrace(), $exc->getMessage(), $_REQUEST);
            throw new Exception($exc->getMessage());
        }
    }

    private static function validar_token() {
        $ci = get_instance();
        $token = $ci->db->where('api_token', self::$token)
                ->get('usuario_api')
                ->row();

        if (count($token) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private static function validar_secretKey() {
        $ci = get_instance();
        $secret = $ci->db->where('api_secret_key', self::$secretKey)
                ->get('usuario_api')
                ->row();

        if (count($secret) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private static function validar_AppID() {
        $ci = get_instance();
        $appid = $ci->db->where('api_app_id', self::$appID)
                ->get('usuario_api')
                ->row();

        if (count($appid) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private static function validar_url() {
        $ci = get_instance();

        $token = $ci->db->where('tk_token', self::$token)
                ->where('api_url', $url)
                ->join('usuario_api', 'api_id=tk_id_usuario')
                ->get('token_api_usuario')
                ->row();

        if (count($token) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function dados_usuario($di_niv = 0, $di_email = '') {
        $ci = get_instance();
        $dados_distribuidores = array();
        $agencia = array();
        
        try {

            if (empty($di_niv)) {
                self::$erroCodigo = 1;
                throw new Exception('Não encontramos o Niv do usuário');
            }

            if (empty($di_email)) {
                self::$erroCodigo = 2;
                throw new Exception('Não encontramos o e-email');
            }

            $distribuidores = $ci->db->select('di_nome as name,'
                                    . ' di_ultimo_nome as lastName,'
                                    . ' di_usuario as login,'
                                    . ' IF(di_sexo="M",1,2) as gender,'
                                    . ' di_usuario_patrocinador as sponsor,'
                                    //Niv distribuidor
                                    . ' (select di_niv from distribuidores as d where d.di_id = dis.di_ni_patrocinador) as sponsorNiv,'
                                    //Agencia atual.
                                    . ' (select pa_id_api from planos as p where p.pa_id = c.co_id_plano) as CurrentAgency,'
                                    //Tipo de distribuidor
                                    . ' IF((select end_id from distribuidores_endereco where end_id_distribuidor=dis.di_id),2,1) as registertype,'
                                    //situação do login
                                    . ' IF(c.co_pago=1,1,2) as loginsituation,'
                                    //verifica a ativação do usuário.
                                    . ' IF(c.co_pago=0,3, IF((select at_data from registro_ativacao where at_distribuidor=dis.di_id and at_data  > DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) limit 1),1,2)) as MonthlyActivation,'
                                    //Escolha do produto
                                    . ' (select pe_id from produtos_escolha_entrega where pe_id=c.co_id_produto_escolha_entrega) as SelectedProduct, '
                                    . ' c.co_forma_pgt as PayMethod, '
                                    . ' IF(c.co_parcelado=0,0,'
                                    . ' IF((select count(*) from compras_financiamento where cof_id_compra= c.co_id and cof_pago=1)!=0,1,2)) as PlostSituation,'
                                    //Distribuidor qualificação.
                                    . ' dis.di_qualificacao as CareerPlan,'
                                    . ' (select at_data from registro_ativacao where at_distribuidor=dis.di_id order by at_id desc limit 1) as activationTimestamp '
                                    , false)
                            ->join('compras as c', 'c.co_id_distribuidor= di_id')
                            ->where('di_niv', $di_niv)
                            ->where('di_email', $di_email)
                            ->where('co_eplano', 1)
                            ->where('di_excluido', 0)
                            ->get('distribuidores as dis')->result();

            if (count($distribuidores) == 0) {
                self::$erroCodigo = 10;
            }

            self::acessoapi();
            return $distribuidores;
        } catch (Exception $exc) {
            self::log($exc->getTrace(), $exc->getMessage(), $_REQUEST);
            throw new Exception($exc->getMessage() . ' entre os parâmetros enviados ou o nome do parametro pode esta errado vide documentação da api.');
            return array();
        }
    }

    public static function verificar_patrocinador($di_usuario) {
        $ci = get_instance();
        $erro = 0;
        if (empty($di_usuario)) {
            return false;
        }
        $patrocinador = $ci->db->join('registro_ativacao', 'di_id = at_distribuidor')
                ->where('di_usuario', $di_usuario)
                ->get('distribuidores')
                ->row();


        $membership = $ci->db->join('registro_planos_distribuidor', 'pa_id = ps_plano')
                ->join('distribuidores', 'di_id = ps_distribuidor')
                ->where('di_usuario', $di_usuario)
                ->where('pa_id', 99)
                ->get('planos')
                ->row();



        if (count($patrocinador) == 0) {
            $erro = 1;
        }

        if (count($membership) > 0) {
            $erro = 1;
        }


        if ($erro == 1) {
            return false;
        }
        if ($erro == 0) {
            return true;
        }
    }

    public static function verifica_planos($di_usuario, $pa_id) {
        $ci = get_instance();
        if (empty($di_usuario) || empty($pa_id)) {
            return false;
        }
        $plano = DistribuidorDAO::getPlanoPorUsuario($di_usuario);

        //verifica se é fast.
        if ($plano->pa_id == 100) {

            if (in_array($pa_id, array(100, 99))) {
                return false;
            }
        }

        //Verifica bloqueio.
        $financeiro = $ci->db->select('valor')
                ->where('field', 'grupo_usuarios')
                ->get('config')
                ->row();

        $total = $ci->db->select('di_login_status')
                ->where('di_usuario', $di_usuario)
                ->where('di_login_status', 1)
                ->get('distribuidores')
                ->row();

        if (!empty($financeiro)) {
            if (in_array($di_usuario, explode(',', $financeiro->valor))) {
                return false;
            }
        }

        if (empty($total)) {
            return false;
        }

        return true;
    }

    public static function verificar_login($di_usuario) {
        $ci = get_instance();
        if (empty($di_usuario)) {
            return false;
        }
        if (strlen($di_usuario) < 4) {
            return false;
        }
        $patrocinador = $ci->db->where('di_usuario', $di_usuario)
                ->get('distribuidores')
                ->row();
        if (count($patrocinador) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public static function verificar_senha($senha) {

        if (empty($senha)) {
            return false;
        }

        if (strlen($senha) < 8) {
            return false;
        }
        return true;
    }

    public static function verificar_senha_seguranca($senha_seguranca, $senha) {

        if (empty($senha_seguranca)) {
            return false;
        }

        if (strlen($senha_seguranca) < 8) {
            return false;
        }

        if ($senha == $senha_seguranca) {
            return false;
        }

        return true;
    }

    public static function verificar_email($email) {
        $ci = get_instance();
        if (empty($email)) {
            return 'vazio';
        }

        if (!strpos($email, '@')) {
            return 'invalido';
        }

        $retorna_email = $ci->db->where('di_email', $email)
                ->get('distribuidores')
                ->row();

        if (count($retorna_email) > 0) {
            return 'existente';
        }

        return true;
    }

    public static function verificar_data($data) {
        if (empty($data)) {
            return 'vazio';
        }
        if (!preg_match('^\d{1,2}/\d{1,2}/\d{4}$^', $data)) {
            return 'formato';
        }
        //return str_replace('/', '-', $data)
    }

    public static function verificar_pais($pais) {
        $ci = get_instance();
        if (empty($pais)) {
            return 'vazio';
        }
        if (strlen($pais) < 2 || strlen($pais) > 3) {
            return 'formato';
        }
        if (strlen($pais) == 3) {
            $paises = $ci->db->where('ps_iso3', $pais)
                    ->get('pais')
                    ->row();

            if (count($paises) < 1) {
                return 'invalido';
            } else {
                return $paises->ps_id;
            }
        }
        if (strlen($pais) == 2) {
            $paises = $ci->db->where('ps_sigla', $pais)
                    ->get('pais')
                    ->row();

            if (count($paises) < 1) {
                return 'invalido';
            } else {
                return $paises->ps_id;
            }
        }
    }

    public static function codigoError() {
        return self::$erroCodigo;
    }
    
    public static function acessoapi(){
         $ci = get_instance();
         $ci->db->insert('acesso_api',array(
             'ac_app_id'=>self::$appID,
             'ac_error'=>self::$erroCodigo
         ));
    }

    public static function log($tranced = '', $mensage = '', $POST = array()) {
        $corpo_log = "";
        $corpo_log.= "Rota: " . print_r($tranced, TRUE) . "\n";
        $corpo_log.= "Mensagem : {$mensage}\n";
        $corpo_log.= "Parametros enviados: " . print_r($POST, TRUE) . "\n";
        $pasta = 'log_consulta_usuario';

        $corpo_log_email = "";
        $corpo_log_email.= "Mensagem do erro: {$mensage}\n";
        $corpo_log_email.= "Parametros enviados: " . print_r($POST, TRUE) . "\n";
        
        mail(self::$emailLog, "API de Consulta de Usuário ",$corpo_log_email);

        $caminho = realpath(dirname(dirname(dirname(dirname(__FILE__))))) . '/log_api/';

        if (!file_exists($caminho)) {
            mkdir($caminho);
        }

        if (!empty($pasta)) {
            $caminho.=$pasta . '/';

            if (!file_exists($caminho)) {
                mkdir($caminho);
            }
        }


        $fp = fopen($caminho . "log_" . date('d_m_Y_H_i_s') . ".txt", "a");
        // Escreve "exemplo de escrita" no bloco1.txt
        $escreve = fwrite($fp, $corpo_log);
        // Fecha o arquivo
        fclose($fp);
    }

}
