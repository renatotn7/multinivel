<?php
class solicitacoes_saques extends CI_Controller{
    
    public function gerarcsv(){
      $nome_arquivo = "pagamento_".date('d_m_Y').".csv";
      
 if(get_parameter('de')){
		$this->db->where('cdp_data >=',data_usa(get_parameter('de'))." 00:00:00");
}
if(get_parameter('ate')){
		$this->db->where('cdp_data <=',data_usa(get_parameter('ate'))." 23:59:59");
}

      $solicitacoes_depositos = $this->db->where('cdp_status',0)
                                         ->where('cdp_id NOT IN(select ex_id_conta_bonus from conta_extorno)')
                                         ->join('distribuidores','cdp_distribuidor=di_id')
                                         ->get('conta_deposito')
                                         ->result();
         $data_csv='';
         foreach ($solicitacoes_depositos as $solicitacoes_deposito) {
             $niv = $solicitacoes_deposito->di_niv?$solicitacoes_deposito->di_niv:'xxxxxx';
             $usuario = utf8_decode($solicitacoes_deposito->di_usuario);
             $nome = utf8_decode($solicitacoes_deposito->di_nome);
             $email = utf8_decode($solicitacoes_deposito->di_email);
             $data_csv.= "3,{$this->db->get('fabricas',1)->row()->fa_nome},{$solicitacoes_deposito->cdp_valor},{$usuario},{$niv},{$nome},{$email}\n";
         }

         $abbre = fopen($nome_arquivo, "a");
         fwrite($abbre, $data_csv);
         fclose($abbre);
         
      //Forçando a apertura do arquivo   
      header("Content-Type: csv; charset=utf-8"); // informa o tipo do arquivo ao navegador
      header("Content-Length: ".filesize($nome_arquivo)); // informa o tamanho do arquivo ao navegador
      header("Content-Disposition: attachment; filename=".basename($nome_arquivo)); 
      readfile($nome_arquivo); // lê o arquivo
      unlink(basename($nome_arquivo));
    }
    
}