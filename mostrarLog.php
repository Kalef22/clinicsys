<?php
    //ruta del archivo de texto que se desea mostrar
    $archivo_log = 'logs/navegacion_usuarios.txt';

    //verificar si el archivo existe
    if (file_exists($archivo_log)){
        //leer el contenido del archivo
        $contenido = file_get_contents($archivo_log);

        //establecer el tipo de contenido para el navegador
        header('Content-Type: text/plain');

        //mostrar el contenido del archivo
        echo $contenido;
    }else{
        //mostrar un mensaje de error si el archivo no existe
        echo 'El archivo no existe';
    }