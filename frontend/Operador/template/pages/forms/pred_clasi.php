<?php
// pred_clasi.php
if (isset($_POST['dcm_path'])) {
    $dcm_path = $_POST['dcm_path'];

    $python_script = '../../../../../backend/predecir.py';
    $command = "python " . escapeshellarg($python_script) . " " . escapeshellarg($dcm_path);
    $output = shell_exec($command);

    echo $output; // La salida del script Python (la predicción)
} else {
    echo "No se recibió la ruta del archivo DCM.";
}
?>