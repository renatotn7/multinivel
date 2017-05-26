<?php

class confirmacao_recebimento extends CI_Controller {

    public function salvar_recebimento() {
        $this->lang->load('distribuidor/confirmacao_recebimento/confirmacao_recebimento');
        $formulario = '';
        try {

            if (!$this->input->post('forma_recebimento')) {
                throw new Exception('Erro:' . $this->lang->line('erro_selecione_tipo_produto'));
            }
            //pegando a compra correspoondentes.
            $compras = $this->db->where('co_id_distribuidor', get_user()->di_id)
                            ->where('co_pago', 1)
                            ->where('co_eplano', 1)
                            ->where('co_confirmou_recebimento', 0)
                            ->where('co_total_valor !=0.00')
                            ->get('compras')->row();

            //Atualizandoque recebeu o produto.
            $this->db->where('co_id', $compras->co_id)
                    ->update('compras', array('co_confirmou_recebimento' => $this->input->post('forma_recebimento')));


            set_notificacao(1, $this->lang->line('successo'));
            redirect(base_url());
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url());
        }
    }

    public function notificar_nao_recebimento() {
        $this->lang->load('distribuidor/confirmacao_recebimento/confirmacao_recebimento');
        $formulario = '';
        try {
             //pegando a compra correspoondentes.
            $compras = $this->db->where('co_id_distribuidor', get_user()->di_id)
                            ->where('co_pago', 1)
                            ->where('co_eplano', 1)
                            ->where('co_confirmou_recebimento', 0)
                            ->where('co_total_valor !=0.00')
                            ->get('compras')->row();
            
            //Formulário formatado.
            $formulario = "{$this->lang->line('label_usuario')}: <strong>{$this->input->post('di_usuario')}</strong> <br/>";
            $formulario.= "{$this->lang->line('label_email')}: <strong>{$this->input->post('di_email')}</strong> <br/>";
            $formulario.= "{$this->lang->line('label_celular')}: <strong>{$this->input->post('di_celular')}</strong> <br/>";
            $formulario.= "{$this->lang->line('label_telefone')}: <strong>{$this->input->post('di_telefone')}</strong> <br/>";
            $formulario.= "{$this->lang->line('label_pais')}: <strong>{$this->input->post('di_pais')}</strong> <br/>";
            $formulario.= " {$this->lang->line('label_estado')}: <strong>{$this->input->post('di_pais')}</strong> <br/>";
            $formulario.= " {$this->lang->line('label_cidade')}: <strong>{$this->input->post('di_cidade')}</strong> <br/>";
            $formulario.= " {$this->lang->line('label_bairro')}: <strong>{$this->input->post('di_bairro')}</strong><br/> ";
            $formulario.= " {$this->lang->line('label_rua')}: <strong>{$this->input->post('di_rua')}</strong> <br/>";
            $formulario.= " {$this->lang->line('label_numero')}: <strong>{$this->input->post('di_numero')}</strong> ";
            $formulario.= " {$this->lang->line('label_complemento')}: <strong>{$this->input->post('di_complemento')}</strong><br/> ";
            $formulario.= " {$this->lang->line('label_codigo_postal')} : <strong>{$this->input->post('di_cep')}</strong> ";

            //Atualizandoque recebeu o produto.
            $this->db->where('co_id', $compras->co_id)
                    ->update('compras', array('co_nao_recebeu_produto' => 1));
            
            //enviando email
            enviar_notificao_nao_recebeu_produto($formulario, $this->input->post('forma_recebimento'));
            set_notificacao(1, $this->lang->line('successo'));
            redirect(base_url());
            
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url());
        }
    }

}
