<?php
header('Content-Type: application/json');

include_once '../config/database.php';
include_once '../models/Denuncia.php';

$database = new Database();
$db = $database->getConnection();
$denuncia = new Denuncia($db);

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if(isset($_GET['id']) && !isset($_GET['pagina'])) {
            $denuncia->id = $_GET['id'];
            if($denuncia->leerUna()) {
                echo json_encode([
                    'success' => true,
                    'denuncia' => [
                        'id' => $denuncia->id,
                        'titulo' => $denuncia->titulo,
                        'descripcion' => $denuncia->descripcion,
                        'ubicacion' => $denuncia->ubicacion,
                        'estado' => $denuncia->estado,
                        'ciudadano' => $denuncia->ciudadano,
                        'fecha_registro' => $denuncia->fecha_registro
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Denuncia no encontrada']);
            }
        } else {
            $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";
            $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
            $registros_por_pagina = isset($_GET['registros_por_pagina']) ? intval($_GET['registros_por_pagina']) : 10;
            
            $stmt = $denuncia->leer($busqueda, $pagina, $registros_por_pagina);
            $total_registros = $denuncia->totalRegistros($busqueda);
            $total_paginas = ceil($total_registros / $registros_por_pagina);
            
            $denuncias = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $denuncias[] = $row;
            }
            
            echo json_encode([
                'success' => true,
                'denuncias' => $denuncias,
                'paginacion' => [
                    'pagina_actual' => $pagina,
                    'total_paginas' => $total_paginas,
                    'total_registros' => $total_registros
                ]
            ]);
        }
        break;
        
    case 'DELETE':
        if(isset($_GET['id'])) {
            $denuncia->id = $_GET['id'];
            if($denuncia->eliminar()) {
                echo json_encode(['success' => true, 'message' => 'Denuncia eliminada correctamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar denuncia']);
            }
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
        break;
}
?>