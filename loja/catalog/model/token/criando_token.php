<?php

class ModelTokenCriandoToken extends Model {

    public function gerarToken($id_custumer = 0) {
        $token = substr(base64_encode(md5(date('Y-m-d H:i:s') . '-' . time())), 0, 20);
        $consulta_token = $this->db->query("SELECT * FROM token_confirmacao_cadastro WHERE tk_token = '{$token}';");
        if ($consulta_token->num_rows) {
            self::token($id_custumer);
        }
        //inser a token;
        $this->db->query("INSERT INTO token_confirmacao_cadastro "
                . "(tk_distribuidor,tk_token) values('{$id_custumer}','{$token}');");

        return $token;
    }

    public function get_email() {
        $email_remetente = $this->db->query("select valor from config where field ='email_ativacao_estados_unidos';");
        return $email_remetente->row['valor'];
    }

}
