<?php

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        permissao('usuario', 'visualizar', get_user(), true);
    }

    public function index() {

        $data['usuarios'] = $this->db->where('rf_tipo', 2)->get('responsaveis_fabrica')->result();
        $data['pagina'] = 'usuario/usuario';
        $this->load->view('home/index_view', $data);
    }

    public function add() {
        $data['pagina'] = 'usuario/add';
        $this->load->view('home/index_view', $data);
    }

    public function editar() {

        autenticar();
        $data['user'] = $this->db->where('rf_id', $this->uri->segment(3))
                        ->get('responsaveis_fabrica')->row();

        $data['pagina'] = 'usuario/editar';
        $this->load->view('home/index_view', $data);
    }

    public function salvar_usuario() {
        autenticar();
        $dados = array(
            'rf_nome' => $this->input->post('rf_nome'),
            'rf_fabrica' => 1,
            'rf_email' => $this->input->post('rf_email'),
            'rf_pw' => sha1($this->input->post('senha')),
            'rf_tipo' => 2,
            'rf_permissao' => (isset($_POST['permissao']) ? json_encode($_POST['permissao']) : '')
        );

        $this->db->insert('responsaveis_fabrica', $dados);
        $id_user = $this->db->insert_id();
        redirect(base_url('index.php/usuario/editar/' . $id_user));
    }

    public function editar_usuario() {
        autenticar();

        $dados = array(
            'rf_nome' => $this->input->post('rf_nome'),
            'rf_email' => $this->input->post('rf_email'),
            'rf_tipo' => 2,
            'rf_fabrica' => 1,
            'rf_permissao' => (isset($_POST['permissao']) ? json_encode($_POST['permissao']) : '')
        );

        if ($this->input->post('senha')) {
            $dados['rf_pw'] = sha1($this->input->post('senha'));
        }

        $this->db
                ->where('rf_id', $this->input->post('rf_id'))
                ->update('responsaveis_fabrica', $dados);


        redirect(base_url('index.php/usuario/editar/' . $this->input->post('rf_id')));
    }

    public function remover() {
        $this->db
                ->where('rf_id', $this->uri->segment(3))
                ->delete('responsaveis_fabrica');
        redirect(base_url('index.php/usuario/'));
    }

    /**
     * Salva as permissões de países que o 
     * usuário da fabrica pode ter.
     */
    public function permissao_pais() {
        if ($this->input->post('rfp_id_pais')) {
            $this->db->insert('responsaveis_fabrica_paises', array(
                'rfp_id_pais' => $this->input->post('rfp_id_pais'),
                'rfp_id_responsavel_fabrica' => $this->input->post('rf_id'),
            ));
        }
        redirect(base_url('index.php/usuario/editar/' . $this->input->post('rf_id') . ""));
    }

    /**
     * Salvar as permissão por empresa.
     */
    public function permissao_empresas() {
        try {

            if (!$this->input->post('empresa')) {
                throw new Exception('erro: Não foi possível incluir a permissão.');
            }
            //Verificando se já existe uma empresa.
            $permissao = $this->db->where('per_id_empresa', $this->input->post('empresa'))
                            ->where('per_id_usuario', $this->uri->segment(3))
                            ->get('permissao_empresas_usuario')->row();

            if (count($permissao) === 0) {
                $this->db->insert('permissao_empresas_usuario', array(
                    'per_id_empresa' => $this->input->post('empresa'),
                    'per_id_usuario' => $this->uri->segment(3)
                ));
            }

            set_notificacao(1, 'successo');
            redirect(base_url('index.php/usuario/editar/' . $this->uri->segment(3) . '#permissao'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/usuario/editar/' . $this->uri->segment(3) . '#permissao'));
        }
    }

    public function remover_permissao_empresas() {
        try {
            if (!$this->input->get('per_id')) {
                throw new Exception('error: Não foi possível remover a empresa.');
            }

            $this->db->where('per_id', $this->input->get('per_id'))
                    ->delete('permissao_empresas_usuario');

            set_notificacao(1, 'successo');
            redirect(base_url('index.php/usuario/editar/' . $this->uri->segment(3) . '#permissao'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/usuario/editar/' . $this->uri->segment(3) . '#permissao'));
        }
    }

    public function bloquear_usuario() {
        autenticar();
        if ($this->uri->segment(3)) {


            $usuario = $this->db->where('rf_id', $this->uri->segment(3))
                    ->get('responsaveis_fabrica')
                    ->row();

            $this->db->where('rf_id', $this->uri->segment(3))->update('responsaveis_fabrica', array(
                'rf_bloqueio' => ($usuario->rf_bloqueio == 0 ? 1 : 0)
            ));

            set_notificacao(1, "Salvo com sucesso!");
        }
        redirect(base_url('index.php/usuario'));
    }

    /**
     * Editar as permissão de países que o 
     * usuário da fabrica pode ter.
     */
    public function remover_permissao_pais() {

        if (get_parameter('rfp_id')) {
            $this->db->where('rfp_id', get_parameter('rfp_id'))
                    ->delete('responsaveis_fabrica_paises');
        }

        redirect(base_url('index.php/usuario/editar/' . get_parameter('rf_id')));
    }

}
