<?php
class ManageAlumno{
    private $bd = null;
    private $tabla = "alumno";
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }
    
    function get($tel){
        //devuelve un objeto de la clase usuario
        $parametros = array();
        $parametros[tel] = $tel;
        $this->bd->select($this->tabla, "*", "tel=:tel", $parametros);
        $fila=$this->bd->getRow();
        $alumno = new Alumno();
        $alumno->set($fila);
        return $alumno;
    }
    
    function delete($tel){
        $parametros = array();
        $parametros['tel'] = $tel;
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    
    function deleteAlumnos($parametros){
        return $this->bd->delete($this->tabla, $parametros);
    }
    
  
    //$tel, $nombre, $apellido,$clave, $activo, $deuda;
    function set(Alumno $alumno){
        //Update de todos los campos menos el tel, el tel se usara como el where para el update numero de filas modificadas
        $parametrosSet=array();
        $parametrosSet['tel']=$alumno->getTelefono();
        $parametrosSet['nombre']=$alumno->getNombre();
        $parametrosSet['apellido']=$alumno->getApellido();
        $parametrosSet['clave']=$alumno->getClave();
        $parametrosSet['activo']=$alumno->getActivo();
        $parametrosSet['deuda']=$alumno->getDeuda();
        $parametrosSet['email']=$alumno->getEmail();
        $parametrosWhere = array();
        $parametrosWhere['tel'] = $alumno->getTelefono();
        return $this->bd->update($this->tabla, $parametrosSet, $parametrosWhere);
        
    }
    
    function insert(Alumno $alumno){
        //Se pasa un objeto usuario y se inserta, se devuelve el email del elemento con el que se ha insertado
        $parametrosSet=array();
        $parametrosSet['tel']=$alumno->getTelefono();
        $parametrosSet['nombre']=$alumno->getNombre();
        $parametrosSet['apellido']=$alumno->getApellido();
        $parametrosSet['clave']=$alumno->getClave();
        $parametrosSet['activo']=$alumno->getActivo();
        $parametrosSet['deuda']=$alumno->getDeuda();
        $parametrosSet['email']=$alumno->getEmail();
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
             $alumno = new Alumno();
             $alumno->set($fila);
             $r[]=$alumno;
         }
         return $r;
    }
        
    function getListWhere( $condicion ="1=1", $parametros = array(), $pagina=1, $orden="", $nrpp=Constant::NRPP){
       
        $ordenPredeterminado = "$orden,tel";
        if($orden==="" || $orden === null){
            $ordenPredeterminado = "apellido";
        }
         $registroInicial = ($pagina-1)*$nrpp;
         $this->bd->select($this->tabla, "*", $condicion, $parametros , $ordenPredeterminado , "$registroInicial, $nrpp");
         $r=array();
         while($fila =$this->bd->getRow()){
             $alumno = new Alumno();
             $alumno->set($fila);
             $r[]=$alumno;
         }
         return $r;
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
    
     function getValuesSelect(){
        $this->bd->query($this->tabla, "ID, Name", array(), "Name");
        $array = array();
        while($fila=$this->bd->getRow()){
            $array[$fila[0]] = $fila[1];
        }
        return $array;
    }
        function getAlumnos($condicion,$proyeccion="*",$tabla1="",$tabla2="matricula",$parametros=array()){
        $tabla1=$this->tabla;
        $this->bd->productoEscalar($proyeccion,$tabla1,$tabla2,$condicion,$parametros);
        $r=array();
        $contador=0;
         while($fila =$this->bd->getRow()){
            
             $alumno=new Alumno();
             $alumno->set($fila);
              $matricula = new Matricula();
             $matricula->set($fila,7);
             $r[$contador]["alumno"] = $alumno;
             $r[$contador]["matricula"] = $matricula;
             $contador++;
             
         }
         return $r;
    }
    
    function count($condicion="1 = 1", $parametros = array()){
        return $this->bd->count($this->tabla, $condicion, $parametros);
    }
}