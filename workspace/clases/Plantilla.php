<?php
class Plantilla {
    static function cargarPlantilla($ruta){
        return file_get_contents($ruta);
    }
    static function sustituirDatos($datos,$pagina){        
          foreach ($datos as $key => $value) {
            $pagina = str_replace("{" . $key . "}", $value, $pagina);
        }
        return $pagina;
    }
 
}
