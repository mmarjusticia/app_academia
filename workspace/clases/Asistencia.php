<?php

class Asistencia {

    private $id, $idMat, $fecha,$asiste;

    function __construct($id, $idMat, $fecha, $asiste) {
       $this->id=$id;
        $this->fecha=$fecha;
        $this->asiste=$asiste;
    }
     
    function getId() {
        return $this->id;
    }
    
    function getIdMat(){
        return $this->idMat;
    }
    function getFecha(){
        return $this->fecha;
    }
 
    function getAsiste(){
        return $this->asiste;
    }
    
    function setId($id) {
        $this->id = $id;
    }
  
    function setIdMat($idMat){
        $this->idMat=$idMat;
    }
    function setFecha($fecha){
        $this->fecha=$fecha;
    }
   
    function setAsiste($asiste){
        $this->asiste=$asiste;
    }
    
    public function getJson() {
        $r = '{';
        foreach ($this as $indice => $valor) {
            $r .= '"' . $indice . '":' . json_encode($valor) . ','; //Se codifican algunos caracteres
        }
        $r = substr($r, 0, -1);
        $r .='}';
        return $r;
    }
    function set($valores, $inicio=0){
        $i = 0;
        foreach ($this as $indice => $valor) {
           $this->$indice = $valores[$i+$inicio];
           $i++;
        }
    }
    
    public function __toString() {
        $r ='';
        foreach ($this as $key => $valor) { 
            $r .= "$valor ";
        }
        return $r;
    }
    
    function read() {
        foreach ($this as $key => $valor){
            $this->$key = Request::req($key);
        }
    }
}