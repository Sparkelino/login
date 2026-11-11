<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        throw new Exception('Método no permitido. Solo se permite POST');
    }

    $datos = json_decode(file_get_contents('php://input'), true);
    if (!is_array($datos) || json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        throw new Exception('Datos inválidos en la solicitud');
    }

    $email = $datos['email'] ?? $datos['Usuario'] ?? '';
    $contraseña = $datos['Contraseña'] ?? $datos['password'] ?? '';

    if (!is_string($email) || !is_string($contraseña) || trim($email) === '' || trim($contraseña) === '') {
        http_response_code(400);
        throw new Exception('Email o contraseña inválidos');
    }

    require_once __DIR__ . '/../Config/conexion.php';
    $conexion = (new Conexion())->conectar();
    $consulta = $conexion->prepare('SELECT * FROM asociados WHERE email = :email');
    $consulta->execute([':email' => trim($email)]);
    $asociado = $consulta->fetch();

    if (!$asociado || !password_verify($contraseña, $asociado['contraseña']) || empty($asociado['contraseña'])) {
        http_response_code(401);
        throw new Exception('Email o contraseña incorrectos');
    }

    $_SESSION['rol'] = strtolower($asociado['rol']);
    $_SESSION['documento'] = $asociado['documento'];

    echo json_encode([
        'success' => true,
        'rol' => $_SESSION['rol'],
        'nombre' => $asociado['nombres'] . ' ' . $asociado['apellidos'],
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
}