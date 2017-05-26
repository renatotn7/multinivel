<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/total');
	 
		$total_data[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'text'       => $this->currency->format(max(0, $total)),
			'value'      => max(0, $total),
			'sort_order' => $this->config->get('total_sort_order')
		);
	}
        
        
        
         public function getBonus($valor) {
        $query = $this->db->query("SELECT SUM(cb_credito)-SUM(cb_debito) as bonus "
                . "FROM conta_bonus "
                . "JOIN distribuidores ON di_id = cb_distribuidor "
                . "WHERE cb_distribuidor = '" . $this->customer->getConsultorId() . "'");

        if ($query->num_rows == 0) {
            return false;
        }

        $qualificacao = $this->db->query("SELECT * FROM historico_qualificacao JOIN distribuidor_qualificacao ON dq_id = hi_qualificacao
WHERE hi_distribuidor = '" . $this->customer->getConsultorId() . "' ORDER BY hi_qualificacao DESC LIMIT 1");

        if ($qualificacao->num_rows == 0) {
            return false;
        }

        if ($query->row['bonus'] >= $valor && $qualificacao->row['hi_qualificacao'] >= 2) {
            return true;
        }

        return false;
    }
        
}
?>