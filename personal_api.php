<?php
session_start();
require_once 'conexion.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'listar':
            $stmt = $pdo->query("SELECT * FROM personal ORDER BY id DESC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            break;
            
        case 'obtener':
            if (empty($_GET['id'])) throw new Exception("ID no proporcionado");
            $stmt = $pdo->prepare("SELECT * FROM personal WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode(['success' => (bool)$data, 'data' => $data]);
            break;
            
        case 'guardar':
            $data = $_POST;
            $esNuevo = empty($data['id']);
            
            $campos = ['nombre', 'apellido', 'usuario', 'rol', 'cedula', 'fecha_ingreso'];
            foreach ($campos as $c) {
                if (empty(trim($data[$c] ?? ''))) throw new Exception("El campo $c es obligatorio");
            }
            
            $stmt = $pdo->prepare("SELECT id FROM personal WHERE usuario = ?" . ($esNuevo ? "" : " AND id != ?"));
            $stmt->execute($esNuevo ? [$data['usuario']] : [$data['usuario'], $data['id']]);
            if ($stmt->fetch()) throw new Exception("El usuario ya existe");
            
            if ($esNuevo) {
                if (empty(trim($data['password'] ?? ''))) throw new Exception("La contraseña es obligatoria");
                $sql = "INSERT INTO personal (nombre, apellido, usuario, password, rol, cedula, telefono, direccion, fecha_ingreso) VALUES (?,?,?,?,?,?,?,?,?)";
                $params = [$data['nombre'], $data['apellido'], $data['usuario'], $data['password'], $data['rol'], $data['cedula'], $data['telefono'] ?? '', $data['direccion'] ?? '', $data['fecha_ingreso']];
            } else {
                if (!empty(trim($data['password'] ?? ''))) {
                    $sql = "UPDATE personal SET nombre=?, apellido=?, usuario=?, password=?, rol=?, cedula=?, telefono=?, direccion=?, fecha_ingreso=? WHERE id=?";
                    $params = [$data['nombre'], $data['apellido'], $data['usuario'], $data['password'], $data['rol'], $data['cedula'], $data['telefono'] ?? '', $data['direccion'] ?? '', $data['fecha_ingreso'], $data['id']];
                } else {
                    $sql = "UPDATE personal SET nombre=?, apellido=?, usuario=?, rol=?, cedula=?, telefono=?, direccion=?, fecha_ingreso=? WHERE id=?";
                    $params = [$data['nombre'], $data['apellido'], $data['usuario'], $data['rol'], $data['cedula'], $data['telefono'] ?? '', $data['direccion'] ?? '', $data['fecha_ingreso'], $data['id']];
                }
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            echo json_encode(['success' => true, 'message' => $esNuevo ? 'Personal agregado' : 'Personal actualizado']);
            break;
            
        case 'eliminar':
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['id'])) throw new Exception("ID no proporcionado");
            if (isset($_SESSION['usuario']) && $data['id'] == $_SESSION['usuario']['id']) {
                throw new Exception("No puedes eliminar tu propia cuenta");
            }
            $stmt = $pdo->prepare("DELETE FROM personal WHERE id = ?");
            $stmt->execute([$data['id']]);
            echo json_encode(['success' => true, 'message' => 'Personal eliminado']);
            break;
            
        default:
            throw new Exception("Acción no válida");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>