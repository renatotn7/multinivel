<?php

class Pedidos_distribuidor extends CI_Controller {

    public function index() {

        permissao('relacao_pedidos', 'visualizar', get_user(), true);

        $this->db->start_cache();

        if (get_parameter('situacao')) {
            $this->db->where('co_situacao', get_parameter('situacao'));
        }

        if (get_parameter('usuario')) {
            $this->db->where('di_usuario', get_parameter('usuario'));
        }

        if($this->input->get('empresa')){
            $this->db->where('di_empresa',$this->input->get('empresa'));
        }
        
        if (get_parameter('nome')) {
            $this->db->like('di_nome', get_parameter('nome'));
        }

        if (get_parameter('id')) {
            $this->db->like('co_id', get_parameter('id'));
        }

        if (get_parameter('pago') != 'false') {
            $this->db->where('co_pago', get_parameter('pago'));
        }

        if (get_parameter('cpf')) {
            $this->db->where('di_cpf', get_parameter('cpf'));
        }



        $this->db->stop_cache();

        $data['num_vendasDistribuidor'] = $num_pedidos = $this->db
                        ->where('co_situacao <> -1')
                        ->where('co_id_cd', 0)
                        ->order_by('co_id', 'DESC')
                        ->join('compra_situacao', 'co_situacao=st_id', 'left')
                        ->join('produtos_comprados', 'co_id=pm_id_compra')
                        ->join('produtos', 'pr_id=pm_id_produto')
                        ->join('cidades', 'ci_id=co_entrega_cidade', 'left')
                        ->join('distribuidores', 'di_id=co_id_distribuidor')
                        ->get('compras')->num_rows;

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/pedidos_distribuidor/index/');
        $config['total_rows'] = $num_pedidos;
        $config['per_page'] = 20;
        $config['num_links'] = 7;
        $config['last_link'] = 'Ultima';
        $config['first_link'] = 'Primeira';
        $config['suffix'] = '?' . http_build_query($_REQUEST);

        $data['pedidos'] = $this->db
                        ->where('co_situacao <> -1')
                        ->where('co_id_cd', 0)
                        ->order_by('co_id', 'DESC')
                        ->group_by('co_id')
                        ->join('compra_situacao', 'co_situacao=st_id', 'left')
                        // ->join('produtos_comprados', 'co_id=pm_id_compra')
                        // ->join('produtos', 'pr_id=pm_id_produto')
                        ->join('cidades', 'ci_id=co_entrega_cidade', 'left')
                        ->join('distribuidores', 'di_id=co_id_distribuidor')
                        ->get('compras', $config['per_page'], $this->uri->segment(3))->result();
                        

        $this->db->flush_cache();

        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function pedido_imprimir() {
        autenticar();
        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }

    public function cancelar_pedido() {
        $this->db->where('co_id', $this->uri->segment(3))->update('compras', array(
            'co_situacao' => '-1'
        ));
        redirect(base_url('index.php/pedidos'));
    }

    //******************
    //***
    //********************

    public function editar_pedido() {

        autenticar();
        permissao('relacao_pedidos', 'editar', get_user(), true);


        //Library finalização dos correios //system/libraries/
        $this->load->library('correios');
        $this->load->library('estoque');


//        if ($this->input->post('situacao')) {

        $codigo_ras = $this->input->post('co_frete_codigo');

        $compra = $this->db->where('co_id', $this->uri->segment(3))->get('compras')->row();

        //SE ESTIVER PAGANDO A COMPRA
        if ($this->input->post('pago') == 1 && $compra->co_pago == 0) {

            ##Inicia uma transação
            $this->db->trans_start();

            $compraModel = new ComprasModel($compra);
            $valorTotalCompra = $compraModel->valorCompra();

            $arrayCompras = array();
            $arrayCompras = $compraModel->getCompras();


            #-- coloca a compra financiada se for --#
            if ($this->input->post('forma_pagamento') == 18) {
                $distribuidor = $this->db->where('di_id', $compra->co_id_distribuidor)
                        ->get('distribuidores')
                        ->row();

                ComprasModel::gerar_parcelas_compras($distribuidor, 1);
            }

            foreach ($arrayCompras as $compra) {

                $valor_compra = $compra->co_total_valor + $compra->co_frete_valor;
                $forma_pagamento_txt = $this->db->where('fp_id', $this->input->post('forma_pagamento'))
                                ->get('formas_pagamento')->row();
                
                if (count($forma_pagamento_txt) > 0) {
                    $forma_pagamento_txt = $forma_pagamento_txt->fp_descricao;
                }

                ##Conta como paga
                $this->db->where('co_id', $compra->co_id)->update('compras', array(
                'co_forma_pgt' => $this->input->post('forma_pagamento'),
                'co_forma_pgt_txt' =>$forma_pagamento_txt,
                'co_pago' => 1,
                'co_data_compra' => date('Y-m-d H:i:s')
                ));



                #verificar se é plano para inserir as parcelas e pontos

                if ($compra->co_eplano == 1) {
                    $this->load->library('rede');
                    $this->rede->alocar($compra->co_id_distribuidor);
                    $this->load->library('planos');
                    $this->planos->lancar($compra);

                    Evoucher::lancar($compra);
                }

                #-- Lançar compra do cartão --#
                if ($compra->co_tipo == 100 && $compra->co_id_cartao != 0) {
                    $this->db->insert("cartoes_distribuidor", array(
                        "cd_distribuidor" => $compra->co_id_distribuidor,
                        "cd_id_cartao" => $compra->co_id_cartao,
                        "cd_compra" => $compra->co_id
                    ));
                }

                #-- Lançar ativação da compra --#
                $this->load->library('ativacao');
                $this->ativacao->lancar_ativacao($compra);
                #-- Lançar ativação da compra --#	
                ##Credita o CD ou a Fabrica
                if ($compra->co_id_cd != 0) {
                    $this->estoque->saida_cd($compra->co_id);
                } else {
                    $this->estoque->saida_fabrica($compra->co_id);
                }
            }

            //Se todas as operações ocorrem como esperado
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }//FIM DO PAGAMENTO DA COMPRA
//        }


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function visualizar_pedido() {
        autenticar();
        permissao('relacao_pedidos', 'editar', get_user(), true);

        //Pedido do distribuidor
        $data['c'] = $this->db
                        ->join('distribuidores', 'di_id=co_id_distribuidor')
                        ->join('cidades', 'ci_id=di_cidade')
                        ->join('compra_situacao', 'st_id=co_situacao')
                        ->join('formas_pagamento', 'fp_id=co_forma_pgt')
                        ->where('co_id', $this->uri->segment(3))->get('compras')->row();

        // var_dump($data['c']);exit;
        $this->load->view('pedidos_distribuidor/visualizar_view', $data);
    }

}

?>