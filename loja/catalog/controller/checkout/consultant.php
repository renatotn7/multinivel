<?php

class ControllerCheckoutConsultant extends Controller {

    public function index() {
        $this->language->load('checkout/consultant');

        $this->data['estado_id'] = isset($this->request->post['estados'])?$this->request->post['estados']:0;
        
        // Consultant confirm
        if (isset($this->request->post['consultor']) && $this->request->post['consultor'] != 0) {
            
//            echo $this->request->post['consultor'] . "<br>";
//            echo $this->request->post['nome_consultor'][$this->request->post['consultor']] . "<br>";
//            exit;
            
            if ($this->customer->getConsultorId() == $this->request->post['consultor']) {
                $_SESSION['distribuidor_recebera_bonus']->recebera = 0;
                $_SESSION['distribuidor_recebera_bonus']->di_id = $this->request->post['consultor'];
                $_SESSION['distribuidor_recebera_bonus']->di_usuario = $this->request->post['nome_consultor'][$this->request->post['consultor']];
            } else {
                $_SESSION['distribuidor_recebera_bonus']->recebera = 1;
                $_SESSION['distribuidor_recebera_bonus']->di_id = $this->request->post['consultor'];
                $_SESSION['distribuidor_recebera_bonus']->di_usuario = $this->request->post['nome_consultor'][$this->request->post['consultor']];
            }
            
        }

        if (isset($this->session->data['distribuidor_recebera_bonus']) 
            && $this->session->data['distribuidor_recebera_bonus'] != '' 
            && !is_null($this->session->data['distribuidor_recebera_bonus'])
            ) {

            $this->redirect($this->url->link('checkout/checkout'));
        }



        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home'),
            'text' => $this->language->get('text_home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/consultant'),
            'text' => $this->language->get('heading_title'),
            'separator' => $this->language->get('text_separator')
        );

        // Page Title
        $this->data['heading_title'] = $this->language->get('heading_title');

        // Field name
        $this->data['text_zone'] = $this->language->get('text_zone');
        
        $this->data['text_city'] = $this->language->get('text_city');
        
        $this->data['text_neighborhood'] = $this->language->get('text_neighborhood');
        
        $this->data['text_consultant'] = $this->language->get('text_consultant');

        
        // Label of select
        $this->data['entry_select_zone'] = $this->language->get('entry_select_zone');
        
        $this->data['entry_select_city'] = $this->language->get('entry_select_city');
        $this->data['entry_select_city_ok'] = $this->language->get('entry_select_city_ok');
        
        $this->data['entry_select_neighborhood'] = $this->language->get('entry_select_neighborhood');
        $this->data['entry_select_neighborhood_ok'] = $this->language->get('entry_select_neighborhood_ok');
        
        $this->data['entry_select_consultant'] = $this->language->get('entry_select_consultant');
        $this->data['entry_select_consultant_ok'] = $this->language->get('entry_select_consultant_ok');
        

        // Label button
        $this->data['button_confirm'] = $this->language->get('button_confirm');
        
        // Alerts
        $this->data['error_warning'] = $this->language->get('error_consultant');

        // action link of form
        $this->data['action'] = $this->url->link('checkout/consultant');

        
        $this->load->model('localisation/estados');
        $this->data['estados'] = $this->model_localisation_estados->getEstados();
        

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/consultant.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/consultant.tpl';
        } else {
            $this->template = 'default/template/error/not_found.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_bottom',
            'common/content_top',
            'common/footer',
            'common/header'
        );
        
        $this->response->setOutput($this->render());
    }

    /**
     * Lista de consultores ativo por estado
     */
    public function consultor() {

        $this->load->model('localisation/estados');

        $consultores = $this->model_localisation_estados->getDistribuidorPorEstado($this->request->get['estado_id']);
        
        $this->response->setOutput(json_encode($consultores));
    }
    
    
    /**
     * Lista de cidades com distribuidores ativos por estado
     */
    public function cidades() {

        $this->load->model('localisation/estados');
        
        $estadoId = ($this->request->get['estado_id'])?$this->request->get['estado_id']:'';
        
        $cidades = $this->model_localisation_estados->getCidadePorEstado($estadoId);
        
        $this->response->setOutput(json_encode($cidades));
    }
    
    
    /**
     * Lista de bairros com distribuidores ativos por cidade
     */
    public function bairros() {

        $this->load->model('localisation/estados');
        
        $cidadeId = ($this->request->get['cidade_id'])?$this->request->get['cidade_id']:'';
        
        $bairros = $this->model_localisation_estados->getBairrosPorCidade($cidadeId);
        
        $this->response->setOutput(json_encode($bairros));
    }
    
    /**
     * Lista de consultores ativos por bairro
     */
    public function consultores() {

        $this->load->model('localisation/estados');
        
        $bairroNome = $this->request->get['bairro_nome'];
        
        $estadoId = ($this->request->get['estado_id'])?$this->request->get['estado_id']:'';
        
        $consultores = $this->model_localisation_estados->getDistribuidorPorBairro($bairroNome,$estadoId);
        
        $this->response->setOutput(json_encode($consultores));
    }
}

?>