<?php
header('Content-Type: application/json');

// Incluir la clase Database
require_once 'Database.php';

// Crear una instancia de la clase Database
$db = new Database();

try {
    
    $pdo = $db->getPDO();

    // Obtener el ID del parámetro GET
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id) {
        // Preparar y ejecutar la consulta
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        // Cerrar la conexión explícitamente
        $db = null;
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'No se pudo eliminar el registro']);
        }
    } else {
        echo json_encode(['error' => 'ID no proporcionado']);
    }
    
} catch (PDOException $e) {
    // Manejar errores
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}