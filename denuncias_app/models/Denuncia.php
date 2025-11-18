<?php
class Denuncia {
    private $conn;
    private $table_name = "denuncias";

    public $id;
    public $titulo;
    public $descripcion;
    public $ubicacion;
    public $estado;
    public $fecha_registro;
    public $ciudadano;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear denuncia
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET titulo=:titulo, descripcion=:descripcion, ubicacion=:ubicacion, estado=:estado, ciudadano=:ciudadano";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->ciudadano = htmlspecialchars(strip_tags($this->ciudadano));
        
        // Bind parameters
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":ciudadano", $this->ciudadano);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Leer todas las denuncias con paginación y búsqueda
    public function leer($busqueda = "", $pagina = 1, $registros_por_pagina = 10) {
        $inicio = ($pagina - 1) * $registros_por_pagina;
        
        $where = "";
        if(!empty($busqueda)) {
            $where = "WHERE titulo LIKE :busqueda OR ciudadano LIKE :busqueda OR ubicacion LIKE :busqueda";
        }
        
        $query = "SELECT * FROM " . $this->table_name . " 
                 $where 
                 ORDER BY fecha_registro DESC 
                 LIMIT :inicio, :registros_por_pagina";
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($busqueda)) {
            $busqueda_param = "%$busqueda%";
            $stmt->bindParam(":busqueda", $busqueda_param);
        }
        
        $stmt->bindParam(":inicio", $inicio, PDO::PARAM_INT);
        $stmt->bindParam(":registros_por_pagina", $registros_por_pagina, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    // Obtener total de registros para paginación
    public function totalRegistros($busqueda = "") {
        $where = "";
        if(!empty($busqueda)) {
            $where = "WHERE titulo LIKE :busqueda OR ciudadano LIKE :busqueda OR ubicacion LIKE :busqueda";
        }
        
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " $where";
        $stmt = $this->conn->prepare($query);
        
        if(!empty($busqueda)) {
            $busqueda_param = "%$busqueda%";
            $stmt->bindParam(":busqueda", $busqueda_param);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'];
    }

    // Obtener una denuncia por ID
    public function leerUna() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->titulo = $row['titulo'];
            $this->descripcion = $row['descripcion'];
            $this->ubicacion = $row['ubicacion'];
            $this->estado = $row['estado'];
            $this->ciudadano = $row['ciudadano'];
            $this->fecha_registro = $row['fecha_registro'];
            return true;
        }
        return false;
    }

    // Actualizar denuncia
    public function actualizar() {
        $query = "UPDATE " . $this->table_name . " 
                 SET titulo=:titulo, descripcion=:descripcion, ubicacion=:ubicacion, estado=:estado, ciudadano=:ciudadano 
                 WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->ubicacion = htmlspecialchars(strip_tags($this->ubicacion));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->ciudadano = htmlspecialchars(strip_tags($this->ciudadano));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        // Bind parameters
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":ubicacion", $this->ubicacion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":ciudadano", $this->ciudadano);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar denuncia
    public function eliminar() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>