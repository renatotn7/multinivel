<?php 
class Compra_entregue extends CI_Controller{
    public function salvar_produto_entregue(){
        $pedido = $this->input->post("pedido");
        $data_entrega = $this->input->post("data_entrega");
        $quem_entregou = $this->input->post("quem_entregou");
        $satisfacao = $this->input->post("satisfacao");
        $observacao = $this->input->post("observacao");
        $ip = $this->input->post("ip");
        
        $dados = array(
            'ce_compra' => $pedido,
            'ce_ip' => $ip,
            'ce_data_chegada' => self::formata_data_sql($data_entrega));
        
        $this->db->insert('compras_entregues', $dados);
        
        $distribuidor = $this->db->get_where('distribuidores', array('di_id' => get_user()->di_id))->row();
        
        email_compraentregue(
                "Pedido entregue: ". $pedido,
                
                $distribuidor->di_usuario,
                
                "Pedido: ". $pedido . "\r\n".
                "Data da entrega: " . $data_entrega . "\r\n".
                "Quem entregou: " . $quem_entregou . "\r\n".
                "Satisfação: " . $satisfacao . "\r\n".
                "Observação: " . $observacao . "\r\n". "\r\n" . "\r\n".
                
                "Empreendedor: " . $distribuidor->di_nome . "\r\n".
                "Usuario: " . $distribuidor->di_usuario . "\r\n".
                "Data do envio:" . date('d/m/Y H:i') . "\r\n".
                "IP:" . $ip
        );
        
        redirect(base_url("index.php/"));
    }
    
    
    
    
    private function formata_data_sql($data){
        $tokens = explode('/', $data);
        return $tokens[2] . '-' . $tokens[1] . '-' . $tokens[0]; 
    }
    
}
?>