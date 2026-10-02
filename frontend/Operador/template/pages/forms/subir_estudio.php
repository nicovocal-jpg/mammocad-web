<?php
header('Content-Type: text/plain; charset=utf-8'); // Cambiar el tipo de contenido a texto plano

// Conexión a la base de datos
$host = 'localhost';
$dbname = 'php_login_database';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
    exit;
}

// Funciones auxiliares
function limpiar($texto) {
    return trim(htmlspecialchars($texto));
}

// Recibir datos del formulario
$patient_name = limpiar($_POST['patient_name']);
$patient_id_dcm = limpiar($_POST['patient_id']);
$patient_bd = limpiar($_POST['patient_bd']);
$modality = limpiar($_POST['modality']);
$study_date = limpiar($_POST['study_date']);
$study_uid = limpiar($_POST['study_uid']);
$series_uid = limpiar($_POST['series_uid']);
$sop_uid = limpiar($_POST['sop_uid']);
$medico = limpiar($_POST['medico_id']);

// Validación archivo
if (!isset($_FILES['dcm_files'])) {
    echo'No se subió ningún archivo.';
    exit;
}

// Depuración: Verifica la carga del archivo
var_dump($_FILES['dcm_files']);

// Variables del archivo
$archivo = $_FILES['dcm_files']['tmp_name'];
$nombre_original = $_FILES['dcm_files']['name'];

// Buscar el médico (usuario) por email
$stmt = $pdo->prepare('SELECT id FROM usuario WHERE id = ?');
$stmt->execute([$medico]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo 'Usuario (médico) no encontrado.';
    exit;
}
$usuario_id = $usuario['id'];

// Buscar o crear paciente
$stmt = $pdo->prepare('SELECT id_paciente FROM paciente WHERE patient_id = ?');
$stmt->execute([$patient_id_dcm]);
$paciente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($paciente) {
    $paciente_id = $paciente['id_paciente'];
} else {
    $stmt = $pdo->prepare('INSERT INTO paciente (patient_name, patient_id, patient_bd, medico_id) VALUES (?, ?, ?, ?)');
    $stmt->execute([$patient_name, $patient_id_dcm, $patient_bd, $usuario_id]);
    $paciente_id = $pdo->lastInsertId();
}

// Verificar si ya existe un estudio DCM con el mismo SopUID
$stmt = $pdo->prepare('SELECT id_paciente_dcm FROM paciente_dcm WHERE sop_instance_uid = ? ');
$stmt->execute([$sop_uid]);
$paciente_dcm = $stmt->fetch(PDO::FETCH_ASSOC);

if ($paciente_dcm) {
    // Si ya existe, mostramos un error
    echo  'El estudio con este SopUID ya existe.';
    exit;
}

// Insertamos el nuevo registro en paciente_dcm
$stmt = $pdo->prepare('INSERT INTO paciente_dcm (patient_id, modality, study_date, study_instance_uid, series_instance_uid,sop_instance_uid) VALUES (?, ?, ?, ?, ?,?)');
$stmt->execute([$paciente_id, $modality, $study_date, $study_uid, $series_uid,$sop_uid]);
$paciente_dcm_id = $pdo->lastInsertId();

// Guardar el archivo DICOM
$carpeta = "../../../../../storage/{$study_uid}/{$series_uid}/";
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}
$ruta_final = $carpeta . basename($nombre_original);

if (!move_uploaded_file($archivo, $ruta_final)) {
    echo 'Error al mover el archivo DICOM.';
    exit;
}

// Insertamos la ruta en tabla imagen
$ruta_relativa = "../../../../../storage/{$study_uid}/{$series_uid}/" . basename($nombre_original);
$stmt = $pdo->prepare('INSERT INTO imagenes_dcm (paciente_dcm_id, image_path) VALUES (?, ?)');
$stmt->execute([$paciente_dcm_id, $ruta_relativa]);

$response =  '¡Estudio registrado correctamente!';

// Si todo está bien, enviamos la respuesta JSON
echo $response;
exit;
?>




