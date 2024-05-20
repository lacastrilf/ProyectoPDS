<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("location: /ProyectoPDS/inicio/login.php");
    exit;
}
$idUsuario = $_SESSION['id_usuario'];
$conexion = new mysqli("localhost", "root", "", "base_proyecto");


if (isset($_POST['enviar'])) {
    $posibleCodigo=$_POST['codigo'];
    $sql="SELECT * FROM codigos WHERE codigo='$posibleCodigo'";
    $resultado=$conexion->query($sql);
    $dato = $resultado->fetch_assoc();
    if($dato){
        $_SESSION['id_usuario'] = $dato['idUsuario'];
        header("location:http://localhost/ProyectoPDS/ProyectoPDS/otro/estudiante.php");
    }
    else{
        echo "Error";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SmartSpends</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <style>
    body{
        display: flex;
        align-content: center;
        justify-content: center;
    }
  </style>
</head>

<body>






  

          
    <div>
    <form action="codigo.php" method="POST">
        <label>Ingresa el codigo de tu hijo</label>
        <input type="number" name="codigo">
        <input type="submit" name="enviar" value="Enviar">
    </form>
    </div>
          




  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>