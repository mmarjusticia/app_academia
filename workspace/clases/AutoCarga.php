<?php
class AutoCarga {
    
    static function cargar($clase){
        $clase = str_replace("\\", "/", $clase);
        $archivo = "../clases/" .$clase . ".php";
         if (file_exists($archivo)) {
              require $archivo;
         }else{
             if(file_exists("clases/" .$clase . ".php")){
                $archivo = "clases/" .$clase . ".php";
                require $archivo;
             }else{
                 //$archivo=$clase.".php";
                 //require $archivo;
             }
         }
   }
}
spl_autoload_register('AutoCarga::cargar'); // Tiene que estar fuera de la clase
        
