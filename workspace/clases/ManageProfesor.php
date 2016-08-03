<?php
class ManageProfesor{
    private $bd = null;
    private $tabla = "profesor";
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }

    function get($tel){
        //devuelve un objeto de la clase profesor
        $parametros = array();
        $parametros[tel] = $tel;
        $this->bd->select($this->tabla, "*", "tel=:tel", $parametros);
        $fila=$this->bd->getRow();
        $profesor= new Profesor();
        $profesor->set($fila);
        return $profesor;
    }
        function getByName($nombre){
        //devuelve un objeto de la clase profesor
        $parametros = array();
        $parametros[nombre] = $nombre;
        $this->bd->select($this->tabla, "*", "nombre=:nombre", $parametros);
        $fila=$this->bd->getRow();
        $profesor= new Profesor();
        $profesor->set($fila);
        return $profesor;
    }
    
    function delete($tel){
        $parametros = array();
        $parametros['tel'] = $tel;
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    
    function deleteProfesores($parametros){
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    
    function set(Profesor $profesor){
        //Update de todos los campos menos el tel, el tel se usara como el where para el update numero de filas modificadas
        $parametrosSet=array();
        $parametrosSet['tel']=$profesor->getTelefono();
        $parametrosSet['nombre']=$profesor->getNombre();
        $parametrosSet['apellido']=$profesor->getApellido();
        $parametrosSet['clave']=$profesor->getClave();
        $parametrosSet['activo']=$profesor->getActivo();
        $parametrosSet['administrador']=$profesor->getAdministrador();
        $parametrosSet['email']=$profesor->getEmail();
        $parametrosWhere = array();
        $parametrosWhere['tel'] = $profesor->getTelefono();
        return $this->bd->update($this->tabla, $parametrosSet, $parametrosWhere);
        
    }
    //$tel, $nombre, $apellido, $clave, $activo, $administrador;
    function insert(Profesor $profesor){
        //Se pasa un objeto usuario y se inserta, se devuelve el email del elemento con el que se ha insertado
        $parametrosSet=array();
        $parametrosSet['tel']=$profesor->getTelefono();
        $parametrosSet['nombre']=$profesor->getNombre();
        $parametrosSet['apellido']=$profesor->getApellido();
        $parametrosSet['clave']=$profesor->getClave();
        $parametrosSet['activo']=$profesor->getActivo();
        $parametrosSet['administrador']=$profesor->getAdministrador();
        $parametrosSet['email']=$profesor->getEmail();
        return $this->bd->insert($this->tabla, $parametrosSet);
    }
    
    function getList($pagina=1, $orden="", $nrpp=Constant::NRPP, $condicion ="1=1", $parametros = array()){
        
        $ordenPredeterminado = "$orden,tel";
        if($orden==="" || $orden === null){
            $ordenPredeterminado = "apellido";
        }
         $registroInicial = ($pagina-1)*$nrpp;
         $this->bd->select($this->tabla, "*", $condicion, $parametros , $ordenPredeterminado , "$registroInicial, $nrpp");
         $r=array();
         while($fila =$this->bd->getRow()){
             $profesor = new Profesor();
             $profesor->set($fila);
             $r[]=$profesor;
         }
         return $r;
    }
        function getListWhere( $condicion ="1=1", $parametros = array(),$pagina=1, $orden="", $nrpp=Constant::NRPP){
        
        $ordenPredeterminado = "$orden,tel";
        if($orden==="" || $orden === null){
            $ordenPredeterminado = "apellido";
        }
         $registroInicial = ($pagina-1)*$nrpp;
         $this->bd->select($this->tabla, "*", $condicion, $parametros , $ordenPredeterminado , "$registroInicial, $nrpp");
         $r=array();
         while($fila =$this->bd->getRow()){
             $profesor = new Profesor();
             $profesor->set($fila);
             $r[]=$profesor;
         }
         return $r;
    }
        function getValuesSelect($proyeccion,$orden){
        $this->bd->query($this->tabla, $proyeccion, array(), $orden);
        $array = array();
        while($fila=$this->bd->getRow()){
            $array[$fila[0]] = $fila[1];
        }
        return $array;
    }
    function getListJson($pagina=1, $orden="", $nrpp=Constant::NRPP, $condicion ="1=1", $parametros = array()){
        $lista = $this->getList($pagina, $orden, $nrpp, $condicion, $parametros);
        $r = "[ ";
        foreach ($lista as $objeto){
            $r .= $objeto->getJson() . ",";
        }
        $r = substr($r, 0, -1) . "]";
        return $r;
    }
    
  
    function count($condicion="1 = 1", $parametros = array()){
        return $this->bd->count($this->tabla, $condicion, $parametros);
    }
}