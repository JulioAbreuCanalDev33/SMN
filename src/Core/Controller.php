<?php
/**
 * Classe Base Controller
 * Autor: Julio Abreu
 */

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Renderiza uma view
     */
    protected function view($viewName, $data = []) {
        // Extrai variáveis para o escopo da view
        extract($data);
        
        // Inclui o header
        $headerPath = __DIR__ . '/../Views/layouts/header.php';
        if (file_exists($headerPath)) {
            include $headerPath;
        }
        
        // Inclui a view principal
        $viewPath = __DIR__ . '/../Views/' . $viewName . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new Exception("View não encontrada: {$viewName}");
        }
        
        // Inclui o footer
        $footerPath = __DIR__ . '/../Views/layouts/footer.php';
        if (file_exists($footerPath)) {
            include $footerPath;
        }
    }
    
    /**
     * Redireciona para uma URL
     */
    protected function redirect($url) {
        if (strpos($url, 'http') !== 0) {
            $url = BASE_URL . ltrim($url, '/');
        }
        
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Retorna JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Valida dados de entrada
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            // Required
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "O campo {$field} é obrigatório";
                continue;
            }
            
            // Email
            if (strpos($rule, 'email') !== false && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "O campo {$field} deve ser um email válido";
            }
            
            // Numeric
            if (strpos($rule, 'numeric') !== false && !empty($value) && !is_numeric($value)) {
                $errors[$field] = "O campo {$field} deve ser numérico";
            }
            
            // Max length
            if (preg_match('/max:(\d+)/', $rule, $matches) && !empty($value)) {
                $maxLength = (int)$matches[1];
                if (strlen($value) > $maxLength) {
                    $errors[$field] = "O campo {$field} deve ter no máximo {$maxLength} caracteres";
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Sanitiza dados de entrada
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Verifica se a requisição é POST
     */
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Verifica se a requisição é AJAX
     */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Obtém dados POST sanitizados
     */
    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $this->sanitize($_POST);
        }
        
        return isset($_POST[$key]) ? $this->sanitize($_POST[$key]) : $default;
    }
    
    /**
     * Obtém dados GET sanitizados
     */
    protected function getGet($key = null, $default = null) {
        if ($key === null) {
            return $this->sanitize($_GET);
        }
        
        return isset($_GET[$key]) ? $this->sanitize($_GET[$key]) : $default;
    }
    
    /**
     * Gera paginação
     */
    protected function paginate($table, $page = 1, $where = '', $params = []) {
        $limit = ITEMS_PER_PAGE;
        $total = $this->db->count($table, $where, $params);
        $totalPages = ceil($total / $limit);
        
        $data = $this->db->paginate($table, $page, $limit, $where, $params);
        
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_items' => $total,
                'items_per_page' => $limit,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
                'prev_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < $totalPages ? $page + 1 : null
            ]
        ];
    }
}
?>

