function validarFormulario(){
    //obtener los valores del formulario
    let usuario = document.getElementById("usu").value;
    let pass = document.getElementById("pass").value;
    let errorMensaje = document.getElementById("errorMensaje").value;

    //limpiar el mensaje de error
    errorMensaje.innnerHTML = "";

    //verificar si el campo usuario esta vacio
    if(usuario == ""){
        errorMensaje.innnerHTML= "El campo usuario no puede estar vacio";
        return false;
    }
    //verificar si el campo contraseña esta vacio
    if(pass == ""){
        errorMensaje.innnerHTML= "El campo contraseña no puede estar vacio";
        return false;
    }
    //verificar la longitud minima( no sabemos que longitud tendra)

    //verificar si la contraseña cumple con otros requisitos(no sabemos que requisitos deben cumplir)

    //Si toda las validaciones pasan, permitir el envio del formulario
    alert("Login válido, enviando...");
    return true;
}