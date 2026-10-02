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
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="../../vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../../vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../../../Inicio/template/images/LOGO.png" />
 
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
          <li class="nav-item ">
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
                <li class="nav-item "> <a class="nav-link" href="registropaciente.php">Registro</a></li> 
              
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

        <label for="dcm_files">Seleccionar archivo DICOM (.dcm):</label>
        <input type="file" id="dcm_files" class="form-control" name="dcm_files[]" accept=".dcm" multiple required>
    </fieldset>

   
    <!-- Datos del Médico -->
    <fieldset>
        <legend>Asignar Médico</legend>

        <div class="form-group">
            <label>Médico</label>
            <select class="form-control" name="medico_id" id="medico_id">
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
        const files = event.target.files;
        if (!files || files.length === 0) return;

        let archivosProcesados = 0;
        const totalArchivos = files.length;
        const mensajesPHP = [];
        let erroresEncontrados = false;

        async function subirArchivo(file) {
            return new Promise(async (resolve, reject) => {
                const reader = new FileReader();
                reader.onload = async function(e) {
                    const arrayBuffer = e.target.result;
                    try {
                        const byteArray = new Uint8Array(arrayBuffer);
                        const dataSet = dicomParser.parseDicom(byteArray);

                        const atributos = {
                            patientName: getTag(dataSet, 'x00100010'),
                            patientID: getTag(dataSet, 'x00100020'),
                            studyInstanceUID: getTag(dataSet, 'x0020000d'),
                            seriesInstanceUID: getTag(dataSet, 'x0020000e'),
                            sopInstanceUID: getTag(dataSet, 'x00080018'),
                            modality: getTag(dataSet, 'x00080060'),
                            studyDate: formatDate(getTag(dataSet, 'x00080020')),
                            patientBD: formatDate(getTag(dataSet, 'x00100030'))
                            // Puedes extraer más atributos según necesites
                        };

                        const medicoId = document.getElementById('medico_id').value;

                        const formData = new FormData();
                        formData.append('medico_id', medicoId);
                        formData.append('atributos_dcm', JSON.stringify(atributos));
                        formData.append('dcm_file', file);

                        const response = await fetch('subir_estudio.php', {
                            method: 'POST',
                            body: formData
                        });

                        const responseText = await response.text();

                        if (!response.ok) {
                            erroresEncontrados = true;
                            mensajesPHP.push({ icon: 'error', title: 'Error al subir', text: `${file.name}: ${responseText}` });
                            reject(`Error al subir ${file.name}: ${responseText}`);
                            return;
                        }

                        mensajesPHP.push({ icon: 'success', title: 'Subida exitosa', text: `${file.name}: ${responseText}` });
                        resolve(responseText);

                    } catch (error) {
                        console.error('Error leyendo DICOM:', error);
                        erroresEncontrados = true;
                        mensajesPHP.push({ icon: 'error', title: 'Error leyendo DICOM', text: `${file.name}: ${error}` });
                        reject(`Error al leer ${file.name}: ${error}`);
                    }
                };
                reader.onerror = () => {
                    erroresEncontrados = true;
                    mensajesPHP.push({ icon: 'error', title: 'Error al leer archivo', text: `${file.name}: Error al leer.` });
                    reject(`Error al leer el archivo ${file.name}`);
                };
                reader.readAsArrayBuffer(file);
            });
        }

        async function procesarArchivos() {
            Swal.fire({
                title: 'Subiendo archivos...',
                html: `Procesando 0 de ${totalArchivos}`,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            for (const file of files) {
                try {
                    await subirArchivo(file);
                    archivosProcesados++;
                    Swal.update({
                        html: `Procesando ${archivosProcesados} de ${totalArchivos}`
                    });
                    console.log(`Archivo ${file.name} procesado.`);
                } catch (error) {
                    console.error(`Error al procesar ${file.name}:`, error);
                    archivosProcesados++; // Consideramos el archivo como procesado (con error)
                    Swal.update({
                        html: `Procesando ${archivosProcesados} de ${totalArchivos}`
                    });
                }
            }

            Swal.close(); // Cerramos el "Subiendo archivos..."

            if (mensajesPHP.length > 0) {
                let htmlMensajes = '';
                mensajesPHP.forEach(mensaje => {
                    htmlMensajes += `<div style="margin-bottom: 10px;"><b>${mensaje.title}:</b> ${mensaje.text}</div>`;
                });

                Swal.fire({
                    icon: erroresEncontrados ? 'error' : 'info',
                    title: erroresEncontrados ? 'Resultados de la subida' : 'Resultados de la subida',
                    html: htmlMensajes,
                    confirmButtonText: 'Cerrar'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: '¡Subida completa!',
                    text: 'Todos los archivos se han procesado correctamente.'
                }).then(() => {
                    location.reload();
                });
            }
        }

        const submitButton = document.querySelector('button[type="submit"]');
        submitButton.addEventListener('click', (e) => {
            e.preventDefault();
            if (files.length > 0 && document.getElementById('medico_id').value) {
                procesarArchivos();
                submitButton.disabled = true;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'Por favor, selecciona al menos un archivo y un médico.'
                });
            }
        });
    });

    function getTag(dataSet, tag) {
        const value = dataSet.string(tag);
        return value ? value.trim() : '';
    }

    function formatDate(dicomDate) {
        if (!dicomDate || dicomDate.length !== 8) return '';
        return `${dicomDate.substring(0,4)}-${dicomDate.substring(4,6)}-${dicomDate.substring(6,8)}`;
    }
</script>

  <!-- End custom js for this page-->
</body>

</html>
