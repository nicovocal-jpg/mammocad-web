<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);

if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Primero, obtener todos los registros relacionados en la tabla paciente_dcm
    $sql_dcm = "SELECT * FROM paciente_dcm WHERE patient_id = '$id_paciente'";
    $result_dcm = mysqli_query($conexion, $sql_dcm);
    while ($dicom = mysqli_fetch_assoc($result_dcm)) {
        // Obtener el ID de las imágenes relacionadas en imagenes_dcm
        $image_id = $dicom['id_paciente_dcm'];

        // Eliminar las imágenes relacionadas en la base de datos (imagenes_dcm)
        $delete_images_sql = "DELETE FROM imagenes_dcm WHERE paciente_dcm_id = '$image_id'";
        mysqli_query($conexion, $delete_images_sql);

        // Eliminar las imágenes físicas en el sistema de almacenamiento
        $image_path = "../../../../../storage/" . $dicom['study_instance_uid'] . "/" . $dicom['series_instance_uid'];
        $second_path="../../../../../storage/" . $dicom['study_instance_uid'];
        if (file_exists($image_path)) {
            // Eliminar imágenes
            array_map('unlink', glob("$image_path/*"));

           // Eliminar la carpeta si está vacía
         if (count(glob("$image_path/*")) === 0) {
            rmdir($image_path);

            if (count(glob("$second_path/*")) === 0) {
                rmdir($second_path);
        }
        }
    } 
    }

    // Eliminar los registros de la tabla paciente_dcm
    $delete_dcm_sql = "DELETE FROM paciente_dcm WHERE patient_id = '$id_paciente'";
    mysqli_query($conexion, $delete_dcm_sql);

    // Ahora eliminar el paciente de la tabla paciente
    $delete_sql = "DELETE FROM paciente WHERE id_paciente = '$id_paciente'";
    if (mysqli_query($conexion, $delete_sql)) {
        // Redirigir con mensaje de éxito
        header("Location: listapaciente.php?success_delete=1");
    } else {
        // Redirigir con mensaje de error
        header("Location: listapaciente.php?error_delete=1");
    }
} else {
    // Si no se encuentra el ID, redirigir con error
    header("Location: listapaciente.php?error_delete=1");
}
?>
