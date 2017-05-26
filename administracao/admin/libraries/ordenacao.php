<?php
class Ordenacao{
    
    static public function urlRequest(){
         
         $url=parse_url($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
         $path=$url['path'];
         $return = array();
           
//         verifica se a queri exite
         if(isset($url['query'])){
             
            $urlExploded =  explode("&",$url['query']);
            $return = array();

            foreach ($urlExploded as $param){
             $explodedPar = explode("=", $param);
             $return[$explodedPar[0]] = $explodedPar[1];
             }
         }
      return array('path'=>$path,'query'=>  array_filter($return));  
    }
    
      /**
     * O tratapamento para o carregamento de pÃ¡gina Ã© 
     * realizado pelo metodo urlLoadPage();
     * @param type $page
     * @param type $url
     * @return type 
     */
    static public function urlCreater($page=null,$url=array()){
       $url=self::urlRequest();
      if(empty($page))
         return $url['path'].(count($url['query'])>0?'?'. http_build_query($url['query']):'');    
     else {
           return 'http://localhost/cadastroPasciente/index.php?r='.$page.(count($url['query'])>0?'&'. http_build_query($url['query']):'');    
     }
    }
    
    /**
     * Ordernar ascendente qualquer campo
     * @param type $campo
     * @param type $action 
     */
   
    static function ordenarAsc($campo='nome')
    {
        $url=self::urlRequest();
           //ordenar os acendentes
        
          if(!isset($url['query'][$campo.'[ordDsc]']))
            $url['query'][$campo]['ordDsc']=false;
          else
            $url['query'][$campo.'[ordDsc]']=false;
                  
            if(!isset($url['query'][$campo.'[ordAsc]']))
              $url['query'][$campo]['ordAsc']=true;
            else
              $url['query'][$campo.'[ordAsc]']=true;
            return urldecode("http://".$url['path'].'?'. http_build_query($url['query']));
     }
     
     /**
     * Ordenar descrecente qualquer campo 
     * @param type $campo
     * @param type $action 
     */
    static public function ordenarDesc($campo='nome')
    {
        $url=self::urlRequest();
       
        //ordenar os acendentes
         if(!isset($url['query'][$campo.'[ordAsc]']))
            $url['query'][$campo]['ordAsc']=false;
          else
            $url['query'][$campo.'[ordAsc]']=false;
                  
            if(!isset($url['query'][$campo.'[ordDsc]']))
              $url['query'][$campo]['ordDsc']=true;
            else
              $url['query'][$campo.'[ordDsc]']=true;
          
//            $this->activeDesc=true;
//            $this->activAsc=false;
            
         return urldecode("http://".$url['path'].'?'.http_build_query($url['query']));
      }
      
     /**
     *Retorna o status do ativo do acendente
     * @return type 
     */ 
      static function activASC($campo=''){
         $url=self::urlRequest();
         
        if(isset($url['query'][$campo.'[ordAsc]']))  
        $activAsc = $url['query'][$campo.'[ordAsc]']==true? true: false;
         else
             $activAsc=false;
        return  $activAsc;
    }
    
     /**
     *Retorna o status do ativo do descendente
     * @return type 
     */ 
      static function activeDesc($campo=''){
          $url=self::urlRequest();
        
        if(isset($url['query'][$campo.'[ordDsc]'])) 
         $activeDesc = $url['query'][$campo.'[ordDsc]']==true?true:false;
         else 
             $activeDesc=false;
        return  $activeDesc;
    }
}

//var_dump(api::ordenarDesc('casa1'));
//var_dump(api::ordenarAsc('casa1'));
//var_dump(api::activASC('casa1'));
//var_dump(api::activeDesc('casa1'));
//var_dump(api::urlRequest());
/*
 *  <a href="<?php echo api::ordenarDesc('contato'); ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em decrescente" ><i class="icon-arrow-down <?php echo $api->activASC('contato')?'icon-white':'';?>"></i><a>
  <a href="<?php echo api::ordenarAsc('contato'); ?>" class="btn pop" data-toggle="popover" data-placement="top" title="" data-original-title="Ordena em ascendente" ><i class="icon-arrow-up <?php echo $api->activeDesc('contato')?'icon-white':'';?>"></i><a>
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
 
