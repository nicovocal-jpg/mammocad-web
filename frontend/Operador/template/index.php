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
if(isset($_SESSION['user_id'])){
$records=$conn->prepare('SELECT id,email,password,nombrec FROM usuario WHERE id=:id');
$records->bindParam(':id',$_SESSION['user_id']);
$records->execute();
$results = $records->fetch(PDO::FETCH_ASSOC);

$user=null;

if(count($results)>0){
$user=$results;
}
}


// Configuración de zona horaria
date_default_timezone_set('America/La_Paz');
$hoy = date("F j");   

// Conexión segura (asumiendo que $lte es tu conexión MySQLi)

// 1. Total de pacientes (optimizada)
$sqlpa = "SELECT COUNT(id_paciente) AS total FROM paciente";
$resultpa = mysqli_query($lte, $sqlpa); 
$filapa = mysqli_fetch_assoc($resultpa);

// 2. Total de pacientes del médico actual (protegida contra SQL injection)
$id_medico = (int)$user['id']; // Forzamos tipo entero para seguridad
$sqlpaop = "SELECT COUNT(id_paciente) AS total FROM paciente WHERE medico_id = ?";
$stmt = $lte->prepare($sqlpaop);
$stmt->bind_param("i", $id_medico);
$stmt->execute();
$filapaop = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 3. Total de estudios DR y CR en una sola consulta (optimización)

// Consulta para obtener todas las modalidades y sus conteos
$sql_modalities = "SELECT modality, COUNT(*) AS total 
                   FROM paciente_dcm 
                   GROUP BY modality
                   ORDER BY total DESC";

$result_mod = mysqli_query($lte, $sql_modalities); 

// Preparar arrays para el gráfico
$labels = [];
$data = [];
$backgroundColors = [];
$borderColors = [];

// Colores para las barras (puedes agregar más si tienes muchas modalidades)
$colorPalette = [
    'rgba(244, 243, 112, 0.7)',
    'rgba(179, 244, 112, 0.7)',
    'rgba(112, 198, 244, 0.7)',
    'rgba(244, 112, 198, 0.7)',
    'rgba(198, 112, 244, 0.7)'
];

$i = 0;
while ($row = mysqli_fetch_assoc($result_mod)) {
    $labels[] = $row['modality'];
    $data[] = $row['total'];
    $backgroundColors[] = $colorPalette[$i % count($colorPalette)];
    $borderColors[] = str_replace('0.7', '1', $colorPalette[$i % count($colorPalette)]);
    $i++;
}

// Convertir arrays a formato JSON para JavaScript
$labels_json = json_encode($labels);
$data_json = json_encode($data);
$bgColors_json = json_encode($backgroundColors);
$borderColors_json = json_encode($borderColors);





// Consulta combinada para obtener fechas extremas y sus conteos
$sql_stats = "SELECT 
                (SELECT study_date 
                 FROM paciente_dcm 
                 GROUP BY study_date 
                 ORDER BY COUNT(*) DESC, study_date DESC 
                 LIMIT 1) AS max_date,
                
                (SELECT COUNT(*) 
                 FROM paciente_dcm 
                 GROUP BY study_date 
                 ORDER BY COUNT(*) DESC 
                 LIMIT 1) AS max_count,
                
                (SELECT study_date 
                 FROM paciente_dcm 
                 GROUP BY study_date 
                 ORDER BY COUNT(*) ASC, study_date ASC 
                 LIMIT 1) AS min_date,
                
                (SELECT COUNT(*) 
                 FROM paciente_dcm 
                 GROUP BY study_date 
                 ORDER BY COUNT(*) ASC 
                 LIMIT 1) AS min_count";
                
$result_stats = mysqli_query($lte, $sql_stats); 
$stats = mysqli_fetch_assoc($result_stats);

// Formatear fechas para mejor visualización
$max_date_formatted = date("d/m/Y", strtotime($stats['max_date']));
$min_date_formatted = date("d/m/Y", strtotime($stats['min_date']));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>OPERADOR</title>
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
              <img src="images/faces/operador.jpg" alt="profile"/>
              <span class="nav-profile-name"><?= $user['nombrec'] ?></span>
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
          <a href="pages/forms/listapaciente.php"><i class="typcn typcn-clipboard"></i></a>
        </div>
      
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item ml-0">
            <h4 class="mb-0">Operador</h4>
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
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="typcn typcn-clipboard menu-icon"></i>
              <span class="menu-title">Pacientes</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="pages/forms/listapaciente.php">Lista</a></li>
                <li class="nav-item"><a class="nav-link" href="pages/forms/subirimagen.php">Imagen</a></li>
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
                      <p class="mb-2 text-md-center text-lg-left">Total Pacientes</p>
                      <h1 class="mb-0"><?php echo $filapa['total'];?></h1>
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
                      <p class="mb-2 text-md-center text-lg-left">Fecha de Estudio</p>
                    </div>
                  </div>
                
                  <canvas id="doughnutChart"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-center justify-content-xl-between flex-wrap mb-4">
                    <div>
                      <p class="mb-2 text-md-center text-lg-left">Cantidad de pacientes de <?= $user['nombrec'] ?></p>
                    </div>
                  </div>
                  <h1 class="mb-0"><?php echo $filapaop['total'];?></h1>
                  
                </div>
              </div>
            </div>
          </div>

          




          <div class="row">
           

            
          <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
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
                    <?php 
                    $contador=1;
                    $sqllpa="SELECT * from paciente ";
                    $resultpa=mysqli_query($lte,$sqllpa);          
                    while($mostrarpa=mysqli_fetch_array($resultpa)){
                    ?>
                    <tbody>
                      <tr>
                        
                      <td><?php echo $contador; ?></td>
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
                <p class="mb-2 text-md-center text-lg-left">Modalidad  </p>
                <canvas id="barChart" ></canvas>
                </div>
              </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <p class="mb-2 text-md-center text-lg-left">Pacientes De <?= $user['nombrec'] ?></p>
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
                    $id_medico=$user['id'] ;
                    $sqllpa="SELECT * from paciente WHERE medico_id='$id_medico'";
                    $resultpa=mysqli_query($lte,$sqllpa);          
                    while($mostrarpa=mysqli_fetch_array($resultpa)){
                    ?>
                    <tbody>
                      <tr>
                        
                      <td><?php echo $contador; ?></td>
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


            </div>
            
            </div>
            
      
      
         
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">CALCIVIEW 3 © 2025 <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank"></a></span>
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
  <!-- endinject -->
  <!-- Custom js for this page-->

  <!-- End plugin js for this page -->
  
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script >
        var ctxB = document.getElementById("barChart").getContext('2d');
        var ctxB = document.getElementById("barChart").getContext('2d');
var myBarChart = new Chart(ctxB, {
    type: 'bar',
    data: {
        labels: <?php echo $labels_json; ?>,
        datasets: [{
            label: 'Estudios por Modalidad',
            data: <?php echo $data_json; ?>,
            backgroundColor: <?php echo $bgColors_json; ?>,
            borderColor: <?php echo $borderColors_json; ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    precision: 0
                }
            }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    return data.datasets[0].data[tooltipItem.index] + ' estudios';
                }
            }
        }
    }
});

var ctxD = document.getElementById("doughnutChart").getContext('2d');
var myDoughnutChart = new Chart(ctxD, {
    type: 'doughnut',
    data: {
        labels: [
            `Máximo: ${<?php echo json_encode($max_date_formatted); ?>}`, 
            `Mínimo: ${<?php echo json_encode($min_date_formatted); ?>}`
        ],
        datasets: [{
            data: [
                <?php echo $stats['max_count']; ?>,
                <?php echo $stats['min_count']; ?>
            ],
            backgroundColor: [
                "#ed2a4f", 
                "#ed672a"
            ],
            hoverBackgroundColor: [
                "#ff3d6a", 
                "#ff8c5a"
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        cutoutPercentage: 70,
        animation: {
            animateScale: true,
            animateRotate: true
        },
        legend: {
            position: 'bottom',
            labels: {
                fontSize: 12,
                padding: 20
            }
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.labels[tooltipItem.index] || '';
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    return `${label}: ${value} estudios`;
                }
            }
        }
    }
});
  </script>
  <!-- End custom js for this page-->
</body>

</html>

