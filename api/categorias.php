<?php
// api/categorias.php - API para gestión de categorías

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getCategorias($db);
        break;
    
    case 'POST':
        createCategoria($db);
        break;
    
    case 'PUT':
        updateCategoria($db);
        break;
    
    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteCategoria($db, $_GET['id']);
        } else {
            jsonResponse(['error' => 'ID requerido para eliminar'], 400);
        }
        break;
    
    default:
        jsonResponse(['error' => 'Método no soportado'], 405);
}

// Obtener todas las categorías
function getCategorias($db) {
    try {
        $query = "SELECT * FROM categorias WHERE activo = 1 ORDER BY nombre";
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['categorias' => $categorias]);
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al obtener categorías: ' . $e->getMessage()], 500);
    }
}

// Crear nueva categoría
function createCategoria($db) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        validateRequired($data, ['nombre']);
        
        $query = "INSERT INTO categorias (nombre, descripcion, imagen_url) 
                 VALUES (:nombre, :descripcion, :imagen_url)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion'] ?? '');
        $stmt->bindParam(':imagen_url', $data['imagen_url'] ?? '');
        
        $stmt->execute();
        
        $id = $db->lastInsertId();
        jsonResponse(['mensaje' => 'Categoría creada exitosamente', 'id' => $id], 201);
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al crear categoría: ' . $e->getMessage()], 500);
    }
}

// Actualizar categoría
function updateCategoria($db) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        validateRequired($data, ['id', 'nombre']);
        
        $query = "UPDATE categorias SET 
                 nombre = :nombre, 
                 descripcion = :descripcion, 
                 imagen_url = :imagen_url
                 WHERE id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion'] ?? '');
        $stmt->bindParam(':imagen_url', $data['imagen_url'] ?? '');
        
        if ($stmt->execute()) {
            jsonResponse(['mensaje' => 'Categoría actualizada exitosamente']);
        } else {
            jsonResponse(['error' => 'No se pudo actualizar la categoría'], 400);
        }
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al actualizar categoría: ' . $e->getMessage()], 500);
    }
}

// Eliminar categoría
function deleteCategoria($db, $id) {
    try {
        // Verificar si hay productos asociados
        $checkQuery = "SELECT COUNT(*) as count FROM productos WHERE categoria_id = :id AND activo = 1";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':id', $id);
        $checkStmt->execute();
        $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            jsonResponse(['error' => 'No se puede eliminar la categoría porque tiene productos asociados'], 400);
        }
        
        $query = "UPDATE categorias SET activo = 0 WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            jsonResponse(['mensaje' => 'Categoría eliminada exitosamente']);
        } else {
            jsonResponse(['error' => 'Categoría no encontrada'], 404);
        }
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al eliminar categoría: ' . $e->getMessage()], 500);
    }
}
?>