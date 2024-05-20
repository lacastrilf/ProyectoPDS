<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("location: /ProyectoPDS/inicio/login.php");
    exit;
}
$idUsuario = $_SESSION['id_usuario'];
$conexion = new mysqli("localhost", "root", "", "base_proyecto");
$sql = "SELECT colchon FROM presupuestos WHERE id_usuario='$idUsuario'";
$resultado = $conexion->query($sql);
$dato = $resultado->fetch_assoc();

if ($dato) {
    $_SESSION['presupuesto'] = $dato['colchon'];
    $presupuesto = $dato['colchon'];
} else {
    $_SESSION['presupuesto'] = 0;
    $presupuesto = 0;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["presupuesto"])) {
    $nuevoPresupuesto = $_POST["presupuesto"];
    $sql = "SELECT * FROM presupuestos WHERE id_usuario='$idUsuario'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $sqlUpdate = "UPDATE presupuestos SET colchon=$nuevoPresupuesto WHERE id_usuario='$idUsuario'";
        $conexion->query($sqlUpdate);
    } else {
        $sqlInsert = "INSERT INTO presupuestos (id_usuario, colchon) VALUES ('$idUsuario', $nuevoPresupuesto)";
        $conexion->query($sqlInsert);
    }
    $_SESSION['presupuesto'] = $nuevoPresupuesto;
    $conexion->close();
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["gasto"])) {
    $gasto = $_POST['gasto'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $tipo = "Colchon";

    $sqlInsertGasto = "INSERT INTO gastosI (id_usuario, tipo, gasto, descripcion) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sqlInsertGasto);
    $stmt->bind_param("ssds", $idUsuario, $tipo, $gasto, $descripcion);
    if ($stmt->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } else {
        echo "Error al guardar el gasto: " . $conexion->error;
    }
}

//Generar total de gastos
$sql = "SELECT SUM(gasto) AS total_gastos FROM gastosI WHERE id_usuario='$idUsuario' AND tipo='Colchon'";
$resultado = $conexion->query($sql);
$totalGastos = 0;
if ($resultado->num_rows > 0) {
    $dato = $resultado->fetch_assoc();
    $totalGastos = $dato['total_gastos'];
}
//Cambiar total de gastos en la base de datos
$sqlUpdate="UPDATE diagramagastosestudiante SET colchon='$totalGastos' WHERE idUsuario='$idUsuario'";
$ejecutar3 = mysqli_query($conexion, $sqlUpdate);

//crear notificacion de exceso
if ($totalGastos > $presupuesto) {
    $sqlNotificacion = "SELECT * FROM notificaciones WHERE id_usuario='$idUsuario' AND tipo='Colchon'";
    $resultadoNotificacion = $conexion->query($sqlNotificacion);

    if ($resultadoNotificacion->num_rows == 0) {
        $mensaje = "Has excedido tus gastos de colchon esta semana.";
        $titulo = "Presupuesto superado";
        $fecha = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual
        $sqlInsertNotificacion = "INSERT INTO notificaciones (id_usuario, tipo, icono, color, titulo, mensaje, fecha) VALUES ('$idUsuario', 'Colchon', 'exclamation-circle', 'warning', '$titulo', '$mensaje', '$fecha')";
        if ($conexion->query($sqlInsertNotificacion) === TRUE) {
            echo "Notificación insertada correctamente.";
            error_log("Notificación insertada correctamente: " . $sqlInsertNotificacion);
        } else {
            echo "Error al insertar la notificación: " . $conexion->error;
            error_log("Error al insertar la notificación: " . $conexion->error);
        }
    }
}

if ($totalGastos <= $presupuesto) {
    // Si los gastos son menores o iguales al presupuesto, eliminar cualquier notificación existente
    $sqlNotificacion = "SELECT * FROM notificaciones WHERE id_usuario='$idUsuario' AND tipo='Colchon'";
    $resultadoNotificacion = $conexion->query($sqlNotificacion);

    if ($resultadoNotificacion->num_rows > 0) {
        $sqlDeleteNotificacion = "DELETE FROM notificaciones WHERE id_usuario='$idUsuario' AND tipo='Colchon'";
        if ($conexion->query($sqlDeleteNotificacion) === TRUE) {
            echo "Notificación eliminada correctamente.";
            error_log("Notificación eliminada correctamente: " . $sqlDeleteNotificacion);
        } else {
            echo "Error al eliminar la notificación: " . $conexion->error;
            error_log("Error al eliminar la notificación: " . $conexion->error);
        }
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
            <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number" id="notificationCount"></span>
                </a><!-- End Notification Icon -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" aria-labelledby="navbarDropdown" id="notificationDropdown">

                </ul><!-- End Notification Dropdown Items -->
            </li><!-- End Notification Nav -->

            <script>
                $(document).ready(function() {
                    // Función para cargar las notificaciones
                    function loadNotifications() {
                        $.ajax({
                            url: '../notificaciones.php',
                            type: 'GET',
                            dataType: 'json', // Especificamos que esperamos JSON como respuesta
                            success: function(response) {
                                // Actualizar el contador de notificaciones
                                $('#notificationCount').text(response.length);

                                // Vaciar el contenedor de notificaciones
                                $('#notificationDropdown').empty();

                                $('#notificationDropdown').append(`
                                    <li class="dropdown-header">
                                            Tienes ${response.length} notificaciones nuevas
                                            <a href="#" style="text-decoration: none;"><span class="badge rounded-pill bg-primary p-2 ms-2" style="opacity: 0; border: none;" disabled>Ver todo</span></a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                `);

                                // Agregar las nuevas notificaciones
                                response.forEach(function(notification) {
                                    $('#notificationDropdown').append(`
                                        <li class="notification-item">
                                            <i class="bi bi-${notification.icon} text-${notification.color}"></i>
                                            <div>
                                                <h4>${notification.title}</h4>
                                                <p>${notification.message}</p>
                                                <p>${notification.time}</p>
                                            </div>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    `);
                                });
                            },
                            error: function(xhr, status, error) {
                                console.error('Error al cargar las notificaciones:', error);
                            }
                        });
                    }

                    // Cargar las notificaciones al cargar la página
                    loadNotifications();

                    // Actualizar las notificaciones cada cierto tiempo (por ejemplo, cada 5 segundos)
                    setInterval(function() {
                        loadNotifications();
                    }, 5000);
                });
            </script>


            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                    <span class="d-none d-md-block dropdown-toggle ps-2"><?php
                        if(isset($_SESSION['usuario'])) {
                            echo $_SESSION['usuario'];
                        }
                        ?>
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php
                            if(isset($_SESSION['usuario'])) {
                                echo $_SESSION['usuario'];
                            }
                            ?></h6>
                        <span><?php echo($_SESSION['codigo']); ?></span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
               

                  
                   

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="../estudiante.php">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Resumen</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="../Estudiante/transporte.php">
                <i class="bi bi-bus-front"></i>
                <span>Transporte</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="../Estudiante/alimentacion.php">
                <i class="bi bi-egg-fried"></i>
                <span>Alimentación</span>
            </a>
        </li><!-- End F.A.Q Page Nav -->


        <li class="nav-item">
            <a class="nav-link collapsed" href="../Estudiante/ocio.php">
                <i class="bi bi-controller"></i>
                <span>Ocio</span>
            </a>
        </li><!-- End Register Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="../Estudiante/colchon.php">
                <i class="bi bi-piggy-bank"></i>
                <span>Colchón</span>
            </a>
        </li><!-- End Login Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="../../inicio/login.php">
                <i class="bi bi-person-bounding-box"></i>
                <span>Login</span>
            </a>
        </li>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Colchon</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../estudiante.php">Resumen</a></li>
          <li class="breadcrumb-item active">Colchon</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-6 col-md-6">
              <div class="card info-card sales-card">

                  <div class="filter">
                      <a class="icon" href="#" data-bs-toggle="modal" data-bs-target="#modalAñadirGasto"><i class="bi bi-plus-circle"></i></a>
                  </div>
                  <div class="modal fade" id="modalAñadirGasto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Nuevo Gasto</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <form id="formNuevoGasto">
                                      <div class="mb-3">
                                          <label for="gasto">Monto:</label>
                                          <input type="number" class="form-control" id="gasto" name="gasto" min="0" required oninput="checkMax(this, <?php echo $presupuesto; ?>)">
                                          <div id="mensaje-error" class="mensaje-error"></div>
                                          <script>
                                              function checkMax(input, presupuesto) {
                                                  var value = parseFloat(input.value);
                                                  var mensajeError = document.getElementById('mensaje-error');
                                                  if (value > presupuesto) {
                                                      mensajeError.innerText = "El valor no puede ser mayor que el presupuesto de <?php echo $presupuesto; ?>";
                                                      input.value = presupuesto;
                                                      mensajeError.style.opacity = 1;
                                                  } else {
                                                      mensajeError.innerText = "";
                                                      mensajeError.style.opacity = 0;
                                                  }
                                              }
                                          </script>
                                      </div>
                                      <div class="mb-3">
                                          <label for="descripcion">Descripción:</label>
                                          <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción">
                                      </div>
                                      <button type="submit" class="btn btn-success">Guardar</button>
                                  </form>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="card-body">
                      <h5 class="card-title">Gastos realizados</h5>

                      <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                              <i class="bi bi-cart"></i>
                          </div>
                          <div class="ps-3">
                              <h6 id="total_gastos" >$<?php echo $totalGastos == 0 ? '0' : $totalGastos; ?></h6></h6>
                              <span id="porcentaje" class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">del presupuesto</span>
                          </div>
                      </div>
                  </div>

              </div>
            </div><!-- End Sales Card -->
              <!-- Revenue Card -->
              <div class="col-xxl-6 col-md-6">
                  <div class="card info-card revenue-card">
                      <div class="card-body">
                          <h5 class="card-title">Presupuesto <span>| Esta Semana</span></h5>
                          <div class="d-flex align-items-center">
                              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                  <i class="bi bi-currency-dollar"></i>
                              </div>
                              <div class="ps-3">

                                  <h6 id="presupuesto_usuario">$<?php echo $presupuesto; ?></h6>
                                  <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarPresupuesto">
                                      Editar Presupuesto
                                  </button>

                                  <!-- Modal para editar el presupuesto -->
                                  <div class="modal fade" id="modalEditarPresupuesto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h5 class="modal-title" id="exampleModalLabel">Editar Presupuesto</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                  <!-- Formulario para editar el presupuesto -->
                                                  <form id="formEditarPresupuesto">
                                                      <div class="mb-3">
                                                          <label for="presupuesto">Nuevo Presupuesto:</label>
                                                          <input class="form-control" id="presupuesto" name="presupuesto" type="number" min="0" required>
                                                      </div>
                                                      <button type="submit" class="btn btn-success">Guardar</button>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                              </div>
                          </div>
                      </div>

                  </div>
              </div><!-- End Revenue Card -->

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
                  <h5 class="card-title">Grafica Gastos <span>/Semanal</span></h5>

                  <!-- Line Chart -->
                  <div id="reportsChart"></div>

                  <script>
                    document.addEventListener("DOMContentLoaded", () => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Sales',
                          data: [31, 40, 28, 51, 42, 82, 56],
                        }, {
                          name: 'Revenue',
                          data: [11, 32, 45, 32, 34, 52, 41]
                        }, {
                          name: 'Customers',
                          data: [15, 11, 32, 18, 9, 24, 11]
                        }],
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
                        colors: ['#4154f1', '#2eca6a', '#ff771d'],
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
                          type: 'datetime',
                          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
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

          
          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Recent Activity -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actividad reciente<span>| Esta Semana</span></h5>

                    <div class="activity">
                      <div style="height: 390px;">
                        <?php
                        $sqlGetEventos = "SELECT * FROM gastosi WHERE id_usuario='$idUsuario' AND tipo='Colchon'";
                        $resultado=mysqli_query($conexion, $sqlGetEventos);
                        if($resultado){
                            while($row = $resultado->fetch_array()){
                                $descripcion=$row['descripcion'];
                                $monto=$row['gasto'];
                                $fecha=$row['fecha'];

                                ?>
                                <div class="activity-item d-flex" >
                                    <div class="activite-label"><?php echo($fecha); ?><br><b> <p>$<?php echo($monto); ?></p></b></div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content"><?php echo($descripcion); ?> </div>

                                </div><!-- End activity item-->
                                <?php
                            }
                        }

                        ?>
                      </div>
                    </div>

                </div>
            </div><!-- End Recent Activity -->

        

          </div><!-- End News & Updates -->

        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script>
      $(document).ready(function() {
          $('#formEditarPresupuesto').submit(function(event) {
              event.preventDefault();
              var presupuesto = $('#presupuesto').val();

              $.ajax({
                  url: 'colchon.php',
                  method: 'POST',
                  data: { presupuesto: presupuesto },
                  success: function(response) {
                      alert('Presupuesto guardado correctamente');
                      $('#modalEditarPresupuesto').modal('hide');
                      location.reload();
                  },
                  error: function(xhr, status, error) {
                      alert('Error al guardar el presupuesto');
                      console.error(xhr.responseText);
                  }
              });
          });
          $('#formNuevoGasto').submit(function(event) {
              event.preventDefault();
              var monto = $('#gasto').val();
              var descripcion = $('#descripcion').val();
              $.ajax({
                  url: 'colchon.php',
                  method: 'POST',
                  data: { gasto: monto, descripcion: descripcion },
                  success: function(response) {
                      alert('Gasto guardado correctamente');
                      $('#modalAñadirGasto').modal('hide');
                      location.reload();
                  },
                  error: function(xhr, status, error) {
                      alert('Error al guardar el gasto');
                      console.error(xhr.responseText);
                  }
              });
          });
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
      });
  </script>
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