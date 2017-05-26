<?php

/**
 * Description of VerificacaoBonusPl
 *
 * @author Ronyldo12
 */
class VerificacaoBonusPl {

    private $db;
    private $dia;
    private $distribuidor;

    public function __construct() {
        @header('Content-Type: text/html; charset=utf-8');
        $this->db = get_instance()->db;
    }

    public function setDia($dia) {
        $this->dia = $dia;
    }

    public function setDistribuidor($distribuidor) {
        $this->distribuidor = $distribuidor;
    }

    public function getRegistrosPl($idDistribuidor = null) {
        if ($idDistribuidor == null) {
            $this->db->where('cb_distribuidor', $this->distribuidor->di_id);
        } else {
            $this->db->where('cb_distribuidor', $idDistribuidor);
        }
        return $this->db
                        ->where('cb_data_hora >=', '2014-02-22')
                        ->where('cb_tipo', 106)
                        ->get('conta_bonus')->result();
    }

    public function imprimirTodosRegistrosPl() {

        $pls = $this->getRegistrosPl();

        echo "<table width='800px' cellpadding='5' border='1' cellspacing='0'>";
        foreach ($pls as $pl) {
            echo "<tr>";
            echo "<td>" . $pl->cb_id . "</td>";
            echo "<td>" . $pl->cb_descricao . "</td>";
            echo "<td>" . $this->dataPersonalizada($pl->cb_data_hora) . "</td>";
            echo "<td>" . $this->dataPersonalizada($this->getProvavelDiaFaturado($pl)) . "</td>";
            echo "</tr>";
        }

        if (count($pls) == 0) {
            echo "<tr><td>Nenhum registro encontrado </td></tr>";
        }

        echo "</table>";
    }

    /*
     * Imprime o registros de PL de distribuidores por dia
     */

    public function imprimirRegistrosDeDistribuidores() {
        $distribuidores = $this->db
                        ->select('di_id,di_usuario,di_direita,di_esquerda')
                        ->where('cb_data_hora >=', '2014-02-22')
                        ->join('conta_bonus', 'cb_distribuidor=di_id')
                        ->group_by('di_id')
                        ->get('distribuidores')->result();

        $dataInicio = '2014-02-26';
        $dataFinal = '2014-04-08';

        $dias = $this->getArrayDias($dataInicio, $dataFinal);

        echo "<table width='800px' cellpadding='5' border='1' cellspacing='0'>";

        foreach ($distribuidores as $distribuidor) {
            echo "<tr>";
            echo "<td colspan='4' style='color:blue'><br><br><b>" . $distribuidor->di_usuario . "</b></td>";
            echo "</tr>";
            foreach ($dias as $dia) {
                $plPaga = $this->plFoiPaga($dia, $distribuidor->di_id);

                if ($plPaga) {
                    foreach($plPaga as $k=> $plP){
                    echo "<tr>";
                    echo "<td>" . $this->dataPersonalizada($dia) . "</td>";
                    echo "<td>" . $plP->cb_id . "</td>";
                    echo "<td>" . $plP->cb_descricao . "</td>";
                    echo "<td>" . $plP->cb_credito . "</td>";
                    echo "<td>p-" . count($k+1) . "</td>";
                    echo "</tr>";
                    }
                } else {
                   //new bonus_pl_nova($distribuidor, $dia);
                    echo "<tr>";
                    echo "<td>" . $this->dataPersonalizada($dia) . "</td>";
                    echo "<td>--</td>";
                    echo "<td>--</td>";
                    echo "<td>-,--</td>";
                    echo "<td>-</td>";
                    echo "</tr>";
                }
            }
        }

        echo "</table>";
    }

  

    public function plFoiPaga($data, $idDistribuidor) {
        $pls = $this->getRegistrosPl($idDistribuidor);
        $arrayPlsPagas = array();
        foreach ($pls as $pl) {
            if ($this->getProvavelDiaFaturado($pl) == $data) {
                $arrayPlsPagas[] = $pl;
            }
        }
        if(count($arrayPlsPagas) > 0){
            return $arrayPlsPagas;
        }
        return false;
    }

    /*
     * deve retornar qual o provavel dia de faturamento de uma determinado pagamento de bônus
     * @return datetime
     */

    public function getProvavelDiaFaturado($pl) {

        $data = $this->conteudoTagB($pl->cb_descricao);

        $dataValida = $this->dataValida($data);
        if ($dataValida) {
            return $dataValida;
        }

        return date('Y-m-d', strtotime($pl->cb_data_hora));
    }

    /*
     * Retorna uma data nesse formato: 28 Jan 2014
     */

    public function dataPersonalizada($data) {
        return date('d M Y', strtotime($data));
    }

    public function dataValida($data) {
        list($d, $m, $y) = @explode('/', $data);
        if (checkdate($m, $d, $y)) {
            return $y . '-' . $m . '-' . $d;
        }
        return false;
    }

    public function getArrayDias($inicio, $fim) {
        $dias = array();
        $atual = $inicio;
        while ($atual <= $fim) {
            $dias[] = $atual;
            $timeAtual = strtotime($atual);
            $atual = date('Y-m-d', mktime(0, 0, 0, date('m', $timeAtual), date('d', $timeAtual) + 1, date('Y', $timeAtual)));
        }
        return $dias;
    }

    public function conteudoTagB($html) {
        $ent = $html;
       if( preg_match('/(\d{1,2}\/\d{1,2}\/\d{4})/i', $html, $result) ){
           return $result[1];
       }  
        if (preg_match("{<b>}", $ent)) {
            $a = explode("<b>", $ent);
            if (preg_match("{</b>}", $a[1])) {
                $b = explode("</b>", $a[1]);
                return $b[0];
            }
        } else {
            return trim(str_ireplace('Bônus PL ', '', $ent));
        }
    }


}
