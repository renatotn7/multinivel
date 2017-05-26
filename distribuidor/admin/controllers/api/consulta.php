<?php

class consulta extends CI_Controller {

    private $error = '';
    private $modulos = array('usuarios');

    private function oauth() {
        try {

            api::validarMethod();
            api::setChaveAcesso($this->input->post('token'), $this->input->post('secretKey'), $this->input->post('appID'));
            api::validarAcesso();
        } catch (Exception $ex) {
            echo json_encode(array('status' => api::codigoError(), 'mensage' => $this->handleException($ex), 'data' => null));
            exit();
        }
    }

    private function handleException($e) {
        return "Ao acessar a api ocorreu o seguinte erro: " . $e->getMessage();
        exit;
    }

    private function converte_para_xml($obj = array()) {
        return XMLSerializer::generateValidXmlFromMixiedObj($obj);
    }

    public function modulos() {
        //Valida a autorização do usuário.  
        self::oauth();
        echo json_encode($this->modulos);
    }

    public function usuarios() {
        self::oauth();
        try {

            $tipo = 'json';

            if ($this->uri->segment(4)) {
                $tipo = $this->uri->segment(4);
            }

            $usuario = api::dados_usuario($this->input->post('niv'), $this->input->post('email'));

            if (count($usuario) == 0) {
                throw new Exception('Usuário não encotrado.');
            }

            //Retorno em json.
            if ($tipo == 'json') {
                echo json_encode(array('status' => api::codigoError(), 'mensage' => '', 'data' => $usuario));
            }

            //Retorno em xml.
            if ($tipo == 'xml') {
                echo $this->converte_para_xml($usuario);
            }
        } catch (Exception $exc) {
            echo json_encode(array('status' => api::codigoError(), 'mensage' => $this->handleException($exc), 'data' => null));
        }
    }

}
