<?php 
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$basedata='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);
$lte= mysqli_connect($server,$username,$password,$basedata);
?>
<?php 
session_start();
require '../../Inicio/template/pages/samples/database.php';
if(isset($_SESSION['servicio_id'])){
  $records=$conn->prepare('SELECT id_servicio, usuario_servicio, clave_servicio FROM servicio WHERE id_servicio=:id');  
$records->bindParam(':id',$_SESSION['servicio_id']);
$records->execute();
$results = $records->fetch(PDO::FETCH_ASSOC);

$user=null;

if(count($results)>0){
$user=$results;
}
}

date_default_timezone_set('America/La_Paz');
$hoy = date("F j");

// Función para contar registros en una tabla
function contarRegistros($conexion, $tabla) {
    $sql = "SELECT COUNT(*) AS total FROM $tabla";
    $result = mysqli_query($conexion, $sql);
    return mysqli_fetch_assoc($result)['total'];
}

// Contadores
$totalAdministradores = contarRegistros($conexion, 'administrador');
$totalUsuarios = contarRegistros($conexion, 'usuario');
$totalPacientes = contarRegistros($lte, 'paciente');

// Función para obtener el médico con más o menos pacientes
function obtenerMedicoExtremo($conexion, $lte, $orden = 'DESC') {
    $sql = "SELECT medico_id AS valor, COUNT(*) AS veces
            FROM paciente
            GROUP BY medico_id
            HAVING veces = (
                SELECT COUNT(*) 
                FROM paciente
                GROUP BY medico_id
                ORDER BY COUNT(*) $orden
                LIMIT 1
            )";
    $result = mysqli_query($lte, $sql);
    $fila = mysqli_fetch_assoc($result);

    if (!$fila) return null;

    $id = $fila['valor'];
    $sqlNombre = "SELECT nombrec FROM usuario WHERE id='$id'";
    $resultNombre = mysqli_query($conexion, $sqlNombre);
    $filaNombre = mysqli_fetch_assoc($resultNombre);

    return $filaNombre['nombrec'] ?? null;
}

// Médico con más y menos pacientes
$medicoConMasPacientes = obtenerMedicoExtremo($conexion, $lte, 'DESC');
$medicoConMenosPacientes = obtenerMedicoExtremo($conexion, $lte, 'ASC');

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Servicio</title>
  <!-- base:css -->
  <link rel="stylesheet" href="vendors/typicons/typicons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../Inicio/template/images/LOGO.png" />
</head>
<body>

  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand brand-logo" href="index.php"></a>
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="images/faces/servicio.png" alt="profile"/>
              <span class="nav-profile-name"><?= $user['usuario_servicio'] ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="pages/forms/privacidad.php">
                <i class="typcn typcn-cog-outline text-primary"></i>
                Privacy
              </a>
              <a class="dropdown-item" href="../../Inicio/template/pages/samples/logout.php">
                <i class="typcn typcn-eject text-primary"></i>
                Logout
              </a>
            </div>
          </li>
    
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-date dropdown">
            <a class="nav-link d-flex justify-content-center align-items-center" href="javascript:;">
              <h6 class="date mb-0">Today : <?php echo $hoy;?></h6>
              <i class="mdi mdi-calendar-today"></i>
            </a>
          </li>
         
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="typcn typcn-th-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <nav class="navbar-breadcrumb col-xl-12 col-12 d-flex flex-row p-0">
      <div class="navbar-links-wrapper d-flex align-items-stretch">
        <div class="nav-link">
          <a href="pages/forms/registroadmin.php"><i class="typcn typcn-briefcase"></i></a>
        </div>
        <div class="nav-link">
          <a href="pages/forms/registromedico.php"><i class="typcn typcn-headphones"></i></a>
        </div>
        <div class="nav-link">
          <a href="pages/forms/registropaciente.php"><i class="typcn typcn-clipboard"></i></a>
        </div>
      
      </div>

      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item ml-0">
            <h4 class="mb-0">Servicio</h4>
          </li>
          
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-search d-none d-md-block mr-0">
          </li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="typcn typcn-cog-outline"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close typcn typcn-times"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>

      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="typcn typcn-home menu-icon"></i>
              <span class="menu-title">Inicio</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="typcn typcn-briefcase menu-icon"></i>
              <span class="menu-title">Administradores</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/forms/listaadmin.php">Lista</a></li>
                <li class="nav-item"> <a class="nav-link"href="pages/forms/registroadmin.php">Registro</a></li> 
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="typcn typcn-headphones menu-icon"></i>
              <span class="menu-title">Operadores</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="pages/forms/listamedico.php">Lista</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/forms/registromedico.php">Registro</a></li> 
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="typcn typcn-clipboard menu-icon"></i>
              <span class="menu-title">Pacientes</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="pages/forms/listapaciente.php">Lista</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/forms/registropaciente.php">Registro</a></li> 
              </ul>
            </div>
          </li>
        
          <li class="nav-item">
            <a class="nav-link"  href="pages/forms/privacidad.php" >
              <i class="typcn typcn-cog-outline menu-icon"></i>
              <span class="menu-title">Privacidad</span>
              
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
        <div class="container">
  <!-- Primera fila -->
  <div class="row">
    <!-- Total Administradores -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
            <div>
              <p class="mb-2 text-md-center text-lg-left">Total Administradores</p>
              <h1 class="mb-0"><?php echo $totalAdministradores;?></h1>
            </div>
            <i class="typcn typcn-briefcase icon-xl text-secondary"></i>
          </div>
          <canvas id="expense-chart" height="80"></canvas>
        </div>
      </div>
    </div>

    <!-- Total Operadores -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
            <div>
              <p class="mb-2 text-md-center text-lg-left">Total Operadores</p>
              <h1 class="mb-0"><?php echo $totalUsuarios;?></h1>
            </div>
            <i class="typcn typcn-headphones icon-xl text-secondary"></i>
          </div>
          <canvas id="budget-chart" height="80"></canvas>
        </div>
      </div>
    </div>

    <!-- Total Pacientes -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-4">
            <div>
              <p class="mb-2 text-md-center text-lg-left">Total Pacientes</p>
              <h1 class="mb-0"><?php echo $totalPacientes;?></h1>
            </div>
            <i class="typcn typcn-clipboard icon-xl text-secondary"></i>
          </div>
          <canvas id="balance-chart" height="80"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Segunda fila -->
  <div class="row">
    <!-- Tabla Administradores -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <p class="mb-2 text-md-center text-lg-left">Administradores</p>
          <div class="table-responsive pt-3">
            <table class="table table-striped project-orders-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre de Usuario</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $contador=1;
                $sqlla="SELECT * FROM administrador";
                $resultla=mysqli_query($conexion,$sqlla);          
                while($mostrar=mysqli_fetch_array($resultla)){
                ?>
                <tr>
                  <td><?php echo $contador ?></td>
                  <td><?php echo $mostrar['nombre'] ?></td>
                </tr>
                <?php 
                  $contador++;
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla Operadores -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <p class="mb-2 text-md-center text-lg-left">Operadores</p>
          <div class="table-responsive pt-3">
            <table class="table table-striped project-orders-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre y Apellidos</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $contador=1;
                $sqllop="SELECT * FROM usuario";
                $resultop=mysqli_query($conexion,$sqllop);          
                while($mostrarop=mysqli_fetch_array($resultop)){
                ?>
                <tr>
                  <td><?php echo $contador ?></td>
                  <td><?php echo $mostrarop['nombrec'] ?></td>
                </tr>
                <?php 
                  $contador++;
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla Pacientes -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <p class="mb-2 text-md-center text-lg-left">Pacientes</p>
          <div class="table-responsive pt-3">
            <table class="table table-striped project-orders-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre y Apellidos</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $contador=1;
                $sqllpa="SELECT * FROM paciente";
                $resultpa=mysqli_query($lte,$sqllpa);          
                while($mostrarpa=mysqli_fetch_array($resultpa)){
                ?>
                <tr>
                  <td><?php echo $contador ?></td>
                  <td><?php echo $mostrarpa['patient_name'] ?></td>
                </tr>
                <?php 
                  $contador++;
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tercera fila -->
  <div class="row">
    <!-- Gráfico Doughnut -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <h4 class="card-title">Operadores-Pacientes</h4>
          <canvas id="doughnutChart" width="400" height="400"></canvas>
        </div>
      </div>
    </div>

    <!-- Gráfico Pie -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card h-100">
        <div class="card-body">
          <h4 class="card-title">Administradores-Operadores-Pacientes</h4>
          <canvas id="pieChart" width="400" height="400"></canvas>
        </div>
      </div>
    </div>

    <!-- Resumen Médicos -->
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card shadow-sm border-primary h-100">
        <div class="card-body text-center">
          <h5 class="card-title text-primary mb-4">📋 Resumen de Médicos</h5>
          <div class="row">
            <div class="col-6 border-end">
              <h6 class="text-success">👨‍⚕️ Más Pacientes</h6>
              <p class="h5 fw-bold mb-1"><?php echo $medicoConMasPacientes ?? 'No disponible'; ?></p>
              <small class="text-muted">Pacientes: <?php echo $filamax['veces'] ?? 0; ?></small>
            </div>
            <div class="col-6">
              <h6 class="text-danger">👩‍⚕️ Menos Pacientes</h6>
              <p class="h5 fw-bold mb-1"><?php echo $medicoConMenosPacientes ?? 'No disponible'; ?></p>
              <small class="text-muted">Pacientes: <?php echo $filamin['veces'] ?? 0; ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

      

        
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">CALCIVIEW © 2025 <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank"></a></span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center text-muted"><a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank"></a>Dicom.Inteligencia Artificial</span>
                    </div>
                </div>    
            </div>        
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  

  <!-- base:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Chart.js DataLabels Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script>
  Chart.register(ChartDataLabels); // Registrar plugin de etiquetas

  // Configuración global del plugin DataLabels
  Chart.defaults.set('plugins.datalabels', {
    color: '#fff',
    font: {
      weight: 'bold',
      size: 14
    },
    formatter: (value, ctx) => {
      return value.toFixed(1) + '%';
    }
  });

  // Pie Chart: porcentaje de administradores, operadores y pacientes
  var ctxP = document.getElementById("pieChart").getContext('2d');
  var myPieChart = new Chart(ctxP, {
    type: 'pie',
    data: {
      labels: ["Administradores %", "Operadores %", "Pacientes %"],
      datasets: [{
        data: [
          (parseInt("<?php echo $totalAdministradores; ?>") * 100) / (parseInt("<?php echo $totalAdministradores + $totalUsuarios + $totalPacientes; ?>")),
          (parseInt("<?php echo $totalUsuarios; ?>") * 100) / (parseInt("<?php echo $totalAdministradores + $totalUsuarios + $totalPacientes; ?>")),
          (parseInt("<?php echo $totalPacientes; ?>") * 100) / (parseInt("<?php echo $totalAdministradores + $totalUsuarios + $totalPacientes; ?>"))
        ],
        backgroundColor: ["#F7464A", "#46BFBD", "#FDB45C"],
        hoverBackgroundColor: ["#FF5A5E", "#5AD3D1", "#FFC870"]
      }]
    },
    options: {
      responsive: true,
      animation: {
        animateScale: true,
        animateRotate: true
      },
      plugins: {
        datalabels: {
          formatter: (value) => value.toFixed(1) + '%'
        },
        legend: {
          position: 'bottom',
          labels: {
            font: {
              size: 14
            }
          }
        }
      }
    }
  });

  // Doughnut Chart: médicos con máximo, mínimo pacientes y total pacientes
  var ctxD = document.getElementById("doughnutChart").getContext('2d');
  var myDoughnutChart = new Chart(ctxD, {
    type: 'doughnut',
    data: {
      labels: [
        "Max: <?php echo $medicoConMasPacientes ?? 'No disponible'; ?>",
        "Min: <?php echo $medicoConMenosPacientes ?? 'No disponible'; ?>",
        "Pacientes Total"
      ],
      datasets: [{
        data: [
          "<?php echo $filamax['veces'] ?? 0; ?>",
          "<?php echo $filamin['veces'] ?? 0; ?>",
          "<?php echo $totalPacientes; ?>"
        ],
        backgroundColor: ["#ed2a4f", "#ed672a", "#2ab1ed"],
        hoverBackgroundColor: ["#ff6384", "#ff9f40", "#36a2eb"]
      }]
    },
    options: {
      responsive: true,
      animation: {
        animateScale: true,
        animateRotate: true
      },
      plugins: {
        datalabels: {
          color: '#fff',
          formatter: (value) => value,
          font: {
            weight: 'bold',
            size: 14
          }
        },
        legend: {
          position: 'bottom',
          labels: {
            font: {
              size: 14
            }
          }
        }
      }
    }
  });
</script>


  <!-- End plugin js for this page-->
  <!-- inject:js -->
 
  <!-- End custom js for this page-->
</body>

</html>

