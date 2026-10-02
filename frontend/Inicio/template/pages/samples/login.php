<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php 
session_start();
if(isset($_SESSION['user_id'])){
  header('location:/Proyecto3/frontend/Inicio/template/pages/samples/login.php');
}

require 'database.php';



if(!empty($_POST))
{
  $opciones = $_POST["select"];
if(empty($_POST['email']) || empty($_POST['password'])){
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","Debe completar los campos","info");';
  echo '}, 1);</script>';
}
else{
	$records=$conn->prepare('SELECT id, email, password FROM usuario WHERE email=:email');
  
$query = "SELECT * FROM usuario WHERE email=?";
	$records->bindParam(':email',$_POST['email']);
	$records->execute();
	$results= $records->fetch(PDO::FETCH_ASSOC);
  $stmt = $conn->prepare($query );
  $stmt->execute(array($_POST['email']));
  $emailexistencia = $stmt->fetchColumn();
  if ($emailexistencia > 0) {
  
    if((count($results)>0 )&& password_verify($_POST['password'], $results['password']) && $opciones=="2"){
      $_SESSION['user_id']=$results['id'];
      header('location: /Proyecto3/frontend/Operador/template/Index.php' );
    } else{
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Upss!","Email o contraseña equivocados","info");';
      echo '}, 1);</script>';
    }
  
} else {
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","No existe el usario","info");';
  echo '}, 1);</script>';
}
	
}
}

if(isset($_SESSION['admin_id'])){
	header('location:/feane');
}
if(!empty($_POST))
{
  $opciones = $_POST["select"];
if(empty($_POST['email']) || empty($_POST['password'])){
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","Debe completar los campos","info");';
  echo '}, 1);</script>';
}
else{
	$records=$conn->prepare('SELECT id_administrador, usuario_admin, clave_admin FROM administrador WHERE usuario_admin=:email');
  
$query = "SELECT * FROM administrador WHERE usuario_admin=?";
	$records->bindParam(':email',$_POST['email']);
	$records->execute();
	$results= $records->fetch(PDO::FETCH_ASSOC);
  $stmt = $conn->prepare($query );
  $stmt->execute(array($_POST['email']));
  $emailExistencia = $stmt->fetchColumn();
  if ($emailExistencia > 0) {
    if((count($results)>0 )&& password_verify($_POST['password'], $results['clave_admin']) && $opciones=="1"){
      $_SESSION['admin_id']=$results['id_administrador'];
      header('location: /Proyecto3/frontend/Administrador/template/Index.php' );
    } 
   else{
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Upss!","Email o contraseña equivocados","info");';
      echo '}, 1);</script>';
    }
  
} else {
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","No existe el usario","info");';
  echo '}, 1);</script>';
}
	
}
}


if(isset($_SESSION['servicio_id'])){
  header('location:/Proyecto3/frontend/Inicio/template/pages/samples/login.php');
}
if(!empty($_POST))
{
  $opciones = $_POST["select"];
if(empty($_POST['email']) || empty($_POST['password'])){
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","Debe completar los campos","info");';
  echo '}, 1);</script>';
}
else{
	$records=$conn->prepare('SELECT id_servicio, usuario_servicio, clave_servicio FROM servicio WHERE usuario_servicio=:usuario_servicio');
  
$query = "SELECT * FROM servicio WHERE usuario_servicio=?";
	$records->bindParam(':usuario_servicio',$_POST['email']);
	$records->execute();
	$results= $records->fetch(PDO::FETCH_ASSOC);
  $stmt = $conn->prepare($query );
  $stmt->execute(array($_POST['email']));
  $emailxistencia = $stmt->fetchColumn();
  if ($emailxistencia > 0) {
    if((count($results)>0 )&& password_verify($_POST['password'], $results['clave_servicio']) && $opciones=="3"){
      $_SESSION['servicio_id']=$results['id_servicio'];
      header('location: /Proyecto3/frontend/Servicio/template/Index.php' );
    }
   else{
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Upss!","Email o contraseña equivocados","info");';
      echo '}, 1);</script>';
    }
  
  
} else {
  echo '<script type="text/javascript">';
  echo 'setTimeout(function () { swal("Upss!","No existe el usario","info");';
  echo '}, 1);</script>';
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
  <title>INICIO SESION</title>
  <!-- base:css -->
  <link rel="stylesheet" href="../../vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/LOGO.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <center>
                <img src="../../images/descarga.png" alt="logo"   class="img-fluid rounded-circle" width="132" height="132">
</center>
              </div>

              <form action="login.php" method="POST">
                <div class="form-group">
                  <input type="text"  name="email"  class="form-control form-control-lg"  placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password"  placeholder="Password">
                </div>
                <div class="mt-3">
                <select class="form-control"   id="select"  name="select" require >
                <option value="" style="display:none">Seleccione el rol</option>
												<option value="1">Administrador</option>
												<option value="2">Operador</option>
												<option value="3">Servicio</option>
												</select>
                    </div>
                <div class="mt-3">
                <button type="submit"   style="color: white; background-color: #802a4b" class="btn btn-block  btn-lg font-weight-medium auth-form-btn">Ingresar</button>
   
                </div>

                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <a href="registro.php" style="color: #a2aab4" class="auth-link text-purple">Registrese</a>
                  </div>
                  <a href="clave.php" style="color: #a2aab4" class="auth-link text-purple">Olvido la clave?</a>
                </div>
                
               
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
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
</body>

</html>
