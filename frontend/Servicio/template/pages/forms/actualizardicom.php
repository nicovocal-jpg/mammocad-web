<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);


if (isset($_POST['id_paciente_dcm'])) {
    $id = $_POST['id_paciente_dcm'];
    $modality = $_POST['modality'];
    $study_date = $_POST['study_date'];
    $new_study_instance_uid = $_POST['study_instance_uid'];
    $new_series_instance_uid = $_POST['series_instance_uid'];
    $sop_uid = $_POST['sop_instance_uid'];

    // Buscar Study y Series anteriores
    $sql_old = "SELECT study_instance_uid, series_instance_uid FROM paciente_dcm WHERE id_paciente_dcm='$id'";
    $result_old = mysqli_query($conexion, $sql_old);
    $old_data = mysqli_fetch_assoc($result_old);

    $old_study_instance_uid = $old_data['study_instance_uid'];
    $old_series_instance_uid = $old_data['series_instance_uid'];

    // Actualizar datos en paciente_dcm
    $sql_update = "UPDATE paciente_dcm SET 
                      modality='$modality',
                      study_date='$study_date',
                      study_instance_uid='$new_study_instance_uid',
                      series_instance_uid='$new_series_instance_uid',
                      sop_instance_uid='$sop_uid'
                    WHERE id_paciente_dcm='$id'";

    if (mysqli_query($conexion, $sql_update)) {
        $storage_path = "../../../../../storage";

        $old_folder = "$storage_path/$old_study_instance_uid/$old_series_instance_uid";
        $new_folder = "$storage_path/$new_study_instance_uid/$new_series_instance_uid";

        // Si cambiaron study o serie:
        if ($old_study_instance_uid != $new_study_instance_uid || $old_series_instance_uid != $new_series_instance_uid) {
            
            // Crear nueva carpeta si no existe
            if (!is_dir($new_folder)) {
                mkdir($new_folder, 0777, true);
            }

            if (is_dir($old_folder)) {
                // Mover archivos del folder viejo al nuevo
                $files = scandir($old_folder);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') {
                        rename("$old_folder/$file", "$new_folder/$file");
                    }
                }

                // Eliminar la carpeta vieja (serie) si queda vacía
                if (is_dir($old_folder) && count(scandir($old_folder)) == 2) {
                    rmdir($old_folder);
                }

                // Eliminar la carpeta study si quedó vacía
                $old_study_folder = "$storage_path/$old_study_instance_uid";
                if (is_dir($old_study_folder) && count(scandir($old_study_folder)) == 2) {
                    rmdir($old_study_folder);
                }
            }

            // Actualizar paths en la tabla imagenes_dcm
            $sql_images = "SELECT id_image_dcm, image_path FROM imagenes_dcm WHERE paciente_dcm_id = '$id'";
            $result_images = mysqli_query($conexion, $sql_images);

            while ($row = mysqli_fetch_assoc($result_images)) {
                $id_image_dcm = $row['id_image_dcm'];
                $image_filename = basename($row['image_path']); // nombre de la imagen

                $new_image_path = "../../../../../storage/$new_study_instance_uid/$new_series_instance_uid/$image_filename";

                $sql_update_img = "UPDATE imagenes_dcm 
                                   SET image_path = '$new_image_path'
                                   WHERE id_image_dcm = '$id_image_dcm'";
                mysqli_query($conexion, $sql_update_img);
            }
        }

        header("Location: listapaciente.php?success=1");
        exit();
    } else {
        header("Location: listapaciente.php?error=1");
        exit();
    }
}



?>

