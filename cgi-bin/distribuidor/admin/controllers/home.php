<?php
class Home extends CI_Controller {

	public function t(){
		$at = new AtivacaoBinario();
		$at->verificarBinarioAtivo(get_user());
		exit;
		
	}
    function index() {
    	
    
        $this->db->trans_rollback();

        autenticar();

        $qualificacao = new QualificacaoModel();
        $qualificacao->setDistribuidor(get_user());
        $qualificacao->executar();
        $qualificacao->clear();

        function trim_value(&$value) {
            $value = trim($value);
        }

        $permissao = $this->db->query("select valor from config where config.field='grupo_usuarios'")->row();
        $data = array(
            'permissao' => explode(',', $permissao->valor)
        );
        array_walk($data['permissao'], 'trim_value');

        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view", $data);
    }
    
    public function escolha_produto (){
          $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }
    
    public function escolha_confirmacao1 (){
          $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }
    public function escolha_confirmacao2(){
          $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }
    public function escolha_confirmacao3(){
          $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }
    
    public function set_idioma($lang = "es"){
        $_SESSION["lang"] = $lang;        
        $this->config->set_item('language', $lang);
        $url_retorno = (isset($_REQUEST["url"])) ? $_REQUEST["url"] : base_url() ;
        redirect($url_retorno);
    }
    
    public function help_uplines() {
        $this->lang->load('distribuidor/home/help_uplines');

        $patrocinador = $this->db->query("
		   SELECT *
           FROM  `distribuidores`
           WHERE `di_id`=" . get_user()->di_ni_patrocinador . "
           ")->row();

        if (count($patrocinador) > 0) {
            //pega a descricao do dristribuidor
            $descricao = $this->input->post('descricao');

            //Envia o e-mail para patrocinador
            email_patrocinador_help_line($descricao, $patrocinador);

            //Envia para o primeiro UPLINE com saldo acima de US$1600,00
            email_primeiro_upline_help($descricao, $this->primeiro_upline());

            //Envia para o segundo UPLINE com saldo acima de US$3100,00
            email_segundo_upline_help($descricao, $this->segundo_upline());

            set_notificacao(array(
                0 => array(
                    'tipo' => 1,
                    'mensagem' => $this->lang->line('email_sucesso')
                )
            ));
        }

        redirect(base_url());
    }

    public function primeiro_upline() {

        //Valor saldo uplines
        $SaldoPrimeiroUpline = $this->db->where('field', 'primeiro_upline_saldo')->get('config')->row();
        $SaldoSegundoUpline = $this->db->where('field', 'segundo_upline_saldo')->get('config')->row();

        //Query que pega todos os distribuidores ativos e soma os bonus
        $uplines = $this->db->query("
		   SELECT di_id,di_email,(SELECT SUM(cb_credito)) as saldo
		   FROM `conta_bonus`JOIN distribuidores
		   ON `cb_distribuidor`=`di_id`
		   WHERE `di_ativo`=1
		   GROUP BY di_id
		")->result();

        //pegar o primeiro que tem o saldo acima de 1600
        foreach ($uplines as $up) {
            if ($up->saldo > $SaldoPrimeiroUpline->valor && $up->saldo < $SaldoSegundoUpline->valor) {
                $emailUpline = $up->di_email;
                return $emailUpline;
                exit;
            }
        }
    }

    public function segundo_upline() {

        //Valor saldo uplines  pega acima de 3100
        $SaldoSegundoUpline = $this->db->where('field', 'segundo_upline_saldo')->get('config')->row();

        //Query que pega todos os distribuidores ativos e soma os bonus
        $uplines = $this->db->query("
		   SELECT di_id,di_email,(SELECT SUM(cb_credito)) as saldo
		   FROM `conta_bonus`JOIN distribuidores
		   ON `cb_distribuidor`=`di_id`
		   WHERE `di_ativo`=1
		   GROUP BY di_id
		")->result();

        $i = 0;
        for ($i == 0; $i < count($uplines); $i++) {
            if ($uplines[$i]->saldo > $SaldoSegundoUpline->valor) {
                $emailUpline = $uplines[$i + 1]->di_email;
                return $emailUpline;
                exit;
            }
        }
    }

    /*
      | -------------------------------------------------------------------------
      | FIM DO CONTROLLER
      | -------------------------------------------------------------------------
     */
}

?>