<?php
// obtener_citas.php
require_once 'db.php';

// Obtener todas las citas de la base de datos
$stmt = $pdo->query("SELECT nombre, fecha, hora FROM citas");
$citas = $stmt->fetchAll();

echo json_encode($citas);

// Para mostrar las citas desde la base de datos, crea un archivo que recupere las citas y las envíe en formato JSON (obtener_citas.php):
