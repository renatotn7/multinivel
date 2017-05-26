<?php

class produtoModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    public static function getProdutoComprados($distribuidor = array(), $categoria = 0, $idCompra = 0, $pago = false, $situacao_pedido = null) {
        if (count($distribuidor) == 0) {
            return $distribuidor;
        }

        self::db()->db->start_cache();
        if (!empty($categoria)) {
            self::db()->db->where('pr_categoria', $categoria);
        }
        if (!empty($idCompra)) {
            self::db()->db->where('co_id', $idCompra);
        }
        if ($pago) {
            self::db()->db->where('co_pago', 1);
        } else {
            self::db()->db->where('co_pago', 0);
        }
        if( $situacao_pedido == 1 ){
            self::db()->db->where('co_situacao_pedido', 1); // igual a pedido em abertos
        }else{
            self::db()->db->where('co_situacao_pedido', 0);// igual a pedido em abertos
        }

        self::db()->db->stop_cache();

        
        $dados = self::db()->db
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->join('produtos_comprados', 'pm_id_produto=pr_id')
                        ->join('compras', 'co_id=pm_id_compra')
                        ->get('produtos')->row();
       

       
        self::db()->db->flush_cache();

        return $dados;
    }

    public static function atualizarProduto($pr_id = 0, $data = array()) {

        if (empty($pr_id)) {
            return false;
        }

        if (count($data) == 0) {
            return false;
        }

        return self::db()->db->where('pr_id', $pr_id)->update('produtos', $data);
    }

    public static function getTotalProdutoComprados($distribuidor = array(), $categoria = 0) {
        if (count($distribuidor) == 0) {
            return 0;
        }
        if (!empty($categoria)) {
            self::db()->db->where('pr_categoria', $categoria);
        }

        return self::db()->db->select('count(pr_id) as total')
                        ->where('co_pago', 0)
                        ->where('co_id_distribuidor', $distribuidor->di_id)
                        ->where('co_situacao_pedido', 0)
                        ->join('produtos_comprados', 'pm_id_produto=pr_id')
                        ->join('compras', 'co_id=pm_id_compra')
                        ->get('produtos')->row()->total;
    }

    public static function getProduto($id_pr = 0) {
        if (!empty($id_pr)) {
            self::db()->db->where('pr_id', $id_pr);
        }
        $produtos = self::db()->db->where_not_in('pr_id', array(12, 13, 14, 15, 21))
                ->get('produtos');

        if (!empty($id_pr)) {
            return $produtos->row();
        }
        return $produtos->result();
    }

    public static function getProdutoCategoria($id_categoria = 0, $pr_id = 0, $pr_valor = 0,$pagina=false) {
        
        self::db()->db->start_cache();
        if (!empty($id_categoria)) {
            self::db()->db->where('pr_categoria', $id_categoria);
        }

        if (!empty($pr_id)) {
            self::db()->db->where('pr_id', $pr_id);
        }

        if (!empty($pr_valor)) {
            self::db()->db->where('pr_valor', $pr_valor);
        }
        
        self::db()->db->where_not_in('pr_id', array(12, 13, 14, 15, 21));
        self::db()->db->stop_cache();
        
        if($pagina !=false){
            $produtos= self::db()->db->get('produtos',12,$pagina);
        }else{
            $produtos= self::db()->db->get('produtos',12);
        }
        
       self::db()->db->flush_cache();

        return $produtos->result();
    }

    public static function addProduto($produtos = array()) {
        return self::db()->db->insert('produtos', funcoesdb::valida_fields('produtos', $produtos));
    }

    /**
     * Se a imagem for invalida, (não existir ou não for passada uma url valida retorna uma imagem padrão)
     * @param type $imagem_url
     * @return string
     */
    public static function validar_imagem($imagem_url = '') {
        $imagem = 'data:image/png;base64,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';
        if (empty($imagem_url)) {
            return $imagem;
        }

        if (!@file_get_contents($imagem_url)) {
            return $imagem;
        }

        return $imagem_url;
    }

    /**
     *  Retorna a imagem se o tamanho pode ser passado 
     * na opção widht='valorpx ou %' 
     * @param type $ID
     * @param type $option
     * @return boolean
     */
    public static function get_imagem_thumb($ID = 0, $capa = false) {

        $path = APP_BASE_URL . APP_ADMINISTRACAO . "/public/imagem/uploads-produtos/thumbs/";


        if (empty($ID)) {
            return array();
        }


        $produto_imagem = self::db()->db->where('img_id_poduto', $ID)
                ->get('imagem_produto');


        //se escolher a capa
        if ($capa) {
            $produto_imagem = $produto_imagem->row();


            if (count($produto_imagem) == 0) {

                return funcoesdb::arrayToObject(array(
                            'url' => '',
                            'base_64' => '',
                            'width' => '',
                            'heigth' => ''
                ));
            }

            $sizer_imagem = @getimagesize($path . $produto_imagem->img_nome);

            //Obtendo informações da imagem.
            $imagem_array = array(
                'url' => $path . $produto_imagem->img_nome,
                'base_64' => 'data:image/png;base64,' . base64_encode(@file_get_contents($path . $produto_imagem->img_nome)),
                'width' => $sizer_imagem[0],
                'heigth' => $sizer_imagem[1],
            );
            return funcoesdb::arrayToObject($imagem_array);
        } else {
            $produto_imagem = $produto_imagem->result();
        }



        foreach ($produto_imagem as $img_produto) {

            $sizer_imagem = getimagesize($path . $img_produto->img_nome);

            //Obtendo informações da imagem.
            $imagem_array[] = array(
                'url' => $path . $img_produto->img_nome,
                'base_64' => 'data:image/png;base64,' . base64_encode(file_get_contents($path . $img_produto->img_nome)),
                'width' => $sizer_imagem[0],
                'heigth' => $sizer_imagem[1],
            );
        }

        return funcoesdb::arrayToObject($imagem_array);
    }

    /**
     *  Retorna a imagem se o tamanho pode ser passado 
     * na opção widht='valorpx ou %' 
     * @param type $ID
     * @param type $option
     * @return boolean
     */
    public static function get_imagem($ID = 0, $capa = false) {

        $path = APP_BASE_URL . APP_ADMINISTRACAO . "/public/imagem/uploads-produtos/";


        if (empty($ID)) {
            return array();
        }


        $produto_imagem = self::db()->db->where('img_id_poduto', $ID)
                ->get('imagem_produto');


        //se escolher a capa
        if ($capa) {
            $produto_imagem = $produto_imagem->row();


            if (count($produto_imagem) == 0) {

                return funcoesdb::arrayToObject(array(
                            'url' => '',
                            'base_64' => '',
                            'width' => '',
                            'heigth' => ''
                ));
            }

            $sizer_imagem = @getimagesize($path . $produto_imagem->img_nome);

            //Obtendo informações da imagem.
            $imagem_array = array(
                'url' => $path . $produto_imagem->img_nome,
                'base_64' => 'data:image/png;base64,' . base64_encode(@file_get_contents($path . $produto_imagem->img_nome)),
                'width' => $sizer_imagem[0],
                'heigth' => $sizer_imagem[1],
            );
            return funcoesdb::arrayToObject($imagem_array);
        } else {
            $produto_imagem = $produto_imagem->result();
        }



        foreach ($produto_imagem as $img_produto) {

            $sizer_imagem = getimagesize($path . $img_produto->img_nome);

            //Obtendo informações da imagem.
            $imagem_array[] = array(
                'url' => $path . $img_produto->img_nome,
                'base_64' => 'data:image/png;base64,' . base64_encode(file_get_contents($path . $img_produto->img_nome)),
                'width' => $sizer_imagem[0],
                'heigth' => $sizer_imagem[1],
            );
        }

        return funcoesdb::arrayToObject($imagem_array);
    }

    private function attr($optionHtml = array()) {
        $html = "";
        if (count($optionHtml) > 0) {
            foreach ($optionHtml as $key => $value) {
                $html.=$key . "='" . $value . "' ";
            }
        }
        return $html;
    }

}
