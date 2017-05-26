<?php

class ativacao_mensal extends CI_Controller {

    public static $instance;
    public static $countInstance;

    public function index() {

        $this->db->start_cache();
        if ($this->input->get('usuario')) {
            $this->db->where('di_usuario', $this->input->get('usuario'));
        }

        if ($this->input->get('plano')) {
            $this->db->where('co_id_plano', $this->input->get('plano'));
        }

        if ($this->input->get('data_ini')) {
            $this->db->where(" at_data >='" . data_to_usa($this->input->get('data_ini')) . "00:00:00'", '', false);
        }

        if ($this->input->get('data_fin')) {
            $this->db->where(" at_data <='" . data_to_usa($this->input->get('data_fin')) . "23:59:59'", '', false);
        }

        if ($this->input->get('status')) {
            if (($this->input->get('status') - 1) == 1) {

                $this->db->where("at_id > 0");
            }

            if (($this->input->get('status') - 1) == 0) {
                $this->db->where("at_id is null");
            }
        }

        $this->db->stop_cache();

        $distribuidores = $this->db->where('di_excluido', 0)
               
                        ->select(array(' co_data_compra','IF(at_id is null,0,1) as ativacao','pa_descricao','di_usuario'), FALSE)
                        ->where('di_id in(select di_id from distribuidor_ligacao)','',false)
                        ->join('registro_ativacao', 'at_distribuidor = di_id', 'left')
                        ->join('compras', 'co_id_distribuidor = di_id')
                        ->join('planos', 'co_id_plano = pa_id')
                        ->group_by('di_id')
                        ->order_by('ativacao','desc')
                        ->get('distribuidores', 10, $this->uri->segment(3))->result();


        $total = $this->db->where('di_excluido', 0)
                        ->where('di_id in(select di_id from distribuidor_ligacao)')
                        ->select('count(di_id) as total')
                        ->join('registro_ativacao', 'at_distribuidor = di_id', 'left')
                        ->get('distribuidores')->row();
        
        $this->db->flush_cache();

        $this->load->library('pagination');
        
        $config['base_url'] = base_url('index.php/ativacao_mensal/index/');
        $config['total_rows'] = count($total) > 0 ? $total->total : 0;
        $config['per_page'] = 20;
        $config['num_links'] = 7;
        $config['last_link'] = 'Ultima';
        $config['first_link'] = 'Primeira';
        $config['suffix'] = '?usuario=' . get_parameter('usuario') . '&plano=' . get_parameter('plano') . '&data_ini=' . get_parameter('data_ini') . '&data_fin=' . get_parameter('data_fin') . '&status=' . get_parameter('status');
        $this->pagination->initialize($config);

        $data['link'] = $this->pagination->create_links();
        $data['ativacao_mensal'] = $distribuidores;
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

}
