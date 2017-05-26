<?php
class ConfigSingleton
{
    // Guarda uma instância da classe
    private static $configs;
    
    // Um construtor privado; previne a criação direta do objeto
    private function loadConfig() 
    {
        $confs = get_instance()->db->get('config')->result();
        self::$configs = new stdClass();
        foreach($confs as $config){
         self::$configs->{$config->field} =  $config->valor;  
        }
    }

    // O método singleton 
    public static function getValue($field)
    {
        if (!isset(self::$configs)) {
            self::loadConfig();
        }

        if(isset(self::$configs->{$field})){
            return self::$configs->{$field};
        }else{
            return false;
        }
    }
    
    

}