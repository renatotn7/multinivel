<?php

class comprar_cartao extends CI_Controller {

    public function index() {
        autenticar();
        //Carrega pacode de linguagem. 
        $this->lang->load('distribuidor/distribuidor/cartao_view');

        $total = $this->db->select('count(*) as total')->where('pr_categoria', 7)->get('produtos')->row();

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/comprar_cartao/index/');
        $config['total_rows'] = $total->total;
        $config['per_page'] = 12;
        $config['num_links'] = 7;
        $config['last_link'] = 'Ultima';
        $config['first_link'] = 'Primeira';
        $config['suffix'] = '?produto=' . get_parameter('produto') . '&valor=' . get_parameter('valor');
        
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['pagina'] = strtolower(__CLASS__)
                . "/compra_cartao";
        $this->load->view('home/index_view', $data);
    }

    /**
     * Retorna o erro personalizado do cartão.
     * @param type $cartao_id
     */
    private function get_error_cartao($cartao_id = 0) {

        if ($cartao_id == 1) {
            return 'erro_cartao_atm_visa';
        }

        if ($cartao_id == 2) {
            return 'erro_cartao_union_pay';
        }

        if ($cartao_id == 3) {
            return 'erro_cartao_atm_master';
        }
    }

    /**
     * Retorna o numero de cartões que o usuário
     * pode tem.
     */
    private function numero_cartoes_usuario() {
        return $this->db->where('di_niv', get_user()->di_niv)
                        ->select('count(cd_distribuidor) as total')
                        ->join('compras', 'co_id=cd_compra')
                        ->join('distribuidores', 'co_id_distribuidor = di_id')
                        ->get('cartoes_distribuidor')
                        ->row()->total;
    }

    public function pagar_cartao() {

        //Carrega pacode de linguagem. 
        $this->lang->load('distribuidor/distribuidor/cartao_view');

        if (!isset($_REQUEST['co_id'])) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Compra não encontrada!")));
            redirect(base_url("index.php/comprar_cartao"));
            exit;
        }

        if (!isset($_REQUEST['di_cartao_membership']) or $_REQUEST['di_cartao_membership'] == 0) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => "Escolha um cartão!")));
            redirect(base_url("index.php/comprar_cartao"));
            exit;
        }

        $pais = $this->db->where("ci_id", get_user()->di_cidade)->get("cidades")->row()->ci_pais;

        if ($_REQUEST['di_cartao_membership'] == 1 && $pais != 1) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('erro_cartao_atm_visa'))));
            redirect(base_url("index.php/comprar_cartao"));
            exit;
        }

        if ($_REQUEST['di_cartao_membership'] == 2 && $pais == 1) {
            set_notificacao(array(0 =>
                array('tipo' => 2, 'mensagem' => $this->lang->line('erro_cartao_union_pay'))));
            redirect(base_url("index.php/comprar_cartao"));
            exit;
        }

        if ($_REQUEST['di_cartao_membership'] == 3) {
            //se BRASIL, ou EUA, ou INDIA ou COREIA DO SUL ou FILIPINAS
            if ($pais == 1 or $pais == 2 or $pais == 100 or $pais == 114 or $pais == 169) {
                set_notificacao(array(0 =>
                    array('tipo' => 2, 'mensagem' => $this->lang->line('erro_cartao_atm_master'))));
                redirect(base_url("index.php/comprar_cartao"));
                exit;
            }
        }

        $id_compra = $_REQUEST['co_id'];
        $paymentMethod = $_REQUEST['paymentMethod'];

        $cartao = $this->db->where("cm_id", $_REQUEST['di_cartao_membership'])->get("cartoes_membership")->row();
        redirect(base_url("index.php/loja/pagar_cartao?c={$id_compra}&paymentMethod={$paymentMethod}"));
    }

}
