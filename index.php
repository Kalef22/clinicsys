<?php
    session_start();
    
    // Generar un token CSRF si no existe
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    // $mensaje = '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="Inicio de ClinicSys" />
        <meta name="author" content="Kaleff Villanueva" />
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <title>ClinicSys</title>
</head>
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
                                        <img src="assets/img/logo01-clinicsys (140 x 50 px).svg" alt="" class="mt-5 mb-1 mx-auto d-block">
                                        <h3 class="text-center font-weight-light my-4">Iniciar sesión</h3>
                                    </div>
                                    <div class="card-body">

                                        <form action="login_process.php" method="POST" id="loginForm">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="email" name="email" type="email" placeholder="email" required  />
                                                <label for="user">Correo</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="pass" name="pass" type="password" placeholder="Password" required />
                                                <label for="pass">Contraseña</label>
                                            </div>
                                            <!-- Mensaje de error -->
                                            <p id="errorMensaje" style="color: red;">
                                            <?php
                                            if (isset($_SESSION['error_message'])) {
                                                echo "<p style='color:red;'>" . $_SESSION['error_message'] . "</p>";
                                                unset($_SESSION['error_message']); // Eliminar el mensaje de error después de mostrarlo
                                            }
                                            ?>
                                            </p>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Recordar contraseña</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-3">
                                                <a class="small" href="password.html">¿olvidaste tu contraseña?</a>
                                                <!-- <a class="btn btn-primary" href="index.html">Login</a> -->
                                                <input class="btn btn-primary" type="submit" value="Acceder" name="submit">
                                            </div>
                                            <!-- Token CSRF -->
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
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