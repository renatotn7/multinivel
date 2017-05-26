<?php

class Url extends CI_Controller {
    function redirecionar(){
        error_reporting(0);
        if ($_GET["uri"]!=""){
            $uri = $_GET["uri"];
          
            if(substr($uri,0,2) == '//'){
                $uri = 'http:'.$uri;
            }
            redirect($uri);
        }else{
            redirect(base_url());
        }
        
        exit;
    
        
    }
}

