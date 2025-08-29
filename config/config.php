<?php
/**
 * Configurações do Sistema de Monitoramento
 * Autor: Julio Abreu
 */

// Configurações do Sistema
define('SYSTEM_NAME', 'Sistema de Monitoramento');
define('SYSTEM_VERSION', '1.0.0');
define('SYSTEM_AUTHOR', 'Julio Abreu');

// Configurações de Banco de Dados
define('DB_PATH', __DIR__ . '/../database/database.sqlite');

// Configurações de URL
define('BASE_URL', '/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOADS_URL', BASE_URL . 'uploads/');

// Configurações de Upload
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Configurações de Paginação
define('ITEMS_PER_PAGE', 20);

// Configurações de Timezone
date_default_timezone_set('America/Sao_Paulo');

// Configurações de Erro (desenvolvimento)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurações de Sessão (apenas se não estiver ativa)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_strict_mode', 1);
}
?>

