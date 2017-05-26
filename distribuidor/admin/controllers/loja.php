<?php

class Loja extends CI_Controller {
    /*
     * A função calcula_upgrade usa esse att para armazenar os planos o distribuidor deve adquirir
     * Para realizar o upgrade de conta
     */

    private $planosComprarParaUpgrade;

    function __construct() {
        parent::__construct();
        //$this->nova_compra();
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function index() {
        autenticar();
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function opcao_pagamento() {
        autenticar();
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function opcao_pagamento_pacelado() {
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function compra_servico() {
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function upgrade_plano() {
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function upgrade_plano_finaliza() {

        if (!$this->input->post('plano')) {
            echo "<p>Plano nao encontrado</p>";
            exit;
        }

        $this->planosComprarParaUpgrade = array();
        $this->calcula_upgrade(get_user()->distribuidor->getPlano()->getQuantidadePrimes(), 0, $this->input->post('plano'));

        $data['arrayRequisitos'] = $this->planosComprarParaUpgrade;
        $data['planoEscolhido'] = $this->input->post('plano');

        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));

        $this->load->view('home/index_view', $data);
    }

    public function calcula_upgrade($qtdAtual = 0, $qtdRestante = 0, $qtdAlvo = 0, $planosComprar = array()) {
        //VERIFICA QUANTOS PRIMES FALTAM PARA O OBJETIVO
        $qtdRestante = ($qtdAlvo - $qtdAtual);

        //BUSCA O PLANO COM A QUANTIDADE DE PRIMES MAIS APROXIMADA AO RESTANTE
        $planoMaisProximo = $this->db
                        ->where('pa_primes <=', $qtdRestante)
                        ->order_by('pa_primes', 'desc')
                        ->get('planos', 1)->row();

        //INCREMENTA O ARRAY DOS PLANOS QUE O SOLICITADOR DO UPGRADE TERA DE COMPRAR PARA CHEGAR NO SEU OBJETIVO
        array_push($planosComprar, $planoMaisProximo);

        /* INCREMENTA A QUANTIDADE DE PRIMES ANTERIOR COM A DO NOVO PLANO QUE SERA NECESSARIO COMPRAR */
        $qtdAtual = ($qtdAtual + $planoMaisProximo->pa_primes);

        /* DECREMENTA A QUANTIDADE QUE FALTA DE ACORDO COM A QUANTIDADE JÁ OBTIDA */
        $qtdRestante = $qtdAlvo - $qtdAtual;

        /* ENQUANTO NÃO FOR ALCANÇADA UMA QUANTIDADE EXATA */
        if ($qtdRestante > 0) {
            /* CHAMA A FUNÇÃO NOVAMENTE PASSANDO OS VALORES ATUALIZADOS E O ARRAY COM OS PLANOS QUE JÁ SERÃO NECESSÁRIOS PARA O UPGRADE */
            $this->calcula_upgrade($qtdAtual, $qtdRestante, $qtdAlvo, $planosComprar);
        } else {
            $this->planosComprarParaUpgrade = $planosComprar;
        }
    }

    public function prepara_compra_servico() {

        //VALIDA SE FOI PASSADO O PLANO QUE DESEJA COMPRAR
        if (!$this->input->post('plano')) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Plano escolhido não encontrado.")));
            redirect(base_url() . 'index.php/loja/compra_servico');
            exit;
        }
        //SE O PLANO FOR ABAIXO DE PRATA VALIDA A SELEÇÃO DOS PRODUTOS
        if ($this->input->post('plano') <= 3) {
            if (count($_POST['combo'][$this->input->post('plano')]) == 0) {
                set_notificacao(array(0 =>
                    array('tipo' => 2, 'mensagem' => "Produtos não selecionados.")));
                redirect(base_url() . 'index.php/loja/compra_servico');
                exit;
            }
        }


        $plano = $this->db->where('pa_id', $this->input->post('plano'))->get('planos')->row();

        if (count($plano) == 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Combo não selecionado")));
            redirect(base_url() . 'index.php/loja/compra_servico');
            exit;
        }

        $combosSelecionados = isset($_POST['combo'][$this->input->post('plano')]) ? $_POST['combo'][$this->input->post('plano')] : array();

        //Se o plano for maior ou igual a ouro
        if ($plano->pa_id >= 4) {
            //Busca os produtos padrões destes planos
            $combosPadrao = $this->db
                    ->join('produtos', 'pr_id = pn_produto')
                    ->where('pn_plano', $plano->pa_id)
                    ->get('produtos_padrao_plano')
                    ->result();
            //Limpa o array de selecionados para receber os padroes
            $combosSelecionados = array();
            //Armazena os produtos padrões no array de escolhidos
            foreach ($combosPadrao as $combosPadraoAtual) {
                $combosSelecionados[] = $combosPadraoAtual->pr_id;
            }
        }



        //COMO A COMPRA É DE UM UNICO SERVIÇO PASSAMOS GRUPO 0
        $idGrupoCompra = 0;
        $this->gravar_compra_servico($this->input->post('plano'), $combosSelecionados, $idGrupoCompra);

        redirect(base_url() . 'index.php/loja/finalizar_compra');
    }

    /**
     * Comprar produto na loja interna.
     */
    public function comprar_produto_loja_add_carrinho() {
        try {

            if (!$this->input->get('prod')) {
                throw new Exception('Error:Não passou id do produto');
            }
//            
//            if (get_user()->distribuidor->getAtivo() !=1) {
//                throw new Exception('Error:Usuário não ativo');   
//            }
//            
            //Verifica se o produto existe.
            $produto = produtoModel::getProduto($this->input->get('prod'));
            if (count($produto) == 0) {
                throw new Exception('Erro: Produto informado não existe');
            }

            $compraDescricao = "Compra do produto: <b>{$produto->pr_nome}</b>";
            ComprasModel::addCompraProduto($this->input->get('prod'), $compraDescricao);

            set_notificacao(1, 'Produto Inserido no carrinho com sucesso.');
            redirect(base_url('index.php/comprar_cartao'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/comprar_cartao'));
        }
    }

    public function prepara_upgrade_servico() {

        //VALIDA SE FOI PASSADO O PLANO QUE DESEJA COMPRAR
        if (count($_POST['planos']) == 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Plano(s) não encontrado(s).")));
            redirect(base_url() . 'index.php/loja/upgrade_plano');
            exit;
        }

        //FOR QUE VALIDA SE PARA CADA PLANO FORAM PASSADOS OS COMBOS (PRODUTOS) NECESSÁRIOS
        foreach ($_POST['planos'] as $idPlanoRequisito) {
            //SE O PLANO FOR ABAIXO DE PRATA VALIDA A SELEÇÃO DOS PRODUTOS
            if ($idPlanoRequisito <= 3) {
                if (count($_POST['combo'][$idPlanoRequisito]) == 0) {
                    set_notificacao(2, "Produtos não selecionados.");
                    redirect(base_url() . 'index.php/loja/upgrade_plano');
                    exit;
                }
            }
        }
        $idGrupoCompra = 0;
        //se for maiss de uma plano
        if (count($_POST['planos']) > 1) {
            //registrar um novo grupo de compra
            $this->db->insert('compras_grupo', array('cg_id' => null));

            //obter o ID desse grupo
            $idGrupoCompra = $this->db->insert_id();
        }


        //FOR QUE GRAVA AS COMPRAS
        foreach ($_POST['planos'] as $idPlanoRequisito) {
            //SE O PLANO FOR ABAIXO DE PRATA VALIDA A SELEÇÃO DOS PRODUTOS
            $combosEscolhidos = isset($_POST['combo'][$idPlanoRequisito]) ? $_POST['combo'][$idPlanoRequisito] : array();
            //CASO SEJA PLANO OURO OU TITANIUM PASSA ARRAY VAZIO POIS A FUNÇÃO VAI BUSCAR OS PADROES
            $this->gravar_compra_servico($idPlanoRequisito, $combosEscolhidos, $idGrupoCompra);
        }

        redirect(base_url() . 'index.php/loja/finalizar_compra/');
    }

    private function gravar_compra_servico($idPlano, $combosSelecionados, $idGrupoCompra) {

        //Obter o plano da compra
        $plano = $this->db->where('pa_id', $idPlano)->get('planos')->row();
        $pesoTotalCompra = 0;
        $valorTotalCompra = 0;

        //Se o plano for maior ou igual a ouro
        if ($plano->pa_id >= 4) {
            //Busca os produtos padrões destes planos
            $combosPadrao = $this->db
                    ->join('produtos', 'pr_id = pn_produto')
                    ->where('pn_plano', $plano->pa_id)
                    ->get('produtos_padrao_plano')
                    ->result();
            //Limpa o array de selecionados para receber os padroes
            $combosSelecionados = array();
            //Armazena os produtos padrões no array de escolhidos
            foreach ($combosPadrao as $combosPadraoAtual) {
                $combosSelecionados[] = $combosPadraoAtual->pr_id;
            }
        }

        $hash_boleto_new = criar_hash_boleto();

        //Inserindo a compra 
        $dadosCompra = array(
            'co_tipo' => 1,
            'co_entrega' => 1,
            'co_id_distribuidor' => get_user()->di_id,
            'co_eplano' => 1,
            'co_entrega_uf' => get_user()->di_uf,
            'co_entrega_cidade' => get_user()->di_cidade,
            'co_entrega_bairro' => get_user()->di_bairro,
            'co_entrega_cep' => get_user()->di_cep,
            'co_entrega_complemento' => get_user()->di_complemento,
            'co_entrega_numero' => get_user()->di_numero,
            'co_entrega_logradouro' => get_user()->di_endereco,
            'co_id_distribuidor' => get_user()->di_id,
            'co_id_plano' => $plano->pa_id,
            'co_total_pontos' => 0,
            'co_situacao' => 5,
            'co_pago' => 0,
            'co_forma_pgt' => 1,
            'co_frete_valor' => 0,
            'co_hash_boleto' => $hash_boleto_new,
            'co_total_valor' => $plano->pa_valor,
            'co_grupo' => $idGrupoCompra,
            'co_data_insert' => date('Y-m-d H:i:s')
        );


        $this->db->insert('compras', $dadosCompra);
        $idCompra = $this->db->insert_id();

        //Obtendo o KIT
        $kit = $this->db->where('pr_id', $plano->pa_kit)->get('produtos')->row();

        //Inserindo o KIT nos produtos da compra
        if (count($kit) > 0) {
            $this->db->insert('produtos_comprados', array(
                'pm_id_compra' => $idCompra,
                'pm_id_produto' => $kit->pr_id,
                'pm_quantidade' => 1,
                'pm_pontos' => $kit->pr_pontos,
                'pm_valor' => $kit->pr_valor - $kit->pr_desconto_distribuidor,
                'pm_valor_total' => $kit->pr_valor - $kit->pr_desconto_distribuidor,
                'pm_tipo' => 2
            ));

            $pesoTotalCompra += $kit->pr_peso;
        }



        foreach ($combosSelecionados as $idProdutoSelecionado) {

            $produtosCombo = $this->db
                            ->where('pr_id', $idProdutoSelecionado)
                            ->get('produtos')->row();

            $this->db->insert('produtos_comprados', array(
                'pm_id_compra' => $idCompra,
                'pm_id_produto' => $produtosCombo->pr_id,
                'pm_quantidade' => 1,
                'pm_pontos' => $produtosCombo->pr_pontos,
                'pm_valor' => $produtosCombo->pr_valor - $produtosCombo->pr_desconto_distribuidor,
                'pm_valor_total' => $produtosCombo->pr_valor - $produtosCombo->pr_desconto_distribuidor,
                'pm_tipo' => 1
            ));

            $pesoTotalCompra += $produtosCombo->pr_peso;
        }

        //Atualizar o peso da compra
        $this->db->where('co_id', $idCompra)->update('compras', array(
            'co_peso_total' => $pesoTotalCompra
        ));

        $compraAtualizada = $this->db->where('co_id', $idCompra)->get('compras')->row();

        set_compra($compraAtualizada);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function configurar_endereco() {

        $this->db->where('co_id', get_compra()->co_id)->update('compras', array(
            'co_entrega' => 0,
            'co_id_distribuidor' => get_user()->di_id,
            'co_entrega_uf' => get_user()->di_uf,
            'co_entrega_cidade' => get_user()->di_cidade,
            'co_entrega_bairro' => get_user()->di_bairro,
            'co_entrega_cep' => get_user()->di_cep,
            'co_entrega_numero' => get_user()->di_numero,
            'co_entrega_logradouro' => get_user()->di_endereco,
            'co_entrega_complemento' => get_user()->di_complemento
        ));

        redirect(base_url('index.php/loja/para_meu_id'));
        exit;
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function sub_categoria_ajax() {
        $cats = $this->db->where('ca_pai', $this->uri->segment(3))->get('categorias_produtos')->result();
        echo json_encode($cats);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function para_meu_id() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function retirar_em_cd() {

        if ($this->input->post('cd_escolhido')) {
            $this->db->where('co_id', get_compra()->co_id)->update('compras', array(
                'co_entrega' => 0,
                'co_id_cd' => $this->input->post('cd_escolhido')
            ));

            $this->db->where('pm_id_compra', get_compra()->co_id)->delete('produtos_comprados');

            $this->atualizar_compra();
            redirect(base_url('index.php/loja/carrinho'));
            exit;
        }

        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function receber_em_casa() {


        $this->db->where('pm_id_compra', get_compra()->co_id)->delete('produtos_comprados');


        $user_compra = $this->db
                        ->join('cidades', 'ci_id=di_cidade')
                        ->where('di_id', get_compra()->co_id_distribuidor)->get('distribuidores')->result();

        $this->db->where('co_id', get_compra()->co_id)->update('compras', array(
            'co_id_cd' => 0,
            'co_entrega' => 1,
            'co_entrega_uf' => $user_compra[0]->di_uf,
            'co_entrega_cidade' => $user_compra[0]->di_cidade,
            'co_entrega_bairro' => $user_compra[0]->di_bairro,
            'co_entrega_cep' => $user_compra[0]->di_cep,
            'co_entrega_numero' => $user_compra[0]->di_numero,
            'co_entrega_logradouro' => $user_compra[0]->di_endereco,
            'co_entrega_complemento' => $user_compra[0]->di_complemento
        ));


        $this->atualizar_compra();

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function carrinho() {

        $compra = $this->db
                ->where('co_id', get_compra()->co_id)->get('compras')
                ->result();
        if ($compra[0]->co_entrega == 0 && $compra[0]->co_id_cd == 0) {
            redirect(base_url('index.php/loja'));
        }

        $compra = $this->db
                        ->where('co_id', get_compra()->co_id)->update('compras', array(
            'co_frete_gratis' => 0,
            'co_frete_tipo' => 0,
            'co_frete_valor' => 0
        ));


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function configurar_kit() {

        if ($this->input->post('kit')) {

            foreach ($_POST['kit'] as $k => $p) {
                $id_kit = $k;
                $this->db->where('pk_kit_comprado', $id_kit)->delete('produtos_kit_opcoes');
                foreach ($p as $ps) {
                    $this->db->insert('produtos_kit_opcoes', array(
                        'pk_kit_comprado' => $id_kit,
                        'pk_produto' => $ps
                    ));
                }
            }

            redirect(base_url('index.php/loja/finalizar_compra'));
            exit;
        }


        //Dados da compra
        $data['compra'] = $compra = $this->db->where('co_id', get_compra()->co_id)->get('compras')->row();

        //dados dos combos
        $data['kits'] = $this->db
                        ->join('produtos', 'pr_id = pm_id_produto')
                        ->where('pm_tipo', 1)
                        ->where('pm_id_compra', $compra->co_id)
                        ->get('produtos_comprados')->result();

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function produtos_ajax() {
        if (get_compra()->co_id_cd == 0) {
            $p = $this->db
                            ->where('pr_categoria', $this->uri->segment(3))
                            ->where('pr_vender', 1)
                            ->where('pr_venda <>', 3)
                            ->get('produtos')->result();
        } else {
            $p = $this->db
                            ->query("
		SELECT * FROM produtos_do_cd
			 JOIN produtos ON pr_id = pc_id_produto
			 WHERE pc_id_cd = " . get_compra()->co_id_cd . "
			 AND pr_categoria = " . $this->uri->segment(3) . "
			 GROUP BY pr_id
			 HAVING SUM(pc_entrada) - SUM(pc_saida) > 0
		")->result();
        }
        echo json_encode($p);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function estoque($prod) {

        $origem = get_compra()->co_id_cd == 0 ? 1 : 2;

        $ja_add_compra = $this->db
                        ->where('pm_id_compra', get_compra()->co_id)
                        ->where('pm_id_produto', $prod)
                        ->get('produtos_comprados')->result();

        $ja_add_compra = isset($ja_add_compra[0]) ? $ja_add_compra[0]->pm_quantidade * count($ja_add_compra) : 0;

        if ($origem == 1) {
            $es = $this->db->select_sum('pr_estoque')->where('pr_id', $prod)
                            ->get('produtos')->result();
            return ($es[0]->pr_estoque - $ja_add_compra);
        } elseif ($origem == 2) {
            $es = $this->db->query("
			  SELECT SUM(pc_entrada)-SUM(pc_saida) as estoque FROM produtos_do_cd
			  WHERE pc_id_cd = " . get_compra()->co_id_cd . "
			AND pc_id_produto = " . $prod . "
			")->result();
            return ($es[0]->estoque - $ja_add_compra);
        }
    }

    public function add_produto_car_ajax() {

        $estoque = $this->estoque($_POST['produtos']);
        if ($estoque > 0) {

            $quantida_inserir = $_POST['qtd'] >= $estoque ? $estoque : $_POST['qtd'];

            if ($_POST['qtd'] > $estoque) {
                set_notificacao(array(0 =>
                    array('tipo' => 1, 'mensagem' => "Existe apenas $quantida_inserir unidade
			   desse produto em estoque
			   que foi adicionado em seu pedido.")));
            }

            //Dados do produto
            $prod = $this->db->where('pr_id', $_POST['produtos'])->get('produtos')->row();

            $pontos_produto = $prod->pr_pontos;
            $preco_produto = $prod->pr_valor;

            if (get_user()->distribuidor->getAtivo() == 1) {
                $preco_produto = $prod->pr_valor - $prod->pr_desconto_distribuidor;
            }

            $cadastrado = $this->db
                            ->where('pm_id_compra', get_compra()->co_id)
                            ->where('pm_id_produto', $prod->pr_id)
                            ->where('pm_valor', ($prod->pr_valor - $prod->pr_desconto_distribuidor))
                            ->get('produtos_comprados')->num_rows;




            //Fim de onde procura o preço do produto

            if ($cadastrado == 0) {
                $this->db->insert('produtos_comprados', array(
                    'pm_id_produto' => $prod->pr_id,
                    'pm_id_compra' => get_compra()->co_id,
                    'pm_quantidade' => $quantida_inserir,
                    'pm_pontos' => $pontos_produto,
                    'pm_valor' => $preco_produto,
                    'pm_tipo' => $prod->pr_ativacao,
                    'pm_valor_total' => $preco_produto * $quantida_inserir
                ));
            } else {
                $this->db->query("UPDATE `produtos_comprados` 
			  SET `pm_quantidade` = pm_quantidade+1,
			  `pm_valor_total` = (pm_valor*pm_quantidade)  
			  WHERE `pm_id_compra` = " . get_compra()->co_id . " AND `pm_id_produto` = " . $prod->pr_id);
            }
        } else {#estoque
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Quantidade indisponível no estoque")));
        }
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function remover_carrinho_ajax() {

        $this->db->where('pm_id', $this->uri->segment(3))->delete('produtos_comprados');
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function get_carrinho_ajax() {
        sleep(1);
        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . '_view');
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function para_minha_rede() {

        if ($this->input->post('ni_escolhido')) {

            $this->db->where('co_id', get_compra()->co_id)
                    ->update('compras', array(
                        'co_id_distribuidor' => $this->input->post('ni_escolhido'),
                        'co_id_comprou' => get_user()->di_id
            ));



            redirect(base_url('index.php/loja/para_meu_id'));
            exit;
        }


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function atualizar_compra() {
        $compra = $this->db
                        ->join('cidades', 'ci_id=co_entrega_cidade')
                        ->where('co_id', get_compra()->co_id)->get('compras')->result();
        set_compra($compra[0]);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function nova_compra() {


        if (get_compra() == false) {
            $hash_boleto_new = criar_hash_boleto();



            $this->db->insert('compras', array(
                'co_tipo' => 1,
                'co_entrega' => 0,
                'co_entrega_uf' => get_user()->di_uf,
                'co_entrega_cidade' => get_user()->di_cidade,
                'co_entrega_bairro' => get_user()->di_bairro,
                'co_entrega_cep' => get_user()->di_cep,
                'co_entrega_complemento' => get_user()->di_complemento,
                'co_entrega_numero' => get_user()->di_numero,
                'co_entrega_logradouro' => get_user()->di_endereco,
                'co_id_distribuidor' => get_user()->di_id,
                'co_hash_boleto' => $hash_boleto_new
            ));
            $id = $this->db->insert_id();
            $compra = $this->db->where('co_id', $id)->get('compras')->result();
            set_compra($compra[0]);
        }
    }

    /* -----------------------------------------------------------------
     * Desconto em produtos DD
     * --------------------------------------------------------------- */

    private function _desconto_produtos_dd() {
        $produtos = $this->db
                        ->join('produtos', 'pr_id=pm_id_produto')
                        ->where('pm_id_compra', get_compra()->co_id)
                        ->get('produtos_comprados')->result();

        $valor_total_compra = 0;
        foreach ($produtos as $p) {
            $valor_produto = ($p->pr_valor - $p->pr_desconto_cd);
            $valor_total_compra += $valor_produto * $p->pm_quantidade;
            $this->db->where('pm_id', $p->pm_id)->update('produtos_comprados', array(
                'pm_valor' => $valor_produto
            ));
        }

        $this->db->where('co_id', get_compra()->co_id)->update('compras', array(
            'co_total_valor' => $valor_total_compra
        ));
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function escolher_frete() {

        $this->atualizar_compra();
        //Escolheu o frete;
        if ($this->input->post('frete')) {

            #-Verifica se é uma compra DD
            $compra_dd = $this->input->post('dd');
            #-Armazena os pontos da compra atual
            $pontos_compra = get_compra()->co_total_pontos;


            #-Se for compra DD zera os pontos da compra;
            if ($compra_dd == 1) {
                $pontos_compra = 0;
                self::_desconto_produtos_dd();
                $this->atualizar_compra();
            }



            list($tipo_frete, $valor) = explode('/', $this->input->post('frete'));

            $entrega = 0;

            if ($tipo_frete == 'Retirar') {
                $entrega = 0;
            } else {
                $entrega = 1;
            }

            if ($valor == 'gratis' || $tipo_frete == 'Retirar') {

                $this->db->where('co_id', get_compra()->co_id)
                        ->update('compras', array(
                            'co_entrega' => $entrega,
                            'co_frete_valor' => 0.0,
                            'co_frete_gratis' => 1,
                            'co_total_pontos' => $pontos_compra,
                            'co_e_dd' => $compra_dd,
                            'co_frete_tipo' => $tipo_frete
                ));
            } else {

                $this->db->where('co_id', get_compra()->co_id)
                        ->update('compras', array(
                            'co_entrega' => $entrega,
                            'co_e_dd' => $compra_dd,
                            'co_total_pontos' => $pontos_compra,
                            'co_frete_valor' => number_format(str_ireplace(',', '.', $valor), 2, '.', ''),
                            'co_frete_tipo' => $tipo_frete
                ));
            }

            $compra = $this->db->where('co_id', get_compra()->co_id)->get('compras')->row();


            redirect(base_url('index.php/loja/finalizar_compra'));
            exit;
        }

        #-Não existe o post frete
        //Dados da compra
        $compra = $this->db->where('co_id', get_compra()->co_id)->get('compras')->row();

        $comprasModel = new ComprasModel($compra);

        $data['frete'] = $this->correios->calcular_frete(get_user()->di_cep, $comprasModel->pesoTotalCompra());


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function finalizar_compra() {
        $compra = $this->db->where('co_id', get_compra()->co_id)->get('compras')->result();
        set_compra($compra[0]);
        if ($compra[0]->co_entrega == 1 && $compra[0]->co_frete_gratis == 0 && $compra[0]->co_frete_valor == 0) {
            redirect(base_url('index.php/loja/escolher_frete'));
            exit;
        }


        //Enviar e-mail de compra
        $id_compra = get_compra()->co_id;
        if ($compra[0]->co_total_valor == 0) {
            $this->db->where('co_id', $id_compra)->update('compras', array('co_situacao' => -1));
            redirect(base_url("index.php/pedidos/"));
        }
        set_compra(0);
        redirect(base_url("index.php/loja/pagamento?c=$id_compra"));
        exit;
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function pagamento() {

        if (!isset($_GET['c']) || $_GET['c'] == '') {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Compra não encontrada!")));
            redirect(base_url('index.php/'));
            exit;
        }

        $id_compra = $_GET['c'];

        //registor de pagamentos
        $compra = $this->db->where('co_id', $id_compra)->get('compras')->row();

        //SE NAO ENCONTRAR A COMPRA
        if (count($compra) == 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Compra não encontrada!")));
            redirect(base_url('index.php/'));
            exit;
        }

        if ($this->input->post('forma_pag')) {

            //Ativa o plano atual.
            $meuPlanoAtual = $this->db
                    ->where('co_pago', 1)
                    ->where('co_id', $id_compra)
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


            $fp = $this->input->post('forma_pag');


            if ($fp == 1) {
                $this->db->where('co_id', $id_compra)->update('compras', array(
                    'co_forma_pgt_txt' => 'Boleto Bancário',
                    'co_forma_pgt' => 1,
                    'co_situacao' => 5
                ));

                $this->db->insert('compra_historico_pagamento', array(
                    'ch_id_compra' => $id_compra,
                    'ch_forma_pag' => 'Boleto Bancário'
                ));

                redirect(base_url('index.php/boleto/config_boleto?c=' . $id_compra));
            }

            if ($fp == 2) {
                $this->db->where('co_id', $id_compra)->update('compras', array(
                    'co_forma_pgt_txt' => 'Dinheiro',
                    'co_forma_pgt' => $fp,
                    'co_situacao' => 5
                ));

                $this->db->insert('compra_historico_pagamento', array(
                    'ch_id_compra' => $id_compra,
                    'ch_forma_pag' => 'Dinheiro'
                ));
                redirect(base_url('index.php/pedidos'));
            }


            //Pagamentos com bônus	
            if ($fp == 3) {

                redirect(base_url('index.php/pedidos/confirmar_pagamento?id_pedido=' . $id_compra));
                //Cartão de credito
            }
            if ($fp == 4) {
                ##Conta como paga
                $this->db->where('co_id', $id_compra)->update('compras', array(
                    'co_forma_pgt_txt' => 'Cartão de crédito',
                    'co_forma_pgt' => $fp,
                    'co_pago' => 0,
                    'co_situacao' => 5
                ));

                redirect(base_url('index.php/cielo/transacao?c=' . $id_compra . "&bandeira=" . $_POST['bandeira'] . "&parcelas=" . $_POST['parcelas']));
            }


            if ($fp == 8) {

                $this->db->where('co_id', $id_compra)->update('compras', array(
                    'co_forma_pgt_txt' => 'AstroPay',
                    'co_forma_pgt' => 8,
                    'co_situacao' => 5
                ));

                $this->db->insert('compra_historico_pagamento', array(
                    'ch_id_compra' => $id_compra,
                    'ch_forma_pag' => 'AstroPay'
                ));

                //Gerar dados tabela astropay 
                $this->db->insert('registro_astropay', array(
                    'rp_id' => NULL,
                    'rp_compra' => $id_compra,
                    'rp_distribuidor' => get_user()->di_id,
                    'rp_data' => date('Y-m-d H:i:s')
                ));

                $invoice = mysql_insert_id();

                redirect(base_url('index.php/astropay/create/?c=' . $invoice));
            }


            if ($fp == 9) {
                $this->db->where('co_id', $id_compra)->update('compras', array(
                    'co_forma_pgt_txt' => 'Deposito Empresarial Identificado',
                    'co_forma_pgt' => $fp,
                    'co_situacao' => 5
                ));

                $this->db->insert('compra_historico_pagamento', array(
                    'ch_id_compra' => $id_compra,
                    'ch_forma_pag' => 'Deposito Empresarial Identificado'
                ));
                redirect(base_url('index.php/pedidos'));
            }

            if ($fp == 10) {
                $this->db->where('co_id', $id_compra)->update('compras', array(
                    'co_forma_pgt_txt' => 'Transferência/Doc',
                    'co_forma_pgt' => $fp,
                    'co_situacao' => 5
                ));

                $this->db->insert('compra_historico_pagamento', array(
                    'ch_id_compra' => $id_compra,
                    'ch_forma_pag' => 'Transferência/Doc'
                ));
                redirect(base_url('index.php/pedidos'));
            }


            //Pagamento via voucher upline franqueado

            if ($fp == 11) {

                //Dados Patrocinador
                $patrocinador = $this->db->query("
		   SELECT * 
                    FROM  `distribuidores` 
                    WHERE `di_id`=" . get_user()->di_ni_patrocinador . "
                    "
                        )->row();


                //Envia o e-mail para patrocinador
                email_patrocinador($id_compra, $patrocinador);

                //Envia para o primeiro UPLINE com saldo acima de US$1600,00
                email_primeiro_upline($id_compra, $this->primeiro_upline());

                //Envia para o segundo UPLINE com saldo acima de US$3100,00
                email_segundo_upline($id_compra, $this->segundo_upline());

                //Envia o e-mail para system@newNossa Empresa.com
                email_solicitacao_voucher($id_compra);

                //Envia e-mail para quem solicitou pagamento via voucher
                email_pagamento_voucher();

                //Notificação da solicitação de ativação
                set_notificacao(array(0 =>
                    array('tipo' => 1, 'mensagem' => "Ativação imediata solicitada com sucesso.")));

                redirect(base_url());
            }
        }



        $data['compra'] = $this->db->where('co_id', $_GET['c'])->get('compras')->row();
        $compraModel = new ComprasModel($data['compra']);

        $data['valorTotalCompra'] = $compraModel->valorCompra();
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
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
   "
                )->result();

        foreach ($uplines as $up) {
            if ($up->saldo > $SaldoPrimeiroUpline->valor && $up->saldo < $SaldoSegundoUpline->valor) {
                $emailUpline = $up->di_email;
                return $emailUpline;
                exit;
            }
        }
    }

    public function segundo_upline() {

        //Valor saldo uplines  	 
        $SaldoSegundoUpline = $this->db->where('field', 'segundo_upline_saldo')->get('config')->row();

        //Query que pega todos os distribuidores ativos e soma os bonus  
        $uplines = $this->db->query("
   SELECT di_id,di_email,(SELECT SUM(cb_credito)) as saldo
   FROM `conta_bonus`JOIN distribuidores 
   ON `cb_distribuidor`=`di_id` 
   WHERE `di_ativo`=1
   GROUP BY di_id
   "
                )->result();

        $i = 0;
        for ($i == 0; $i < count($uplines); $i++) {
            if ($uplines[$i]->saldo > $SaldoSegundoUpline->valor) {
                $emailUpline = $uplines[$i + 1]->di_email;
                return $emailUpline;
                exit;
            }
        }
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO
     * --------------------------------------------------------------- */

    public function saida_credito($di_id, $compra_id, $id_cd) {

        #verifico já é ativo outros meses
        $comprou_kit_1 = $this->db
                        ->join('produtos_comprados', 'pm_id_compra= co_id')
                        ->join('produtos', 'pr_id = pm_id_produto')
                        ->select(array('co_id'))
                        ->where('co_id_distribuidor', $di_id)
                        ->where('co_pago', 1)
                        ->where('pr_kit_tipo', 1)
                        ->get('compras', 1)->num_rows;


        $prods_ativacao = $this->db->query("
		SELECT `pm_id` FROM `produtos_comprados` 
		JOIN compras ON co_id = `pm_id_compra`
		JOIN produtos ON pr_id = `pm_id_produto`
		WHERE 
		co_id_distribuidor =  " . $di_id . "  
		AND co_data_compra >= '" . date('Y-m-01') . " 00:00:00'
		AND `pr_ativacao` = 1
		AND co_id = {$compra_id}
		")->num_rows;


        $ativo_mes = $this->db->where('cp_distribuidor', $di_id)
                        ->where('cp_data >', date('Y-m-01 00:00:00'))
                        ->get('credito_repasse')->num_rows;

        if ($comprou_kit_1 > 0 && $ativo_mes == 0 && $prods_ativacao >= 2) {

            $this->db->insert('credito_repasse', array(
                'cp_cd' => $id_cd,
                'cp_credito' => 0,
                'cp_debito' => 79.0,
                'cp_distribuidor' => $di_id,
                'cp_time' => time()
            ));
        }
    }
    
    public function pagar_transparente() {

        $this->lang->load('distribuidor/distribuidor/atm_pagamento');


        if (!isset($_REQUEST['c']) && empty($_REQUEST['c'])) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('error_compra_invalida'))));
            redirect(base_url());
            return false;
        }

        //Verificando se asenha é valida
        if (!seguranca::validar_senha_seguranca($this->input->post('senha'), get_user())) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => $this->lang->line('label_senha_seguranca_invalida'))));
            redirect(base_url());
            return false;
        }

        if (isset($_REQUEST['co_parcelado']) && $_REQUEST['co_parcelado'] == 1) {

            $request_paymentMethod = explode('-', $_REQUEST['paymentMethod']);
            if (count($request_paymentMethod) > 1) {
                $_REQUEST['paymentMethod'] = $request_paymentMethod[0];
                $_REQUEST['wireType'] = $request_paymentMethod[1];
            } else {
                $_REQUEST['paymentMethod'] = $_REQUEST['paymentMethod'];
            }
        } else {

            $request_paymentMethod = explode('-', $_REQUEST['paymentMethod']);
            if (count($request_paymentMethod) > 1) {
                $_REQUEST['paymentMethod'] = $request_paymentMethod[0];
                $_REQUEST['wireType'] = $request_paymentMethod[1];
            } else {
                $_REQUEST['paymentMethod'] = $_REQUEST['paymentMethod'];
            }

            if (!isset($_REQUEST['paymentMethod']) && empty($_REQUEST['paymentMethod'])) {
                set_notificacao(array(0 =>
                    array('tipo' => 2, 'mensagem' => $this->lang->line('error_selecione_forma_pagamento'))));
                redirect(base_url());
                return false;
            }
        }


        //Verificanod se escolheu o cartão e digitou o código de acesso.
        if (isset($_REQUEST['paymentMethod']) && $_REQUEST['paymentMethod'] == 2) {
            //Se não digitou o codigo de acesso.
            if (isset($_REQUEST['debitCardAccessCode']) && empty($_REQUEST['debitCardAccessCode'])) {
                set_notificacao(array(0 =>
                    array('tipo' => 2, 'mensagem' => $this->lang->line('error_nao_digitou_codigo_acesso'))));
                redirect(base_url());
            }
        }

        //Parcelando a compra.
        if (isset($_REQUEST['co_parcelado']) && $_REQUEST['co_parcelado'] == 1) {
            $valor_entrada = ComprasModel::gerar_parcelas_compras(get_user());
            $this->db->where('co_id', $_REQUEST['c'])->update('compras', array(
                'co_parcelado' => 1,
                'co_situacao' => 12
            ));
        } else {

            $this->db->where('co_id', $_REQUEST['c'])->update('compras', array(
                'co_parcelado' => 0,
            ));
        }

        //Setando a forma de pagamento.
        $forma_pagamento = '';
        switch ($_REQUEST['paymentMethod']) {
            case 0:
                $forma_pagamento = 'Saldo '. ConfigSingleton::getValue("name_plataforma_pagamento") .' (EwC ou EWC voucher) ';
                //Forma de Pagamento.
                ComprasModel::setForma_pagamento($_REQUEST['c'], 13);
                break;
            case 109:
                $forma_pagamento = 'paymentMethod';
                //Forma de Pagamento.
                ComprasModel::setForma_pagamento($_REQUEST['c'], 17);
                break;
        }

        if ($_REQUEST['paymentMethod'] == 13 && $_REQUEST['wireType'] == 0) {
            $forma_pagamento = 'Wire transfer Dolar';
            //Forma de Pagamento.
            ComprasModel::setForma_pagamento($_REQUEST['c'], 14);
        }
        if ($_REQUEST['paymentMethod'] == 13 && $_REQUEST['wireType'] == 1) {
            $forma_pagamento = 'Wire transfer Euro';
            //Forma de Pagamento.
            ComprasModel::setForma_pagamento($_REQUEST['c'], 15);
        }
        if ($_REQUEST['paymentMethod'] == 13 && $_REQUEST['wireType'] == 2) {
            $forma_pagamento = 'Wire transfer Pesos Dominicanos';
            //Forma de Pagamento.
            ComprasModel::setForma_pagamento($_REQUEST['c'], 21);
        }
        if ($_REQUEST['paymentMethod'] == 13 && $_REQUEST['wireType'] == 3) {
            $forma_pagamento = 'Wire transfer Pesos Colombianos';
            //Forma de Pagamento.
            ComprasModel::setForma_pagamento($_REQUEST['c'], 20);
        }
        if ($_REQUEST['paymentMethod'] == 13 && $_REQUEST['wireType'] == 4) {
            $forma_pagamento = 'Wire transfer Soles Peruanos';
            //Forma de Pagamento.
            ComprasModel::setForma_pagamento($_REQUEST['c'], 19);
        }
        if ($_REQUEST['paymentMethod'] == 13 && $_REQUEST['wireType'] == 5) {
            $forma_pagamento = 'Wire transfer Dolar Equador';
            //Forma de Pagamento.
            ComprasModel::setForma_pagamento($_REQUEST['c'], 18);
        }

        $this->db->where('co_id', $_REQUEST['c'])->update('compras', array('co_forma_pgt_txt' => $forma_pagamento));

        $co_id = $_REQUEST['c'];

        //Pagamento normal.
        if ($_REQUEST['paymentMethod'] == 109) {

            //Dados Patrocinador
            $patrocinador = $this->db->query("
		   SELECT * 
                    FROM  `distribuidores` 
                    WHERE `di_id`=" . get_user()->di_ni_patrocinador . "
                    ")->row();

            //Envia o e-mail para patrocinador
            email_patrocinador($_REQUEST['c'], $patrocinador);
            //Envia para o primeiro UPLINE com p acima de US$1600,00
            email_primeiro_upline($_REQUEST['c'], $this->primeiro_upline());
            //Envia para o segundo UPLINE com saldo acima de US$3100,00
            email_segundo_upline($_REQUEST['c'], $this->segundo_upline());
            //Envia o e-mail para system@newNossa Empresa.com
            email_solicitacao_voucher($_REQUEST['c']);
            //Envia e-mail para quem solicitou pagamento via voucher
            email_pagamento_voucher();

            //Notificação da solicitação de ativação
            set_notificacao(array(0 =>
                array('tipo' => 1, 'mensagem' => "Ativação imediata solicitada com sucesso.")));

            redirect(base_url());
            return false;
        }


        //Pega a compra do distribuidor.
        $compra = $this->db->where('co_id', $co_id)
                        ->get('compras')->row();


        //Se a compra ja foi paga ou não existe
        if (count($compra) == 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('error_compra_invalida'))));
            redirect(base_url());
            return false;
        }


        if ($compra->co_pago == 1) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('error_compra_invalida'))));
            redirect(base_url());
            return false;
        }
        
        // corresponde ao fechamento do pedido no sistema (o parametro 1)
        $this->db->where('co_id',$co_id)
        ->update('compras', array('co_situacao_pedido' => 1));

        //Criando o redirecionamento
        atm::builder_pamento_transparente($compra, base_url('index.php/atm_pagamento/pagamentoCartao'), $_REQUEST);
    }

    public function pagar_cartao() {


        if (!isset($_REQUEST['c']) && empty($_REQUEST['c'])) {
            return false;
        }

        $co_id = $_REQUEST['c'];

        //Pega a compra do distribuidor.
        $compra = $this->db->where('co_id_distribuidor', get_user()->di_id)
                        ->where('co_tipo', 100)
                        ->where('co_id', $co_id)
                        ->get('compras')->row();

        //Setando a forma de pagamento.
        $forma_pagamento = '';
        switch ($_REQUEST['paymentMethod']) {
            case 0:
                $forma_pagamento = 'Saldo '. ConfigSingleton::getValue("name_plataforma_pagamento") .' (EwC ou EWC voucher) ';
                ComprasModel::setForma_pagamento($_REQUEST['c'], $forma_pagamento);
                break;
            case 8:
                $forma_pagamento = 'Wire transfer Dolar / Wire transfer Euro';
                ComprasModel::setForma_pagamento($_REQUEST['c'], $forma_pagamento);
                break;
            case 109:
                $forma_pagamento = 'paymentMethod';
                ComprasModel::setForma_pagamento($_REQUEST['c'], $forma_pagamento);
                break;
        }

        //Se a compra ja foi paga não existe
        if (count($compra) == 0) {
            return false;
        }
        //Descrição da compra.
        $descricao = "Pedido Nº:" . $compra->co_id . " Compra cartão InterCash";

        // corresponde ao fechamento do pedido no sistema o parametro 1
        $this->db->where('co_id',$co_id)
        ->update('compras', array('co_situacao_pedido' => 1));
        
        //Criando o redirecionamento
        atm::builder_pamento_transparente($compra, base_url('index.php/atm_pagamento/pagamentoCartao'), $_REQUEST, $descricao);
    }

    public function addProduto() {
        $this->lang->load('distribuidor/distribuidor/addproduto');
        try {

            if (!$this->input->post('pr_id')) {
                throw new Exception($this->lang->line('error_produto_nao_selecionado'));
            }

            $id_pedido = lojaModel::comprarProduto($this->input->post('pr_id'));

            if (!$id_pedido) {
                throw new Exception($this->lang->line('error_nao_foi_possivel_realizar_compra'));
            }

            redirect(base_url("index.php/pedidos/confirmar_pagamento?type=&id_pedido={$id_pedido}"));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/comprar_cartao'));
        }
    }

    public function atualizar_carrinho() {
        // var_dump($this->input->get('cart')); exit;
        try {
            if (!$this->input->get('cart')) {
                throw new Exception('Erro: Não foi possível incluir o produto');
            }

            if (!$this->input->get('pm_quantidade')) {
                throw new Exception('Erro:Informe uma quantidade');
            }

            ComprasModel::atualizarCarrinho($this->input->get('cart'), $this->input->get('pm_quantidade'));

            set_notificacao(1, 'Produto atualizado com sucesso.');
            redirect(base_url('index.php/loja/carrinho_compra'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/loja/carrinho_compra'));
        }
    }

    public function carrinho_compra() {

        $data['pagina'] = "carrinho/index";
        $this->load->view('home/index_view', $data);
    }

    public function excluir_produto_carrinho() {

        try {

            if (!$this->input->get('id_prod')) {
                throw new Exception('');
            }
            if (!$this->input->get('id_pedido')) {
                throw new Exception('');
            }

            $idProd   = $this->input->get('id_prod');
            $idCompra = $this->input->get('id_pedido');
            if (!ComprasModel::removerProduto($idCompra, $idProd)) {
                throw new Exception('');
            };

            redirect(base_url('index.php/loja/carrinho_compra'));
        } catch (Exception $ex) {
            redirect(base_url('index.php/loja/carrinho_compra'));
        }
    }

    public function finalizar() {

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function compra_confirmada() {

        if (!isset($_GET['c']) && $_GET['c'] == '') {
            redirect(APP_BASE_URL . APP_LOJA . '/index.php?route=account/order');
            exit;
        }

        //Pega a compra
        $compra = $this->db->where('order_id', $_GET['c'])->get('loja_order')->row();

        if (count($compra) == 0) {
            redirect(APP_BASE_URL . APP_LOJA . '/index.php?route=account/order');
            exit;
        }
        //#Se tiver Total#//
        //Compra
        $data['compra'] = $compra = $this->db
                        ->join('loja_zone', 'zone_id=shipping_zone_id', 'left')
                        ->where('order_id', $compra->order_id)->get('loja_order')->row();

        //Data Compra
        $data_compra = explode(" ", $compra->date_added);
        $data_compra = explode("-", $data_compra[0]);
        $data_compra = $data_compra[2] . "/" . $data_compra[1] . "/" . $data_compra[0];
        $data['dataCompra'] = $data_compra;


        //Forma de pagamento da Compra
        $data['totalOrders'] = $this->db
                ->where('order_id', $compra->order_id)
                ->get('loja_order_total')
                ->result();

        //Consultor
        $data['consultor'] = $this->db
                ->join('loja_customer', 'consultor_id=di_id')
                ->where('di_id', $data['compra']->consultor_id)
                ->get('distribuidores')
                ->row();

//        if (isset(get_user()->di_id) && $data['consultor']->consultor_id != get_user()->di_id) {
//            redirect(base_url());
//            exit;
//        }
//        if ($compra->pay == 0 && $this->e_pagamento_autorizado($compra)) {
//            $ComprasModel = new ComprasModel();
//            $ComprasModel->finalizarCompra($compra);
//        }
        //Loja Store
        $data['store'] = $this->db
                        ->where('store_id', $data['compra']->store_id)
                        ->get('loja_store')->row();

        //End
        //Total da Compra
        $data['total_compra'] = $compra->total;

        //Produtos
        $data['produtos'] = $this->db->where('order_id', $compra->order_id)->get('loja_order_product')->result();

        //Sando BÃ´nus
        $data['saldo_bonus'] = $this->db->query("
		 SELECT SUM(cb_credito)-SUM(cb_debito) as saldo FROM conta_bonus WHERE  
		 cb_distribuidor =  " . $compra->customer_id . "
		 ")->row();

        if (!empty($data['payment_efetuados'])) {
            $data['pay'] = $data['payment_efetuados'];
        } else {
            $data['pay'] = 0;
        }


        $data['statusAtual'] = OrderStatus::getStatusAtual($compra->order_status_id);
        $data['formasPagamentosAtivas'] = FormaPagamento::getFormasPagamentosAtivas();
        $data['OrderStatus'] = OrderStatus::getStatus();
        //Deslogar Consultor
//        $this->mensagem_logado();

        $data['compra'] = $compra;
        $this->load->view('loja/compra_confirmada_view', $data);
    }

    public function baixar() {

        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0) {
            redirect(base_url());
            exit;
        }

        $user = $this->db->where('user_id', $_SESSION['user_id'])->get('loja_user')->row();
        //Pega a compra
        $compra = $this->db->where('order_id', $this->uri->segment(3))->get('loja_order')->row();


        if (count($compra) == 0) {
            redirect(base_url());
            exit;
        }
        self::atualizar($compra->order_id, $this->input->post('status'));
        if ($this->input->post('status') == 5) {
            //Paagar a bonificação bonefica.
            bonusVendaLojaModel::pagar_bonus($compra->consultor_vendeu_id, $compra->total);
            //Atualiza o status do Compra para Processando
            $this->db->where('order_id', $compra->order_id)->update('loja_order', array(
                'pay' => 1
            ));
        }

        if ($compra->store_id != $user->store_id) {
            redirect(base_url());
            exit;
        }

        if ($compra->pay == 1) {
            redirect(base_url('index.php/loja/compra_confirmada?c=' . $compra->order_id));
            exit;
        }

        redirect(base_url('index.php/loja/compra_confirmada?c=' . $compra->order_id));
    }

    public function atualizar($order_id, $status) {
        if (!empty($order_id) && !empty($status)) {
            $this->db->where('order_id', $order_id)->update('loja_order', array(
                'order_status_id' => $status
            ));
            return true;
        } else {
            return false;
        }
    }

}

?>