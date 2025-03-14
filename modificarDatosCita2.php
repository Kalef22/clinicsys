<?php
session_start();
// include_once "includes/header.php";
// include_once "includes/aside.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_cambios'])) {
    $id_cita = htmlspecialchars(stripcslashes($_POST['id_cita']));
    $nombre = htmlspecialchars(stripcslashes($_POST['nombre']));
    $apellido1 = htmlspecialchars(stripcslashes($_POST['apellido1']));
    $apellido2 = htmlspecialchars(stripcslashes($_POST['apellido2']));
    $dni = htmlspecialchars(stripcslashes($_POST['dni']));
    $nombre_maquina_old = htmlspecialchars(stripcslashes($_POST['nombre_maquina_old']));
    $fecha_old = htmlspecialchars(stripcslashes($_POST['fecha_old']));
    $hora_inicio_old = htmlspecialchars(stripcslashes($_POST['hora_inicio_old']));
    //variables a modificar por el usuario
    $id_maquina_new = htmlspecialchars(stripcslashes($_POST['id_maquina_new']));
    $fecha_new = isset($_POST['fecha_new']) ? htmlspecialchars($_POST['fecha_new']) : '';
    // echo $id_maquina_new;

    try {
        require_once "config/Conexion.php";
        //verificar si el donante ya existe
        $pdo = (new Conexion())->getConexion();
        $queryDniDonante = "SELECT id_donante FROM donante WHERE dni = :dni";
        $stmt_dni = $pdo->prepare($queryDniDonante);
        $stmt_dni->bindParam(":dni", $dni);
        $stmt_dni->execute();

        //obtener el id del donante
        $id_donante = $stmt_dni->fetchColumn();

        //obtener el nombre de la maquina elegida
        $queryMaquina = "SELECT descripcion_maquina FROM maquina WHERE id_maquina = :id_maquina";
        $stmt_maquina = $pdo->prepare($queryMaquina);
        $stmt_maquina->bindParam(":id_maquina", $id_maquina_new);
        $stmt_maquina->execute();
        $nombre_maquina_new = $stmt_maquina->fetchColumn();
        // echo $nombre_maquina_new;

        //SI EL DONANTE NO EXISTE
        if(!$id_donante){
            //si el donante no existe insertar y obtener su id_donante
            $query_NoExiste = "INSERT INTO donante(nombre, apellido1, apellido2, telef1, dni) VALUES (:nombre, :apellido1, :apellido2, :telf1, :dni)";
            $stmt_Noexiste = $pdo->prepare($query_NoExiste);
            $stmt_Noexiste->bindParam(":nombre", $nombre);
            $stmt_Noexiste->bindParam(":apellido1", $apellido1);
            $stmt_Noexiste->bindParam(":apellido2", $apellido2);
            $stmt_Noexiste->bindParam(":telf1", $telefono);
            $stmt_Noexiste->bindParam(":dni", $dni);
            $stmt_Noexiste->execute();
            
            //obtener el id_donante recien insertado
            $id_donante = $pdo->lastInsertId();
            $donante_existe =  "Nuevo donante creado con id: ". $id_donante . "<br>";
        }else{
        //SI EL DONANTE EXISTE
            $donante_existe = "Donante ".$nombre." ".$apellido1." "." ya existente con ID: ". $id_donante . "<br>";    
        }

        //obtener el id_dia
        require_once "config/Conexion.php";
        $pdo = (new Conexion())->getConexion();
        $query_idDia = "SELECT id_dia FROM calendario WHERE fecha = :fecha";
        $stmt_idDia = $pdo->prepare($query_idDia);
        $stmt_idDia->bindParam(":fecha", $fecha_new);
        $stmt_idDia->execute();
        $id_dia = $stmt_idDia->fetchColumn();
        // echo "<br>";
        // echo $id_dia;
        // echo "<br>";
        // echo $fecha;
        
    } catch (PDOException $e) {
        echo "Error al pedir la cita. " . $e->getMessage();
    }
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios Disponibles</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">

            <div class="container my-5">
                <h2 class="text-center mb-4">Horarios Disponibles para <?php echo $nombre_maquina_new; ?></h2>
                <strong><?php //echo $donante_existe; ?></strong><br>
                 

                  <!-- Aviso de creación o existencia del donante -->
                    <?php if (isset($donante_existe)): ?>
                        <div class="alert <?php echo isset($id_donante) && !$id_donante ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
                            <strong><?php echo $donante_existe; ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <!-- Fin del aviso -->

                <?php
                //Renderizado del dia seleccionado Inicio
                try {
                    require_once 'config/Conexion.php';
                    $pdo = (new Conexion())->getConexion();

                    // Consulta SQL para obtener los horarios disponibles
                    $sql_horarios = "SELECT id_dia, descripcion_dia, fecha, hora_inicio, hora_fin FROM calendario WHERE fecha = :fecha ";
                    $stmt_horarios = $pdo->prepare($sql_horarios);
                    $stmt_horarios->bindParam(":fecha", $fecha_new);
                    $stmt_horarios->execute();

                    // Verificar si hay resultados
                    if ($stmt_horarios->rowCount() > 0) {
                        echo '<table class="table table-striped table-bordered">';
                        echo '<thead class="table-dark">';
                        echo '<tr><th>Día</th><th>Fecha</th><th>Hora de inicio</th><th>Hora de fin</th></tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Recorrer y mostrar los resultados
                        while ($fila = $stmt_horarios->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila["descripcion_dia"]) . '</td>';
                            echo '<td>' . htmlspecialchars($fila["fecha"]) . '</td>';
                            echo '<td>' . htmlspecialchars($fila["hora_inicio"]) . '</td>';
                            echo '<td>' . htmlspecialchars($fila["hora_fin"]) . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p class="text-center">No hay horarios disponibles en este momento.</p>';
                    }
                } catch (PDOException $e) {
                    // Manejo de errores
                    echo '<p class="text-danger text-center">Error en la conexión: ' . $e->getMessage() . '</p>';
                }
                // Renderizado del dia seleccionado fin


                // Renderizado de horarios, si existe la fecha seleccionada INICIO
                if (isset($_POST['fecha_new'])) {
                    // $selectedDate = $_POST['fecha'];

                    try {
                        // Conexión a la base de datos
                        $pdo = (new Conexion())->getConexion();

                        // Obtener el tiempo de cita (en minutos) desde la base de datos
                        $query_tiempo = "SELECT tiempo_cita FROM horarios WHERE id_horario IN (SELECT id_horario FROM calendario WHERE fecha = :fecha)";
                        $stmt_tiempo = $pdo->prepare($query_tiempo);
                        $stmt_tiempo->bindParam(":fecha", $fecha_new);
                        $stmt_tiempo->execute();
                        $tiempoCita_string = $stmt_tiempo->fetchColumn();
                       
                        // Dividimos el tiempoCita_string con explode para obtener los datos separados y lo pasamos a entero
                        list($horas_string, $minutos_string, $segundos_string) = explode(":", $tiempoCita_string);
                        $horas = (int)$horas_string;
                        $minutos = (int)$minutos_string;
                        $segundos = (int)$segundos_string;  
                
                        // Verificar si se obtuvo el tiempo de cita
                        if ($tiempoCita_string) {
                            // Obtener la hora de inicio y fin de la jornada laboral
                            $query_horaInicio = "SELECT hora_inicio, hora_fin FROM calendario WHERE fecha = :fecha_seleccionada";
                            $stmt_horaInicio = $pdo->prepare($query_horaInicio);
                            $stmt_horaInicio->bindParam(":fecha_seleccionada", $fecha_new);
                            $stmt_horaInicio->execute();
                            $horario = $stmt_horaInicio->fetch(PDO::FETCH_ASSOC);

                            if ($horario) {
                                //La línea DateTime::createFromFormat('H:i:s', $horario['hora_inicio']) convierte "09:00:00" en un objeto DateTime que representa las 9:00 AM por ejemplo
                                $horaInicio = DateTime::createFromFormat('H:i:s',$horario['hora_inicio']);
                                $horaFin = DateTime::createFromFormat('H:i:s',$horario['hora_fin']);
                              
                                // Crear un arreglo para los horarios disponibles
                                $horarios = [];

                                // Generar los horarios disponibles según el tiempo de cita
                                while ($horaInicio < $horaFin) {
                                    $horarios[] = $horaInicio->format("H:i:s");
                                    $horaInicio->modify("+{$horas} hours +{$minutos} minutes"); // Aquí aseguramos que solo pasamos el número de minutos y horas
                                }

                                // Mostrar los horarios en una tabla
                                // echo "<h3 class='text-center'>Horarios disponibles para {$fecha_seleccionada}</h3>";
                                echo "<table class='table'>";
                                echo '<table class="table table-striped table-bordered">';
                                echo '<thead class="table-dark">';
                                echo "<thead>
                                        <tr>
                                        <th>Horario</th>
                                        <th>Disponibilidad</th>
                                        <th>Seleccionar</th></tr>
                                        </tr>
                                       </thead><tbody>";
                                foreach ($horarios as $horario) {
                                    // Verificar si la hora ya está reservada
                                    $query_disponible = "SELECT * FROM cita WHERE id_dia = :id_dia AND id_maquina = :id_maquina AND hora_inicio = :hora_inicio";
                                    $stmt_disponible = $pdo->prepare($query_disponible);
                                    $stmt_disponible->bindParam(":id_dia", $id_dia);
                                    $stmt_disponible->bindParam(":id_maquina", $id_maquina_new);
                                    $stmt_disponible->bindParam(":hora_inicio", $horario);
                                    $stmt_disponible->execute();
                                    $citas = $stmt_disponible->fetchAll(PDO::FETCH_ASSOC);

                                    $disponibilidad = empty($citas) ? "Disponible" : "No disponible";
                                   
                                    echo "<tr>
                                            <td>{$horario}</td>
                                            <td>{$disponibilidad}</td>
                                            <td><button class='btn btn-primary select-hour' data-hour='{$horario}' " . ($disponibilidad == "No disponible" ? "disabled" : "") . ">Seleccionar</button></td>
                                        </tr>";
                                }

                                //enviar la fecha
                                ?>
                                <!-- boton del formulario que cogera la fecha -->
                                

                                <?php
                                 echo "</tbody></table>";
                            } else {
                                echo "<p class='text-center'>No se encontraron horarios de trabajo para esta fecha.</p>";
                            }
                        } else {
                            echo "<p class='text-center'>No se encontró configuración de tiempo de cita.</p>";
                        }
                    } catch (PDOException $e) {
                        echo "Error al obtener horarios: " . $e->getMessage();
                    }
                }
                ?>
                     <!-- Formulario de selección de cita -->
                     <form action="registrarModificacion_cita.php" method="post">
                            <!-- Boton oculto para id_cita OK -->
                        <input type="hidden" id="id_cita" name="id_cita" value="<?php echo $id_cita; ?>"> 
                            <!-- Boton oculto para id_donante OK -->
                        <input type="hidden" id="id_donante" name="id_donante" value="<?php echo $id_donante; ?>"> 
                            <!-- Boton oculto para el id_dia OK -->
                        <input type="hidden" id="id_dia" name="id_dia" value="<?php echo $id_dia; ?>">
                            <!-- Campo oculto para nombre OK -->
                        <input type="hidden" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                            <!-- Campo oculto para primer apellido OK -->
                        <input type="hidden" id="apellido1" name="apellido1" value="<?php echo $apellido1; ?>">
                            <!-- Campo oculto para segundo apellido OK -->
                        <input type="hidden" id="apellido2" name="apellido2" value="<?php echo $apellido2; ?>">
                            <!-- Boton oculto para el nombre_maquina_old OK -->
                        <input type="hidden" id="nombre_maquina_old" name="nombre_maquina_old" value="<?php echo $nombre_maquina_old; ?>">
                            <!-- Boton oculto para el nombre_maquina_new OK -->
                        <input type="hidden" id="nombre_maquina_new" name="nombre_maquina_new" value="<?php echo $nombre_maquina_new; ?>">
                            <!-- Boton oculto para el id_maquina_new OK -->
                        <input type="hidden" id="id_maquina_new" name="id_maquina_new" value="<?php echo $id_maquina_new; ?>">
                            <!-- Campo oculto para la fecha antigua -->
                        <input type="hidden" id="fecha_old" name="fecha_old" value="<?php echo $fecha_old; ?>">
                            <!-- Campo oculto para la fecha nueva -->
                        <input type="hidden" id="fecha_new" name="fecha_new" value="<?php echo $fecha_new; ?>">
                            <!-- Campo oculto para la hora antigua -->
                        <input type="hidden" id="hora_inicio_old" name="hora_inicio_old" value="<?php echo $hora_inicio_old; ?>">
                            <!-- Campo oculto para la hora seleccionada -->
                        <input type="hidden" id="selectedHourInput" name="selectedHour"> 
                            <!-- Botón de envío del formulario -->
                        <button type="submit" class="btn btn-success mt-3">Enviar cita</button>
                    <!-- </form>  -->
        
            </div>            
        </div>      
    </main>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener todos los botones con la clase 'select-hour'
        const buttons = document.querySelectorAll('.select-hour');

        // Obtener la hora actual
        const tiempo_actual = new Date();
        const hora_actual = tiempo_actual.getHours();
        const minuto_actual = tiempo_actual.getMinutes();

        // Recorrer todos los botones y compararlos con la hora actual
        buttons.forEach(button => {
            // Obtener la hora del botón desde el atributo data-hour
            const selectedHour = button.getAttribute('data-hour');
            const [hour, minutes] = selectedHour.split(':').map(Number); // Convertir a números

            // Verificar si la hora es anterior a la hora actual
            if (hour < hora_actual || (hour === hora_actual && minutes <= minuto_actual)) {
                // Deshabilitar el botón si la hora es anterior o igual a la actual
                button.disabled = true;
                button.textContent = 'No disponible';
            } else {
                // Agregar el evento de selección de hora
                button.addEventListener('click', function() {
                    // Remover la clase de selección de otros botones
                    buttons.forEach(btn => btn.classList.remove('btn-success', 'active'));

                    // Agregar estilo visual de selección al botón actual
                    this.classList.add('btn-success', 'active');

                    // Almacenar la hora seleccionada en el campo oculto
                    document.getElementById('selectedHourInput').value = selectedHour;
                    console.log(selectedHour);
                    
                });
            }
        });
    });
</script>




</body>
</html>


