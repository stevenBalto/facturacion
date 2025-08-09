<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../config/global.php';
    
    // Obtener acción
    $accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
    
    if ($accion === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'msg' => 'Usuario requerido']);
            exit;
        }
        
        if (empty($password)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'msg' => 'Contraseña requerida']);
            exit;
        }
        
        // Conexión con mysqli
        $conexion = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        if ($conexion->connect_error) {
            throw new Exception("Error de conexión: " . $conexion->connect_error);
        }
        
        // Buscar usuario
        $stmt = $conexion->prepare("SELECT * FROM usuario WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        
        if (!$usuario) {
            $conexion->close();
            http_response_code(401);
            echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado']);
            exit;
        }
        
        // Verificar contraseña (tanto hash como texto plano)
        $passwordValid = false;
        
        if (password_verify($password, $usuario['password'])) {
            $passwordValid = true;
        } else if ($password === $usuario['password']) {
            $passwordValid = true;
        }
        
        if (!$passwordValid) {
            $conexion->close();
            http_response_code(401);
            echo json_encode(['ok' => false, 'msg' => 'Contraseña incorrecta']);
            exit;
        }
        
        // Login exitoso - crear sesión
        $_SESSION['user'] = [
            'id' => $usuario['id_usuario'],
            'username' => $usuario['username'],
            'rol' => $usuario['rol'] ?? 'user'
        ];
        
        $conexion->close();
        
        echo json_encode(['ok' => true, 'msg' => 'Login exitoso']);
        exit;
    }
    
    if ($accion === 'logout') {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }
        session_destroy();
        echo json_encode(['ok' => true]);
        exit;
    }
    
    http_response_code(400);
    echo json_encode(['ok' => false, 'msg' => 'Acción no soportada']);
    
} catch (Exception $e) {
    error_log("AUTH ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Error interno del servidor']);
}
?>
