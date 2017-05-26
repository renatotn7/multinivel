<?php
class acessar_loja extends CI_Controller {
    
    public function index(){
        $this->redirecionar();    
    }
    
    /* --------------------------------------------------------------
     * FUNÇÃO IR LOJA
     *
      /--------------------------------------------------------------- */

    public function redirecionar(){
           $distribuidorLoja = new DistribuidorLoja();
           $distribuidorLoja->atualizar_consultor(get_user());
           
        if($this->input->get('url_loja')){
           redirect($this->input->get('url_loja'));
        }else{
           redirect(APP_BASE_URL.APP_LOJA); 
        }
        
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO E CONSULTOR
     * --------------------------------------------------------------- */

    public function e_consultor() {

        //Verificar se o distribuidor se o distribudiror possui cadastro
        $consultor = $this->db
                        ->where('customer_id', get_user()->di_id)
                        ->get('loja_customer')->num_rows();

        return $consultor;
    }

    public function e_endereco() {
        //Verficar se a endereço cadastrado 
        $endereco = $this->db
                        ->where('customer_id', get_user()->di_id)
                        ->get('loja_address')->num_rows();

        return $endereco;
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO ATUALIZAR DADOS CONSULTOR
     * --------------------------------------------------------------- */

    public function atualizar_consultor() {
        //Dados para a cadastro na tabela customer da loja virtual
        //Nome e Sobrenome
        $nomeSobrenome = explode(' ', get_user()->di_nome);
        $nome = $nomeSobrenome[0];
        $sobreNome1 = isset($nomeSobrenome[1]) ? $nomeSobrenome[1] : '';
        $sobreNome2 = isset($nomeSobrenome[2]) ? $nomeSobrenome[2] : '';
        $sobreNome3 = isset($nomeSobrenome[3]) ? $nomeSobrenome[3] : '';
        $sobreNome4 = isset($nomeSobrenome[4]) ? $nomeSobrenome[4] : '';
        $sobreNome = $sobreNome1 . " " . $sobreNome2 . " " . $sobreNome3 . " " . $sobreNome4;
        //End
        //Cidade
        $cidade = $this->db->where('ci_id', get_user()->di_cidade)->get('cidades')->row();
        $cidade = $cidade->ci_nome;

        $ip = $_SERVER["REMOTE_ADDR"];

        //Dados Tabela loja_adress
        if ($this->e_consultor() > 0 && $this->e_endereco() > 0) {

            $endereco = $this->db->where('customer_id', get_user()->di_id)->get('loja_address')->row();
            $endConsultor = $endereco->address_id;
        } else {

            $endConsultor = NULL;
        }

        //var_dump($endConsultor);exit;

        $Endereco = array(
            'address_id' => $endConsultor,
            'customer_id' => get_user()->di_id,
            'firstname' => $nome,
            'lastname' => $sobreNome,
            'company_id' => get_user()->di_rg,
            'tax_id' => get_user()->di_cpf,
            'address_1' => get_user()->di_endereco . " " . get_user()->di_complemento,
            'address_2' => get_user()->di_bairro,
            'city' => $cidade,
            'postcode' => get_user()->di_cep,
            'country_id' => 30,
            'zone_id' => get_user()->di_uf
        );


        //Inserir endereço do consultor loja_adress
        if ($this->e_consultor() > 0 && $this->e_endereco() > 0) {

            //Atualiza dados do endereço
            $this->db->where('customer_id', get_user()->di_id)->set($Endereco)->update('loja_address');

            //Pega o id do endereço alterado
            $end = $this->db->where('customer_id', get_user()->di_id)->get('loja_address')->row();
            $id_consultor = $end->address_id;
        } else {

            //Insere um novo endereço   
            $this->db->insert('loja_address', $Endereco);

            //Pega o id do endereço inserido
            $id_consultor = mysql_insert_id();
        }
        //End
        //Dados Tabela loja_customer
        $consultor = array(
            'customer_id' => get_user()->di_id,
            'firstname' => $nome,
            'lastname' => $sobreNome,
            'email' => get_user()->di_email,
            'telephone' => get_user()->di_fone1,
            'fax' => '',
            'password' => '',
            'salt' => '',
            'cart' => 'a:0:{}',
            'newsletter' => 1,
            'address_id' => $id_consultor,
            'customer_group_id' => 3,
            'ip' => $ip,
            'status' => 1,
            'approved' => 1,
            'token' => '',
            'date_added' => date('Y-m-d H:i:s'),
        );

        //Inserir dados consultor loja_customer
        if ($this->e_consultor() > 0) {
            $this->db->where('customer_id', get_user()->di_id)->set($consultor)->update('loja_customer');
        } else {
            $this->db->insert('loja_customer', $consultor);
        }
        //End 

        $_SESSION['customer_id'] = get_user()->di_id;

        //End Função gravar dados Consultor na loja 		
    }

    public function template_ir_loja() {
        $data['usuario'] = get_user()->di_usuario;
        $this->load->view('acessar_loja/index_view',$data);
    }

    /*
      | -------------------------------------------------------------------------
      | FIM DO CONTROLLER
      | -------------------------------------------------------------------------
     */
}

?>