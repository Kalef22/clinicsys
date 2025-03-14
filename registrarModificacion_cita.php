<?php
// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once "includes/header.php";
require_once "includes/aside.php";
require_once "config/Conexion.php";

require 'fpdf/fpdf.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if($_SERVER['REQUEST_METHOD']=='POST'){
    $id_cita = htmlspecialchars(stripslashes($_POST["id_cita"]));
    $id_donante = htmlspecialchars(stripslashes($_POST["id_donante"]));
    $id_dia = htmlspecialchars(stripslashes($_POST["id_dia"]));
    $nombre = htmlspecialchars(stripslashes($_POST["nombre"]));
    $primerApellido = htmlspecialchars(stripslashes($_POST["apellido1"]));
    $segundoApellido = htmlspecialchars(stripslashes($_POST["apellido2"]));
    $nombre_maquina_old = htmlspecialchars(stripslashes($_POST["nombre_maquina_old"]));
    $nombre_maquina_new = htmlspecialchars(stripslashes($_POST["nombre_maquina_new"]));
    $id_maquina_new = htmlspecialchars(stripslashes($_POST["id_maquina_new"]));
    $fecha_old = htmlspecialchars(stripslashes($_POST["fecha_old"]));
    $fecha_new = htmlspecialchars(stripslashes($_POST["fecha_new"]));
    $hora_inicio_old = htmlspecialchars(stripslashes($_POST["hora_inicio_old"]));
    $hora_inicio_new = htmlspecialchars(stripslashes($_POST["selectedHour"]));
    
   
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    

    try {
        $apto="PDT";
        $pdo = (new Conexion())->getConexion();
        $query_insertarCita = "UPDATE cita SET id_donante = :id_donante , id_dia = :id_dia, id_maquina = :id_maquina, apto = :apto, hora_inicio =:hora_inicio WHERE id_cita = :id_cita";
        $stmt_insertarCita = $pdo->prepare($query_insertarCita);
        $stmt_insertarCita->bindParam(":id_donante", $id_donante);
        $stmt_insertarCita->bindParam(":id_dia", $id_dia);
        $stmt_insertarCita->bindParam(":id_maquina", $id_maquina_new);
        $stmt_insertarCita->bindParam(":apto", $apto);
        $stmt_insertarCita->bindParam(":hora_inicio", $hora_inicio_new);
        $stmt_insertarCita->bindParam(":id_cita", $id_cita);
        $stmt_insertarCita->execute();


         // Generar el PDF
         class PDF extends FPDF
         {
             function Header()
             {
                 $this->Image('assets/img/logo_12octubre.png',10,8,33);
                 $this->SetFont('Arial','B',15);
                 $this->Cell(80);
                 $this->Ln(20);
             }
 
             function Footer()
             {
                 $this->SetY(-15);
                 $this->SetFont('Arial','I',8);
                 $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
             }
         }
 
         $pdf = new PDF();
         $pdf->AliasNbPages();
         $pdf->AddPage();
         
         // Agregar el nuevo subtítulo
         $pdf->SetFont('Arial','B',13);
         $pdf->Cell(0,10,'Cita antigua',0,1,'L');
         $pdf->Ln(3); // Salto de línea para espaciar el subtítulo del contenido siguiente
         $pdf->SetFont('Arial','',12);

        
         $pdf->Cell(0,10,'Nombre: ' . $nombre,0,1);
         $pdf->Cell(0,10,'Primer Apellido: ' . $primerApellido,0,1);
         $pdf->Cell(0,10,'Segundo Apellido: ' . $segundoApellido,0,1);
         $pdf->Cell(0,10,'Fecha: ' . $fecha_old,0,1);
         $pdf->Cell(0,10,'Hora: ' . $hora_inicio_old,0,1);
         $pdf->Cell(0,10,'Maquina: ' . $nombre_maquina_old,0,1);

         $pdf->Ln(10); // Salto de línea para espaciar el subtítulo del contenido siguiente

         // Agregar el nuevo subtítulo
         $pdf->SetFont('Arial','B',16);
         $pdf->Cell(0,10,'Cita actualizada',0,1,'L');
         $pdf->Ln(3); // Salto de línea para espaciar el subtítulo del contenido siguiente
         $pdf->SetFont('Arial','',12);
         $pdf->Cell(0,10,'Nombre: ' . $nombre,0,1);
         $pdf->Cell(0,10,'Primer Apellido: ' . $primerApellido,0,1);
         $pdf->Cell(0,10,'Segundo Apellido: ' . $segundoApellido,0,1);
         $pdf->Cell(0,10,'Fecha: ' . $fecha_new,0,1);
         $pdf->Cell(0,10,'Hora: ' . $hora_inicio_new,0,1);
         $pdf->Cell(0,10,'Maquina: ' . $nombre_maquina_new,0,1);
 
         // Guardar el PDF en la carpeta 'citas'
         $pdfFilePath = 'citas/cita.pdf';
         $pdf->Output('F', $pdfFilePath);
 
         // Enviar el email con PHPMailer        NO conecta puede que se deba a el firewall del 12.
         // $mail = new PHPMailer(true);
 
         // try {
         //     $mail->isSMTP();
         //     $mail->Host = 'smtp.gmail.com';
         //     $mail->SMTPAuth = true;
         //     $mail->Username = 'tu_correo@gmail.com'; // Tu dirección de correo de Gmail
         //     $mail->Password = 'tu_contraseña'; // Tu contraseña de Gmail o contraseña de aplicación
         //     $mail->SMTPSecure = 'tls';
         //     $mail->Port = 587;
 
         //     $mail->setFrom('tu_correo@gmail.com', 'tu_nombre');
         //     $mail->addAddress('a_donde_envias@gmail.com', 'nombre_a_quien');
 
         //     // Adjuntar el PDF guardado en la carpeta 'citas'
         //     $mail->addAttachment($pdfFilePath);
 
         //     $mail->isHTML(true);
         //     $mail->Subject = 'Datos de la Cita';
         //     $mail->Body    = 'Adjunto encontrarás los datos de tu cita.';
 
         //     $mail->send();
         //     echo 'El mensaje ha sido enviado';
         // } catch (Exception $e) {
         //     echo "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
         // }
    } catch (PDOException $e) {
        echo "Error al inserta la cita ".$e->getMessage();
    }   
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="container my-5">
                <img src="assets/img/logo_12octubre.png" alt="" class="mt-5 mb-5">
                <h3>Cita antigua</h3>
                <ul>
                    <li>Nombre: <strong><?php echo $nombre." ". $primerApellido." ".$segundoApellido ?></strong></li>
                    <li>Fecha: <strong><?php echo $fecha_old ?></strong></li>
                    <li>Hora: <strong><?php echo $hora_inicio_old  ?></strong> </li>
                    <li>Nombre de maquina: <strong><?php echo $nombre_maquina_old ?></strong> </li>
                    <li>Lugar: <strong>Hospital Universitario 12 de Octubre</strong></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid px-4">
            <div class="container my-5">
                <!-- <img src="assets/img/logo_12octubre.png" alt="" class="mt-5 mb-5"> -->
                <h2>Cita actualizada</h2>
                <ul>
                    <li>Nombre: <strong><?php echo $nombre." ". $primerApellido." ".$segundoApellido ?></strong></li>
                    <li>Fecha: <strong><?php echo $fecha_new ?></strong></li>
                    <li>Hora: <strong><?php echo $hora_inicio_new  ?></strong> </li>
                    <li>Nombre de maquina: <strong><?php echo $nombre_maquina_new ?></strong> </li>
                    <li>Lugar: <strong>Hospital Universitario 12 de Octubre</strong></li>
                </ul>
                <!-- Botón para descargar el PDF -->
                <a href="<?php echo $pdfFilePath; ?>" class="btn btn-primary mt-3" download="cita.pdf">Descargar Cita</a>
            </div>
        </div>
    </main>
    <?php
require_once "includes/footer.php" 
?>
</div>


