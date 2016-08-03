$(document).ready(function () {
    var acceder=$("#home");
     acceder.on("click", aparecer);
  function aparecer(){
      $('#accede').fadeIn("slow");}
    var desaparecer=$("#entrar");
    desaparecer.on("click", desaparecer);
function desaparecer(){
    $('#accede').fadeOut("slow");}
    

      
      
});