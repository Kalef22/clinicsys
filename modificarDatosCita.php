<?php
session_start(); 
$title = "Editar usuario";
require_once "includes/header.php";
require_once "includes/aside.php";
require_once "config/Conexion.php";
require_once "Usuario.php";
$usuario = new Usuario();
$cambios_realizados="";
$errores=[];

    if($_SERVER['REQUEST_METHOD']=='GET'){
        
        try{
        $id_cita = htmlspecialchars(stripcslashes($_GET['id']));
        $conn = (new Conexion())->getConexion();
        $sql = "SELECT cita.id_cita, donante.nombre, donante.apellido1, donante.apellido2, donante.dni, maquina.descripcion_maquina, calendario.fecha, calendario.hora_inicio  
        FROM cita 
        LEFT JOIN donante ON cita.id_donante = donante.id_donante 
        LEFT JOIN maquina ON cita.id_maquina = maquina.id_maquina 
        LEFT JOIN calendario ON cita.id_dia = calendario.id_dia
        WHERE id_cita = :id_cita";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_cita", $id_cita);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $resultado = $stmt->fetch(); // Obtiene un único registro
        // var_dump($resultado);

        $id_cita = $resultado['id_cita'];
        $nombre = $resultado['nombre'];
        $apellido1 = $resultado['apellido1'];
        $apellido2 = $resultado['apellido2'];
        $dni = $resultado['dni'];
        $nombre_maquina_old = $resultado['descripcion_maquina'];
        $fecha_old = $resultado['fecha'];
        $hora_inicio_old = $resultado['hora_inicio'];
        // echo "<pre>";
        // print_r($nombre_maquina_old);
        // echo "</pre>";
    }catch(PDOException $e ){
        $error['query'] = "Error al conectar con la base de datos ".$e->getMessage();
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
<main>
    <div class="container-fluid px-4">
        <!-- <div class="card-body"> -->
        <!-- CONTENIDO EDITABLE FIN -->
        <div class="container" style="margin-top: 4rem; margin-bottom: 2rem;">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="text-center">Modificar cita Donante</h2>
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
                            <form action="modificarDatosCita2.php" method="post">
                                <input type="hidden" name="id_cita" value="<?php echo $id_cita; ?>">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="<?php echo $nombre; ?>" readonly required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="apellido1" class="form-label">Primer Apellido*:</label>
                                    <input type="text" class="form-control" id="apellido1" name="apellido1"
                                    value="<?php echo $apellido1; ?>" readonly required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="apellido2" class="form-label">Segundo Apellido:</label>
                                    <input type="text" class="form-control" id="apellido2" name="apellido2"
                                    value="<?php echo $apellido2; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI:</label>
                                    <input type="text" class="form-control" id="dni" name="dni"
                                        value="<?php echo $dni; ?>" readonly required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombre_maquina">Máquina *</label>
                                    <input type="text" class="form-control" id="nombre_maquina_old" name="nombre_maquina_old"
                                    value="<?php echo $nombre_maquina_old; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="fecha" class="form-label">Fecha:</label>
                                    <input type="text" class="form-control" id="fecha_old" name="fecha_old"
                                        value="<?php echo $fecha_old; ?>" readonly required>
                                </div>
                                <div class="mb-3">
                                    <label for="hora_inicio" class="form-label">Hora inicio:</label>
                                    <input type="text" class="form-control" id="hora_inicio_old" name="hora_inicio_old"
                                        value="<?php echo $hora_inicio_old; ?>" readonly required>
                                </div>
                                <div class="mb-3">
                                    <p><strong>Campos a modificar: Máquina, fecha y hora</strong></p>
                                </div>
                                 <!-- Selección de máquina -->
                                  <!-- Campo select para las máquinas disponibles-->
                                <div class="mb-3">
                                    <label for="id_maquina_new" class="form-label">Máquina *:</label>
                                    <select class="form-select" id="id_maquina_new" name="id_maquina_new" required>
                                        <option value="">Seleccione nueva máquina</option>
                                        <!-- Consulta para obtener las máquinas desde la base de datos -->
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
                                <div class="mb-3">
                                    <label for="fecha_new">Selecciona nueva fecha:</label>
                                    <input type="date" id="fecha_new" name="fecha_new">
                                </div>
                                <button type="submit" name="guardar_cambios" class="btn btn-primary">Guardar cambios</button>
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
    // Obtiene la fecha de hoy en formato 'YYYY-MM-DD'
    const today = new Date().toISOString().split('T')[0];
    // Establece el atributo 'min' del input para bloquear fechas anteriores
    document.getElementById("fecha_new").setAttribute("min", today);
</script>
