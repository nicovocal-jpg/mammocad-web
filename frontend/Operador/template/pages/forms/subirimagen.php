<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php 
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database); 

$server='localhost';
$username='root';
$password='';
$database='php_login_database';
	$con= mysqli_connect($server,$username,$password,$database);
	if($con->connect_error){
		die("Error al conectar la base de datos de la pagina".$con->connect_error);
	  }
?>
<?php 
session_start();
require '../../../../Inicio/template/pages/samples/database.php';
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
date_default_timezone_set('America/La_Paz');
$hoy = date("F j");   
 

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
<style>
.custom-file-upload {
  position: relative;
  overflow: hidden;
  display: inline-block;
}

.custom-file-upload input[type="file"] {
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

.custom-file-upload label {
  display: inline-block;
  padding: 10px 20px;
  background: #4285f4;
  color: white;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s;
  font-family: Arial, sans-serif;
}

.custom-file-upload label:hover {
  background: #3367d6;
}

.custom-file-upload i {
  margin-right: 8px;
}
</style>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>OPERADOR</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="../../vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../../vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../../../Inicio/template/images/LOGO.png" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand brand-logo" href="index.php"></a>
          <a class="navbar-brand brand-logo-mini" href="index.php"><img src="" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../../images/faces/operador.jpg" alt="profile"/>
              <span class="nav-profile-name"><?= $user['nombrec'] ?></span>
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
          <a href="subirimagen.php"><i class="typcn typcn-clipboard"></i></a>
        </div> 
      </div>
      
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item ml-0">
            <h4 class="mb-0">Operador</h4>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
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
      
      <!-- partial -->
      <!-- partial:../../partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="../../index.php">
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
                <li class="nav-item"><a class="nav-link" href="listapaciente.php">Lista</a></li>
                <li class="nav-item"><a class="nav-link" href="subirimagen.php">Imagen</a></li>
               
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
            
            
          <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Registro Paciente</h4>
                  
                  <form id="registroForm" action="subir_estudio.php" method="POST" enctype="multipart/form-data">
    <!-- Subida de Imagen DICOM -->
    <fieldset>
        <legend>Subir Imagen/es DICOM</legend>

        <div class="custom-file-upload">
  <input type="file" id="dcm_files" name="dcm_files" accept=".dcm" required>
  <label for="dcm_files">
    <i class="fas fa-cloud-upload-alt"></i> Seleccionar archivos DICOM
  </label>
</div>
    </fieldset>

    <!-- Datos del Paciente -->
    <fieldset>
        <legend>Datos del Paciente</legend>

        <label for="patient_name">Nombre del Paciente:</label>
<input type="text" id="patient_name" class="form-control" name="patient_name" required readonly>

<label for="patient_id">Patient ID (DICOM):</label>
<input type="text" id="patient_id" class="form-control" name="patient_id" required readonly>

<label for="patient_bd">Fecha de Nacimiento:</label>
<input type="date" id="patient_bd" class="form-control" name="patient_bd" required readonly>
    </fieldset>

    <!-- Datos del Estudio -->
    <fieldset>
       
    <div hidden>
    <label for="modality">Modalidad:</label>
    <input type="text" id="modality" class="form-control" name="modality" required>

    <label for="study_date">Fecha de Estudio:</label>
    <input type="date" id="study_date" class="form-control" name="study_date" >

    <label for="study_uid">Study Instance UID:</label>
    <input type="text" id="study_uid" class="form-control" name="study_uid" required>

    <label for="series_uid">Series Instance UID:</label>
    <input type="text" id="series_uid" class="form-control" name="series_uid" required>

    <label for="sop_uid">Sop Instance UID:</label>
    <input type="text" id="sop_uid" class="form-control" name="sop_uid" required>
</div>
    </fieldset>

    <!-- Datos del Médico -->
    <fieldset>
        <legend>Médico</legend>

        <div class="form-group">
            <label>Médico</label>
            <select class="form-control" name="medico_id" id="medico_id">
              <?php
              $sql_medicos = "SELECT * FROM usuario WHERE id = '{$user['id']}'"; // Ajusta según tu tabla y columna
              $res_medicos = mysqli_query($conexion, $sql_medicos);
              
              while ($medico = mysqli_fetch_assoc($res_medicos)) {
                  echo '<option value="' . $medico['id'] . '">' . $medico['nombrec'] . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        
       
    </fieldset>

    <button type="submit" name="registrar" class="btn btn-block btn-primary mr-2">Submit</button>
    <a href="registropaciente.php" class="btn btn-block btn-light">Cancel</a>
</form>
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
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
  <!-- plugin js for this page -->
  <script src="../../vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="../../vendors/select2/select2.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- Custom js for this page-->
  <script src="../../js/file-upload.js"></script>
  <script src="../../js/typeahead.js"></script>
  <script src="../../js/select2.js"></script>

   <!-- Cargamos dicomParser -->
   <script src="https://unpkg.com/dicom-parser/dist/dicomParser.min.js"></script>

<script>
    document.getElementById('dcm_files').addEventListener('change', function(event) {
        const file = event.target.files[0]; // Solo procesamos el primer archivo para autocompletar
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const arrayBuffer = e.target.result;
            try {
                const byteArray = new Uint8Array(arrayBuffer);
                const dataSet = dicomParser.parseDicom(byteArray);

                // Extraemos los campos DICOM
                document.getElementById('patient_name').value = getTag(dataSet, 'x00100010');
                document.getElementById('patient_id').value = getTag(dataSet, 'x00100020');
                document.getElementById('patient_bd').value = formatDate(getTag(dataSet, 'x00100030'));
                document.getElementById('modality').value = getTag(dataSet, 'x00080060');
                document.getElementById('study_date').value = formatDate(getTag(dataSet, 'x00080020'));
                document.getElementById('study_uid').value = getTag(dataSet, 'x0020000d');
                document.getElementById('series_uid').value = getTag(dataSet, 'x0020000e');
                document.getElementById('sop_uid').value = getTag(dataSet, 'x00080018');

            } catch (error) {
                console.error('Error leyendo DICOM:', error);
                alert('No se pudo leer el archivo DICOM.');
            }
        };
        reader.readAsArrayBuffer(file);
    });

    // Función para obtener un valor de un tag DICOM
    function getTag(dataSet, tag) {
        const value = dataSet.string(tag);
        return value ? value.trim() : '';
    }

    // Función para formatear fechas DICOM YYYYMMDD a YYYY-MM-DD
    function formatDate(dicomDate) {
        if (!dicomDate || dicomDate.length !== 8) return '';
        return `${dicomDate.substring(0,4)}-${dicomDate.substring(4,6)}-${dicomDate.substring(6,8)}`;
    }
</script>

<script>
document.getElementById('registroForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevenir envío normal

    const formData = new FormData(this);

    fetch('subir_estudio.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            // Si el servidor responde con un error, mostrarlo
            throw new Error('Error en la respuesta del servidor');
        }
        return response.text();  // Recibimos la respuesta como texto
    })
    .then(text => {
        console.log('Respuesta del servidor:', text);  // Log de la respuesta completa

        // Dividir el texto en líneas y obtener la última línea
        const lines = text.split('\n');  // Divide el texto por saltos de línea
        const lastLine = lines[lines.length - 1];  // Obtener la última línea

        console.log('Última línea:', lastLine);  // Mostrar la última línea

        // Verificar si la última línea contiene el mensaje de éxito
        if (lastLine.includes('¡Estudio registrado correctamente!')) {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: lastLine
            }).then(() => {
                location.reload(); // Recargar página o redirigir
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: lastLine || 'Error desconocido'
            });
        }
    })
    .catch(error => {
        console.error('Error en la petición:', error);  // Aquí puedes ver más detalles del error
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Ocurrió un problema inesperado.'
        });
    });
});





</script>
  <!-- End custom js for this page-->
</body>

</html>
