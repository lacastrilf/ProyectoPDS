<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("location: /ProyectoPDS/inicio/login.php");
    exit;
}
$idUsuario = $_SESSION['id_usuario'];
$conexion = new mysqli("localhost", "root", "", "base_proyecto");

// Obtener la suma de todos los presupuestos
$sqlSuma = "SELECT SUM(alimentacion + colchon + ocio+ transporte + vivienda ) AS suma_total FROM presupuestos WHERE id_usuario='$idUsuario'";
$resultadoSuma = $conexion->query($sqlSuma);
$sumaTotal = $resultadoSuma->fetch_assoc()['suma_total'] ?? 0;

//obtener cada presupuesto para la grafica
$sqlCategorias = "SELECT alimentacion AS alimentacion, transporte AS transporte, ocio AS ocio, vivienda AS vivienda FROM presupuestos WHERE id_usuario='$idUsuario'";
$resultadoCategorias = $conexion->query($sqlCategorias);
$categorias = $resultadoCategorias->fetch_assoc() ?? ['alimentacion' => 0, 'transporte' => 0, 'ocio' => 0, 'estudiante' => 0, 'vivienda' => 0];
$gAlimentacion = $categorias['alimentacion'];
$gTransporte = $categorias['transporte'];
$gOcio = $categorias['ocio'];
$gVivienda = $categorias['vivienda'];


//Obtener la suma total de los gastos
$sqlSumaG = "SELECT SUM(alimentacion + transporte + vivienda + ocio+colchon) AS suma_total FROM diagramagastoshogar WHERE idUsuario='$idUsuario'";
$resultadoSumaG= $conexion->query($sqlSumaG);
$sumaTotalG = $resultadoSumaG->fetch_assoc()['suma_total'];

//Creación de fila de la tabla diagrama Costos del Hogar
$sqlSeleccion = "SELECT * FROM diagramagastoshogar WHERE idUsuario = '$idUsuario'";
$resultado = $conexion->query($sqlSeleccion);
if ($resultado->num_rows == 0) {
    $sqlIngresar = "INSERT INTO diagramagastoshogar VALUES ('null', '$idUsuario', '0', '0', '0', '0', '0','0')";
    $ejecutar3 = mysqli_query($conexion, $sqlIngresar);
}
$resultado = $conexion->query($sqlSeleccion);
$dato = $resultado->fetch_assoc();
if($dato){
  $alimentacionGrafico=$dato['alimentacion'];
  $transporteGrafico=$dato['transporte'];
  $viviendaGrafico=$dato['vivienda'];
  $ocioGrafico=$dato['ocio'];
}


//Ingresar pendientes de la semana
if(isset($_POST['enviarPendiente'])){
    $nombrePendiente=$_POST['nombrePendiente']; 
    $presupuestoPendiente=$_POST['montoPendiente']; 
    $fechaPendiente=$_POST['fechaPendiente']; 
    $sqlInsertPendiente = "INSERT INTO  pendiente VALUES ('null','$idUsuario','$nombrePendiente','$presupuestoPendiente','$fechaPendiente')";
    $ejecutar3 = mysqli_query($conexion, $sqlInsertPendiente);
    header("Location: {$_SERVER['PHP_SELF']}");
}

// Creación de fila de la tabla promedio semanas
$sqlSeleccionSemanas= "SELECT * FROM semanas WHERE idUsuario = '$idUsuario'";
$resultado = $conexion->query($sqlSeleccionSemanas);
if ($resultado->num_rows == 0) {
    $sqlIngresarSemanas="INSERT INTO semanas VALUES (NULL, '$idUsuario', 0, 0, 0, 0, 0)";
    $conexion->query($sqlIngresarSemanas);
}

// Creación de fila de la tabla presupuestos
$sqlSeleccionPresupuestos= "SELECT * FROM presupuestos WHERE id_usuario = '$idUsuario'";
$resultado = $conexion->query($sqlSeleccionPresupuestos);
if ($resultado->num_rows == 0) {
    $sqlIngresarPresupuestos="INSERT INTO presupuestos VALUES ('$idUsuario', 0, 0, 0, 0, 0, 0)";
    $conexion->query($sqlIngresarPresupuestos);
}

// Creación de fila de la tabla Ahorro
$sqlSeleccionAhorro= "SELECT * FROM ahorro WHERE idUsuario = '$idUsuario'";
$resultado = $conexion->query($sqlSeleccionAhorro);
if ($resultado->num_rows == 0) {
    $sqlIngresarAhorro="INSERT INTO ahorro VALUES (NULL, '$idUsuario', 0, 0)";
    $conexion->query($sqlIngresarAhorro);
} else {
    $datoAhorro = $resultado->fetch_assoc();
    $ahorroTotal = $datoAhorro['Ahorro'];
    $meta = $datoAhorro['ahorroEstablecido'];
}

// Creación de fila de la tabla Ahorro
$sqlSeleccionAhorro= "SELECT * FROM ahorro WHERE idUsuario = '$idUsuario'";
$resultado = $conexion->query($sqlSeleccionAhorro);
if ($resultado->num_rows == 0) {
    $sqlIngresarAhorro="INSERT INTO ahorro VALUES (NULL, '$idUsuario', 0, 0)";
    $conexion->query($sqlIngresarAhorro);
}
$resultado = $conexion->query($sqlSeleccionAhorro);
$datoAhorro = $resultado->fetch_assoc();
if($datoAhorro){
    $ahorroTotal = $datoAhorro['Ahorro'];
    $meta = $datoAhorro['ahorroEstablecido'];
}

function datos($conexion, $idUsuario) {
    $sqlGastosSemanas = "SELECT * FROM diagramagastoshogar WHERE idUsuario='$idUsuario'";
    $resultado = $conexion->query($sqlGastosSemanas);
    return $resultado->fetch_assoc();
}

// Actualizar semanas
if (isset($_POST['actualizarSemanas'])) {
    // Obtener los gastos de la semana
    $gastosSemanas = datos($conexion, $idUsuario);
    $transporteGS = $gastosSemanas['transporte'];
    $ocioGS = $gastosSemanas['ocio'];
    $colchonGS = $gastosSemanas['colchon'];
    $alimentacionGS = $gastosSemanas['alimentacion'];
<<<<<<< Updated upstream
    $datosSemanales = 0;
    $viviendaGS= $datosSemanales['vivienda'];
    $estudianteGS= $datosSemanales['estudiante'];
=======
    $viviendaGS=$datosSemanas['vivienda'];
    $estudianteGS=$datosSemanas['estudiante'];
>>>>>>> Stashed changes

    // Seleccionar las semanas
    $sqlSeleccionSemanas = "SELECT * FROM semanas WHERE idUsuario = '$idUsuario'";
    $resultado = $conexion->query($sqlSeleccionSemanas);
    $dato = $resultado->fetch_assoc();

    if ($dato['semana1'] == 0) {

        $sqlUpdateSemanas = "UPDATE semanas SET semana1='$sumaTotalG' WHERE idUsuario='$idUsuario'";
        $conexion->query($sqlUpdateSemanas);
        $sqlUpdatePresupuestosGS = "UPDATE semanasgastosh SET alimentacion='$alimentacionGS', transporte='$transporteGS', ocio='$ocioGS', colchon='$colchonGS', vivienda='$viviendaGS', estudiante='$estudianteGS' WHERE idUsuario='$idUsuario' AND semana='1'";
        $conexion->query($sqlUpdatePresupuestosGS);
    } elseif ($dato['semana2'] == 0) {
        $sqlUpdateSemanas = "UPDATE semanas SET semana2='$sumaTotalG' WHERE idUsuario='$idUsuario'";
        $conexion->query($sqlUpdateSemanas);
        $sqlUpdatePresupuestosGS = "UPDATE semanasgastosh SET alimentacion='$alimentacionGS', transporte='$transporteGS', ocio='$ocioGS', colchon='$colchonGS', vivienda='$viviendaGS', estudiante='$estudianteGS' WHERE idUsuario='$idUsuario' AND semana='2'";
        $conexion->query($sqlUpdatePresupuestosGS);
    } elseif ($dato['semana3'] == 0) {
        $sqlUpdateSemanas = "UPDATE semanas SET semana3='$sumaTotalG' WHERE idUsuario='$idUsuario'";
        $conexion->query($sqlUpdateSemanas);
        $sqlUpdatePresupuestosGS = "UPDATE semanasgastosh SET alimentacion='$alimentacionGS', transporte='$transporteGS', ocio='$ocioGS', colchon='$colchonGS', vivienda='$viviendaGS', estudiante='$estudianteGS' WHERE idUsuario='$idUsuario' AND semana='3'";
        $conexion->query($sqlUpdatePresupuestosGS);
    } elseif ($dato['semana4'] == 0) {
        $sqlUpdateSemanas = "UPDATE semanas SET semana4='$sumaTotalG' WHERE idUsuario='$idUsuario'";
        $conexion->query($sqlUpdateSemanas);
        $sqlUpdatePresupuestosGS = "UPDATE semanasgastosh SET alimentacion='$alimentacionGS', transporte='$transporteGS', ocio='$ocioGS', colchon='$colchonGS', vivienda='$viviendaGS', estudiante='$estudianteGS' WHERE idUsuario='$idUsuario' AND semana='4'";
        $conexion->query($sqlUpdatePresupuestosGS);
    } else {
        $sqlUpdateSemanas = "UPDATE semanas SET semana1='$sumaTotalG', semana2=0, semana3=0, semana4=0 WHERE idUsuario='$idUsuario'";
        $conexion->query($sqlUpdateSemanas);
        $sqlUpdatePresupuestosGS = "UPDATE semanasgastosh SET alimentacion='$alimentacionGS', transporte='$transporteGS', ocio='$ocioGS', colchon='$colchonGS' WHERE idUsuario='$idUsuario' AND semana='1'";
        $conexion->query($sqlUpdatePresupuestosGS);
        $sqlReiniciarSemanasGastos = "UPDATE semanasgastosh SET alimentacion=0, transporte=0, ocio=0, colchon=0 WHERE idUsuario='$idUsuario' AND semana IN ('2', '3', '4')";
        $conexion->query($sqlReiniciarSemanasGastos);
    }

    // Actualizar Ahorro
    $sqlSeleccionarAhorro = "SELECT * FROM ahorro WHERE idUsuario='$idUsuario'";
    $resultado = $conexion->query($sqlSeleccionarAhorro);
    $dato = $resultado->fetch_assoc();
    if ($dato) {
        $ahorro = $sumaTotal - $sumaTotalG + $dato['Ahorro'];
        $sqlUpdateAhorro = "UPDATE ahorro SET Ahorro='$ahorro' WHERE idUsuario='$idUsuario'";
        $conexion->query($sqlUpdateAhorro);
    }

    // Eliminar datos de las tablas para iniciar cada semana
    $sqlEliminarGastos = "DELETE FROM gastosi WHERE id_usuario='$idUsuario'";
    $conexion->query($sqlEliminarGastos);
    $sqlEliminarPendientes = "DELETE FROM pendiente WHERE idUsuario='$idUsuario'";
    $conexion->query($sqlEliminarPendientes);
    $sqlEliminarEventos = "DELETE FROM eventosespeciales WHERE idUsuario='$idUsuario'";
    $conexion->query($sqlEliminarEventos);
    $sqlEliminarPresupuestos = "DELETE FROM presupuestos WHERE id_usuario='$idUsuario'";
    $conexion->query($sqlEliminarPresupuestos);

    // Reiniciar diagramagastosHOGAR a 0
    $sqlReiniciarGastosHogar = "UPDATE diagramagastoshogar SET transporte=0, alimentacion=0, ocio=0, colchon=0, vivienda=0, estudiante=0 WHERE idUsuario='$idUsuario'";
    $conexion->query($sqlReiniciarGastosHogar);

    header("Location: {$_SERVER['PHP_SELF']}");
}


// Retirar Ahorros
if (isset($_POST['retirar'])) {
    $sqlSeleccionarAhorro = "SELECT * FROM ahorro WHERE idUsuario='$idUsuario'";
    $resultado = $conexion->query($sqlSeleccionarAhorro);
    $dato = $resultado->fetch_assoc();
    $presupuesto = $dato['Ahorro'] + $sumaTotal;
    $sqlUpdateAhorro = "UPDATE ahorro SET Ahorro=0 WHERE idUsuario='$idUsuario'";
    $conexion->query($sqlUpdateAhorro);
    $sqlUpdatePresupuestos = "UPDATE presupuestos SET colchon='$presupuesto' WHERE id_usuario='$idUsuario'";
    $conexion->query($sqlUpdatePresupuestos);
    header("Location: {$_SERVER['PHP_SELF']}");
}

// Registrar ahorros
if (isset($_POST['registrarAhorro'])) {
    $ahorroEstablecido = $_POST['ahorroEstablecido'];
    $sqlUpdateAhorro = "UPDATE ahorro SET ahorroEstablecido='$ahorroEstablecido' WHERE idUsuario='$idUsuario'";
    $conexion->query($sqlUpdateAhorro);
    header("Location: {$_SERVER['PHP_SELF']}");
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
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="favicon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="../inicio/index.php" class="logo d-flex align-items-center">
            <span class="d-none d-lg-block">INICIO</span>
        </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a><!-- End Notification Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-exclamation-circle text-warning"></i>
              <div>
                <h4>Lorem Ipsum</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>30 min. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-x-circle text-danger"></i>
              <div>
                <h4>Atque rerum nesciunt</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>1 hr. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Sit rerum fuga</h4>
                <p>Quae dolorem earum veritatis oditseno</p>
                <p>2 hrs. ago</p>
              </div>
            </li>

            <li>
              <hr class="dropdown-divider">
            </li>



          </ul><!-- End Notification Dropdown Items -->

        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown">
           

           

        </li><!-- End Messages Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php
                        if(isset($_SESSION['usuario'])) {
                            echo $_SESSION['usuario'];
                        }
                        ?></span>
          </a><!-- End Profile Iamge Icon -->

         
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">


      <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="../otro/hogar.php">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Resumen</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="../otro/Hogar/transporte.php">
                <i class="bi bi-bus-front"></i>
                <span>Transporte</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="../otro/Hogar/alimentacion.php">
                <i class="bi bi-egg-fried"></i>
                <span>Alimentación</span>
            </a>
        </li><!-- End Profile Page Nav -->

      <li class="nav-item">
      <a class="nav-link collapsed" href="../otro/Hogar/vivienda.php">
          <i class="bi bi-house"></i>
          <span>Vivienda</span>
        </a>
      </li><!-- End F.A.Q Page Nav -->



        <li class="nav-item">
            <a class="nav-link collapsed" href="../otro/Hogar/ocio.php">
                <i class="bi bi-controller"></i>
                <span>Ocio</span>
            </a>
        </li><!-- End Register Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="../otro/Hogar/colchon.php">
                <i class="bi bi-piggy-bank"></i>
                <span>Colchón</span>
            </a>
        </li><!-- End Login Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="../inicio/login.php">
                <i class="bi bi-person-bounding-box"></i>
                <span>Login</span>
            </a>
        </li><!-- End Error 404 Page Nav -->

      

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

      <div class="pagetitle">
          <h1>Resumen</h1>
          <nav>
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="../inicio/index.php">Home</a></li>
                  <li class="breadcrumb-item active">Resumen</li>
              </ol>
          </nav>
      </div><!-- End Page Title -->

      <section class="section dashboard">
          <div class="row">

              <!-- Left side columns -->
              <div class="col-lg-12">
                  <div class="row">

                      <!-- Sales Card -->
                      <div class="col-xxl-4 col-xl-4 col-md-4">
                          <div class="card info-card sales-card">
                          <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Actualizar</h6>
                    </li>
                    <li>
                      <form action="hogar.php" method="POST">
                      <input type="hidden" value="<?php echo (isset($sumaTotalG) ? $sumaTotalG : 0); ?>" name="presupuestoProm">
                      <input type="submit" class="dropdown-item" href="#" name="actualizarSemanas" value="Nueva semana" onclick="recargarPaginas()">
                    </form>
                    </li>
                  </ul>
                </div>
                              <div class="card-body">
                                  <h5 class="card-title">Gastos <span>| Semanales</span></h5>
                                  <div class="d-flex align-items-center">
                                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                          <i class="bi bi-cart"></i>
                                      </div>
                                      <div class="ps-3">
                                          <h6 id="total_gastos">  <br>$<?php echo (isset($sumaTotalG) ? $sumaTotalG : 0); ?></h6>
                                          <span id="porcentaje" class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">del presupuesto</span>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div><!-- End Sales Card -->

                      <!-- Revenue Card -->
                      <div class="col-xxl-4 col-xl-4 col-md-4">
                          <div class="card info-card revenue-card">
                              <div class="filter">
                            
                              </div>
                              <div class="card-body">
                                  <h5 class="card-title">Presupuesto <span>| Esta Semana</span></h5>

                                  <div class="d-flex align-items-center">
                                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                          <i class="bi bi-currency-dollar"></i>
                                      </div>
                                      <div class="ps-3">
                                      <h6 id="presupuesto_usuario"><br>$<?php echo (isset($sumaTotal) ? $sumaTotal : 0); ?></h6>
                      <br>
                                      </div>
                                  </div>
                              </div>

                          </div>
                      </div><!-- End Revenue Card -->

                      <!-- Customers Card -->
                      <div class="col-xxl-4 col-xl-4 col-md-4">

                          <div class="card info-card customers-card">

                              <div class="filter">
                                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                  <li class="dropdown-header text-start">
                                  <h6>Actualizar</h6>
                              </li>
                              <li>
                                  <form action="hogar.php" method="POST">
                                      <input type="hidden" value="<?php echo (isset($ahorroTotal) ? $ahorroTotal : 0); ?>" name="ahorroEstablecido">
                                      <input type="submit" class="dropdown-item" href="#" name="retirar" value="Retirar">
                                  </form>
                              </li>
                                  </ul>
                              </div>
                              <div class="card-body">
                              <h5 class="card-title">Ahorro<span> | meta: <span id="meta"><?php echo (isset($meta) ? $meta : 0); ?></span></span></h5>
                                  <div class="d-flex align-items-center">
                                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                          <i class="bi bi-piggy-bank-fill"></i>
                                      </div>
                                      <div class="ps-3">
                                  <h6 id="ahorroTotal">$<?php echo ($ahorroTotal); ?></h6>
                                  <span id="porcentajeA" class="text-success small pt-1 fw-bold"></span><span class="text-muted small pt-2 ps-1">de la meta</span>
                                  <button  type="button" class="btn btn-custom-orange btn-sm" data-bs-toggle="modal" data-bs-target="#modalDefinirMeta">
                                      Definir Meta
                                  </button>
                                  <div class="modal fade" id="modalDefinirMeta" tabindex="-1" aria-hidden="true">
                                      <div class="modal-dialog">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h5 class="modal-title">Definir nueva Meta</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                  <!-- Formulario para poner meta de ahorro-->
                                                  <form action="hogar.php" method="POST" id="formDefinirMeta">
                                                      <div class="mb-3">
                                                          <label for="ahorro">Nueva Meta:</label>
                                                          <input class="form-control" id="ahorro" name="ahorroEstablecido" type="number" required>
                                                      </div>
                                                      <button type="submit" name="registrarAhorro" class="btn btn-success">Registrar</button>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- End Customers Card -->

              

              

              

          </div>
          </div><!-- End Left side columns -->

          <div class="row">
              <div class="col-lg-4">
                  <div class="card">
                      <div class="filter">
                          <a class="icon" href="#" data-bs-toggle="modal" data-bs-target="#modalAñadirPendientes"><i class="bi bi-plus-circle"></i></a>
                      </div>

                      <div class="card-body">
                          <h5 class="card-title">Pendientes</h5>

                          <div class="activity">
                        <div style="height: 400px;">
         
                <?php
                $sqlGetEventos = "SELECT * FROM pendiente WHERE idUsuario='$idUsuario'";
                $resultado=mysqli_query($conexion, $sqlGetEventos);
                if($resultado){
                  while($row = $resultado->fetch_array()){
                    $nombrePendiente=$row['nombre'];
                    $montoPendiente=$row['precio'];
                    $fechaPendiente=$row['fecha'];
                 
                ?>
                  <div class="activity-item d-flex" >
                  <div class="activite-label"><?php echo($fechaPendiente); ?><br><b> <p>$<?php echo($montoPendiente); ?></p></b></div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content"><?php echo($nombrePendiente); ?> </div>
                  </div><!-- End activity item-->
                <?php
                   }
                }

                ?>
            </div>



                              <div class="modal fade" id="modalAñadirPendientes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Añadir pendiente</h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                              <form action="hogar.php" method="POST">
                                                  <div class="mb-3">
                                                      <label for="pendiente">Pendiente:</label>
                                                      <input type="text" class="form-control" id="pendiente" name="nombrePendiente" required>
                                                  </div>
                                                  <div class="mb-3">
                                                      <label for="monto">Monto:</label>
                                                      <input type="number" class="form-control" id="monto" name="montoPendiente" placeholder="Monto">
                                                  </div>
                                                  <div class="mb-3">
                                                      <label for="descripcion">Fecha:</label>
                                                      <input type="date" class="form-control" id="descripcion" name="fechaPendiente">
                                                  </div>
                                                  <button type="submit" class="btn btn-success" name="enviarPendiente">Guardar</button>
                                              </form>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                    

                  </div>
              </div><!-- End Recent Activity -->




              <div class="col-lg-4">
                  <div class="card">
                      <div class="card-body pb-0">
                          <h5 class="card-title">Gasto-Presupuesto <span> | Semanal</span></h5>
                          <div id="budgetChart" style="min-height: 400px;" class="echart"></div>
                          <script>
                              document.addEventListener("DOMContentLoaded", () => {
                                  var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                                      legend: {
                                          data: ['Gastos', 'Presupuesto']
                                      },
                                      radar: {
                                          indicator: [
                                              { name: 'Transporte', max: <?php echo $sumaTotal; ?>},
                                              { name: 'Alimentación', max: <?php echo $sumaTotal; ?> },
                                              { name: 'Vivienda', max: <?php echo $sumaTotal; ?> },
                                              { name: 'Ocio', max: <?php echo $sumaTotal; ?> },
                                          ]
                                      },
                                      series: [{
                                          name: 'Presupuesto vs Gastos',
                                          type: 'radar',
                                          data: [{
                                              value: [<?php echo $transporteGrafico; ?>, <?php echo $alimentacionGrafico; ?>, <?php echo $viviendaGrafico; ?>, <?php echo $ocioGrafico; ?>],
                                              name: 'Gastos',
                                              areaStyle: {
                                                  color: 'rgba(0, 128, 255, 0.5)'
                                              }
                                          },
                                              {
                                                  value: [<?php echo $gTransporte; ?>,<?php echo $gAlimentacion; ?>, <?php echo $gVivienda; ?>,<?php echo $gOcio; ?>],
                                                  name: 'Presupuesto',
                                                  areaStyle: {
                                                      color: 'rgba(64,255,0,0.5)'
                                                  }
                                              }]
                                      }]
                                  });
                              });


                          </script>
                      </div>
                  </div><!-- End Budget Report -->
              </div>
              <div class="col-lg-4">
                  <!-- Website Traffic -->
                  <div class="card">
                      <div class="filter">
                         
                      </div>
                      <div class="card-body pb-0">
                          <h5 class="card-title">Gráfico Gastos <span>| Semanal</span></h5>
                          <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
                          <br>
                          <script>
                              document.addEventListener("DOMContentLoaded", () => {
                                  echarts.init(document.querySelector("#trafficChart")).setOption({
                                      tooltip: {
                                          trigger: 'item'
                                      },
                                      legend: {
                                          top: '5%',
                                          left: 'center'
                                      },
                                      series: [{
                                          name: 'Access From',
                                          type: 'pie',
                                          radius: ['40%', '70%'],
                                          avoidLabelOverlap: false,
                                          label: {
                                              show: false,
                                              position: 'center'
                                          },
                                          emphasis: {
                                              label: {
                                                  show: true,
                                                  fontSize: '18',
                                                  fontWeight: 'bold'
                                              }
                                          },
                                          labelLine: {
                                              show: false
                                          },
                                          data: [{
                                              value: <?php echo($transporteGrafico)  ?>,
                                              name: 'Transporte'
                                          },
                                              {
                                                  value:  <?php echo($alimentacionGrafico)  ?>,
                                                  name: 'Alimentación'
                                              },
                                              
                                              {
                                                  value: <?php echo($viviendaGrafico)  ?>,
                                                  name: 'Vivienda'
                                              },
                                              {
                                                  value:  <?php echo($ocioGrafico)  ?>,
                                                  name: 'Ocio'
                                              }
                                          ]
                                      }]
                                  });
                              });
                          </script>
                      </div>
                  </div><!-- End Website Traffic -->
              </div>

              <div>

        </div>

              <!-- Reports -->
              <div class="col-12">
                  <div class="card">

                      <div class="filter">
                          <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                              <li class="dropdown-header text-start">
                                  <h6>Filter</h6>
                              </li>

                              <li><a class="dropdown-item" href="#">Today</a></li>
                              <li><a class="dropdown-item" href="#">This Month</a></li>
                              <li><a class="dropdown-item" href="#">This Year</a></li>
                          </ul>
                      </div>

                      <div class="card-body">
                          <h5 class="card-title">Gastos <span>/Ultimo mes</span></h5>
                        <?php
                        $sqlSeleccionarGrafica="SELECT * FROM semanasgastosh WHERE idUsuario='$idUsuario'";
                        $resultado=mysqli_query($conexion, $sqlSeleccionarGrafica);
                        $array_Grafica=array();
                        if ($resultado){
                            while($row = $resultado->fetch_array()){
                            array_push($array_Grafica, $row);
                            }
                        }
                        ?>
                          <div id="reportsChart"></div>

                          <script>
                    document.addEventListener("DOMContentLoaded", () => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Transporte',
                          data: [<?php print_r($array_Grafica[0]['transporte']); ?>,<?php print_r($array_Grafica[1]['transporte']); ?>, <?php print_r($array_Grafica[2]['transporte']); ?>, <?php print_r($array_Grafica[3]['transporte']); ?>],
                        }, {
                          name: 'Alimentacion',
                          data: [<?php print_r($array_Grafica[0]['alimentacion']); ?>,<?php print_r($array_Grafica[1]['alimentacion']); ?>, <?php print_r($array_Grafica[2]['alimentacion']); ?>, <?php print_r($array_Grafica[3]['alimentacion']); ?>]
                        }, {
                          name: 'Ocio',
                          data: [<?php print_r($array_Grafica[0]['ocio']); ?>,<?php print_r($array_Grafica[1]['ocio']); ?>, <?php print_r($array_Grafica[2]['ocio']); ?>, <?php print_r($array_Grafica[3]['ocio']); ?>]
                        },{
                          name: 'Colchon',
                          data: [<?php print_r($array_Grafica[0]['colchon']); ?>,<?php print_r($array_Grafica[1]['colchon']); ?>, <?php print_r($array_Grafica[2]['colchon']); ?>, <?php print_r($array_Grafica[3]['colchon']); ?>]
                        },{
                          name: 'Vvivienda',
                          data: [<?php print_r($array_Grafica[0]['vivienda']); ?>,<?php print_r($array_Grafica[1]['vivienda']); ?>, <?php print_r($array_Grafica[2]['vivienda']); ?>, <?php print_r($array_Grafica[3]['vivienda']); ?>] 
                        },],
                        chart: {
                          height: 350,
                          type: 'area',
                          toolbar: {
                            show: false
                          },
                        },
                        markers: {
                          size: 4
                        },
                        colors: ['#4154f1', '#2eca6a', '#ff771d','#ff3067','#F9FF30','#4AFFD6'],
                        fill: {
                          type: "gradient",
                          gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
                            stops: [0, 90, 100]
                          }
                        },
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          curve: 'smooth',
                          width: 2
                        },
                        xaxis: {
                          type: 'text',
                          categories: ["Semana 1", "Semana 2", "Semana 3", "Semana 4"]
                        },
                        tooltip: {
                          x: {
                            format: 'dd/MM/yy HH:mm'
                          },
                        }
                      }).render();
                    });
                  </script>
                          <!-- End Line Chart -->

                      </div>

                  </div>
              </div><!-- End Reports -->




              
          </div><!-- End News & Updates -->
          </div>
          </div>
      </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
      <div class="copyright">
          &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script>
      $(document).ready(function() {
      function actualizarPorcentaje() {
          var presupuestoTexto = $('#presupuesto_usuario').text().replace('$', '').replace(',', '');
          var totalGastosTexto = $('#total_gastos').text().replace('$', '').replace(',', '');
          var presupuesto = parseFloat(presupuestoTexto);
          var totalGastos = parseFloat(totalGastosTexto);
          if (!isNaN(presupuesto) && !isNaN(totalGastos) && presupuesto !== 0) {
              var porcentaje = (totalGastos / presupuesto) * 100;
              $('#porcentaje').text(porcentaje.toFixed(2) + '%');
          } else {
              $('#porcentaje').text('0%');
          }
      }
      actualizarPorcentaje();
          function actualizarPorcentajeA() {
              var ahorroTotalTexto = $('#ahorroTotal').text().replace('$', '').replace(',', '');
              var metaTexto = $('#meta').text().replace('$', '').replace(',', '');
              var ahorroTotal = parseFloat(ahorroTotalTexto);
              var meta = parseFloat(metaTexto);
              if (!isNaN(ahorroTotal) && !isNaN(meta) && meta !== 0) {
                  var porcentaje = (ahorroTotal / meta) * 100;
                  $('#porcentajeA').text(porcentaje.toFixed(2) + '%');
              } else {
                  $('#porcentajeA').text('0%');
              }
          }
          actualizarPorcentajeA();
          $('#formDefinirMeta').submit(function(event) {
              event.preventDefault();
              var ahorro = $('#ahorro').val();

              $.ajax({
                  url: 'hogar.php',
                  method: 'POST',
                  data: { ahorroEstablecido: ahorro, registrarAhorro: true },
                  success: function(response) {
                      alert('Meta guardada correctamente');
                      $('#modalDefinirMeta').modal('hide');
                      location.reload();
                  },
                  error: function(xhr, status, error) {
                      alert('Error al guardar la meta');
                      console.error(xhr.responseText);
                  }
              });
          });
      });
  </script>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>