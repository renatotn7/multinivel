<?php
class ControllerAccountConfirmar extends Controller{
    
    public function index(){
         $this->language->load('account/confirmado');
         $this->data['text_confirmado_com_sucesso'] = $this->language->get('text_confirmado_com_sucesso');
         $this->data['text_voltar'] = $this->language->get('text_voltar');
         
        //Verificando se o template existe na pasta de origem se não pega o padrão.
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/confirmar.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/confirmar.tpl';
        } else {
            $this->template = 'default/template/account/confirmar.tpl';
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
