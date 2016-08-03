<?php
class ManageMatricula{
    private $bd = null;
    private $tabla = "matricula";
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }
    //private $id, $tel, $idClase, $fecha, $activa;
    function get($id){
        //devuelve un objeto de la clase matricula
        $parametros = array();
        $parametros[id] = $id;
        $this->bd->select($this->tabla, "*", "id=:id", $parametros);
        $fila=$this->bd->getRow();
        $matricula= new Matricula();
        $matricula->set($fila);
        return $matricula;
    }
    
    function delete($id){
        $parametros = array();
        $parametros['id'] = $id;
        $this->bd->delete($this->tabla, $parametros);
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    function deleteMatriculas($parametros){
        return $this->bd->delete($this->tabla, $parametros);
    }
     function getListWhere($condicion, $pagina = 1, $nrpp = Constant::NRPP) {
        $registroInicial = ($pagina - 1) * $nrpp;
        $this->bd->select($this->tabla, "*", $condicion, array(), "id, tel", "", $registroInicial, $nrpp);
        $r = array();
        while ($fila = $this->bd->getRow()) {
            $matricula = new Matricula();
            $matricula->set($fila);
            $r[] = $matricula;
        }
        return $r;
    }
  
// $id, $tel, $idClase, $fecha, $activa;
    function set(Matricula $matricula){
        //Update de todos los campos menos el tel, el tel se usara como el where para el update numero de filas modificadas
        $parametrosSet=array();
        $parametrosSet['id']=$matricula->getId();
        $parametrosSet['tel']=$matricula->getTelefono();
        $parametrosSet['idClase']=$matricula->getIdClase();
        $parametrosSet['fecha']=$matricula->getFecha();
        $parametrosSet['activa']=$matricula->getActiva();
        $parametrosWhere = array();
        $parametrosWhere['id'] = $matricula->getId();
        return $this->bd->update($this->tabla, $parametrosSet, $parametrosWhere);
        
    }
    
    function insert(Matricula $matricula){
        //Se pasa un objeto clase y se inserta, se devuelve el id del elemento con el que se ha insertado
        $parametrosSet=array();
       // $parametrosSet['id']=$matricula->getId();
        $parametrosSet['tel']=$matricula->getTelefono();
        $parametrosSet['idClase']=$matricula->getIdClase();
        $parametrosSet['fecha']=$matricula->getFecha();
        $parametrosSet['activa']=$matricula->getActiva();
        return $this->bd->insert($this->tabla, $parametrosSet);
    }
   
    function getAsignaturas($condicion,$proyeccion="materia",$tabla1="",$tabla2="clase",$parametros=array()){
        $tabla1=$this->tabla;
        $this->bd->productoEscalar($proyeccion,$tabla1,$tabla2,$condicion,$parametros);
        $r=array();
        $contador=0;
         while($fila =$this->bd->getRow()){
            
             $matricula=new Matricula();
             $matricula->set($fila);
              $clase = new Clase();
             $clase->set($fila,5);
             $r[$contador]["matricula"] = $matricula;
             $r[$contador]["clase"] = $clase;
             $contador++;
             
         }
         return $r;
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
             $matricula = new Matricula();
             $matricula->set($fila);
             $r[]=$matricula;
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
    
    function count($condicion="1 = 1", $parametros = array()){
        return $this->bd->count($this->tabla, $condicion, $parametros);
    }
}