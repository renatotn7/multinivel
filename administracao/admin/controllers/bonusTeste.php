<?php
class BonusTeste extends CI_Controller{
	function index() {
		/**
         * Transação de pagamentos de bonus
         * 1 - verifica se o bonus não ja foi pago 

		 */
		$objeCompra=$this->db->where('co_id','12903')->get('compras',1)->row();
		Bonus::IndicacaoDireta($objeCompra);
	}
}