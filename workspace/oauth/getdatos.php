<?php
require '../clases/AutoCarga.php';
$bd=new DataBase();
$gestor=new ManageUsuario($bd);
$sesionaca=new Session();

$email=  Request::post('email');

    
$clave=  Request::post('clave');
$claveR=  Request::post('claveR');
$alias=$email;
$destino=$email;
$claveCifrada=sha1($clave.Constant::SEMILLA);
$sha1=  sha1($destino.Constant::SEMILLA);
$fechaAlta=date('Y-m-d');
$sesionaca->set('_email', $destino);
$sesionaca->set('_clave',$clave);
echo $destino;
echo $clave;
$guardado=$sesion->get("_email");
echo 'lo guardado en sesion es: '.$guardado;

?>