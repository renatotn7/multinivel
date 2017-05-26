<?php

class ControllerCommonSeoUrl extends Controller {

    //array adicionado para definir os links da url amigavel
    private $urlCliente;
    private $urlFriendly;
    private function carregaUrlCliente($cliente = ''){
         return array(
            'common/home' => $cliente.'home',
            'checkout/cart' => $cliente.'carrinho',
            'account/register' => $cliente.'cadastre-se',
            'account/wishlist' => $cliente.'lista-de-desejo',
            'checkout/checkout' => $cliente.'checkout',
            'account/logout' => $cliente.'sair',
            'account/login' => $cliente.'login',
            'product/product' => $cliente.'produto',
            'product/special' => $cliente.'especial',
            'affiliate/account' => $cliente.'afiliado',
            'checkout/voucher' => $cliente.'vale-presente',
            'product/manufacturer' => $cliente.'fabricante',
            'account/newsletter' => $cliente.'newsletter',
            'account/order' => $cliente.'meus-pedidos',
            'account/account' => $cliente.'minha-conta',
            'information/contact' => $cliente.'contato',
            'information/information'=> $cliente.'institucional',
            'information/sitemap' => $cliente.'mapa-do-site',
            'account/forgotten' => $cliente.'lembrar-senha',
            'account/download' => $cliente.'meus-downloads',
            'account/return' => $cliente.'minhas-devolucoes',
            'account/transaction' => $cliente.'minhas-indicacoes',
            'account/password' => $cliente.'alterar-senha',
            'account/edit' => $cliente.'alterar-informacoes',
            'account/address' => $cliente.'alterar-enderecos',
            'account/reward' => $cliente.'pontos-de-fidelidade',
        );
    }

    public function index() {
        if(isset($this->session->data['distribuidor_recebera_bonus'])){
            $this->url->setNomeParaUrl($this->session->data['distribuidor_recebera_bonus']->di_usuario);
            $this->urlCliente = $this->session->data['distribuidor_recebera_bonus']->di_usuario;
        }
        $this->urlFriendly = ($this->urlCliente)?$this->carregaUrlCliente($this->urlCliente."/"):$this->carregaUrlCliente();
        // Adicionando rewrite para url class
        if ($this->config->get('config_seo_url')) {
            $this->url->addRewrite($this);
        }
        
        
        
        if (isset($this->request->get['_route_'])) {
            $parts = explode('/', $this->request->get['_route_']);

            foreach ($parts as $part) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");

                if ($query->num_rows) {
                    $url = explode('=', $query->row['query']);

                    if ($url[0] == 'product_id') {
                        $this->request->get['product_id'] = $url[1];
                    }

                    if ($url[0] == 'category_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }

                    // add blog
                    if ($url[0] == 'infocategory_id') {
                        if (!isset($this->request->get['path'])) {
                            $this->request->get['path'] = $url[1];
                        } else {
                            $this->request->get['path'] .= '_' . $url[1];
                        }
                    }
                    // end add blog
                    if ($url[0] == 'manufacturer_id') {
                        $this->request->get['manufacturer_id'] = $url[1];
                    }

                    if ($url[0] == 'information_id') {
                        $this->request->get['information_id'] = $url[1];
                    }
                } else {
                    $this->request->get['route'] = 'error/not_found';
                }
            }
            //inicio modificado para url
            if ($_key = $this->getKeyFriendly($this->request->get['_route_'])) {
                $this->request->get['route'] = $_key;
                //fim modificado para url
            } elseif (isset($this->request->get['product_id'])) {
                $this->request->get['route'] = 'product/product';
                // add blog
            } elseif (isset($this->request->get['path']) and $url[0] == 'infocategory_id'
                and ! isset($this->request->get['information_id'])) {
                $this->request->get['route'] = 'information/infocategory';
            } elseif (isset($this->request->get['path']) and $url[0] == 'category_id') {
                // end add blog
                $this->request->get['route'] = 'product/category';
            } elseif (isset($this->request->get['manufacturer_id'])) {
                $this->request->get['route'] = 'product/manufacturer';
            } elseif (isset($this->request->get['information_id'])) {
                $this->request->get['route'] = 'information/information';
            }

            if (isset($this->request->get['route'])) {
                return $this->forward($this->request->get['route']);
            }
        }
    }

    /**
     * Função para montar url amigavel
     * @param String $link
     * @return String
     */
    public function rewrite($link) {
        if ($this->config->get('config_seo_url')) {
            $url_data = parse_url(str_replace('&amp;', '&', $link));

            $url = '';

            $data = array();

            parse_str($url_data['query'], $data);

            foreach ($data as $key => $value) {
                if (isset($data['route'])) {
                    if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/product' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
                        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int) $value) . "'");

                        if ($query->num_rows) {
                            $url .= '/' . $query->row['keyword'];

                            unset($data[$key]);
                        }
                    } elseif ($key == 'path') {
                        $categories = explode('_', $value);

                        foreach ($categories as $category) {
                            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int) $category . "'");

                            if ($query->num_rows) {
                                $url .= '/' . $query->row['keyword'];
                            }
                        }

                        unset($data[$key]);
                    }
                    //Modificado
                    if ($_link = $this->getValueFriendly($data['route'])) {
                        $url .= $_link;
                        unset($data[$key]);
                    }
                }
            }

            if ($url) {
                unset($data['route']);

                $query = '';

                if ($data) {
                    foreach ($data as $key => $value) {
                        $query .= '&' . $key . '=' . $value;
                    }

                    if ($query) {
                        $query = '?' . trim($query, '&');
                    }
                }

                return $url_data['scheme'] . '://' . $url_data['host'] . (isset($url_data['port']) ? ':' . $url_data['port'] : '') . str_replace('/index.php', '', $url_data['path']) . $url . $query;
            } else {
                return $link;
            }
        } else {
            return $link;
        }
    }

    /**
     * Ajuda a montar a url amigavel
     * @param String $_route
     * @return boolean
     */
    public function getKeyFriendly($_route) {
        if (count($this->urlFriendly) > 0) {
            $key = array_search($_route, $this->urlFriendly);
            if ($key && in_array($_route, $this->urlFriendly)) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Ajuda a montar url amigavel
     * @param String $route
     * @return boolean
     */
    public function getValueFriendly($route) {
        if (count($this->urlFriendly) > 0) {
            if (in_array($route, array_keys($this->urlFriendly))) {
                return '/' . $this->urlFriendly[$route];
            }
        }
        return false;
    }
    
}

?>