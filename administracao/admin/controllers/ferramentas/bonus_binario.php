<?php

/**
 * Description of bonus_binario
 *
 * @author Ronyldo12
 */
class bonus_binario extends CI_Controller {

    public function pontos() {
        autenticar();
        $distribuidores = $this->db
                        ->select('di_usuario,di_id,di_esquerda,di_direita')
                        ->join('registro_bonus_indireto_pagos', 'di_id=pg_distribuidor')
                        ->get('distribuidores')->result();

        $pontos = new PontosBonusBinario();
        echo "<table border=1 width=50% cellpadding=5 cellspacing=0>";
        echo "<tr>";
            echo "<td>Usuario</td>";
            echo "<td>Total Esquerda</td>";
            echo "<td>Total Direita</td>";
            echo "<td>Esquerda Disponivel</td>";
            echo "<td>Direita Disponivel</td>";
            echo "<td>Pontos Pagos</td>";
            echo "<td>A Pagar</td>";
            echo "</tr>";
        foreach ($distribuidores as $distribuidor) {
            $pontos->setDistribuidor($distribuidor);
            $pagos = $pontos->pontosPagos();
            echo "<tr>";
            echo "<td>" . $distribuidor->di_usuario . "</td>";
            echo "<td>" . $pontos->esquerda() . "</td>";
            echo "<td>" . $pontos->direita() . "</td>";
            echo "<td>" . ($pontos->esquerda() - $pagos) . "</td>";
            echo "<td>" . ($pontos->direita() - $pagos) . "</td>";
            echo "<td>" . $pagos . "</td>";
            echo "<td>" . $pontos->pontosAPagar() . "</td>";
            echo "</tr>";
        }
        echo '</table>';
    }

}
