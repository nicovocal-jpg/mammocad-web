<?php
header('Content-Type: text/plain; charset=utf-8');

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

// Recibir datos
$medico_id = limpiar($_POST['medico_id'] ?? '');
$atributos_json = $_POST['atributos_dcm'] ?? '{}';
$atributos = json_decode($atributos_json, true);

// Validación básica
if (empty($medico_id) || empty($_FILES['dcm_file'])) {
    echo 'Error: Faltan datos del médico o el archivo.';
    exit;
}

// Buscar el médico
$stmt_medico = $pdo->prepare('SELECT id FROM usuario WHERE id = ?');
$stmt_medico->execute([$medico_id]);
$medico = $stmt_medico->fetch(PDO::FETCH_ASSOC);

if (!$medico) {
    echo 'Error: Médico no encontrado.';
    exit;
}
$medico_id = $medico['id'];

$archivo_tmp = $_FILES['dcm_file']['tmp_name'];
$nombre_original = $_FILES['dcm_file']['name'];

$patient_name = limpiar($atributos['patientName'] ?? '');
$patient_id_dcm = limpiar($atributos['patientID'] ?? '');
$study_uid = limpiar($atributos['studyInstanceUID'] ?? '');
$series_uid = limpiar($atributos['seriesInstanceUID'] ?? '');
$sop_uid = limpiar($atributos['sopInstanceUID'] ?? '');
$modality = limpiar($atributos['modality'] ?? '');
$study_date = limpiar($atributos['studyDate'] ?? '');
$patient_bd = limpiar($atributos['patientBD'] ?? '');

try {
    // Buscar o crear paciente
    $stmt_paciente = $pdo->prepare('SELECT id_paciente FROM paciente WHERE patient_id = ?');
    $stmt_paciente->execute([$patient_id_dcm]);
    $paciente = $stmt_paciente->fetch(PDO::FETCH_ASSOC);

    if (!$paciente) {
        $stmt_insert_paciente = $pdo->prepare('INSERT INTO paciente (patient_name, patient_id, medico_id,patient_bd) VALUES (?, ?, ?,?)');
        $stmt_insert_paciente->execute([$patient_name, $patient_id_dcm, $medico_id,$patient_bd]);
        $paciente_id = $pdo->lastInsertId();
    } else {
        $paciente_id = $paciente['id_paciente'];
    }

    // Verificar si ya existe el estudio DCM
    $stmt_dcm = $pdo->prepare('SELECT id_paciente_dcm FROM paciente_dcm WHERE sop_instance_uid = ?');
    $stmt_dcm->execute([$sop_uid]);
    $paciente_dcm_existe = $stmt_dcm->fetch(PDO::FETCH_ASSOC);

    if ($paciente_dcm_existe) {
        echo "Advertencia: El estudio con SopUID {$sop_uid} ya existe.";
        exit;
    }

    // Insertar en paciente_dcm
    $stmt_insert_dcm = $pdo->prepare('INSERT INTO paciente_dcm (patient_id, modality, study_date, study_instance_uid, series_instance_uid, sop_instance_uid) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt_insert_dcm->execute([$paciente_id, $modality, $study_date, $study_uid, $series_uid, $sop_uid]);
    $paciente_dcm_id = $pdo->lastInsertId();

    // Guardar el archivo
    $carpeta = "../../../../../storage/{$study_uid}/{$series_uid}/";
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    $ruta_final = $carpeta . basename($nombre_original);

    if (move_uploaded_file($archivo_tmp, $ruta_final)) {
        $ruta_relativa = "../../../../../storage/{$study_uid}/{$series_uid}/" . basename($nombre_original);
        $stmt_insert_imagen = $pdo->prepare('INSERT INTO imagenes_dcm (paciente_dcm_id, image_path) VALUES (?, ?)');
        $stmt_insert_imagen->execute([$paciente_dcm_id, $ruta_relativa]);
        echo '¡Estudio registrado correctamente!';
    } else {
        echo 'Error al mover el archivo.';
    }

} catch (PDOException $e) {
    echo 'Error en la base de datos: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'Error general: ' . $e->getMessage();
}

exit;
?>