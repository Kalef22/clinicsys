<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
    require_once "includes/header.php";
    require_once "includes/aside.php";
    require_once "config/Conexion.php";
    $aviso_eliminacion="";

    if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['id_cita'])){
        $id_cita = htmlspecialchars(stripcslashes($_POST['id_cita']));
      try {
        $pdo = (new Conexion())->getConexion();
        $query_eliminar = "DELETE FROM cita WHERE id_cita = :id_cita";
        $statement_eliminar = $pdo->prepare($query_eliminar);
        $statement_eliminar->bindParam(":id_cita", $id_cita);
        $statement_eliminar->execute();
        if($statement_eliminar->execute()){
            $aviso_eliminacion ="<div class='alert alert-success'> Cita ".$id_cita." eliminada correctamente.</div>";
        }else{
            $aviso_eliminacion="<div class='alert-danger>Error al eliminar la cita ".$id_cita.".</div>";
        }
      } catch (PDOException $e) {
        $aviso_eliminacion = "<div class='alert alert-danger'>Error de eliminación de cita: ". $e->getMessage()."</div>";
      }
    }
?>
<!-- Asegúrate de que la URL esté correcta -->
 <style>
    /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */
    thead{
        background-color:#00ABE4 ;
    }
    tr:hover{
        background-color:#E9F1FA ;
    }
 </style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> 

<div id="layoutSidenav_content">
    <main>
        <!-- padding 4 al eje horizontal margin 5 al eje vertical -->
        <div class="container-fluit px-4 my-5">
            <?php
                $aviso_eliminacion;
            ?>
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Listado de citas</strong>
                </div>
                <div class="card-body">
                    <?php
                    try{
                        $pdo = (new Conexion())->getConexion();
                        $query_listado = "SELECT cita.id_cita, donante.nombre, donante.apellido1, donante.apellido2, donante.dni, maquina.descripcion_maquina, calendario.fecha, cita.hora_inicio
                                        FROM cita
                                        LEFT JOIN donante ON cita.id_donante = donante.id_donante
                                        LEFT JOIN maquina ON cita.id_maquina = maquina.id_maquina
                                        LEFT JOIN calendario ON cita.id_dia = calendario.id_dia";
                        $statement = $pdo->prepare($query_listado);
                        $statement->execute();
                        $statement->setFetchMode(PDO::FETCH_ASSOC);
                        $rows = $statement->fetchAll();
                        // echo "<pre>";
                        // var_dump($rows);
                        // echo "</pre>";
                        if(count($rows)>0){
                            echo "<table id='mitabla' class='table table-striped table-hover table-bordered'>
                                    <thead>
                                        <tr>
                                            <th>Id cita</th>
                                            <th>Nombre</th>
                                            <th>Primer apellido</th>
                                            <th>Segundo apellido</th>
                                            <th>DNI</th>
                                            <th>Nombre maquina</th>
                                            <th>Fecha</th>
                                            <th>Hora inicio</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            foreach($rows as $row){
                                echo "<tr>
                                        <td>".$row['id_cita']."</td>
                                        <td>".$row['nombre']."</td>
                                        <td>".$row['apellido1']."</td>
                                        <td>".$row['apellido2']."</td>
                                        <td>".$row['dni']."</td>
                                        <td>".$row['descripcion_maquina']."</td>
                                        <td>".$row['fecha']."</td>
                                        <td>".$row['hora_inicio']."</td>
                                        <td>
                                            <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar esta cita ".$row['id_cita']."?\");'>
                                                <input type='hidden' name='id_cita' value='".$row['id_cita']."'>
                                                <button type='submit' class='btn btn-danger btn-sm'>Eliminar cita</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                            echo "</tbody></table>";
                        }else{
                            echo "No hay datos disponibles";
                        }
                    }catch(PDOException $e){
                        echo "Error en la consulta: ". $e->getMessage();
                    }
                    ?>
                </div>
            </div>   
        </div>
    </main>
    <!-- En el footer esta el link para que funcione los estilo de la tabla -->
    <?php require_once "includes/footer.php"; ?>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function(){
        $('#mitabla').DataTable({
            "paging":true,
            "lengthMenu":[5, 10, 15, 20],
            "language":{
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
    });
</script>