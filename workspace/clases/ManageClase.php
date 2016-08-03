<?php
class ManageClase{
    private $bd = null;
    private $tabla = "clase";
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }
    
    function get($id){
        //devuelve un objeto de la clase clase
        $parametros = array();
        $parametros[tel] = $tel;
        $this->bd->select($this->tabla, "*", "id=:id", $parametros);
        $fila=$this->bd->getRow();
        $clase = new Clase();
        $clase->set($fila);
        return $clase;
    }
    
    function delete($id){
        $parametros = array();
        $parametros['id'] = $id;
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    
    function deleteClases($parametros){
        return $this->bd->delete($this->tabla, $parametros);
    }
    
  
    // $id, $materia, $curso, $activo, $tlfProf;
    function set(Alumno $alumno){
        //Update de todos los campos menos el tel, el tel se usara como el where para el update numero de filas modificadas
        $parametrosSet=array();
        $parametrosSet['id']=$clase->getId();
        $parametrosSet['materia']=$clase->getMateria();
        $parametrosSet['curso']=$clase->getCurso();
        $parametrosSet['activo']=$clase->getActivo();
        $parametrosSet['tlfProf']=$clase->getTlfProf();
        
        $parametrosWhere = array();
        $parametrosWhere['id'] = $clase->getId();
        return $this->bd->update($this->tabla, $parametrosSet, $parametrosWhere);
        
    }
    
    function insert(Clase $clase){
        //Se pasa un objeto clase y se inserta, se devuelve el id del elemento con el que se ha insertado
        $parametrosSet=array();
        $parametrosSet['materia']=$clase->getMateria();
        $parametrosSet['curso']=$clase->getCurso();
        $parametrosSet['activo']=$clase->getActivo();
        $parametrosSet['tlfProf']=$clase->getTlfProf();
        return $this->bd->insert($this->tabla, $parametrosSet);
    }
    
    function getList($pagina=1, $orden="", $nrpp=Constant::NRPP, $condicion ="1=1", $parametros = array()){
        
        $ordenPredeterminado = "$orden,id";
        if($orden==="" || $orden === null){
            $ordenPredeterminado = "id";
        }
         $registroInicial = ($pagina-1)*$nrpp;
         $this->bd->select($this->tabla, "*", $condicion, $parametros , $ordenPredeterminado , "$registroInicial, $nrpp");
         $r=array();
         while($fila =$this->bd->getRow()){
             $clase = new Clase();
             $clase->set($fila);
             $r[]=$clase;
         }
         return $r;
    }
     function getListWhere($condicion, $pagina = 1, $nrpp = Constant::NRPP) {
        $registroInicial = ($pagina - 1) * $nrpp;
        $this->bd->select($this->tabla, "*", $condicion, array(), "id, materia", "", $registroInicial, $nrpp);
        $r = array();
        while ($fila = $this->bd->getRow()) {
            $clase = new Clase();
            $clase->set($fila);
            $r[] = $clase;
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
        function getValuesSelect2($proyeccion,$orden){
        $this->bd->query($this->tabla,'*', array(), $orden);
        $array = array();
        while($fila=$this->bd->getRow()){
            $array[$fila[0]] = $fila[2];
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