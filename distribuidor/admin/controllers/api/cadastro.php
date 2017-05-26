<?php

class cadastro extends CI_Controller {

    private function oauth() {

        //Verificando se a toquem passada é valida.
        try {

            if ($this->input->post('token')) {

                if (api::validar_token($this->input->post('token'))) {
                    return true;
                } else {
                    throw new Exception('Erro: Token inválida.');
                }
            } else {
                throw new Exception('Erro: Token inválida.');
            }
        } catch (Exception $ex) {
            $this->handleException($ex);
            return false;
        }
    }

    private function handleException($e) {
        echo "Erro api cadastro usuário: " . $e->getMessage();
        exit;
    }
    
    public function usuario() {
        
     try {
            //Tratamento de erro de parâmetros nulos.
           /* if (!$this->input->post('sponsor')) {
                throw new Exception('Error: Patrocinador não informado.');
            }

            if (!$this->input->post('login')) {
                throw new Exception('Error: Login não informado.');
            }

            if (!$this->input->post('password')) {
                throw new Exception('Error: Senha não informada.');
            }
            if (!$this->input->post('passwordsecurity')) {
                throw new Exception('Error: Chave de segurança não informada.');
            }
            if (!$this->input->post('agency')) {
                throw new Exception('Error: Código do plano não informado.');
            }
            if (!$this->input->post('registrationtype')) {
                throw new Exception('Error: Tipo de cadastro não informado.');
            }           
            if (!$this->input->post('email')) {
                throw new Exception('Error: Email não informado.');
            }
            if (!$this->input->post('niv')) {
                throw new Exception('Error: Niv não informado.');
            }  
            if (!$this->input->post('name')) {
                throw new Exception('Error: Nome não informado.');
            }  
            if (!$this->input->post('lastname')) {
                throw new Exception('Error: Sobrenome não informado.');
            }  
            if (!$this->input->post('cellularphone')) {
                throw new Exception('Error: Celular não informado.');
            }   
            if (!$this->input->post('homephone')) {
                throw new Exception('Error: Telefone fixo não informado.');
            }   
            if (!$this->input->post('doctype')) {
                throw new Exception('Error: Tipo de documento não informado.');
            }   
            if (!$this->input->post('docnumber')) {
                throw new Exception('Error: Número do documento não informado.');
            }   
            if (!$this->input->post('birthdate')) {
                throw new Exception('Error: Data de nascimento não informada.');
            }   
            if (!$this->input->post('citybirth')) {
                throw new Exception('Error: Cidade não informada.');
            }   
            if (!$this->input->post('countrybirth')) {
                throw new Exception('Error: País não informado.');
            }   
            if (!$this->input->post('gender')) {
                throw new Exception('Error: Sexo não informado.');
            }   
            if (!$this->input->post('countryregister')) {
                throw new Exception('Error: País não informado.');
            }   
            if (!$this->input->post('stateregister')) {
                throw new Exception('Error: Estado não informado.');
            }   
            if (!$this->input->post('cityregister')) {
                throw new Exception('Error: Cidade não informado.');
            }   
            if (!$this->input->post('street')) {
                throw new Exception('Error: Nome da rua não informado.');
            }   
            if (!$this->input->post('addressnumber')) {
                throw new Exception('Error: Número não informado.');
            }   
            if (!$this->input->post('addresscomp')) {
                throw new Exception('Error: Complemento não informado.');
            }   
            if (!$this->input->post('zipcode')) {
                throw new Exception('Error: CEP não informado.');
            }   
            if (!$this->input->post('districtregion')) {
                throw new Exception('Error: Bairro não informado.');
            }   
            if (!$this->input->post('cardflag')) {
                throw new Exception('Error: Tipo de cartão não informado.');
            } 
            // ---
            
            if(!api::verificar_patrocinador($this->input->post('sponsor'))){
                throw new Exception('Error: Patrocinador não encontrado.');
            }  
            
            if(!api::verifica_planos($this->input->post('sponsor'),$this->input->post('agency'))){
                throw new Exception('Error: Usuário não pode ser patrocinador dessa agencia.');
            }
            
            if(!api::verificar_login($this->input->post('login'))){
                throw new Exception('Error: Login inválido.');
            }
            
            if(!api::verificar_senha($this->input->post('password'))){
                throw new Exception('Error: Senha inválida.');
            }
         
            if(!api::verificar_senha_seguranca($this->input->post('passwordsecurity'),$this->input->post('password'))){
                throw new Exception('Error: Senha de segurança inválida.');
            }
            
            if(!in_array($this->input->post('agency'),array(99,100,101,102,103))){
                throw new Exception('Error: Plano inválido.');
            }
            if(api::verificar_email($this->input->post('email')) == 'vazio'){
                throw new Exception('Error: E-mail não informado.');
            }
            if(api::verificar_email($this->input->post('email')) == 'invalido'){
                throw new Exception('Error: E-mail inválido.');
            }            
            if(api::verificar_email($this->input->post('email')) == 'existente'){
                throw new Exception('Error: E-mail já cadastrado.');
            }  
            if(api::verificar_data($this->input->post('birthdate')) == 'vazio'){
                throw new Exception('Error: Data de nascimento não informada.');
            }
            if(api::verificar_data($this->input->post('birthdate')) == 'formato'){
                throw new Exception('Error: Data com padrão incorreto.');
            }  
            if(api::verificar_pais($this->input->post('countrybirth')) == 'vazio'){
                throw new Exception('Error: País de nascimento não informado.');
            }
            if(api::verificar_pais($this->input->post('countrybirth')) == 'formato'){
                throw new Exception('Error: País de nascimento com formato inválido.');
            }
            if(api::verificar_pais($this->input->post('countrybirth')) == 'invalido'){
                throw new Exception('Error: País de nascimento não encontrado.');
            } 
            if(api::verificar_pais($this->input->post('countryregister')) == 'vazio'){
                throw new Exception('Error: País de cadastro não informado.');
            }
            if(api::verificar_pais($this->input->post('countryregister')) == 'formato'){
                throw new Exception('Error: País de cadastro com formato inválido.');
            }
            if(api::verificar_pais($this->input->post('countryregister')) == 'invalido'){
                throw new Exception('Error: País de cadastro não encontrado.');
            }
            if ($this->input->post('cardflag') <> 9 && $this->input->post('cardflag') <> 7 && $this->input->post('cardflag') <> 10) {
                throw new Exception('Error: Tipo de cartão inválido.');
            }*/
         
         
            
         
                                                                                                     
     } catch (Exception $exc) {
         echo $this->handleException($exc);
     }

        
        
    }

}

?>
