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
    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/favicon.png" rel="favicon">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .form-container {
            background-color: #007bff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-container label {
            color: #fff;
            margin-bottom: 20px;
            display: block;
            font-size: 1.5rem;
            text-align: center;
        }
        .form-container input[type="number"] {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box;
        }
        .form-container button {
            background-color: #fff;
            color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 0 auto;
        }
        .form-container button:hover {
            background-color: #e0e0e0;
        }

    </style>
</head>
<body>
<div class="form-container">
    <form action="codigo.php" method="POST">
        <div class="form-group">
            <label for="codigo">Ingresa el código de tu hijo</label>
            <input type="number" name="codigo" class="form-control" id="codigo" required>
        </div>
        <button type="submit" name="enviar" class="btn btn-primary">Enviar</button>
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