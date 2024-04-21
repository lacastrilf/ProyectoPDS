<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("location: /ProyectoPDS/inicio/login.php");
    exit;
}
$idUsuario = $_SESSION['id_usuario'];
$conexion = new mysqli("localhost", "root", "", "base_proyecto");
$sql = "SELECT ocio FROM presupuestos WHERE id_usuario='$idUsuario'";
$resultado = $conexion->query($sql);
$dato = $resultado->fetch_assoc();

if ($dato) {
    $_SESSION['presupuesto'] = $dato['ocio'];
    $presupuesto = $dato['ocio'];
} else {
    $_SESSION['presupuesto'] = 0;
    $presupuesto = 0;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["presupuesto"])) {
    $nuevoPresupuesto = $_POST["presupuesto"];
    $sql = "SELECT * FROM presupuestos WHERE id_usuario='$idUsuario'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $sqlUpdate = "UPDATE presupuestos SET ocio=$nuevoPresupuesto WHERE id_usuario='$idUsuario'";
        $conexion->query($sqlUpdate);
    } else {
        $sqlInsert = "INSERT INTO presupuestos (id_usuario, ocio) VALUES ('$idUsuario', $nuevoPresupuesto)";
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
    $tipo = "Ocio";

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


$sql = "SELECT SUM(gasto) AS total_gastos FROM gastosI WHERE id_usuario='$idUsuario' AND tipo='Ocio'";
$resultado = $conexion->query($sql);
$totalGastos = 0;
if ($resultado->num_rows > 0) {
    $dato = $resultado->fetch_assoc();
    $totalGastos = $dato['total_gastos'];
}

//Ingresar a la base de datos los eventos especiales 
if(isset($_POST['agregarEvento'])){
  $nombreEvento=$_POST['nombreEvento'];
  $presupuestoEvento=$_POST['montoEvento'];
  $fecha=$_POST['fechaEvento'];
  $sqlInsertEvento = "INSERT INTO eventosespeciales VALUES ('null','$idUsuario','$nombreEvento','$presupuestoEvento','$fecha')";
  $ejecutar3 = mysqli_query($conexion, $sqlInsertEvento);
}


$conexion->close();
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
      <a href="../../inicio/index.php" class="logo d-flex align-items-center">
          <span class="d-none d-lg-block">INICIO</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
      </div><!-- End Logo -->



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
      <h1>Ocio</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../estudiante.php">Resumen</a></li>
          <li class="breadcrumb-item active">Ocio</li>
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
                                          <input type="number" class="form-control" id="gasto" name="gasto" required>
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
                                                          <input type="number" class="form-control" id="presupuesto" name="presupuesto"   required>
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

             <!-- Top Selling -->
             <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="filter">
                <a class="icon" href="#" data-bs-toggle="modal" data-bs-target="#modalAñadirEvento"><i class="bi bi-plus-circle"></i></a>
                </div>

                <div class="card-body pb-0">
                  <h5 class="card-title">Eventos Especiales <span>| Semanales</span></h5>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Evento</th>
                        <th scope="col">Presupuesto</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Estado</th>
                      </tr>
                    </thead>
                    <tbody>

                    <?php
                    $conexion = new mysqli("localhost", "root", "", "base_proyecto");
                    $sqlGetEventos = "SELECT * FROM eventosespeciales WHERE idUsuario='$idUsuario'";
                    $resultado=mysqli_query($conexion, $sqlGetEventos);
                    if($resultado){
                      while($row = $resultado->fetch_array()){
                        $nombreEvento=$row['nombreEvento'];
                        $montoEvento=$row['precio'];
                        $fecha=$row['fecha'];
                     
                    ?>
                      <tr>
                        <td><?php echo($nombreEvento)?></td>
                        <td>$<?php echo($montoEvento)?></td>
                        <td class="fw-bold"><?php echo($fecha)?></td>
                        <td>Pendiente</td>
                      </tr>
                    <?php
                       }
                    }

                    ?>
                      
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

            <!--Modal Añadir Eventos-->
            <div class="modal fade" id="modalAñadirEvento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Nuevo Gasto</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <form action="ocio.php" method="POST">
                                      <div class="mb-3">
                                          <label for="gasto">Evento:</label>
                                          <input type="text" class="form-control" id="gasto" name="nombreEvento" required>
                                      </div>
                                      <div class="mb-3">
                                          <label for="descripcion">Monto:</label>
                                          <input type="number" class="form-control" id="descripcion" name="montoEvento" placeholder="Descripción">
                                      </div>
                                      <div class="mb-3">
                                          <label for="descripcion">Fecha:</label>
                                          <input type="date" class="form-control" id="descripcion" name="fechaEvento" placeholder="Descripción">
                                      </div>
                                      <button type="submit" class="btn btn-success" name="agregarEvento">Guardar</button>
                                  </form>
                              </div>
                          </div>
                      </div>
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
                  <h5 class="card-title">Reports <span>/Today</span></h5>

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

            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

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
                  <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                  <table class="table table-borderless datatable">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row"><a href="#">#2457</a></th>
                        <td>Brandon Jacob</td>
                        <td><a href="#" class="text-primary">At praesentium minu</a></td>
                        <td>$64</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2147</a></th>
                        <td>Bridie Kessler</td>
                        <td><a href="#" class="text-primary">Blanditiis dolor omnis similique</a></td>
                        <td>$47</td>
                        <td><span class="badge bg-warning">Pending</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2049</a></th>
                        <td>Ashleigh Langosh</td>
                        <td><a href="#" class="text-primary">At recusandae consectetur</a></td>
                        <td>$147</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Angus Grady</td>
                        <td><a href="#" class="text-primar">Ut voluptatem id earum et</a></td>
                        <td>$67</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                      </tr>
                      <tr>
                        <th scope="row"><a href="#">#2644</a></th>
                        <td>Raheem Lehner</td>
                        <td><a href="#" class="text-primary">Sunt similique distinctio</a></td>
                        <td>$165</td>
                        <td><span class="badge bg-success">Approved</span></td>
                      </tr>
                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Recent Sales -->

           

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">

          <!-- Recent Activity -->
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
              <h5 class="card-title">Recent Activity <span>| Today</span></h5>

              <div class="activity">

                <div class="activity-item d-flex">
                  <div class="activite-label">32 min</div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">56 min</div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    Voluptatem blanditiis blanditiis eveniet
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 hrs</div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    Voluptates corrupti molestias voluptatem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">1 day</div>
                  <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                  <div class="activity-content">
                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2 days</div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content">
                    Est sit eum reiciendis exercitationem
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">4 weeks</div>
                  <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                  <div class="activity-content">
                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                  </div>
                </div><!-- End activity item-->

              </div>

            </div>
          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
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

            <div class="card-body pb-0">
              <h5 class="card-title">Budget Report <span>| This Month</span></h5>

              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                    legend: {
                      data: ['Allocated Budget', 'Actual Spending']
                    },
                    radar: {
                      // shape: 'circle',
                      indicator: [{
                          name: 'Sales',
                          max: 6500
                        },
                        {
                          name: 'Administration',
                          max: 16000
                        },
                        {
                          name: 'Information Technology',
                          max: 30000
                        },
                        {
                          name: 'Customer Support',
                          max: 38000
                        },
                        {
                          name: 'Development',
                          max: 52000
                        },
                        {
                          name: 'Marketing',
                          max: 25000
                        }
                      ]
                    },
                    series: [{
                      name: 'Budget vs spending',
                      type: 'radar',
                      data: [{
                          value: [4200, 3000, 20000, 35000, 50000, 18000],
                          name: 'Allocated Budget'
                        },
                        {
                          value: [5000, 14000, 28000, 26000, 42000, 21000],
                          name: 'Actual Spending'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Budget Report -->

          <!-- Website Traffic -->
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

            <div class="card-body pb-0">
              <h5 class="card-title">Website Traffic <span>| Today</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

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
                          value: 1048,
                          name: 'Search Engine'
                        },
                        {
                          value: 735,
                          name: 'Direct'
                        },
                        {
                          value: 580,
                          name: 'Email'
                        },
                        {
                          value: 484,
                          name: 'Union Ads'
                        },
                        {
                          value: 300,
                          name: 'Video Ads'
                        }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Website Traffic -->


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
                  url: 'ocio.php',
                  method: 'POST',
                  data: { presupuesto: presupuesto },
                  success: function(response) {
                      alert('Presupuesto guardado correctamente');
                      $('#presupuesto_usuario').text("$" + presupuesto);
                      $('#modalEditarPresupuesto').modal('hide');
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
                  url: 'ocio.php',
                  method: 'POST',
                  data: { gasto: monto, descripcion: descripcion },
                  success: function(response) {
                      alert('Gasto guardado correctamente');
                      $('#modalAñadirGasto').modal('hide');
                      var totalActual = parseFloat($('#total_gastos').text().replace('$', ''));
                      var nuevoTotal = totalActual + parseFloat(monto);
                      $('#total_gastos').text('$' + nuevoTotal);
                      actualizarPorcentaje();
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