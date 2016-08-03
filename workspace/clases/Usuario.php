<?php

class Usuario {

    private $email, $clave, $administrador, $activo;

    function __construct($email = null, $clave = null, $administrador = null, $activo = null) {
        $this->email = $email;
        $this->clave = $clave;
        $this->administrador = $administrador;
        $this->activo = $activo;
    }

    function getEmail() {
        return $this->email;
    }

    function getClave() {
        return $this->clave;
    }

    function getAdministrador() {
        return $this->administrador;
    }

    function getActivo() {
        return $this->activo;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setClave($clave) {
        $this->clave = $clave;
    }

    function setAdministrador($administrador) {
        $this->administrador = $administrador;
    }

    function setActivo($activo) {
        $this->activo = $activo;
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