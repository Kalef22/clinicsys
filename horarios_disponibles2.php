<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once "includes/header.php";
require_once "includes/aside.php";
// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars(isset($_POST['appointmentName']) ? $_POST['appointmentName'] : '');
    $firstSurname = htmlspecialchars(isset($_POST['appointmentLastName1']) ? $_POST['appointmentLastName1'] : '');
    $secondSurname = htmlspecialchars(isset($_POST['appointmentLastName2']) ? $_POST['appointmentLastName2'] : '');
    $dni = htmlspecialchars(isset($_POST['appointmentDNI']) ? $_POST['appointmentDNI'] : '');
    $telefono = htmlspecialchars(isset($_POST['appointmentPhone']) ? $_POST['appointmentPhone'] : '');
    $id_maquina = htmlspecialchars(isset($_POST['appointmentMachine']) ? $_POST['appointmentMachine'] : '');
    $fecha_seleccionada = htmlspecialchars(isset($_POST['selectedDate']) ? $_POST['selectedDate'] : ''); //ejemplo 2024-11-25
    $hora_seleccionada = htmlspecialchars(isset($_POST['selectedHour']) ? $_POST['selectedHour'] : '');

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

        //SI EL DONANTE NO EXISTE
        if(!$id_donante){
            //si el donante no existe insertar y obtener su id_donante
            $query_NoExiste = "INSERT INTO donante(nombre, apellido1, apellido2, telef1, dni) VALUES (:nombre, :apellido1, :apellido2, :telf1, :dni)";
            $stmt_Noexiste = $pdo->prepare($query_NoExiste);
            $stmt_Noexiste->bindParam(":nombre", $nombre);
            $stmt_Noexiste->bindParam(":apellido1", $firstSurname);
            $stmt_Noexiste->bindParam(":apellido2", $secondSurname);
            $stmt_Noexiste->bindParam(":telf1", $telefono);
            $stmt_Noexiste->bindParam(":dni", $dni);
            $stmt_Noexiste->execute();
            
            //obtener el id_donante recien insertado
            $id_donante = $pdo->lastInsertId();
            $donante_existe =  "Nuevo donante creado con id: ". $id_donante . "<br>";
        }else{
        //SI EL DONANTE EXISTE
            $donante_existe = "Donante ".$nombre." ".$firstSurname." "." ya existente con ID: ". $id_donante . "<br>";    
        }

        //obtener el id_dia
        $query_idDia = "SELECT id_dia FROM calendario WHERE fecha = :fecha";
        $stmt_idDia = $pdo->prepare($query_idDia);
        $stmt_idDia->bindParam(":fecha", $fecha_seleccionada);
        $stmt_idDia->execute();
        $id_dia = $stmt_idDia->fetchColumn();

        //obtener la descripcion de la maquina
        $query_descripcion_maquina = "SELECT descripcion_maquina FROM maquina WHERE id_maquina = :id_maquina";
        $statement_descripcion_maquina = $pdo->prepare($query_descripcion_maquina);
        $statement_descripcion_maquina->bindParam(":id_maquina", $id_maquina);
        $statement_descripcion_maquina->execute();
        $descripcion_maquina = $statement_descripcion_maquina->fetchColumn();

         // Verificar si ya existe una cita en la fecha seleccionada o una cita futura para este usuario -----------------
         $queryVerificarCita = "SELECT COUNT(*) FROM cita 
         WHERE id_donante = :id_donante 
         AND (id_dia = :fecha_seleccionada 
              OR id_dia IN (SELECT id_dia FROM calendario WHERE fecha > CURDATE()))";
        $stmt_verificarCita = $pdo->prepare($queryVerificarCita);
        $stmt_verificarCita->bindParam(":id_donante", $id_donante);
        $stmt_verificarCita->bindParam(":fecha_seleccionada", $id_dia);
        $stmt_verificarCita->execute();
        $citaExistente = $stmt_verificarCita->fetchColumn();
        // print_r($citaExistente);

        $citaExistenteFlag = false; // Inicializamos en falso

        if ($citaExistente > 0) {
           
            // Si ya tiene una cita en la fecha seleccionada o una cita futura, mostrar mensaje de error
            $error_msg = "El usuario ya tiene una cita en la fecha seleccionada o una cita futura activa.";
            $citaExistenteFlag = true; // Cambia el indicador a verdadero si ya tiene una cita
            $sql = "SELECT cita.id_cita, donante.nombre, donante.apellido1, donante.apellido2, donante.dni, 
            maquina.descripcion_maquina, calendario.fecha, cita.hora_inicio  
            FROM cita 
            LEFT JOIN donante ON cita.id_donante = donante.id_donante 
            LEFT JOIN maquina ON cita.id_maquina = maquina.id_maquina 
            LEFT JOIN calendario ON cita.id_dia = calendario.id_dia WHERE fecha >= CURDATE() AND donante.dni= :dni ";
            $stm = $pdo->prepare($sql);
            $stm->bindParam(':dni', $dni);
            $stm->execute();
            $stm->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stm->fetchAll();
            // echo '<pre>';
            // print_r($rows);
            // echo '</pre>';
                 
            // return; // Detener la ejecución si ya hay una cita existente
        }
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
                <!-- <h2 class="text-center mb-4">Horarios Disponibles para: <?php //echo $descripcion_maquina; ?></h2> -->
                <h2 class="text-center mb-4" style="color: #2a82bf; font-weight: bold;">
                    <i class="fas fa-clock"></i> Horarios Disponibles para: <span style="text-decoration: underline;"><?php echo htmlspecialchars($descripcion_maquina); ?></span>
                </h2>
                <div class="fixed-header">
                    <!-- Este es un encabezado fijo -->
                </div>

                <strong><?php //echo $donante_existe; ?></strong><br>

                  <!-- Aviso de creación o existencia del donante -->
                    <?php if (isset($donante_existe)): ?>
                        <div class="alert <?php echo isset($id_donante) && !$id_donante ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
                            <strong><?php echo $donante_existe; ?></strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <!-- Fin del aviso -->

                    <?php if (isset($error_msg)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error_msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php elseif (isset($success_msg)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $success_msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; 
                        if(isset($donante_existe) && $citaExistente > 0){
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                        echo "<h3>Cita pendiente</h3>";
                        echo "<strong>Id cita: </strong>".$rows[0]['id_cita']."<br>";
                        echo "<strong>Nombre: </strong>".$rows[0]['nombre']."<br>";
                        echo "<strong>Apellidos: </strong>".$rows[0]['apellido1']. " " . $rows[0]['apellido2']."<br>";
                        echo "<strong>DNI: </strong>".$rows[0]['dni']."<br>";
                        echo "<strong>Máquina: </strong>".$rows[0]['descripcion_maquina']."<br>";
                        echo "<strong>Fecha: </strong>".$rows[0]['fecha']."<br>";
                        echo "<strong>Hora de inicio: </strong> " .$rows[0]['hora_inicio']."<br>";
                        echo "</div>";
                        }
                    ?>

                <?php
                //Renderizado del dia seleccionado Inicio -----------------------------------------------------------------------------------------
                try {
                    require_once 'config/Conexion.php';
                    $pdo = (new Conexion())->getConexion();

                    // Consulta SQL para obtener los horarios disponibles
                    $sql_horarios = "SELECT id_dia, descripcion_dia, fecha, hora_inicio, hora_fin FROM calendario WHERE fecha = :fecha ";
                    $stmt_horarios = $pdo->prepare($sql_horarios);
                    $stmt_horarios->bindParam(":fecha", $fecha_seleccionada, PDO::PARAM_STR);
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
                // Renderizado del dia seleccionado fin --------------------------------------------------------------------------------------------
                ?>

                <?php
                // Renderizado de horarios, si existe la fecha seleccionada INICIO------------------------------------------------------------------
                if (isset($_POST['selectedDate'])) {
                    // $selectedDate = $_POST['selectedDate'];

                    try {
                        // Conexión a la base de datos
                        $pdo = (new Conexion())->getConexion();

                        // Obtener el tiempo de cita (en minutos) desde la base de datos
                        $query_tiempo = "SELECT tiempo_cita FROM horarios WHERE id_horario IN (SELECT id_horario FROM calendario WHERE fecha = :fecha)";
                        $stmt_tiempo = $pdo->prepare($query_tiempo);
                        $stmt_tiempo->bindParam(":fecha", $fecha_seleccionada);
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
                            $stmt_horaInicio->bindParam(":fecha_seleccionada", $fecha_seleccionada);
                            $stmt_horaInicio->execute();
                            $horario = $stmt_horaInicio->fetch(PDO::FETCH_ASSOC);
                            // echo "<pre>";
                            // print_r($horario);
                            // echo "</pre>";


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
                                    
                                    //Verificar si la hora ya está reservada
                                    $query_disponible = "SELECT * FROM cita WHERE id_dia = :id_dia AND id_maquina = :id_maquina AND hora_inicio = :hora_inicio";
                                    $stmt_disponible = $pdo->prepare($query_disponible);
                                    $stmt_disponible->bindParam(":id_dia", $id_dia);
                                    $stmt_disponible->bindParam(":id_maquina", $id_maquina);
                                    $stmt_disponible->bindParam(":hora_inicio", $horario);
                                    $stmt_disponible->execute();
                                    $citas = $stmt_disponible->fetchAll(PDO::FETCH_ASSOC);
                                  

                                    $disponibilidad = empty($citas) ? "Disponible" : "reservado";

                                  
                                    echo "<tr>
                                            <td>{$horario}</td>
                                            <td>{$disponibilidad}</td>
                                            <td>
                                                <button class='btn " . ($disponibilidad == "reservado" ? "btn-danger" : "btn-primary") . " select-hour' data-hour='{$horario}' " . ($disponibilidad == "reservado" ? "disabled" : "") . ">
                                                " . ($disponibilidad == "reservado" ? "Reservado" : "Seleccionar") . "   
                                                </button>
                                            </td>
                                        </tr>";

                                    //enviar la fecha

                                    // Procesar la creación de la cita si no hay conflictos
                                    if (!empty($hora_seleccionada)) {
                                        $queryCrearCita = "INSERT INTO cita(id_donante, id_dia, id_maquina, hora_inicio) VALUES (:id_donante, :id_dia, :id_maquina, :hora_seleccionada)";
                                        $stmt_crearCita = $pdo->prepare($queryCrearCita);
                                        $stmt_crearCita->bindParam(":id_donante", $id_donante);
                                        $stmt_crearCita->bindParam(":id_dia", $id_dia);
                                        $stmt_crearCita->bindParam(":id_maquina", $id_maquina);
                                        $stmt_crearCita->bindParam(":hora_seleccionada", $hora_seleccionada);
                                        $stmt_crearCita->execute();
                                        $success_msg = "Cita reservada exitosamente para el usuario.";
                                    } else {
                                        $error_msg = "La hora seleccionada no es válida.";
                                    }
                
                                    //boton del formulario que cogera la fecha
                                }
                                 echo "</tbody></table>";
                            }else {
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
                     <form action="registrar_cita.php" method="post" id="citaForm">
                        <!-- Boton oculto para id_donante -->
                        <input type="hidden" id="id_donante" name="id_donante" value="<?php echo $id_donante; ?>">
                        <!-- Boton oculto para el id_dia -->
                         <input type="hidden" id="id_dia" name="id_dia" value="<?php echo $id_dia; ?>">
                         <!-- Boton oculto para el id_maquina -->
                          <input type="hidden" id="id_maquina" name="id_maquina" value="<?php echo $id_maquina; ?>">
                        <!-- Campo oculto para la hora seleccionada -->
                        <input type="hidden" id="selectedHourInput" name="selectedHour"> 
                        <!-- Campo oculto para la fecha seleccionada -------------------------------------------------------------------->
                        <input type="hidden" id="fecha_selecionada" name="fecha_selecionada" value="<?php echo $fecha_seleccionada; ?>">
                        <!-- Campo oculto para nombre -->
                        <input type="hidden" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                        <!-- Campo oculto para primer apellido -->
                        <input type="hidden" id="apellido1" name="apellido1" value="<?php echo $firstSurname; ?>">
                        <!-- Campo oculto para segundo apellido -->
                        <input type="hidden" id="apellido2" name="apellido2" value="<?php echo $secondSurname; ?>">
                        <!-- Botón de envío del formulario -->
                         <div class="d-flex justify-content-end gap-2">
                             <button type="button" class="btn btn-danger" onclick="window.location.href='horarios_disponibles1.php'">Cancelar cita</button>
                             <button type="submit" class="btn btn-success" id="enviarCitaBtn">Enviar cita</button>
                         </div>
                    </form> 
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

        // Verificar si el usuario ya tiene una cita (PHP pasará este valor)
        const citaExistente = <?php echo json_encode($citaExistenteFlag); ?>;

        // Si el usuario ya tiene una cita, deshabilitar todos los botones de horario
        if (citaExistente) {
            buttons.forEach(button => {
                button.disabled = true;
                button.textContent = 'No disponible';
            });
        } else {
            // Obtener la fecha seleccionada
            const fechaSeleccionada = document.getElementById('fecha_selecionada').value;
            const fechaActual = new Date().toISOString().split('T')[0];

            // Si la fecha seleccionada es igual a la fecha actual, aplicar lógica para comparar con la hora actual
            if (fechaSeleccionada === fechaActual) {
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
                            // Agregar la clase de selección al botón actual
                            button.classList.add('btn-success', 'active');
                            // Establecer el valor del input hidden con la hora seleccionada
                            document.getElementById('selectedHourInput').value = selectedHour;
                        });
                    }
                });
            } else {
                // Si la fecha seleccionada no es igual a la fecha actual, agregar el evento de selección de hora
                buttons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Remover la clase de selección de otros botones
                        buttons.forEach(btn => btn.classList.remove('btn-success', 'active'));
                        // Agregar la clase de selección al botón actual
                        button.classList.add('btn-success', 'active');
                        // Establecer el valor del input hidden con la hora seleccionada
                        document.getElementById('selectedHourInput').value = button.getAttribute('data-hour');
                    });
                });
            }
        }
  

        // Si el usuario ya tiene una cita, deshabilitar el botón de enviar cita
        if (citaExistente) {
            document.getElementById('enviarCitaBtn').disabled = true;
            alert('Ya tienes una cita programada.');
        }

           // Validar el formulario antes de enviarlo
           document.getElementById('citaForm').addEventListener('submit', function(event) {
            const selectedHour = document.getElementById('selectedHourInput').value;
            if (!selectedHour) {
                event.preventDefault(); // Evitar el envío del formulario
                alert('Por favor, seleccione una hora para la cita.');
            }
        });
    });
</script>
</body>
</html>