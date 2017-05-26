<?php

class AtivacaoBinario {

    private $pontosEsquerda;
    private $pontosDireita;
    private $compraAtivacaoBinario;
    private $distribuidor;
    private $pontosNecessariosParaAtivarBinario;
    private $db;

    public function __construct($distribuidor) {
        $this->db = get_instance()->db;
        $this->pontosDireita = 0;
        $this->pontosEsquerda = 0;
        $this->distribuidor = $distribuidor;
    }
    
    public function desativarBinarioDistribuidor() {

        // Atualiza o binário do distribuidor
        $ja_lancado_binario = $this->db->where('db_distribuidor', $this->distribuidor->di_id)->get('registro_distribuidor_binario')->row();

        // Verifica se não já tem registro ativo
        if (count($ja_lancado_binario) > 0) {
            //Gerando os codigo promocionais se essa não gerá os código é provável que o usuário foi 
            //ativado com um dos codigo.
            ComprasModel::codigoPromocionalAtivacaoBinario($this->distribuidor->di_id);
            //echo "<p>Deletou: {$this->distribuidor->di_usuario}</p>";
            // Salva o log do registro do distribuidor no binário.
            $this->db->insert('registro_distribuidor_binario_log', array(
                'rbl_distribuidor' => $ja_lancado_binario->db_distribuidor,
                'rbl_data' => $ja_lancado_binario->db_data
            ));

            // Excluido o registro do distribuidor do binário pois não ta qualificado.
            $this->db->where('db_distribuidor', $this->distribuidor->di_id)->delete('registro_distribuidor_binario');
        }
    }

    public function ativarBinarioDistribuidor() {
        // Atualiza o binário do distribuidor
        if ($this->distribuidor->di_binario ==0) {
            //Gerando os codigo promocionais se essa não gerá os código é provável que o usuário foi 
            //ativado com um dos codigo.
            //ComprasModel::codigoPromocionalAtivacaoBinario($this->distribuidor->di_id);
            $this->db->insert('registro_distribuidor_binario', array(
                'db_distribuidor' => $this->distribuidor->di_id,
                'db_data' => $this->compraAtivacaoBinario->co_data_compra
            ));
            //atualizando o di_binário voltando ativação binária antiga.
            $this->db->where('di_id', $this->distribuidor->di_id)->update('distribuidores',array(
                'di_binario'=>1
            ));
            
        } 
//retirada porque não tem ativação mensal mais
//        else {
//
//            if (date('Y-m-d', strtotime($ja_lancado_binario->db_data)) != date('Y-m-d', strtotime($this->compraAtivacaoBinario->co_data_compra))) {
//                $this->db->where('db_distribuidor', $this->distribuidor->di_id)->update('registro_distribuidor_binario', array(
//                    'db_data' => $this->compraAtivacaoBinario->co_data_compra
//                ));
//            } else {
//                //echo "<p>Nao Atualizou data tava certinho: {$this->distribuidor->di_usuario}</p>";
//            }
//        }
    }

    private function definirPerna($compras, $perna) {
        foreach ($compras as $k => $compra) {
            $compras [$k]->perna = $perna;
        }
        return $compras;
    }

    private function verificarSeAtivouAbinario($compraAtual) {
        if ($this->pontosDireita >= $this->pontosNecessariosParaAtivarBinario && $this->pontosEsquerda >= $this->pontosNecessariosParaAtivarBinario) {
            $this->compraAtivacaoBinario = $compraAtual;
            return true;
        } else {
            return false;
        }
    }

    private function carregarPontosNecessariosParaArivarBinario() {
        $this->pontosNecessariosParaAtivarBinario = isset(DistribuidorDAO::getPlano($this->distribuidor->di_id)->pa_pontos) ? DistribuidorDAO::getPlano($this->distribuidor->di_id)->pa_pontos : null;
    }

    public static function binarioAtivo($distribuidor = array(), $data = '') {
        if (empty($data)) {
            $data = date('Y-m-d');
        }

        $ativo = get_instance()->db->where("db_data <= '{$data} 23:59:59'")
                        ->where('db_distribuidor', $distribuidor->di_id)
                        ->get('registro_distribuidor_binario')->row();

        if (count($ativo) > 0)
            return true;
        else
            return false;
    }

}

function dataMenor($a, $b) {
    return strtotime($a->co_data_compra) < strtotime($b->co_data_compra) ? - 1 : 1;
}
