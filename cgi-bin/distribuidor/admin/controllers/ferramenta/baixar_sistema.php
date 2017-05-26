<?php

class baixar_sistema extends CI_Controller {

    public function index(){
         if($this->uri->segment(3) !=  md5('script top da balada')){
            echo "you are not allowed.";
            return false;
        }
        
    }

    public function run() {
        if($this->uri->segment(4) !=  md5('script top da balada')){
            echo "you are not allowed.";
            return false;
        }
        
        error_reporting(E_ALL);
        ini_set("max_execution_time", 43200);
        $pasta_compactar = PATH_ROOT . '/public_html/';
        $caminhoArquivo_banco = "";

        //Gerando backup do banco de dados.  
        $banco_nome= "/banco-" . date('Y-m-d_H-i-s') . ".sql.gz";
        $caminhoArquivo_banco = $pasta_compactar."".$banco_nome ;
        $comando_script = "mysqldump --opt -Q -u " . APP_USER . " --password=" . APP_SENHA . " " . APP_DATABASE . " | gzip > {$caminhoArquivo_banco}";
        $output = shell_exec($comando_script);


        $destinoArquivo = realpath(dirname(__FILE__)) . '/sistema/';
        if (!file_exists($destinoArquivo)) {
            mkdir($destinoArquivo);
        }

        //Compactando arquivo
        $file_name= "sistema_".date("Y-m-d_H-i-s").".tar.gz";
        $comm_compactar = "tar -zcf {$destinoArquivo}{$file_name} {$pasta_compactar}";
        
        shell_exec("{$comm_compactar}");

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize("{$destinoArquivo}{$file_name}"));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        // Envia o arquivo para o cliente
        readfile("{$destinoArquivo}{$file_name}");
        
        //Destruindo arquivos temp criados
        unlink($caminhoArquivo_banco);
        unlink("{$destinoArquivo}{$file_name}");
    }

}
