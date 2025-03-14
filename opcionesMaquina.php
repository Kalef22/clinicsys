<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
$title = "Mi Cuenta";
require_once "includes/header.php";
//require_once "includes/aside.php";
require "Maquina.php";
require_once "config/Conexion.php";
$maquina = new Maquina();


//Para cambiar el estado de una maquina
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_maquina']) && isset($_POST['boton_presionado'])) {
        $id_maquina =htmlspecialchars($_POST['id_maquina']);
        $estado_maquina = htmlspecialchars($_POST['estado_actual']);
        $maquina->actualizarEstadoMaquina($id_maquina, $estado_maquina);
        header('Refresh: 0; URL = opcionesMaquina.php');
    }
}

//Para dar de alta una maquina
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_alta'])) {
        $estado_maquina = htmlspecialchars($_POST['estado']);
        $descripcion_maquina = htmlspecialchars($_POST['descripcion']);
        $maquina->altaMaquina($descripcion_maquina, $estado_maquina);
        header('Refresh: 0; URL = opcionesMaquina.php');
    }
}

//Para elminiar maquina
if($_SERVER['REQUEST_METHOD']== "POST"){
    if(isset($_POST['id_maquina']) && isset($_POST['eliminar_maquina'])){
        $id_maquina = htmlspecialchars($_POST['id_maquina']);
        $maquina->eliminarMaquina();
        $pdo = (new Conexion())->getConexion();
        $query = "DELETE FROM maquina WHERE id_maquina = :id_maquina";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_maquina', $id_maquina);
        $stmt->execute();
        header('Refresh: 0; URL = opcionesMaquina.php');
    }
}

//Para cambiar el tiempo de la cita
if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST["nuevo_tiempo"])){
        $id_tiempo = $_POST['nuevo_tiempo'];
        $pdo = (new Conexion())->getConexion();
        $query = "UPDATE horarios SET tiempo_cita = :tiempo_cita";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":tiempo_cita", $id_tiempo);
        $stmt->execute();

        //header("Refresh: 0; URL = opcionesMaquina.php ");
    }
    
}
function verTiempo(){
    $pdo = (new Conexion())->getConexion();
    $query2 = "SELECT tiempo_cita From horarios";
    $stmt = $pdo->prepare($query2);
    $stmt->execute();
    $id_tiempoNuevo = $stmt->fetchColumn();
    return $id_tiempoNuevo; //obtiene el primer valor de la primera columna
}
?>

<style>
/* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */
    .card-header{
        background-color: #00ABE4;
    }

</style>

<!-- <body class="sb-nav-fixed"> -->
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Aside -->
            <aside class="col-md-3">
                <?php require_once "includes/aside.php"; ?>
            </aside>
        <main class="col-md-7 mt-5">
           
            <div class="container" style="margin-top: 3rem;">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Listado de Máquinas</h2>
                            </div>
                            <div class="card-body">
                                <?php echo $maquina->listarMaquinas(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
     

            <div class="container" style="margin-bottom: 4rem; margin-top: 3rem;">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Alta de Máquina</h2>
                            </div>
                            <div class="card-body">
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <div class="mb-3">
                                        <label for="descripcion_maquina" class="form-label">Descripción:</label>
                                        <input type="text" class="form-control" id="descripcion_maquina" name="descripcion"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="activa" class="form-label">Estado:</label>
                                        <select class="form-select" id="activa" name="estado">
                                            <option value="SI">SI</option>
                                            <option value="NO">NO</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="form_alta">Alta Máquina</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container" style="margin-bottom: 4rem; margin-top: 3rem;">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Tiempo de la cita</h2>
                            </div>
                            <div class="card-body">
                                <p>El tiempo actual de la cita es de: <strong><?php echo verTiempo(); ?></strong></p>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                    <div class="mb-3">
                                        <label for="nuevo_tiempo" class="form-label">Nuevo Tiempo:</label>
                                        <select class="form-select" id="nuevo_tiempo" name="nuevo_tiempo" required>
                                            <option value="<?php echo date('00:30:00');  ?>">media hora</option>
                                            <option value="<?php echo date('01:00:00');  ?>">1 hora</option>
                                            <option value="<?php echo date('01:30:00');  ?>">1 hora y media</option>
                                            <option value="<?php echo date('02:00:00'); ?>">2 horas</option>
                                            <option value="<?php echo date('02:30:00');  ?>">2 horas y media</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="form_tiempo">Definir Duracion de la
                                        cita</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container" style="margin-top: 3rem;">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Baja Máquina</h2>
                            </div>
                            <div class="card-body">
                            <?php echo $maquina->eliminarMaquina(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer mt-5 bg-light">
            <div class="container">
                <?php require_once "includes/footer.php"; ?>
            </div>
        </footer>
    </div>
</div>
   
</body>
<!-- <script>
    //capturar el boton por su id
    const btnEstado = document.querySelector('.cambiar_estado');
    //capturar el input por su id
    const inputIdEstado =document.querySelector('.cambiarColor');

    //capturar el valor del input
    var valorInput = inputIdEstado.value;
    

    console.log(btnEstado);
    console.log(inputIdEstado);
    console.log(valorInput);
   

        if(valorInput == 'NO'){
            btnEstado.classList.remove('btn-danger');
            btnEstado.classList.add('btn-primary');
        }else if(valorInput == 'SI'){
            btnEstado.classList.remove('btn-primary');
            btnEstado.classList.add('btn-danger');
        }
        //ver la clase despues de cambiarla
         console.log("clase despues del camnbio", btnEstado.className);
        
        
    // })
    

    
</script> -->
<script>
    //capturar todos los botones por su clase
    const botonesEstado = document.querySelectorAll('.cambiar_estado');
    //capturar todos los inputs asociados al estado por su clase
    const inputsEstado = document.querySelectorAll('.cambiarColor');

    //Iterar sobre todos los botones e inputs
    botonesEstado.forEach((btnEstado, index) => {
        // Obtener el valor del input correspondiente usando el índice
        var valorInput = inputsEstado[index].value;

        // Mostrar en consola el valor del botón e input para depuración
        console.log("Botón:", btnEstado);
        console.log("Input:", inputsEstado[index]);
        console.log("Valor Input:", valorInput);

        // Cambiar la clase del botón según el valor del input
        if(valorInput == 'SI'){
            btnEstado.classList.remove('btn-danger');
            btnEstado.classList.add('btn-success');
        }else if(valorInput == 'NO'){
            btnEstado.classList.remove('btn-success');
            btnEstado.classList.add('btn-danger');
        }

        // Mostrar la clase después del cambio en consola para verificar
        console.log("Clase después del cambio:", btnEstado.className);
    });
</script>


<?php require_once "includes/footer.php"; ?>
</html>