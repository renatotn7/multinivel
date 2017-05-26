<?php

class empresas extends CI_Controller {

    public function index() {
        autenticar();
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function add() {
        try {
            if (!$this->input->post('ep_nome')) {
                throw new Exception('Erro: O nome da empresa');
            }

            $this->db->insert('empresas', array(
                'ep_nome' => $this->input->post('ep_nome'),
                'ep_status' => $this->input->post('ep_status'),
            ));

            set_notificacao(1, 'sucesso');
            redirect(base_url('index.php/empresas/'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/empresas/'));
        }
    }

    public function remover() {
        try {
            if (!$this->input->get('remove')) {
                throw new Exception('Erro: Não encontramos a empresa');
            }
            $this->db->where('ep_id', $this->input->get('remove'))
                    ->delete('empresas');

            set_notificacao(1, 'sucesso');
            redirect(base_url('index.php/empresas/'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/empresas/'));
        }
    }

    public function atualizar() {
        try {
            if (!$this->input->post('ep_nome')) {
                throw new Exception('Erro: O nome da empresa');
            }
            
            $this->db->where('ep_id',  $this->input->post('ep_id'))->update('empresas', array(
                'ep_nome' => $this->input->post('ep_nome'),
                'ep_status' => $this->input->post('ep_status'),
            ));
            
            set_notificacao(1, 'sucesso');
            redirect(base_url('index.php/empresas/'));
            
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/empresas/'));
        }
    }

}
