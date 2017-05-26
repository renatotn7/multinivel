<?php

class enviar_arquivo extends CI_Controller {

    public function index() {
        $pr_id=0;
        $path = realpath(dirname(dirname(dirname(__FILE__))));
        $conf_pasta = '/public/imagem/uploads-produtos/';
        $conf_pasta_thumb = '/public/imagem/uploads-produtos/thumbs/';

        $pasta = $path . $conf_pasta;
        $pasta_thumb = $path . $conf_pasta_thumb;

        //Se a pasta não existir ele cria.
        if (!is_dir($pasta)) {
            mkdir($pasta);
        }

        if (!is_dir($pasta_thumb)) {
            mkdir($pasta_thumb);
        }

        if (!$this->input->post('pr_id')) {
            echo json_encode(array('erro' => 'error ao enviar a imagem.'));
            return false;
        }
        
        $pr_id = $this->input->post('pr_id');

        //Verifica se passou alguma imagem
        if (!isset($_FILES['arquivo'])) {
            echo json_encode(array('erro' => 'Selecione uma imagem'));
            return false;
        }

        $arquivo = $_FILES['arquivo'];
        $tipo_arquivo = explode('/', $arquivo['type']);
        /* Define os tipos de arquivos válidos (No nosso caso, só imagens) */
        $tipos = array('jpg', 'jpeg', 'png', 'gif', 'psd', 'bmp');

        //Verifica se o formato da imagem é valido.
        if (!in_array($tipo_arquivo[1], $tipos)) {
            echo json_encode(array('erro' => 'Formato inválido'));
            return false;
        }

        //criando o nome do arquivo.
        $nome = explode('.', $arquivo['name']);
        $nome = 'prod_' . substr(md5(time()), 0, 4) . '.' . $tipo_arquivo[1];
        $cominho = $pasta . $nome;

        if (!move_uploaded_file($arquivo['tmp_name'], $cominho)) {
            echo json_encode(array('erro' => 'Formato inválido'));
        }

        //Redimensionando a imagem para normal e miniatura.
        ob_start();
        //NORMAL
        $canvas = new canvas($cominho);
        $canvas->redimensiona(500, 400);
        $canvas->grava($cominho);

        //MINIATURA
        $canvas = new canvas($cominho);
        $canvas->redimensiona(157, 117);
        $canvas->grava($pasta_thumb . $nome);
        ob_clean();

        //Salvando a imagem no banco de dados.
        $this->db->insert('imagem_produto', array(
            'img_nome' => $nome,
            'img_id_poduto' => $pr_id,
        ));
        $img_id = $this->db->insert_id();

        echo json_encode(array('sucesso' => true, 'msg' => substr(base_url(), 0, -1) . $conf_pasta_thumb . $nome,'img_id'=>$img_id,'image' => $nome));
    }

}
