<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of transferencia_credito
 *
 * @author Ronyldo12
 */
class transferencia_credito extends CI_Controller {

    private $de;
    private $para;

    public function __construct() {
        parent::__construct();
        $this->de = $this->db->where('di_id', 206)->get('distribuidores')->row();
        $this->para = $this->db->where('di_id', 208)->get('distribuidores')->row();
    }

    public function ver_transacoes() {

        $daRedeDe = $this->de->di_id;
        $paraRedeDe = $this->para->di_id;

        $transacoes = $this->db
                        ->join('distribuidores', 'di_id=cb_distribuidor')
                        ->where('cb_distribuidor IN(SELECT li_id_distribuidor FROM distribuidor_ligacao WHERE li_no = ' . $daRedeDe . ')')
                        ->where('cb_tipo', 4)
                        ->where('cb_debito >', 0)
                        ->get('conta_bonus')->result();

        foreach ($transacoes as $key => &$transacao) {
            $usuarioReceptor = trim($this->conteudoTagB($transacao->cb_descricao));
            $transacao->usuario_receptor = $usuarioReceptor;
            //echo "<p>Analisando {$transacao->di_usuario}</p>"; 
            $eDaRede = $this->db
                            ->join('distribuidor_ligacao', 'di_id=li_id_distribuidor')
                            ->where('di_usuario', $usuarioReceptor)
                            ->where('li_no', $paraRedeDe)
                            ->get('distribuidores')->row();


            if (count($eDaRede) == 0) {
                unset($transacoes[$key]);
            }
        }

        $data['de'] = $this->de;
        $data['para'] = $this->para;
        $data['transacoes'] = $transacoes;
        $data['pagina'] = 'transferencia_credito/ver_transacoes';
        $this->load->view('home/index_view', $data);
    }

    public function ver_compras_pagas() {

        $daRedeDe = $this->de->di_id;
        $paraRedeDe = $this->para->di_id;

        $transacoes = $this->db
                        ->select('co_id,co_data_compra,rc_data,di_id,di_usuario,co_total_valor,rc_comprador')
                        ->join('compras', 'co_id=rc_compra')
                        ->join('distribuidores', 'di_id=rc_pagante')
                        ->where('rc_pagante IN(SELECT li_id_distribuidor FROM distribuidor_ligacao WHERE li_no = ' . $daRedeDe . ')')
                        ->get('registro_pagamento_compra_terceiro')->result();

        foreach ($transacoes as $key => &$transacao) {

            //echo "<p>Analisando {$transacao->di_usuario}</p>"; 
            $eDaRede = $this->db
                            ->select('di_usuario')
                            ->join('distribuidor_ligacao', 'di_id=li_id_distribuidor')
                            ->where('li_no', $paraRedeDe)
                            ->where('di_id', $transacao->rc_comprador)
                            ->get('distribuidores')->row();


            if (count($eDaRede) == 0) {
                unset($transacoes[$key]);
            } else {
                $transacao->usuario_receptor = $eDaRede->di_usuario;
            }
        }

        $data['de'] = $this->de;
        $data['para'] = $this->para;
        $data['transacoes'] = $transacoes;
        $data['pagina'] = 'transferencia_credito/ver_compras_pagas';
        $this->load->view('home/index_view', $data);
    }

    public function conteudoTagB($html) {
        $ent = $html;
        if (preg_match('/(\d{1,2}\/\d{1,2}\/\d{4})/i', $html, $result)) {
            return $result[1];
        }
        if (preg_match("{<b>}", $ent)) {
            $a = explode("<b>", $ent);
            if (preg_match("{</b>}", $a[1])) {
                $b = explode("</b>", $a[1]);
                return $b[0];
            }
        } else {
            return trim(str_ireplace('Bônus PL ', '', $ent));
        }
    }

}
