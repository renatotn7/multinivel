<?php

class planos extends CI_Controller {

    public function index() {
        autenticar();
        //Planos do sistema.
        $this->plano();
    }

    public function plano() {
        autenticar();
        $planos = $this->db->order_by('pa_id', 'asc')->get('planos')->result();

        $data['planos'] = $planos;
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function salvar() {
        autenticar();
       
        
        foreach ($_POST['pa_id'] as $key => $post) {
            
            $dados = array(
                'pa_descricao' => $_POST['pa_descricao_' . $key],
                'pa_valor' => $_POST['pa_valor_' . $key],
                'pa_indicacao_direta' => $_POST['pa_indicacao_direta_' . $key],
                'pa_indicacao_indireta' => $_POST['pa_indicacao_indireta_' . $key],
                'pa_pl' => $_POST['pa_pl_' . $key],
                'pa_avanco_titulo' => $_POST['pa_avanco_titulo_' . $key],
                'pa_binario' => $_POST['pa_binario_' . $key],
                'pa_bonus_lideranca' => $_POST['pa_bonus_lideranca_' . $key],
                'pa_pontos' => $_POST['pa_pontos_' . $key],
                'pa_qtd_niveis' => $_POST['pa_qtd_niveis_' . $key],
                'pa_produto' => $_POST['pa_produto_'. $key],
                'pa_total' => $_POST['pa_total_' . $key],
                'pa_bonus_unilevel_valor' => $_POST['pa_bonus_unilevel_valor_' . $key],
                'pa_bonus_unilevel_geracoes' => $_POST['pa_bonus_unilevel_geracoes_' . $key],
            	'pa_taxa_manutencao'=>	$_POST['pa_taxa_manutencao_' . $key],
            	'pa_link_bonus'=>	$_POST['pa_link_bonus_' . $key],
            	'pa_valor_euro'=>	$_POST['pa_valor_euro_' . $key],
            	'pa_numero_token_derramamento'=>	$_POST['pa_numero_token_derramamento_' . $key],
            	'pa_numero_token_ativacao_binario'=>	$_POST['pa_numero_token_ativacao_binario_' . $key],
            	'pa_id'=>$post
            );
            
             //Auditoria geral 
//             auditoriaGeral::update('pa_id',$dados,'planos');
             $this->db->where('pa_id', $post)->update('planos',$dados);
        }
        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Atualizado com sucesso.")));
        redirect(base_url('index.php/planos'));
    }
    /*
     * Salvar e editar valores de upgrades.
     */
    public function upgrade(){
        autenticar();
         foreach ($_POST['pug_id'] as $key => $idUpgrade) {
                $this->db->where('pug_id', $idUpgrade)->update('planos_upgrades', array(
                'pug_valor' => $_POST['pug_valor_'.$idUpgrade],
                'pug_pontos' => $_POST['pug_pontos_'.$idUpgrade],
                'pug_produto' => $_POST['pug_produto_'.$idUpgrade],
            ));
         }
        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Atualizado com sucesso.")));
        redirect(base_url('index.php/planos'));
    }

}