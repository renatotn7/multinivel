<?php

class usuario_api extends CI_Controller {

    public function index() {

        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function addUsuario() {
        try {

            if (!$this->input->post('api_nome')) {
                throw new Exception('Informe um nome para o usuário.');
            }

            if (!$this->input->post('api_url')) {
                throw new Exception('Informe uma url para o usuário');
            }
            $usuario = array(
                'api_nome' => $this->input->post('api_nome'),
                'api_url' => $this->input->post('api_url'),
                'api_status' => 'Ativo',
            );

            $usuario_cad = usuarioAPIModel::addUsuario($usuario);

            if (count($usuario_cad) == 0) {
                throw new Exception('Usuário não foi cadastrado');
            }

            //Gerando a token
            usuarioAPIModel::gerarToken($usuario_cad->api_id);

            //Gerando a app ID
            usuarioAPIModel::geraAppID($usuario_cad->api_id);

            //Gerando secret key
            usuarioAPIModel::geraSecretKey($usuario_cad->api_id);

            set_notificacao(1, 'Operação realizada com sucesso.');
            redirect(base_url('index.php/usuario_api'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/usuario_api'));
        }
    }

    public function AtualizarUsuario() {
        try {

            if (!$this->input->get('indet')) {
                throw new Exception('Selecione um usuário.');
            }
            $usuario_dados = array(
                'api_nome' => $this->input->post('api_nome'),
                'api_url' => $this->input->post('api_url'),
                'api_status' => $this->input->post('api_status'),
            );

            if (!usuarioAPIModel::atualiarUsuario($this->input->get('indet'), $usuario_dados)) {
                throw new Exception('Erro ao atualizar o usuario');
            }
            //Gerando nova token
             usuarioAPIModel::gerarToken($this->input->get('indet'));
            //Gerando novo secret key
            usuarioAPIModel::geraSecretKey($this->input->get('indet'));

            set_notificacao(1, 'Operação realizada com sucesso.');
            redirect(base_url('index.php/usuario_api'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/usuario_api'));
        }
    }

    public function removerUsuario() {
        try {

            if (!$this->input->get('indet')) {
                throw new Exception('Selecione um usuário.');
            }

            if (!usuarioAPIModel::removerUsuario($this->input->get('indet'))) {
                throw new Exception('Erro ao remover o usuário');
            }

            set_notificacao(1, 'Operação realizada com sucesso.');
            redirect(base_url('index.php/usuario_api'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/usuario_api'));
        }
    }

}
