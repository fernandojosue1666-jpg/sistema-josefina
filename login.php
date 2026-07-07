<?php
session_start();
require_once 'conexion.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($usuario) || empty($password)) {
        die(json_encode(['success' => false, 'message' => 'Complete todos los campos']));
    }
    
    $stmt = $pdo->prepare("SELECT * FROM personal WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || $user['password'] !== $password) {
        die(json_encode(['success' => false, 'message' => $user ? 'Contraseña incorrecta' : 'Usuario no registrado']));
    }
    
    $_SESSION['usuario'] = [
        'nombre' => $user['nombre'] . ' ' . $user['apellido'],
        'rol' => $user['rol'],
        'id' => $user['id']
    ];
    
    echo json_encode(['success' => true, 'message' => 'Bienvenido ' . $_SESSION['usuario']['nombre'], 'usuario' => $_SESSION['usuario']]);
}
?>