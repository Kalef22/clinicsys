<?php
session_start(); 
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
$title = "Editar usuario";
require_once "includes/header.php";
require_once "includes/aside.php";
require_once "config/Conexion.php";
require "Usuario.php";
$usuario = new Usuario();
$cambios_realizados="";
$errores=[];

    //Recoger datos por formulario actual------------------------------------------------------------------------
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['guardar_cambios'])) {
        // Obtener los datos del formulario
        $id_usuario = htmlspecialchars(stripcslashes($_POST['id_usuario']));
        $nombre = htmlspecialchars(stripcslashes($_POST['nombre']));
        $apellido1 = htmlspecialchars(stripcslashes($_POST['apellido1']));
        $apellido2 = htmlspecialchars(stripcslashes($_POST['apellido2']));
        $id_rol = htmlspecialchars(stripcslashes($_POST['id_rol']));
        // Crear una instancia de la clase Donantes
        

        // Obtener los datos actualizados del paciente
        $datos_actuales = $usuario->obtenerUsuarioPorId($id_usuario);

        
        //verificar si hay cambios en los datos antes de actualizar
        if($id_usuario !== $datos_actuales['id_usuario'] || 
                    $nombre !== $datos_actuales['nombre'] || 
                    $apellido1 !== $datos_actuales['apellido1'] || 
                    $apellido2 !== $datos_actuales['apellido2'] || 
                    $id_rol !== $datos_actuales['id_rol']){

            //solo si hay cambios llamar a la funcion editarUsuario
            $resultado = $usuario->editarUsuario($id_usuario, $nombre, $apellido1, $apellido2, $id_rol);
            
            if($resultado){
                $cambios_realizados = "<div class='alert alert-success'> Cambios realizados!</div>"; 
            }else{
            $cambios_realizados = "<div class='alert alert-warning'> Pendiente actualizar!</div>";        
            }
            
        } else{
            $cambios_realizados = "<div class='alert alert-info'>No se detectaron cambios.</div>";
        }
        // echo "<br>";
        // print_r($datos_paciente);
        // echo "<br>";

    } 
    else { // Obtener el ID del paciente de la URL----------------------------------------------
        if(isset($_GET['id'])){  
        // Obtener el ID del paciente de la URL
        $id_usuario = htmlspecialchars(stripcslashes($_GET['id']));
        //Crear una instancia de la clase Usuario
        $usuario = new Usuario();

        //Obtener los datos del usuario por su ID
        $datos_usuario = $usuario->obtenerUsuarioPorId($id_usuario);

        //Verificar si se han obtenido datos del paciente
        if (isset($datos_usuario) && !empty($datos_usuario)) {
            // Extraer los datos del paciente
            $id_usuario = $datos_usuario['id_usuario'];
            $nombre = $datos_usuario['nombre'];
            $apellido1 = $datos_usuario['apellido1'];
            $apellido2 = $datos_usuario['apellido2'];
            $id_rol = $datos_usuario['id_rol'];

            // Obtener los datos actualizados del paciente
            $datos_actuales = $usuario->obtenerUsuarioPorId($id_usuario);

            //verificar si hay cambios en los datos antes de actualizar
            if($id_usuario !== $datos_actuales['id_usuario'] || 
            $nombre !== $datos_actuales['nombre'] || 
            $apellido1 !== $datos_actuales['apellido1'] || 
            $apellido2 !== $datos_actuales['apellido2'] || 
            $id_rol !== $datos_actuales['id_rol']){

            //solo si hay cambios llamar a la funcion editarUsuario
            $resultado = $usuario->editarUsuario($id_usuario, $nombre, $apellido1, $apellido2, $id_rol);

                if($resultado){
                    $cambios_realizados = "<div class='alert alert-success'> Cambios realizados!</div>"; 
                }else{
                    $cambios_realizados = "<div class='alert alert-warning'> Pendiente actualizar!</div>";        
                }

            } else{
                $cambios_realizados = "<div class='alert alert-info'>No se detectaron cambios.</div>";
            }






          
        //     //Editar el donante con los datos proporcionados
        //     $resultado = $usuario->editarUsuario($id_usuario, $nombre, $apellido1, $apellido2, $id_rol);
          
        //     if($resultado){
        //         $cambios_realizados = "<div class='alert alert-success'> Cambios realizados!</div>";
        //     }else{
        //         $cambios_realizados = "<div class='alert alert-warning'> Pendiente de actualizar!</div>";        
        //    }
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
                            <h2 class="text-center">Modificar datos usuario</h2>
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
                                <div class="d-flex">  
                                <P class="ms-auto text-danger">* Campos obligatorios</P>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $id_usuario; ?>">
                                    <div class="mb-3">
                                        <label for="id_usuario" class="form-label">DNI*:</label>
                                        <input type="text" class="form-control" id="id_usuario" name="id_usuario"
                                            value="<?php echo $id_usuario; ?>" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre*:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                            value="<?php echo $nombre; ?>" required readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="apellido1" class="form-label">Primer Apellido*:</label>
                                        <input type="text" class="form-control" id="apellido1" name="apellido1"
                                            value="<?php echo $apellido1; ?>" required readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="apellido2" class="form-label">Segundo Apellido:</label>
                                        <input type="text" class="form-control" id="apellido2" name="apellido2"
                                            value="<?php echo $apellido2; ?>" readonly>
                                    </div>

                                    <!-- <div class="mb-3">
                                        <label for="nhc" class="form-label">Id rol*:</label>
                                        <input type="number" class="form-control" id="id_rol" name="id_rol" min="0" max="3"
                                            value="<?php echo $id_rol; ?>">
                                    </div> -->
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

                                    <button type="submit" name="guardar_cambios" class="btn btn-primary">Guardar cambios</button>
                            </form>
                            <div class="mt-3">
                                <form action="editarUsuarios.php" method="POST">
                                    <button type="submit" name="cancelar" class="btn btn-primary">Cancelar</button>
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