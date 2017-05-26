<?php

class AtivacaoBinario {

    private $pontosEsquerda;
    private $pontosDireita;
    private $compraAtivacaoBinario;
    private $distribuidor;
    private $pontosNecessariosParaAtivarBinario;
    private $db;

    public function __construct() {
        $this->db = get_instance()->db;
        $this->pontosDireita = 0;
        $this->pontosEsquerda = 0;
    }

    public function verificarBinarioAtivo($distribuidor) {
        $this->distribuidor = $distribuidor;
        $this->compraAtivacaoBinario = null;
        $this->pontosDireita = 0;
        $this->pontosEsquerda = 0;
        $this->pontosNecessariosParaAtivarBinario = 0;
        $this->carregarPontosNecessariosParaArivarBinario();

        $comprasDeDiretosEsquerda = $this->db->select("di_id,co_total_pontos,co_data_compra")->join("distribuidores", "co_id_distribuidor=di_id")->join("distribuidor_ligacao", "di_id=li_id_distribuidor")->where("co_pago", 1)->where("co_total_valor <>", 0)->where('li_no', $distribuidor->di_esquerda)->where("di_ni_patrocinador", $distribuidor->di_id)->order_by('co_data_compra', 'ASC')->get("compras")->result();

        $comprasDeDiretosEsquerda = $this->definirPerna($comprasDeDiretosEsquerda, 'esquerda');

        $comprasDeDiretosDireita = $this->db->select("di_id,co_total_pontos,co_data_compra, co_id as direita")->join("distribuidores", "co_id_distribuidor=di_id")->join("distribuidor_ligacao", "di_id=li_id_distribuidor")->where("co_pago", 1)->where("co_total_valor <>", 0)->where('li_no', $distribuidor->di_direita)->where("di_ni_patrocinador", $distribuidor->di_id)->order_by('co_data_compra', 'ASC')->get("compras")->result();

        $comprasDeDiretosDireita = $this->definirPerna($comprasDeDiretosDireita, 'direita');

        $compras = array_merge($comprasDeDiretosEsquerda, $comprasDeDiretosDireita);

        // Ordenar por data da compra
        usort($compras, "dataMenor");

        foreach ($compras as $compra) {
            if ($compra->perna == 'direita') {
                $this->pontosDireita += $compra->co_total_pontos;
            }
            if ($compra->perna == 'esquerda') {
                $this->pontosEsquerda += $compra->co_total_pontos;
            }

            if ($this->verificarSeAtivouAbinario($compra)) {
                break;
            }
        }


        if ($this->compraAtivacaoBinario == null) {
            //echo "<p style='color:f00'>Desativou Distribuidor: ".$this->distribuidor->di_usuario." E: ".$this->pontosEsquerda." D: ".$this->pontosDireita." Plano: ".$this->pontosNecessariosParaAtivarBinario."</p>";
            $this->desativarBinarioDistribuidor();
        } else {
            $this->ativarBinarioDistribuidor();
            //echo "<p style='color:green'>Ativou Distribuidor: ".$this->distribuidor->di_usuario." E: ".$this->pontosEsquerda." D: ".$this->pontosDireita." Plano: ".$this->pontosNecessariosParaAtivarBinario."</p>";
        }
    }

    private function desativarBinarioDistribuidor() {

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

    private function ativarBinarioDistribuidor() {
        // Atualiza o binário do distribuidor
        $ja_lancado_binario = $this->db->where('db_distribuidor', $this->distribuidor->di_id)->get('registro_distribuidor_binario')->row();

        if (count($ja_lancado_binario) == 0) {
            //Gerando os codigo promocionais se essa não gerá os código é provável que o usuário foi 
            //ativado com um dos codigo.
            ComprasModel::codigoPromocionalAtivacaoBinario($this->distribuidor->di_id);
            $this->db->insert('registro_distribuidor_binario', array(
                'db_distribuidor' => $this->distribuidor->di_id,
                'db_data' => $this->compraAtivacaoBinario->co_data_compra
            ));
        } else {

            if (date('Y-m-d', strtotime($ja_lancado_binario->db_data)) != date('Y-m-d', strtotime($this->compraAtivacaoBinario->co_data_compra))) {
                $this->db->where('db_distribuidor', $this->distribuidor->di_id)->update('registro_distribuidor_binario', array(
                    'db_data' => $this->compraAtivacaoBinario->co_data_compra
                ));
            } else {
                //echo "<p>Nao Atualizou data tava certinho: {$this->distribuidor->di_usuario}</p>";
            }
        }
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
