<?php
$server='localhost';
$username='root';
$password='';
$database='php_login_database';
$conexion= mysqli_connect($server,$username,$password,$database);

if (isset($_POST['id_paciente'])) {
    $id = $_POST['id_paciente'];
    $name = $_POST['patient_name'];
    $patient_id = $_POST['patient_id'];
    $bd = $_POST['patient_bd'];
    $medico_id = $_POST['medico_id'];

    $sql = "UPDATE paciente SET 
              patient_name='$name',
              patient_id='$patient_id',
              patient_bd='$bd',
              medico_id='$medico_id'
            WHERE id_paciente='$id'";

    if (mysqli_query($conexion, $sql)) {
        header("Location: listapaciente.php?success=1");
    } else {
        header("Location: listapaciente.php?error=1");
    }
}
?>
