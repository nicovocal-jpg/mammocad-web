<?php
if (isset($_GET['image_path'])) {
    $image_path = urldecode($_GET['image_path']);

    // Devolver el image_path como JSON
    $response = array(
        'imagePath' => $image_path
    );
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'No se proporcionó image_path'));
}
?>