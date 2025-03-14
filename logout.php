<?php
// Inicia la sesión
session_start();

// Destruye todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión por completo, incluyendo la cookie de sesión, se debe hacer esto
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000,
//         $params["path"], $params["domain"],
//         $params["secure"], $params["httponly"]
//     );
// }

// Finalmente, destruye la sesión
session_destroy();

// Redirige al usuario a la página de inicio de sesión u otra página
header("Location: index.php"); // Cambia esto según la ruta de tu página de inicio de sesión
exit();
