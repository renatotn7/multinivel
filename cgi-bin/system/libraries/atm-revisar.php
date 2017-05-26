<?php
class atm {

    private $objatm;
    private $db;
    private $ci;
    private $apiKey;
    private $apiSecret;
    private $externalID;
    private $valorVisa;
    private $valorMaster;
    private $valorUniPay;

    public function __construct($compras = null) {

        $this->db = & get_instance()->db;
        $this->ci = & get_instance();
        $this->objatm = new stdClass();

        if (count($compras) > 0) {
            $this->objatm->compras = $compras;
            $this->get_dados();
        }
        /**
         * Configurações
         */
        $key = $this->db->where('field', 'ewallet_pay_api_key')->get('config')->row();
        $secret = $this->db->where('field', 'ewallet_pay_api_secret_key')->get('config')->row();
        $exter = $this->db->where('field', 'ewallet_pay_api_externalID_key')->get('config')->row();

        $this->apiKey = $key->valor;
        $this->apiSecret = $secret->valor;
        $this->externalID = $exter->valor;
    }

    /**
     * Solicitar cartão empresay pay
     * @param type $niv
     */
    public static function consultarSaldo($distribuidor = array()) {

        //Se não passar o distribuidor retorna false
        if (count($distribuidor) == 0) {
            return false;
        }

        //Se tiver com o nive vazio então retorna false
        if (empty($distribuidor->di_niv)) {
            return false;
        }

        $key = ConfigSingleton::getValue("ewallet_pay_api_key");
        $secret = ConfigSingleton::getValue("ewallet_pay_api_secret_key");
        $url = ConfigSingleton::getValue("url_ewallet_pay_consultar_saldo");

        $fields = array(
            'apiKey' => $key,
            'apiSecret' => $secret,
            'niv' => $distribuidor->di_niv
        );

        $responta = self::send($fields, $url);
        return $responta;
    }

    private function identificacao_unica($idcompra, $id_co = 0) {
        $id = substr(rand(10000000, 99999999), 0, 128);
        $verifica_compra = get_instance()->db->where('co_empresa_uniq_id', $id)
                        ->get('compras')->row();

        if (count($verifica_compra) == 0) {
            get_instance()->db->where('co_id', $idcompra)
                    ->update('compras', array('co_empresa_uniq_id' => $id));
        } else {
            $this->identificacao_unica();
        }

        get_instance()->db->insert('compras_sales', array(
            'sa_id_compra' => $idcompra,
            'sa_numero' => $id
        ));

        return $id;
    }

    /**
     * Solicitar cartão empresay pay
     * @param type $niv
     */
    public function solicitar_cartao($distribuidor) {
        $url = 'https://Plataforma de Pagamento.com/ws/request_card_api';

        $sql = "SELECT co_id, cm_card_flag FROM compras 
                    JOIN distribuidores ON co_id_distribuidor=di_id AND di_id={$distribuidor->di_id}
                    JOIN cartoes_membership ON co_id_cartao = cm_id";

        $compra_cartao = get_instance()->db->query($sql)->row();

        if (is_object($compra_cartao)) {
            $flag = $compra_cartao->cm_card_flag;
        } else {
            $flag = 0;
        }

        $fields = array(
            'apiKey' => $this->apiKey,
            'niv' => $distribuidor->di_niv,
            'cardFlag' => $flag
        );

        $resposta = $this->send($fields, $url);
        return $resposta;
    }

    /**
     * Reupera a token do usuario na empresay pay pay pay...
     */
    public function getToken($email = "") {
        $url = 'https://Plataforma de Pagamento.com/ws/get_token';
        $fields = array(
            'apiKey' => $this->apiKey,
            'type' => 19,
            'email' => !empty($email) ? $email : $Objdis->di_email
        );

        $responta = $this->send($fields, $url);
        return $responta;
    }

    public static function consultarCadastro($distribuidor = array()) {

        if (count($distribuidor) == 0) {
            return $distribuidor;
        }

        $url = 'https://Plataforma de Pagamento.com/ws/consult_register';
        $fields = array(
            'apiKey' => ConfigSingleton::getValue('ewallet_pay_api_key'),
            'apiSecret' => ConfigSingleton::getValue('ewallet_pay_api_secret_key'),
            'email' => $distribuidor->di_email
        );

        $responta = self::send($fields, $url);

        if (!$responta) {
            return array();
        }

        if (count($responta) == 0) {
            return array();
        }

        return self::xmlarray($responta);
    }

    /**
     * Consultar cadastro na ewallet para ver se o usuario.
     */
    public function consulta_cadastro_ewallet($email = "") {
        $url = 'https://Plataforma de Pagamento.com/ws/consult_register';
        $fields = array(
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
            'email' => !empty($email) ? $email : $Objdis->di_email
        );

        $responta = $this->send($fields, $url);

        if (!$responta) {
            return false;
        }

        if (count($responta) == 0) {
            return false;
        } else {
            return $this->xmlarray($responta);
        }
    }

    /**
     * Cadastra o usuário na empresa.
     *   Retorno status
     *   1 - cadastro não encontrado.
     *   2 - cadstro econtado.
     */
    public function cadastro_ewallet($Objdis, $password) {
        //Setando o valores.
        $url = 'https://Plataforma de Pagamento.com/ecommerce/register';
        $fields = array(
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
            'name' => $Objdis->di_nome,
            'surname' => $Objdis->di_ultimo_nome,
            'phone' => $Objdis->di_fone1,
            'celPhone' => $Objdis->di_fone2,
            'birthday' => date('d/m/Y', strtotime($Objdis->di_data_nascimento)),
            'cityOfBirth' => $Objdis->di_cidade_nascimento,
            'cpfCnpj' => '',
            'country' => DistribuidorDAO::getPais($Objdis->di_cidade)->ps_sigla,
            'taxIdNumber' => $Objdis->di_rg,
            'email' => $Objdis->di_email,
            'emailConfirm' => $Objdis->di_email,
            'idUserFirm' => $Objdis->di_id,
            'password' => $password,
            'passwordConfirm' => $password,
            'state' => DistribuidorDAO::getEstado($Objdis->di_uf)->es_nome,
            'city' => DistribuidorDAO::getCidade($Objdis->di_cidade)->ci_nome,
            'cep' => $Objdis->di_cep,
            'street' => $Objdis->di_endereco,
            'district' => $Objdis->di_bairro,
            'number' => $Objdis->di_numero,
            'completion' => $Objdis->di_complemento,
        );

        $responta = $this->send($fields, $url);
        return $responta;
    }

    /**
     * atualiza cadastro do usuário na empresa.
     *   Retorno status
     *   1 - cadastro não encontrado.
     *   2 - cadstro econtado.
     */
    public function atualiar_cadastro_ewallet($Objdis = array()) {

        if (count($Objdis) == 0) {
            return false;
        }

        $key = get_instance()->db->where('field', 'ewallet_pay_api_key')
                        ->get('config')->row();

        $secret = get_instance()->db->where('field', 'ewallet_pay_api_secret_key')
                        ->get('config')->row();

        //Setando o valores.
        $url = 'https://Plataforma de Pagamento.com/update_user_data_api';
        $fields = array(
            'apiKey' => $key->valor,
            'apiSecret' => $secret->valor,
            'name' => $Objdis->di_nome,
            'surname' => $Objdis->di_ultimo_nome,
            'phone' => $Objdis->di_fone1,
            'celPhone' => $Objdis->di_fone2,
            'birthday' => date('d/m/Y', strtotime($Objdis->di_data_nascimento)),
            'cityOfBirth' => $Objdis->di_cidade_nascimento,
            'cpfCnpj' => '',
            'country' => DistribuidorDAO::getPais($Objdis->di_cidade)->ps_sigla,
            'taxIdNumber' => $Objdis->di_rg,
            'email' => $Objdis->di_email,
            'emailConfirm' => $Objdis->di_email,
            'idUserFirm' => $Objdis->di_id,
            'state' => DistribuidorDAO::getEstado($Objdis->di_uf)->es_nome,
            'city' => DistribuidorDAO::getCidade($Objdis->di_cidade)->ci_nome,
            'cep' => $Objdis->di_cep,
            'street' => $Objdis->di_endereco,
            'district' => $Objdis->di_bairro,
            'number' => $Objdis->di_numero,
            'completion' => $Objdis->di_complemento,
        );

        $responta = self::send($fields, $url);
        return $responta;
    }

    /**
     * Esse metodo implementa o sistema de pagamento da api transparente.
     * 
     * metodos de pagamentos
      0 - pay balace
      1 - credit card
      2 - debit card
      3 - bank billet
      5 - derect debit
      6 - bank financing
      8 - wire transfer
     * @param type $compra
     */
    public static function builder_pamento_transparente($compra = '', $urlRetorno = '', $post = null, $descricao = '', $patrocinador = array(), $method = 'form') {
        if (empty($urlRetorno)) {
            return false;
        }
        //Obrigatorio
        if (count($compra) == 0) {
            return false;
        }

        //Setando as variaveis.
        $fields = array(
            'kits' => array(),
            'produto' => array(),
            'ativacao' => array(),
            'upgrade' => array(),
            'campos' => array(),
        );

        $ditribuidor = get_instance()->db->where('co_id', $compra->co_id)
                        ->join('distribuidores', 'di_id=co_id_distribuidor')
                        ->get('compras')->row();

        $kitOwnership = get_instance()->db->where('field', 'kitOwnership')
                        ->get('config')->row();

        $url = "https://Plataforma de Pagamento.com/payment_order/tp";

        //Valor ta taxa do cartão combrada apenas para 1 niv.
        $valor_taxa_niv = 0;
        //verificando se ja tem cartão para o niv
        $cartao = get_instance()->db->where('ccm_niv', $ditribuidor->di_niv)
                        ->get('compra_cartao_memberships')->row();

        //Descricao da compra.
        if (empty($descricao)) {
            $descricao = "Home Kit e Arrendamento da licença de Agência Virtual Nº{$compra->co_id}";
        }

        //niv do distribuidor.
        $di_niv = $ditribuidor->di_niv;

        //se o patrocinador estiver pagando a compra usa o niv dele
        if ($compra->co_id_distribuidor != get_user()->di_id) {
            $di_niv = get_user()->di_niv;
        }

        $total_taxa = 0;
        //Pegando a taxa de imposto do pais.
        $taxa = paisModel::taxa(paisModel::getPaisDistribuidor($compra->co_id_distribuidor)->ps_id);
        if (count($taxa) > 0) {
            $total_taxa = number_format($taxa->camb_frete + $taxa->camb_impostos + $taxa->camb_taxas, '2');
        }


        $voucherValor = 0;
        //Agencia valores do voucher
        switch ($compra->co_id_plano) {
            case 103:
                $voucherValor = 500.00;
                break;
            case 102:
                $voucherValor = 242.50;
                break;
            case 101:
                $voucherValor = 130.00;
                break;
        }

        //Atualizar o valores da escolha
        $objDistribuidor = funcoesdb::arrayToObject(array('di_id' => $compra->co_id_distribuidor));
        $escolha = ComprasModel::fez_escolha_recebimento($objDistribuidor);
        $voucherempresa = 0;

        switch (@$escolha->pe_id) {
            case 1:
                $voucherempresa = 0;
                $voucherValor = 0;
                break;
            case 2:
                $voucherempresa = 1;
                break;
            case 3:
                $voucherempresa = 2;
                break;
        }



//            Paramentro dos produtos
//            'item_id_1' => código,
//            'item_desc_1' => descrição,
//            'item_qtd_1' => quantidade,
//            'item_value_1' => valor do produto formatodo ,
//            o id do produto enviado dever ser informando no undeline 
//            ex.: item_1, item_2, ...[]
        //Colocando os produtos.
        $comboProdutos = combopacoteModel::getComboPacotesPorPlano($compra->co_id_plano);

        if (count($comboProdutos) > 0) {
            $descricao = $comboProdutos->pn_descricao . " Nº:" . $compra->co_id;
        }

        //Gerando token de revenda de produto.
//        ComprasModel::addTokenProdutoComprado($compra->co_id);

        $fields['campos'] = array(
            'apiKey' => ConfigSingleton::getValue('ewallet_pay_api_key'),
            'apiSecret' => ConfigSingleton::getValue('ewallet_pay_api_secret_key'),
            'freight' => '0.00',
            'sale' => self::identificacao_unica($compra->co_id),
            'currency' => 'USD',
            'voucherempresa' => $voucherempresa,
            'voucherValue' => $voucherValor,
            'userNiv' => $di_niv,
            'paymentMethod' => "{$post['paymentMethod']}",
            'urlReturn' => $urlRetorno,
            'serviceType' => 2,
            'IpNumber' => $_SERVER['SERVER_ADDR'],
            'flag' => 1,
            'qtdParcel' => 1,
            'Desc' => utf8_encode($descricao),
            'kitOwnership' => $kitOwnership->valor,
        );

        $produtos = produtoModel::getProdutoComprados($ditribuidor, 0, $compra->co_id);
        // var_dump($produtos); exit();
        //Verifincado ser ha produto comprados.
        if (count($produtos) > 0) {
            $chave=0;
            foreach ($produtos as $key => $produto) {
            
                //Verificando se o produto contém um produto tipo kit.
                
                if (!empty($produto->pr_kit)) {
                	var_dump('aki'); exit;
                    for ($i = 1; $i < kitModel::getquantidadeKit($produto->pr_kit); $i++) {

                        //gerando compras e produtos.
                        $fields['kits'][] = array(
                            'item_id_' . ($chave + $i) => $produto->pr_id,
                            'item_desc_' . ($chave + $i) => utf8_encode($produto->pr_nome),
                            'item_qtd_' . ($chave + $i) => kitModel::quantidadeComprado($compra->co_id, $produto->pr_id),
                            'item_value_' . ($chave + $i) => number_format(($compra->co_parcelado == 1 ? $produto->pr_valor / 2 : $produto->pr_valor) * $produto->pm_quantidade, 2),
                            'item_tax_' . ($chave + $i) => $total_taxa,
                            'tokenCod_' . ($chave + $i) => ComprasModel::geraTokenProdutoKit($produto, $compra, $i + 1),
                            'tokenValue_' . ($chave + $i) => number_format($produto->pr_reebolso * (int)$produto->pm_quantidade,2),
                        );
                        $chave++;
                    }
                }
                var_dump('aki2'); exit;
                //Produto que não é kit de compra.
                if ($produto->pr_kit == 0) {

                    $auxi = array(
                        'item_id_' . ($chave) => $produto->pr_id,
                        'item_desc_' . ($chave) => utf8_encode($produto->pr_nome),
                        'item_qtd_' . ($chave ) => $produto->pm_quantidade,
                        'item_value_' . ($chave) => number_format(($compra->co_parcelado == 1 ? $produto->pr_valor / 2 : $produto->pr_valor) * $produto->pm_quantidade,2),
                        'item_tax_' . ($chave) => $total_taxa,
                        'tokenCod_' . ($chave) => !empty($produto->pr_token_agencia) ? ComprasModel::geraTokenProdutoKit($produto, $compra) : '',
                        'tokenValue_' . ($chave) => $produto->pr_reebolso * (int)$produto->pm_quantidade,
                    );

                    //Retira o campo se não for um produto que pode informar token
                    if (empty($produto->pr_token_agencia)) {
                        unset($auxi['tokenCod_' . ($chave)]);
                    }

                    $fields['produto'][] = $auxi;
                    $chave++;
                }

                //Verificando se o produto é de ativação.
                if ($produto->co_eplano == 1 && $produto->co_eupgrade == 0) {

                    $fields['ativacao'][] = array(
                        'item_id_' . ($chave + 1) => $produto->pr_id,
                        'item_desc_' . ($chave + 1) => utf8_encode($produto->pr_nome),
                        'item_qtd_' . ($chave + 1) => $produto->pm_quantidade,
                        'item_value_' . ($chave + 1) => number_format($produto->pr_valor * (int)$produto->pm_quantidade,2),
                        'item_tax_' . ($chave + 1) => $total_taxa
                    );
                    $chave++;
                }
                //Produto Upgrade
                if ($produto->co_eplano == 1 && $produto->co_eupgrade == 1) {

                    $fields['upgrade'][] = array(
                        'item_id_' . ($chave + 1) => $produto->pr_id,
                        'item_desc_' . ($chave + 1) => utf8_encode($produto->pr_nome),
                        'item_qtd_' . ($chave + 1) => $produto->pm_quantidade,
                        'item_value_' . ($chave + 1) => number_format($produto->pr_valor * (int) $produto->pm_quantidade,2) ,
                        'item_tax_' . ($chave + 1) => $total_taxa
                    );
                    $chave++;
                }
            }
        }



        //Enviar parâmetros cardflag e purchaseServiceType somente na compra de cartão de membership,
        if (count($cartao) > 0) {

            $cartao = get_instance()->db
                            ->where("cm_id", $compra->co_id_cartao)
                            ->get("cartoes_membership")->row();

            if (is_object($cartao)) {
                $fields['campos'] = array_merge($fields['campos'], array(
                    'cardFlag' => $cartao->cm_card_flag,
                    'purchaseServiceType' => 2
                ));
            }
        }

        //Solicitação de pagamento via cartão.
        if ($post['paymentMethod'] == 2) {
            $fields['campos'] = array_merge($fields['campos'], array(
                'debitCardMethod' => $post['paymentMethod'],
                'debitCardAccessCode' => $post['debitCardAccessCode']
            ));
        }

        //Se for Wire transfer
        if ($post['paymentMethod'] == 13) {
            $fields['campos'] = array_merge($fields['campos'], array(
                'serviceType' => 8,
                'wireType' => $post['wireType']
            ));
        }

        //Se for boleto bancario 
        if ($post['paymentMethod'] == 3) {
            $fields['campos'] = array_merge($fields['campos'], array('serviceType' => 8));
        }

        if ($post['paymentMethod'] == 8) {
            $fields['campos'] = array_merge($fields['campos'], array('serviceType' => 8));
        }

        if ($post['paymentMethod'] == 10) {
            $fields['campos'] = array_merge($fields['campos'], array('serviceType' => 8));
        }

        if ($method == 'form') {
            //♫♭♪♯♬♮ Construido formulário transparente ♫♭♪♯♬♮ pa pa pa  
            echo ' <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                   <!-- <script type="text/javascript">
                        function closethisasap() {
                         document.forms["pagamento-atm"].submit();
                        }
                    </script> -->
                </head>
                <body onload="closethisasap();">';

            echo "<form name='pagamento-atm' action='{$url}' method='post'>";
            echo "<!-- campos de configuração inicial-->";
            //Gerando os compos de configuração inicial
            foreach ($fields['campos'] as $key => $field) {
                if ($key == 'paymentMethod' && $field == 0) {
                    echo '<input type="hidden" name="' . $key . '" value="0"/>';
                } else {

                    echo '<input type="hidden" name="' . $key . '" value="' . $field . '"/>';
                }
            }
            echo "<!-- produtos normal da loja interna e não kits-->";
            //Gerando os campos de produtos.
            if (count($fields['produto']) > 0) {
                foreach ($fields['produto'] as $key => $produtos) {
                    foreach ($produtos as $key => $value) {
                        echo '<input type="hidden" name="' . $key . '" value="' . $value . '"/>';
                    }
                }
            }
            echo "<!-- produtos do tipo kits-->";
            //Gerando os campos de kits.
            if (count($fields['kits']) > 0) {
                foreach ($fields['kits'] as $key => $kits) {
                    foreach ($kits as $key => $value) {
                        echo '<input type="hidden" name="' . $key . '" value="' . $value . '"/>';
                    }
                }
            }

            //Gerando os campos de ativacao.
            if (count($fields['ativacao']) > 0) {
                foreach ($fields['ativacao'] as $key => $ativacao) {
                    foreach ($ativacao as $key => $value) {
                        echo '<input type="hidden" name="' . $key . '" value="' . $value . '"/>';
                    }
                }
            }

            //Gerando os campos de upgrade.
            if (count($fields['upgrade']) > 0) {
                foreach ($fields['upgrade'] as $key => $upgrade) {
                    foreach ($upgrade as $key => $value) {
                        echo '<input type="hidden" name="' . $key . '" value="' . $value . '"/>';
                    }
                }
            }

            echo "<input type='submit' value='Enviar Solicitação de Pagamento'/>";
            echo '</form>';
            echo "</body></html>";
            exit;
        }

        if ($method == 'curl') {
            return self::send($fields, $url);
        }
    }

    public static function builder_quitar_pagamento_parcelado($distribuidor = 0, $cof_id = 0) {

        $url = "https://Plataforma de Pagamento.com/payment_order/tp";
        $urlRetorno = base_url('index.php/atm_pagamento/baixaPagamentoParcelado/' . (!empty($cof_id) ? $cof_id : ''));

        if (count($distribuidor) == 0) {
            return false;
        }

        $compra = get_instance()->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_pago', 1)
                        ->where('co_eplano', 1)
                        ->get('compras')->row();

        if (count($compra) == 0) {
            return false;
        }

        $type_sale = 0;

        //Pagamento da parcela unica
        if (!empty($cof_id)) {
            get_instance()->db->where('cof_id', $cof_id);
            $type_sale = $cof_id;
        }

        $parcelas = get_instance()->db->where('cof_pago', 0)
                ->where('cof_id_compra', $compra->co_id)
                ->select('sum(cof_valor) as total, cof_valor')
                ->get('compras_financiamento')
                ->row();

        $total_parcelas = get_instance()->db
                ->where('cof_pago', 0)
                ->where('cof_id_compra', $compra->co_id)
                ->select('count(*) as total')
                ->get('compras_financiamento')
                ->row();

        $descricao = "Quintação das parcelas em Aberto {$total_parcelas->total} X de {$parcelas->cof_valor}";

        $key = get_instance()->db->where('field', 'ewallet_pay_api_key')
                        ->get('config')->row();

        $secret = get_instance()->db->where('field', 'ewallet_pay_api_secret_key')
                        ->get('config')->row();

        $fields = array(
            'apiKey' => $key->valor,
            'apiSecret' => $secret->valor,
            'freight' => '0.00',
            'sale' => self::identificacao_unica($compra->co_id),
            'currency' => 'USD',
            'userNiv' => $distribuidor->di_niv,
            'paymentMethod' => "0",
            'urlReturn' => $urlRetorno,
            'serviceType' => 2,
            'IpNumber' => $_SERVER['SERVER_ADDR'],
            'flag' => 1,
            'qtdParcel' => 1,
            'Desc' => utf8_decode($descricao),
            'item_id_1' => $compra->co_id,
            'item_desc_1' => utf8_decode($descricao),
            'item_qtd_1' => 1,
            'item_value_1' => number_format($parcelas->total, 2),
        );

        //♫♭♪♯♬♮ Construido formulário transparente ♫♭♪♯♬♮ pa pa pa  
        echo ' <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <script type="text/javascript">
                        function closethisasap() {
                         document.forms["pagamento-atm"].submit();
                        }
                    </script>
                </head>
                <body onload="closethisasap();">';

        echo "<form name='pagamento-atm' action='{$url}' method='post'>";
        foreach ($fields as $key => $field) {
            if ($key == 'paymentMethod' && $field == 0) {
                echo '<input type="hidden" name="' . $key . '" value="0"/>';
            } else {
                echo '<input type="hidden" name="' . $key . '" value="' . $field . '"/>';
            }
        }

//        echo "<input type='submit' value='Enviar Solicitação de Pagamento'/>";
        echo '</form>';
        echo "</body></html>";
        return true;
    }

    public function criarBotao_pagamento_atm() {
        //pegando as configuração do plano
        $plano = $this->db->where('pa_id', $this->objatm->co_id_plano)->get('planos')->row();

        //Pegando a nascionalidade do usuário.
        $nascionalidade = $this->db->where('ci_id', $this->objatm->di_cidade)->get('cidades')->row();

        $html = "";
        $html.="<form method='POST' action='https://Plataforma de Pagamento.com/payment_order/' target='_blank'> 

				 <!-- DADOS DA VENDA -->

			    <input name='apiKey' value='{$this->apiKey}' type='hidden'>  
			    <input name='sale' value='{$this->objatm->co_id}' type='hidden'>  
			    <input name='desc' value='Pagamento da compra Número: {$this->objatm->co_id}' type='hidden'>  
			    <input name='freight' value='{$this->objatm->co_frete_valor}' type='hidden'>";
        //Sefor brasileiro vai pagar dolar em Reais
        $html.="    <input name='currency' value='USD' type='hidden'>";
        //Se compra de cartão ATM 
        if ($this->objatm->co_tipo == 5) {
            $html.="    <input name='purchaseServiceType ' value='2' type='hidden'>";

            $pais = DistribuidorDAO::getPais($this->objatm->di_id);
            //Se for brasileiro so vai aceita visa electron.
            if ($pais->ps_id == 2) {
                $html.="    <input name='cardFlag ' value='10' type='hidden'>";
            }
            //Se residente no Uruguai, so vai aparecer a opção de comprar o ATM Union Pay
            if ($pais->ps_id == 225) {
                $html.="    <input name='cardFlag ' value='7' type='hidden'>";
            }

            //Se residente em qualquer outro pais, só vai aparecer a opção de compra do ATM Master Card (com exceção dos EUA, India, Filipinas e Coréia do Sul)
            if ($pais->ps_id != 2 && $pais->ps_id != 225) {
                $html.="    <input name='cardFlag ' value='9' type='hidden'>";
            }

            $html.="  <input name='item_value_1' value='" . (number_format($this->objatm->co_total_valor, 2, ".", "")) . "' type='hidden'>";
        } else {
            $tax = isset($this->objatm->pa_taxa_manutencao) ? $this->objatm->pa_taxa_manutencao : 0;
            $html.="  <input name='item_value_1' value='" . (number_format($this->objatm->co_total_valor + $tax, 2, ".", "")) . "' type='hidden'>";
        }

        $html.=" 
			    <input name='urlReturn' value='" . base_url('/index.php/atm_pagamento/retorno') . "' type='hidden'>  
			   <!--  <input name='userNiv' value='{$this->objatm->di_niv}' type='hidden'>  -->
			   <!--  <input name='paymentMethod' value='0' type='hidden'> -->
			     <!-- <input name='serviceType' value='2' type='hidden'>   -->
			     <!-- <input name='ipNumber' value='{$_SERVER["REMOTE_ADDR"]}' type='hidden'>  -->
			     
			     
			    <!-- PRODUTO 1 -->  
			    <input name='item_id_1' value='{$this->objatm->co_tipo}' type='hidden'> ";


        $html.= " <input name='item_desc_1' value='Pagamento da compra Número: {$this->objatm->co_id}' type='hidden'> ";


        $html.= " <input name='item_qtd_1' value='1' type='hidden'>  ";

        //Sefor brasileiro vai pagar dolar em Reais

        $html.="	<input name='item_weight_1' value='" . (0 * 1000) . "' type='hidden'> ";

        $html.='<input type="submit" style="margin:0 0 0 30px;" class="btn" value="' . $this->ci->lang->line('label_iniciar_pagamento') . '" />';

        $html.='</form>';
        return $html;
    }

    /**
     * Retorna o distribuidor
     * @param type $distribuidor
     */
    public function solicitar_saque($distribuidor = array(), $value = 0, $currencyType = 0) {
        //Pegando dados do usuario 
        $dados_atm = $this->consulta_cadastro_ewallet($distribuidor->di_email);

        if (!$dados_atm) {
            return false;
        }

        if ($dados_atm['status'] == 1) {
            return false;
        }

        if ($value == 0) {
            return false;
        }


        $url = 'https://Plataforma de Pagamento.com/ws/withdrawal';
        $fields = array(
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
            'name' => $dados_atm['name'],
            'surname' => $dados_atm['surname'],
            'externalId' => 13, //Estabelecimento
            'email' => $distribuidor->di_email,
            'value' => number_format($value, 2),
            'niv' => $dados_atm['niv'],
            'CurrencyType' => ($currencyType == 0 ? 'EWC' : 'EVOUCHER'),
            'description' => $distribuidor->prk_token,
        );

        $responta = $this->send($fields, $url);

        return $this->xmlarray($responta);
    }

    /**
     * Envia uma requisição para verificar o estado 
     * do pagamento.
     */
    public function estado_pagamento($compra) {
        //Setando o valores.
        $url = 'https://Plataforma de Pagamento.com/ws/payment/status.json';
        //Pegando o valor da compra.
        $compra_valor = $this->db
                ->where('co_id', $compra->sa_id_compra)
                ->select('co_total_valor')
                ->get('compras')
                ->row();

        $fields = array(
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
            'sale' => $compra->sa_numero,
            'value' => $compra_valor->co_total_valor
        );

        //Envia a requisição de status de pagamento.
        $responta = $this->send($fields, $url);
        return $responta;
    }

    public function estado_pagamento_parcelado($compra) {
        //Setando o valores.

        $url = 'https://Plataforma de Pagamento.com/ws/payment/status.json';

        //Pegando o valor total da parcelas 
        $parcelas = $this->db->where('cof_pago', 0)
                ->where('cof_id_compra', $compra->sa_id_compra)
                ->select('sum(cof_valor) as total')
                ->get('compras_financiamento')
                ->row();

        $fields = array(
            'apiKey' => $this->apiKey,
            'apiSecret' => $this->apiSecret,
            'sale' => $compra->sa_numero,
            'value' => $parcelas->total
        );

        //Envia a requisição de status de pagamento.
        $responta = $this->send($fields, $url);
        return $responta;
    }

    public static function status_universidade($distribuidor = array()) {
        if (count($distribuidor) == 0) {
            return $distribuidor;
        }


        $url = "https://Plataforma de Pagamento.com/ws/university_status_report";
        $fields = array(
            'apiKey' => ConfigSingleton::getValue('ewallet_pay_api_key'),
            'apiSecret' => ConfigSingleton::getValue('ewallet_pay_api_secret_key'),
            'email' => trim($distribuidor->di_email),
            'login' => trim($distribuidor->di_usuario),
        );

        //Envia a requisição de status de pagamento.
        $responta = self::xmlarray(self::send($fields, $url));
        return $responta;
    }

    public static function contacao_cambio($moeda = '') {

        $url = "https://Plataforma de Pagamento.com/ws/exchange_rate";
        $fields = array(
            'apiKey' => ConfigSingleton::getValue('ewallet_pay_api_key'),
            'apiSecret' => ConfigSingleton::getValue('ewallet_pay_api_secret_key'),
            'currency' => empty($moeda) ? "BRL" : trim($moeda),
        );

        //Envia a requisição de status de pagamento.
        $responta = self::xmlarray(self::send($fields, $url));
        return $responta;
    }

    /**
     * Metodos de envio .
     */
    private function send($fields = '', $url = "") {

        if (empty($fields)) {
            return false;
        }

        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($fields, '', '&')
            )
        );

        $context = stream_context_create($opts);
        $result = @file_get_contents($url, false, $context);
        return $result;
    }

    /**
     * Pega dos os dados da compra e seta no objeto stdClass;
     */
    private function get_dados() {

        if (count($this->objatm->compras) > 0) {


            $this->objatm = $this->db->query("
					select 
				      co_frete_valor,di_email,di_email_atm, co_eplano,co_tipo,co_id_plano,
				      co_id,co_total_valor, di_niv,di_cidade, co_tipo,tpa_plano ,di_id
				    from  compras as c 
				    join distribuidores as d on d.di_id=co_id_distribuidor 
				    left join tipo_planos as tp on tp.tpa_id= c.co_tipo_plano 
					where c.co_id={$this->objatm->compras->co_id}
					")->row();
        }
    }

    /*
     * Converter moeda
     */

    private function converterMoeda($valor = 0) {
        if ($valor != 0) {
            //Pegando a cotação do dollar
            $contacao_dollar = $this->db->where('field', 'cotacao_dolar')->get('config')->row();
            $valor_dollar = trim(str_replace('R$', '', str_replace(',', '.', $contacao_dollar->valor)));

            return ( $valor_dollar * $valor);
        }
    }

    /**
     * Fução de mascara com php 
     * @param unknown $val
     * @param unknown $mask
     * @return Ambigous <string, unknown>
     */
    static public function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    protected function xmlarray($source, $arr = array()) {
        $xml = simplexml_load_string($source);
        $iter = 0;
        foreach ($xml->children() as $b) {
            $a = $b->getName();
            if (!$b->children()) {
                $arr[$a] = trim($b[0]);
            } else {
                $arr[$a][$iter] = array();
                $arr[$a][$iter] = xml2phpArray($b, $arr[$a][$iter]);
            }
            $iter++;
        }
        return $arr;
    }

}
