<?php

class carreiras extends CI_Controller {

    public function index() {
        autenticar();
        //Planos do sistema.
        $this->carreira();
    }

    public function carreira() {
        autenticar();
        $planos = $this->db->order_by('dq_id', 'asc')->get('distribuidor_qualificacao')->result();

        $data['planos'] = $planos;
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function salvar() {
        autenticar();
        
        foreach ($_POST['dq_id'] as $key => $post) {
            $this->db->where('dq_id', $post)->update('distribuidor_qualificacao', array(
                'dq_descricao' => $_POST['dq_descricao_' . $key],
                'dq_pontos' => $_POST['dq_pontos_' . $key],
                'dq_niveis' => $_POST['dq_niveis_'. $key],
                'dq_premiacoes' => $_POST['dq_premiacoes_'. $key]
            ));
        }
        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Atualizado com sucesso.")));
        redirect(base_url('index.php/carreiras'));
    }

}