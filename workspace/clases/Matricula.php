<?php

class Matricula {

    private $id, $tel, $idClase, $fecha, $activa;

    function __construct($id=null, $tel=null, $idClase=null, $fecha=null, $activa=1) {
        $this->id=$id;
        $this->tel = $tel;
        $this->idClase = $idClase;
        $this->fecha = $fecha;
        $this->activa=$activa;
    }
    function getId(){
        return $this->id;} 
    function getTelefono() {
        return $this->tel;
    }
    function getIdClase() {
        return $this->idClase;
    }
    function getfecha() {
        return $this->fecha;
    }
    
    function getActiva(){
        return $this->activa;
    }
    function setId($id) {
        $this->id = $id;
    }
    function setTelefono($tel) {
        $this->tel = $tel;
    }
    function setidClase($idClase) {
        $this->idClase=$idClase;
    }
    function setFechaMat($fecha) {
        $this->fecha = $fecha;
    }
    function setActiva($acttiva) {
        $this->activa = $activa;
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