<?php

session_start();

header("Content-Type: application/json; charset=utf-8");

try{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        //Saltar a catch y mostrar el mensaje de error
        throw new Exception("Método HTTP no permitido");
    }

    $datos = json_decode(file_get_contents('php://input'), true);

    if (!is_array($datos) || json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        throw new Exception("Datos inválidos");
    }

    $email = $datos['email'] ?? "";
    $password = $datos['password'] ?? "";

    if(!is_string($email) || !is_string($password)) || $email === "" || $password === ""){
        http_response_code(400);
        throw new Exception("Email o contraseña invalidos");
    }

    require_once __DIR__ . '/../Config/conexion.php';
    $conexionDB = (new Conexion())->conectar();

    $consulta = $conexion->prepare("SELECT * FROM asociados WHERE email = :email");
    $consulta->execute([':email' => $email]);
    $asociado = $consulta->fetch();

    if(!$asociado || !password_verify($password, $asociado['password_hash'])||empty($asociado['password_hash'])){
        http_response_code(401);
        throw new Exception("Email o contraseña incorrectos");
    }

    $_SESSION["rol"] = strtolower($asociado["rol"]);
    $_SESSION["documento"] = $asociado["documento"];

    echo json_encode([
        "success" => true,
        "rol" => $_SESSION["rol"]
        "nombre" => $asociado["nombres"] . " " . $asociado["apellidos"],
    ]);
}catch (PODOException $e){
    $errorMessage = "Error: " . $e->getMessage();
    echo json_encode([
        "success" => false,
        "message" => $errorMessage
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
