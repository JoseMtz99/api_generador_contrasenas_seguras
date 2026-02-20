<?php
require_once __DIR__ . '/../../models/PasswordService.php';

header('Content-Type: application/json');

$service = new PasswordService();

try {

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $password = $service->generate($_GET);

        echo json_encode([
            "success" => true,
            "password" => $password
        ]);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $input = json_decode(file_get_contents("php://input"), true);

        $passwords = $service->generateMultiple($input);

        echo json_encode([
            "success" => true,
            "passwords" => $passwords
        ]);
        exit;
    }

    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);

} catch (InvalidArgumentException $e) {

    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "Error interno del servidor"
    ]);
}