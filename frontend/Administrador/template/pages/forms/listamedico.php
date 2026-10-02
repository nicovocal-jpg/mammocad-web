<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php 
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);
?>
<?php 
session_start();
require '../../../../Inicio/template/pages/samples/database.php';
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

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>ADMINISTRADOR</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
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
              <img src="../../images/faces/admin.jpg" alt="profile"/>
              <span class="nav-profile-name"><?= $user['usuario_admin'] ?></span>
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
          <a href="registromedico.php"><i class="typcn typcn-headphones"></i></a>
        </div>
        <div class="nav-link">
          <a href="registropaciente.php"><i class="typcn typcn-clipboard"></i></a>
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
          <div class="input-group">
            <input type="text" id="search" placeholder="Buscar medico..." class="form-control mb-3">
            <i class="typcn typcn-zoom" style="font-size: 24px; color: #118bd1;"></i>
            </div>
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
         
           
          <li class="nav-item active">
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
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="typcn typcn-clipboard menu-icon"></i>
              <span class="menu-title">Pacientes</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="listapaciente.php">Lista</a></li>
               
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
    
          
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Tabla de Operadores</h4>
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Genero</th>
                        <th>Acciones</th>
                        </tr>
                      </thead>
                     
                       <tbody id="resultados">
      <!-- Aquí se carga dinámicamente -->
                     </tbody>
                    </table>
                  </div>

                  <div id="paginacion" class="mt-3 text-center">
  <!-- Botones de paginación aquí -->
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

    <!-- Modal de edición -->
<div class="modal" id="modalEditar" style="display:none; position:fixed; top:10%; left:20%;  width:60%; height: 400px; background:#fff; padding:20px; box-shadow: 0px 0px 10px rgba(0,0,0,0.5); z-index:9999;">
    <h4>Editar Operador</h4>
    <form id="formEditar">
        <input type="hidden" id="editar_id">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" id="editar_nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="text" id="editar_usuario" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
    </form>
</div>

  <script>
    //Funcion busqueda y caraga de datos administradores
function cargarMedico(busqueda = '', pagina = 1) {
    fetch('buscarmed.php?q=' + encodeURIComponent(busqueda) + '&page=' + pagina)
        .then(response => response.json())
        .then(data => {
            document.getElementById('resultados').innerHTML = data.tabla;

            const paginacion = document.getElementById('paginacion');
            paginacion.innerHTML = '';

            // Botón anterior
            if (data.pagina_actual > 1) {
                paginacion.innerHTML += `<button class="btn btn-primary btn-sm m-1" onclick="cargarMedico('${busqueda}', ${data.pagina_actual - 1})">Anterior</button>`;
            } else {
                paginacion.innerHTML += `<button class="btn btn-secondary btn-sm m-1" disabled>Anterior</button>`;
            }

            // Botones numerados
            for (let i = 1; i <= data.total_paginas; i++) {
                if (i === data.pagina_actual) {
                    paginacion.innerHTML += `<button class="btn btn-dark btn-sm m-1" disabled>${i}</button>`;
                } else {
                    paginacion.innerHTML += `<button class="btn btn-primary btn-sm m-1" onclick="cargarMedico('${busqueda}', ${i})">${i}</button>`;
                }
            }

            // Botón siguiente
            if (data.pagina_actual < data.total_paginas) {
                paginacion.innerHTML += `<button class="btn btn-primary btn-sm m-1" onclick="cargarMedico('${busqueda}', ${data.pagina_actual + 1})">Siguiente</button>`;
            } else {
                paginacion.innerHTML += `<button class="btn btn-secondary btn-sm m-1" disabled>Siguiente</button>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('resultados').innerHTML = '<tr><td colspan="6">Error al cargar datos</td></tr>';
        });
}

// Al inicio
document.addEventListener('DOMContentLoaded', function() {
  cargarMedico();
});

// Buscando
document.getElementById('search').addEventListener('keyup', function() {
  cargarMedico(this.value, 1); // Volvemos a página 1 cuando escriben
});
</script>


<script>
// Función para confirmar eliminación

function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción eliminará el operadaor  permanentemente.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('eliminarmed.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            })
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Eliminado',
                    text: data
                });
                cargarMedico(); // Recarga la tabla
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un problema al eliminar el operador.'
                });
            });
        }
    });
}


</script>

<script>
  //Funcion modal para editar
// Abrir modal con datos
function abrirEditar(id) {
    fetch('obtenermed.php?id=' + id)
    .then(response => response.json())
    .then(data => {
        document.getElementById('editar_id').value = data.id;
        document.getElementById('editar_nombre').value = data.nombrec;
        document.getElementById('editar_usuario').value = data.email;
        document.getElementById('modalEditar').style.display = 'block';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al obtener datos');
    });
}

// Cerrar modal
function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

// Guardar cambios
document.getElementById('formEditar').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('editar_id').value;
    const nombre = document.getElementById('editar_nombre').value;
    const usuario = document.getElementById('editar_usuario').value;
    
    fetch('editarmed.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}&nombre=${encodeURIComponent(nombre)}&usuario=${encodeURIComponent(usuario)}`
    })
    .then(response => response.text())
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Administrador actualizado correctamente.',
        });
        cerrarModal();
        cargarMedico(); // Recargar tabla
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al editar el administrador.',
        });
    });
});

</script>

  
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
</body>

</html>
