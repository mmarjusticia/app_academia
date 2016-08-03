<?php

class Alumno {

    private $tel, $nombre, $apellido,$clave, $activo, $deuda,$email;

    function __construct($tel=null, $nombre=null, $apellido=null, $clave=null, $activo=1, $deuda=0,$email=null) {
        $this->tel = $tel;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->clave = $clave;
        $this->activo = $activo;
        $this->deuda = $deuda;
        $this->email=$email;
    }
    function getTelefono() {
        return $this->tel;
    }
    function getNombre() {
        return $this->nombre;
    }
    function getApellido() {
        return $this->apellido;
    }
    function getClave(){
        return $this->clave;
    }
    function getActivo(){
        return $this->activo;
    }
    function getDeuda(){
        return $this->deuda;
    }
    function getEmail(){
        return $this->email;
    }
    function setTelefono($tel) {
        $this->tel = $tel;
    }
    function setNombre($nombre) {
        $this->nombre=$nombre;
    }
    function setApellido($apellido) {
        $this->apellido = $apellido;
    }
    function setClave($clave) {
        $this->clave = $clave;
    }
    
    function setActivo($activo) {
        $this->activo = $activo;
    }
    function setDeuda($deuda){
        $this->deuda=$deuda;
    }   
    function setEmail($email){
        $this->email=$email;
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