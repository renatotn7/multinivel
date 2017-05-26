<?php

class cadastro extends CI_Controller {

    public function index() {
        $distribuidor = substr(md5(time()), 0,25);
        $usuario = 'usuario'.rand(0,100);
    var_dump($usuario);
        $fields = array ( 
//            'codigo_promocional' => '58ab-5805-5811-25128.2014-2',
            'di_usuario_patrocinador' => 'usuario89',
            'di_usuario' => $usuario,
            'senha' => $distribuidor,
            'senha_finaceira' => $distribuidor.'1',
            'tipopessoa' => '0',
//            'dpj_nome_empresa' => '',
//            'dpj_tx_identificacao' => '',
//            'dpj_diregente_responsavel' => '',
//            'dpj_diretor' => '',
//            'dpj_endereco' => '', 
            'di_email' => $distribuidor.'@mail.com',
            'di_nome' => 'nome da vovo',
            'di_ultimo_nome' => 'apelido cunha', 
            'di_fone2' => '12345678', 
            'di_fone1' => '12345678',
            'di_tipo_documento' => 'sdfasdf', 
            'di_rg' => 'DOC'.  rand(1,20),
            'di_data_nascimento' => '11/11/1987',
            'di_cidade_nascimento' => 'lugar do nascimento',
            'di_pais_nascimento' => '19',
            'di_sexo' => 'M', 
            'di_pais' => '8', 
            'di_uf' => '271',
            'di_cidade' => 'kdafjakl',
            'end_pais' => '2',
            'end_cidade' => 'djfkajkd',
            'di_endereco' => 'asfdkaj',
            'di_numero' => '98983939',
            'di_complemento' => 'jkadaskl',
            'di_cep' => 'adfaksdjfk',
            'di_bairro' => 'ba,lÃ§kdalf',
            'li' => 'sim',
            'di_cartao_membership' => '3',
            'plano' => '100',
            );
        
            $url='http://localhost/empresa/publico/index.php/distribuidor/salvar_distribuidor';
            $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($fields, '', '&')
            )
        );
        
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        
         //Colocando o email no distribuidor
        $this->db->where('di_usuario',$usuario)->update('distribuidores',array(
            'di_niv'=>'20183',
            'di_email'=>'system@empresa.com',
            'di_email_atm'=>'system@empresa.com'
        ));
        
        var_dump ($result);
        
       
    }

}
