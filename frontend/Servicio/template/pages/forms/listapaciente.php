
<?php 
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);
?>

<?php
 $server='localhost';
 $username='root';
 $password='';
 $database='php_login_database';
 try {
   $lte=new PDO("mysql:host=$server;dbname=$database",$username,$password);
 } catch(PDOException $e) {
 die('Connected failed: '.$e->getMessage());
 }
 ?>
<?php 
session_start();
require '../../../../Inicio/template/pages/samples/database.php';
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

 ?>
 <?php 
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
	$con= mysqli_connect($server,$username,$password,$database);
	if($con->connect_error){
		die("Error al conectar la base de datos de la pagina".$con->connect_error);
	  }
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PolluxUI Admin</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../../../Inicio/template/images/LOGO.png" />

  
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>

<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
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
              <img src="../../images/faces/servicio.png" alt="profile"/>
              <span class="nav-profile-name"><?= $user['usuario_servicio'] ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="privacidad.php">
                <i class="typcn typcn-cog-outline text-primary"></i>
                Privacy
              </a>
              <a class="dropdown-item" href="../../../../Inicio/template/pages/samples/logout.php">
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
    
    <nav class="navbar-breadcrumb col-xl-12 col-12 d-flex flex-row p-0">
      <div class="navbar-links-wrapper d-flex align-items-stretch">
        <div class="nav-link">
          <a href="registroadmin.php"><i class="typcn typcn-briefcase"></i></a>
        </div>
        <div class="nav-link">
          <a href="registromedico.php"><i class="typcn typcn-headphones"></i></a>
        </div>
        <div class="nav-link">
          <a href="registropaciente.php"><i class="typcn typcn-clipboard"></i></a>
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
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
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
  
      <!-- partial:../../partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="../../index.php">
              <i class="typcn typcn-home menu-icon"></i>
              <span class="menu-title">Inicio</span>
              
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="typcn typcn-briefcase menu-icon"></i>
              <span class="menu-title">Administradores</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="listaadmin.php">Lista</a></li>
                <li class="nav-item"> <a class="nav-link"href="registroadmin.php">Registro</a></li> 
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
                <li class="nav-item"><a class="nav-link" href="listamedico.php">Lista</a></li>
                <li class="nav-item"> <a class="nav-link" href="registromedico.php">Registro</a></li> 
              </ul>
            </div>
          </li>
          <li class="nav-item active">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="typcn typcn-clipboard menu-icon"></i>
              <span class="menu-title">Pacientes</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="listapaciente.php">Lista</a></li>
                <li class="nav-item"> <a class="nav-link" href="registropaciente.php">Registro</a></li> 
               
              </ul>
            </div>
          </li>

       <li class="nav-item">
            <a class="nav-link"  href="privacidad.php" >
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

           
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <h4 class="card-title">Lista de Pacientes</h4>
<p class="card-description">
  Datos Personales e Información DICOM
</p>

<div class="table-responsive">
  <table id="pacientesTable" class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Patient Name</th>
        <th>Patient ID</th>
        <th>Fecha De Nacimiento</th>
        <th>Médico</th>
        <th>Modalidad</th>
        <th>Fecha Estudio</th>
        <th>Study </th>
        <th>Serie</th>
        <th>Sop </th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $sql = "SELECT * FROM paciente";
      $result = mysqli_query($conexion, $sql);   
      $contador = 1;              

      while($mostrar = mysqli_fetch_array($result)){
          // Obtener nombre del médico
          $stmt = $conn->prepare("SELECT * FROM usuario WHERE id = '".$mostrar['medico_id']."'");
          $stmt->execute();
          $medicos = $stmt->fetchAll();
          $medico_nombre = "";
          foreach($medicos as $medico) {
              $medico_nombre = $medico['nombrec'];
          }

          // Obtener información DICOM del paciente
          $stmt2 = $lte->prepare("SELECT * FROM paciente_dcm WHERE patient_id = '".$mostrar['id_paciente']."'");
          $stmt2->execute();
          $dicoms = $stmt2->fetchAll();

          if (count($dicoms) > 0){
              foreach($dicoms as $dcm){
      ?>
      <tr>
        <td><?php echo $contador; ?></td>
        <td><?php echo $mostrar['patient_name']; ?></td>
        <td><?php echo $mostrar['patient_id']; ?></td>
        <td><?php echo $mostrar['patient_bd']; ?></td>
        <td><?php echo $medico_nombre; ?></td>
        <td><?php echo $dcm['modality']; ?></td>
        <td><?php echo $dcm['study_date']; ?></td>
        <td><?php echo $dcm['study_instance_uid']; ?></td>
        <td><?php echo $dcm['series_instance_uid']; ?></td>
        <td><?php echo $dcm['sop_instance_uid']; ?></td>
        <td>
          <div class="d-flex align-items-center">
          <button 
    class="btn btn-success btn-sm btn-edit" 
    data-toggle="modal" 
    data-target="#editModal"
    data-id="<?php echo $mostrar['id_paciente']; ?>"
    data-name="<?php echo $mostrar['patient_name']; ?>"
    data-patientid="<?php echo $mostrar['patient_id']; ?>"
    data-bd="<?php echo $mostrar['patient_bd']; ?>"
    data-medico="<?php echo $medico['id']; ?>">
  Editar
</button>
<button 
    class="btn btn-primary btn-sm btn-edit-dicom" 
    data-toggle="modal" 
    data-target="#editDicomModal"
    data-id="<?php echo $dcm['id_paciente_dcm']; ?>"
    data-modality="<?php echo $dcm['modality']; ?>"
    data-studydate="<?php echo $dcm['study_date']; ?>"
    data-studyuid="<?php echo $dcm['study_instance_uid']; ?>"
    data-seriesuid="<?php echo $dcm['series_instance_uid']; ?>"
    data-sopuid="<?php echo $dcm['sop_instance_uid']; ?>">
  Editar DICOM
</button>
           <a href="eliminarimagen.php?id_img=<?php echo $dcm["id_paciente_dcm"]; ?>" class="btn btn-secondary btn-sm">
              Eliminar imagen
            </a>
            <a href="eliminarpaciente.php?id=<?php echo $mostrar["id_paciente"]; ?>" class="btn btn-danger btn-sm">
              Eliminar Paciente
            </a>
          </div>
        </td>
      </tr>
      <?php 
              }
          } else {
      ?>
      <tr>
        <td><?php echo $contador; ?></td>
        <td><?php echo $mostrar['patient_name']; ?></td>
        <td><?php echo $mostrar['patient_id']; ?></td>
        <td><?php echo $mostrar['patient_bd']; ?></td>
        <td><?php echo $medico_nombre; ?></td>
        <td colspan="4" class="text-center">Sin Información DICOM</td>
        <td>
          <div class="d-flex align-items-center">
          <button 
    class="btn btn-success btn-sm btn-edit" 
    data-toggle="modal" 
    data-target="#editModal"
    data-id="<?php echo $mostrar['id_paciente']; ?>"
    data-name="<?php echo $mostrar['patient_name']; ?>"
    data-patientid="<?php echo $mostrar['patient_id']; ?>"
    data-bd="<?php echo $mostrar['patient_bd']; ?>"
    data-medico="<?php echo $medico['id']; ?>">
  Editar
</button>
            <a href="eliminarpaciente.php?id=<?php echo $mostrar["id_paciente"]; ?>" class="btn btn-danger btn-sm">
              Eliminar Paciente
            </a>
          </div>
        </td>
      </tr>
      <?php
          }
          $contador++;
      }
      ?>
    </tbody>
  </table>
</div>
                    </div>
                  </div>
                </div>
            
                <!-- Modal -->

                            
      </div>
     
      </div>
      
    </div>
   
  </div>
</div>
          
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">CALCIVIEW © 2025  <a href="https://www.bootstrapdash.com/" class="text-muted" target="_blank"></a></span>
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
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <!-- Modal de edición -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="actualizarpaciente.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Editar Paciente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <input type="hidden" name="id_paciente" id="edit-id">

          <div class="form-group">
            <label>Nombre del Paciente</label>
            <input type="text" class="form-control" name="patient_name" id="edit-name" required>
          </div>

          <div class="form-group">
            <label>Patient ID</label>
            <input type="text" class="form-control" name="patient_id" id="edit-patientid" required>
          </div>

          <div class="form-group">
            <label>Fecha de Nacimiento</label>
            <input type="date" class="form-control" name="patient_bd" id="edit-bd" required>
          </div>

          <div class="form-group">
            <label>Médico</label>
            <select class="form-control" name="medico_id" id="edit-medico">
              <?php
              $sql_medicos = "SELECT * FROM usuario"; // Ajusta según tu tabla de médicos
              $res_medicos = mysqli_query($conexion, $sql_medicos);
              while ($medico = mysqli_fetch_assoc($res_medicos)) {
                  echo '<option value="'.$medico['id'].'">'.$medico['nombrec'].'</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de edición DICOM -->
<div class="modal fade" id="editDicomModal" tabindex="-1" aria-labelledby="editDicomModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="actualizardicom.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="editDicomModalLabel">Editar Información DICOM</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <input type="hidden" name="id_paciente_dcm" id="edit-dicom-id">

          <div class="form-group">
            <label>Modalidad</label>
            <input type="text" class="form-control" name="modality" id="edit-dicom-modality" required>
          </div>

          <div class="form-group">
            <label>Fecha de Estudio</label>
            <input type="date" class="form-control" name="study_date" id="edit-dicom-studydate" required>
          </div>

          <div class="form-group">
            <label>Study Instance UID</label>
            <input type="text" class="form-control" name="study_instance_uid" id="edit-dicom-studyuid" required>
          </div>

          <div class="form-group">
            <label>Series Instance UID</label>
            <input type="text" class="form-control" name="series_instance_uid" id="edit-dicom-seriesuid" required>
          </div>

          <div class="form-group">
            <label>Sop Instance UID</label>
            <input type="text" class="form-control" name="sop_instance_uid" id="edit-dicom-sopuid" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
$(document).ready(function(){
  $('.btn-edit').click(function(){
    var id = $(this).data('id');
    var name = $(this).data('name');
    var patientid = $(this).data('patientid');
    var bd = $(this).data('bd');
    var medico = $(this).data('medico');

    $('#edit-id').val(id);
    $('#edit-name').val(name);
    $('#edit-patientid').val(patientid);
    $('#edit-bd').val(bd);
    $('#edit-medico').val(medico);
  });
});
</script>

<script>
$(document).ready(function(){
  $('.btn-edit-dicom').click(function(){
    var id = $(this).data('id');
    var modality = $(this).data('modality');
    var studydate = $(this).data('studydate');
    var studyuid = $(this).data('studyuid');
    var seriesuid = $(this).data('seriesuid');
    var sopuid = $(this).data('sopuid');

    $('#edit-dicom-id').val(id);
    $('#edit-dicom-modality').val(modality);
    $('#edit-dicom-studydate').val(studydate);
    $('#edit-dicom-studyuid').val(studyuid);
    $('#edit-dicom-seriesuid').val(seriesuid);
    $('#edit-dicom-sopuid').val(sopuid);
  });
});
</script>
<?php
// Asegúrate de que estás cargando SweetAlert2
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

if (isset($_GET['success_delete'])) {
    echo '
    <script>
    Swal.fire({
        icon: "success",
        title: "¡Eliminación Exitosa!",
        text: "El paciente fue eliminado correctamente junto con sus imágenes.",
          confirmButtonText: "Aceptar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar el parámetro "success" de la URL sin recargar la página
            history.replaceState(null, "", window.location.pathname);
        }
    });
    </script>';

}

if (isset($_GET['error_delete'])) {
    echo '
    <script>
    Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un error al intentar eliminar al paciente.",
        confirmButtonText: "Aceptar"
    });
    </script>';
}
?>

<?php
// Asegúrate de que estás cargando SweetAlert2
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

if (isset($_GET['success_delete1'])) {
    echo '
    <script>
    Swal.fire({
        icon: "success",
        title: "¡Eliminación Exitosa!",
        text: "La imagen fue eliminado correctamente.",
          confirmButtonText: "Aceptar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar el parámetro "success" de la URL sin recargar la página
            history.replaceState(null, "", window.location.pathname);
        }
    });
    </script>';

}

if (isset($_GET['error_delete1'])) {
    echo '
    <script>
    Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un error al intentar eliminar la imagen.",
        confirmButtonText: "Aceptar"
    });
    </script>';
}
?>

<?php
// Asegúrate de que estás cargando SweetAlert2
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

if (isset($_GET['success'])) {
    echo '
    <script>
    Swal.fire({
        icon: "success",
        title: "¡Actualización Exitosa!",
        text: "El paciente fue actualizado correctamente.",
        confirmButtonText: "Aceptar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar el parámetro "error" de la URL sin recargar la página
            history.replaceState(null, "", window.location.pathname);
        }
    });
    </script>';
}

if (isset($_GET['error'])) {
    echo '
    <script>
    Swal.fire({
        icon: "error",
        title: "Error",
        text: "Ocurrió un error al intentar actualizar el paciente.",
        confirmButtonText: "Aceptar"
    }).then((result) => {
        if (result.isConfirmed) {
            // Eliminar el parámetro "error" de la URL sin recargar la página
            history.replaceState(null, "", window.location.pathname);
        }
    });
    </script>';
}
?>


<script>
$(document).ready(function() {
  $('#pacientesTable').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
    },
    "pageLength": 10,
    "lengthMenu": [1, 10, 25, 50, 100]
  });
});
</script>

  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
</body>

</html>
