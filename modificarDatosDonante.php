<?php
session_start(); 
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
$title = "Editar donante";
require_once "includes/header.php";
require_once "includes/aside.php";
require_once "config/Conexion.php";
require "Donante.php";
require_once 'Log.php';
              
    //Recoger datos por formulario actual------------------------------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['guardar_cambios'])) {
        // Obtener los datos del formulario
        $id_donante = htmlspecialchars($_POST['id']);
        $nombre = htmlspecialchars($_POST['nombre']);
        $apellido1 = htmlspecialchars($_POST['apellido1']);
        $apellido2 = htmlspecialchars($_POST['apellido2']);
        $nhc = htmlspecialchars($_POST['nhc']);
        $telef1 = htmlspecialchars($_POST['telef1']);
        $telef2 = htmlspecialchars($_POST['telef2']);
        $dni = htmlspecialchars($_POST['dni']);
        $ultima_donacion = !empty($_POST['ultima_donacion']) ? htmlspecialchars($_POST['ultima_donacion']) : null;
        $recordatorio = htmlspecialchars($_POST['recordatorio']);
        $observaciones = htmlspecialchars($_POST['observaciones']);
        $llamar = isset($_POST['llamar']) ? htmlspecialchars($_POST['llamar']) : '';
        $cipa = htmlspecialchars($_POST['cipa']);
        $acepto_comunicacion = isset($_POST['acepto_comunicacion']) ? htmlspecialchars($_POST['acepto_comunicacion']) : '';
        $fecha_nacimiento = htmlspecialchars($_POST['fecha_nacimiento']);
        $citable = isset($_POST['citable']) ? htmlspecialchars($_POST['citable']) : '';

        // Crear una instancia de la clase Donantes
        $donante = new Donante();
        

        // Editar el donante con los datos proporcionados
        $resultado = $donante->EditarDonante($id_donante, $nombre, $apellido1, $apellido2, $nhc, $telef1, $telef2, $dni, $ultima_donacion, $recordatorio, $observaciones, $llamar, $cipa, $acepto_comunicacion, $fecha_nacimiento, $citable);
       
        // Obtener los datos actualizados del paciente
        $datos_paciente = $donante->ObtenerDonantePorId($id_donante);

        if($resultado){
            $cambios_realizados = "<div class='alert alert-success'> Cambios realizados!</div>"; 
       }else{
        $cambios_realizados = "<div class='alert alert-warning'> Pendiente actualizar!</div>";        
       }
     
        // echo "<br>";
        // print_r($datos_paciente);
        // echo "<br>";

    } 
    else { // Obtener el ID del paciente de la URL----------------------------------------------
        if(isset($_GET['id'])){  
        // Obtener el ID del paciente de la URL
        $id = htmlspecialchars($_GET['id']);
        //Crear una instancia de la clase Donante
        $donante = new Donante();

        //Obtener los datos del paciente por su ID
        $datos_paciente = $donante->ObtenerDonantePorId($id);
        // echo "<br>";
        // print_r($datos_paciente);
        // echo "<br>";
    
        //Verificar si se han obtenido datos del paciente
        if (isset($datos_paciente) && !empty($datos_paciente)) {
            // Extraer los datos del paciente
            $id_donante = $datos_paciente['id_donante'];
            $nombre = $datos_paciente['nombre'];
            $apellido1 = $datos_paciente['apellido1'];
            $apellido2 = $datos_paciente['apellido2'];
            $nhc = $datos_paciente['nhc'];
            $telef1 = $datos_paciente['telef1'];
            $telef2 = $datos_paciente['telef2'];
            $dni = $datos_paciente['dni'];
            $ultima_donacion = $datos_paciente['ultima_donacion'];
            $recordatorio = $datos_paciente['recordatorio'];
            $observaciones = $datos_paciente['observaciones'];
            $llamar = $datos_paciente['llamar'];
            $cipa = $datos_paciente['cipa'];
            $acepto_comunicacion = $datos_paciente['acepto_comunicacion'];
            $fecha_nacimiento = $datos_paciente['fecha_nacimiento'];
            $citable = $datos_paciente['citable'];

            // Editar el donante con los datos proporcionados
            $resultado = $donante->EditarDonante($id_donante, $nombre, $apellido1, $apellido2, $nhc, $telef1, $telef2, $dni, $ultima_donacion, $recordatorio, $observaciones, $llamar, $cipa, $acepto_comunicacion, $fecha_nacimiento, $citable);
            // var_dump($resultado);
            // Obtener los datos actualizados del paciente
            $datos_paciente = $donante->ObtenerDonantePorId($id_donante);
            if($resultado){
                $cambios_realizados = "<div class='alert alert-success'> Cambios realizados!</div>";
                require_once("Log.php");
                $log_usuario = new Logs();
                $evento = "Donante con DNI ".$dni. " Modificado";
                $log_usuario->crear_log($_SESSION['usuario'],$evento);
            }else{
                $cambios_realizados = "<div class='alert alert-warning'> Pendiente de actualizar!</div>";        
           }
        } else {

            // Si no se obtienen datos del paciente, mostrar un mensaje de error o redirigir a una página de error
            $errores[] = " No se ha encontrado usuario";
        }
    }
}

    
    ?>
<style>
    /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */
    .card-header{
        background-color: #00ABE4;
    }
    .card-body{
       
        background-color: #E9F1FA;
    }
    
</style>

 <div id="layoutSidenav_content">
    <!-- MAIN INICIO -->
    <main>
    <div class="container-fluid px-4">
        <!-- <div class="card-body"> -->
        <!-- CONTENIDO EDITABLE FIN -->
        <div class="container" style="margin-top: 4rem; margin-bottom: 2rem;">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Modificar datos Donante</h2>
                            <!-- Mostrar errores -->
                            <?php if (!empty($errores)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        <?php foreach ($errores as $error): ?>
                                            <li>
                                                <?php echo $error; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif;
                            if (!empty($errorDuplicado)) {
                                echo $errorDuplicado;
                                $errorDuplicado = "";
                            } ?>
                            <?php
                            if (!empty($opeExitosa)) {
                                echo $opeExitosa;
                            }
                            ?>
                        </div>
                        <div class="card-body">
                            <!-- Mostrar mensaje de confirmación de cambios -->
                            <?php echo $cambios_realizados; ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                        <input type="hidden" name="id" value="<?php echo $id_donante; ?>">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre*:</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre"
                                                value="<?php echo $nombre; ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="apellido1" class="form-label">Primer Apellido*:</label>
                                            <input type="text" class="form-control" id="apellido1" name="apellido1"
                                                value="<?php echo $apellido1; ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="apellido2" class="form-label">Segundo Apellido:</label>
                                            <input type="text" class="form-control" id="apellido2" name="apellido2"
                                                value="<?php echo $apellido2; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="nhc" class="form-label">NHC:</label>
                                            <input type="text" class="form-control" id="nhc" name="nhc"
                                                value="<?php echo $nhc; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="telef1" class="form-label">Teléfono 1:</label>
                                            <input type="text" class="form-control" id="telef1" name="telef1"
                                                value="<?php echo $telef1; ?>" pattern="[0-9]{9}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="telef2" class="form-label">Teléfono 2:</label>
                                            <input type="text" class="form-control" id="telef2" name="telef2"
                                                value="<?php echo $telef2; ?>" >
                                        </div>

                                        <div class="mb-3">
                                            <label for="dni" class="form-label">DNI*:</label>
                                            <input type="text" class="form-control" id="dni" name="dni"
                                                value="<?php echo $dni; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dni" class="form-label">Ultima donación:</label>
                                            <input type="date" class="form-control" id="ultima_donacion" name="ultima_donacion"
                                                value="<?php echo $ultima_donacion; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="dni" class="form-label">Recordatorio:</label>
                                            <input type="text" class="form-control" id="recordatorio" name="recordatorio"
                                                value="<?php echo $recordatorio; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="dni" class="form-label">Observaciones:</label>
                                            <input type="text" class="form-control" id="observaciones" name="observaciones"
                                                value="<?php echo $observaciones; ?>">
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="llamar"
                                             name="llamar" value="<?php echo isset($llamar)? htmlspecialchars($llamar): "" ?>">
                                            <label class="form-check-label" for="llamar">LLamar</label>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cipa" class="form-label">CIPA:</label>
                                            <input type="text" class="form-control" id="cipa" name="cipa"
                                                value="<?php echo $cipa; ?>" >
                                        </div>
                                        <p>Acepta comunicaciones: </p>
                                        <div class="mb-3 form-check">
                                            <input type="radio" class="form-check-input" id="radioSi"
                                                name="aceptaComunicaciones" value="<?php echo isset($acepto_comunicacion)? htmlspecialchars($acepto_comunicacion): "" ?>">
                                                <label for="radioSi" class="form-check-label">Si</label>
                                        </div>
                                        <div class="mb-3 form-check">
                                            <input type="radio" class="form-check-input" id="radioNo"
                                                name="aceptaComunicaciones" value="No">
                                                <label for="radioNo" class="form-check-label">No</label>
                                        </div>

                                        <div class="mb-3">
                                            <label for="fechaNa" class="form-label">Fecha de Nacimiento*:</label>
                                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                                value="<?php echo $fecha_nacimiento; ?>" required>
                                        </div>

                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="citable"
                                                name="citable" value="<?php echo isset($citable)? htmlspecialchars($citable): "" ?>">
                                            <label class="form-check-label" for="citable">Citable</label>
                                        </div>
                                        <P>* Campos obligatorios</P>

                                        <button type="submit" name="guardar_cambios" class="btn btn-primary">Guardar cambios</button>
                                    </form>
                                    <div class="mt-3">
                                    <form action="editarDonante.php" method="POST">
                                        <button type="submit" name="cancelar" class="btn btn-primary">cancelar</button>
                                    </form>
                                    </div>
                        </div>
                    </div>
               </div>            
            </div>
            <!-- </div> -->
        </div>
    </div>
    
    </main>
    <!-- MAIN FINAL -->
    <?php 
    require_once "includes/footer.php";
    ?>
</div>