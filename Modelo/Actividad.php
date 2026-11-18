<?php

require_once _DIR_ . '/../Config/conexion.php';

class actividad{

    private $conexion;

    public function __construct()
    {
        $conexionDB = new Conexion();
        $this->conexion = $conexionDB->conectar();
    }

    public function obtenerActividades(){
        $sql = "SELECT * FROM actividades ORDER BY fechaEnvento ASC";
        $consulta = $this->conexion->prepare($sql);
        return $consulta->fetchAll();
    }


    public function guardar(){
        $sql = "INSERT INTO actividades (nombreActividad, fechaEvento, municipio) 
                VALUES (:nombreActividad, :fechaEvento, :municipio)";
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
            ':nombreActividad' => $datos['nombreActividad'],
            ':fechaEvento' => $datos['fechaEvento'],
            ':municipio' => $datos['municipio']
        ]);
    }

    public function eliminar($idActividad)
    {
        $sql = "DELETE FROM actividades WHERE idActividad = :idActividad";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':idActividad' => $idActividad]);
        return $consulta->rowCount() > 0; // Retorna true si se eliminó alguna fila
    }

    public function editar($datos)
    {
        $sql = "UPDATE actividades SET nombreActividad = :nombreActividad, fechaEvento = :fechaEvento, municipio = :municipio, totalRecaudado WHERE idActividad = :idActividad";
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
            ':nombreActividad' => $datos['nombreActividad'],
            ':fechaEvento' => $datos['fechaEvento'],
            ':municipio' => $datos['municipio'],
            ':totalRecaudado' => $datos['totalRecaudado'],
            ':idActividad' => $datos['idActividad']
        ]);

        if ($consulta->rowCount() > 0) {
            return true; 
        }

        $verificar = $this->conexion->prepare("SELECT * FROM actividades WHERE idActividad = :idActividad");
        $verificar->execute([':idActividad' => $datos['idActividad']]);

        return (bool)$verificar->fetch(); // Retorna true si el registro existe, false si no
    }
}