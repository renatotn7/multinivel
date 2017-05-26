<?php

class teste extends CI_Controller {

    public function index() {
        $distribuidores = $this->db->where('di_excluido', 0)->get('distribuidores')
                ->result();
  
        foreach ($distribuidores as $key => $distribuidor) {


            echo "<h2>{$distribuidor->di_usuario} </h2>";
            echo "e-mail: {$distribuidor->di_email}";
            //Verificar se tem cadastro na empresa
            $situacaoCadastro = atm::consultarCadastro($distribuidor);
            var_dump($situacaoCadastro);
            if (count($situacaoCadastro)>0) {
                var_dump(atm::status_universidade($distribuidor));
            } else {
                echo "Não tem cadastro na Plataforma de Pagamento";
            }
        }
    }

}
