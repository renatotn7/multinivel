<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payment_bonus
 *
 * @author Werlon Guilherme <werlong@gmail.com>
 */
class ControllerPaymentPaymentBonus extends Controller {

    protected function index() {
        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->data['continue'] = $this->url->link('checkout/success');
        $this->data['pagar_com_bonus'] = APP_BASE_URL . APP_DISTRIBUIDOR . '/index.php/pedidos/confirmar_pagamento?id_pedido=' . $this->session->data['order_id'] . '&from=oc';
        

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/payment_bonus.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/payment_bonus.tpl';
        } else {
            $this->template = 'default/template/payment/payment_bonus.tpl';
        }

        $this->render();
    }

    /**
     * Para outros metodos
     */
    public function confirm() {
        $this->load->model('checkout/order');

        $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cod_order_status_id'));
    }

}
