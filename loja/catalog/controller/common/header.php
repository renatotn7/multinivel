<?php

class ControllerCommonHeader extends Controller {

    protected function index() {


        $this->load->model('design/layout');

        $this->load->model('catalog/category');

        $this->load->model('catalog/product');

        $this->load->model('catalog/information');

        $this->data['title'] = $this->document->getTitle();



        $layout_id = 1;


        //Configuração Sacola
        //Configuração Sacola {
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

        //FIM Configuração Sacola }

        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            $this->data['base'] = $this->config->get('config_ssl');
        } else {

            $this->data['base'] = $this->config->get('config_url');
        }



        $this->data['description'] = $this->document->getDescription();

        $this->data['keywords'] = $this->document->getKeywords();

        $this->data['links'] = $this->document->getLinks();

        $this->data['styles'] = $this->document->getStyles();

        $this->data['scripts'] = $this->document->getScripts();

        $this->data['lang'] = $this->language->get('code');

        $this->data['direction'] = $this->language->get('direction');

        $this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');

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

        //Styles e Scripts Banner
        //CSS
        $this->data['banner_css'] = 'catalog/view/theme/default/stylesheet/slideshow.css';

        //SCRIPT
        $this->data['banner_script'] = 'catalog/view/javascript/jquery/nivo-slider/jquery.nivo.slider.pack.js';

        foreach ($module_data as $module) {

            //echo $module['code']."<br>";

            $module = $this->getChild('module/' . $module['code'], $module['setting']);

            if ($module) {

                $this->data['banner_principal'] = $module;
            }
        }

        ###END BANNER
        // Whos Online

        if ($this->config->get('config_customer_online')) {

            $this->load->model('tool/online');



            if (isset($this->request->server['REMOTE_ADDR'])) {

                $ip = $this->request->server['REMOTE_ADDR'];
            } else {

                $ip = '';
            }



            if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {

                $url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
            } else {

                $url = '';
            }



            if (isset($this->request->server['HTTP_REFERER'])) {

                $referer = $this->request->server['HTTP_REFERER'];
            } else {

                $referer = '';
            }



            $this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
        }



        $this->language->load('common/header');



        if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {

            $server = HTTPS_IMAGE;
        } else {

            $server = HTTP_IMAGE;
        }



        if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {

            $this->data['icon'] = $server . $this->config->get('config_icon');
        } else {

            $this->data['icon'] = '';
        }



        $this->data['name'] = $this->config->get('config_name');



        if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {

            $this->data['logo'] = $server . $this->config->get('config_logo');
        } else {

            $this->data['logo'] = '';
        }




        $this->data['text_home'] = $this->language->get('text_home');
        
        $this->data['btn_buscar'] = $this->language->get('btn_buscar');
        $this->data['btn_meus_pedidos'] = $this->language->get('btn_meus_pedidos');
        $this->data['btn_carrinho'] = $this->language->get('btn_carrinho');

        $this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));

                $this->data['link_logo'] = $this->url->link('common/home');
                
                $this->data['link_meus_pedidos'] = $this->url->link('account/order');

        $this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
        $this->data['text_search'] = $this->language->get('text_search');
        $this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
        $this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
        $this->data['text_account'] = $this->language->get('text_account');
        $this->data['text_checkout'] = $this->language->get('text_checkout');
        $this->data['text_buscar'] = $this->language->get('text_buscar');
        $this->data['text_voce_estar_comprando_loja'] = $this->language->get('text_voce_estar_comprando_loja');
        $this->data['text_pedidos'] = $this->language->get('text_pedidos');
        $this->data['text_lista_desejos'] = $this->language->get('text_lista_desejos');
        $this->data['text_atedimentos'] = $this->language->get('text_atedimentos');

        $this->data['home'] = $this->url->link('common/home');
        $this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
        $this->data['logged'] = $this->customer->isLogged();
        $this->data['account'] = $this->url->link('account/account', '', 'SSL');
        $this->data['shopping_cart'] = $this->url->link('checkout/cart');
        $this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        if (isset($this->request->get['filter_name'])) {
            $this->data['filter_name'] = $this->request->get['filter_name'];
        } else {
            $this->data['filter_name'] = '';
        }



        // Menu

        $this->load->model('catalog/category');



        $this->load->model('catalog/product');



        $this->data['categories'] = array();



        $categories = $this->model_catalog_category->getCategories(0);



        foreach ($categories as $category) {

            if ($category['top']) {

                $children_data = array();



                $children = $this->model_catalog_category->getCategories($category['category_id']);



                foreach ($children as $child) {

                    $data = array(
                    'filter_category_id' => $child['category_id'],
                    'filter_sub_category' => true
                    );



                    $product_total = $this->model_catalog_product->getTotalProducts($data);



                    $children_data[] = array(
                    'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                    'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
                }



                // Level 1

                $this->data['categories'][] = array(
                'name' => $category['name'],
                'children' => $children_data,
                'column' => $category['column'] ? $category['column'] : 1,
                'href' => $this->url->link('product/category', 'path=' . $category['category_id'])
                );
            }
        }



        $this->children = array(
        'module/language',
        'module/currency',
        'module/cart'
        );



        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {

            $this->template = $this->config->get('config_template') . '/template/common/header.tpl';
        } else {

            $this->template = 'default/template/common/header.tpl';
        }

        $module_data = array();

        $this->load->model('setting/extension');

        $extensions = $this->model_setting_extension->getExtensions('module');

        foreach ($extensions as $extension) {
            $modules = $this->config->get($extension['code'] . '_module');

            if ($modules) {
                foreach ($modules as $module) {
                    if ($module['layout_id'] == $layout_id && $module['position'] == 'header' && $module['status']) {
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

        $this->data['modules'] = array();

        foreach ($module_data as $module) {
            $module = $this->getChild('module/' . $module['code'], $module['setting']);

            if ($module) {
                $this->data['modules'][] = $module;
            }
        }


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/header.tpl';
        } else {
            $this->template = 'default/template/common/header.tpl';
        }

        $this->render();
    }

}

?>