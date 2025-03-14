<?php 
  
  session_start();
  $mensaje = '';
  
  require "Acceso.php";
  require "config/Conexion.php";
  $conexion = (new Conexion())->getConexion();
  $acc = new Acceso();
      if (isset($_POST['submit']) && !empty($_POST['user']) && !empty($_POST['pass'])) {
      $site = "10.35.49.125";
      $port = 6774;
      set_time_limit(0);
      $fp = fsockopen($site, $port, $errno, $errstr, 10);
      echo "<script>showSpinner();</script>";
      if (!$fp) {
          $mensaje = "Falla en la Conexion";
          echo "<script>hideSpinner() ;</script>";
      } else {
          $myObj = new stdClass();
          $myObj->user =$_POST['user'];
          $myObj->password = $_POST['pass'];
          $myJSON = json_encode($myObj);
          $a = fwrite($fp, $myJSON);
          while (!feof($fp)) {
              $output = fgets($fp, 2048);
          }
  
          $json = $output;
          $obj = json_decode($json);
         
          if ($obj->error == null && $obj->rol != 'Sin permisos') {
              $_SESSION['valid'] = true;
              $_SESSION['timeout'] = time();
              $_SESSION['usuario'] = $obj->usuario;
              $_SESSION['rol'] = $obj->rol;
              $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
              $_SESSION['nombre'] = $obj->nombre;
              $_SESSION['apellidos'] = $obj->apellidos;
              $mensaje = 'Bienvenido ' . $obj->nombre . ' ' . $obj->apellidos . '<br>Tu rol es: ' . $obj->rol . '<br>Estás conectado desde la IP: ' . $_SERVER['REMOTE_ADDR'];
              $_SESSION['mensaje'] = $mensaje;
              $ultimaConex = $acc->consultarFechaUltimoAcceso($_SESSION['usuario']);
              try{
                  $query_ultima_conexion ="UPDATE usuario SET ultima_conexion = :ultima_conexion WHERE id_usuario = :id_usuario";
                  $stmt_ultima_conexion = $conexion->prepare($query_ultima_conexion);
                  $stmt_ultima_conexion->bindParam(':ultima_conexion', $ultimaConex);
                  $stmt_ultima_conexion->bindParam(':id_usuario', $_SESSION['usuario']);
                  $stmt_ultima_conexion->execute();
              }catch(PDOException $e){
                  $error['ultima_conexion'];
              }
              $_SESSION['ultimaCon'] = $ultimaConex;
              $acc->acceso($_SESSION['usuario']);
  
              header("Location: ./validacionInterna.php");
              exit();
          } else {
              if ($obj->error == null && $obj->rol == 'Sin permisos') {
                  $mensaje = 'Usuario sin permisos';
              } 
              else{
                  $mensaje = 'Usuario o contraseña incorrecto';
              }
          }
      }
  }
  ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
        /* ----------- estilos del spinner ---------------------- */ 
        /* Fondo transparente que cubre toda la pantalla */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            display: none; /* Oculto por defecto */
        }

        /* Spinner circular */
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #e8e8e8; /* Color del borde */
            border-top: 5px solid #2a82bf; /* Color del indicador */
            border-radius: 50%;
            animation: spin 1s linear infinite; /* Animación de giro */
        }

        /* Animación del spinner */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <body class="bg-ligth">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <!-- Alineamiento de la caja login -->
                        <div class="d-flex justify-content-center align-items-center" style="height: 95vh;">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <img src="assets/img/logo_12octubre.png" alt="" class="mt-5 mb-1 mx-auto d-block">
                                        <h3 class="text-center font-weight-light my-4">Iniciar sesión</h3>
                                    </div>
                                    <div class="card-body">

                                        <form action="<?php  $_SERVER['PHP_SELF'] ?>" method="POST" id="loginForm">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="user" name="user" type="text" placeholder="usuario" required  />
                                                <label for="user">Usuario</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="pass" name="pass" type="password" placeholder="Password" required />
                                                <label for="pass">Contraseña</label>
                                            </div>
                                            <!-- Mensaje de error -->
                                            <p id="errorMensaje" style="color: red;">
                                            <?php echo $mensaje ?>
                                            </p>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
                                                <a class="small" href="password.html">¿olvidaste tu contraseña?</a>
                                                <!-- <a class="btn btn-primary" href="index.html">Login</a> -->
                                                <input class="btn btn-primary" type="submit" value="Acceder" name="submit">
                                            </div>
                                        </form>

                                    </div>
                                    <!-- <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.html">Need an account? Sign up!</a></div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!-- aqui aparece el spinner -->
        <div class="spinner-overlay">
            <div class="spinner"></div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <!-- <script src="js/validarFormulario.js"></script> -->
        <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            // Mostrar el spinner
            document.querySelector('.spinner-overlay').style.display = 'flex';
        });
    </script>
    </body>
</html>
