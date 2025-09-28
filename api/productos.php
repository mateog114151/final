<?php
// api/productos.php - API para gestión de productos

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getProducto($db, $_GET['id']);
        } else {
            getProductos($db);
        }
        break;
    
    case 'POST':
        createProducto($db);
        break;
    
    case 'PUT':
        updateProducto($db);
        break;
    
    case 'DELETE':
        if (isset($_GET['id'])) {
            deleteProducto($db, $_GET['id']);
        } else {
            jsonResponse(['error' => 'ID requerido para eliminar'], 400);
        }
        break;
    
    default:
        jsonResponse(['error' => 'Método no soportado'], 405);
}

// Obtener todos los productos
function getProductos($db) {
    try {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                 FROM productos p 
                 LEFT JOIN categorias c ON p.categoria_id = c.id 
                 WHERE p.activo = 1 
                 ORDER BY p.fecha_creacion DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        jsonResponse(['productos' => $productos]);
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al obtener productos: ' . $e->getMessage()], 500);
    }
}

// Obtener un producto por ID
function getProducto($db, $id) {
    try {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                 FROM productos p 
                 LEFT JOIN categorias c ON p.categoria_id = c.id 
                 WHERE p.id = :id";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($producto) {
            jsonResponse(['producto' => $producto]);
        } else {
            jsonResponse(['error' => 'Producto no encontrado'], 404);
        }
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al obtener producto: ' . $e->getMessage()], 500);
    }
}

// Crear nuevo producto
function createProducto($db) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        validateRequired($data, ['nombre', 'precio', 'categoria_id']);
        
        $query = "INSERT INTO productos (nombre, descripcion, precio, precio_original, 
                 descuento_porcentaje, categoria_id, imagen_url, stock) 
                 VALUES (:nombre, :descripcion, :precio, :precio_original, 
                 :descuento_porcentaje, :categoria_id, :imagen_url, :stock)";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion'] ?? '');
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':precio_original', $data['precio_original'] ?? null);
        $stmt->bindParam(':descuento_porcentaje', $data['descuento_porcentaje'] ?? 0);
        $stmt->bindParam(':categoria_id', $data['categoria_id']);
        $stmt->bindParam(':imagen_url', $data['imagen_url'] ?? '');
        $stmt->bindParam(':stock', $data['stock'] ?? 0);
        
        $stmt->execute();
        
        $id = $db->lastInsertId();
        jsonResponse(['mensaje' => 'Producto creado exitosamente', 'id' => $id], 201);
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al crear producto: ' . $e->getMessage()], 500);
    }
}

// Actualizar producto
function updateProducto($db) {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        validateRequired($data, ['id', 'nombre', 'precio']);
        
        $query = "UPDATE productos SET 
                 nombre = :nombre, 
                 descripcion = :descripcion, 
                 precio = :precio, 
                 precio_original = :precio_original,
                 descuento_porcentaje = :descuento_porcentaje,
                 categoria_id = :categoria_id, 
                 imagen_url = :imagen_url, 
                 stock = :stock,
                 fecha_actualizacion = CURRENT_TIMESTAMP
                 WHERE id = :id";
        
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':descripcion', $data['descripcion'] ?? '');
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':precio_original', $data['precio_original'] ?? null);
        $stmt->bindParam(':descuento_porcentaje', $data['descuento_porcentaje'] ?? 0);
        $stmt->bindParam(':categoria_id', $data['categoria_id']);
        $stmt->bindParam(':imagen_url', $data['imagen_url'] ?? '');
        $stmt->bindParam(':stock', $data['stock'] ?? 0);
        
        if ($stmt->execute()) {
            jsonResponse(['mensaje' => 'Producto actualizado exitosamente']);
        } else {
            jsonResponse(['error' => 'No se pudo actualizar el producto'], 400);
        }
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al actualizar producto: ' . $e->getMessage()], 500);
    }
}

// Eliminar producto (soft delete)
function deleteProducto($db, $id) {
    try {
        $query = "UPDATE productos SET activo = 0 WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            jsonResponse(['mensaje' => 'Producto eliminado exitosamente']);
        } else {
            jsonResponse(['error' => 'Producto no encontrado'], 404);
        }
        
    } catch (Exception $e) {
        jsonResponse(['error' => 'Error al eliminar producto: ' . $e->getMessage()], 500);
    }
}
?>