<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
    require_once "includes/header.php";
    require_once "includes/aside.php";
    

    $title = "Alta Donante";
                                        

    if (isset($_SESSION['usuario'])) {
            // Array para almacenar los mensajes de error
        $errores = array();

        // Verificar si se envió el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Inicializar las variables
            $nombre = isset($_POST["nombre"]) ? htmlspecialchars($_POST["nombre"]) : "";
            $apellido1 = isset($_POST["apellido1"]) ? htmlspecialchars($_POST["apellido1"]) : "";
            $apellido2 = isset($_POST["apellido2"]) ? htmlspecialchars($_POST["apellido2"]) : "";
            $nhc = isset($_POST["nhc"]) ? htmlspecialchars($_POST["nhc"]) : null; // Valor nulo
            $tel1 = isset($_POST["telef1"]) ? htmlspecialchars($_POST["telef1"]) : "";
            $tel2 = isset($_POST["telef2"]) ? htmlspecialchars($_POST["telef2"]) : ""; 
            $dni = isset($_POST["dni"]) ? htmlspecialchars($_POST["dni"]) : "";
            $ultimaDonacion = isset($_POST["uldon"]) ? htmlspecialchars($_POST["uldon"]): "";
            $recordatorio = isset($_POST["recordatorio"]) ? htmlspecialchars($_POST["recordatorio"]) : "";
            $observaciones = isset($_POST["observaciones"]) ? htmlspecialchars($_POST["observaciones"]) : "";
            $llamar = isset($_POST["llamar"])? htmlspecialchars($_POST["llamar"]) : ""; 
            $cipa = isset($_POST["cipa"]) ? htmlspecialchars($_POST["cipa"]) : null; //valor nulo
            $aceptaComunicaciones = isset($_POST["aceptaComunicaciones"]) ? htmlspecialchars($_POST["aceptaComunicaciones"]) : ""; 
            $fechaNacimiento = isset($_POST["fechaNacimiento"]) ? htmlspecialchars($_POST["fechaNacimiento"]) : "";
            $citable = isset($_POST["citable"]) ? htmlspecialchars($_POST["citable"]) : ""; 

            // echo "<pre>";
            // var_dump($llamar);
            // echo "</pre>";
            // echo "<pre>";
            // var_dump($aceptaComunicaciones);
            // echo "</pre>";
            // echo "<pre>";
            // var_dump($citable);
            // echo "</pre>";
            
            //si no se proporciona ninguna fecha de ultima donacion, se ingresara null como valor predeterminado
            if(empty($ultimaDonacion)){
                $ultimaDonacion = null;
            }
            // echo "<br>";
            // echo $ultimaDonacion;
            // echo "<br>";

            // Verificación de errores
            if (empty($nombre)) {
                $errores['nombre'] = "Debes ingresar el nombre.";
            }
            if (empty($apellido1)) {
                $errores['apellido1'] = "Debes ingresar el primer apellido.";
            }
            // if (empty($apellido2)) {
            //     $errores['apellido2'] = "Debes ingresar segundo apellido.";
            // }
            // if (empty($nhc)) {
            //     $errores['nhc'] = "Debes ingresar el numero de historial clinico.";
            // }
            if (empty($tel1)) {
                $errores['telefono'] = "Debes ingresar el teléfono.";
            } elseif (!preg_match("/^[0-9]{9}$/", $tel1)) { // Cambiar aquí
                $errores['telefono'] = "El teléfono debe contener 9 dígitos numéricos.";
            }
            if (empty($dni)) {
                $errores['dni'] = "Debes ingresar el DNI.";
            }
            if (empty($llamar)) {
                $errores['llamar'] = "Seleccionar si debe ser llamado.";
            }
            // if (empty($cipa)) {
            //     $errores['cipa'] = "Debe ingresar el numero de CIPA.";
            // }
            if (empty($aceptaComunicaciones)) {
                $errores['aceptaComunicaciones'] = "Debes ingresar si acepta comunicaciones.";
            }
            if (empty($fechaNacimiento)) {
                $errores['fechaNacimiento'] = "Debes ingresar la fecha de nacimiento.";
            }


            // Si no hay errores, puedes continuar con el procesamiento de los datos
            if (empty($errores)) {
                try {
                    require "config/Conexion.php";
                    $conexion = new Conexion();
                    $pdo = $conexion->getConexion();
                    $queryValidar = "SELECT id_donante FROM donante WHERE dni= :dniDonante";
                    $stmt = $pdo->prepare($queryValidar);
                    $stmt->bindParam(':dniDonante', $dni, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($resultado) {
                        $errorDuplicado = "<div class='alert alert-danger' role='alert'>Este usuario ya existe en la Base de datos.</div>";
                    }else {
                        require_once "Donante.php";
                        //require_once "config/Conexion.php";
                        //$conexion = new Conexion();
                        $donante = new Donante();
                        $resultado = $donante->altaDonante($nombre, $apellido1, $apellido2, $nhc, $tel1, $tel2, $dni, $ultimaDonacion , $recordatorio, $observaciones, $llamar, $cipa, $aceptaComunicaciones, $fechaNacimiento, $citable);

                        if ($resultado) {
                            $opeExitosa = "<div class='alert alert-success' role='alert'>El paciente ha sido dado de alta correctamente:<br>
                            <strong>Nombre:</strong> $nombre<br>
                            <strong>Apellidos:</strong> $apellido1 $apellido2<br>
                            <strong>NHC:</strong> $nhc<br>
                            <strong>Teléfono:</strong> $tel1<br>
                            <strong>Teléfono:</strong> $tel2<br>
                            <strong>DNI:</strong> $dni<br>
                            <strong>Ultima donación:</strong> $ultimaDonacion<br>
                            <strong>Recordatorio:</strong> $recordatorio<br>
                            <strong>Observaciones:</strong> $observaciones<br>
                            <strong>Llamar:</strong> $llamar<br>
                            <strong>CIPA:</strong> $cipa<br>
                            <strong>Acepta Comunicaciones:</strong> $aceptaComunicaciones<br>
                            <strong>Fecha nacimiento:</strong> $fechaNacimiento<br>
                            <strong>Citable:</strong> $citable<br>
                        </div>";
                        $id_usuario =$_SESSION['usuario'];
                        $operacion = "Alta donante";
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $observacion = "El donante con DNI $dni ha sido dado de alta";
                        $url =  $_SERVER['REQUEST_URI'];
                        
                        $query_log_alta = "INSERT INTO log (id_usuario, operacion, ip, observacion, url) VALUES (:id_usuario, :operacion, :ip, :observacion, :url)";
                        $statement_log_alta = $pdo->prepare($query_log_alta);
                        $statement_log_alta->bindParam(':id_usuario', $id_usuario);
                        $statement_log_alta->bindParam(':operacion', $operacion);
                        $statement_log_alta->bindParam(':ip', $ip);
                        $statement_log_alta->bindParam(':observacion', $observacion);
                        $statement_log_alta->bindParam(':url', $url);
                        $statement_log_alta->execute();
                        } else {
                            $opeExitosa = "<div class='alert alert-danger' role='alert'>El paciente no ha sido dado de alta<br>"
                            ;
                        }



                    }
                } catch (PDOException $e) {
                    // Manejar el error de la consulta SQL
                    $errorVerfic = "Error: " . $e->getMessage();
                }
            }

        }

    } else {
        // Si no hay sesión de usuario, mostrar mensaje de error
        echo "No se ha iniciado sesión.";
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

        <!-- Aqui Empieza el contenedor principal -->
        <!-- <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol> -->
        <!-- INICIO TARJETAS -->
       

        <!-- FIN TARJETAS -->

        <!-- CONTENIDO EDITABLE INICIO -->
        <!-- <div class="card-body"> -->
            

        <!-- CONTENIDO EDITABLE FIN -->
        <div class="container" style="margin-top: 4rem; margin-bottom: 2rem;">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Alta de Donante</h2>
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
                            <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>"  onsubmit="return validarFormulario()" id="miFormulario">
                                <div class="d-flex">
                                    <p class="ms-auto text-danger">* Campos obligatorios</p>
                                </div> 
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre*: </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($nombre)? htmlspecialchars($nombre): "" ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apellido1" class="form-label">Primer apellido*: </label>
                                    <input type="text" class="form-control" id="apellido1" name="apellido1" value="<?php echo isset($apellido1)? htmlspecialchars($apellido1): ""?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="apellido2" class="form-label">Segundo apellido: </label>
                                    <input type="text" class="form-control" id="apellido2" name="apellido2" value="<?php echo isset($apellido2)? htmlspecialchars($apellido2): ""?>">
                                </div>
                                <div class="mb-3">
                                    <label for="nhc" class="form-label">NHC: </label>
                                    <input type="text" class="form-control" id="nhc" name="nhc" value="<?php echo isset($nhc)? htmlspecialchars($nhc): "" ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="telef1" class="form-label">Telefono 1*: </label>
                                    <input type="text" class="form-control" id="telef1" name="telef1" value="<?php echo isset($tel1)? htmlspecialchars($tel1): "" ?>"  required>
                                </div>
                                <div class="mb-3">
                                    <label for="telef2" class="form-label">Telefono 2: </label>
                                    <input type="text" class="form-control" id="telef2" name="telef2" value="<?php echo isset($tel2)? htmlspecialchars($tel2): "" ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI*: </label>
                                    <input type="text" class="form-control" id="dni" name="dni" value="<?php echo isset($dni)? htmlspecialchars($dni): "" ?>" required  onblur="comprobarDNI()">
                                    <span id="dniError" class="text-danger"></span> <!-- Mensaje de error -->
                                </div>
                                <div class="mb-3">
                                    <label for="uldon" class="form-label">Ultima donación: </label>
                                    <input type="date" class="form-control" id="uldon" name="uldon" value="<?php echo isset($ultimaDonacion)? htmlspecialchars($ultimaDonacion): "" ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="recordatorio" class="form-label">Recordatorio: </label>
                                    <input type="text" class="form-control" id="recordatorio" name="recordatorio" value="<?php echo isset($recordatorio)? htmlspecialchars($recordatorio): "" ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="observasiones" class="form-label">Observaciones: </label>
                                    <input type="text" class="form-control" id="observasiones" name="observasiones" value="<?php echo isset($observaciones)? htmlspecialchars($observaciones): "" ?>">
                                </div>
                                
                                <p>Llamar*: </p>
                                <div class="mb-3 form-check">
                                    <input type="radio" class="form-check-input" id="llamarSi" name="llamar" value="Si" <?php if (isset($llamar) && $llamar === "Si") echo 'checked'; ?>>
                                        <label for="llamarSi" class="form-check-label">Si</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="radio" class="form-check-input" id="llamarNo" name="llamar" value="No" <?php if (isset($llamar) && $llamar === "No") echo 'checked'; ?>>
                                        <label for="llamarNo" class="form-check-label">No</label>
                                </div>
                                <div class="mb-3">
                                    <label for="cipa" class="form-label">CIPA: </label>
                                    <input type="text" class="form-control" id="cipa" name="cipa" value="<?php echo isset($cipa)? htmlspecialchars($cipa): "" ?>"> 
                                </div>
                                <p>Acepta comunicaciones*: </p>
                                <div class="mb-3 form-check">
                                    <input type="radio" class="form-check-input" id="radioSi" name="aceptaComunicaciones" value="Si" <?php if (isset($aceptaComunicaciones) && $aceptaComunicaciones === "Si") echo 'checked'; ?>>
                                        <label for="radioSi" class="form-check-label">Si</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="radio" class="form-check-input" id="radioNo" name="aceptaComunicaciones" value="No" <?php if (isset($aceptaComunicaciones) && $aceptaComunicaciones === "No") echo 'checked'; ?>>
                                    <label for="radioNo" class="form-check-label">No</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input class="form-control" id="fechaNacimiento" name="fechaNacimiento" type="date" placeholder="Fecha de Nacimiento" value="<?php echo isset($fechaNacimiento)? htmlspecialchars($fechaNacimiento): "" ?>" required />
                                    <label for="fechaNacimiento">Fecha de Nacimiento*: </label>
                                </div>
                                <p>Citable: </p>
                                <div class="mb-3 form-check">
                                    <input type="radio" class="form-check-input" id="citableSi" name="citable" value="Si" <?php if (isset($citable) && $citable === "Si") echo 'checked'; ?>>
                                        <label for="citableSi" class="form-check-label">Si</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="radio" class="form-check-input" id="citableNo" name="citable" value="No"  <?php if (isset($citable) && $citable === "No") echo 'checked'; ?>>
                                        <label for="citableNo" class="form-check-label">No</label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mx-3">Enviar</button>
                                    <button type="text" class="btn btn-primary" onclick="limpiarFormulario()">Limpiar</button>
                                </div>
                                <div class="text-center">
                                    
                                </div>
                            </form>
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
  <script>
    function validarDNI(dni) {
        // Expresión regular para validar el formato del DNI
        const dniRegex = /^[0-9]{8}[A-Z]$/i;

        if (!dniRegex.test(dni)) {
            return false; // El formato es incorrecto
        }

        // Obtener el número (primeros 8 caracteres) y la letra del DNI (último carácter)
        const numero = dni.slice(0, 8);
        const letra = dni.slice(-1).toUpperCase();

        // Tabla de letras válidas en el DNI
        const letrasValidas = "TRWAGMYFPDXBNJZSQVHLCKE";
        
        // Calcular la letra correcta
        const letraCorrecta = letrasValidas.charAt(parseInt(numero, 10) % 23);

        // Comparar la letra proporcionada con la letra correcta
        return letra === letraCorrecta;
    }

    function comprobarDNI() {
        const dni = document.getElementById('dni').value;
        const dniError = document.getElementById('dniError');

        if (validarDNI(dni)) {
            dniError.textContent = ""; // Limpia el mensaje de error si el DNI es válido
        } else {
            dniError.textContent = "DNI inválido, verifica el formato o la letra.";
        }
    }

    function validarFormulario() {
        const dni = document.getElementById('dni').value;
        const telef1 = document.getElementById('telef1').value;
        const telef2 = document.getElementById('telef2').value;
        const telefonoRegex = /^[0-9]{9}$/;
        if (!telefonoRegex.test(telef1)) {
            alert("El teléfono 1 debe tener exactamente 9 dígitos.");
            return false; // El formato es incorrecto
        }

        if (isset(!telefonoRegex.test(telef2))) {
            alert("El teléfono 2 debe tener exactamente 9 dígitos.");
            return false; // El formato es incorrecto
        }

        if (validarDNI(dni)) {
            return true; // Permitir envío del formulario
        } else {
            alert("DNI inválido, verifica el formato o la letra.");
            return false; // Evitar envío del formulario
        }
    }

    function limpiarFormulario() {
        // Obtener el formulario y restablecer sus campos
        document.getElementById('miFormulario').reset();

        // Limpiar los mensajes de error
        document.getElementById('dniError').textContent = "";
    }
</script>


</div>
