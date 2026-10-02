<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
 <?php 
function isValid($text){
  $pattern = "/^[a-zA-Z\sñáéíóúÁÉÍÓÚ]+$/";
  return preg_match($pattern, $text);
}

function emailvalid($text1){
  $pattern = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";
  return preg_match($pattern, $text1);
}

if(!empty($_POST))
{

if(empty($_POST['email']) || empty($_POST['password'])|| empty($_POST['nombre'])|| empty($_POST['contraseina'])){
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","Debe completar los campos","info");';
  echo '}, 1);</script>';
}

else{
  
$records=$conn->prepare('SELECT email FROM usuario WHERE email=:email');
$records->bindParam(':email', $_POST['email']);
$records->execute();
$results=$records->fetch(PDO::FETCH_ASSOC);

if (!empty($results) && count($results)>0){
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","El usuario ya existe","info");';
  echo '}, 1);</script>';
} else {  
  $password=($_POST['password']);
$passwordcf=($_POST['contraseina']);
if(strlen($password)>=6){
if($password==$passwordcf)
  {
    if(isValid($_POST['nombre'])==false)
    {
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Upss!","El nombre solo requiere letras a-z","info");';
      echo '}, 1);</script>';
      }
   else{
    if(emailvalid($_POST['email'])==false)
    {
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Upss!","La direccion es invalida","info");';
      echo '}, 1);</script>';
      }
      else{

    $sql="INSERT INTO usuario (email, password,nombrec,genero) VALUES (:email, :password, :nombrec,:genero)";
$stmt=$conn->prepare($sql);
$stmt->bindParam(':email',$_POST['email']);
$stmt->bindParam(':nombrec',$_POST['nombre']);
$password=password_hash($_POST['password'], PASSWORD_BCRYPT);
$stmt->bindParam(':password',$password);
$stmt->bindParam(':genero',$_POST['select']);

if($stmt->execute())
{
	echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Genial!","Usuario creado con exito","success");';
  echo '}, 1);</script>';
} else {
	echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Error!","El usuario no se pudo crear","error");';
  echo '}, 1);</script>';
}
   }
  }
} 
else{
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","Las contraseñas no coinciden","info");';
  echo '}, 1);</script>';
}	
  }
 

else{
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Cuidado","Contraseña demasiado corta, debe ser mayor a 6","info");';
  echo '}, 1);</script>';
}
}
}
}

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
          <div class="row">
          
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Registro Operadores</h4>
                  
                  <form action="registromedico.php" method="POST">
                    <div class="form-group">
                      <label for="exampleInputName1">Nombre</label>
                      <input type="text" class="form-control" name="nombre"  placeholder="Name">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Usuario</label>
                      <input type="text" class="form-control" name="email" placeholder="User" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">Contraseña</label>
                      <input type="password" class="form-control" name="password" placeholder="Password" />
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword4">Confirmar Contraseña</label>
                      <input type="password" class="form-control" name="contraseina" placeholder="Confirm Password">
                    </div>
                    <div class="form-group">
                      <label for="exampleSelectGender">Genero</label>
                      <select class="form-control"   id="select"  name="select" >
												<option value="Masculino">Hombre</option>
												<option value="Femenino">Mujer</option>
												<option value="SID">No me identifico con ninguno</option>
												</select>
                      </div>
                      <button type="submit" name="cambiarusuario" class="btn btn-primary mr-2">Submit</button>
                    <a href="registromedico.php" class="btn btn-light">Cancel</a>
                  </form>
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
  <!-- End custom js for this page-->
</body>

</html>
