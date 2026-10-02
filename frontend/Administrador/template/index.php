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
if(isset($_SESSION['admin_id'])){
  $records=$conn->prepare('SELECT id_administrador, usuario_admin, clave_admin FROM administrador WHERE id_administrador=:id');  
$records->bindParam(':id',$_SESSION['admin_id']);
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
  <title>ADMINISTRADOR</title>
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
              <img src="images/faces/admin.jpg" alt="profile"/>
              <span class="nav-profile-name"><?= $user['usuario_admin'] ?></span>
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
          <a href="pages/forms/registromedico.php"><i class="typcn typcn-headphones"></i></a>
        </div>
        <div class="nav-link">
          <a href="pages/forms/registropaciente.php"><i class="typcn typcn-clipboard"></i></a>
        </div>
      
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item ml-0">
            <h4 class="mb-0">Administrador</h4>
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
          

        <div class="row">
          
           
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
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
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
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
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left">Operador-Paciente</p>
                    </div>
                  </div>
                
                  <canvas id="doughnutChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          




          <div class="row">
           

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <p class="mb-2 text-md-center text-lg-left">Operadores</p>
                <div class="table-responsive pt-3">
                  <table class="table table-striped project-orders-table">
                    <thead>
                      <tr>
                       
                        <th>#</th>
                        <th>Nombre y Apellidos</th>
                       
                      </tr>
                    </thead>
                    <?php 
                    $contador=1;
                    $sqllop="SELECT * from usuario ";
                    $resultop=mysqli_query($conexion,$sqllop);          
                    while($mostrarop=mysqli_fetch_array($resultop)){
                    ?>
                    <tbody>
                      <tr>
                        
                      <td><?php echo $contador; ?></td>
		                	<td><?php echo $mostrarop['nombrec'] ?></td>
                      </tr>
                    </tbody>
                    <?php 
                    $contador++;
                          }
                          $contador=1;
                    ?>
                  </table>
                </div>
                </div>
              </div>
            </div>

          <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <p class="mb-2 text-md-center text-lg-left">Pacientes</p>
                <div class="table-responsive pt-3">
                  <table class="table table-striped project-orders-table">
                    <thead>
                      <tr>
                       
                        <th>#</th>
                        <th>Nombre y Apellidos</th>
                        
                       
                      </tr>
                    </thead>
                    <?php 
                    $sqllpa="SELECT * from paciente ";
                    $resultpa=mysqli_query($lte,$sqllpa);          
                    while($mostrarpa=mysqli_fetch_array($resultpa)){
                    ?>
                    <tbody>
                      <tr>
                        
                      <td><?php echo $contador ?></td>
                      <td> <?php echo $mostrarpa['patient_name']; ?></td>
                      </tr>
                    </tbody>
                    <?php 
                    $contador++;
                          }
                    ?>
                  </table>
                </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <p class="mb-2 text-md-center text-lg-left">Operadores-Pacientes</p>
                <canvas id="barChart" ></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->

  <!-- End plugin js for this page -->
  
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script>
// Bar Chart Mejorado
var ctxB = document.getElementById("barChart").getContext('2d');
var myBarChart = new Chart(ctxB, {
  type: 'bar',
  data: {
    labels: [
      "Max: <?php echo $medicoConMasPacientes ?? 'No disponible'; ?>",
      "Min: <?php echo $medicoConMenosPacientes ?? 'No disponible'; ?>",
    ],
    datasets: [{
      label: 'Cantidad de Pacientes',
      data: [
       "<?php echo $filamax['veces'] ?? 0; ?>",
          "<?php echo $filamin['veces'] ?? 0; ?>",
      ],
      backgroundColor: [
        'rgba(255, 99, 132, 0.7)',  // Color más bonito
        'rgba(54, 162, 235, 0.7)'   // Otro color bonito
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)'
      ],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      title: {
        display: true,
        text: 'Médicos con Máximo y Mínimo de Pacientes',
        font: {
          size: 18
        }
      },
      legend: {
        display: false
      },
      tooltip: {
        backgroundColor: 'rgba(0,0,0,0.7)',
        titleFont: {
          size: 14
        },
        bodyFont: {
          size: 12
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1
        },
        grid: {
          color: 'rgba(200,200,200,0.2)'
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

// Doughnut Chart Mejorado
var ctxD = document.getElementById("doughnutChart").getContext('2d');
var myDoughnutChart = new Chart(ctxD, {
  type: 'doughnut',
  data: {
    labels: ["Operadores", "Pacientes"],
    datasets: [{
      data: [
        <?php echo $totalUsuarios;?>, 
        <?php echo $totalPacientes;?>
      ],
      backgroundColor: [
        '#36A2EB',  // Azul
        '#FF6384'   // Rosado
      ],
      hoverBackgroundColor: [
        '#36A2EB',
        '#FF6384'
      ],
      borderWidth: 2,
      borderColor: '#fff'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      title: {
        display: true,
        text: 'Distribución Operadores vs Pacientes',
        font: {
          size: 18
        }
      },
      legend: {
        position: 'bottom',
        labels: {
          font: {
            size: 14
          }
        }
      },
      tooltip: {
        backgroundColor: 'rgba(0,0,0,0.7)',
        bodyFont: {
          size: 12
        }
      }
    }
  }
});
</script>

  </script>
  <!-- End custom js for this page-->
</body>

</html>

