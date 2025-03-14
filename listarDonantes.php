<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/aside.php';
require_once 'config/Conexion.php';
require_once 'Donante.php';
?>
<div id="layoutSidenav_content">

<!-- MAIN INICIO -->
<main>
    <style>
    /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */

        thead{
            background-color: #00ABE4 ;
        }
        tr:hover{
            background-color: #D6EAF8  ;
          
        }
        td{
            background-color: #E9F1FA  ;
        }
    </style>
   <div class="container-fluid px-4 my-5">
       <!-- <h1 class="mt-4">Dashboard</h1>
       <ol class="breadcrumb mb-4">
           <li class="breadcrumb-item active">Dashboard</li>
       </ol> -->
       <div class="card mb-4">
           <div class="card-header">
               <i class="fas fa-table me-1"></i>
               Listado de donantes
           </div>
           <div class="card-body">
                <div class="container mt-5">
                    <h4>Tabla de busqueda</h4>
                    <input class="form-control mb-3" id="buscar" type="text" placeholder="Buscar en la tabla">
                </div>
                <?php
                $donante = new Donante();
                $donante->listarDonantesNew() ;
                ?>
           </div>
       </div>
   </div>
</main>
<script>
</script>
<!-- MAIN FINAL -->
 <?php
 require_once 'includes/footer.php' ?>
 <script>
    document.getElementById("buscar").addEventListener("keyup", function(){
        //obtener el valor del campo de busqueda y convertirlo a minuscula
        let valorBusqueda = this.value.toLowerCase();
        //seleccionar todas las filas de la tablas
        let fila = document.querySelectorAll("tbody tr");

        //recorrer las filas de tablas
        fila.forEach(function(fila){
            //obtener el texto de la fila y convertirlo a minuscula
            let textoFila = fila.textContent.toLowerCase();
            //mostrar la fila si coincide con al busqueda, ocultarla si no 
            fila.style.display= textoFila.includes(valorBusqueda)? "": "none";
         });
        });
</script>