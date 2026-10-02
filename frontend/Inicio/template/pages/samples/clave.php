<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php 
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);
?>
<?php 
if (isset($_POST['cambiar'])) {
    if(empty($_POST['clave']) || empty($_POST['password'])||empty($_POST['contraseina'])||empty($_POST['email'])){
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () {swal("Que paso?","Debes llenar todos los campos","info");';
      echo '}, 1);</script>';
    }
    else {
        $op = $_POST['clave'];
        $np = $_POST['password'];
        $c_np = $_POST['contraseina'];
        $email=$_POST['email'];
       
        if(strlen($np)>=6){

            if($np==$c_np){
        
    	$np = password_hash($np, PASSWORD_BCRYPT);
        
  
        $query = mysqli_query($conexion,"SELECT *
        FROM servicio WHERE 
       ( usuario_servicio ='$email' )" );
       $result=mysqli_fetch_array($query);

       $query1 = mysqli_query($conexion,"SELECT *
        FROM administrador WHERE 
       ( usuario_admin ='$email' )" );
       $result1=mysqli_fetch_array($query1);

       $query2 = mysqli_query($conexion,"SELECT *
        FROM usuario WHERE 
       ( email ='$email' )" );
       $result2=mysqli_fetch_array($query2);
        if(($result=== 0)||($result1=== 0)||($result2=== 0)){
            header('Location: clave.php');
        	
        }
        else if ($op=="UNIVALLE2025"){
            $sql_1 = "UPDATE servicio
        	          SET clave_servicio='$np'
        	          WHERE usuario_servicio='$email'";
        	$sql_update1=mysqli_query($conexion, $sql_1);

            $sql_2 = "UPDATE administrador
            SET clave_admin='$np'
            WHERE usuario_admin='$email'";
  $sql_update2=mysqli_query($conexion, $sql_2);

  $sql_3 = "UPDATE usuario
  SET password='$np'
  WHERE email='$email'";
$sql_update3=mysqli_query($conexion, $sql_3);

            if($sql_update1 || $sql_update2||$sql_update3)
            {
                 echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("Genial!","Password Actualizado","success");';
                echo '}, 1);</script>';
            }
            else{
              echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("ERROR","Problemas al actualizar ","error");';
                echo '}, 1);</script>';
                }
        
    }
    else{
        echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("ERROR","Clave equivocado ","error");';
                echo '}, 1);</script>';
    }
            }
            else {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("Upss!","Las contraseñas no coinciden","info");';
                echo '}, 1);</script>';
            }
        }
else{
    echo '<script type="text/javascript">';
    echo 'setTimeout(function () { swal("Upss!","El nuevo password debe tener al menos 6 caracteres","info");';
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
  <title>RESETEO CONTRASEÑA</title>
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
              </div>
              <h4>Reseteo de contraseña</h4>
              <h6 class="font-weight-light">Debe usar la clave de la empresa para verificar
              </h6>
              <form action="clave.php" method="POST">

              <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="clave"  placeholder="CLAVE DE LA EMPRESA">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password"  placeholder="Nuevo Password">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="contraseina"  placeholder="Confirme Password">
                </div>
                <div class="form-group">
                  <input type="text"  name="email"  class="form-control form-control-lg"  placeholder="Usuario">
                </div>
            
              
                <div class="mt-3">
                <button type="submit"  name="cambiar" style="color: white; background-color: #802a4b" class="btn btn-block  btn-lg font-weight-medium auth-form-btn">CAMBIAR</button>
   
                </div>

                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                   
                  </div>
                  <a href="login.php"  style="color: #a2aab4" class="auth-link text-purple">Login</a>
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
