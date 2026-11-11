<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ .'/../Modelo/Asociado.php';

try{


    if($_SERVER['REQUEST_METHOD'] !=='POST'){
        http_response_code(405);

        //saltar a catch y mostrar el error
        throw new exception('Método no permitido. Solo se permite POST');
    }
    //leer el contenido JSON de la solicitud enviada por fetch
    $json = file_get_contents('php://input');

    //decodificar el JSON a un array asociativo
    $datos = json_decode($json, true);  

    //validar que los datos requeridos estén presentes
    if(!is_array($datos) || json_last_error() !== JSON_ERROR_NONE){
        http_response_code(400);
        throw new exception('Datos inválidos en la solicitud');
    }
    $campos=["documento","nombres","apellidos","direccionResidencia","ciudad","telefono","email","genero","fechaNacimiento","Contraseña"];
    foreach($campos as $campo){
        if(!isset($datos[$campo])){
            http_response_code(400);
            throw new exception("Falta el campo:".$campo);
        }
    }

    if(!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)){
        http_response_code(400);
        throw new exception("Email inválido");
    }
    $asociado = new Asociado($datos);
    $resultado = $asociado->guardar();
    if($resultado){
        http_response_code(201);
        echo json_encode(["success" => true, "message" => "Asociado guardado exitosamente"]);
    }else{
        http_response_code(500);
        throw new exception("Error al guardar el asociado");    
    }
}catch(PDOException $e){
    if($e-> getCode() ==23000){
        http_response_code(409);
        echo json_encode(["success" => false, "message" => "El documento ya existe en la base de datos"]);
    }else{
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error de base de datos: ".$e->getMessage()]);
    }
    //errores de codigo por ejemplo metodo http, datos invalidos 
    

}catch (Exception $e){
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}