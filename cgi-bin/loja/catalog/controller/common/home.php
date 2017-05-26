<?php

class ControllerCommonHome extends Controller {

    public function index() {

        $this->url->setNomeParaUrl('');

        /*
         * Seta o consultor que ganhará comissão
         */
        $userGet = isset($this->request->get['user_afiliate']) ? $this->request->get['user_afiliate'] : false;

        if (isset($this->request->get['user_afiliate']) || $userGet!=false ){
           $this->session->data['lojaUser'] = $userGet;
           $queryConsultorID = $this->db->query("SELECT di_id FROM distribuidores WHERE di_usuario = '" . $userGet . "'");
           $this->session->data['lojaUserID'] =$queryConsultorID->row['di_id'];
           
        }

        if ($userGet){

            if (isset($this->session->data['distribuidor_log']) && $this->session->data['distribuidor_log'] != false){

                
                    $_SESSION['distribuidor_recebera_bonus']->recebera = 0;
                    $_SESSION['distribuidor_recebera_bonus']->di_id = $this->session->data['distribuidor_log']->di_id;
                    $_SESSION['distribuidor_recebera_bonus']->di_usuario = $userGet;
//                    $this->session->data['distribuidor_recebera_bonus']->recebera = 0;
//                    $this->session->data['distribuidor_recebera_bonus']->di_id = $this->session->data['distribuidor_log']->di_id;
//                    $this->session->data['distribuidor_recebera_bonus']->di_usuario = $userGet;
//                    $this->url->setNomeParaUrl($this->session->data['distribuidor_log']->di_usuario);
                
            } else {
                $queryConsultor = $this->db->query("SELECT di_id FROM distribuidores WHERE di_usuario = '" . $userGet . "'");

                if ($queryConsultor->num_rows) {
                    $_SESSION['distribuidor_recebera_bonus']->recebera = 1;
                    $_SESSION['distribuidor_recebera_bonus']->di_id = $queryConsultor->row['di_id'];
                    $_SESSION['distribuidor_recebera_bonus']->di_usuario = $userGet;
//                    $this->session->data['distribuidor_recebera_bonus']->recebera = 1;
//                    $this->session->data['distribuidor_recebera_bonus']->di_id = $queryConsultor->row['di_id'];
//                    $this->session->data['distribuidor_recebera_bonus']->di_usuario = $userGet;
//                    $this->url->setNomeParaUrl($userGet);
                }
            }
        }
//        var_dump($this->session);
//        exit;
        /*
         * Seta o consultor que ganhará comissão
         */


        if (isset($this->request->get['order'])) {

            $order = $this->request->get['order'];
        } else {

            $order = 'ASC';
        }



        if (isset($this->request->get['page'])) {

            $page = $this->request->get['page'];
        } else {

            $page = 1;
        }



        if (isset($this->request->get['limit'])) {

            $limit = $this->request->get['limit'];
        } else {

            $limit = $this->config->get('config_catalog_limit');
        }

        $data = array(
        'start' => ($page - 1) * $limit,
        'limit' => $limit,
        'show_in_list' => 1
        );

        if ($this->customer->eConsultor()) {
            //MODEL PLANOS  
            if ($this->customer->isLogged()) {
                $this->load->model('catalog/planos');
                $temPlano = $this->model_catalog_planos->getTemPlanoDistribuidor($this->customer->getConsultorId());

                //REDIRECIONA SE NÃO TER PLANO
                if ($temPlano == false) {
                    echo "<script>location.href='" . HTTP_SERVER . "index.php?route=plan/plan';</script>";
                    exit;
                }
            }
        }

        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $product_total = $this->model_catalog_product->getTotalProducts($data);

        //Gerando os Links
        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('common/home', 'page={page}&limit=' . $limit);


        $this->data['pagination'] = $pagination->render();

        /* Adicionado para bloquear acesso ao Material de Apoio 14-11-14 Werlon */
        /* problemas removido
        if(isset($_SESSION['distribuidor_log']) && $_SESSION['distribuidor_log']!=''){
            $results = $this->model_catalog_product->getProducts($data,0);
        }else{
            $results = $this->model_catalog_product->getProducts($data);
        }*/
        
        $results = $this->model_catalog_product->getProducts($data);
        foreach ($results as $result) {
            
            if($result['is_activation'] == 1){
                continue;
            }
            if($result['is_plan'] == 1){
                continue;
            }
            
            if($result['product_id']){
                if(!isset($_SESSION['distribuidor_log'])
                        || $_SESSION['distribuidor_log']==''
                        || is_null($_SESSION['distribuidor_log'])){
                    
                    $produtoApoio = $this->db
                                ->query(
                                        "SELECT * FROM loja_product_to_category "
                                        . "WHERE category_id=11 AND product_id=" . $result['product_id'] . ""
                                )->row;
                    if(count($produtoApoio)>0){
                        continue;
                    }
                }
            }
            
            if ($result['product_id']) {

                $imagem = $this->db
                                ->query(
                                        "SELECT * FROM loja_product_image "
                                        . "WHERE product_id=" . $result['product_id'] . ""
                                )->row;

                if (isset($imagem['image'])) {
                    $imagem['image'] = $imagem['image'];
                } else {
                    $imagem['image'] = '';
                }

                $imgHover = $this->model_tool_image->resize($imagem['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {

                $imgHover = false;
            }

            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            } else {

                $image = false;
            }


            if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {

                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
            } else {

                $price = false;
            }



            if ((float) $result['special']) {

                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
            } else {

                $special = false;
            }



            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float) $result['special'] ? $result['special'] : $result['price']);
            } else {
                $tax = false;
            }



            if ($this->config->get('config_review_status')) {

                $rating = (int) $result['rating'];
            } else {

                $rating = false;
            }


            if (isset($this->request->get['path'])) {
                $this->request->get['path'] = $this->request->get['path'];
            } else {
                $this->request->get['path'] = '';
            }



            $this->data['products'][] = array(
            'product_id' => $result['product_id'],
            'thumb' => $image,
            'name' => $result['name'],
            'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
            'price' => $price,
            'special' => $special,
            'hover' => $imgHover,
            'tax' => $tax,
            'rating' => $result['rating'],
            'reviews' => sprintf($this->language->get('text_reviews'), (int) $result['reviews']),
            'href' => $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'])
            );
        }

        $this->document->setTitle($this->config->get('config_title'));

        $this->document->setDescription($this->config->get('config_meta_description'));

        $this->data['heading_title'] = $this->config->get('config_title');
        

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {

            $this->template = $this->config->get('config_template') . '/template/common/home.tpl';
        } else {

            $this->template = 'default/template/common/home.tpl';
        }
        

        //Verfica se estar logado
        $this->data['logged'] = $this->customer->isLogged();


        $this->children = array(
        'common/column_left',
        'common/column_right',
        'common/content_top',
        'common/content_bottom',
        'common/footer',
        'common/header'
        );



        if (isset($this->session->data['plano_set'])) {
            $this->data['temPlano'] = $this->session->data['plano_set'];
        } else {
            $this->data['temPlano'] = false;
        }

        $this->response->setOutput($this->render());
    }

    public function index_padrao() {

        $this->document->setTitle($this->config->get('config_title'));

        $this->document->setDescription($this->config->get('config_meta_description'));



        $this->data['heading_title'] = $this->config->get('config_title');



        if (file_exists(DIR_TEMPLATE . '/template/common/home.tpl')) {

            $this->template = $this->config->get('config_template') . '/template/common/home.tpl';
        } else {

            $this->template = 'default/template/common/home.tpl';
        }



        $this->children = array(
        'common/column_left',
        'common/column_right',
        'common/content_top',
        'common/content_bottom',
        'common/footer',
        'common/header',
        'module/specialhome',
        'module/bannermeio',
        );



        //Configuração Sacola
        // Totals
        $this->load->model('setting/extension');

        $total_data = array();
        $total = 0;
        $taxes = $this->cart->getTaxes();

        // Display prices
        if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            $results = $this->model_setting_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('total/' . $result['code']);

                    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
                }

                $sort_order = array();

                foreach ($total_data as $key => $value) {
                    $sort_order[$key] = $value['sort_order'];
                }

                array_multisort($sort_order, SORT_ASC, $total_data);
            }
        }


        $this->data['totals'] = $total_data;
        $this->data['qtd_itens'] = $this->cart->countProducts();
        $valor_t = str_replace("R$", "", $this->currency->format($total));
        $this->data['valor_total'] = $valor_t;

        ######
        ##BANNER
        ######

        $module_data = array();



        $this->load->model('setting/extension');



        $extensions = $this->model_setting_extension->getExtensions('module');



        foreach ($extensions as $extension) {



            if ($extension['code'] == 'slideshow') {



                $modules = $this->config->get($extension['code'] . '_module');



                if ($modules) {

                    foreach ($modules as $module) {



                        $module_data[] = array(
                        'code' => $extension['code'],
                        'setting' => $module,
                        'sort_order' => $module['sort_order']
                        );
                    }
                }
            }
        }



        $sort_order = array();



        foreach ($module_data as $key => $value) {

            $sort_order[$key] = $value['sort_order'];
        }



        array_multisort($sort_order, SORT_ASC, $module_data);



        $this->data['banner_principal'] = array();



        foreach ($module_data as $module) {

            //echo $module['code']."<br>";



            $module = $this->getChild('module/' . $module['code'], $module['setting']);



            if ($module) {

                $this->data['banner_principal'] = $module;
            }
        }

        //var_dump($this->data['banner_principal']);
        ###END BANNER

        $this->response->setOutput($this->render());
    }

}

?>