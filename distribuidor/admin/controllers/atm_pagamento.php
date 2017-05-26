<?php

class atm_pagamento extends CI_Controller {

    public function get_sale() {
        echo $_REQUEST['sale'];
    }

    public function pagamentoCartao() {
        $this->lang->load('distribuidor/distribuidor/atm_pagamento');
        if (!isset($_REQUEST['sale'])) {
            return false;
        }

        $compra = $this->db->where('co_empresa_uniq_id', $_REQUEST['sale'])->get('compras')->row();

        if (count($compra) == 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('error_compra_nao_existe'))));
            redirect(base_url());
            return false;
        }

        $erro = (int) 0;
        if (isset($_REQUEST['constraintError'])) {
            $erro = $_REQUEST['constraintError'];
        }


        if ($erro != 0) {
            switch ($erro) {
                case 28:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_saldo_cartao_prepago_insuficiente'))));
                    redirect(base_url());
                    break;
                case 26:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_saldo_cartao_prepago_insuficiente'))));
                    redirect(base_url());
                    break;
                case 5:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_sem_saldo'))));
                    redirect(base_url());
                    break;
                case 6:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_transacao_expirada'))));
                    redirect(base_url());
                    break;
                case 10:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_transacao_nao_encontrada'))));
                    redirect(base_url());
                    break;
                case 17:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_valor_igual_zero'))));
                    redirect(base_url());
                    break;
                case 27:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_nao_tem_cartao_associado'))));
                    redirect(base_url());
                    break;
                case 9:
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_sem_origem'))));
                    redirect(base_url());
                    break;
                default :
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_inesperado'))));
                    redirect(base_url());
                    break;
            }
        }

        //Pegando o estado de pagamento na empresay pay
        $compras = $this->db->where('sa_id_compra', $compra->co_id)
                ->get('compras_sales')
                ->result();

        $status = 999;
        $error2 = true;

        foreach ($compras as $compra_b) {

            $atm = new atm();
            $situacao = json_decode($atm->estado_pagamento($compra_b));

            if ($situacao != false) {
                if ($situacao->status < $status) {
                    $status = $situacao->status;
                }
            } else {
                $status = '';
            }
        }

//        //Evitando o erro na empresaypay
        if (empty($status) & $status !== 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('error_sem_origem'))));
            redirect(base_url());
        }

        //Verificando a transação se deu certo.
        if ($status !== 0) {
            switch ($status) {
                case 3:
                    $error2 = false;
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_usuario_nao_comcluiu_pagamento'))));
                    redirect(base_url());
                    break;
                case 4:
                    $error2 = false;
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_aguardando_sicronizacao_banco'))));
                    redirect(base_url());
                    break;
                case 5:
                    $error2 = false;
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_trasacao_cancelada'))));
                    redirect(base_url());
                    break;
                case 6:
                    $error2 = false;
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_usuario_nao_comcluiu_pagamento'))));
                    redirect(base_url());
                    break;
                case 10:
                    $error2 = false;
                    ComprasModel::removerTokenProdutoComprado($compra->co_id);
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_trasacao_nao_encontrada'))));
                    redirect(base_url());
                    break;
            }
        }


        //Se for compra de ativação mensal

        if ($compra->co_ativacao_mensal == 1) {

            $this->db->insert('registro_ativacao_mensal', array(
                'am_distribuidor' => $compra->co_id_distribuidor,
                'am_compra' => $compra->co_id,
                'am_data' => date('Y-m-d H:i:s')
            ));

            //Inserindo na conta bônus.
            $this->db->insert('conta_bonus', array(
                'cb_tipo' => 239,
                'cb_debito_EWC' => $compra->co_id,
                'cb_distribuidor' => $compra->co_id_distribuidor,
                'cb_compra' => $compra->co_id,
                'cb_descricao' => "Ativação mensal:<b>{$compra->co_id}</b>"
            ));

            $ativacao = new Ativacao();
            $ativacao->lancar_ativacao($compra);
            ComprasModel::dabaixaCompra($compra->co_id);
        } else {

            //Se não tiver erro então não realiza o pagamento da compra.
            if ($erro == 0 && $error2) {
                $pagamento = new Pagamento();
                $pagamento->realizarPagamento(new PagamentoATM($compra));

                //Se não escolheu cartão EDIZ então solicitaão o cartão agora
                if ($compra->co_id_cartao != 3) {

                    $distribuidor = $this->db->where('di_id', $compra->co_id_distribuidor)
                            ->get('distribuidores')
                            ->row();

                    $atm->solicitar_cartao($distribuidor);
                }
            }

            $tokens = ComprasModel::getTokenCompra($compra->co_id);

            $string_email = "";
            $distribuidor_email = $this->db->where('di_id', $compra->co_id_distribuidor)->get('distribuidores')->row();
            $string_prod_es = "";
            $string_prod_en = "";
            $string_prod_pt = "";
            foreach ($tokens as $key => $token) {

                $tokenRevenda = $token->prk_token;
                $string_prod_es.= $produtos['es'] = 'productos: ' . $token->pr_nome . ' Código:' . $tokenRevenda . "<br/>";
                $string_prod_en.= $produtos['en'] = 'product: ' . $token->pr_nome . ' code:' . $tokenRevenda . "<br/>";
                $string_prod_pt.= $produtos['pt'] = 'Produtos: ' . $token->pr_nome . ' Código:' . $tokenRevenda . "<br/>";


                //enviando 
            }

            $string_email.="Hola Señor (a) ({$distribuidor_email->di_nome})<br/> 
                                                        Felicitaciones por su compra.<br/> 
                                                        Siga las muestras de sus productos:<br/> " . $string_prod_es
                    . " <br><br>Saludos.<br/>nossa empresa<br><br>"
                    . "Hello Mr(s.) ({$distribuidor_email->di_nome}) <br/> 
                                                        Congratulations on your purchase..<br/> 
                                                       Follow the tokens of their products:<br/> " . $string_prod_en
                    . " <br><br>Regards.<br/>nossa empresa<br><br>"
                    . "Ola Senhor(a) ({$distribuidor_email->di_nome})<br/> 
                                                        Parabéns por sua compra.<br/> 
                                                        Segue os tokens de seus produtos:<br/> " . $string_prod_pt
                    . " <br><br>Atenciosamente.<br/>nossa empresa<br/><br/>";

            ComprasModel::sendEmail(get_user(), $string_email);


            //verificando se não e compra de plano e nem compra de upgrade.
            if ($compra->co_eupgrade != 1 && $compra->co_eplano != 1) {
                //Realizar Pagamento do bonus para os que comprar na loja interna
//                bonusVendaLojaModel::pagar_bonus(get_user(), $compra);
            }
        }

        set_notificacao(array(0 =>
            array('tipo' => 1, 'mensagem' => $this->lang->line('sucesso'))));
        redirect(base_url());
    }
    
    

    /**
     * Da baixa em todas as parcelas de uma unica vez.
     * @return boolean
     */
    public function baixaPagamentoParcelado() {

        $this->lang->load('distribuidor/distribuidor/atm_pagamento');
        if (!isset($_REQUEST['sale'])) {
            return false;
        }

        $compra = $this->db->where('co_empresa_uniq_id', $_REQUEST['sale'])->get('compras')->row();
        if (count($compra) == 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('error_compra_nao_existe'))));
            redirect(base_url());
            return false;
        }

        $erro = (int) 0;
        if (isset($_REQUEST['constraintError'])) {
            $erro = $_REQUEST['constraintError'];
        }


        if ($erro != 0) {
            switch ($erro) {
                case 28:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_saldo_cartao_prepago_insuficiente'))));
                    redirect(base_url());
                    break;
                case 26:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_saldo_cartao_prepago_insuficiente'))));
                    redirect(base_url());
                    break;
                case 5:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_sem_saldo'))));
                    redirect(base_url());
                    break;
                case 6:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_transacao_expirada'))));
                    redirect(base_url());
                    break;
                case 10:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_transacao_nao_encontrada'))));
                    redirect(base_url());
                    break;
                case 17:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_valor_igual_zero'))));
                    redirect(base_url());
                    break;
                case 27:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_nao_tem_cartao_associado'))));
                    redirect(base_url());
                    break;
                case 9:
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_sem_origem'))));
                    redirect(base_url());
                    break;
                default :
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_inesperado'))));
                    redirect(base_url());
                    break;
            }
        }

        //Pegando o estado de pagamento na empresay pay
        $compras = $this->db->where('sa_id_compra', $compra->co_id)
                ->get('compras_sales')
                ->result();

        $status = 999;
        $error2 = true;


        foreach ($compras as $compra_b) {

            $atm = new atm();
            $situacao = json_decode($atm->estado_pagamento_parcelado($compra_b));

            if ($situacao->status < $status) {
                $status = $situacao->status;
            }
        }

        //Verificando a transação se deu certo.
        if ($status != 0) {
            switch ($status) {
                case 3:
                    $error2 = false;
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_usuario_nao_comcluiu_pagamento'))));
                    redirect(base_url());
                    break;
                case 4:
                    $error2 = false;
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_aguardando_sicronizacao_banco'))));
                    redirect(base_url());
                    break;
                case 5:
                    $error2 = false;
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_trasacao_cancelada'))));
                    redirect(base_url());
                    break;
                case 6:
                    $error2 = false;
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_usuario_nao_comcluiu_pagamento'))));
                    redirect(base_url());
                    break;
                case 10:
                    $error2 = false;
                    set_notificacao(array(0 =>
                        array('tipo' => 2, 'mensagem' => $this->lang->line('error_trasacao_nao_encontrada'))));
                    redirect(base_url());
                    break;
            }
        }


        //Se tiver erro então não realiza o pagamento da compra.
        if ($erro == 0 && $error2) {
            $cof_id = 0;
            if ($this->uri->segment(3)) {
                $cof_id = $this->uri->segment(3);
            }

            $pagamento = new Pagamento();
            $pagamento->pagarParcelaPendentesplataformPay(new PagamentoParcelado($compra, 0, $cof_id));
        }

        set_notificacao(array(0 =>
            array('tipo' => 1, 'mensagem' => $this->lang->line('sucesso'))));

        redirect(base_url());
    }

}
