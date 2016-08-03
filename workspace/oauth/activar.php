<?php
require '../clases/AutoCarga.php';
$bd=new DataBase();
$gestor=new ManageUsuario($bd);
$sesion=new Session();
$email=Request::get('email');
$sha1=Request::get('sha1');
$emailCifrado=sha1($email.Constant::SEMILLA);

if($emailCifrado===$sha1){
        $usuario=$gestor->get($email);
        //$usuario->setActivo(1);
        //vamos a modificar al usuario en la tabla usuario de la base de datos
        $alias=$usuario->getAlias();
        $clave=$usuario->getClave();
        $fechaAlta=$usuario->getFechaAlta();
        $activo=1;
        $administrador=$usuario->getAdministrador();
        $trabajador=$usuario->getPersonal();
        
        $usuario2= new Usuario($email,$clave,$alias,$fechaAlta,$activo,$administrador,$trabajador); 
        $gestor->set2($usuario2);
        $sesion->sendRedirect('../php/index.php?registro=si');
    }
else{
        echo 'error, no ha podido hacerse el registro';}

    
    ?>
