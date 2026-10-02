<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'php_login_database';
$conexion = mysqli_connect($server, $username, $password, $database);

if (isset($_GET['id_img'])) {
    $id_img = $_GET['id_img'];

    // 1. Obtener la información de la imagen específica que queremos borrar
    $sql_image = "SELECT * FROM imagenes_dcm WHERE id_image_dcm = '$id_img'";
    $result_image = mysqli_query($conexion, $sql_image);
    
    if ($image = mysqli_fetch_assoc($result_image)) {
        // 2. Eliminar el archivo físico usando el image_path
        $image_path = $image['image_path'];
        
        if (file_exists($image_path)) {
            if (!unlink($image_path)) {
                header("Location: listapaciente.php?error_delete1=1");
                exit;
            }
        }
        
        // 3. Eliminar el registro de la imagen en la base de datos
        $delete_image_sql = "DELETE FROM imagenes_dcm WHERE id_image_dcm = '$id_img'";
        if (!mysqli_query($conexion, $delete_image_sql)) {
            header("Location: listapaciente.php?error_delete1=1");
            exit;
        }
        
        // 4. Opcional: Verificar si hay más imágenes en la misma serie/estudio
        // Obtener información del estudio/serie
        $paciente_dcm_id = $image['paciente_dcm_id'];
        $sql_dcm = "SELECT * FROM paciente_dcm WHERE id_paciente_dcm = '$paciente_dcm_id'";
        $result_dcm = mysqli_query($conexion, $sql_dcm);
        
        if ($dicom = mysqli_fetch_assoc($result_dcm)) {
            // Verificar si quedan más imágenes para esta serie
            $check_images_sql = "SELECT COUNT(*) as total FROM imagenes_dcm WHERE paciente_dcm_id = '$paciente_dcm_id'";
            $result_check = mysqli_query($conexion, $check_images_sql);
            $row = mysqli_fetch_assoc($result_check);
            
            if ($row['total'] == 0) {
                // No hay más imágenes, podemos borrar las carpetas si están vacías
                $series_path = "../../../../../storage/" . $dicom['study_instance_uid'] . "/" . 
                               $dicom['series_instance_uid'];
                $study_path = "../../../../../storage/" . $dicom['study_instance_uid'];
                
                // Borrar carpeta de serie si está vacía
                if (is_dir($series_path) && count(glob("$series_path/*")) === 0) {
                    rmdir($series_path);
                    
                    // Borrar carpeta de estudio si está vacía
                    if (is_dir($study_path) && count(glob("$study_path/*")) === 0) {
                        rmdir($study_path);
                    }
                }
            }
        }
        
     

    } else {
        // No se encontró la imagen
        header("Location: listapaciente.php?error_delete1=1");
        exit;
    }

      // Eliminar los registros de la tabla paciente_dcm
      $delete_dcm_sql = "DELETE FROM paciente_dcm WHERE id_paciente_dcm = ' $id_img'";
      mysqli_query($conexion, $delete_dcm_sql);
  
      // Ahora eliminar el paciente de la tabla paciente
      if (mysqli_query($conexion, $delete_dcm_sql)) {
          // Redirigir con mensaje de éxito
          header("Location: listapaciente.php?success_delete1=1");
      } else {
          // Redirigir con mensaje de error
          header("Location: listapaciente.php?error_delete1=1");
      }
} 
else {
    // Si no se encuentra el ID, redirigir con error
    header("Location: listapaciente.php?error_delete1=1");
    exit;
}
?>