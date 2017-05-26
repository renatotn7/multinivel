<?php

/**
 *  excluir_pl_errado
 *
 * @author Ronildo Souza <ronyldo12@hotmail.com>
 */
class excluir_pl_errado extends CI_Controller{
    
    public function pl_errada(){
        
        /*
        $plPaga = $this->db->query("
            SELECT * FROM `registro_bonus_pl` 
            WHERE `rbpl_valor` = 14 AND `rbpl_data_fatura` = '2014-03-16' AND `rbpl_tipo` = 1
            ")->result();
        echo "<p>".count($plPaga)." distribuidores</p>";
        foreach($plPaga as $pl){
            
            
            $this->db->where('rbpl_id',$pl->rbpl_id)->delete('registro_bonus_pl');
            echo "<p>apagando a pl - ".$pl->rbpl_id." D: ".$pl->rbpl_valor."</p>";
            $this->db->where('cb_id',$pl->rbpl_id_conta_bonus)->delete('conta_bonus');
            echo "<p>apagando a pl - ".$pl->rbpl_id_conta_bonus."</p>";
            echo "<p>-------------------------------------------</p>";
            
        }
        */
    }
    
}

?>
