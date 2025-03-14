<?php
// guardar_cita.php
require_once 'db.php'; // Incluir conexión a la base de datos

// Recibir datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // Verificar si ya existe una cita para la misma fecha y hora
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE fecha = ? AND hora = ?");
    $stmt->execute([$fecha, $hora]);
    $citaExistente = $stmt->fetchColumn();

    if ($citaExistente > 0) {
        echo json_encode(['error' => 'Ya existe una cita programada para esta fecha y hora.']);
        exit;
    }

    // Insertar la nueva cita en la base de datos
    $stmt = $pdo->prepare("INSERT INTO citas (nombre, fecha, hora) VALUES (?, ?, ?)");
    $stmt->execute([$nombre, $fecha, $hora]);

    echo json_encode(['success' => 'Cita guardada exitosamente.']);
}
?>
