<?php

class kitModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    /**
     * Retorna todo kits cadastrados 
     * @param type $id_kit
     * @return type
     */
    public static function getKits($id_kit = 0) {

        if (!empty($id_kit)) {
            self::db()->db->where('pr_id', $id_kit);
        }

        $kits = self::db()->db->where('pr_kit_tipo', 1)
                ->get('produtos');

        if (!empty($id_kit)) {
            return $kits->row();
        }

        return $kits->result();
    }

    public static function getProdutoTemKit($id_kit = 0) {
        if (!empty($id_kit)) {
            self::db()->db->where('pr_kit', $id_kit);
        }

        $kits = self::db()->db->get('produtos');

        if (!empty($id_kit)) {
            return $kits->row();
        }

        return $kits->result();
    }

    /**
     * retorna todos os produtos cadastrados nos kits com os produtos.
     * @param type $id_kit
     * @param type $produto
     * @return type
     */
    public static function getProdutoskits($idkits) {
        $produtos = get_instance()->db
                        ->where('pc_id_kit', $idkits)
                        ->join('produtos_kit_categoria', 'pc_id_produto=pr_id ')
                        ->get('produtos')->result();
        return $produtos;
    }

    /**
     * Remover produto combo.
     * @param type $codigo
     * @return type
     */
    public static function removerProdutoCombo($codigo) {

        return get_instance()->db->where('pc_id', $codigo)
                        ->delete('produtos_kit_categoria');
    }

    /**
     *  Insere produto no kit desejado....
     * @param type $codigo_produto
     * @param type $codigo_kit
     * @return type
     */
    public static function addProdutoKits($codigo_produto, $codigo_kit, $quantidade) {
        return get_instance()->db->insert('produtos_kit_categoria', array(
                    'pc_id_kit' => $codigo_kit,
                    'pc_id_produto' => $codigo_produto,
                    'pc_quantidade' => $quantidade,
        ));
    }

    public static function produtoexist($codigo_produto, $codigo_kit) {
        $produto = get_instance()->db->where('pc_id_kit', $codigo_kit)
                        ->where('pc_id_produto', $codigo_produto)
                        ->get('produtos_kit_categoria')->row();

        if (count($produto) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cadastra novos kits
     * @param type $kits
     * @return boolean
     * */
    public static function criarKits($kits = array()) {
        if (count($kits) == 0) {
            return false;
        }

        $kits = array_merge($kits, array('pr_kit_tipo' => 1));
        return self::db()->db->insert('produtos', funcoesdb::valida_fields('produtos', $kits));
    }

    /**
     * Salta todas atualizações de um kit existente.
     * @param type $kits
     * @return boolean
     */
    public static function AtualizarKits($kits = array()) {
        if (count($kits) == 0) {
            return false;
        }


        if (!isset($kits['pr_id'])) {
            return false;
        }

        return self::db()->db->where('pr_id', $kits['pr_id'])
                        ->where('pr_kit_tipo', 1)
                        ->update('produtos', funcoesdb::valida_fields('produtos', $kits));
    }

    /**
     * Remove o kit indesejado existe no banco e os desejado também pa pa pa beautiful day.
     * @param type $id_kit
     * @return boolean
     */
    public static function removeKits($id_kit) {

        if (empty($id_kit)) {
            return false;
        }

        //Removendo todas entrada para os produtos.
        get_instance()->db->where('pc_id_kit', $id_kit)
                ->delete('produtos_kit_categoria');

        return self::db()->db->where('pr_kit_tipo', 1)
                        ->where('pr_id', $id_kit)
                        ->delete('produtos');
    }

     public static function getProdutosKitComprado($idCompra = 0) {
        if (empty($idCompra)) {
            return array();
        }
        
        $produto = self::db()->db->query("select * from compras 
                                            join produtos_comprados on pm_id_compra = co_id
                                            join  produtos on pm_id_produto = pr_id

                                            WHERE `co_id` = '{$idCompra}'")->row();
                                            
        $produtos = self::db()->db->where('pc_id_kit', $produto->pr_kit)
                        ->join('produtos', 'pr_id = pc_id_produto')
                        ->get('produtos_kit_categoria')->result();
        
        
        return $produtos;
    }
    // comentado por min (eduardo) para testes
    // public static function quantidadeComprado($idCompra = 0,$pr_id) {
    //     if (empty($idCompra)) {
    //         return array();
    //     }
        
    //     $quantidade= self::db()->db->query("select pm_quantidade from produtos 
    //                                     join produtos_kit_categoria on produtos_kit_categoria.pc_id_kit = pr_kit
    //                                     join produtos_comprados on pm_id_produto=pr_id
    //                                     where pm_id_compra={$idCompra}
    //                                     and pc_id_produto={$pr_id}")->row();
                                        
    //     if(count($quantidade)>0){
    //         return $quantidade->pm_quantidade;
    //     }else{
    //         return 1;
    //     }
    // }

    public static function getquantidadeKit($pr_kit = 0) {

        $produtos =(int) self::db()->db->where('pc_id_kit', $pr_kit)
                        ->join('produtos', 'pr_id = pc_id_produto')
                        ->get('produtos_kit_categoria')->row()->pc_quantidade;


        return $produtos;
    }

    /**
     * Verifica se o produto ta no kit.
     * @param type $id_produto
     * @return type
     */
    public static function produtoTaEmKit($id_produto = 0) {

        return self::db()->db->where('pc_id_produto', $id_produto)
                        ->join('produtos', 'pr_id = pc_id_produto')
                        ->get('produtos_kit_categoria')->row();
    }

}
