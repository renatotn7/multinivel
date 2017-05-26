<?php

class Bonus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        autenticar();

        if (get_user()->distribuidor->getAtivo() == 0) {
            redirect(base_url());
        }
    }

    public function token_loja() {
        $data['dados'] = 'token_loja';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function codigo_promocional_ativacao() {
        $data['dados'] = 'codigo_promocional_ativacao';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function pagar_pedido() {
        $data['dados'] = 'pagar_pedido';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function parcelas() {
        $data['dados'] = 'parcelas';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function historico_ativacao() {
        $data['dados'] = 'historico_ativacao';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function pagar_parcelas_em_aberto_bonus() {
        $this->lang->load('distribuidor/bonus/pagar_parcelas');
        autenticar();

        //Parcela unica.
        $parcela_unica = '';
        if (isset($_REQUEST['c']) && !empty($_REQUEST['c'])) {
            $parcela_unica = $_REQUEST['c'];
        }

        //Verificar senha informada.
        if (!$this->input->post('senha')) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_senha_nao_informada'))));
            redirect(base_url('index.php/pedidos/confirmar_pagamento_parcelado_bonus') . '' . (isset($_REQUEST['c']) ? '?c=' . $_REQUEST['c'] : ''));
            return false;
        }

        //Validando a senha.
        if (!seguranca::validar_senha_seguranca($this->input->post('senha'), get_user())) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_senha_invalida'))));
            redirect(base_url('index.php/pedidos/confirmar_pagamento_parcelado_bonus') . '' . (isset($_REQUEST['c']) ? '?c=' . $_REQUEST['c'] : ''));
            return false;
        }

        //Pegando a parcelas pendentes
        $parcelas = ComprasModel::parcelas_pendentes(get_user(), $parcela_unica);

        $valor_total_parcelas = 0;
        foreach ($parcelas as $parcela) {
            $valor_total_parcelas+= $parcela->cof_valor;
        }

        //Verificar se o distribuidor tem saldo.
        if (contaBonusModel::saldo(get_user())->saldo < $valor_total_parcelas) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_saldo_insuficiente'))));
            redirect(base_url());
        }




        foreach ($parcelas as $parcela) {
            $pagamento = new Pagamento();
            $pagamento->pagarParcelaPendentes(new PagamentoParcelado($parcela, 0, $parcela_unica));
        }

        redirect(base_url());
    }

    public function pagar_parcelas_em_aberto_plataform() {

        $this->lang->load('distribuidor/bonus/pagar_parcelas');
        autenticar();
        //Verificar senha informada.
        if (!$this->input->post('senha')) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_senha_nao_informada'))));

            redirect(base_url('index.php/pedidos/confirmar_pagamento_parcelado_atm') . '' . (isset($_REQUEST['c']) ? '?c=' . $_REQUEST['c'] : ''));
            return false;
        }

        //Validando a senha.
        if (!seguranca::validar_senha_seguranca($this->input->post('senha'), get_user())) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_senha_invalida'))));
            redirect(base_url('index.php/pedidos/confirmar_pagamento_parcelado_atm') . '' . (isset($_REQUEST['c']) ? '?c=' . $_REQUEST['c'] : ''));
            return false;
        }

        //Se for parcelamento unico, so deve ser chamado em caso da atm.
        if (isset($_REQUEST['c']) && !empty($_REQUEST['c'])) {
            ComprasModel::compra_pagar_parcela_unica($_REQUEST['c']);
        }
        atm::builder_quitar_pagamento_parcelado(get_user(), (isset($_REQUEST['c']) ? $_REQUEST['c'] : 0));
    }

    public function por_categoria() {

        $data['dados'] = 'por_categoria';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function extrato() {

        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function transferir_usuario() {
        /*
          1- Verificar se o usuário esta logado
          2- Verificar se a senha esta correta
          3- Verifica se o valor é maior que 250
          4- Verificar se o saldo e maior que o valor solicitado
         */

        $this->lang->load('distribuidor/bonus/transferir_usuario');

        verificar_permissao_acesso();
        autenticar();

        //Verificando qual o plano do usuário.
        if (DistribuidorDAO::getPlano(get_user()->di_id)->pa_id == 100) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacao_plano_fast') . '<i><b>' . DistribuidorDAO::getPlano(get_user()->di_id)->pa_descricao . "</i>")));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return false;
        }


        $senha = $this->db
                        ->where('di_pw', sha1($this->input->post('senha')))
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->num_rows;

        $valor = str_ireplace(',', '.', str_ireplace('.', '', $this->input->post('valor')));

        //Verifica se o valor da compra não ta acima do permitido
        if (!verificar_compra_valor_acima_permitido()) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transferencia_no_permitido'))));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return FALSE;
        }

        //Verifica se o usuario digitou uma senha valida.
        if ($senha == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacao_senha'))));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return false;
        }

        //Verifica se o usuário informou um usuário valido para receber o dinheiro.
        $receptor = $this->db->select(array('di_id', 'di_cpf', 'di_titular2_cpf'))->where('di_usuario', $this->input->post('user'))->get('distribuidores')->row();
        if (count($receptor) == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_usuario_no_localizado') . " {$_POST['user']}")));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return FALSE;
        }

        //Saldo do usuário que vai receber a transferencia, ele tem que ter no minimo US$: 300.00
        $saldoReceptor = $this->db->query("
                SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
                WHERE cb_distribuidor = " . $receptor->di_id . "
                ")->row();

        //Se o saldo for menor que 300 mão pode receber.
        if ($saldoReceptor->saldo < 300.00) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_usuario_receptor_nao_tem_saldo_suficiente_para_receber_dinheiro'))));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return FALSE;
        }


        $saldo = $this->db->query("
                SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
                WHERE cb_distribuidor = " . get_user()->di_id . "
                ")->row();


        $numeroTransferência = $this->db->query("select count(*) as total from conta_bonus where cb_tipo=4 and  cb_distribuidor=" . get_user()->di_id . "
                                                    and cb_data_hora >=concat(DATE_FORMAT(CURRENT_DATE(),'%Y-%m-%d'), ' 00:00:00')
                                                    and cb_data_hora <=concat(DATE_FORMAT(CURRENT_DATE(),'%Y-%m-%d'), ' 23:59:59')
                                                    and cb_credito=0.00
                                                    order by cb_data_hora desc")->row()->total;

        //Pega a quantidade de transferência diária na configuração.
        if ($numeroTransferência >= conf()->numero_transferencia_diaria) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => str_replace("{numero}", conf()->numero_transferencia_diaria, $this->lang->line('erro_excedeu_limite_diario')))));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return false;
        }

        $logado = $this->db->select(array('di_titular2_cpf', 'di_cpf', 'di_id'))->where('di_id', get_user()->di_id)->get('distribuidores')->row();
        $config = $this->db->where('field', 'saldo_minino')->get('config')->row();

        //Cobrando a taxa de transferência do usuári valor pegado da config
        $valor_debito_taxa = ((conf()->taxa_transferecia_entre_usuario / 100) * $valor);

        //Impede que o saldo fique menor que o valor estipulado na config.
        if (($saldo->saldo - ($valor + $valor_debito_taxa)) < $config->valor) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_quantia_menor') . " " . number_format($config->valor, 2, ',', '.') . " + " . number_format($valor_debito_taxa, 2, ',', '.') . str_replace("{taxa}", conf()->taxa_transferecia_entre_usuario, $this->lang->line('erro_transacoes_quantia_menor_mais_taxa')))));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return false;
        }

        //Verifica se o valor corresponde ao valor minimo para transferência.
        if ($valor < conf()->minimo_transferencia_user) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_menor_que') . " " . conf()->minimo_transferencia_user)));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return false;
        }

        if ($saldo->saldo < $valor) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_quantia_indisponivel') . " " . conf()->minimo_transferencia_user)));
            redirect(base_url('index.php/bonus/transferencia_usuarios'));
            return false;
        }


        $this->db->trans_begin();
        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => $receptor->di_id,
            'cb_compra' => 0,
            'cb_descricao' => $this->lang->line('transferencia_usuario') . " <b>" . get_user()->di_usuario . "</b>",
            'cb_credito' => $valor,
            'cb_tipo' => 4,
            'cb_debito' => 0
        ));

        $id_deposito = $this->db->insert_id();

        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => get_user()->di_id,
            'cb_compra' => 0,
            'cb_descricao' => $this->lang->line('transferencia_usuario') . " <b>" . $_POST['user'] . "</b>",
            'cb_credito' => 0,
            'cb_tipo' => 4,
            'cb_debito' => $valor
        ));

        $id_trasacao = $this->db->insert_id();
        //Inserido valor do debito.
        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => get_user()->di_id,
            'cb_compra' => 0,
            'cb_descricao' => str_replace("{taxa}", conf()->taxa_transferecia_entre_usuario, str_replace("{transacao}", $id_trasacao, str_replace("{usuario}", "<b>" . $_POST['user'] . "</b>", $this->lang->line('transferencia_usuario_taxa_cobrada')))),
            'cb_credito' => 0,
            'cb_tipo' => 108,
            'cb_debito' => $valor_debito_taxa
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('transacoes_concluida'))));
        }

        redirect(base_url('index.php/bonus/transferencia_usuarios'));
    }

    public function get_nome_usuario() {

        if ($this->input->post('di_usuario') != "") {

            $usuario_receber_transferencia = $this
                            ->db->select(array('di_nome'))
                            ->where('di_usuario', $this->input->post('di_usuario'))
                            ->get('distribuidores')->row();


            if (count($usuario_receber_transferencia) > 0) {
                echo json_encode(array('infor' => $usuario_receber_transferencia->di_nome));
            } else {
                echo json_encode(array('infor' => 'Usuário não encontrado.'));
            }
        } else {
            echo json_encode(array('infor' => ''));
        }
    }

    public function usuario_verificar() {

        $receber_transferencia = $this
                        ->db->select(array('di_cpf', 'di_usuario', 'di_nome', 'di_titular2_cpf'))
                        ->where('di_usuario', $this->uri->segment(3))
                        ->get('distribuidores')->row();


        $enviar_transferencia = $this->db->select(array('di_titular2_cpf'))
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->row();


        if (count($receber_transferencia) > 0) {

            if ($enviar_transferencia->di_cpf == $receber_transferencia->di_cpf) {
                echo json_encode($receber_transferencia);
                exit;
            } else {
                echo json_encode(array('e_titular' => 'nao'));
                exit;
            }
        }
        echo json_encode(array('di_nome' => ''));
    }

    public function transacoes() {

        verificar_permissao_acesso();

        $this->load->library('paginacao');
        $this->paginacao->por_pagina(20);

        $de = data_to_usa((get_parameter('de') ? get_parameter('de') : date('01/m/Y')));
        $ate = data_to_usa((get_parameter('ate') ? get_parameter('ate') : date('d/m/Y')));

        $mov = $this->db
            ->where("cb_tipo NOT IN (SELECT tb_id FROM bonus_tipo)")
            ->where('cb_distribuidor',get_user()->di_id)
            ->where('cb_data_hora >=',$de.' 00:00:00')
            ->where('cb_data_hora <=',$ate.' 23:59:59')
            ->order_by('cb_data_hora','DESC')
            ->get('conta_bonus')->result();

        /*
        $mov = $this->db->query("
            SELECT * FROM conta_bonus
            WHERE conta_bonus.cb_tipo
            NOT IN(SELECT tb_id FROM bonus_tipo)
            AND conta_bonus.cb_data_hora >= '".$de." 00:00:00'
            AND conta_bonus.cb_data_hora <= '".$ate." 23:59:59'
            AND cb_distribuidor = ".get_user()->di_id."
            ORDER BY conta_bonus.cb_id DESC
        ")->result();
        */

        $data['mov'] = $this->paginacao->rows($mov);
        $data['links'] = $this->paginacao->links();

        $data['dados'] = 'transacoes';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function transferencia_usuarios() {

        $data['dados'] = 'transferencia_usuarios';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function requisitar_saque() {

        $this->lang->load('distribuidor/bonus/requisitar_saque');
        /*
          1- Verificar se a data de saque e tal dia
          1- Verificar se o usuário esta logado
          1.1- Verificar se a conta foi verificada
          2- Verificar se a senha esta correta
          3- Verifica se o valor é maior que tal valor
          4- Verificar se o saldo e maior que o valor solicitado
         */

        if (date("d") != 10){
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('data_nao_permitida'))));
            redirect(base_url('index.php/bonus/extrato'));
            return false;
        } else {
            verificar_permissao_acesso();
            autenticar();
        }

        //Verifica a senha se ta correta;
        $senha = $this->db
            ->where('di_pw', sha1($this->input->post('senha')))
            ->get('distribuidores')->num_rows;

        if ($senha == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacao_senha'))));
            redirect(base_url('index.php/bonus/extrato'));
            return false;
        }

        //Verifica os dados bancarios;
        $dados_bandarios = $this->db
            ->select(array('di_conta_banco','di_conta_agencia','di_conta_numero'))
            ->where('di_id', get_user()->di_id)
            ->get('distribuidores')->row();

        if ($dados_bandarios->di_conta_banco == NULL
            || $dados_bandarios->di_conta_agencia == NULL
            || $dados_bandarios->di_conta_numero == NULL
        ) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('nenhuma_conta'))));
            redirect(base_url('index.php/distribuidor/dados_bancarios'));
            return false;
        }

        // // Verificando qual o plano do usuário.
        // if (DistribuidorDAO::getPlano(get_user()->di_id)->pa_id == 100) {
        //     set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacao_plano_fast') . '<i><b>' . DistribuidorDAO::getPlano(get_user()->di_id)->pa_descricao . "</i>")));
        //     redirect(base_url('index.php/bonus/extrato'));
        //     return false;
        // }

        $config = $this->db->where('field', 'saldo_minino')->get('config')->row();

        //Valor minimo de saque
        $valorMinimoSaque = conf()->valor_minimo_saque;

        // // Verificando sua documentação Ta correta
        // $dis = $this->db->select(array('di_contrato', 'di_conta_verificada'))
        //                 ->where('di_id', get_user()->di_id)
        //                 ->get('distribuidores')->row();
        // if ($dis->di_contrato == 0 && $dis->di_conta_verificada == 0) {
        //     set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Erro na Transação: Sua documentação não foi verificada. Entre no menu <em>MEUS DADOS > VERIFICAÇÃO DE CONTA</em> para maiores informações")));
        //     redirect(base_url('index.php/bonus/extrato'));
        //     return false;
        // }

        // verifica se o valor informado eh positivo
        $valor = str_ireplace(',', '.', str_ireplace('.', '', $this->input->post('valor')));
        if ($valor < 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_quantia_menor') . "  " . number_format($config->valor, 2, ',', '.'))));
            redirect(base_url('index.php/bonus/extrato'));
            return false;
        }

        $saldo = $this->db->query("
			SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
			WHERE cb_distribuidor = " . get_user()->di_id . "
			")->row();

        //Se a quantia for ficar menor do valor minimo exigito não pode retirar da compra bônus.
        if (($saldo->saldo - $valor) < $config->valor) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_quantia_menor') . "  " . number_format($config->valor, 2, ',', '.'))));
            redirect(base_url('index.php/bonus/extrato'));
            return false;
        }

        //Valor minimo para saques;
        if ($valor < $valorMinimoSaque) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_quantia_abaixo_valor_minimo') . $valorMinimoSaque)));
            redirect(base_url('index.php/bonus/extrato'));
            return false;
        }

        //Valor não disponível
        if ($saldo->saldo < $valor) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacoes_quantia_indisponivel') . $valorMinimoSaque)));
            redirect(base_url('index.php/bonus/extrato'));
            return false;
        }

        //Inicia uma transação
        $this->db->trans_begin();

        //Quantidade saque solicitada pelo distribuidor no mês
        $quantidadeSaque = $this->db
            ->where('cdp_distribuidor', get_user()->di_id)
            ->like('cdp_data', date('Y-m-'))
            ->get('conta_deposito')->result();

        $valorTaxaSaque = 10.00;

        //Inserindo o saque na conta do distribuidor
        $this->db->insert('conta_deposito', array(
            'cdp_distribuidor' => get_user()->di_id,
            // 'cpd_conta_distribuidor' => $this->input->post('cpd_conta_distribuidor'),
            'cdp_valor' => $valor-$valorTaxaSaque,
            'cdp_status' => 0,
            'cdp_data' => date('Y-m-d'),
            'cdp_apuracao' => date('Y-m-01')
        ));

        //Obtendo o numero do deposito
        $id_deposito = $this->db->insert_id();

        //Debitando o saque no bônus do distribuidor
        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => get_user()->di_id,
            'cb_compra' => 0,
            'cb_descricao' => $this->lang->line('solicitar_deposito') . " {$id_deposito}",
            'cb_credito' => 0,
            'cb_tipo' => 5,
            'cb_debito' => $valor-$valorTaxaSaque
        ));

        //Debitando taxa do saque no bônus do distribuidor
        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => get_user()->di_id,
            'cb_compra' => 0,
            'cb_descricao' => $this->lang->line('taxa_deposito') . " {$id_deposito}",
            'cb_credito' => 0,
            'cb_tipo' => 21,
            'cb_debito' => $valorTaxaSaque
        ));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            //Informa do a ATM de que foi requisitado o saque.
            /**
             * returno da ATM
             * 1 concluido com sucesso
             * 2 usuário não encontrado
             * 3 saldo insuficiente na ewallet pay
             * 100 erros desconhecido. tente mais tarde
             * 200 não tem informações do usuarioo na empresa nos dados bancarios
             */

            $this->db->trans_commit();
            //Notificação
            set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('transacao_realizada_sucesso'))));

            // $atm = new atm();
            // $status = $atm->solicitar_saque(get_user(),$valor);

            // if($status=='1'){
            //     $this->db->trans_commit();
            // }

            // if($status=='2'){
            //     set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>$this->lang->line('erro_transacao_atm_usuario_nao_existe'))));
            //     $this->db->trans_rollback();
            // }

            // if($status=='3'){
            //     set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>$this->lang->line('erro_transacao_atm_saldo_insuficiente'))));
            //     $this->db->trans_rollback();
            // }

            // if($status=='100'){
            //     set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>$this->lang->line('erro_transacao_atm_erro_desconhecido'))));
            //     $this->db->trans_rollback();
            // }

            // if($status=='200'){
            //     set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>$this->lang->line('erro_transacao_atm_inform_dados_empresa'))));
            //     $this->db->trans_rollback();
            // }
        }

        redirect(base_url('index.php/bonus/extrato'));
    }

    public function resumo() {
        $this->load->view('bonus/resumo_view');
    }

    public function credito() {

        $this->load->library('paginacao');
        $this->paginacao->por_pagina(20);

        $de = data_to_usa((get_parameter('de') ? get_parameter('de') : date('01/m/Y')));
        $ate = data_to_usa((get_parameter('ate') ? get_parameter('ate') : date('d/m/Y')));

        if (get_parameter('tipo')) {
            // $where = "AND conta_bonus.cb_tipo ='" . get_parameter('tipo') . "'";
            $this->db->where('cb_tipo', get_parameter('tipo'));
        }

        $mov = $this->db
            ->where("cb_tipo IN (SELECT tb_id FROM bonus_tipo)")
            ->where('cb_distribuidor',get_user()->di_id)
            ->where('cb_data_hora >=',$de.' 00:00:00')
            ->where('cb_data_hora <=',$ate.' 23:59:59')
            ->order_by('cb_data_hora','DESC')
            ->get('conta_bonus')->result();

        // $mov = $this->db->query("
        //     SELECT * FROM conta_bonus
        //     WHERE cb_tipo IN(SELECT tb_id FROM bonus_tipo)
        //     " . $where . "
        //     AND cb_distribuidor ='" . get_user()->di_id . "'
        //     AND cb_data_hora >='" . $de . "00:00:00'
        //     AND cb_data_hora <='" . $ate . "23:59:59'
        //     ORDER BY cb_data_hora DESC
        //     ")->result();

        $data['mov'] = $this->paginacao->rows($mov);
        $data['links'] = $this->paginacao->links();

        $data['dados'] = 'credito';
        $data['pagina'] = 'bonus/layout_extrato';
        $this->load->view('home/index_view', $data);
    }

    public function forma_recebimento() {

        #cria ou atualiza a forma de recebimento
        if ($this->db->where('fr_apuracao', date('Y-m-01'))
                        ->where('fr_distribuidor', get_user()->di_id)
                        ->get('forma_recebimento')->num_rows == 0) {

            $this->db->insert('forma_recebimento', array(
                'fr_apuracao' => date('Y-m-01'),
                'fr_forma' => 1,
                'fr_distribuidor' => get_user()->di_id
            ));
        }


        if ($this->input->post('fr_forma')) {

            $this->db->where('fr_apuracao', date('Y-m-01'))
                    ->where('fr_distribuidor', get_user()->di_id)
                    ->update('forma_recebimento', array(
                        'fr_forma' => $this->input->post('fr_forma')
            ));
        }

        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function transferir() {

        if ($this->input->post('para')) {
            switch ($this->input->post('para')) {
                case 1: redirect(base_url('index.php/bonus/transferir_escolher_distribuidor'));
                    break;
                case 2: redirect(base_url('index.php/bonus/transferir_escolher_cd'));
                    break;
            }
        }

        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function transferir_escolher_cd() {



        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function transferir_escolher_distribuidor() {


        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function transferir_informar_valor() {
        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function transferir_finalizar() {

        $this->lang->load('distribuidor/bonus/transferir_finalizar');

        $saldo = $this->db->query("
	SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
	WHERE cb_distribuidor = " . get_user()->di_id . "
	")->result();

        if ($saldo[0]->saldo < $_POST['valor']) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacao_saldo_insuficiente'))));
            redirect(base_url('index.php/bonus/transferir'));
            exit;
        }elseif($_POST['valor'] < 100){
           set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => 'Valor mínimo para transferência é de US$100,00')));
            redirect(base_url('index.php/bonus/transferir')); 
        }


        if ($this->input->post('para') == 1) {

            ##Inicia uma transação
            $this->db->trans_start();

            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => $_POST['ni_escolhido'],
                'cb_descricao' => $this->lang->line('transferencia_bonus') . ' ' . get_user()->di_usuario . '/' . get_user()->di_id,
                'cb_credito' => $_POST['valor'],
                'cb_tipo' => 1000,
                'cb_data_hora' => date('Y-m-01')
            ));

            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => get_user()->di_id,
                'cb_descricao' => $this->lang->line('transferencia_bonus_para') . ' ' . $_POST['ni_escolhido'],
                'cb_debito' => $_POST['valor'],
                'cb_tipo'=> 1000,
                'cb_data_hora' => date('Y-m-01')
            ));

            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => get_user()->di_id,
                'cb_descricao' => 'Taxa de transferência'. ' ' . $_POST['ni_escolhido'],
                'cb_debito' => 5,
                'cb_tipo'=> 1000,
                'cb_data_hora' => date('Y-m-01')
            ));

            ##Verifica se a transação foi completa
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                set_notificacao(array(0 =>
                    array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacao_tente_novamente'))));
            } else {
                $this->db->trans_commit();
                set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('bonus_transferido_sucesso'))));
            }
        } else {

            $cd = $this->db->where('cd_id', $_POST['cd_escolhido'])->get('cd')->result();

            ##Inicia uma transação
            $this->db->trans_start();

            $this->db->insert('conta_cd', array(
                'cc_compra' => 0,
                'cc_venda' => 0,
                'cc_descricao' => $this->lang->line('transferencia_bonus') . ' ' . get_user()->di_nome . '/' . get_user()->di_id,
                'cc_credito' => $_POST['valor'],
                'cc_data' => date('Y-m-01'),
                'cc_cd' => $_POST['cd_escolhido']
            ));

            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => get_user()->di_id,
                'cb_descricao' => $this->lang->line('transferencia_bonus_para_cd') . ' ' . $cd[0]->cd_nome . "/" . $cd[0]->cd_id,
                'cb_debito' => $_POST['valor'],
                'cb_data' => date('Y-m-01')
            ));

            ##Verifica se a transação foi completa
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                set_notificacao(array(0 =>
                    array('tipo' => 2, 'mensagem' => $this->lang->line('erro_transacao_tente_novamente'))));
            } else {
                $this->db->trans_commit();
                set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('bonus_transferido_sucesso'))));
            }
        }

        redirect(base_url(''));
    }

}
