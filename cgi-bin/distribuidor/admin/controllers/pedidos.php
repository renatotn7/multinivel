<?php

class Pedidos extends CI_Controller {

    public function index() {

        $data['entrega'] = isset($_GET['entrega']) && $_GET['entrega'] != '' ? $_GET['entrega'] : FALSE;
        $data['situacao'] = isset($_GET['situacao']) && $_GET['situacao'] != '' ? $_GET['situacao'] : FALSE;

        if ($data['entrega']) {
            $this->db->where('co_entrega', $data['entrega']);
        }
        if ($data['situacao']) {
            $this->db->where('co_situacao', $data['situacao']);
        }
        #Paginação
        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/pedidos/index/');
        $config['total_rows'] = $this->db->where('co_id_distribuidor', get_user()->di_id)->where('co_eplano', 0)->where('co_situacao <>', -1)->get('compras')->num_rows;
        $config['per_page'] = $data['per_page'] = 15;
        $config['suffix'] = "?" . $_SERVER['QUERY_STRING'];

        $this->pagination->initialize($config);

        $data['links'] = $this->pagination->create_links();


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function pedido_imprimir() {
        autenticar();
        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }

    public function pedidos_pendentes() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function confirmar_pagamento_parcelado_atm() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function confirmar_pagamento_parcelado_bonus() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

//    public function pagar_pedido() {
//    $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
//        $this->load->view('home/index_view', $data);
//    }

    public function confirmar_pagamento() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }
    
    public function confirmar_pagamento_loja_interna() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function pagar_pedido_com_bonus() {

        $this->lang->load('distribuidor/pedidos/confirmar_pagamento');
        if (!isset($_REQUEST['c']) && empty($_REQUEST['c'])) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('compra_invalida'))));
            redirect(base_url());
            return false;
        }

        $co_id = $_REQUEST['c'];

        //Pega a compra do distribuidor.
        $compra = $this->db->where('co_id', $co_id)
                        ->get('compras')->row();

        //Se não concontrar uma compra valida.
        if (count($compra) == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('compra_invalida'))));
            redirect(base_url());
            return false;
        }

        //Se a compra já foi paga.
        if ($compra->co_pago == 1) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('compra_ja_paga'))));
            redirect(base_url());
            return false;
        }

        //Setando a forma de pagamento.
        $forma_pagamento = 'Saldo do backOffice';
        ComprasModel::setForma_pagamento($co_id, $forma_pagamento);



        /**
         * O pagar pedido com bônus retorna false apenas se o usuário não tiver 
         * um saldo suficiente para pagar o valor da compra.
         * existe uma possibilidade de retornar Número no qual cada retorno pode 
         * simbolizar o erro, exemplo:
         *  1 - erro, saldo insuficiente para realização do pagamento.
         *  2 - etc..
         * o usuário após a compra so deve ser passado caso seja pagamento 
         * de uma compra cuja a mesma não seja dele. caso não passe o id 
         * do usuário no paramentro a compra será paga pelo saldo do dono da compra.
         */
        //Criando o redirecionamento
        $pagamento = new Pagamento();
        $resposta = $pagamento->realizarPagamento(new PagamentoBonus($compra, get_user()->di_id));

        if (!$resposta) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_saldo_insuficiente'))));
            redirect(base_url());
            return false;
        }

        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('label_sucesso'))));
        redirect(base_url());
    }
/**
 * Verificar para desativar esse metodo usar 
 * o da loja metodo -> pagar_transparente
 * @return boolean
 */
    public function pagar_pedido() {

        $this->lang->load('distribuidor/pedidos/confirmar_pagamento');

        //Validando senha
        if (!$this->input->post('senha')) {

            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_senha_seguranca_invalida'))));
            redirect(base_url());
            return false;
        }

        //Verificando se asenha é valida
        if (!seguranca::validar_senha_seguranca($this->input->post('senha'), get_user())) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_senha_seguranca_invalida'))));
            redirect(base_url());
            return false;
        }

        if (!isset($_REQUEST['c']) && empty($_REQUEST['c'])) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('compra_invalida'))));
            redirect(base_url());
            return false;
        }

        $co_id = $_REQUEST['c'];
        $descricao = '';

        //Pega a compra do distribuidor.
        $compra = $this->db->where('co_id', $co_id)
                        ->get('compras')->row();

        //Se não concontrar uma compra valida.
        if (count($compra) == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('compra_invalida'))));
            redirect(base_url());
            return false;
        }

        //Se a compra já foi paga.
        if ($compra->co_pago == 1) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('compra_ja_paga'))));
            redirect(base_url());
            return false;
        }
        $request_paymentMethod = explode('-', $_REQUEST['paymentMethod']);

        if (count($request_paymentMethod) == 2) {
            $_REQUEST['paymentMethod'] = $request_paymentMethod[0];
            $_REQUEST['wireType'] = $request_paymentMethod[1];
        }
        
        //Setando a forma de pagamento.
        $forma_pagamento = '';
        switch ($_REQUEST['paymentMethod']) {
            case 0:
                $forma_pagamento = 'Saldo '. ConfigSingleton::getValue("name_plataforma_pagamento") .' (EwC ou EWC voucher) ';
                break;
            case 8:
                $forma_pagamento = 'Wire transfer Dolar / Wire transfer Euro';
                break;
            case 109:
                $forma_pagamento = 'paymentMethod';
                break;
        }

        $this->db->where('co_id', $co_id)->update('compras', array('co_forma_pgt_txt' => $forma_pagamento));

        //Descrição da compra.
        if ($compra->co_tipo == 100) {
            $descricao = "Pedido Nº:" . $compra->co_id . " Compra cartão InterCash";
        }

        //Criando o redirecionamento
        atm::builder_pamento_transparente($compra, base_url('index.php/atm_pagamento/pagamentoCartao'), $_REQUEST, $descricao, get_user());
    }

    public function solicitar_evouche() {
        verificar_permissao_acesso();
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function finalizar_comprar_voucher() {
        autenticar();
        $this->lang->load('distribuidor/pedidos/realizar_pagamento');

        $senha = $this->db
                        ->where('di_pw', sha1($this->input->post('senha')))
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->num_rows;

        if (!$senha) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('senha_nao_encontrada'))));
            redirect(base_url("index.php/pedidos/solicitar_evouche"));
            exit;
        }

        if ($this->input->post('evoucher') == "0.00") {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('nenhuma_erro_valor_zerado'))));
            redirect(base_url("index.php/pedidos/solicitar_evouche"));
            exit;
        }

        //gerado compra
        $this->db->insert('compras', array(
            'co_tipo' => 4,
            'co_entrega' => 0,
            'co_id_distribuidor' => get_user()->di_id,
            'co_entrega_cidade' => get_user()->di_cidade,
            'co_entrega_uf' => get_user()->di_uf,
            'co_entrega_bairro' => get_user()->di_bairro,
            'co_entrega_cep' => get_user()->di_cep,
            'co_entrega_complemento' => get_user()->di_complemento,
            'co_entrega_numero' => get_user()->di_numero,
            'co_total_pontos' => 0,
            'co_situacao' => 7,
            'co_id_plano' => 0,
            'co_eplano' => 0,
            'co_pago' => 0,
            'co_forma_pgt' => 3,
            'co_total_valor' => (double) $this->input->post('evoucher'),
            'co_tipo_plano' => 0,
            'co_evoucher' => 1,
            'co_data_insert' => date('Y-m-d H:i:s')
        ));

        //enviar email ao realizar a compra        
        $idCompra = $this->db->insert_id();
        redirect(base_url("index.php/pedidos/confirmar_pagamento?id_pedido=" . $idCompra));
        exit;
    }

    public function realizar_pagamento() {

        autenticar();
        $this->lang->load('distribuidor/pedidos/realizar_pagamento');

        $this->load->library('lib_bonus');
        $this->load->library('estoque');

        $id_compra = $_POST['id_compra'];

        $distribuidor = $this->db
                ->where('co_pago', 0)
                ->where('co_id', $id_compra)
                ->join('planos', 'pa_id=co_id_plano')->get('compras')
                ->row();

        //Ativa o plano atual.
        $meuPlanoAtual = $this->db
                ->where('co_pago', 1)
                ->where('co_id_distribuidor', $distribuidor->co_id_distribuidor)
                ->join('planos', 'pa_id=co_id_plano')->get('compras')
                ->row();

        //Mantendo o historico de planos antigos
        if (count($meuPlanoAtual) > 0) {

            $this->db->insert('historico_planos', array(
                'hp_id_plano' => $meuPlanoAtual->co_id_plano,
                'hp_id_distribuidor' => $meuPlanoAtual->co_id_distribuidor,
                'hp_valor' => $meuPlanoAtual->pa_valor,
                'hp_data' => date('Y-m-d H:i:s')
            ));
        }


        // verififica se selecionou alguma comprar valida.
        $compra = $this->db
                        ->join('distribuidores', 'co_id_distribuidor=di_id')
                        ->where('co_id', $id_compra)
                        ->where('co_pago', 0)
                        ->get('compras')->row();

        if (count($compra) == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('nenhuma_compra_selecionada'))));
            redirect(base_url());
            exit;
        }


        $compraModel = new ComprasModel($compra);
        $valorTotalCompra = $compraModel->valorCompra();

        $senha = $this->db
                        ->where('di_pw', sha1($this->input->post('senha')))
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->num_rows;

        if (!$senha) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('senha_nao_encontrada'))));
            redirect(base_url("index.php/pedidos/confirmar_pagamento?id_pedido=" . $compra->co_id));
            exit;
        }


        $saldo = $this->db->query("
			SELECT SUM(cb_credito) - SUM(cb_debito) AS saldo FROM conta_bonus
			WHERE cb_distribuidor = " . get_user()->di_id . "
			")->row();

        $saldo->saldo = isset($saldo->saldo) ? $saldo->saldo + 0 : 0;

        $config = $this->db->where('field', 'saldo_minino')->get('config')->row();

        if (($saldo->saldo - $valorTotalCompra) < $config->valor) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_quantia_inferior') . "  " . number_format($config->valor, 2, ',', '.'))));
            redirect(base_url());
            exit;
        }

        if ($saldo->saldo < $valorTotalCompra) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('erro_sem_saldo') . "<strong>R$ {$valorTotalCompra}</strong>")));
            redirect(base_url());
            exit;
        }

        $arrayCompras = array();
        $arrayCompras = $compraModel->getCompras();

        ##Inicia uma transação
        $this->db->trans_start();


        foreach ($arrayCompras as $compra) {

            $valor_compra = $compra->co_total_valor + $compra->co_frete_valor;


            ##Conta como paga
            $this->db->where('co_id', $compra->co_id)->update('compras', array(
                'co_forma_pgt_txt' => 'Bônus',
                'co_forma_pgt' => 3,
                'co_pago' => 1,
                'co_situacao' => 3,
                'co_data_compra' => date('Y-m-d H:i:s')
            ));

            ##Debitar bonus distribuidor
            $this->lib_bonus->debitar_bonus(get_user()->di_id, $valor_compra, $this->lang->line('pagamento_comprar_1') . " " . $compra->co_id . $this->lang->line('pagamento_comprar_2') . " <b>" . $compra->di_usuario . "</b>", $compra->co_id, 3);


            #verificar se é plano para inserir as parcelas e pontos

            if ($compra->co_eplano == 1) {
                $this->load->library('rede');
                $this->rede->alocar($compra->co_id_distribuidor);
                $this->load->library('planos');
                $this->planos->lancar($compra);


                if ($compra->co_tipo_plano == 2) {
                    Evoucher::lancar($compra);
                }
            }

            //Se a compra for tipo voucher no cadastro.
            //Se for compra de evoucher.
            if ($compra->co_evoucher == 1 && $compra->co_eplano != 1) {
                Evoucher::lancar($compra);
            }

            #-- Lançar ativação da compra --#
            $this->load->library('ativacao');
            $this->ativacao->lancar_ativacao($compra);
            #-- Lançar ativação da compra --#	   
            #-- Regitra se estiver pagando compra de outro distribuidor
            if (get_user()->di_id != $compra->co_id_distribuidor) {

                $this->db->insert('registro_pagamento_compra_terceiro', array(
                    'rc_compra' => $compra->co_id,
                    'rc_comprador' => $compra->co_id_distribuidor,
                    'rc_pagante' => get_user()->di_id,
                    'rc_data' => date('Y-m-d H:i:s')
                ));
            }

            ##Credita o CD ou a Fabrica
            if ($compra->co_id_cd != 0) {

                $this->lib_bonus->depositar_cd($compra->co_id_cd, $valor_compra, $this->lang->line('recibo_venda_numero') . $compra->co_id, $compra->co_id);

                $this->estoque->saida_cd($compra->co_id);
            } else {
                //Venda pela fabrica 
                $this->lib_bonus->depositar_fabrica(get_user()->di_id, $valor_compra, $this->lang->line('recibo_venda_numero') . $compra->co_id, $compra->co_id);

                $this->estoque->saida_fabrica($compra->co_id);
            }
        }

        //Se todas as operações ocorrem como esperado
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('pagamento_realizado_sucesso'))));

        //Se o tipo do pagamento for e-voucher
        if ($compra->co_evoucher == 1) {
            $mgs.="<h3>Compra de e-voucher</h3>";
            $mgs.="<strong>Nome:</strong> " . get_user()->di_nome;
            $mgs.="<br><strong>Usuário:</strong> " . get_user()->di_usuario;
            $mgs.="<br><strong>Valor:</strong> US$ " . $compra->co_total_valor;
            $mgs.="<br><strong>Data:</strong> " . date('d/m/Y H:i:s', strtotime(date('Y-m-d H:i:s')));

            foreach (explode(';', conf()->email_compra_voucher)as $email_compra_voucher)
                enviar_notificacao($email_compra_voucher, 'Compra de e-voucher', $mgs);
        }

        foreach (explode(';', conf()->email_todos_cadastro_brasil)as $email_todos_cadastro_brasil) {
            enviar_notificacao($email_todos_cadastro_brasil, 'Compra de e-voucher', $mgs);
        }

        if ($compra->co_eplano == 1) {
            redirect(base_url('index.php/pacotes'));
        } else {
            redirect(base_url('index.php/pedidos'));
        }
    }

    /*
     * Função:
     * Quando o distribuidor paga o boleto e gerado um registro na tabela aguardando_aprovacao
     * que autoriza o patrocinador a ativar o distribuidor na rede e pagar o plano.<br />
     * Requisitos:
     * -Deve fornecer a senha de segurança do patrocianador
     * -Deve existir o registro na tabela aguardando_aprovacao com status igual a zero(0)
     */

    public function ativar_distribuidor() {
        autenticar();
        $this->lang->load('distribuidor/bonus/ativar_distribuidor');

        //Senha de Segurança
        $senha = $this->db
                        ->where('di_pw', sha1($this->input->post('senha_segurancao')))
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->num_rows;

        //Se for uma senha inválida
        if ($senha == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('senha_invalida'))));
            redirect(base_url("index.php/distribuidor/pendentes"));
            exit;
        }

        //Compra
        $compra = $this->db
                        ->join('distribuidores', 'co_id_distribuidor=di_id')
                        ->where('co_id', $this->uri->segment(3))
                        ->where('co_pago', 0)
                        ->get('compras')->row();

        //Compra não encontrada, executa; 
        if (count($compra) == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('compra_nao_encontrada'))));
            redirect(base_url("index.php/distribuidor/pendentes"));
            exit;
        }

        //Buscando registro de Autorização
        /*
          $aguardandoAtivacao = $this->db
          ->where('aa_compra',$compra->co_id)
          ->where('aa_distribuidor',$compra->co_id_distribuidor)
          ->where('aa_status',0)
          ->get('aguardando_aprovacao')->row();
         */

        //Compra não encontrada, executa; 
        /*
          if(count($aguardandoAtivacao)==0){
          set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Não existe uma autorização para essa transação.")));
          redirect(base_url("index.php/distribuidor/pendentes"));
          exit;
          }
         */

        ##Inicia uma transação
        $this->db->trans_start();

        #id da compra
        $id_compra = $compra->co_id;
        $this->load->library('estoque');

        ##Conta como paga
        $this->db->where('co_id', $id_compra)->update('compras', array(
            'co_forma_pgt_txt' => 'Bônus',
            'co_forma_pgt' => 1,
            'co_pago' => 1,
            'co_situacao' => 1,
            'co_data_compra' => date('Y-m-d H:i:s')
        ));

        //Auto-Ativação usada
        /*
          $this->db->where('aa_id',$aguardandoAtivacao->aa_id)->update('aguardando_aprovacao',array(
          'aa_status'=>1
          )); */


        #verificar se é plano para inserir as parcelas e pontos
        if ($compra->co_eplano == 1) {
            $this->load->library('rede');
            $this->rede->alocar($compra->co_id_distribuidor);
            $this->load->library('planos');
            $this->planos->lancar($compra);
        }

        #-- Lançar ativação da compra --#
        $this->load->library('ativacao');
        $this->ativacao->lancar_ativacao($compra);
        #-- Lançar ativação da compra --#	   
        #-- Regitra se estiver pagando compra de outro distribuidor
        if (get_user()->di_id != $compra->co_id_distribuidor) {

            $this->db->insert('registro_pagamento_compra_terceiro', array(
                'rc_compra' => $compra->co_id,
                'rc_comprador' => $compra->co_id_distribuidor,
                'rc_pagante' => get_user()->di_id,
                'rc_data' => date('Y-m-d H:i:s')
            ));
        }


        ##Credita o CD ou a Fabrica
        if ($compra->co_id_cd != 0) {
            //Venda pela CD 
            $this->estoque->saida_cd($compra->co_id);
        } else {
            //Venda pela fabrica 					
            $this->estoque->saida_fabrica($compra->co_id);
        }

        //Se todas as operações ocorrem como esperado
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('transacao_realizada_com_sucesso'))));
        redirect(base_url("index.php/distribuidor/pendentes"));
    }

    public function salvar_escolha() {
        $this->lang->load('distribuidor/pedidos/escolha');

        if ($this->input->post('co_id')) {
            $this->db->where('co_id', $this->input->post('co_id'))
                    ->update('compras', array('co_id_produto_escolha_entrega' => $this->input->post('co_id_produto_escolha_entrega'))
            );

            set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => $this->lang->line('salvo_com_sucesso'))));
        }

        redirect(base_url());
    }

}

?>