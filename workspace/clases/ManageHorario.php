<?php
class ManageHorario {

    private $bd = null;
    private $tabla = "horario";
    
    function __construct(DataBase $bd) {
        $this->bd = $bd;
    }
    
    function get($id){
        //devuelve un objeto de la clase reserva
        $parametros = array();
        $parametros[id] = $id;
        $this->bd->select($this->tabla, "*", "id=:id", $parametros);
        $fila=$this->bd->getRow();
        $horario = new Horario();
        $horario->set($fila);
        return $horario;
    }
    
    function delete($id){
        $parametros = array();
        $parametros['id'] = $id;
        return $this->bd->delete($this->tabla, $parametros);
    }
    
    
    function deleteHorarios($parametros){
        return $this->bd->delete($this->tabla, $parametros);
    }
    
  
    //$id, $diaSem, $hora, $idClase;

    function set(Horario $horario){
        //Update de todos los campos menos el id, el id se usara como el where para el update numero de filas modificadas
        $parametrosSet=array();
        $parametrosSet['id']=$horario->getId();
        $parametrosSet['diaSem']=$horario->getDiaSem();
        $parametrosSet['hora']=$horario->getHora();
        $parametrosSet['idClase']=$horario->getIdClase();
        $parametrosWhere = array();
        $parametrosWhere['id'] = $horario->getId();
        return $this->bd->update($this->tabla, $parametrosSet, $parametrosWhere);
        
    }
    
    function insert(Horario $horario){
        //Se pasa un objeto reserva y se inserta, se devuelve el id del elemento con el que se ha insertado
        $parametrosSet=array();
        $parametrosSet['id']=$horario->getId();
        $parametrosSet['diaSem']=$horario->getDiaSem();
        $parametrosSet['hora']=$horario->getHora();
        $parametrosSet['idClase']=$horario->getIdClase();
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
             $horario = new Horario();
             $horario->set($fila);
             $r[]=$horario;
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
    
    function count($condicion="1 = 1", $parametros = array()){
        return $this->bd->count($this->tabla, $condicion, $parametros);
    }
}