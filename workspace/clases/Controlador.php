<?php
require 'clases/AutoCarga.php';
    
class Controlador {
    private $bd,$gestorClase,$gestorHorario,$gestorMatricula,$gestorProfesor,$gestorAlumno,$sesion;
     function __construct() {
        $this->bd=new DataBase();
        $this->gestorClase=new ManageClase($this->bd);
        $this->gestorHorario=new ManageHorario($this->bd);
        $this->gestorMatricula=new ManageMatricula($this->bd);
        $this->gestorProfesor=new ManageProfesor($this->bd);
        $this->gestorAlumno=new ManageAlumno($this->bd);
        $this->sesion=new Session();
     }
    function handle() {
        $op = Request::get("op");
        $metodo = "metodo" . $op;
        if (method_exists($this, $metodo)) { //ucfirst pone la primera en mayuscula
            $this->$metodo();
        } else {
            $this->metodo0();
        }
    }
    function metodo0() {
        $contenidoParticular = Plantilla::cargarPlantilla("templates/_principal.html");
        $pagina = Plantilla::cargarPlantilla("templates/_template2.html");
        $datos = array(
            "contenidoParticular" => $contenidoParticular,
            "relleno"=>"",
            "volver"=>""
        );
        echo Plantilla::sustituirDatos($datos,$pagina);
        }
    function metodoEntrar() {
        $telefono=Request::post('telefono') ;
        $clav=Request::post('clave');
        $claveCifrada=sha1($clav.Constant::SEMILLA);
        $clave=substr($claveCifrada, 0, 6);
        
        
        if($clave!=""&&$telefono!=""){
        $alumno=$this->gestorAlumno->get($telefono);
        $telefonoAlumno=$alumno->getTelefono();
        $profesor=$this->gestorProfesor->get($telefono);
        $telefonoProfesor=$profesor->getTelefono();
        
        if($telefonoAlumno===""&&$telefonoProfesor===""){//si no es ni profesor ni alumno.Mensaje de error
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "El email introducido no pertenece a la base de datos",
                "ruta"=>"../index.php?op=0"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
    }
        else{
            if($telefonoAlumno!=""){//si eres alumno
                if($alumno->getClave()==$clave){//si la clave coincide con su clave
                    $this->sesion;
                    $this->sesion->set("_usuario",$alumno);
                    $contenidoParticular=Plantilla::cargarPlantilla("templates/_zonaPrivadaAlumno.html");
                    $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                    $datos=array(
                    "contenidoParticular"=> $contenidoParticular,
                    "relleno"=>"",
                    "volver"=>""
                    );
                    echo Plantilla::sustituirDatos($datos,$pagina);
                }
                else{
                    $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
                    $datos = array(
                    "mensaje" => "La contraseña es incorrecta",
                    "ruta"=>"../index.php?op=0"
                    );
                    $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
                    $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                    $datos = array(
                    "contenidoParticular" => $contenidoParticular,
                    "relleno"=>"",
                    "volver"=>""
                    );
                    echo Plantilla::sustituirDatos($datos,$pagina);
                }
            }
            else{
                if($profesor->getAdministrador()==1){//si eres administrador
                    if($profesor->getClave()==$clave){//si las clave es correcta
                        $this->sesion;
                        $this->sesion->set("_usuario",$profesor);
                        $contenidoParticular=Plantilla::cargarPlantilla("templates/_zonaPrivadaAdministrador.html");
                        $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                        $datos=array(
                            "contenidoParticular"=> $contenidoParticular,
                            "relleno"=>"",
                            "volver"=>""
                            );
                            
                        echo Plantilla::sustituirDatos($datos,$pagina);}
                    else{//si es administrador pero la contraseña no es correcta
                        $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
                        $datos = array(
                        "mensaje" => "La contraseña es incorrecta",
                        "ruta"=>"../index.php?op=0"
                        );
                        $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
                        $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                        $datos = array(
                        "contenidoParticular" => $contenidoParticular,
                        "relleno"=>"",
                        "volver"=>""
                        );
                        echo Plantilla::sustituirDatos($datos,$pagina);
                        
                    }
                }
                else{//si es profesor
                    if($profesor->getClave()==$clave){//si es profesor y la contraseña es correcra
                        $this->sesion;
                        $this->sesion->set("_usuario",$profesor);
                        $this->sesion->set("_palabra",'token');
                        $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                        $contenidoParticular=Plantilla::cargarPlantilla("templates/_zonaPrivadaProfesor.html");
                        $datos=array(
                        "contenidoParticular"=>$contenidoParticular,
                        "relleno"=>"",
                        "volver"=>"");
                        echo Plantilla::sustituirDatos($datos,$pagina);}
                    else{
                        $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
                        $datos = array(
                        "mensaje" => "La contraseña es incorrecta",
                        "ruta"=>"../index.php?op=0"
                        );
                        $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
                        $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                        $datos = array(
                        "contenidoParticular" => $contenidoParticular,
                        "relleno"=>"",
                        "volver"=>""
                        );
                        echo Plantilla::sustituirDatos($datos,$pagina);
                    }

                }
                    
                }
            }}
            
            else{
                
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "Por favor rellene todos los datos",
                "ruta"=>"../index.php?op=0"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
        
        
            }
        }
        
        function metodoViewAsignaturas(){
            $usuario=$this->sesion->get("_usuario");
            if($usuario->getTelefono()==""){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $tel=$usuario->getTelefono();
            $condicion="matricula.tel='".$tel."' and matricula.idClase=clase.id";
            $proyeccion="*";
            $listaClases=$this->gestorMatricula->getAsignaturas($condicion,$proyeccion);
            $relleno=Plantilla::cargarPlantilla('templates/_asgnaturaRelleno3.html');
            $plantillaListaAsignaturas =Plantilla::cargarPlantilla("templates/_asignaturas.html");
            $asignaturas = "";
            foreach ($listaClases as $key => $value) {
                    $asignaturai = str_replace("{contenido}", $value["clase"]->getMateria(), $plantillaListaAsignaturas);
                    $asignaturai = str_replace("{telPro}", $value["clase"]->getTlfProf(), $asignaturai);
                    $asignaturai = str_replace("{curso}", $value["clase"]->getCurso(), $asignaturai);
                    $asignaturas .= $asignaturai;
                      }
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $asignaturas,
                "relleno"=>$relleno,
                "volver"=>'<a href="index.php?op=VolverZonaUsuario"><li class="volver">'.Volver.'</li></a>'
                
                );
            echo Plantilla::sustituirDatos($datos,$pagina);}
            }
          
        function metodoVerContenido(){
            $usuario=$this->sesion->get("_usuario");
            if($usuario->getTelefono()==""){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $contenidoParticular=Plantilla::cargarPlantilla("templates/_contenidoAlumno.html");
            $string="";
            $telPro=Request::get("telPro");
            $materia=Request::get("materia");
            $curso=Request::get("curso");
            $relleno=Plantilla::cargarPlantilla('templates/_contenidoRelleno.html');
            $directorio="profesor/$telPro/$materia/$curso";
            
            if(file_exists($directorio)){
                $carpeta=  opendir($directorio);
                $r="";
                while($archivo=  readdir($carpeta)){
                    if(!is_dir($archivo)){
                        $string=$string.'<a class="fondoContenido" href="?op=Descarga&telPro='.$telPro.'&materia='.$materia.'&curso='.$curso.'&archivo='.$archivo.'"><li>'.$archivo.'</li></a>';
                    }
                }
                $r=str_replace("{enlace}",$string,$contenidoParticular);
            }
           
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $r ,
                "relleno"=>$relleno,
                "volver"=>'<a class="volver" href="index.php?op=VolverZonaUsuario"><li class="volver">'.Volver.'</li></a>');
            echo Plantilla::sustituirDatos($datos,$pagina);
            }}
           /*
                $contenidoParticular=Plantilla::cargarPlantilla('templates/_descarga.html');
                $datos=array(
                "contenidoParticular"=>$contenidoParticular);
                $pagina=Plantilla::cargarPlantilla('templates/_template.html');
                echo Plantilla::sustituirDatos($datos,$pagina);
                header("Content-disposition: attachment; filename=$archivo");
                header("Content-type: application/pdf");
                readfile("profesor/$telPro/$materia/$curso/$archivo");
                if (!isset($_GET['archivo']) || empty($_GET['archivo'])) {
                 exit();
                }
                $root = "profesor/$telPro/$materia/$curso/";
                $file = basename($_GET['archivo']);
                $path = $root.$file;
                $type = 'pdf';
  
                if (is_file($path)) {
                $size = filesize($path);
                if (function_exists('mime_content_type')) {
                $type = mime_content_type($path);
                } else if (function_exists('finfo_file')) {
                        $info = finfo_open(FILEINFO_MIME);
                        $type = finfo_file($info, $path);
                        finfo_close($info);
                        }
                if ($type == '') {
                        $type = "application/force-download";
                }
                // Definir headers
                header("Content-Type: $type");
                header("Content-Disposition: attachment; filename=$file");
                header("Content-Transfer-Encoding: binary");
                header("Content-Length: " . $size);
                // Descargar archivo
                readfile($path);
                } else {
                 die("El archivo no existe.");
                }
  
*///-------------------------------------------Si la variable archivo que pasamos por URL no esta 
//establecida acabamos la ejecucion del script.

//Utilizamos basename por seguridad, devuelve el 
//nombre del archivo eliminando cualquier ruta.
        function metodoDescarga(){
            $usuario=$this->sesion->get("_usuario");
            
            if($usuario==null){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
                $archivo=Request::get('archivo');
                $telPro=Request::get('telPro');
                $materia=Request::get('materia');
                $curso=Request::get('curso'); 
                $archivo =Request::get('archivo');
                $ruta="profesor/$telPro/$materia/$curso/$archivo";
              
                if (is_file($ruta))
                    {   header('Content-Type: application/force-download');
                        header('Content-Disposition: attachment; filename='.$archivo);
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: '.filesize($ruta));
                        readfile($ruta);
                        $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
                        $datos = array(
                                "mensaje" => "Archivo descargado con éxito",
                                "ruta"=>"../index.php?op=0"
                        );
                        $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
                        $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                        $datos = array(
                        "contenidoParticular" => $contenidoParticular,
                        "relleno"=>"",
                         "volver"=>'<a href="index.php?op=VolverZonaUsuario"><li class="volver">'.Volver.'</li></a>'
                            );
                        echo Plantilla::sustituirDatos($datos,$pagina);
                        
                    }
                else
                    exit();
        }}
            
        function metodoViewDudas(){
             $usuario=$this->sesion->get("_usuario");
            if($usuario==null){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $usuario=$this->sesion->get("_usuario");
            $tel=$usuario->getTelefono();
            $condicion="matricula.tel='".$tel."' and matricula.idClase=clase.id";
            $proyeccion="*";
            $listaClases=$this->gestorMatricula->getAsignaturas($condicion,$proyeccion);
            $plantillaListaAsignaturas =Plantilla::cargarPlantilla("templates/_zonaDudas.html");
            $asignaturas = "";
            foreach ($listaClases as $key => $value) {
                 
                    $asignaturai = str_replace("{contenido}", $value["clase"]->getMateria(), $plantillaListaAsignaturas);
                    $asignaturai = str_replace("{telPro}", $value["clase"]->getTlfProf(), $asignaturai);
                    $asignaturai = str_replace("{curso}", $value["clase"]->getCurso(), $asignaturai);
                    $asignaturas .= $asignaturai;
          
            }
            $pagina = Plantilla::cargarPlantilla("templates/_zonaDudas1.html");
            $datos = array(
                "contenidoParticular" => $asignaturas,
                "relleno"=>"",
                "volver"=>'<a href="index.php?op=VolverZonaUsuario"><li class="volver">'.Volver.'</li></a>'
                );
            echo Plantilla::sustituirDatos($datos,$pagina);}
        }
            
    function metodoAccederFormDuda(){
        $usuario=$this->sesion->get("_usuario");
        if($usuario==null){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $telPro=Request::get("telPro");
        $sesion=new Session();
        $sesion->set("_telPro",$telPro);
        $contenidoParticular=Plantilla::cargarPlantilla("templates/_formunlarioDudas.html");
        $pagina=Plantilla::cargarPlantilla("templates/_template.html");
        $datos= array(
                 "contenidoParticular"=>$contenidoParticular,
                 "relleno"=>"",
                 "volver"=>'<a href="index.php?op=VolverZonaUsuario"><li class="volver">'.Volver.'</li></a>');
             echo Plantilla::sustituirDatos($datos,$pagina);}
         }  
         
    function metodophpEnviarduda(){
        $usuario=$this->sesion->get("_usuario");
        if($usuario==null){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $sesion=new Session();
            $usuario=$sesion->get("_usuario");
            $telPro=$sesion->get("_telPro");
            $profesor=$this->gestorProfesor->get($telPro);
            $email=$usuario->getEmail();
            $origen='mmarjusticia@gmail.com';
            $email2=$profesor->getEmail();
            $destino=''.$email2.'';
            $alias=$destino;
            $asunto='Tiene una duda de '.$email.'';
            $mensaje=Request::post("dudas");
            require_once 'clases/Google/autoload.php';
            require_once 'clases/class.phpmailer.php';  //las últimas versiones también vienen con autoload
            $cliente = new Google_Client();
            $cliente->setApplicationName('enviarduda');
            $cliente->setClientId("526797281130-1nmul2s6boved7ctbqa0f4s768ner78c.apps.googleusercontent.com");
            $cliente->setClientSecret("hTHy64Z7J-9LwqgGBUo4jQl_");
            $cliente->setRedirectUri('https://app-clasesparticulares-mmarjusticia.c9users.io/oauth/guardar.php');
            $cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
            $cliente->setAccessToken(file_get_contents('oauth/token.conf'));
        if ($cliente->getAccessToken()) {
             $service = new Google_Service_Gmail($cliente);
            try {
               $mail = new PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->From = $origen;
                $mail->FromName = $alias;
                $mail->AddAddress($destino);
                $mail->AddReplyTo($origen, $alias);
                $mail->Subject = $asunto;
                $mail->Body = $mensaje;
                $mail->preSend();
                $mime = $mail->getSentMIMEMessage();
                $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
                $mensaje = new Google_Service_Gmail_Message();
                $mensaje->setRaw($mime);
                $service->users_messages->send('me', $mensaje);
               
                } catch (Exception $e) {
                        print($e->getMessage());
                } 
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
            "mensaje" => "El email ha sido enviado correctamente",
                "ruta"=>"../index.php?op=volverZonaUsuario"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
            }
        else{
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
            "mensaje" => "Ha habido un error en el envio",
                "ruta"=>"../index.php?op=volverZonaUsuario"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
    
            }

        }}
    
    function metodoViewAltaAlumno(){
        $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $contenidoParticular = Plantilla::cargarPlantilla("templates/_ViewAltaAlumno.html");
        $pagina = Plantilla::cargarPlantilla("templates/_template.html");
        $datos = array(
            "contenidoParticular" => $contenidoParticular,
            "relleno"=>"",
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
        );
        echo Plantilla::sustituirDatos($datos,$pagina);}
    }
    function metodophpAltaAlumno(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $pagina=Plantilla::cargarPlantilla('templates/_template.html');
        $mensaje=Plantilla::cargarPlantilla('templates/_mensaje.html');
        $nombre=Request::post('nombre');
        $apellido=Request::post('apellido');
        $telefono=Request::post('telefono');
        $email=Request::post('email');
        $condicion="tel='$telefono'";
        $listaAlumnosPorTlf=$this->gestorAlumno->getListWhere($condicion);
        if(count($listaAlumnosPorTlf)<1){
            $a=substr($nombre, 0, 2);
            $b=substr($apellido,0,2);
            $c=substr($telefono,7,2);
            $clav=$a.$b.$c;
            $claveCifrada=sha1($clav.Constant::SEMILLA);
            $clave=substr($claveCifrada, 0, 6);
            $condicion="clave='$clave'";
            $listaAlumnos=$this->gestorAlumno->getListWhere($condicion);
            $alumno=new Alumno($telefono,$nombre,$apellido,$clave,1,0,$email);
            $this->gestorAlumno->insert($alumno);
            $datos = array(
                "mensaje" => "El alumno ha sido introducido exitosamente y su contraseña es $clav",
                "ruta"=>"../index.php?op=VolverZonaAdmin"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$mensaje);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
        }
            else{
                 $datos = array(
                "mensaje" => "El teléfono introducido ya pertenece a la base de datos",
                "ruta"=>"../index.php?op=ViewAltaAlumno"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$mensaje);
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
                echo 'el telefono ya existe';
            }}
        }
        function metodoVolverZonaAdmin(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $contenidoParticular=Plantilla::cargarPlantilla("templates/_zonaPrivadaAdministrador.html");
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos=array(
                "contenidoParticular"=> $contenidoParticular,
                "relleno"=>"",
                "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
                 echo Plantilla::sustituirDatos($datos,$pagina);}
        }
        function metodoVolverZonaProfesor(){
            $contenidoParticular=Plantilla::cargarPlantilla("templates/_zonaPrivadaProfesor.html");
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos=array(
                "contenidoParticular"=> $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                 echo Plantilla::sustituirDatos($datos,$pagina);
        }
        function metodoVolverZonaUsuario(){
            $contenidoParticular=Plantilla::cargarPlantilla("templates/_zonaPrivadaAlumno.html");
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos=array(
                "contenidoParticular"=> $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
            echo Plantilla::sustituirDatos($datos,$pagina);
        }
        function metodoViewAltaProfesor(){
             $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_ViewAltaProfesor.html");
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
            );
            echo Plantilla::sustituirDatos($datos,$pagina);}
        }
        function metodophpAltaProfesor(){
             $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $pagina=Plantilla::cargarPlantilla('templates/_template.html');
            $mensaje=Plantilla::cargarPlantilla('templates/_mensaje.html');
            $nombre=Request::post('nombre');
            $apellido=Request::post('apellido');
            $telefono=Request::post('telefono');
            $email=Request::post('email');
            $condicion="tel='$telefono'";
            //$tel=null, $nombre=null, $apellido=null, $clave=null, $activo=1, $administrador=0,$email=null
            $listaAlumnosPorTlf=$this->gestorProfesor->getListWhere($condicion);
            if(count($listaAlumnosPorTlf)<1){
                $a=substr($nombre, 0, 2);
                $b=substr($apellido,0,2);
                $c=substr($telefono,7,2);
                $clav=$a.$b.$c;
                $claveCifrada=sha1($clav.Constant::SEMILLA);
                 $clave=substr($claveCifrada, 0, 6);
                $profesor=new Profesor($telefono,$nombre,$apellido,$clave,1,0,$email);
                $this->gestorProfesor->insert($profesor);
                $datos = array(
                    "mensaje" => "El profesor ha sido introducido exitosamente",
                    "ruta"=>"../index.php?op=VolverZonaAdmin"
                    );
                $contenidoParticular=Plantilla::sustituirDatos($datos,$mensaje);
                $pagina=Plantilla::cargarPlantilla("templates/_template.html");
                $datos = array(
                    "contenidoParticular" => $contenidoParticular,
                    "relleno"=>"",
                    "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                    );
                echo Plantilla::sustituirDatos($datos,$pagina);
            }
            else{
                $datos = array(
                    "mensaje" => "El teléfono introducido ya pertenece a la base de datos",
                    "ruta"=>"../index.php?op=ViewAltaProfesor"
                    );
                $contenidoParticular=Plantilla::sustituirDatos($datos,$mensaje);
                $datos = array(
                    "contenidoParticular" => $contenidoParticular,
                    "relleno"=>"",
                    "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                 );
                echo Plantilla::sustituirDatos($datos,$pagina);}
            }
        }
    function metodoViewFormAsignaturas(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $contenidoParticular=Plantilla::cargarPlantilla("templates/_ViewAltaAsignatura.html");
        $r= Util::getSelect("profeSeleccionado", $this->gestorProfesor->getValuesSelect("*","nombre"), "todos", false);
        
        $datos = array(
                    "select" => $r);
        $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $datos2=array(
            "contenidoParticular"=>$contenidoParticular,
            "relleno"=>"",
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos2,$fila);}
    }
    function metodoMatricularAlumno(){
             $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $relleno=Plantilla::cargarPlantilla('templates/_asgnaturaRelleno.html');
            $plantillaListaAlumnos =Plantilla::cargarPlantilla("templates/_ViewMatAlum.html");
            $condicion='activo=1';
            $listaAlumnos=$this->gestorAlumno->getListWhere($condicion);
            $alumnos="";
            foreach ($listaAlumnos as $key => $value) {
                $alumnoi=str_replace("{contenido}", $value->getNombre().' ' .$value->getApellido(), $plantillaListaAlumnos);
                $alumnoi=str_replace("{telefono}",$value->getTelefono(),$alumnoi);
                $alumnos.=$alumnoi;
            }
           
            
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $alumnos,
                "relleno"=>$relleno,
                "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                );
            echo Plantilla::sustituirDatos($datos,$pagina);}
        
    }
    function metodoViewOfertaAsignaturas(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $telefono=Request::get('telefono');
        $sesion=new Session();
        $sesion->set('_telalumnoMat',$telefono);
        $condicion='activo=1';
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_viewOferta.html');
        $listaClases=$this->gestorClase->getListWhere($condicion);
        $clases="";
        foreach ($listaClases as $key => $value) {
                $clasei=str_replace("{enlace}",'<a class="st" href="?op=Prueba&id='. $value->getId().'&tel='.$value->getTlfProf().'"><li class="oferta">'.$value->getMateria().' '.$value->getCurso().' '.$this->gestorProfesor->get($value->getTlfProf())->getNombre().'</li></a>',$contenidoParticular);
                $clases.=$clasei;
            }
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $relleno=Plantilla::cargarPlantilla('templates/_ofertaRelleno.html');
        $datos= array(
            "contenidoParticular"=>$clases,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}
    }
        
    
    function metodophpAltaAsignatura(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $pagina=Plantilla::cargarPlantilla('templates/_template.html');
        $mensaje=Plantilla::cargarPlantilla('templates/_mensaje.html');
        // private $id, $materia, $curso, $activo, $tlfProf;
        $materia=Request::post('materia');
        $curso=Request::post('curso');
        $telefono=Request::post('profeSeleccionado');
        $clase=new Clase($id,$materia,$curso,1,$telefono);
        $condicion="materia='".$materia."' and curso='".$curso."'and tlfProf='".$telefono."'";
        $listaClases=$this->gestorClase->getListWhere($condicion);
        if(count($listaClases)<1){
            $this->gestorClase->insert($clase);
            $datos = array(
                    "mensaje" => "La clase ha sido dada de alta exitosamente",
                    "ruta"=>"../index.php?op=VolverZonaAdmin"
                    );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$mensaje);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                    "contenidoParticular" => $contenidoParticular,
                    "relleno"=>"",
                    "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                    );
            echo Plantilla::sustituirDatos($datos,$pagina);
        }
        else{
           $datos = array(
                    "mensaje" => "Error. La clase que pretende dar de alta ya existe",
                    "ruta"=>"../index.php?op=VolverZonaAdmin"
                    );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$mensaje);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                    "contenidoParticular" => $contenidoParticular,
                    "relleno"=>"",
                    "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                    
                    );
            echo Plantilla::sustituirDatos($datos,$pagina);
        }}
        
    }
    function metodoListarAlumno(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $condicion='activo=1';
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_verAlumnos.html');
        $listaAlumnos=$this->gestorAlumno->getListWhere($condicion);
        $alumnos="";
        foreach ($listaAlumnos as $key => $value) {
                $alumnoi=str_replace("{alumno}", $value->getNombre().' '.$value-> getApellido(), $contenidoParticular);
                $alumnos.=$alumnoi;
            }
        $relleno=Plantilla::cargarPlantilla('templates/_verListado.html');
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $datos= array(
            "contenidoParticular"=>$alumnos,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}
       
    }
    function metodoListarClaveAlumno(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $condicion='activo=1';
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_verClaveAlumnos.html');
        $listaAlumnos=$this->gestorAlumno->getListWhere($condicion);
        $alumnos="";
        foreach ($listaAlumnos as $key => $value) {
                $alumnoi=str_replace("{alumno}", $value->getNombre().' '.$value-> getApellido().' CLAVE -->'.$value->getClave(), $contenidoParticular);
                $alumnos.=$alumnoi;
            }
        $relleno=Plantilla::cargarPlantilla('templates/_verListado.html');
        
        
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $datos= array(
            "contenidoParticular"=>$alumnos,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}
    }
    function metodoListarProfesor(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $condicion='activo=1';
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_verProfesores.html');
        $listaProfesores=$this->gestorProfesor->getListWhere($condicion);
        $profesores="";
        foreach ($listaProfesores as $key => $value) {
                $profesori=str_replace("{profesor}", $value->getNombre().' '.$value->getApellido().' ---> Telefono: '.$value->getTelefono(), $contenidoParticular);
                $profesores.=$profesori;
            }
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $relleno=Plantilla::cargarPlantilla('templates/_verListadoProf.html');
        $datos= array(
            "contenidoParticular"=>$profesores,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}
       
    }

    function metodoListarAsignaturas(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $condicion='activo=1';
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_verClases.html');
        $listaClases=$this->gestorClase->getListWhere($condicion);
        $clases="";
        foreach ($listaClases as $key => $value) {
                $clasei=str_replace("{clase}", $value->getMateria().' '.$value->getCurso(), $contenidoParticular);
                $clases.=$clasei;
            }
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $relleno=Plantilla::cargarPlantilla('templates/_verListadoClases3.html');
        $datos= array(
            "contenidoParticular"=>$clases,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}
    }
        function metodoBajaAlumno(){
             $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
           
            $relleno=Plantilla::cargarPlantilla('templates/_rellenoBaja.html');
            $plantillaListaAlumnos =Plantilla::cargarPlantilla("templates/_bajaAlumnos.html");
            $condicion='activo=1';
            $listaAlumnos=$this->gestorAlumno->getListWhere($condicion);
            $alumnos="";
            foreach ($listaAlumnos as $key => $value) {
                $alumnoi=str_replace("{contenido}", $value->getNombre(), $plantillaListaAlumnos);
                $alumnoi=str_replace("{telefono}",$value->getTelefono(),$alumnoi);
                $alumnos.=$alumnoi;
            }
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $alumnos,
                "relleno"=>$relleno,
                "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                
                );
            echo Plantilla::sustituirDatos($datos,$pagina);}}
        function metodoBajaProfesor(){
             $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $relleno=Plantilla::cargarPlantilla('templates/_rellenoBaja.html');
            $plantillaListaProfesores =Plantilla::cargarPlantilla("templates/_bajaProfesores.html");
            $condicion='activo=1';
            $listaAProfesores=$this->gestorProfesor->getListWhere($condicion);
            $profesores="";
            foreach ($listaAProfesores as $key => $value) {
                $profesori=str_replace("{contenido}", $value->getNombre(), $plantillaListaProfesores);
                $profesori=str_replace("{telefono}",$value->getTelefono(),$profesori);
                $profesores.=$profesori;
            }
           
            
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $profesores,
                "relleno"=>$relleno,
                "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                
                );
            echo Plantilla::sustituirDatos($datos,$pagina);}}
        
    
        function metodoViewBajaAsignatura(){
            
             $usuario=$this->sesion->get("_usuario");
        if($usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
            $tel=Request::get('telefono');
            $sesion=new Session();
            $sesion->set('_telAlumno',$tel);
            $condicion="matricula.tel='".$tel."' and matricula.idClase=clase.id";
            $proyeccion="*";
            $listaClases=$this->gestorMatricula->getAsignaturas($condicion,$proyeccion);
            $relleno=Plantilla::cargarPlantilla('templates/_asgnaturaRelleno.html');
            $plantillaListaAsignaturas =Plantilla::cargarPlantilla("templates/_asignaturas2.html");
            $asignaturas = "";
            foreach ($listaClases as $key => $value) {
                 
                    $asignaturai = str_replace("{contenido}", $value["clase"]->getMateria(), $plantillaListaAsignaturas);
                    $asignaturai = str_replace("{idMatricula}", $value["matricula"]->getId(), $asignaturai);
                    $asignaturai = str_replace("{curso}", $value["clase"]->getCurso(), $asignaturai);
                    $asignaturas .= $asignaturai;
            }
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $asignaturas,
                "relleno"=>$relleno,
                "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                );
            echo Plantilla::sustituirDatos($datos,$pagina);}
        }
    function metodophpBajaProfesor(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $telefono=Request::get('telefono');
        $this->gestorProfesor->delete($telefono);
        $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "El profesor ha sido borrado con éxito",
                "ruta"=>"../index.php?op=VolverZonaAdmin"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);}
    }
    function metodoPhpBajaAlumno(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null||$usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $telefono=Request::get('telefono');
        $this->gestorAlumno->delete($telefono);
        $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "El alumno ha sido borrado con éxito",
                "ruta"=>"../index.php?op=VolverZonaAdmin"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);}
    }
        
    
    function metodoPhpBajaAsignatura(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $idMatricula=Request::get('idMatricula');
        echo $idMatricula;
        $this->gestorMatricula->delete($idMatricula);}
    }
    function metodophpMatricular(){
        //$idClase=Request::get("id");
        //$tel=Request::get("tel");
        //$fecha=time();
        //$id=5;
        //$activa=1;
        //$fechaA=getDate($fecha);
        //$mes=$fechaA['mon'];
        //if(strlen($mes)==1){
         //   $mes="0".$mes;
        //}
        //$f=$fechaA['year']."-".$mes."-".$fechaA['mday'];
        //echo 'idClase'.$idClase.'<br>';
        //echo 'tel'.$tel.'<br>';
        //$matricula=new Matricula($id,$tel,$idClase,$f,$activa);
        //$this->gestorMatricula->insert($matricula);
       
    
}
    function metodoPrueba(){
         $usuario=$this->sesion->get("_usuario");
         echo $usuario->getAdministrador();
        if($usuario->getAdministrador()!=1){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $sesion=new Session;
        $idClase=Request::get("id");
        $tel=$sesion->get('_telalumnoMat');
        
        $condicion="idClase='".$idClase."' and tel='".$tel."'";
        $listaMatriculas=$this->gestorMatricula->getListWhere($condicion);
        if(count($listaMatriculas)<1){
            $fecha=time();
            $fechaA=getDate($fecha);
            $mes=$fechaA['mon'];
            if(strlen($mes)==1){
                $mes="0".$mes;
            }
            $f=$fechaA['year']."-".$mes."-".$fechaA['mday'];
            $matricula=new Matricula($id,$tel,$idClase,$f,1);
            $this->gestorMatricula->insert($matricula);
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "El alumno ha sido matriculado con éxito",
                "ruta"=>"../index.php?op=VolverZonaAdmin"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
        }
        else{
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "El alumno ya está matriculado en esta asignatura",
                "ruta"=>"../index.php?op=VolverZonaAdmin"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);}
        }
    }
    function metodocerrarSesion(){
         $usuario=$this->sesion->get("_usuario");
        if($usuario==null){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
        $sesion=new Session;
        $sesion->destroy();
          $contenidoParticular = Plantilla::cargarPlantilla("templates/_principal.html");
        $pagina = Plantilla::cargarPlantilla("templates/_template2.html");
        $datos = array(
            "contenidoParticular" => $contenidoParticular,
            "relleno"=>"",
            "volver"=>""
        );
        echo Plantilla::sustituirDatos($datos,$pagina);}
    }
    /*------------profesor-------------*/
     function metodoViewAsignaturasProfesor(){
        $profesor=$this->sesion->get('_usuario');
        $palabra=$this->sesion->get('_palabra');
         if($profesor==null){echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}else{
             if($palabra=='token'){
        $telefono=$profesor->getTelefono();
        $condicion="tlfProf='".$telefono."'";
        $listaClases=$this->gestorClase->getListWhere($condicion);
        $clases="";
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_verClases.html');
        foreach ($listaClases as $key => $value) {
                $clasei=str_replace("{clase}", $value->getMateria().' '.$value->getCurso(), $contenidoParticular);
                $clases.=$clasei;
            }
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $relleno=Plantilla::cargarPlantilla('templates/_verListadoClases3.html');
        $datos= array(
            "contenidoParticular"=>$clases,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaProfesor"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}else{echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}}
     }
     function metodoViewAlumnosPorAsignatura(){
   $profesor=$this->sesion->get('_usuario');
        $palabra=$this->sesion->get('_palabra');
        if($profesor==null){
            echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
        else{
             if($palabra=='token'){
        $telefono=$profesor->getTelefono();
        $condicion="tlfProf='".$telefono."'";
        $listaClases=$this->gestorClase->getListWhere($condicion);
        $clases="";
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_verClasesEnlace.html');
        foreach ($listaClases as $key => $value) {
                $clasei=str_replace("{clase}", $value->getMateria().' '.$value->getCurso(), $contenidoParticular);
                $clasei=str_replace("{enlace}",'<a class="st" href="?op=VerAlumnosDeAsignatura&id='. $value->getId().'&tel='.$value->getTlfProf().'"><li class="oferta2">'.$value->getMateria().' '.$value->getCurso().'</li></a>',$clasei);
                $clases.=$clasei;
            }
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $relleno=Plantilla::cargarPlantilla('templates/_verListadoClases2.html');
        $datos= array(
            "contenidoParticular"=>$clases,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaProfesor"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}
        else{'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
     
         
     }}
     function metodoVerAlumnosDeAsignatura(){
         $profesor=$this->sesion->get('_usuario');
        $palabra=$this->sesion->get('_palabra');
         if($profesor==null)
         {echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
        else{
             if($palabra=='token'){
         $idClase=Request::get('id');
         $condicion="matricula.tel=alumno.tel and matricula.idClase='".$idClase."'";
            $proyeccion="*";
            $listaClases=$this->gestorAlumno->getAlumnos($condicion,$proyeccion);
            $relleno=Plantilla::cargarPlantilla('templates/_asgnaturaRelleno2.html');
            $plantillaListaAsignaturas =Plantilla::cargarPlantilla("templates/_asignaturas3.html");
            $asignaturas = "";
            foreach ($listaClases as $key => $value) {
                 
                    $asignaturai = str_replace("{contenido}", $value["alumno"]->getNombre(), $plantillaListaAsignaturas);
                    $asignaturai = str_replace("{curso}", $value["alumno"]->getTelefono(), $asignaturai);
                    $asignaturai = str_replace("{email}", $value["alumno"]->getEmail(), $asignaturai);
                    $asignaturas .= $asignaturai;
            }
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $asignaturas,
                "relleno"=>$relleno,
                "volver"=>'<a href="index.php?op=VolverZonaProfesor"><li class="volver">'.Volver.'</li></a>'
                );
            echo Plantilla::sustituirDatos($datos,$pagina);}
            else{echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
     }}
       function metodoPhpSubirArchivos(){
         
         $profesor=$this->sesion->get('_usuario');
        $palabra=$this->sesion->get('_palabra');
         if($profesor==null)
         {echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
        else{
             if($palabra=='token'){
         
         $nombre=$profesor->getNombre();
         $telefono=$profesor->getTelefono();
         $materia=Request::post('materia');
         $curso=Request::post('curso');
         $subir= new FileUpload("archivo");
         $categoriaRuta='profesor/'.$telefono.'/'.$materia.'/'.$curso;
         echo 'cat'. $categoriaRuta;
         if(!file_exists($categoriaRuta)){
            mkdir($categoriaRuta, 0777, true);}
            $subir->setDestino("profesor/$telefono/$materia/$curso/");
            $subir->setTamaño(1000000);
            $subir->setPolitica(FileUpload::RENOMBRAR);
        if($subir->upload()){
            $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "Archivo subido con éxito",
                "ruta"=>"../index.php?op=VolverZonaProfesor"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>'<a href="index.php?op=VolverZonaProfesor"><li class="volver">'.Volver.'</li></a>'
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
           
            } else{
                  $contenidoParticular = Plantilla::cargarPlantilla("templates/_mensaje.html");
            $datos = array(
                "mensaje" => "Error al subir el archivo",
                "ruta"=>"../index.php?op=VolverZonaProfesor"
                );
            $contenidoParticular=Plantilla::sustituirDatos($datos,$contenidoParticular);
            $pagina=Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>""
                );
                echo Plantilla::sustituirDatos($datos,$pagina);
           
            }}else{echo 'No tiene permisos para ver este contenido, por favor introduzca primero sus datos de acceso';}}
        }
    function metodoViewSubirArchivos(){
        $profesor=$this->sesion->get('_usuario');
        $palabra=$this->sesion->get('_palabra');
         
        if($profesor==null){
            echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
        else{
             if($palabra=='token'){
            $tel=$profesor->getTelefono();
            $condicion="clase.tlfProf='".$tel."' and matricula.idClase=clase.id";
            $proyeccion="*";
            $listaClases=$this->gestorMatricula->getAsignaturas($condicion,$proyeccion);
            $plantillaListaAsignaturas =Plantilla::cargarPlantilla("templates/_radioAsignaturas.html");
            $asignaturas = "";
            foreach ($listaClases as $key => $value) {
                    $asignaturai = str_replace("{clases}", $value["clase"]->getMateria().' '.$value["clase"]->getCurso(), $plantillaListaAsignaturas);
                    $asignaturai = str_replace("{materia}", $value["clase"]->getMateria(), $asignaturai);
                    $asignaturai = str_replace("{curso}", $value["clase"]->getCurso(), $asignaturai);                   
                    $asignaturas .= $asignaturai;
            }
           $datos2=array(
               "capa"=>$asignaturas);
            $contenidoParticular=Plantilla::cargarPlantilla("templates/_ViewSubirArchivos.html");
            $contenidoParticular=Plantilla::sustituirDatos($datos2,$contenidoParticular);
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $contenidoParticular,
                "relleno"=>"",
                "volver"=>'<a href="index.php?op=VolverZonaProfesor"><li class="volver">'.Volver.'</li></a>');
            echo Plantilla::sustituirDatos($datos,$pagina);}else{echo 'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
    }}
    function metodoViewAlumnosPorAsignaturaDelCentro(){
        $profesor=$this->sesion->get('_usuario');
       
        if($profesor!=null&&$profesor->getAdministrador()==1){
      
        $listaClases=$this->gestorClase->getList();
        $clases="";
        $contenidoParticular=Plantilla::cargarPlantilla('templates/_verClasesEnlace.html');
        foreach ($listaClases as $key => $value) {
                $clasei=str_replace("{enlace}",'<a class="st" href="?op=VerAlumnos&id='. $value->getId().
                '&tel='.$value->getTlfProf().'"><li class="oferta">'.$value->getMateria().' '.$value->getCurso().' '.
                $this->gestorProfesor->get($value->getTlfProf())->getNombre().'</li></a>',$contenidoParticular);
                $clases.=$clasei;
            }
        $fila=Plantilla::cargarPlantilla('templates/_template.html');
        $relleno=Plantilla::cargarPlantilla('templates/_verListadoClases2.html');
        $datos= array(
            "contenidoParticular"=>$clases,
            "relleno"=>$relleno,
            "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>');
        echo Plantilla::sustituirDatos($datos,$fila);}
        else{echo'No tiene permisos par ver este contenido, por favor introduzca primero sus datos de acceso';}
     
         
       
    }
    function metodoVerAlumnos(){
         $idClase=Request::get('id');
         $condicion="matricula.tel=alumno.tel and matricula.idClase='".$idClase."'";
            $proyeccion="*";
            $listaClases=$this->gestorAlumno->getAlumnos($condicion,$proyeccion);
            $relleno=Plantilla::cargarPlantilla('templates/_asgnaturaRelleno2.html');
            $plantillaListaAsignaturas =Plantilla::cargarPlantilla("templates/_asignaturas3.html");
            $asignaturas = "";
            foreach ($listaClases as $key => $value) {
                 
                $asignaturai = str_replace("{contenido}", $value["alumno"]->getNombre().' '. $value["alumno"]->getApellido() , $plantillaListaAsignaturas);
                $asignaturai = str_replace("{curso}", $value["alumno"]->getTelefono(), $asignaturai);
                $asignaturai = str_replace("{email}", $value["alumno"]->getEmail(), $asignaturai);
                $asignaturas .= $asignaturai;
            }
            $pagina = Plantilla::cargarPlantilla("templates/_template.html");
            $datos = array(
                "contenidoParticular" => $asignaturas,
                "relleno"=>$relleno,
                "volver"=>'<a href="index.php?op=VolverZonaAdmin"><li class="volver">'.Volver.'</li></a>'
                );
            echo Plantilla::sustituirDatos($datos,$pagina);
    }
    
}
    
    
    
   

  
   
    
