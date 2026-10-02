<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>OPERADOR</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../../../Inicio/template/images/LOGO.png" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <!-- FancyTree CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.38.2/skin-win8/ui.fancytree.min.css" rel="stylesheet">

<!-- FancyTree JS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Cargar FancyTree -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.38.2/jquery.fancytree-all-deps.min.js"></script>



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
          <a href="listapaciente.php"><i class="typcn typcn-clipboard"></i></a>
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
 
            
             <div class="col-lg-12 grid-margin stretch-card">
               <div class="card">
                 <div class="card-body">
                 <div class="input-group">
            <input type="text" id="searchInput" placeholder="Buscar paciente, fecha..." class="form-control mb-3">
            <i class="typcn typcn-zoom" style="font-size: 24px; color: #118bd1;"></i>
            </div>
                 <div id="pacientesTree" style="height:600px; overflow:auto;"></div>
                 <?php
$data = [];

$sql = "SELECT * FROM paciente WHERE medico_id= '".$user['id']."'";
$result = mysqli_query($conexion, $sql);

while($mostrar = mysqli_fetch_array($result)){
  $pacienteNode = [
    "title" => $mostrar['patient_name'] . " (" . $mostrar['patient_id'] . ")",
    "key" => "paciente_" . $mostrar['id_paciente'],
    "folder" => true,
    "data" => [
      "isPaciente" => true,
      "id_paciente" => $mostrar['id_paciente'],
      "patient_name" => $mostrar['patient_name'],
      "patient_id" => $mostrar['patient_id'],
      "patient_bd" => $mostrar['patient_bd']
    ],
    "children" => []
  ];

    $stmt2 = $lte->prepare("SELECT * FROM paciente_dcm WHERE patient_id = '".$mostrar['id_paciente']."' GROUP BY study_instance_uid");
    $stmt2->execute();
    $studies = $stmt2->fetchAll();

    foreach($studies as $study){
        $studyNode = [
            "title" => "Estudio: " . $study['study_date'],
            "key" => "study_" . $study['study_instance_uid'],
            "folder" => true,
            "children" => []
        ];

        $stmt3 = $lte->prepare("SELECT * FROM paciente_dcm WHERE study_instance_uid = '".$study['study_instance_uid']."' GROUP BY series_instance_uid");
        $stmt3->execute();
        $series = $stmt3->fetchAll();

       

        foreach($series as $serie){
           // Obtener las imágenes asociadas a la serie
           $stmt4 = $lte->prepare("SELECT * FROM imagenes_dcm WHERE paciente_dcm_id='".$serie['id_paciente_dcm']."'");
           $stmt4->execute();
           $imagenes = $stmt4->fetchAll();

           foreach($imagenes as $imagen){
             $imagenNode = [
                 "title" => '<img src="' . $imagen['image_path'] . '" alt="Imagen" width="100" height="100" />',  // Mostrar la imagen
                 "key" => "imagen_" . $imagen['image_path'],
                 "data" => [
                     "image_path" => $imagen['image_path']
                 ]
             ];
           
         }
          $serieNode = [
            "title" => "Serie: " . $serie['series_instance_uid'] . 
                       ' <div style="display: inline-block; margin-left: 10px;">' .
                       '<a href="ver_imagen.php?image_path=' . urlencode($imagen['image_path']) . 
                       '" class="btn btn-primary btn-sm btn-icon-text">Ver Imágenes</a></div>',
            "key" => "serie_" . $serie['series_instance_uid'],
            "data" => [
                "series_instance_uid" => $serie['series_instance_uid']
            ],
            "children" => []
        ];

           

            $studyNode["children"][] = $serieNode;
        }

        $pacienteNode["children"][] = $studyNode;
    }

    $data[] = $pacienteNode;
}
?>

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
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- FancyTree JS -->
<!-- Cargar jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
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

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


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
<!-- Cargar jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Cargar FancyTree -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.fancytree/2.38.2/jquery.fancytree-all-deps.min.js"></script>

<!-- Inicializar FancyTree -->
<script>

$(document).ready(function() {
  var tree = $("#pacientesTree").fancytree({
    source: <?php echo json_encode($data); ?>,
    extensions: ["filter"],
    quicksearch: true,
    filter: {
      autoApply: true,
      counter: true,
      highlight: true,
      mode: "dimm"
    },
    renderNode: function(event, data) {
      var node = data.node;

      // Si es un paciente
      if (node.folder && node.data && node.data.isPaciente) {
        $(node.span).find(".btn-edit").remove();

        var $btn = $('<button class="btn btn-success btn-sm btn-edit ml-2" data-toggle="modal" data-target="#editModal">Editar</button>');

        $btn.data('id', node.data.id_paciente);
        $btn.data('name', node.data.patient_name);
        $btn.data('patientid', node.data.patient_id);
        $btn.data('bd', node.data.patient_bd);

        $(node.span).find(".fancytree-title").after($btn);
      }
    },
    // Añade esta opción para que los nodos expandidos mantengan su estado
    persist: {
      expand: true
    }
  }).fancytree("getTree");

  // Búsqueda en tiempo real con mejor manejo de expansión
  $("#searchInput").keyup(function(e){
    var match = $(this).val();
    var tree = $.ui.fancytree.getTree("#pacientesTree");

    if (match) {
      // Primero colapsamos todo
      tree.visit(function(node){
        node.setExpanded(false);
      });

      // Aplicamos el filtro
      tree.filterNodes(match, {leavesOnly: false});

      // Expandimos los nodos que coinciden y sus ancestros
      tree.visit(function(node){
        if(node.isMatched()) {
          node.setExpanded(true);
          node.visitParents(function(parent){
            parent.setExpanded(true);
          });
        }
      });
    } else {
      // Si no hay búsqueda, eliminamos el filtro
      tree.clearFilter();
      // Opcional: colapsar todo al borrar la búsqueda
      tree.getRootNode().visit(function(node){
        node.setExpanded(false);
      });
    }
  });

  // Botones Editar
  $(document).on('click', '.btn-edit', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');
    var patientId = $(this).data('patientid');
    var bd = $(this).data('bd');
    
    $('#edit-id').val(id);
    $('#edit-name').val(name);
    $('#edit-patientid').val(patientId);
    $('#edit-bd').val(bd);
  });
});
</script>



  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
</body>

</html>
