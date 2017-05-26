<?php

class combopacoteModel {

    public static function getComboPacotes() {
        $pacotes = get_instance()->db
                        ->join('planos', 'pa_id=pn_plano')
                        ->get('produtos_padrao_plano')->result();
        return $pacotes;
    }

    public static function getComboPacotesPorPlano($id_plano) {

        $pacotes = get_instance()->db
                        ->where('pa_id', $id_plano)
                        ->join('planos', 'pa_id=pn_plano')
                        ->get('produtos_padrao_plano')->row();

        return $pacotes;
    }

    public static function getComboPacote($idCombo) {
        $pacotes = get_instance()->db
                        ->where('pn_id', $idCombo)
                        ->join('planos', 'pa_id=pn_plano')
                        ->get('produtos_padrao_plano')->row();
        return $pacotes;
    }

    public static function getProdutosCombo($idcombo) {
        $produtos = get_instance()->db
                        ->where('cbp_id_combo', $idcombo)
                        ->join('produtos_padrao_combo_produtos', 'cbp_id_produto=pr_id')
                        ->get('produtos')->result();
        return $produtos;
    }

    public static function getProdutos() {
        $produtos = get_instance()->db
                        ->join('categorias_produtos', 'ca_id=pr_categoria')
                        ->get('produtos')->result();
        return $produtos;
    }

    public static function criarCombo($dados) {
        return get_instance()->db->insert('produtos_padrao_plano', funcoesdb::valida_fields('produtos_padrao_plano', $dados));
    }

    public static function produtoexist($codigo_produto, $codigo_combo) {
        $produto = get_instance()->db->where('cbp_id_produto', $codigo_produto)
                        ->where('cbp_id_combo', $codigo_combo)
                        ->get('produtos_padrao_combo_produtos')->row();

        if (count($produto) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function addProdutoCombo($codigo_produto, $codigo_combo) {
        return get_instance()->db->insert('produtos_padrao_combo_produtos', array(
                    'cbp_id_combo' => $codigo_combo,
                    'cbp_id_produto' => $codigo_produto,
        ));
    }

    public static function atualizarCombo($codigo, $data) {
        return get_instance()->db->where('pn_id', $codigo)
                        ->update('produtos_padrao_plano', valida_fields('produtos_padrao_plano', $data));
    }

    public static function removerProdutoCombo($codigo) {
        return get_instance()->db->where('cbp_id', $codigo)
                        ->delete('produtos_padrao_combo_produtos');
    }

    public static function removerCombo($codigo) {
        return get_instance()->db->where('pn_id', $codigo)
                        ->delete('produtos_padrao_plano');
    }

    public static function getPlanos() {

        $planos = get_instance()->db->where('pa_id !=104')->get('planos')->result();

        return $planos;
    }

}
