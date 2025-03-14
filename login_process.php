<?php
var_dump($_POST);
session_start();
require_once "config/Conexion.php";
require_once "Acceso.php";
$acc = new Acceso();

// Verificar si se recibió el formulario correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Verificar el token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: Token CSRF inválido.");
    }

    // Conexión segura a la base de datos con PDO
    $pdo = (new Conexion())->getConexion();

    // Sanitizar y validar la entrada del usuario
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['pass'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico inválido.");
    }

    // Buscar el usuario en la base de datos
    $query = "SELECT id_usuario, nombre, apellido1, apellido2,email, contrasenia, id_rol FROM usuario WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Verificar si el usuario existe
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['contrasenia'])) {
        // Inicio de sesión exitoso
        if ($user['id_rol'] >= '1' && $user['id_rol'] <= 3) {
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['apellido1'] = $user['apellido1'];
            $_SESSION['apellido2'] = $user['apellido2'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['id_rol'] = $user['id_rol'];
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $mensaje = 'Bienvenido ' . $_SESSION['nombre'] . ' ' . $_SESSION['apellido1'] . '<br>Tu rol es: ' . ($_SESSION['id_rol']=='1')? 'administrador':(($_SESSION['id_rol']=='2')? 'Profesional':(($_SESSION['id_rol']=='3')? 'Solo lectura': 'Sin permiso')) . '<br>Estás conectado desde la IP: ' . $_SERVER['REMOTE_ADDR'];
            $_SESSION['mensaje'] = $mensaje;
            $ultimaConex = $acc->consultarFechaUltimoAcceso($_SESSION['id_usuario']);
            try{
                $query_ultima_conexion ="UPDATE usuario SET ultima_conexion = :ultima_conexion WHERE id_usuario = :id_usuario";
                $stmt_ultima_conexion = $pdo->prepare($query_ultima_conexion);
                $stmt_ultima_conexion->bindParam(':ultima_conexion', $ultimaConex);
                $stmt_ultima_conexion->bindParam(':id_usuario', $_SESSION['id_usuario']);
                $stmt_ultima_conexion->execute();
            }catch(PDOException $e){
                echo "Error al actualizar la última conexión: " . $e->getMessage(); 
            }
            $_SESSION['ultimaCon'] = $ultimaConex;
            $acc->acceso($_SESSION['id_usuario']);

            header("Location: inicio.php");
            exit();
        } else {
                $mensaje = 'Rol no permitido';
                header("Location: index.php"); // Redirigir al formulario de inicio de sesión con un mensaje de error
        }
        // header("Location: inicio.php"); // Redirigir al panel de usuario
        // exit();
    } else {
        $_SESSION['error_message'] = "Correo o contraseña incorrectos.";
        header("Location: index.php"); // Redirigir al formulario de inicio de sesión con un mensaje de error
        exit();
    }
}