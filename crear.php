<?php
header('Content-Type: application/json');

// Incluir la clase Database
require_once 'Database.php';

// Crear una instancia de la clase Database
$db = new Database();

try {
    $pdo = $db->getPDO();

    // Obtener los datos del formulario
    parse_str(file_get_contents("php://input"), $parsedInput);
    $firstName = $parsedInput['firstName'] ?? '';
    $lastName = $parsedInput['lastName'] ?? '';
    $email = $parsedInput['email'] ?? '';
    $phone = $parsedInput['phone'] ?? '';
    $address = $parsedInput['address'] ?? '';

    // Validar los datos
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($address)) {
        http_response_code(400);
        echo json_encode(['error' => 'Todos los campos son requeridos.']);
        exit();
    }

    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare("INSERT INTO students (first_name, last_name, email, phone, address) VALUES (:firstName, :lastName, :email, :phone, :address)");
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->execute();
    // Cerrar la conexión explícitamente
    $db = null;
    // Respuesta exitosa
    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    // Manejar errores
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar el formulario: ' . $e->getMessage()]);
}