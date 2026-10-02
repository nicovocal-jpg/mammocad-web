<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

              <form action="registro_op.php" method="POST">
    <div class="form-group">
        <input type="text" name="nombre" class="form-control form-control-lg" placeholder="Nombre">
    </div>
    <div class="form-group">
        <input type="text" name="email" class="form-control form-control-lg" placeholder="Email">
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-lg" name="password" placeholder="Contraseña">
    </div>
    <div class="form-group">
        <input type="password" class="form-control form-control-lg" name="confirm_password" placeholder="Confirmar Contraseña">
    </div>
    <div class="form-group">
        <select class="form-control form-control-lg" name="genero">
            <option value="">Seleccionar género</option>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
            <option value="otro">Otro</option>
        </select>
    </div>
    <div class="mt-3">
        <button type="submit" style="color: white; background-color: #802a4b" class="btn btn-block btn-lg font-weight-medium auth-form-btn">Confirmar</button>
    </div>
    <div class="my-2 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <a href="login.php" style="color: #a2aab4" class="auth-link text-purple">Login </a>
        </div>
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

  <script>
  // Función para obtener parámetros de la URL
  function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }

  // Verificar si hay un mensaje de error en la URL
  const errorMessage = getParameterByName('error');
  if (errorMessage) {
    Swal.fire({
      icon: 'error',
      title: 'Error en el Registro',
      html: errorMessage,
    });
  }

  // Verificar si hay un mensaje de éxito en la URL
  const successMessage = getParameterByName('success');
  if (successMessage) {
    Swal.fire({
      icon: 'success',
      title: 'Registro Exitoso',
      text: successMessage,
    }).then((result) => {
      // Redirigir al inicio de sesión después de cerrar el SweetAlert (opcional)
      if (result.isConfirmed || result.dismiss === Swal.DismissReason.close) {
        window.location.href = 'login.php'; // Reemplaza con la URL de tu página de inicio de sesión
      }
    });
  }

  // Limpiar los parámetros de la URL para que no se muestren al recargar la página
  if (window.history.replaceState) {
    const newURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({ path: newURL }, '', newURL);
  }
</script>
  <!-- endinject -->
</body>

</html>
