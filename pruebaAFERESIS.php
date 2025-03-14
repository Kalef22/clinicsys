<?php 
    $contraseña = '0102';

    $hash = password_hash( $contraseña, PASSWORD_BCRYPT);
    
    echo $hash;
    echo '<br>';
    // if(password_verify($contraseña, $hashalmacenado)){
    //     echo 'Contraseña correcta';
    // }else{
    //     echo 'Contraseña incorrecta';
    // }
      
   


    // password_verify($contraseña, $hash);
    // echo '<br>';
    // echo $hash;
    // echo '<br>';
    // echo $contraseña;
    // echo '<br>';
    // echo password_verify($contraseña, $hash);
    // echo '<br>';
?>