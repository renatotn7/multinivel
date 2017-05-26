<?php

class historico_transacao extends CI_Controller {

    public function index() {
        try {

            if (!$this->uri->segment(3)) {
                throw new Exception('usuário não encotrado');
            }

            $historico = $this->db->where('co_id_distribuidor', $this->uri->segment(3))
                    ->select("GROUP_CONCAT('<li>',pr_nome,'</li>') as produto,co_descricao,sa_id,co_id,sa_mensagem,sa_protocolo,sa_status,sa_numero",false)
                    ->join('compras_sales', 'sa_id_compra=co_id')
                    ->join('produtos_comprados', 'pm_id_compra=co_id')
                    ->join('produtos', 'pr_id=pm_id_produto')
                    ->group_by('sa_numero')
                    ->get('compras')
                    ->result();
    
            $data['historico'] = $historico;
            $data['pagina'] = strtolower(__CLASS__) . "/historico_transacao";
            $this->load->view('home/index_view', $data);
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url());
        }
    }

    public function atualizar() {
        try {

            if (!$this->uri->segment(3)) {
                throw new Exception('usuário não encotrado');
            }

            $historico = $this->db->where('co_id_distribuidor', $this->uri->segment(3))
                    ->select('co_descricao,sa_id,co_id,sa_mensagem,sa_protocolo,sa_status')
                    ->join('compras_sales', 'sa_id_compra=co_id')
                    ->group_by('sa_numero')
                    ->get('compras')
                    ->result();


            if (count($historico) > 0) {
                foreach ($historico as $key => $value) {
                    if (!in_array($value->sa_status, array(3, 4, 6, ''))) {
                        continue;
                    }

                    ComprasModel::logSalesTransacoes($value);
                }
            }

            set_notificacao(1, 'Atualizado com sucesso.');
            redirect(base_url('index.php/historico_transacao/index/' . $this->uri->segment(3)));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/historico_transacao/index/' . $this->uri->segment(3)));
        }
    }

}
