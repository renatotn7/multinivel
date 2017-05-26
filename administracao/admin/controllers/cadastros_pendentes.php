<?php

class Cadastros_pendentes extends CI_Controller {

    public function index() {

        $paises_permitidos = $this->db->where('rfp_id_responsavel_fabrica', get_user()->rf_id)
                        ->select("GROUP_CONCAT(rfp_id_pais) as paises", false)
                        ->get('responsaveis_fabrica_paises')->row();

        $this->db->start_cache();

        $where = '';

        if (get_parameter('usuario')) {
            $this->db->where('di_usuario', get_parameter('usuario'));
        }

        if (get_parameter('nome')) {
            $this->db->like('di_nome', get_parameter('nome'));
        }

        if (get_parameter('cpf')) {
            $this->db->where('di_rg', get_parameter('rg'));
        }

        if (get_parameter('plano')) {
            $this->db->where('pa_id', get_parameter('plano'));
        }

        if ($this->input->get('empresa')) {
            $this->db->where('di_empresa', $this->input->get('empresa'));
        }


        $this->db->stop_cache();


        if ($paises_permitidos->paises != null) {
            $data['num_pendentes'] = $pendentes_rows = $this->db
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'co_id_plano=pa_id')
                            ->where('ci_pais IN(' . $paises_permitidos->paises . ')')
                            ->where('di_id NOT IN', '(SELECT li_id_distribuidor FROM distribuidor_ligacao)', false)
                            ->where('di_id NOT IN', '(SELECT co_id_distribuidor FROM compras WHERE co_pago = 1)', false)
                            ->where('di_excluido', 0)
                            ->get('distribuidores')
                    ->num_rows;
        } else {
            $data['num_pendentes'] = $pendentes_rows = $this->db
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'co_id_plano=pa_id')
                            ->where('di_id NOT IN', '(SELECT li_id_distribuidor FROM distribuidor_ligacao)', false)
                            ->where('di_id NOT IN', '(SELECT co_id_distribuidor FROM compras WHERE co_pago = 1)', false)
                            ->where('di_excluido', 0)
                            ->get('distribuidores')
                    ->num_rows;
        }

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/cadastros_pendentes/index/');
        $config['total_rows'] = $pendentes_rows;
        $config['per_page'] = 15;
        $config['num_links'] = 7;
        $config['last_link'] = 'Ultima';
        $config['first_link'] = 'Primeira';
        $config['suffix'] = '?usuario=' . get_parameter('usuario') . '&cpf=' . get_parameter('cpf') . '&nome=' . get_parameter('nome') . '&plano=' . get_parameter('plano');


        if ($paises_permitidos->paises != null) {
            $data['distribuidoresPendentes'] = $this->db
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'co_id_plano=pa_id')
                            ->where('ci_pais IN(' . $paises_permitidos->paises . ')')
                            ->where('di_id NOT IN', '(SELECT li_id_distribuidor FROM distribuidor_ligacao)', false)
                            ->where('di_id NOT IN', '(SELECT co_id_distribuidor FROM compras WHERE co_pago = 1)', false)
                            ->where('di_excluido', 0)
                            ->order_by('di_data_cad', 'desc')
                            ->get('distribuidores', $config['per_page'], $this->uri->segment(3))->result();
        } else {
            $data['distribuidoresPendentes'] = $this->db
                    ->join('cidades', 'ci_id=di_cidade')
                    ->join('compras', 'co_id_distribuidor=di_id')
                    ->join('planos', 'co_id_plano=pa_id')
                    ->where('di_id NOT IN', '(SELECT li_id_distribuidor FROM distribuidor_ligacao)', false)
                    ->where('di_id NOT IN', '(SELECT co_id_distribuidor FROM compras WHERE co_pago = 1)', false)
                    ->where('di_excluido', 0)
                    ->order_by('di_data_cad', 'desc')
                    ->get('distribuidores', $config['per_page'], $this->uri->segment(3))
                    ->result();
        }

        $this->db->flush_cache();

        $this->pagination->initialize($config);
        $total = $this->db->query("SELECT count(*)  as total from distribuidores
                                    join compras on co_id_distribuidor =di_id
                                    join planos on pa_id = co_id_plano
                                    where co_pago =0
                                    and co_eplano = 1
                                    and co_eupgrade=0
                                    and di_excluido=0")->row();



        $data['total'] = $total->total;
        $data['links'] = $this->pagination->create_links();

        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

}

?>