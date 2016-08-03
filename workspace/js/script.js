window.onload = inicio;

function inicio() {
    var boton=document.getElementById('altaAlumno');
    if(boton!=null){
    boton.addEventListener('click',validar);}
     var boton2=document.getElementById('altaProfesor');
     if(boton2!=null){
    boton2.addEventListener('click',validar2);}
    
}

function validar(e){
    var spanError1=document.getElementById('error1');
    var spanError2=document.getElementById('error2');
    var spanError3=document.getElementById('error3');
    var spanError4=document.getElementById('error4');
    var error1=false;
    var error2=false;
    var error3=false;
    var error4=false;
    var nombre=document.getElementById('nombreAlumno').value;
    var apellido=document.getElementById('apellidoAlumno').value;
    var telefono=document.getElementById('telefonoAlumno').value;
    var email=document.getElementById('emailAlumno').value;
    if(cadenaVacia(nombre)||nombre.length>30){
        error1=true;
        spanError1.textContent='* Introduce un nombre de longitud inferior a 30 caracteres';
    }else{
        spanError1.textContent='';
    }
    if(cadenaVacia(apellido)||apellido.length>30){
        error2=true;
        spanError2.textContent='* Introduce un apellido de longitud inferior a 30 caracteres';
    }else{
        spanError2.textContent='';
    }
    if(validarTelefono(telefono)){
        error3=true;
        spanError3.textContent='* Introduce un teléfono compuesto por nueve dígitos numéricos';
    }else{
        spanError3.textContent='';
    }
    if(validarEmail(email)){
        error4=true;
        spanError4.textContent='* Introduce un email correcto';
    }else{
        spanError4.textContent='';
    }
    if(error1||error2||error3||error4){
        e.preventDefault();
    }
}

function cadenaVacia(cadena){
    if(cadena==''){
        return true;
    }
    return false;
}
function validarTelefono(telefono){
    var patron=/^([0-9]+){9}$/;
    if(patron.test(telefono)){
        return false;
    }
    return true;
}
function validarEmail(email){
    var patron=new RegExp(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/);
    if(patron.test(email)){
        return false;
    }
    return true;
}
/*-----------*/
function validar2(e){
    var spanError1=document.getElementById('error1');
    var spanError2=document.getElementById('error2');
    var spanError3=document.getElementById('error3');
    var spanError4=document.getElementById('error4');
    var error1=false;
    var error2=false;
    var error3=false;
    var error4=false;
    var nombre=document.getElementById('nombreProfesor').value;
    var apellido=document.getElementById('apellidoProfesor').value;
    var telefono=document.getElementById('telefonoProfesor').value;
    var email=document.getElementById('emailProfesor').value;
    if(cadenaVacia(nombre)||nombre.length>30){
        error1=true;
        spanError1.textContent='* Introduce un nombre de longitud inferior a 30 caracteres';
    }else{
        spanError1.textContent='';
    }
    if(cadenaVacia(apellido)||apellido.length>30){
        error2=true;
        spanError2.textContent='* Introduce un apellido de longitud inferior a 30 caracteres';
    }else{
        spanError2.textContent='';
    }
    if(validarTelefono(telefono)){
        error3=true;
        spanError3.textContent='* Introduce un teléfono compuesto por nueve dígitos numéricos';
    }else{
        spanError3.textContent='';
    }
    if(validarEmail(email)){
        error4=true;
        spanError4.textContent='* Introduce un email correcto';
    }else{
        spanError4.textContent='';
    }
    if(error1||error2||error3||error4){
        e.preventDefault();
    }
}

function cadenaVacia(cadena){
    if(cadena==''){
        return true;
    }
    return false;
}
function validarTelefono(telefono){
    var patron=/^([0-9]+){9}$/;
    if(patron.test(telefono)){
        return false;
    }
    return true;
}
function validarEmail(email){
    var patron=new RegExp(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/);
    if(patron.test(email)){
        return false;
    }
    return true;
}