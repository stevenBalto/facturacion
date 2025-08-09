<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 

// Si ya está logueado, redirigir
if (!empty($_SESSION['user'])) { 
    header('Location: vistas/categoria.php'); 
    exit; 
}

$error = '';
$success = '';

// Procesar login si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'login') {
    require_once __DIR__ . '/config/global.php';
    
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username)) {
        $error = 'Por favor ingrese su usuario';
    } elseif (empty($password)) {
        $error = 'Por favor ingrese su contraseña';
    } else {
        try {
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
                $error = 'Usuario no encontrado';
            } else {
                // Verificar contraseña (tanto hash como texto plano)
                $passwordValid = false;
                
                if (password_verify($password, $usuario['password'])) {
                    $passwordValid = true;
                } else if ($password === $usuario['password']) {
                    $passwordValid = true;
                }
                
                if (!$passwordValid) {
                    $error = 'Contraseña incorrecta';
                } else {
                    // Login exitoso - crear sesión
                    $_SESSION['user'] = [
                        'id' => $usuario['id_usuario'],
                        'username' => $usuario['username'],
                        'rol' => $usuario['rol'] ?? 'user'
                    ];
                    
                    $conexion->close();
                    
                    // Redirigir a categorías
                    header('Location: vistas/categoria.php');
                    exit;
                }
            }
            
            $conexion->close();
            
        } catch (Exception $e) {
            error_log("LOGIN ERROR: " . $e->getMessage());
            $error = 'Error interno del servidor';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sistema de Facturación - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/login.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-container">
                    <div class="login-header">
                        <h2><i class="fas fa-file-invoice"></i> Sistema de Facturación</h2>
                        <p class="mb-0">Iniciar sesión</p>
                    </div>
                    <div class="login-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="accion" value="login">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Usuario
                                </label>
                                <input name="username" id="username" type="text" 
                                       class="form-control form-control-lg" required 
                                       placeholder="Ingrese su usuario"
                                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <input name="password" id="password" type="password" 
                                       class="form-control form-control-lg" required 
                                       placeholder="Ingrese su contraseña">
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100 btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.querySelector('form');
            const submitButton = document.querySelector('.btn-login');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Mostrar loading en el botón
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Entrando...';
                    submitButton.disabled = true;
                });
            }
        });
    </script>
</body>
</html>
