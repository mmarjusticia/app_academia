<?php

class Horario {

    private $id, $diaSem, $hora, $idClase;

    function __construct($id=null,$diaSem=null, $hora=null,  $idClase=null) {
        $this->$id=null;
        $this->diaSem=$diaSem;
        $this->hora = $hora;
        $this->idClase = $idClase;}
    function getId(){
        return $this->id;} 
    function getDiaSem() {
        return $this->diaSem;
    }
    function getHora() {
        return $this->hora;
    }
 
    function getIdClase(){
        return $this->idClase;
    }
    function setId($id) {
        $this->id = $id;
    }
    function setDiaSem($diaSem) {
        $this->diaSem = $diaSem;
    }
    function setHora($hora) {
        $this->hora=$hora;
    }

    function setIdClase($idClase) {
        $this->idClase = $idClase;
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