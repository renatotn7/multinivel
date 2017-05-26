<?php

class redeLinear {

    private $redeLinear;
    private $maximoGeracoes;

    static function getRedeLinear($idDistribuidor, $geracoes = 1) {
        $db = get_instance()->db;
        $distribuidor = $db->select('di_id,di_nome,di_usuario,di_data_cad')->where('di_id', $idDistribuidor)->get('distribuidores')->row();

        $redeLinear[0] = array(
            'ids' => array($idDistribuidor),
            'distribuidores' => array($distribuidor)
        );

        for ($geracaoAtual = 1; $geracaoAtual <= $geracoes; $geracaoAtual++) {
            if (count($redeLinear[($geracaoAtual - 1)]['ids']) == 0) {
                unset($redeLinear[($geracaoAtual - 1)]);
                break;
            }
            $distribuidores = $db->where_in('di_ni_patrocinador', $redeLinear[($geracaoAtual - 1)]['ids'])
                            ->select('di_id,di_nome,di_usuario,di_usuario_patrocinador,di_data_cad')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->group_by('di_id')
                            ->get('distribuidores')->result();
            $redeLinear[$geracaoAtual] = array('ids' => self::getArrayIds($distribuidores), 'distribuidores' => $distribuidores);
        }

        return $redeLinear;
    }

    public function getArrayIds($distribuidores) {
        $return = array();
        foreach ($distribuidores as $distribuidor) {
            $return[] = $distribuidor->di_id;
        }
        return $return;
    }

    public function getRede($idPatrocinador, $maxGeracao = 10) {
        $this->redeLinear = array();
        for($geracao = 1; $geracao <= $maxGeracao; $geracao++){
         $this->redeLinear[$geracao] = array();   
        }
        $this->maximoGeracoes = $maxGeracao;

        $geracaoAtual = 1;
        $this->montarRedeLinear($idPatrocinador,$geracaoAtual);
        return $this->redeLinear;
    }

    private function montarRedeLinear($idPatrocinador, $geracaoAtual) {
        if($geracaoAtual > $this->maximoGeracoes){
            return false;
        }
        $gAt = $geracaoAtual;
        $distribuidores = get_instance()->db
                ->select('di_id,di_usuario,di_nome,di_usuario_patrocinador,di_ni_patrocinador')
                ->join('distribuidor_ligacao','di_id=li_id_distribuidor')
                ->where('di_ni_patrocinador', $idPatrocinador)
                ->group_by('di_id')
                ->get('distribuidores')->result();
        
        foreach ($distribuidores as $distribuidor) {
            $this->redeLinear[$gAt][] = $distribuidor;
            $nextGeracao = $geracaoAtual+1;
            $this->montarRedeLinear($distribuidor->di_id, $nextGeracao);
        }
    }

}
