<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once "includes/header.php";
require_once "includes/aside.php";
?>

<style>
    /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */

    /* Limita el ancho del calendario y lo centra */
    .calendar {
        max-width: 700px;
        margin: 50px auto;
    }
    /* Alinea los elementos del control (botones de navegacion, selector mes/año) de forma horizontal y con espacio entre ellos */
    .calendar-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    /* Define el espacio de celda de dia, añade el fondo gris, hace que cada dia sea cliclable al selector cursor:pointer */
    .calendar-day {
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }
    /* Estiliza la fila de encabezado, usando fondo oscuro y texto blanco */
    .calendar-day-header {
        background-color: #343a40;
        color: white;
        text-align: center;
        font-weight: bold;
        max-width: 600px;
    }
    /* Se usa para citas o eventos dentro de cada dia, fondo azul, texto blanco, el tamaño de la fuente se reduce ligeramente */
    .appointment {
        background-color: #0d6efd;
        color: white;
        font-size: 0.9em;
        padding: 5px;
        border-radius: 4px;
        margin-top: 5px;
    }
    /* Estiliza los dias deshabilitados, aplica fondo gris, aplica not-allowed para indicar que esos dias no son interactivos */
    .disabled-day {
        background-color: #e9ecef; /* Gris claro para fechas pasadas */
        color: #6c757d; /* Color de texto deshabilitado */
        cursor: not-allowed; /* Cursor deshabilitado */
    }
   
</style>

<!-- CONTENEDORES COMUNES INICIO -->
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
        <!-- CONTENEDORES COMUNES FIN --> 

            <div class="container calendar">
                <!-- Controles para cambiar de mes -->
                <div class="calendar-controls">
                    <button id="prevMonth" class="btn btn-primary">&lt; Anterior</button>
                    <strong><span id="monthYear"></span></strong>
                    <button id="nextMonth" class="btn btn-primary">Siguiente &gt;</button>
                </div>

                <div class="row">
                    <!-- Encabezados de los días de la semana -->
                    <div class="col calendar-day-header">Dom</div>
                    <div class="col calendar-day-header">Lun</div>
                    <div class="col calendar-day-header">Mar</div>
                    <div class="col calendar-day-header">Mié</div>
                    <div class="col calendar-day-header">Jue</div>
                    <div class="col calendar-day-header">Vie</div>
                    <div class="col calendar-day-header">Sáb</div>
                </div>

                <div class="row" id="calendar-body">
                    <!-- Los días del calendario se generarán dinámicamente aquí -->
                </div>
            </div>

            <!-- Modal para agendar una cita -->
            <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="appointmentModalLabel">Agendar una cita</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulario de cita -->
                            <!-- <form id="appointmentForm" action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="POST"> -->
                            <form id="appointmentForm" action="horarios_disponibles2.php" method="POST" onsubmit="return validarFormulario()">

                                <div class="mb-3">
                                    <label for="appointmentName" class="form-label">Nombre *:</label>
                                    <input type="text" class="form-control" id="appointmentName" name="appointmentName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="appointmentName1" class="form-label">Primer apellido *:</label>
                                    <input type="text" class="form-control" id="appointmentLastName1" name="appointmentLastName1" required>
                                </div>
                                <div class="mb-3">
                                    <label for="appointmentName2" class="form-label">Segundo apellido *:</label>
                                    <input type="text" class="form-control" id="appointmentLastName2" name="appointmentLastName2" required>
                                </div>
                                <div class="mb-3">
                                    <label for="appointmentName" class="form-label">DNI *:</label>
                                    <input type="text" class="form-control" id="appointmentDNI" name="appointmentDNI" required>
                                    <span id="dniError" class="text-danger"></span> <!-- Mensaje de error -->
                                </div>
                                <div class="mb-3">
                                    <label for="appointmentName" class="form-label">Telefono *:</label>
                                    <input type="tel" class="form-control" id="appointmentPhone" name="appointmentPhone" required>
                                </div>
                                <!-- Campo select para las máquinas disponibles-->
                                <div class="mb-3">
                                    <label for="appointmentMachine" class="form-label">Máquina *:</label>
                                    <select class="form-select" id="appointmentMachine" name="appointmentMachine" required>
                                        <option value="">Seleccione una máquina</option>
                                        <?php
                                        // Consulta para obtener las máquinas activas desde la base de datos
                                        try {
                                            require_once "config/Conexion.php";
                                            $pdo = (new Conexion())->getConexion();
                                            $query = "SELECT id_maquina, descripcion_maquina FROM maquina WHERE activa = 'SI'";
                                            $stmt = $pdo->query($query);

                                            // Mostrar las opciones en el select
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . htmlspecialchars($row['id_maquina']) . "'>" . htmlspecialchars($row['descripcion_maquina']) . "</option>";
                                            }
                                        } catch (PDOException $e) {
                                            echo "<option value=''>Error al cargar las máquinas</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="appointmentTime" class="form-label">Hora de la cita</label>
                                    <select class="form-select" id="appointmentTime" name="appointmentTime" required>
                                        <option value="09:00">09:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">01:00 PM</option>
                                        <option value="14:00">02:00 PM</option>
                                    </select>
                                </div> -->
                                <input type="hidden" id="selectedDate" name="selectedDate"> <!--INPUT QUE TE RECOGE LA FECHA-->
                                <button type="submit" class="btn btn-primary" name="enviarDatos">Guardar cita</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
    </main>
    <?php
    require_once "includes/footer.php";
    ?>
</div>
<!-- Bootstrap JS y Popper.js -->
<script src="js/horarios_disponibles1.js"> </script>
<script>
    function validarDNI(dni) {
        // Expresión regular para validar el formato del DNI (la i al final indica que no distingue entre mayusculas y minusculas)
        const dniRegex = /^[0-9]{8}[A-Za-z]{1}$/i;

        if (!dniRegex.test(dni)) {
            return false; // El formato es incorrecto
        } else{
            return true;
        }
        
        // const numero = dni.slice(0, 8);
        // const letra = dni.slice(-1).toUpperCase();

        // // Tabla de letras válidas en el DNI
        // const letrasValidas = "TRWAGMYFPDXBNJZSQVHLCKE";
        
        // // Calcular la letra correcta
        // const letraCorrecta = letrasValidas.charAt(parseInt(numero, 10) % 23);

        // Comparar la letra proporcionada con la letra correcta
        // return letra === letraCorrecta;
    }

    function comprobarDNI() {
        const dni = document.getElementById('appointmentDNI').value;
        const dniError = document.getElementById('dniError');

        if (validarDNI(dni)) {
            dniError.textContent = ""; // Limpia el mensaje de error si el DNI es válido
        } else {
            dniError.textContent = "DNI inválido, verifica el formato o la letra.";
        }
    }

    function validarFormulario() {
        const dni = document.getElementById('appointmentDNI').value;
        const telefono = document.getElementById('appointmentPhone').value;

        const telefonoRegex = /^[0-9]{9}$/;
        if (!telefonoRegex.test(telefono)) {
            alert("El teléfono debe tener exactamente 9 dígitos.");
            return false; // El formato es incorrecto
        }

        if (validarDNI(dni)) {
            return true; // Permitir envío del formulario
        } else {
            alert("DNI inválido, verifica el formato o la letra.");
            return false; // Evitar envío del formulario
        }
    }
    </script>