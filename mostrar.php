<?php
header('Content-Type: application/json');

// Incluir la clase Database
require_once 'Database.php';

// Crear una instancia de la clase Database
$db = new Database();

try {
    // Parámetros de paginación y búsqueda
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

    // Contar el número total de registros con el término de búsqueda
    $sql_count = "SELECT COUNT(*) AS total FROM students WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search";
    $pdo = $db->getPDO();
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt_count->execute();
    
    $total = $stmt_count->fetchColumn();

    // Obtener los registros de la página actual con el término de búsqueda
    $sql = "SELECT * FROM students WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Responder con los datos y la información de paginación
    $response = [
        'total' => $total,
        'students' => $students
    ];
    // Cerrar conexión explícitamente
    $db = null;
    echo json_encode($response);

} catch (PDOException $e) {
    // Manejar errores
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
}
?>
