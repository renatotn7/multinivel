<?php
class Financeiro extends CI_Controller {
	
	public function index() {
		$result = $this->db->where ( 'field', 'administrar_data_deposito_em_conta' )->get ( 'config' )->row ();
		$data ['ct_deposito'] = $result;
		$data ['pagina'] = 'financeiro/administrar__deposito_conta';
		$this->load->view ( 'home/index_view', $data );
	}
	
	public function salvar_conta_depoisto() {
		
		$administrar_data_deposito_em_conta = $this->input->post ( 'administrar_data_deposito_em_conta' );
		$dados = array (
				'valor' => $administrar_data_deposito_em_conta 
		);
		
		$this->db->where ( 'field', 'administrar_data_deposito_em_conta' )->update ( 'config', $dados );
		
		set_notificacao ( array (
				0 => array (
						'tipo' => 1,
						'mensagem' => "Atualizado com sucesso!" 
				) 
		) );
		redirect ( base_url ( 'index.php/financeiro' ) );
	}
        
        public function taxas_cambio_frete(){
            $data ['pagina'] = 'financeiro/taxas_cambio_frete';
            $this->load->view ( 'home/index_view', $data );
        }
        
        /**
         * Salvar taxa de cambío e frete.
         */
        public function salvar_taxas_cambio_frete()
        {
            //Auditoria geral
//            auditoriaGeral::insert(valida_fields('moeda_cambio', $_POST),'moeda_cambio');
            
            $this->db->insert('moeda_cambio',valida_fields('moeda_cambio', $_POST));
            set_notificacao (array(0 => array (
                                'tipo' => 1,
				'mensagem' => "Salvo com sucesso!" 
                                 ))
                            );
            
	     redirect ( base_url ( 'index.php/financeiro/taxas_cambio_frete' ) );
        }
        
        /**
         * Atualizar os valores ta daxa de cambio e frete.
         */
        public function update_taxas_cambio_frete()
        {
            if(isset($_POST['camb_id']) && !empty($_POST['camb_id'])){
            
            //Auditoria geral
//            auditoriaGeral::update('camb_id',valida_fields('moeda_cambio', $_POST),'moeda_cambio');
            
            $this->db->where('camb_id',$_POST['camb_id'])
                    ->update('moeda_cambio',$_POST);
            
            set_notificacao(array(0 =>array(
                                    'tipo' => 1,
				     'mensagem' => "Atualizado com sucesso!" 
				))
                            );
            
            }
            
            redirect ( base_url ( 'index.php/financeiro/taxas_cambio_frete' ) );
        }

        /**
         * Excluir taxa câmbio e frete.
         */
        public function excluir_taxas_cambio_frete()
        {
            
          if(isset($_REQUEST['camb']) && !empty($_REQUEST['camb'])){
              
            //Auditoria geral
//            auditoriaGeral::delete('camb_id',$_REQUEST['camb'],'moeda_cambio');
            
            $this->db->where('camb_id',$_REQUEST['camb'])->delete('moeda_cambio');
               set_notificacao (array(0 => array(
				'tipo' => 1,
				'mensagem' => "Excluído com sucesso!" 
			       ))
                               );
            }
		redirect ( base_url ( 'index.php/financeiro/taxas_cambio_frete' ) );
          
           
        }
        
        public function atualizar_moeda_ajax()
        {
           if(isset($_REQUEST['has_moed_id_pais']) && !empty($_REQUEST['has_moed_id_pais']))
           {
               $moedas = $this->db->where('has_moed_id_pais',$_REQUEST['has_moed_id_pais'])
                       ->select('moedas.*')
                       ->join('moedas_paises','has_moed_id_moeda=moe_id')
                       ->group_by('moe_nome')
                       ->get('moedas')->result();
               
               if(count($moedas)>0)
               {
                   echo json_encode($moedas);
               }else{
                   echo json_encode(array());
               }
           }else{
                echo json_encode(array());
           }
        }
}