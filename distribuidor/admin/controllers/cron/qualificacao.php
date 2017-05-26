<?php

class qualificacao extends CI_Controller {
  public function index(){
      $quantidade=1;
      $total = $this->db->select('count(*) as total')->get('distribuidores')->row();
      echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>';
      echo "<script>
            function carregar(inicio,fim){
             window.document.writeln('=>');
                    $.ajax({
                     url:'".base_url('index.php/cron/qualificacao/run')."',
                     'data':{'inicio':inicio,'ate':fim},
                    success:function(data){                     
                    window.document.writeln(data);
                          carregar(inicio + {$quantidade},fim);
                      
                      }
                    });
                    }";
                          
           echo  "carregar(0,{$quantidade});";
           echo " </script>";
                  
  }
   
    public function run() {
         CHtml::logexec('Qualificacao_horário:'.date('H:s:i'));
         error_reporting(E_ALL);
         set_time_limit(0);
         ini_set('memory_limit', '128M');
        
        $inicio    = isset($_REQUEST['inicio']) && !empty($_REQUEST['inicio'])?$_REQUEST['inicio']:0;
        $intervalo = isset($_REQUEST['ate']) && !empty($_REQUEST['ate'])?$_REQUEST['ate']:20;
        CHtml::berginTime();
        //TODOS DISTRIBUIDORES QUE TEM PLANO
        $distribuidores = $this->db
                        ->join('registro_planos_distribuidor', 'di_id = ps_distribuidor','left')
                        ->get('distribuidores')->result();

        //ATUALIZA QUALIFICAÇÃO DE 1 POR 1
        $objQualificacoes = new QualificacaoModel();
        foreach ($distribuidores as $distribuidor) {
           echo "{ $distribuidor->di_id}-";
            $objQualificacoes->setDistribuidor($distribuidor);
            $objQualificacoes->executar();
            $objQualificacoes->clear();
        }
        
        echo "|finalizado";
           CHtml::endTime();
        
        
    }

}
