<?php

class PagamentoParcelado implements registry {

    private $compra;
    private $cof_id;
    private $di_id_patrocinador;

    public function __construct($objeto = array(), $patrocinador = 0, $cof_id = 0) {
        $this->db = & get_instance()->db;
        $this->compra = new stdClass();

        if ($cof_id != 0) {
            $this->cof_id = $cof_id;
        }


        if (count($objeto) > 0) {
            $this->compra = $objeto;
        }

        if ($patrocinador != 0) {
            $this->di_id_patrocinador = $patrocinador;
        }
    }

    public function realizarPagamento() {

        //Verificano se a compra passada é uma compra valida.
        if (count($this->compra) == 0) {
            return false;
        }

        //Inifia o processo de pagamento.
        if ($this->tem_pagamento_hoje() && $this->saldo_suficiente()) {
            $this->pagarComBonusParcelaAtual();
            //Da baixa na parcela atual.
            $this->dabaixaParcelaAtual();
        } else {
            $this->situacaoCompra(2);
        }
    }

    public function pagarParcelaPendentes() {

        //Verificano se a compra passada é uma compra valida.
        if (count($this->compra) == 0) {
            return false;
        }

        if ($this->saldo_suficiente()) {
            //Inicializa o processo de pagamento.
            $this->pagarComBonusTodasParcelas();
            //Da baixa em todas as parcelas
            $this->dabaixaParcelasTotal();
        }
    }

    public function pagarParcelaPendentesplataformPay() {

        //Verificano se a compra passada é uma compra valida.
        if (count($this->compra) == 0) {
            return false;
        }

        //Inicializa processo de pagamento.
        $this->pagarplataformPay();
    }

    /**
     * Verifica se deve pagar a compra do distribuidor.
     */
    private function tem_pagamento_hoje() {

        //Verifica se a compra passada é uma compra parcelada mesmo
        if (!isset($this->compra->co_parcelado)) {
            return false;
        }

        if ($this->compra->co_parcelado != 1) {
            return false;
        }


        $parcelas = $this->db->where('cof_data_vencimento <=', date('Y-m-d'))
                ->where('cof_pago', 0)
                ->where('cof_id_compra', $this->compra->co_id)
                ->get('compras_financiamento')
                ->row();

        if (count($parcelas) > 0) {
            return true;
        } else {
            return false;
        }
    }

    private function ponto_parcela_pagar_hoje() {
        //Verifica se a compra passada é uma compra parcelada mesmo
        if ($this->compra->co_parcelado != 1) {
            return false;
        }

        $parcelas = $this->db->where('cof_data_vencimento <=', date('Y-m-d'))
                ->where('cof_pago', 0)
                ->where('cof_id_compra', $this->compra->co_id)
                ->get('compras_financiamento')
                ->result();

        foreach ($parcelas as $parcela) {
            $this->compra->co_total_pontos+=$parcela->cof_pontos;
        }

        return $this->compra->co_total_pontos;
    }

    /**
     * Retorna o pontos total de todas as parcelas
     */
    private function total_pontos_parcelas() {
        //Verifica se a compra passada é uma compra parcelada mesmo
        if ($this->compra->co_parcelado != 1) {
            return false;
        }

        //Se for pagamento de 1 unica parcela
        if (!empty($this->cof_id)) {
            $this->db->where('cof_id', $this->cof_id);
        }

        $parcelas = $this->db->where('cof_pago', 0)
                ->where('cof_id_compra', $this->compra->co_id)
                ->select('sum(cof_pontos) as total_pontos_parcelas')
                ->get('compras_financiamento')
                ->row();

        if (empty($parcelas->total_pontos_parcelas)) {
            return false;
        }

        return $parcelas->total_pontos_parcelas + $this->compra->co_total_pontos;
    }

    private function saldo_suficiente() {

        $saldo = $this->db->query("select
                              sum(cb_credito) - sum(cb_debito) as total
                              from conta_bonus where cb_distribuidor = {$this->compra->co_id_distribuidor}")->row();

        $parcela = $this->parcelaAtual();

        if ($saldo->total >= $parcela->cof_valor) {
            return true;
        }

        return false;
    }

    /**
     * Retorna a parcela do mes corrente
     */
    private function parcelaAtual() {

        //Se for pagamento individual.
        if (!empty($this->cof_id)) {
            $this->db->where('cof_id', $this->cof_id);
        } else {
            $this->db->where('cof_data_vencimento <=', date('Y-m-d'));
        }

        //Pegando o valor da parcela atual.
        $parcela = $this->db->where('cof_pago', 0)
                ->where('cof_id_compra', $this->compra->co_id)
                ->get('compras_financiamento')
                ->row();

        return $parcela;
    }

    /**
     * Retorna a parcela do mes corrente
     */
    private function parcelaEmAtraso() {

        if (!isset($this->compra->co_id)) {
            return array();
        }

        //Se for pagamento individual.
        if (!empty($this->cof_id)) {
            $this->db->where('cof_id', $this->cof_id);
        } else {
            $this->db->where('cof_data_vencimento <', date('Y-m-d'));
        }

        //Pegando o valor da parcela atual.
        $parcela = $this->db->where('cof_pago', 0)
                ->where('cof_id_compra', $this->compra->co_id)
                ->get('compras_financiamento')
                ->result();

        return $parcela;
    }

    private function pagarComBonusParcelaAtual() {
        $pontos_pagar_hoje = $this->ponto_parcela_pagar_hoje();

        //Alterando a situação da compra
        $this->db->where('co_id', $this->compra->co_id)
                ->update('compras', array(
                    'co_situacao' => '12',
                    'co_total_pontos' => $pontos_pagar_hoje
        ));

        //Pegando o valor da parcela atual.
        $parcelas = $this->parcelaEmAtraso();

        //Se não tiver parcelas para pagar.
        if (count($parcelas) == 0) {
            return false;
        }

        $total_parcelas = $this->db->where('cof_id_compra', $this->compra->co_id)
                ->select('count(*) as total')
                ->get('compras_financiamento')
                ->row();

        foreach ($parcelas as $parcela) {

            //Descontando o pagamento do bônus.
            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => $this->compra->co_id_distribuidor,
                'cb_compra' => $this->compra->co_id_distribuidor,
                'cb_descricao' => "Pagamento da Parcela Nº<b>{$parcela->cof_numero_parcela} / {$total_parcelas->total} valor: {$parcela->cof_valor}</b> Referente Ativação.",
                'cb_debito' => $parcela->cof_valor,
                'cb_tipo' => 3,
            ));
        }
    }

    /**
     * Realiza o pagamento com o Bônus.s
     */
    private function pagarComBonusTodasParcelas() {

        $total_pontos_parcelados = $this->total_pontos_parcelas();

        if (empty($total_pontos_parcelados)) {
            return false;
        }

        //Alteranado a situação da compra
        $this->db->where('co_id', $this->compra->co_id)
                ->update('compras', array(
                    'co_situacao' => 6,
                    'co_total_pontos' => $total_pontos_parcelados
        ));
        //Se for pagamento de 1 unica parcela
        if (!empty($this->cof_id)) {
            $this->db->where('cof_id', $this->cof_id);
        }

        //Pegando o valor de todas as parcelas
        $parcela = $this->db->where('cof_pago', 0)
                ->where('cof_id_compra', $this->compra->co_id)
                ->select('sum(cof_valor) as valor_total, count(cof_valor) as total_parcelas')
                ->get('compras_financiamento')
                ->row();

        //Se não tiver parcelas para pagar.
        if (empty($parcela->valor_total)) {
            return false;
        }
        //Se não tiver parcelas para pagar.
        if (empty($parcela->total_parcelas)) {
            return false;
        }

        //Descontando o pagamento do bônus.
        $this->db->insert('conta_bonus', array(
            'cb_distribuidor' => $this->compra->co_id_distribuidor,
            'cb_compra' => $this->compra->co_id_distribuidor,
            'cb_descricao' => "Pagamento de <b>{$parcela->total_parcelas} / $parcela->total_parcelas valor: {$parcela->valor_total}</b> Referente Ativação.",
            'cb_debito' => $parcela->valor_total,
            'cb_tipo' => 3,
        ));
    }

    private function pagarplataformPay() {

        $total_pontos_parcelados = $this->total_pontos_parcelas();

        if (empty($total_pontos_parcelados)) {
            return false;
        }

        //Alterando o status da compra.
        $this->db->where('co_id', $this->compra->co_id)
                ->update('compras', array(
                    'co_situacao' => 6,
                    'co_total_pontos' => $total_pontos_parcelados
        ));

        if (!empty($this->cof_id)) {
            $this->db->where('cof_id', $this->cof_id);
        }

        $this->db->where('cof_id_compra', $this->compra->co_id)
                ->update('compras_financiamento', array(
                    'cof_pago' => 1,
                    'cof_situacao' => 3,
                    'cof_data_pagamento' => date('Y-m-d H:i:s')
        ));

        return true;
    }

    /**
     * Coloca a compra com o pagamento em atraso
     */
    private function situacaoCompra($tipo_situacao = 0, $todas = 0) {


        if ($tipo_situacao == 2) {

            $parcelas = $this->parcelaEmAtraso();

            if (count($parcelas) == 0) {
                return false;
            }

            foreach ($parcelas as $parcela) {

                //Dando baixa na parcela corrente.
                $this->db->where('cof_id', $parcela->cof_id)
                        ->update('compras_financiamento', array(
                            'cof_situacao' => $tipo_situacao
                ));
            }
        } else {
            $parcelas = $this->parcelaAtual();

            if (count($parcelas) == 0) {
                return false;
            }

            if (!empty($this->cof_id)) {
                $this->db->where('cof_id', $parcelas->cof_id);
            }

            //Dando baixa na parcela corrente.
            $this->db->where('cof_id_compra', $parcelas->cof_id_compra)
                    ->update('compras_financiamento', array(
                        'cof_situacao' => $tipo_situacao
            ));
        }
    }

    /**
     * Da baixa na parcela do mês atual;
     */
    private function dabaixaParcelaAtual() {

        $parcelas = $this->parcelaEmAtraso();
        foreach ($parcelas as $parcela) {

            //Dando baixa na parcela corrente.
            $this->db->where('cof_id', $parcela->cof_id)
                    ->update('compras_financiamento', array(
                        'cof_pago' => 1,
                        'cof_data_pagamento' => date('Y-m-d H:i:s')
            ));

            //Dando baixa na parcela atual
            $this->db->where('cof_id', $parcela->cof_id)
                    ->update('compras_financiamento', array(
                        'cof_situacao' => 3
            ));
        }
    }

    /**
     * Da baixa em todas as parcelas.
     */
    private function dabaixaParcelasTotal() {

        //Se for pagamento de 1 unica parcela
        if (!empty($this->cof_id)) {
            $this->db->where('cof_id', $this->cof_id);
        }

        //Dando baixa na parcela corrente.
        $this->db->where('cof_id_compra', $this->compra->co_id)
                ->update('compras_financiamento', array(
                    'cof_pago' => 1,
                    'cof_data_pagamento' => date('Y-m-d H:i:s')
        ));

        //Dando baixa na parcela atual
        $this->db->where('cof_id_compra', $this->compra->co_id)
                ->update('compras_financiamento', array(
                    'cof_situacao' => 3
        ));
    }

}

class PagamentoBonus implements registry {

    private $compra;
    private $di_id_patrocinador = 0;

    public function __construct($objeto = array(), $patrocinador = 0) {
        $this->db = & get_instance()->db;
        if (count($objeto) > 0) {
            $this->compra = $objeto;
        }

        if ($patrocinador != 0) {
            $this->di_id_patrocinador = $patrocinador;
        }
    }

    /**
     * Sabe fazer pagamento com bônus do backoffice.
     * Sabe realizar todas as operações de finalização de pagamento.
     * - verificar se tem saldo.
     * - Baixa de compra
     * - Ativação
     * - Inclusão do registro de ativação.
     * - Descontar do saldo.
     * -
     */
    public function realizarPagamento() {
        if (count($this->compra) > 0) {
            if ($this->verificar_disponibilidade_saldo()) {
                $this->pagarCompra();
                $this->descontar_bonus();
                $this->InserirCartaoMemberShip();
                $this->ativacao();
                //Lancando o voucher de compra. nunca usou isso .
                Evoucher::lancar($this->compra);
                return true;
            } else {
                return false;
            }
        }
    }

    public function pagarParcelaPendentes() {

    }

    public function pagarParcelaPendentesplataformPay() {

    }

    /**
     * Realixa todo os processo para ativar o usuário.
     * aloca na rede e insere um novo registo de ativação.
     */
    public function ativacao() {

        //Pegar o plano do distribuidor.
        $planos = $this->db->where('pa_id', $this->compra->co_id_plano)
                        ->get('planos')->row();

        if (count($planos) > 0) {
            //Verificando se o distribuidor já tem aquele plano.
            $registro_plano = $this->db->where('ps_distribuidor', $this->compra->co_id_distribuidor)
                            ->where('ps_plano', $this->compra->co_id_plano)
                            ->get('registro_planos_distribuidor')->row();

            if (count($registro_plano) == 0) {
                //Salvando o registro do plano.
                $this->db->insert('registro_planos_distribuidor', array(
                    'ps_distribuidor' => $this->compra->co_id_distribuidor,
                    'ps_compra' => $this->compra->co_id,
                    'ps_plano' => $this->compra->co_id_plano,
                    'ps_valor' => $planos->pa_valor
                ));
            }
        }


        //Alocando o usuário na rede
        $rede = new Rede();
        $rede->alocar($this->compra->co_id_distribuidor);

        //LanÃ§ando a ativaçã do usuario
        $ativacao = new Ativacao();
        $ativacao->lancar_ativacao($this->compra);
    }

    /**
     * Realiza o pagamento da compra.
     */
    public function pagarCompra() {
        /*
         * Finalizando a compra do usuário.
         */
        $this->db->where('co_id', $this->compra->co_id)->update('compras', array(
            'co_forma_pgt' => 20,
            'co_forma_pgt_txt' => 'Saldo do backOffice',
            'co_data_compra' => date('Y-m-d H:s:i'),
            'co_pago' => 1,
            'co_situacao' => 7,
        ));


        if ($this->compra->co_tipo == 100 && $this->compra->co_id_cartao != 0) {
            $this->db->insert("cartoes_distribuidor", array(
                "cd_distribuidor" => $this->compra->co_id_distribuidor,
                "cd_id_cartao" => $this->compra->co_id_cartao,
                "cd_compra" => $this->compra->co_id
            ));
        }
    }

    /**
     * Verifica a disponiblidade do saldo.
     */
    public function verificar_disponibilidade_saldo() {
        $saldo = $this->db->where('cb_distribuidor', ($this->di_id_patrocinador != 0 ? $this->di_id_patrocinador : $this->compra->co_id_distribuidor)
                )->select('sum(cb_credito) - sum(cb_debito) as total')
                ->get('conta_bonus')
                ->row();

        if ($saldo->total >= $this->compra->co_total_valor) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Desconta o bonus da conta.
     */
    public function descontar_bonus() {
        $usuario = $this->db->where('di_id', $this->compra->co_id_distribuidor)
                        ->get('distribuidores')->row();

        $this->db->insert('conta_bonus', array(
            'cb_debito' => $this->compra->co_total_valor,
            'cb_tipo' => 3,
            'cb_descricao' => "VOUCHER empresa, PAGAMENTO DA COMPRA Nº:" . $this->compra->co_id . " usuario <b>{$usuario->di_usuario}</b>",
            'cb_distribuidor' => ($this->di_id_patrocinador != 0 ? $this->di_id_patrocinador : $this->compra->co_id_distribuidor),
        ));
    }

    /**
     * Realiza o pagamento do cartão memberships
     */
    public function InserirCartaoMemberShip() {
        //Salvando a compra do cartão member ship do niv.
        $distribuidor = $this->db->query("select di_niv from distribuidores "
                        . "where di_id={$this->compra->co_id_distribuidor}")->row();

        if (count($distribuidor) == 0) {
            return false;
        }

        $this->db->insert('compra_cartao_memberships', array(
            'ccm_niv' => $distribuidor->di_niv
        ));
    }

}

class PagamentoATM implements registry {

    private $compra;

    public function __construct($objeto = array(), $patrocinador = 0) {
        $this->db = & get_instance()->db;
        if (count($objeto) > 0) {
            $this->compra = $objeto;
        }
    }

    /**
     * Sabe fazer pagamento do tipo ATM.
     * Sabe realizar todas as operações de finalização de pagamento.
     * - Ativação
     * - Baixa de compra
     * - inclusão do registro de ativação.
     * - envio de email. para membeships.
     *//**
     * Sabe fazer pagamento do tipo ATM.
     * Sabe realizar todas as operações de finalização de pagamento.
     * - Ativação
     * - Baixa de compra
     * - inclusão do registro de ativação.
     * - envio de email. para membeships.
     */
    public function realizarPagamento() {
        if (count($this->compra) > 0) {
            $this->pagamento_parcelado();
            $this->pagarCompra();
            $this->InserirCartaoMemberShip();
            $this->ativacao();
            //Lancando o voucher de compra. nunca usou isso .
            Evoucher::lancar($this->compra);
        }
    }

    public function pagarParcelaPendentes() {

    }

    public function pagarParcelaPendentesplataformPay() {

    }

    /**
     * Realiza o pagamento do cartão memberships
     */
    public function InserirCartaoMemberShip() {
        //Salvando a compra do cartão member ship do niv.
        $distribuidor = $this->db->query("select di_niv from distribuidores "
                        . "where di_id={$this->compra->co_id_distribuidor}")->row();

        if (count($distribuidor) == 0) {
            return false;
        }

        $this->db->insert('compra_cartao_memberships', array(
            'ccm_niv' => $distribuidor->di_niv
        ));
    }

    /**
     * Realiza o pagamento da compra.
     */
    public function pagarCompra() {
        /*
         * Finalizando a compra do usuário.
         */
        $this->db->where('co_id', $this->compra->co_id)->update('compras', array(
            'co_forma_pgt' => 12,
            'co_situacao' => 7,
            'co_data_compra' => date('Y-m-d H:s:i'),
            'co_pago' => 1
        ));

        //Verifica se em sale tem mais compra para dar baixa
        $sale = $this->db->where('sa_id_compra', $this->compra->co_id)
                        ->get('compras_sales')->row();

        $compras = $this->db->where('sa_numero', $sale->sa_numero)
                        ->where('sa_id_compra !=' . $this->compra->co_id)
                        ->get('compras_sales')->result();

        if (count($compras) > 0) {
            foreach ($compras as $key => $compras_value) {
                $this->db->where('co_id', $compras_value->sa_id_compra)->update('compras', array(
                    'co_forma_pgt' => 12,
                    'co_situacao' => 7,
                    'co_data_compra' => date('Y-m-d H:s:i'),
                    'co_pago' => 1
                ));
            }
        }




        if ($this->compra->co_tipo == 100 && $this->compra->co_id_cartao != 0) {
            $this->db->insert("cartoes_distribuidor", array(
                "cd_distribuidor" => $this->compra->co_id_distribuidor,
                "cd_id_cartao" => $this->compra->co_id_cartao,
                "cd_compra" => $this->compra->co_id
            ));
        }
    }

    /**
     * Realixa todo os processo para ativar o usuário.
     * aloca na rede e insere um novo registo de ativação.
     */
    public function ativacao() {

        //Pegar o plano do distribuidor.
        $planos = $this->db->where('pa_id', $this->compra->co_id_plano)
                        ->get('planos')->row();

        if (count($planos) > 0) {
            //Verificando se o distribuidor já tem aquele plano.
            $registro_plano = $this->db->where('ps_distribuidor', $this->compra->co_id_distribuidor)
                            ->where('ps_plano', $this->compra->co_id_plano)
                            ->get('registro_planos_distribuidor')->row();

            if (count($registro_plano) == 0) {
                //Salvando o registro do plano.
                $this->db->insert('registro_planos_distribuidor', array(
                    'ps_distribuidor' => $this->compra->co_id_distribuidor,
                    'ps_compra' => $this->compra->co_id,
                    'ps_plano' => $this->compra->co_id_plano,
                    'ps_valor' => $planos->pa_valor
                ));
            }
        }

        //LanÃ§ando a ativaçã do usuario
        $ativacao = new Ativacao();
        $ativacao->lancar_ativacao($this->compra);
    }

    /**
     * Divide o total de pontos na metade.
     */
    private function pagamento_parcelado() {
        if ($this->compra->co_parcelado == 1) {
            //pegando o total de pontos
            $total_pontos = $this->db->where('pa_id', $this->compra->co_id_plano)
                            ->get('planos')->row();

            $this->db->where('co_id', $this->compra->co_id)->update('compras', array(
                'co_total_pontos' => ($total_pontos->pa_pontos / 2)
            ));
        }
    }

}

interface registry {

    public function realizarPagamento();

    public function pagarParcelaPendentes();

    public function pagarParcelaPendentesplataformPay();
}

class Pagamento {

    public function __construct() {
        $this->db = & get_instance()->ci;
    }

    public function realizarPagamento(Registry $class, $objeto = null, $patrocinador = 0) {
        return $class->realizarPagamento();
    }

    public function pagarParcelaPendentes(Registry $class, $objeto = null, $patrocinador = 0) {
        return $class->pagarParcelaPendentes();
    }

    public function pagarParcelaPendentesplataformPay(Registry $class, $objeto = null, $patrocinador = 0) {
        return $class->pagarParcelaPendentesplataformPay();
    }

}
