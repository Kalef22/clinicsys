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
            $dni = isset($_POST["dni"]) ? htmlspecialchars(stripslashes($_POST["dni"])) : "";
            $nombre = isset($_POST["nombre"]) ? htmlspecialchars(stripslashes($_POST["nombre"])) : "";
            $apellido1 = isset($_POST["apellido1"]) ? htmlspecialchars(stripslashes($_POST["apellido1"])) : "";
            $apellido2 = isset($_POST["apellido2"]) ? htmlspecialchars(stripslashes($_POST["apellido2"])) : "";
            $id_rol = isset($_POST["id_rol"]) ? htmlspecialchars(stripslashes($_POST["id_rol"])) : "";
            //si no se proporciona ninguna fecha de ultima donacion, se ingresara null como valor predeterminado
          
            // Verificación de errores
            if (empty($nombre)) {
                $errores['nombre'] = "Debes ingresar el nombre.";
            }
            if (empty($apellido1)) {
                $errores['apellido1'] = "Debes ingresar el primer apellido.";
            }
            if (empty($apellido2)) {
                $errores['apellido2'] = "Debes ingresar segundo apellido.";
            }
            if (empty($id_rol)) {
                $errores['id_rol'] = "Debes ingresar el numero de rol.";
            }
            if (empty($dni)) {
                $errores['DNI'] = "Debes ingresar el DNI.";
            } elseif (!preg_match("/^[0-9]{8}[A-Za-z]$/", $dni)) { // Cambiar aquí
                $errores['DNI'] = "El dni debe ser valido.";
            }
          


            // Si no hay errores, puedes continuar con el procesamiento de los datos
            if (empty($errores)) {
                try {
                    require "config/Conexion.php";
                    $pdo = (new Conexion())->getConexion();
                    $queryValidar = "SELECT id_usuario FROM usuario WHERE id_usuario= :dni";
                    $stmt = $pdo->prepare($queryValidar);
                    $stmt->bindParam(':dni', $dni, PDO::PARAM_STR);
                    $stmt->execute();
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($resultado) {
                        $errorDuplicado = "<div class='alert alert-danger' role='alert'>Este usuario ya existe en la Base de datos.</div>";
                    }else {
                        require_once "Usuario.php";
                        //require_once "config/Conexion.php";
                        // $conexion = (new Conexion())->getConexion();
                        $usuario = new Usuario();
                        $resultado = $usuario->crearUsuario($dni, $nombre, $apellido1, $apellido2, $id_rol);

                        if ($resultado) {
                            $opeExitosa = "<div class='alert alert-success' role='alert'>El Usuario ha sido dado de alta correctamente:<br>
                            <strong>DNI:</strong> $dni<br>
                            <strong>Nombre:</strong> $nombre<br>
                            <strong>Primer apellido:</strong> $apellido1<br>
                            <strong>Segundo apellido:</strong> $apellido2<br>
                            <strong>Id rol:</strong> $id_rol<br>
                        </div>";
                        } else {
                            $opeExitosa = "<div class='alert alert-danger' role='alert'>El Usuario no ha sido dado de alta<br>"
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
                            <h2 class="text-center">Alta Usuario</h2>
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
                            <form method="post" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return validarFormulario()" id="miFormulario2">
                                <div class="d-flex">
                                    <p class="text-danger ms-auto">* Campos obligatorios</p>
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
                                    <label for="apellido2" class="form-label">Segundo apellido*: </label>
                                    <input type="text" class="form-control" id="apellido2" name="apellido2" value="<?php echo isset($apellido2)? htmlspecialchars($apellido2): ""?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI*: </label>
                                    <input type="text" class="form-control" id="dni" name="dni" value="<?php echo isset($nombre)? htmlspecialchars($dni): "" ?>" required onblur="comprobarDNI()">
                                    <span id="dniError" class="text-danger"></span> <!-- Mensaje de error -->
                                </div>
                                <!-- <div class="mb-3">
                                    <label for="id_rol" class="form-label">Id rol: </label>
                                    <input type="number" class="form-control" id="id_rol" name="id_rol" min="0" max="3" value="<?php echo isset($id_rol)? htmlspecialchars($id_rol): "" ?>">
                                </div>  -->
                                <div class="mb-3">
                                    <label for="id_rol" class="form-label">Rol*: </label>
                                    <select class="form-select" name="id_rol" id="id_rol">
                                        <option value="">Seleccione un rol</option>
                                        <?php 
                                        try{
                                            require_once "config/Conexion.php";
                                            $pdo = (new Conexion())->getConexion();
                                            $query = "SELECT id_rol, descripcion_rol FROM rol";
                                            $stmt = $pdo->query($query);
                                            
                                            //Mostrar las opciones en el select
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                echo "<option value ='" .htmlspecialchars($row['id_rol'])."'>". htmlspecialchars($row['descripcion_rol']). "</option>";
                                            }

                                        }catch(PDOException $e){
                                            echo "<option value=''>Error al cargar los roles</option>";
                                        }
                                    

                                        ?>
                                    </select>
                                </div>        
                            <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                    <button type="text" class="btn btn-primary" onclick="limpiarFormulario()">Limpiar</button>
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
</div>
<script>
    function validarDNI(dni) {
        // Expresión regular para validar el formato del DNI
        const dniRegex = /^[0-9]{8}[A-Za-z]{1}$/i;

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

        if (validarDNI(dni)) {
            return true; // Permitir envío del formulario
        } else {
            alert("DNI inválido, verifica el formato o la letra.");
            return false; // Evitar envío del formulario
        }
    }

    function limpiarFormulario() {
        // Obtener el formulario y restablecer sus campos
        document.getElementById('miFormulario2').reset();

        // Limpiar los mensajes de error
        document.getElementById('dniError').textContent = "";
    }
</script>