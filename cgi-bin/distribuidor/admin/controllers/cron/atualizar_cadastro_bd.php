<?php

class atualizar_cadastro_bd extends CI_Controller {

    public function run() {
        CHtml::berginTime();
	ob_start();        
        $count = 0;
        set_time_limit(0);
        $distribuidores = $this->db->where('di_niv IS NOT NULL')
                        ->where('di_email IS NOT NULL')
                        ->where('di_excluido', 0)
                        ->get('distribuidores')->result();
        
        $consulta = new atm();
        foreach ($distribuidores as $distribuidor) {
            $obj = $consulta->consulta_cadastro_ewallet($distribuidor->di_email);
            
            if((isset($obj['city']) ? self::getIdCidade($obj) : $distribuidor->di_cidade) == false){
                continue;
            }
            
            $data = array(
                'di_data_nascimento' => isset($obj['birthday']) ? $obj['birthday'] : $distribuidor->di_data_nascimento,
                'di_fone2' => isset($obj['cellPhone']) ? $obj['cellPhone'] : $distribuidor->di_fone2,
                'di_cidade' => isset($obj['city']) ? self::getIdCidade($obj) : $distribuidor->di_cidade,
                'di_cidade_nascimento' => isset($obj['cityOfBirth']) ? $obj['cityOfBirth'] : $distribuidor->di_cidade_nascimento,
                'di_complemento' => isset($obj['completion']) ? $obj['completion'] : $distribuidor->di_complemento,
                'di_bairro' => isset($obj['district']) ? $obj['district'] : $distribuidor->di_bairro,
                'di_email' => isset($obj['email']) ? $obj['email'] : $distribuidor->di_email,
                'di_nome' => isset($obj['name']) ? $obj['name'] : $distribuidor->di_nome,
                'di_niv' => isset($obj['niv']) ? $obj['niv'] : $distribuidor->di_niv,
                'di_fone1' => isset($obj['phone']) ? $obj['phone'] : $distribuidor->di_fone1,
                'di_uf' =>  isset($obj['state']) ? self::getIdEstado($obj['state'])->es_uf : $distribuidor->di_uf,
                'di_endereco' => isset($obj['street']) ? $obj['street'] : $distribuidor->di_endereco,
                'di_ultimo_nome' => isset($obj['surname']) ? $obj['surname'] : $distribuidor->di_ultimo_nome,
                'di_rg' => isset($obj['taxIdNumber']) ? $obj['taxIdNumber'] : $distribuidor->di_rg,
                'di_cep' => isset($obj['zipCode']) ? $obj['zipCode'] : $distribuidor->di_cep
            );
            
            $this->db->where('di_email', $distribuidor->di_email)
                       ->update('distribuidores', $data);
            //echo "ID: ".$distribuidor->di_id." Finalizado: ".$count++."<br>";
            

        }
        echo 'Finalizado!';

        CHtml::endTime();
        $registro = ob_get_clean();
            
        CHtml::logexec('bonus_pl_rodou_em_data_'.date('d_m_Y'),$registro.' em '.date('d_m_Y_H_s_i'),'bonus_pl');  

    }

    public static function getIdCidade($obj) {
        $id_cidade = get_instance()->db
                        ->where('ci_nome', $obj['city'])
                        ->get('cidades', 1)->row();
        
        if(count($id_cidade)==0){
            
            /*$id_pais = get_instance()->db
                        ->where('ps_iso3', $obj['country'])
                        ->get('pais')->row();
            
            get_instance()->db->insert('cidades',
                array(
                        'ci_pais'=>$id_pais->ps_id,
                        'ci_nome'=>$obj['city'])
                );
        
        return get_instance()->db->insert_id();
        */
            return false;
        }
        return $id_cidade->ci_id;
    }

    public static function getIdEstado($estado) {
        $id_estado = get_instance()->db
                        ->where('es_nome', $estado)
                        ->get('estados', 1)->row();

        return $id_estado;
    }

}

?>
