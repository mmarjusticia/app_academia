<?php

class Clase {

    private $id, $materia, $curso, $activo, $tlfProf;

    function __construct($id=null, $materia=null, $curso=null, $activo=1, $tlfProf=null) {
        $this->id = $id;
        $this->materia = $materia;
        $this->curso = $curso;
        $this->activo = $activo;
        $this->tlfProf = $tlfProf;
    }
    function getId(){
        return $this->id;
    }
    function getMateria(){
        return $this->materia;
    }
    function getCurso(){
        return $this->curso;
    }
    function getActivo(){
        return $this->activo;
    }
    function getTlfProf(){
        return $this->tlfProf;
    }
    function setId($id){
        $this->id=$id;
    }
    function setMateria($materia){
        $this->materia=$materia;
    }
    function setCurso($curso){
        $this->curso=$curso;
    }
    function setActivo($activo){
        $this->activo=$activo;
        
    }
    function setTlfProf($tlfProf){
        $this->tlfProf=$tlfProf;
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
    