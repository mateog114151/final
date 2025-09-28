<?php
// install.php - Script de instalación de la base de datos Happy Pets

// Configuración de la base de datos
$host = 'localhost';
$username = 'root'; // Cambia por tu usuario de MySQL
$password = '';     // Cambia por tu contraseña de MySQL
$database = 'happy_pets';

try {
    // Conectar a MySQL sin especificar base de datos
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>🐾 Instalación de Base de Datos - Happy Pets</h2>";
    echo "<div style='font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px;'>";
    
    // Crear base de datos
    echo "<p>📦 Creando base de datos '$database'...</p>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p style='color: green;'>✅ Base de datos creada exitosamente.</p>";
    
    // Seleccionar la base de datos
    $pdo->exec("USE $database");
    
    // Array con todas las consultas SQL
    $queries = [
        // Tabla categorias
        "CREATE TABLE IF NOT EXISTS categorias (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(100) NOT NULL,
            descripcion TEXT,
            imagen_url VARCHAR(255),
            activo BOOLEAN DEFAULT TRUE,
            fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Tabla productos
        "CREATE TABLE IF NOT EXISTS productos (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(200) NOT NULL,
            descripcion TEXT,
            precio DECIMAL(10,2) NOT NULL,
            precio_original DECIMAL(10,2),
            descuento_porcentaje INT DEFAULT 0,
            categoria_id INT,
            imagen_url VARCHAR(255),
            stock INT DEFAULT 0,
            calificacion DECIMAL(2,1) DEFAULT 0,
            activo BOOLEAN DEFAULT TRUE,
            destacado BOOLEAN DEFAULT FALSE,
            mas_vendido BOOLEAN DEFAULT FALSE,
            fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
            fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        )",
        
        // Tabla usuarios
        "CREATE TABLE IF NOT EXISTS usuarios (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(100) NOT NULL,
            apellido VARCHAR(100) NOT NULL,
            email VARCHAR(150) UNIQUE NOT NULL,
            telefono VARCHAR(20),
            direccion TEXT,
            fecha_nacimiento DATE,
            fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
            activo BOOLEAN DEFAULT TRUE
        )",
        
        // Tabla servicios
        "CREATE TABLE IF NOT EXISTS servicios (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(150) NOT NULL,
            descripcion TEXT,
            precio DECIMAL(10,2) NOT NULL,
            duracion_minutos INT,
            imagen_url VARCHAR(255),
            activo BOOLEAN DEFAULT TRUE,
            fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Tabla pedidos
        "CREATE TABLE IF NOT EXISTS pedidos (
            id INT PRIMARY KEY AUTO_INCREMENT,
            usuario_id INT,
            fecha_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
            subtotal DECIMAL(10,2) NOT NULL,
            impuestos DECIMAL(10,2) DEFAULT 0,
            descuento DECIMAL(10,2) DEFAULT 0,
            total DECIMAL(10,2) NOT NULL,
            estado ENUM('pendiente', 'confirmado', 'en_proceso', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
            direccion_envio TEXT,
            telefono_contacto VARCHAR(20),
            notas TEXT,
            fecha_entrega_estimada DATE,
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        )",
        
        // Tabla pedido_detalles
        "CREATE TABLE IF NOT EXISTS pedido_detalles (
            id INT PRIMARY KEY AUTO_INCREMENT,
            pedido_id INT NOT NULL,
            producto_id INT NOT NULL,
            cantidad INT NOT NULL,
            precio_unitario DECIMAL(10,2) NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
            FOREIGN KEY (producto_id) REFERENCES productos(id)
        )",
        
        // Tabla citas
        "CREATE TABLE IF NOT EXISTS citas (
            id INT PRIMARY KEY AUTO_INCREMENT,
            usuario_id INT,
            servicio_id INT,
            fecha_cita DATETIME NOT NULL,
            estado ENUM('programada', 'confirmada', 'en_curso', 'completada', 'cancelada') DEFAULT 'programada',
            notas TEXT,
            precio_final DECIMAL(10,2),
            fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
            FOREIGN KEY (servicio_id) REFERENCES servicios(id)
        )",
        
        // Tabla mascotas
        "CREATE TABLE IF NOT EXISTS mascotas (
            id INT PRIMARY KEY AUTO_INCREMENT,
            usuario_id INT NOT NULL,
            nombre VARCHAR(100) NOT NULL,
            tipo ENUM('perro', 'gato', 'otro') NOT NULL,
            raza VARCHAR(100),
            edad INT,
            peso DECIMAL(5,2),
            observaciones TEXT,
            foto_url VARCHAR(255),
            activo BOOLEAN DEFAULT TRUE,
            fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        )",
        
        // Tabla inventario_movimientos
        "CREATE TABLE IF NOT EXISTS inventario_movimientos (
            id INT PRIMARY KEY AUTO_INCREMENT,
            producto_id INT NOT NULL,
            tipo ENUM('entrada', 'salida', 'ajuste') NOT NULL,
            cantidad INT NOT NULL,
            motivo VARCHAR(200),
            fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
            usuario_admin VARCHAR(100),
            FOREIGN KEY (producto_id) REFERENCES productos(id)
        )"
    ];
    
    // Ejecutar consultas de creación de tablas
    echo "<p>🏗️ Creando tablas...</p>";
    foreach ($queries as $query) {
        $pdo->exec($query);
    }
    echo "<p style='color: green;'>✅ Tablas creadas exitosamente.</p>";
    
    // Crear índices
    echo "<p>🔗 Creando índices...</p>";
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_productos_categoria ON productos(categoria_id)",
        "CREATE INDEX IF NOT EXISTS idx_productos_activo ON productos(activo)",
        "CREATE INDEX IF NOT EXISTS idx_productos_destacado ON productos(destacado)",
        "CREATE INDEX IF NOT EXISTS idx_pedidos_usuario ON pedidos(usuario_id)",
        "CREATE INDEX IF NOT EXISTS idx_pedidos_fecha ON pedidos(fecha_pedido)",
        "CREATE INDEX IF NOT EXISTS idx_citas_fecha ON citas(fecha_cita)",
        "CREATE INDEX IF NOT EXISTS idx_usuarios_email ON usuarios(email)"
    ];
    
    foreach ($indexes as $index) {
        $pdo->exec($index);
    }
    echo "<p style='color: green;'>✅ Índices creados exitosamente.</p>";
    
    // Insertar datos de ejemplo
    echo "<p>📝 Insertando datos de ejemplo...</p>";
    
    // Categorías
    $pdo->exec("INSERT IGNORE INTO categorias (id, nombre, descripcion, imagen_url) VALUES
        (1, 'Perros', 'Productos especializados para perros de todas las razas y edades', 'https://images.unsplash.com/photo-1552053831-71594a27632d?w=400'),
        (2, 'Gatos', 'Todo lo que tu gato necesita para estar feliz y saludable', 'https://images.unsplash.com/photo-1574144611937-0df059b5ef3e?w=400'),
        (3, 'Accesorios', 'Collares, correas, juguetes y accesorios variados', 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=400'),
        (4, 'Alimentación', 'Alimentos premium y snacks nutritivos', 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?w=400')");
    
    // Productos
    $pdo->exec("INSERT IGNORE INTO productos (id, nombre, descripcion, precio, precio_original, descuento_porcentaje, categoria_id, imagen_url, stock, calificacion, destacado, mas_vendido) VALUES
        (1, 'Alimento Premium Perros', 'Alimento balanceado premium para perros adultos, rico en proteínas y vitaminas', 85000.00, 100000.00, 15, 4, 'https://images.unsplash.com/photo-1589941013453-ec89f33b5e95?w=400', 50, 4.5, TRUE, TRUE),
        (2, 'Juguete Interactivo', 'Juguete interactivo que estimula la mente de tu mascota', 45000.00, 56000.00, 20, 3, 'https://images.unsplash.com/photo-1571566882372-1598d88abd90?w=400', 30, 4.0, TRUE, FALSE),
        (3, 'Cama Comfort Plus', 'Cama ultra cómoda con relleno de espuma viscoelástica', 120000.00, NULL, 0, 3, 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=400', 15, 5.0, TRUE, FALSE),
        (4, 'Kit de Aseo Completo', 'Kit completo con champú, cepillo, cortaúñas y toalla', 78000.00, NULL, 0, 3, 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400', 25, 4.5, FALSE, TRUE),
        (5, 'Juguete Mordedor', 'Juguete resistente para perros que les gusta morder', 25000.00, 30000.00, 15, 1, 'https://images.unsplash.com/photo-1619980299444-9da93e9d6cf0?w=400', 40, 3.8, FALSE, FALSE),
        (6, 'Comida Premium Gatos', 'Alimento premium para gatos con ingredientes naturales', 120000.00, 150000.00, 20, 4, 'https://images.unsplash.com/photo-1628009368231-f9d64c9f2ca6?w=400', 35, 4.7, TRUE, TRUE),
        (7, 'Cama Acolchada', 'Cama súper acolchada para máximo confort', 180000.00, 240000.00, 25, 3, 'https://images.unsplash.com/photo-1619980299380-17aeeaf9da6c?w=400', 20, 5.0, TRUE, FALSE),
        (8, 'Collar Ajustable', 'Collar ajustable de nylon resistente con hebilla de seguridad', 45000.00, NULL, 0, 3, 'https://images.unsplash.com/photo-1625772452859-5a3b1e13a020?w=400', 60, 4.2, FALSE, FALSE)");
    
    // Servicios
    $pdo->exec("INSERT IGNORE INTO servicios (id, nombre, descripcion, precio, duracion_minutos, imagen_url) VALUES
        (1, 'Baño Completo', 'Baño con champú especializado, secado y perfumado', 35000.00, 60, 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400'),
        (2, 'Corte de Pelo', 'Corte profesional según raza y preferencias del cliente', 45000.00, 90, 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400'),
        (3, 'Consulta Veterinaria', 'Consulta general con veterinario certificado', 80000.00, 30, 'https://images.unsplash.com/photo-1559190394-90ca928c3b85?w=400'),
        (4, 'Guardería Diaria', 'Cuidado y atención durante todo el día', 50000.00, 480, 'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=400'),
        (5, 'Aseo de Uñas', 'Corte y limado profesional de uñas', 15000.00, 20, 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=400')");
    
    // Usuarios de ejemplo
    $pdo->exec("INSERT IGNORE INTO usuarios (id, nombre, apellido, email, telefono, direccion) VALUES
        (1, 'Ana María', 'González', 'ana.gonzalez@email.com', '3001234567', 'Carrera 15 #45-67, Bogotá'),
        (2, 'Carlos', 'Rodríguez', 'carlos.rodriguez@email.com', '3109876543', 'Calle 80 #25-30, Bogotá'),
        (3, 'Laura', 'Martínez', 'laura.martinez@email.com', '3155551234', 'Avenida 68 #12-45, Bogotá')");
    
    // Mascotas de ejemplo
    $pdo->exec("INSERT IGNORE INTO mascotas (id, usuario_id, nombre, tipo, raza, edad, peso) VALUES
        (1, 1, 'Max', 'perro', 'Golden Retriever', 3, 28.5),
        (2, 1, 'Luna', 'gato', 'Siamés', 2, 4.2),
        (3, 2, 'Rocky', 'perro', 'Bulldog', 5, 22.0),
        (4, 3, 'Mimi', 'gato', 'Persa', 4, 3.8)");
    
    echo "<p style='color: green;'>✅ Datos de ejemplo insertados exitosamente.</p>";
    
    // Crear vistas
    echo "<p>👁️ Creando vistas...</p>";
    $pdo->exec("CREATE OR REPLACE VIEW vista_productos AS
        SELECT 
            p.id, p.nombre, p.descripcion, p.precio, p.precio_original, p.descuento_porcentaje,
            p.imagen_url, p.stock, p.calificacion, p.destacado, p.mas_vendido,
            c.nombre as categoria_nombre, p.activo, p.fecha_creacion
        FROM productos p
        LEFT JOIN categorias c ON p.categoria_id = c.id");
    
    $pdo->exec("CREATE OR REPLACE VIEW vista_pedidos AS
        SELECT 
            p.id, p.fecha_pedido, CONCAT(u.nombre, ' ', u.apellido) as cliente,
            u.email, u.telefono, p.total, p.estado, p.direccion_envio
        FROM pedidos p
        INNER JOIN usuarios u ON p.usuario_id = u.id");
    
    echo "<p style='color: green;'>✅ Vistas creadas exitosamente.</p>";
    
    echo "<h3 style='color: green;'>🎉 ¡Instalación completada exitosamente!</h3>";
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h4>📋 Resumen de la instalación:</h4>";
    echo "<ul>";
    echo "<li>✅ Base de datos 'happy_pets' creada</li>";
    echo "<li>✅ 9 tablas creadas</li>";
    echo "<li>✅ Índices optimizados</li>";
    echo "<li>✅ 4 categorías instaladas</li>";
    echo "<li>✅ 8 productos de ejemplo</li>";
    echo "<li>✅ 5 servicios configurados</li>";
    echo "<li>✅ Datos de prueba listos</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0;'>";
    echo "<h4>🔧 Próximos pasos:</h4>";
    echo "<ol>";
    echo "<li>Configura las credenciales de base de datos en <code>config/database.php</code></li>";
    echo "<li>Visita el <a href='admin/index.html' target='_blank'>Panel de Administración</a></li>";
    echo "<li>Prueba el <a href='crud.html' target='_blank'>CRUD simplificado</a></li>";
    echo "<li>Revisa la <a href='index.html' target='_blank'>página principal</a></li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<p><strong>🔒 Importante:</strong> Por seguridad, elimina este archivo install.php después de la instalación.</p>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 20px;'>";
    echo "<h3>❌ Error de instalación</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Verifica que:</p>";
    echo "<ul>";
    echo "<li>MySQL esté ejecutándose</li>";
    echo "<li>Las credenciales de base de datos sean correctas</li>";
    echo "<li>El usuario tenga permisos para crear bases de datos</li>";
    echo "</ul>";
    echo "</div>";
}
?>