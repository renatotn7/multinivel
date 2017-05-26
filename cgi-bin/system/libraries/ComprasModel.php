<?php

class ComprasModel {

    private $stdCompra;
    private $db;

    function __construct($compra) {
        $this->stdCompra = $compra;

        $ci = & get_instance();
        $this->db = $ci->db;
    }

    public function pesoTotalCompra() {
        if ($this->stdCompra->co_grupo == 0) {
            return $this->stdCompra->co_peso_total;
        } else {
            $pesoTotalGrupo = $this->db
                            ->select('SUM(co_peso_total) as peso_total')
                            ->where('co_grupo', $this->stdCompra->co_grupo)
                            ->get('compras')->row();

            return isset($pesoTotalGrupo->peso_total) ? $pesoTotalGrupo->peso_total : 0;
        }
    }

    public function getCompras() {

        if ($this->stdCompra->co_grupo == 0) {
            return array($this->stdCompra);
        } else {
            return $this->db
                            ->join('distribuidores', 'co_id_distribuidor=di_id')
                            ->where('co_grupo', $this->stdCompra->co_grupo)
                            ->get('compras')->result();
        }
    }

    public static function compra($distribidor = array(), $plano = false, $upgrade = false, $compraPaga = false) {
        if (count($distribidor) == 0) {
            return $distribidor;
        }

        $ci = & get_instance();
        if ($plano) {
            $ci->db->where('co_eplano', 1);
        }

        if ($upgrade) {
            $ci->db->where('co_eupgrade', 1);
        }

        if ($compraPaga) {
            $ci->db->where('co_pago', 1);
        }

        return $ci->db->where('co_id_distribuidor', $distribidor->di_id)
                        ->get('compras')->result();
    }

    public function valorCompra() {
        if ($this->stdCompra->co_grupo == 0) {
            return $this->stdCompra->co_total_valor + $this->stdCompra->co_frete_valor;
        } else {

            $valortotal = $this->db
                            ->where('co_grupo', $this->stdCompra->co_grupo)
                            ->select('SUM(co_total_valor)+SUM(co_frete_valor) as valor_total')
                            ->get('compras')->row();

            return isset($valortotal->valor_total) ? $valortotal->valor_total : 0;
        }
    }

    public static function getCompraRealizada($idCompra = 0, $compraPaga = false) {
        $ci = & get_instance();

        if (!empty($idCompra)) {
            $ci->db->where('co_id', $idCompra);
        }

        if ($compraPaga) {
            $ci->db->where('co_pago', 0);
        }

        $compras = $ci->db->get('compras');

        if (!empty($idCompra)) {
            return $compras->row();
        }

        return $compras->result();
    }

    public static function removerProduto($idCompra = 0, $idProd = 0) {
        $ci = & get_instance();
        if (empty($idCompra)) {
            return false;
        }
        if (empty($idProd)) {
            return false;
        }

        $resposta     = false;
        $selectCompra = $ci->db->where('pm_id_compra', $idCompra)->get('produtos_comprados')->result();

        if( count( $selectCompra ) == 1 ){
            $resposta = $ci->db->where('co_id', $idCompra)
                      ->delete('compras');
            $resposta = $ci->db->where('pm_id_produto', $idProd)->where('pm_id_compra', $idCompra)
                      ->delete('produtos_comprados');
        }else{
            $resposta = $ci->db->where('pm_id_produto', $idProd)
                      ->delete('produtos_comprados');
        }

        return $resposta;
    }

    public static function dabaixaCompra($idCompra = 0) {
        $ci = & get_instance();
        if (empty($idCompra)) {
            return false;
        }

        return $ci->db->where('co_id', $idCompra)->update('compras', array('co_pago' => 1, 'co_situacao' => 1)
        );
    }

    /**
     * Verifica se o tipo de compra, se é de ativaçãoMensal.
     * @param type $idCompra
     * @param type $distribuidor
     * @return boolean
     */
    public static function compraAtivacaoMensal($idCompra = 0, $distribuidor = array(), $pago = false, $tipo = 'bool') {
        $ci = & get_instance();
        $compra = produtoModel::getProdutoComprados($distribuidor, 8, $idCompra, $pago);

        if ($tipo == 'bool') {
            if (count($compra) > 0) {
                return true;
            }

            return false;
        }

        if ($tipo == 'objeto') {
            if (count($compra) == 0) {
                return array();
            }
            return $compra;
        }
    }

    public static function addCompraAtivacao($idDistribuidor = 0) {
        if (empty($idDistribuidor)) {
            return array();
        }
        //Se já existir não faz uma nova compra.
        $objetoDistribuidor = funcoesdb::arrayToObject(array('di_id' => $idDistribuidor));
        $compraAtivacaoMensal = self::compraAtivacaoMensal(0, $objetoDistribuidor, false, 'objeto');

        if (count($compraAtivacaoMensal) > 0) {
            return $compraAtivacaoMensal[0];
        }

        //verificando se tem categoria de ativação mensal.
        $categoria = categoriaModel::getCategorias(8);

        if (count($categoria) == 0) {
            return array();
        }

        //se tem o produto ativação.
        $produto_ativacao = produtoModel::getProdutoCategoria($categoria->ca_id);

        if (count($produto_ativacao) == 0) {
            return array();
        }

        //Se não for de ativação então não continua.
        if ($produto_ativacao[0]->pr_ativacao == 0) {
            return array();
        }

        $compra_id = 0;

        //Inser a compra a ativação mensal
        get_instance()->db->insert('compras', array(
            'co_id_distribuidor' => $idDistribuidor,
            'co_descricao' => "Ativação mensal: <b>" . date('d/m/Y') . "</b>",
            'co_situacao' => 2,
            'co_eplano' => 1,
            'co_ativacao_mensal' => 1,
            'co_total_valor' => $produto_ativacao[0]->pr_valor * $c->pm_quantidade
        ));

        $compra_id = get_instance()->db->insert_id();

        if (empty($compra_id)) {
            return array();
        }

        //Gerando o produto comprado.         
        get_instance()->db->insert('produtos_comprados', array(
            'pm_id_compra' => $compra_id,
            'pm_id_produto' => $produto_ativacao[0]->pr_id,
            'pm_quantidade' => 1,
            'pm_pontos' => 0,
            'pm_valor' => $produto_ativacao[0]->pr_valor - $produto_ativacao[0]->pr_desconto_distribuidor,
            'pm_valor_total' => $produto_ativacao[0]->pr_valor
        ));

        return get_instance()->db->where('co_id', $compra_id)
                        ->get('compras')->row();
    }

    public static function addCompraAgencia($distribuidor = array(), $idPlano = 0, $idCartao = 0, $codigoPromocional = false) {

        if (count($distribuidor) == 0) {
            return array();
        }

        if (empty($idPlano)) {
            return array();
        }
        if (empty($idCartao)) {
            return array();
        }

        $planos = PlanosModel::getPlano($idPlano);
        if (count($planos) == 0) {
            return array();
        }
        $ObjetoCompra = array();
        $ObjetoValor = self::taxaValorDescricao($distribuidor, $idCartao, $planos);

        if (count($ObjetoValor) == 0) {
            return array();
        }

        $cartao = get_instance()->db->where("cm_id", $idCartao)
                        ->get("cartoes_membership")->row();

        if (count($cartao) == 0) {
            return array();
        }

        get_instance()->db->insert('compras', array(
            'co_tipo' => 1,
            'co_entrega' => 1,
            'co_id_distribuidor' => $distribuidor->di_id,
            'co_entrega_cidade' => $distribuidor->di_cidade,
            'co_entrega_uf' => $distribuidor->di_uf,
            'co_entrega_bairro' => $distribuidor->di_bairro,
            'co_entrega_cep' => $distribuidor->di_cep,
            'co_entrega_complemento' => $distribuidor->di_complemento,
            'co_entrega_numero' => $distribuidor->di_numero,
            'co_entrega_logradouro' => $distribuidor->di_endereco,
            'co_total_pontos' => $planos->pa_pontos,
            'co_situacao' => 5,
            'co_id_plano' => $planos->pa_id,
            'co_descricao' => $ObjetoValor->descricao,
            'co_eplano' => 1,
            'co_pago' => ($codigoPromocional == false ? 0 : 1),
            'co_forma_pgt' => 1,
            'co_hash_boleto' => funcoesdb::criar_hash_boleto(),
            'co_total_valor' => $ObjetoValor->valortotal,
            'co_data_insert' => date('Y-m-d H:i:s'),
            'co_id_cartao' => $idCartao,
            'co_promocional' => ($codigoPromocional == true ? 1 : 0),
            'co_situacao_pedido' => 1
        ));

        $idCompra = get_instance()->db->insert_id();
        
        //Colocando o tipo de pagamento.
        if ($codigoPromocional) {
            ComprasModel::setForma_pagamento($idCompra, 19);
            get_instance()->db->where('co_id', $idCompra)
                    ->update('compras', array(
                        'co_forma_pgt_txt' => 'Ativação por código promocional'
            ));
        }

        $ObjetoCompra['compra'] = self::getCompraRealizada($idCompra);

        get_instance()->db->insert('produtos_comprados', array(
            'pm_id_compra' => $idCompra,
            'pm_id_produto' => $planos->pa_id_produto,
            'pm_quantidade' => 1,
            'pm_pontos' => $planos->pa_pontos,
            'pm_valor' => $planos->pa_valor,
            'pm_valor_total' => $planos->pa_valor,
            'pm_tipo' => 1
        ));

        get_instance()->db->insert('produtos_comprados', array(
            'pm_id_compra' => $idCompra,
            'pm_id_produto' => $cartao->cm_id_produto,
            'pm_quantidade' => 1,
            'pm_valor' => $cartao->cm_valor,
            'pm_valor_total' => $cartao->cm_valor,
            'pm_tipo' => 1
        ));

        $ObjetoCompra['produtoComprado'] = $planos;
        $tokeComfirmacao = uniqid() . "==";

        //Inserido a token de confirmação de cadastro.
        get_instance()->db->insert('token_confirmacao_cadastro', array(
            'tk_token' => $tokeComfirmacao,
            'tk_distribuidor' => $distribuidor->di_id,
            'tk_compra' => $idCompra
        ));

        $ObjetoCompra['token'] = $tokeComfirmacao;

        //Ativando o usuario.
        if ($codigoPromocional) {

            //Verificando se o distribuidor já tem aquele plano.
            $registro_plano = get_instance()->db->where('ps_distribuidor', $ObjetoCompra['compra']->co_id_distribuidor)
                            ->where('ps_plano', $ObjetoCompra['compra']->co_id_plano)
                            ->get('registro_planos_distribuidor')->row();

            if (count($registro_plano) == 0) {
                //Salvando o registro do plano.
                get_instance()->db->insert('registro_planos_distribuidor', array(
                    'ps_distribuidor' => $ObjetoCompra['compra']->co_id_distribuidor,
                    'ps_compra' => $ObjetoCompra['compra']->co_id,
                    'ps_plano' => $ObjetoCompra['compra']->co_id_plano,
                    'ps_valor' => $planos->pa_valor
                ));
            }

            //Alocando o usuário na rede
            $rede = new Rede();
            $rede->alocar($ObjetoCompra['compra']->co_id_distribuidor);

            //Lançando a ativação do usuario
            $ativacao = new Ativacao();
            $ativacao->lancar_ativacao($ObjetoCompra['compra']);
        }

        return funcoesdb::arrayToObject($ObjetoCompra);
    }

    /**
     * Verifica se o distribuidor já tem um cartão memberShips se tem não combra 
     * caso tenha a taxa memberShip então combra a taxa.
     * @param type $distribuidor
     * @param type $idCartaoMemberShip
     * @param type $plano
     * @return type
     */
    public static function taxaValorDescricao($distribuidor = array(), $idCartaoMemberShip = 0, $plano = array()) {
        if (count($distribuidor) == 0) {
            return array();
        }

        if (empty($idCartaoMemberShip)) {
            return array();
        }

        $cartao = get_instance()->db->where("cm_id", $idCartaoMemberShip)
                        ->get("cartoes_membership")->row();

        if (count($cartao) == 0) {
            return array();
        }

        $pais = DistribuidorDAO::getPais($distribuidor->di_cidade);
        if (count($pais) == 0) {
            return array();
        }


        $objetoTaxa = array(
            'valortaxa' => 0,
            'descricao' => "Compra do plano {$plano->pa_descricao}",
            'valortotal' => $plano->pa_valor
        );

        $cartao_merberShip = get_instance()->db->select(array('count(di_id) as total'))
                ->where('di_email', $distribuidor->di_email)
                ->join('distribuidores', 'di_id=txm_id_distribuidor')
                ->get('registro_member_ships')
                ->row();

        // if ($plano->pa_id != 99) {
        //     $objetoTaxa['valortaxa'] = 0;
        //     $objetoTaxa['descricao'].= ' mais cartão ' . $cartao->cm_nome;
        // }

        //Valor d' Câmbio,
        $objcambio = get_instance()->db->where('camb_id_pais', $pais->ps_id)
                ->join('moedas', 'moe_id=camb_id_moedas')
                ->join('pais', 'ps_id=camb_id_pais')
                ->get('moeda_cambio')
                ->row();

        if (count($objcambio) > 0) {
            $objetoTaxa['valortotal'] = self::valor_plano_percetual_tx($plano->pa_id, $objcambio);
        } else {
            $objetoTaxa['valortotal'] = $objetoTaxa['valortotal'] + $objetoTaxa['valortaxa'];
        }

        return funcoesdb::arrayToObject($objetoTaxa);
    }

    public static function addCompraProduto($produto = 0, $compraDescricao = '') {

        $id_compra = 0;
        if (empty($produto)) {
            return false;
        }
        $prod = $produto;
        
        $produto = produtoModel::getProduto($produto);

        //Verificar se já existe uma compra para o produto.
        $compra = produtoModel::getProdutoComprados(get_user(), 7);

        if (count($compra) == 0) {
            get_instance()->db->insert('compras', array(
                'co_id_distribuidor' => get_user()->di_id,
                'co_descricao' => $compraDescricao,
                'co_situacao' => 5,
                'co_total_valor' => $produto->pr_valor - $produto->pr_desconto_distribuidor
            ));
            $id_compra = get_instance()->db->insert_id();
        } else {
            $id_compra = $compra[0]->co_id;
        }
        
        $searchProdExistendi = get_instance()->db->where('pm_id_produto', $prod)->where('pm_id_compra',$id_compra)
        ->get('produtos_comprados')->row();

        if ( count($searchProdExistendi) == 0 ){

            get_instance()->db->insert('produtos_comprados', array(
                'pm_id_compra' => $id_compra,
                'pm_id_produto' => $produto->pr_id,
                'pm_quantidade' => 1,
                'pm_pontos' => 0,
                'pm_valor' => $produto->pr_valor - $produto->pr_desconto_distribuidor,
                'pm_valor_total' => $produto->pr_valor
            ));

        }else{
            
            $qtd = get_instance()->db->where('pm_id_produto', $prod)
            ->where('pm_id_compra',$id_compra)->get('produtos_comprados')->row();
            $q = $qtd->pm_quantidade+1;

            get_instance()->db->where('pm_id_produto', $prod)->where('pm_id_compra',$id_compra)
            ->update('produtos_comprados', array('pm_quantidade' => $q));

        } 

        @$valorTotal = $compra[0]->co_total_valor + ($produto->pr_valor - $produto->pr_desconto_distribuidor);
        get_instance()->db->where('co_id',$id_compra)
        ->update('compras', array('co_total_valor' => $valorTotal, 'co_situacao_pedido' => 0));

        return true;
    }

    public static function validarTokenProdutoComprado($token = 0) {
        //Validando o formato da token.
        $formato1 = explode('-', $token);

        if (count($formato1) < 4) {
            return array();
        }

        //Validando o segundo formato
        $formato2 = explode('.', $formato1[3]);
        if (count($formato2) < 2) {
            return array();
        }

        //Validando a token no banco de dados.
        $dados = get_instance()->db->where("REPLACE(REPLACE(prk_token,'.',''),'-','')  = REPLACE(REPLACE('{$token}','.',''),'-','')")
                        ->where('prk_situacao', 0)
                        ->where('prk_distribuidor_revendedor !=' . get_user()->di_id)
                        ->where('prk_data_aquisicao < DATE_ADD(CURDATE(), INTERVAL 30 DAY)')
                        ->join('produtos', 'pr_id=prk_produto')
                        ->join('compras', 'co_id=prk_compra')
                        ->join('distribuidores', 'di_id=prk_distribuidor_revendedor')
                        ->get('produto_token_revenda')->row();


        return $dados;
    }

    public static function podeInformarToken($distribuidor = array()) {

        if (count($distribuidor) == 0) {
            return false;
        }

//        //Se o usuário já informaou a token uma vez.
//        if ($distribuidor->di_ja_informou_token == 1) {
//            return false;
//        }
        //è necessário te planos
        $plano = DistribuidorDAO::getPlano($distribuidor->di_id);
        if (count($plano) == 0) {
            return false;
        }

        //So vai pagar se for diamante
        if ($plano->pa_id != 103) {
            return false;
        }

        //Verifinado se o login do usuário ta bloqueado
        if ($distribuidor->di_login_status == 0) {
            return false;
        }

        //Verificando se o login financeiro do usuário ta bloqueado.
        if (DistribuidorDAO::status_financeiro_bloqueado($distribuidor->di_usuario)) {
            return false;
        }

        //Verificando se o distribuifor ta com a conta parcelada
        if (ComprasModel::compra_foi_parcelada($distribuidor)) {
            return false;
        }
        //Quando a situação for diferente de pendentes id 7 compra normal ou upgrade.
        $compra = DistribuidorDAO::situacaoPrimeiraCompra(get_user()->di_id);

        if ($compra->st_id != 7) {
            return false;
        }

        //Quando o distribuidor escolheu joia como produto de envio.
        if (count($compra) == 0) {
            return false;
        }

        if ($compra->co_id_produto_escolha_entrega != 1) {
            return false;
        }

        //Quando o cadastro é financiado.
        if (self::compra_foi_parcelada($distribuidor)) {
            return false;
        }

        return true;
    }

    public static function getTokenProduto($idCompra = 0, $idProduto = 0, $next = 0) {
        if (empty($idCompra)) {
            return array();
        }
        if (empty($idProduto)) {
            return array();
        }

        $token = get_instance()->db->where("prk_compra", $idCompra)
                        ->where('prk_produto', $idProduto)
                        ->where('prk_situacao', 0)
                        ->get('produto_token_revenda', 1, $next)->row();

        return $token;
    }

    public static function getTokenCompra($idCompra = 0) {
        $ci = get_instance();
        if (empty($idCompra)) {
            return array();
        }

        $ci->db->stop_cache();
        $ci->db->flush_cache();
        $token = $ci->db->where("prk_compra", $idCompra)
                        ->join('produtos', 'pr_id=prk_produto')
                        ->get('produto_token_revenda')->result();

        return $token;
    }

    public static function getTokenAtivacao($idUsuario = 0, $idCompra = 0) {
        $ci = get_instance();
        $objetoToken = array();
        if (empty($idUsuario)) {
            return array();
        }

        if (!empty($idCompra)) {
            $ci->db->where('prk_compra', $idCompra);
        }

        $codigoPromocionais = $ci->db->where('prk_distribuidor_patrocinador', $idUsuario)
                ->get('produto_token_ativacao');

        foreach ($codigoPromocionais->result() as $key => $codigoPro) {
            $usuario = $ci->db->where('di_id', $codigoPro->prk_distribuidor_beneficiado)
                    ->get('distribuidores')
                    ->row();


            $usuario = count($usuario) > 0 ? $usuario : array();

            $objetoToken[$key] = funcoesdb::arrayToObject(array(
                        'token' => $codigoPro,
                        'plano' => PlanosModel::getPlano($codigoPro->prk_plano),
                        'usuarioativado' => $usuario,
            ));
        }

        return $objetoToken;
    }

    public static function getTokenCodigoPromocionais($idUsuario = 0, $idCompra = 0) {
        $ci = get_instance();
        $objetoToken = array();
        if (empty($idUsuario)) {
            return array();
        }

        if (!empty($idCompra)) {
            $ci->db->where('prk_compra', $idCompra);
        }

        $codigoPromocionais = $ci->db->where('co_id_distribuidor', $idUsuario)
                ->join('compras', 'co_id=prk_compra')
                ->get('produto_token_revenda');

        foreach ($codigoPromocionais->result() as $key => $codigoPro) {
            $usuario = $ci->db->where('di_id', $codigoPro->prk_distribuidor_comprador)
                    ->get('distribuidores')
                    ->row();


            $usuario = count($usuario) > 0 ? $usuario : array();

            $objetoToken[$key] = funcoesdb::arrayToObject(array(
                        'token' => $codigoPro,
                        'plano' => PlanosModel::getPlano($codigoPro->prk_agencia),
                        'usuarioativado' => $usuario,
            ));
        }

        return $objetoToken;
    }

    public static function removerTokenProdutoComprado($idCompra = 0) {
        if (empty($idCompra)) {
            return false;
        }

        //Removendo todas as entradas da compra, não concluida.
        get_instance()->db->where('prk_compra', $idCompra)
                ->delete('produto_token_revenda');
    }

    public static function utilizarTokenProdutoComprado($token = 0, $distribuidor = array()) {
        if (empty($token)) {
            return false;
        }

        if (count($distribuidor) == 0) {
            return false;
        }

        $tk_compra = get_instance()->db->where('prk_token', $token)
                ->get('produto_token_revenda')
                ->row();


        //gerando uma nova compra de produto
//        $id_pedido = lojaModel::comprarProduto($tk_compra->prk_produto);
        $fezUpgrade = get_instance()->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_eplano', '1')
                        ->where('co_eupgrade', '1')
                        ->where('co_id_plano', 103)
                        ->get('compras')->row();


        if (count($fezUpgrade) == 0) {
            //Alterando a Situação da compra que gerou o token para trocou por token.
            get_instance()->db->where('co_id_distribuidor', $distribuidor->di_id)
                    ->where('co_eplano', '1')
                    ->where('co_eupgrade', '0')
                    ->update('compras', array('co_situacao' => 17));
        } else {
            //Alterando a Situação da compra  DO UPGRADE que gerou o token para trocou por token.
            get_instance()->db->where('co_id', $fezUpgrade->co_id)
                    ->update('compras', array('co_situacao' => 17));
        }

        //colocando o distribuidor ja com a token usada.
        get_instance()->db->where('di_id', $distribuidor->di_id)->update('distribuidores', array(
            'di_ja_informou_token' => 1
        ));

        //Removendo inutializa a token.
        get_instance()->db->where('prk_token', $token)
                ->update('produto_token_revenda', array(
                    'prk_situacao' => 1,
                    'prk_distribuidor_comprador' => $distribuidor->di_id,
                    'prk_data_revenda' => date('Y-m-d H:i:s'),
        ));
    }

    public static function geraTokenProdutoKit($produto, $compra, $index = 0) {
        $tokenRevenda = self::gerarToken($compra->co_id, $index);
        get_instance()->db->insert('produto_token_revenda', array(
            'prk_token' => $tokenRevenda,
            'prk_produto' => $produto->pr_id,
            'prk_agencia' => $produto->pr_token_agencia,
            'prk_reembolso' => $produto->pr_reebolso,
            'prk_compra' => $compra->co_id,
            'prk_distribuidor_revendedor' => $compra->co_id_distribuidor,
        ));

        return $tokenRevenda;
    }
    // isso está comentado na versão da objeto comunicacao
   public static function addTokenProdutoComprado($idCompra = 0) {
       if (empty($idCompra)) {
           return false;
       }



       $ditribuidor = get_instance()->db->where('co_id', $idCompra)
                       ->select('di_id,co_eplano,co_id_plano,di_email,di_usuario,pr_id')
                       ->join('compras', 'co_id_distribuidor=di_id')
                       ->join('produtos_comprados', 'pm_id_compra=co_id')
                       ->join('produtos', 'pr_id=pm_id_produto')
                       ->get('distribuidores')->row();

       if (count($ditribuidor) == 0) {
           return false;
       }

       $produtos = array();
       $tipo = 0;

       if ($ditribuidor->co_eplano == 1) {
           return false;
       }

       if ($ditribuidor->co_eplano == 1) {
           $combo = combopacoteModel::getComboPacotesPorPlano($ditribuidor->co_id_plano);
           if (count($combo) > 0) {
               $produtos = combopacoteModel::getProdutosCombo($combo->pn_id);
           }
       } else {
           $produtos = kitModel::getProdutosKitComprado($idCompra);
           $tipo = 1;
       }

       if (count($produtos) == 0) {
           $produtos = array(0 => produtoModel::getProduto($ditribuidor->pr_id));
           $tipo = 0;
       }


       //Se não tiver produto até aqui é porque o produto é de ativação mensal
       if (count($produtos) > 0) {

           foreach ($produtos as $key => $produto) {

               //Verificando se o produto gera token
               if ($produto->pr_gera_token == 0) {
                   continue;
               }

               $token_existe = get_instance()->db->where('prk_compra', $idCompra)
                               ->where('prk_produto', $produto->pr_id)
                               ->where('prk_situacao', 0)
                               ->get('produto_token_revenda')->row();

               if (count($token_existe) > 0) {
                   continue;
               }

               //Produto do tipo kits
               if ($tipo == 1) {

                   for ($i = 0; $i < $produto->pc_quantidade; $i++) {
                       $tokenRevenda = self::gerarToken($idCompra, $i);
                       get_instance()->db->insert('produto_token_revenda', array(
                           'prk_token' => $tokenRevenda,
                           'prk_produto' => $produto->pr_id,
                           'prk_agencia' => $produto->pr_token_agencia,
                           'prk_reembolso' => $produto->pr_reebolso,
                           'prk_compra' => $idCompra,
                           'prk_distribuidor_revendedor' => $ditribuidor->di_id,
                       ));
                   }

                   continue;
               }


               //Produto do tipo combo ou So produto normal mesmo 
               $tokenRevenda = self::gerarToken($idCompra, $key);
               get_instance()->db->insert('produto_token_revenda', array(
                   'prk_token' => $tokenRevenda,
                   'prk_produto' => $produto->pr_id,
                   'prk_agencia' => $produto->pr_token_agencia,
                   'prk_reembolso' => $produto->pr_reebolso,
                   'prk_compra' => $idCompra,
                   'prk_distribuidor_revendedor' => $ditribuidor->di_id,
               ));
           }

           //Fim foreach;
       }
       return true;
   }

    public function sendEmail($ditribuidor, $produtos) {
        self::enviarEmailToken($ditribuidor->di_email, $ditribuidor->di_usuario, $produtos);
    }

    private function enviarEmailToken($email = '', $usuario = '', $message = '', $titulo = 'Código do Produto Comprado') {
        if (empty($email)) {
            return false;
        }

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: ' . $usuario . ' <' . $email . '>' . "\r\n";
        $headers .= 'From:<' . conf()->email_compra_voucher . '>' . "\r\n";
        $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
        $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente


        @mail($email, $titulo, $message, $headers);
    }

    public static function atualizarCarrinho($idcarrinho = 0, $quantidade = 0) {

        if (empty($quantidade)) {
            return false;
        }

        return get_instance()->db->where('pm_id', $idcarrinho)
                        ->update('produtos_comprados', array(
                            'pm_quantidade' => $quantidade,
        ));
    }

    /**
      Atualiza os dados de entrega do produto.
     * @param type $compras
     * @return type]
     */
    public static function salvarLogistica($compras) {
        $logistica = get_instance()->db
                ->where('co_id', $compras['co_id'])
                ->update('compras', funcoesdb::valida_fields('compras', $compras));
        return $logistica;
    }

    /**
     * Retorna o distribudor da compra passada.
     * @param type $id_compra
     * @return boolean
     */
    public static function get_usuario_da_compra($id_compra = 0) {
        $ci = & get_instance();
        if ($id_compra == 0) {
            return false;
        }

        //Regar o usuário da compra 
        $distribuidor = $ci->db->where('co_id', $id_compra)
                ->join('distribuidores', 'di_id=co_id_distribuidor')
                ->get('compras')
                ->row();
        if (count($distribuidor) > 0) {
            return $distribuidor;
        }

        return false;
    }

    /**
     * Verifica ser o país pode parcelar a compra e 
     * divide o valor da compra do plano e ja incrementa na
     * tablea de parcelamento.
     * retorna o valor da entrada
     * @param type $distribuidor
     * @return boolean
     */
    public static function gerar_parcelas_compras($distribuidor = array(), $ja_pagas = 0) {
        $ci = & get_instance();
        $informacao = array();

        //Se não passar o distribuidor não for passada para a execução
        if (count($distribuidor) == 0) {
            return false;
        }


        /**
         * Pegando a compra não paga e so compra de planos.
         */
        $compra = $ci->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_ativacao_mensal', 0)
                        ->where('co_pago', 0)
                        ->where('co_eplano', 1)
                        ->where('co_parcelado', 1)
                        ->get('compras')->row();

        //so vai continuar se tiver uma compra de plano não paga não pode continuar
        if (count($compra) == 0) {
            return false;
        }

        //Para continuar e necessário te um pais ligado ao distribuidor
        $pais = DistribuidorDAO::getPais($distribuidor->di_cidade);
        if (count($pais) == 0) {
            return false;
        }

        //Se o pais não tiver opção de parcelamento interrompe a execução.
        $pais_parecelamento = $ci->db->where('cp_id_pais', $pais->ps_id)
                        ->get('config_pais_parcelamento')->row();

        if (count($pais_parecelamento) == 0) {
            return false;
        }


        //Descobrindo o valor da entrada.
        $valor_entrada = $compra->co_total_valor * ($pais_parecelamento->cp_entrada / 100);

        //Descobrindo o valor que pode ser parcelado.
        $valor_parcelavel = abs($compra->co_total_valor - $valor_entrada);

        //Descobrindo o valor de cada parcela.
        $valor_cada_parcela = ($valor_parcelavel / $pais_parecelamento->cp_numero_parcela);

        $valor_cada_parcela = $valor_cada_parcela + ( $valor_cada_parcela * ($pais_parecelamento->cp_juros / 100));

        //Remove as compras para refazer o parcelamento.
        $ci->db->where('cof_id_compra', $compra->co_id)
                ->delete('compras_financiamento');

        $data_atual = date('Y-m-d', mktime(0, 0, 0, date('m') + 1, date('d'), date('Y')));

        //Adiscionado o valor da compra parcelado
        $ci->db->where('co_id', $compra->co_id)
                ->update('compras', array(
                    'co_valor_entrada' => $valor_entrada
        ));

        $datas_parcelas = self::calcularParcelas($pais_parecelamento->cp_numero_parcela, $data_atual);

        //Inserindo as parcelas calculadas.
        for ($i = 0; $i < $pais_parecelamento->cp_numero_parcela; $i++) {

            $ci->db->insert('compras_financiamento', array(
                'cof_id_compra' => $compra->co_id,
                'cof_numero_parcela' => $i + 1,
                'cof_valor' => $valor_cada_parcela,
                'cof_pago' => $ja_pagas,
                'cof_data_vencimento' => $datas_parcelas[$i],
                'cof_pontos' => (($compra->co_total_pontos / 2) / $pais_parecelamento->cp_numero_parcela)
            ));
        }

        return abs($compra->co_total_valor - $valor_entrada);
    }

    /**
     * Setar forma de pagamento
     *  Plataforma de Pagamento Saldo - 13
     *  Wire Transfer Dolar - 14
     *  Wire Transfer EURO - 15
     *  Cartão Master Card - 16
     *  Voucher Saldo  - 17
     */
    public static function setForma_pagamento($idCompra = 0, $forma_pg = '') {
        if (empty($idCompra)) {
            return false;
        }

        $ci = & get_instance();
        $ci->db->where('co_id', $idCompra)->update('compras', array(
            'co_forma_pgt' => $forma_pg
        ));

        return true;
    }

    /**
     * Verifica se o pais, permit parcelamento.
     * @param type $distribuidor
     * @return boolean
     */
    public static function pais_permiter_parcelamento($distribuidor = 0) {
        $ci = & get_instance();
        $planos_aceitos = array('103', '102', '101');

        //Verifica se o plano do distribuidor é aceito
        if (!@in_array(distribuidorDAO::getPlanoNaoPago($distribuidor->di_id)->pa_id, $planos_aceitos)) {
            return false;
        }


        //Para continuar e necessário te um pais ligado ao distribuidor
        $pais = DistribuidorDAO::getPais($distribuidor->di_cidade);
        if (count($pais) == 0) {
            return false;
        }

        //Se o pais não tiver opção de parcelamento interrompe a execução.
        $pais_parecelamento = $ci->db->where('cp_id_pais', $pais->ps_id)
                        ->get('config_pais_parcelamento')->row();
        if (count($pais_parecelamento) == 0) {
            return false;
        }

        //Verifica se todas as parcelas estão já pagas
        $parcelas = $ci->db->where('co_eplano', 1)//tem que ser plano 
                        ->where('cof_pago', 0)//não pode ter parcela em aberto
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->select('co_valor_entrada,cof_valor')
                        ->join('compras', 'co_id=cof_id_compra')
                        ->get('compras_financiamento')->row();
        if (count($parcelas) == 0) {
            return false;
        }

        //Verifica o valor de cada parcela + o valor da ativação mensal.
        $valorAtivacao = (float) ConfigSingleton::getValue("valor_ativacao_mensal");
        $saldo_necessário = $parcelas->cof_valor + $valorAtivacao;

        $conta_bonus = $ci->db->where('cb_distribuidor', $distribuidor->di_id)
                        ->select('(sum(cb_credito) - sum(cb_debito)) as saldo')
                        ->get('conta_bonus')->row();


//          if($conta_bonus->saldo < $saldo_necessário){
//              return false;
//          }



        return true;
    }

    public static function tem_parcela_em_aberto($distribuidor = 0) {
        $ci = & get_instance();
        $planos_aceitos = array('103', '102', '101');

        //Verifica se o plano do distribuidor é aceito
        if (!@in_array(distribuidorDAO::getPlanoNaoPago($distribuidor->di_id)->pa_id, $planos_aceitos)) {
            return false;
        }

        //Se não passar o distribuidor não for passada para a execução
        if (count($distribuidor) == 0) {
            return false;
        }

        /**
         * Pegando a compra não paga e so compra de planos.
         */
        $compra = $ci->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_eplano', 1)
                        ->where('co_parcelado', 1)
                        ->get('compras')->row();

        //so vai continuar se tiver uma compra de plano não paga não pode continuar
        if (count($compra) == 0) {
            return false;
        }
        //Para continuar e necessário te um pais ligado ao distribuidor
        $pais = DistribuidorDAO::getPais($distribuidor->di_cidade);
        if (count($pais) == 0) {
            return false;
        }

        $parcelas_pendentes = $ci->db->where('cof_id_compra', $compra->co_id)
                        ->where('cof_pago', 0)
                        ->get('compras_financiamento')->row();

        if (count($parcelas_pendentes) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica se o país de origem do distribuidor 
     * permite compras parceladas.
     * @param type $distribuidor
     * @return boolean
     */
    public static function pode_parcelar($distribuidor = 0) {
        $ci = & get_instance();
        $planos_aceitos = array('103', '102', '101');
        $plano = distribuidorDAO::getPlanoNaoPago($distribuidor->di_id);
        if (count($plano) > 0) {
            //Verifica se o plano do distribuidor é aceito
            if (!in_array($plano->pa_id, $planos_aceitos)) {
                return false;
            }
        }
        //Se não passar o distribuidor não for passada para a execução
        if (count($distribuidor) == 0) {
            return false;
        }

        /**
         * Pegando a compra não paga e so compra de planos.
         */
        $compra = $ci->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_eplano', 1)
                        ->get('compras')->row();

        //so vai continuar se tiver uma compra de plano não paga não pode continuar
        if (count($compra) == 0) {
            return false;
        }
        //Para continuar e necessário te um pais ligado ao distribuidor
        $pais = DistribuidorDAO::getPais($distribuidor->di_cidade);
        if (count($pais) == 0) {
            return false;
        }

        //Se o pais não tiver opção de parcelamento interrompe a execução.
        $pais_parecelamento = $ci->db->where('cp_id_pais', $pais->ps_id)
                        ->get('config_pais_parcelamento')->row();

        if (count($pais_parecelamento) > 0) {
            return $pais_parecelamento->cp_numero_parcela;
        } else {
            return false;
        }
    }

    /**
     * Pagamento unico compra parcelada, ajusta a compra para pagamento unico 
     * @param type $distribuidor
     * @return boolean
     */
    public static function compra_pagar_parcela_unica($cof_id = 0) {
        $ci = & get_instance();
        if (empty($cof_id)) {
            return false;
        }
        $ci->db->where('cof_id', $cof_id)->update('compras_financiamento', array(
            'cof_paga_unico' => 1
        ));
    }

    /**
     * Verifica se o usuário teve a compra parcelada
     * se tiver retorna as pendetes
     * @param type $distribuidor
     * @return boolean| ou se tiver parcelas em aberto;
     */
    public static function compra_foi_parcelada($distribuidor = array()) {
        $ci = & get_instance();
        if (count($distribuidor) == 0) {
            return false;
        }

        //Verifica se todas as parcelas estão já pagas
        $parcelas = $ci->db->where('co_eplano', 1)//tem que ser plano 
                        ->where('co_parcelado', 1)//e tem que ser compra parcelada.
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->join('compras', 'co_id=cof_id_compra')
                        ->get('compras_financiamento')->row();

        if (count($parcelas) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Retorna o numero de parcelas para o usuário 
     * se tiver retorna as pendetes
     * @param type $distribuidor
     * @return boolean| ou se tiver parcelas em aberto;
     */
    public static function numero_de_parcelas($distribuidor = array()) {
        $ci = & get_instance();
        if (count($distribuidor) == 0) {
            return false;
        }

        //Verifica se todas as parcelas estão já pagas
        $parcelas = $ci->db->where('co_eplano', 1)//tem que ser plano 
                        ->where('co_parcelado', 1)//e tem que ser compra parcelada.
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->join('compras', 'co_id=cof_id_compra')
                        ->select('count(cof_id) as total')
                        ->get('compras_financiamento')->row();

        if (count($parcelas) > 0) {
            return $parcelas->total;
        }

        return 0;
    }

    /**
     * Retorna todas as parcelas pagas  
     * se tiver retorna as pendetes
     * @param type $distribuidor
     * @return boolean| ou se tiver parcelas em aberto;
     */
    public static function parcelas_pagas($distribuidor = array()) {
        $ci = & get_instance();
        if (count($distribuidor) == 0) {
            return true;
        }

        //Verifica se todas as parcelas estão já pagas
        $parcelas = $ci->db->where('co_eplano', 1)//tem que ser plano 
                        ->where('cof_pago', 1)//não pode ter parcela em aberto
                        ->where('co_parcelado', 1)//e tem que ser compra parcelada.
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->join('compras', 'co_id=cof_id_compra')
                        ->get('compras_financiamento')->result();

        if (count($parcelas) > 0) {
            return $parcelas;
        }

        return array();
    }

    /**
     * Verifica se tem parcelas pentes 
     * se tiver retorna as pendetes
     * @param type $distribuidor
     * @return boolean| ou se tiver parcelas em aberto;
     */
    public static function parcelas_pendentes($distribuidor = array(), $id_parcela = '') {
        $ci = & get_instance();
        if (count($distribuidor) == 0) {
            return true;
        }

        if (!empty($id_parcela)) {
            $ci->db->where('cof_id', $id_parcela);
        }

        //Verifica se todas as parcelas estão já pagas
        $parcelas = $ci->db->where('co_eplano', 1)//tem que ser plano 
                        ->where('cof_pago', 0)//não pode ter parcela em aberto
                        ->where('co_parcelado', 1)//e tem que ser compra parcelada.
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->join('compras', 'co_id=cof_id_compra')
                        ->get('compras_financiamento')->result();

        if (count($parcelas) > 0) {
            return $parcelas;
        }

        return array();
    }

    /**
     * retorna o valor da entrada e taxa
     * @param type $distribuidor
     */
    public static function get_entrada_e_parcela_com_tx($distribuidor = array()) {
        $ci = & get_instance();
        if (count($distribuidor) == 0) {
            return true;
        }

        //Verifica se todas as parcelas estão já pagas
        $parcelas = $ci->db->where('co_eplano', 1)//tem que ser plano 
                        ->where('cof_pago', 0)//não pode ter parcela em aberto
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->select('co_valor_entrada,cof_valor')
                        ->join('compras', 'co_id=cof_id_compra')
                        ->get('compras_financiamento')->row();

        return $parcelas;
    }

    /**
     * verificar se o usuário já informou o cecebimento dos produtos.
     * @param type $distribuidor
     * @return type
     */
    public static function informouRecebimento($distribuidor = array()) {
        $ci = & get_instance();
        if (count($distribuidor) == 0) {
            echo "erro informe um distribuidor";
            return false;
        }

        $compra_paga = $ci->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_total_valor !=0.00')
                        ->where('co_eplano', 1)
                        ->where('co_pago', 0)->get('compras')->row();

        if (count($compra_paga) > 0) {
            return false;
        }

        if (in_array(PlanosModel::getPlanoDistribuidor($distribuidor->di_id)->pa_id, array(99, 100))) {
            return false;
        }


        //Colocar regra dos 30 dias para poder informar o recebimento da loja.
        $tempoCadastro = $ci->db->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_total_valor !=0.00')
                        ->where('co_eplano', 1)
                        ->where('co_pago', 1)->get('compras')->row();

        /*if (funcoesdb::diffData($tempoCadastro->co_data_compra, date('Y-m-d')) < 30) {
            return false;
        }*/

        $distribuidor = $ci->db->where('co_confirmou_recebimento', 0)
                        ->where('co_pago', 1)
                        ->where('co_eplano', 1)
                        ->where('co_ativacao_mensal', 0)
                        ->where('co_total_valor !=0.00')
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->get('compras')->row();


        if (count($distribuidor) > 0) {
            if ($distribuidor->co_nao_recebeu_produto == 1) {
                return false;
            }

            return true;
        }



        return false;
    }

    /**
     * Verifica se o usuário ja escolheu o produto ou  receber em
     * bônus.
     * @param type $distribuidor
     * @return boolean
     */
    public static function fez_escolha_recebimento($distribuidor = array()) {

        $ci = & get_instance();
        if (count($distribuidor) == 0) {
            return array();
        }

        $verifica_plano = $ci->db
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where_in('co_id_plano', array(101, 102, 103))
                        ->get('compras')->row();


        if (count($verifica_plano) == 0) {
            return array();
        }

        $plano = DistribuidorDAO::getPlano($distribuidor->di_id);

        if (count($plano) == 0) {
            return array();
        }


        $fez_escolha = $ci->db
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_id_produto_escolha_entrega', 0)
                        ->where('co_id_plano', $plano->pa_id)
                        ->where_in('co_id_plano', array(101, 102, 103))
                        ->where('co_eplano', 1)
                        ->order_by('co_id', 'desc')
                        ->get('compras')->row();

        if (count($fez_escolha) == 0) {
            return array();
        }


        if ($fez_escolha->co_id_produto_escolha_entrega == 0) {

            //Regra do temporária retirar a opção de escolha e deixa como 1
            $ci->db->where('co_id', $verifica_plano->co_id)
                    ->update('compras', array(
                        'co_id_produto_escolha_entrega' => 1
            ));
            return array();
//            return $fez_escolha;
        }


        return array();
    }

    public static function codigoPromocionalAtivacaoBinario($idDistribuidor = 0, $derramamento = false) {
        if (empty($idDistribuidor)) {
            return false;
        }

        //Desativando o código de derramento
        if (ConfigSingleton::getValue('ativar_ou_destivar_codigo_promocional')) {
            return false;
        }

        $distribuidor = get_instance()->db->where('di_id', $idDistribuidor)
                        ->get('distribuidores')->row();



        $pontos = new Pontos($distribuidor);
        $plano = PlanosModel::getPlanoDistribuidor($distribuidor->di_id);

        $compra = self::compra($distribuidor, 1, 0, 1);

        if (count($compra) == 0) {
            return false;
        }

        //O patrocinador não pode ganhar mais de uma vez esse codigo
        $existe_token = get_instance()->db->where('prk_distribuidor_patrocinador', $distribuidor->di_id)
                        ->where("prk_perna_derramamento", 0)
                        ->get('produto_token_ativacao')->row();

        if (count($existe_token) > 0) {
            return false;
        }

        $diferencaData = (int) funcoesdb::diffData(date('Y-m-d', strtotime($compra[0]->co_data_compra)), date('Y-m-d'));


        if ($diferencaData <= 14) {
            $tokenAtivacao = '';

            //RN 001
            if ($plano->pa_id == 103) {
                for ($i = 0; $i < $plano->pa_numero_token_ativacao_binario; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, funcoesdb::arrayToObject(array('di_ni_patrocinador' => $distribuidor->di_id)), $derramamento);
                }
            }

            //RN 002
            if ($plano->pa_id == 102) {
                for ($i = 0; $i < $plano->pa_numero_token_ativacao_binario; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, funcoesdb::arrayToObject(array('di_ni_patrocinador' => $distribuidor->di_id)), $derramamento);
                }
            }

            //RN 003
            if ($plano->pa_id == 101) {
                for ($i = 0; $i < $plano->pa_numero_token_ativacao_binario; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, funcoesdb::arrayToObject(array('di_ni_patrocinador' => $distribuidor->di_id)), $derramamento);
                }
            }

            //RN 004
            if ($plano->pa_id == 100) {
                for ($i = 0; $i < $plano->pa_numero_token_ativacao_binario; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, funcoesdb::arrayToObject(array('di_ni_patrocinador' => $distribuidor->di_id)), $derramamento);
                }
            }

            //RN 005
            if ($plano->pa_id == 99) {
                for ($i = 0; $i < $plano->pa_numero_token_ativacao_binario; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, funcoesdb::arrayToObject(array('di_ni_patrocinador' => $distribuidor->di_id)), $derramamento);
                }
            }

            $comporEmail = $string_email.="Hola Señor (a) ({$patrocinador->di_nome})<br/> 
                            Por su desempeño en el nossa empresa están enviando códigos promocionales Cash Back para el registro de las agencias de activos y pagado.
                            Cada código de promoción da derecho una Membresía agencia, valorado en $ 99.95.
                            Este valor es enteramente su !!!!!!!!!
                            Obtener el valor en las manos del usuario registrado o donar una agencia para una persona.
                            La elección es suya !!!!!!!<br/>
                            Sus códigos de promoción:<br/> " . $tokenAtivacao
                    . " <br/>Los códigos de promoción también se pueden ver en el menú del Banco, "
                    . "códigos promocionales, en su Oficina Virtual nossa empresa.<br/><br/>"
                    . "Congratulations (s.) ({$patrocinador->di_nome}) <br/> 
                     For their performance in nossa empresa are sending Cash Back promotional codes for registration of active agencies and paid.
                        Each promotional code entitles an agency Membership, valued at $ 99.95.
                        This value is entirely her !!!!!!!!!
                        Get the value in the hands of the registered user or donate an agency for a person.
                        The choice is yours !!!!!!!<br/>

                      His promotional codes:<br/> " . $tokenAtivacao
                    . " <br>Promotion codes can also be viewed at the Bank menu,"
                    . " tab promotional codes in your nossa empresa Virtual Office.<br><br>"
                    . "Parabéns (a) ({$patrocinador->di_nome})<br/> 
                            Pelo seu desempenho na nossa empresa, estamos enviando códigos promocionais Cash Back  para cadastro de agências ativas e pagas.
                            Cada código promocional dá direito a uma agência Membership, no valor de US$ 99,95.
                            Esse valor é integralmente seu !!!!!!!!!
                            Receba o valor em mãos do usuário cadastrado ou doe uma agência para uma pessoa.
                            A escolha é sua !!!!!!!<br/>
                           <br/>  
                           Seus códigos promocionais:<br/> " . $tokenAtivacao
                    . "<br/>Os códigos promocionais também podem ser visualizados no menu Banco,"
                    . "   na aba códigos promocionais em seu escritório Virtual nossa empresa.<br/><br/>";



            //RN 006 Enviar email. 
            self::enviarEmailToken($distribuidor->di_email, $distribuidor->di_usuario, $comporEmail, 'Códigos Promocionales /Códigos Promocionais/ Promotional codes');
        }
    }

    public static function codigoPromocionalDerramamento($idDistribuidor = 0, $derramamento = false) {

        //Desativando o código de derramento
        if (ConfigSingleton::getValue('ativar_ou_destivar_codigo_promocional')) {
            return false;
        }

        $distribuidor = get_instance()->db->where('di_id', $idDistribuidor)
                        ->get('distribuidores')->row();

        $patrocinador = get_instance()->db->where('di_id', $distribuidor->di_ni_patrocinador)
                        ->get('distribuidores')->row();


        if (count($patrocinador) == 0) {
            return false;
        }

        //O patrocinador não pode ganhar mais de uma vez esse codigo
        $existe_token = get_instance()->db->where('prk_distribuidor_patrocinador', $distribuidor->di_ni_patrocinador)
                        ->where("prk_perna_derramamento", 1)
                        ->get('produto_token_ativacao')->row();

        if (count($existe_token) > 0) {
            return false;
        }


        $pontos = new Pontos($patrocinador);
        $plano = PlanosModel::getPlanoDistribuidor($patrocinador->di_id);

        $compra = self::compra($distribuidor, 1, 0, 1);


        if (count($compra) == 0) {
            return false;
        }
        error_reporting(E_ALL);

        $diferencaData = funcoesdb::diffData(date('Y-m-d', strtotime($compra[0]->co_data_compra)), date('Y-m-d'));



        if ($diferencaData <= 7) {
            $tokenAtivacao = '';

            //RN 001
            if ($plano->pa_id == 103) {
                for ($i = 0; $i < $plano->pa_numero_token_derramamento; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, $distribuidor, $derramamento);
                }
            }

            //RN 002
            if ($plano->pa_id == 102) {
                for ($i = 0; $i < $plano->pa_numero_token_derramamento; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, $distribuidor, $derramamento);
                }
            }

            //RN 003
            if ($plano->pa_id == 101) {
                for ($i = 0; $i < $plano->pa_numero_token_derramamento; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, $distribuidor, $derramamento);
                }
            }

            //RN 004
            if ($plano->pa_id == 100) {
                for ($i = 0; $i < $plano->pa_numero_token_derramamento; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, $distribuidor, $derramamento);
                }
            }

            //RN 005
            if ($plano->pa_id == 99) {
                for ($i = 0; $i < $plano->pa_numero_token_derramamento; $i++) {
                    $tokenAtivacao.= '<br/>' . ($i + 1) . ' - ' . self::gerarTokenAtivacaoPromocional($compra[0]->co_id, $distribuidor, $derramamento);
                }
            }

            $comporEmail = "";

            $comporEmail .="Hola Señor (a) ({$patrocinador->di_nome})<br/> 
                            Por su desempeño en el nossa empresa están enviando códigos promocionales Cash Back para el registro de las agencias de activos y pagado.
                            Cada código de promoción da derecho una Membresía agencia, valorado en $ 99.95.
                            Este valor es enteramente su !!!!!!!!!
                            Obtener el valor en las manos del usuario registrado o donar una agencia para una persona.
                            La elección es suya !!!!!!!<br/>
                            Sus códigos de promoción:<br/> " . $tokenAtivacao
                    . " <br/>Los códigos de promoción también se pueden ver en el menú del Banco, "
                    . "códigos promocionales, en su Oficina Virtual nossa empresa.<br/><br/>"
                    . "Congratulations (s.) ({$patrocinador->di_nome}) <br/> 
                     For their performance in nossa empresa are sending Cash Back promotional codes for registration of active agencies and paid.
                        Each promotional code entitles an agency Membership, valued at $ 99.95.
                        This value is entirely her !!!!!!!!!
                        Get the value in the hands of the registered user or donate an agency for a person.
                        The choice is yours !!!!!!!<br/>

                      His promotional codes:<br/> " . $tokenAtivacao
                    . " <br>Promotion codes can also be viewed at the Bank menu,"
                    . " tab promotional codes in your nossa empresa Virtual Office.<br><br>"
                    . "Parabéns (a) ({$patrocinador->di_nome})<br/> 
                            Pelo seu desempenho na nossa empresa, estamos enviando códigos promocionais Cash Back  para cadastro de agências ativas e pagas.
                            Cada código promocional dá direito a uma agência Membership, no valor de US$ 99,95.
                            Esse valor é integralmente seu !!!!!!!!!
                            Receba o valor em mãos do usuário cadastrado ou doe uma agência para uma pessoa.
                            A escolha é sua !!!!!!!<br/>
                           <br/>  
                           Seus códigos promocionais:<br/> " . $tokenAtivacao
                    . "<br/>Os códigos promocionais também podem ser visualizados no menu Banco,"
                    . "   na aba códigos promocionais em seu escritório Virtual nossa empresa.<br/><br/>";

            //RN 006 Enviar email. 
            self::enviarEmailToken($patrocinador->di_email, $patrocinador->di_usuario, $comporEmail, 'Códigos Promocionales /Códigos Promocionais/ Promotional codes');
        }
    }

    public function gerarTokenAtivacaoPromocional($id_compra = 0, $distribuidor = array(), $derramamento = false, $debug = false) {
        if (empty($id_compra)) {
            return '';
        }

        if (count($distribuidor) == 0) {
            return '';
        }

        $token = substr(md5(time()), 0, 4) . '-' . substr(md5(time()), 0, 2) . date('d') . '-' . substr(md5(time()), 0, 2) . date('m') . '-' . $id_compra . '.' . date('Y') . '-' . rand(0, 9);

        $ja_exist = get_instance()->db
                        ->where("REPLACE(REPLACE(prk_token,'.',''),'-','')  = REPLACE(REPLACE('{$token}','.',''),'-','')")
                        ->where('prk_distribuidor_patrocinador', $distribuidor->di_ni_patrocinador)
                        ->get('produto_token_ativacao')->row();

        if (count($ja_exist) > 0) {
            self::gerarTokenAtivacaoPromocional($id_compra, $distribuidor, $derramamento);
        } 
        else {

            get_instance()->db->insert('produto_token_ativacao', array(
                'prk_token' => $token,
                'prk_perna_derramamento' => $derramamento,
                'prk_plano' => 99,
                'prk_distribuidor_patrocinador' => $distribuidor->di_ni_patrocinador,
                'prk_compra' => $id_compra,
                'prk_teste' => $debug
            ));
        }

        return $token;
    }

    protected function gerarToken($id_compra = 0, $diff = 0) {

        if (empty($id_compra)) {
            return '';
        }

        $token = substr(md5(time() + $diff), 0, 4) . '-' . substr(md5(time()), 0, 2) . date('d') . '-' . substr(md5(time()), 0, 2) . date('m') . '-' . $id_compra . '.' . date('Y');

        $ja_exist = get_instance()->db
                        ->where('prk_token', $token)
                        ->get('produto_token_revenda')->row();

        if (count($ja_exist) > 0) {
            self::gerarToken($id_compra);
        }

        return $token;
    }

    /**
     * Verifica as datas de vencimento de acordo com as quantidade de 
     * parcelas.
     * @param type $quantidade_parcelas
     * return array()
     */
    protected function calcularParcelas($nParcelas, $dataPrimeiraParcela = null) {
        if ($dataPrimeiraParcela != null) {
            $dia = date('d', strtotime($dataPrimeiraParcela));
            $mes = date('m', strtotime($dataPrimeiraParcela));
            $ano = date('Y', strtotime($dataPrimeiraParcela));
        } else {
            $dia = date("d");
            $mes = date("m");
            $ano = date("Y");
        }

        for ($x = 0; $x < $nParcelas; $x++) {
            $dt_parcelas[] = date("Y-m-d", strtotime("+" . $x . " month", mktime(0, 0, 0, $mes, $dia, $ano)));
        }//for
        return $dt_parcelas;
    }

    public static function valor_plano_percetual_tx($id_plano = 0, $objCambio = array()) {

        if ($id_plano == 0) {
            return 0.00;
        }

        if (count($objCambio) == 0) {
            return 0.00;
        }
        $ci = get_instance();
        $total_percentual = 0.00;
        $ci = & get_instance();
        $planos = $ci->db->where('pa_id', $id_plano)
                ->get('planos')
                ->row();

        $member_ship_valor = $ci->db->where('pa_id', 99)
                ->get('planos')
                ->row();

        $total_percentual +=$objCambio->camb_taxas;
        $total_percentual +=$objCambio->camb_impostos;
        $total_percentual +=$objCambio->camb_frete;
        $total_percentual = (int) $total_percentual / 100;

        if ($id_plano != 99) {
            //$planos->pa_valor += $member_ship_valor->pa_valor;
            $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
            return $valor;
        } else {
            $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
            return $valor;
        }
    }

    public function logSalesTransacoes($compra = array()) {

        if (count($compra) == 0) {
            return false;
        }

        $atm = new atm();
        $distribuidores = $this->db->where('co_id', $compra->co_id)
                        ->select(array(
                            'sa_id_compra',
                            'sa_id',
                            'sa_numero',
                        ))
                        ->join('compras_sales', 'sa_id_compra=co_id')
                        ->get('compras')->result();
        ob_start();
        CHtml::berginTime();

        foreach ($distribuidores as $key => $distribuidor_value) {

            $resposta = $atm->estado_pagamento($distribuidor_value);
            $resposta = json_decode($resposta);

            echo "<pre>";
            echo "\nAtualização-sale:" . $distribuidor_value->sa_numero;
            echo "\nCompra:" . $distribuidor_value->sa_id_compra;
            echo "\nCompra:" . self::mensagemSalesTransacao($resposta->status);

            get_instance()->db->where('sa_numero', $distribuidor_value->sa_numero)
                    ->update('compras_sales', array(
                        'sa_mensagem' => self::mensagemSalesTransacao($resposta->status),
                        'sa_protocolo' => $resposta->protocol,
                        'sa_status' => $resposta->status
            ));
        }

        CHtml::endTime();
        $registro = ob_get_contents();
        ob_end_clean();
        echo $registro;
        CHtml::logexec('status_pagamento_empresa_' . date('d_m_Y'), $registro . ' em ' . date('d_m_Y_H_s_i'), 'status_pagamentos');
    }

    protected function mensagemSalesTransacao($status) {
        switch ($status) {
            case 0:
                return "Transação completada";
                break;
            case 1:
                return "Uma transação concluída com maior valor";
                break;
            case 2:
                return "Transação concluída com menor valor";
                break;
            case 3:
                return "Transação em andamento (usuário não tenha concluído o pagamento)";
                break;
            case 4:
                return "Aguarde até que a sincronização com o banco";
                break;
            case 5:
                return "Transação cancelada";
                break;
            case 6:
                return "Transação em andamento (usuário não tenha concluído o pagamento)";
                break;
            case 10:
                return "Transação não encontrada";
                break;
            case 11:
                return "Autenticação de chave inválida estabelecimento";
                break;
            case 12:
                return "Estabelecimento não encontrado";
                break;
            case 13:
                return "valor de pesquisa inválido";
                break;
        }
    }

}
