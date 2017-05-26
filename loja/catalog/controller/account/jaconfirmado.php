<?php
class ControllerAccountJaconfirmado extends Controller{
    
    public function index(){
         $this->language->load('account/jaconfirmado');
         $this->data['text_usuario_ja_foi_confirmado'] = $this->language->get('text_usuario_ja_foi_confirmado');
         $this->data['text_voltar'] = $this->language->get('text_voltar');
         
        //Verificando se o template existe na pasta de origem se não pega o padrão.
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/jaconfirmado.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/jaconfirmado.tpl';
        } else {
            $this->template = 'default/template/account/jaconfirmado.tpl';
        }
        
         $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );
         
         $this->response->setOutput($this->render());
    
    }
}
