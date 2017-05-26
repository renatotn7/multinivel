<?php

/**
 * Description of DistribuidorLoja
 *
 * @author Ronyldo12
 */
error_reporting(E_ALL);
class DistribuidorLoja {

    private $db;

    public function __construct() {
        $this->db = & get_instance()->db;
    }

    public function atualizar_consultor($distribuidor) {
        $jaEstaCadastrado = $this->e_consultor($distribuidor);
        //Dados para a cadastro na tabela customer da loja virtual
        //Nome e Sobrenome
        $nome =  $distribuidor->di_nome.' '.$distribuidor->di_ultimo_nome;
        $sobreNome = $distribuidor->di_ultimo_nome;
       
        //End
        //Cidade
        $cidade = $this->db->where('ci_id', $distribuidor->di_cidade)->get('cidades')->row();
        $cidade = $cidade->ci_nome;

        $ip = $_SERVER["REMOTE_ADDR"];

        $telefone = empty($distribuidor->di_fone1) ? '-' : $distribuidor->di_fone1;
        $email = empty($distribuidor->di_email) ? '-' : $distribuidor->di_email;

        //Dados Tabela loja_customer
        $consultorArray = array(
            'firstname' => $nome,
            'consultor_id' => $distribuidor->di_id,
            'lastname' => $sobreNome,
            'email' => $email,
            'telephone' => $telefone,
            'fax' => '',
            'password' => '',
            'salt' => '',
            'cart' => 'a:0:{}',
            'newsletter' => 1,
            'address_id' => '1',
            'customer_group_id' => 3,
            'ip' => $ip,
            'status' => 1,
            'approved' => 1,
            'token' => '',
            'date_added' => date('Y-m-d H:i:s'),
        );

        $consultor = $this->db
                        ->where('consultor_id', $distribuidor->di_id)
                        ->get('loja_customer')->row();

        //Inserir dados consultor loja_customer
        if (count($consultor)>0) {
            $this->db
                    ->where('consultor_id', $distribuidor->di_id)
                    ->set($consultorArray)
                    ->update('loja_customer');
            $idConsultor = $consultor->customer_id;
        } else {
            $this->db->insert('loja_customer', $consultorArray);
            $idConsultor = $this->db->insert_id();
        }

        $zone = $this->db
                ->where('code',$distribuidor->ci_uf)
                ->get('loja_zone')->row();

        $zoneId = isset($zone->zone_id)?$zone->zone_id:0;
        $countryId = isset($zone->country_id)?$zone->country_id:0;
        
        $Endereco = array(
            'consultor_id' => $distribuidor->di_id,
            'customer_id' => $idConsultor,
            'firstname' => $nome,
            'lastname' => $sobreNome,
            'company_id' => $distribuidor->di_rg,
            'tax_id' => empty($distribuidor->di_rg)?0:$distribuidor->di_rg,
            'address_1' => $distribuidor->di_endereco . " " . $distribuidor->di_complemento,
            'address_2' => $distribuidor->di_bairro,
            'city' => $cidade,
            'postcode' => $distribuidor->di_cep,
            'country_id' => $countryId,
            'zone_id' => $zoneId
        );


        $enderecoConsultor = $this->db->where('consultor_id', $distribuidor->di_id)->get('loja_address')->row();

        //Inserir endereço do consultor loja_adress
        if (is_object($enderecoConsultor)) {
            //Atualiza dados do endereço
            $this->db->where('consultor_id', $distribuidor->di_id)->set($Endereco)->update('loja_address');
            //Pega o id do endereço alterado
            $end = $this->db->where('consultor_id', $distribuidor->di_id)->get('loja_address')->row();
            $idEndereco = $end->address_id;
        } else {

            //Insere um novo endereço   
            $this->db->insert('loja_address', $Endereco);

            //Pega o id do endereço inserido
            $idEndereco = $this->db->insert_id();
            $this->db->where('consultor_id',$idConsultor)->update('loja_customer', array(
                'address_id' => $idEndereco
            ));
        }
        //End



        $_SESSION['customer_id'] = $idConsultor;

        //End Função gravar dados Consultor na loja 		
    }

    /* -----------------------------------------------------------------
     * FUNÇÃO E CONSULTOR
     * --------------------------------------------------------------- */

    public function e_consultor($distribuidor) {

        //Verificar se o distribuidor se o distribudiror possui cadastro
        $consultor = $this->db
                        ->where('consultor_id', $distribuidor->di_id)
                        ->get('loja_customer')->num_rows();

        return $consultor > 0;
    }

}
