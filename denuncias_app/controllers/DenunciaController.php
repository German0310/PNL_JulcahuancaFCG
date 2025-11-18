<?php
include_once '../config/database.php';
include_once '../models/Denuncia.php';
$database = new Database();
$db = $database->getConnection();
$denuncia = new Denuncia($db);

$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';

switch($accion) {
    case 'crear':
        if($_POST) {
            $denuncia->titulo = $_POST['titulo'];
            $denuncia->descripcion = $_POST['descripcion'];
            $denuncia->ubicacion = $_POST['ubicacion'];
            $denuncia->estado = $_POST['estado'];
            $denuncia->ciudadano = $_POST['ciudadano'];
            
            if($denuncia->crear()) {
                header("Location: ../views/index.php?mensaje=Denuncia creada correctamente");
            } else {
                header("Location: ../views/index.php?error=Error al crear denuncia");
            }
        }
        break;
        
    case 'editar':
        if($_POST) {
            $denuncia->id = $_POST['id'];
            $denuncia->titulo = $_POST['titulo'];
            $denuncia->descripcion = $_POST['descripcion'];
            $denuncia->ubicacion = $_POST['ubicacion'];
            $denuncia->estado = $_POST['estado'];
            $denuncia->ciudadano = $_POST['ciudadano'];
            
            if($denuncia->actualizar()) {
                header("Location: ../views/index.php?mensaje=Denuncia actualizada correctamente");
            } else {
                header("Location: ../views/editar.php?id=" . $_POST['id'] . "&error=Error al actualizar denuncia");
            }
        }
        break;
        
    case 'eliminar':
        if(isset($_GET['id'])) {
            $denuncia->id = $_GET['id'];
            if($denuncia->eliminar()) {
                header("Location: ../views/index.php?mensaje=Denuncia eliminada correctamente");
            } else {
                header("Location: ../views/index.php?error=Error al eliminar denuncia");
            }
        }
        break;
}
?>