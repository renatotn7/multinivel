<?php

class createXls{
    
    public static function create($contend='',$nome='relatorio_despachante_'){
          $arquivo=$nome.''.date('Y-m-d').'.xls';
                        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                        header ("Cache-Control: no-cache, must-revalidate");
                        header ("Pragma: no-cache");
                        header ("Content-type: application/x-msexcel");
                        header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
                        header ("Content-Description: PHP Generated Data" );
                        echo utf8_decode($contend);
    }
}