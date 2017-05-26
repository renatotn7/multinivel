<?php

class categoriaModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    public static function getCategorias($id_categoria = 0, $retorno = '') {

        if (!empty($id_categoria)) {
            self::db()->db->where('ca_id', $id_categoria);
        }

        $categorias = self::db()->db->get('categorias_produtos');


        if (!empty($id_categoria)) {
            
            if ($retorno == 'result') {
                return $categorias->result();
            }

            return $categorias->row();
        }

        return $categorias->result();
    }

    public static function getCategoriaFilhas($idCategoria) {
        $categoriasFilhas = self::db()->db->where('ca_pai', $idCategoria)
                ->get('categorias_produtos')
                ->result();
        return $categoriasFilhas;
    }

    public static function AtualizarCategoria($objetoCategoria = array()) {
        self::db()->db
                ->where('ca_id', $objetoCategoria['ca_id'])
                ->update('categorias_produtos', funcoesdb::valida_fields('categorias_produtos', $objetoCategoria));
    }

    public static function criarCategoria($objetoCategoria = array()) {
        return self::db()->db->insert('categorias_produtos', funcoesdb::valida_fields('categorias_produtos', $objetoCategoria));
    }

}
