<?php

require_once __DIR__ . '/models/PasswordService.php';

header('Content-Type: application/json');

$service = new PasswordService();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/examen1';
$route = str_replace($basePath, '', $uri);

try {
    if ($route === '/api/password' && $_SERVER['REQUEST_METHOD'] === 'GET') {

        $password = $service->generate($_GET);

        echo json_encode([
            "success" => true,
            "password" => $password
        ]);
        exit;
    }
    if ($route === '/api/passwords' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $input = json_decode(file_get_contents("php://input"), true);

        $passwords = $service->generateMultiple($input);

        echo json_encode([
            "success" => true,
            "passwords" => $passwords
        ]);
        exit;
    }
    if ($route === '/api/password/validate' && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $input = json_decode(file_get_contents("php://input"), true);

        $result = $service->validate(
            $input['password'],
            $input['requirements']
        );

        echo json_encode($result);
        exit;
    }

    http_response_code(404);
    echo json_encode(["error" => "Ruta no encontrada"]);

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