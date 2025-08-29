<?php
/**
 * Sistema de Roteamento
 * Autor: Julio Abreu
 */

class Router {
    private $routes = [];
    
    /**
     * Adiciona uma rota GET
     */
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }
    
    /**
     * Adiciona uma rota POST
     */
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }
    
    /**
     * Adiciona uma rota
     */
    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }
    
    /**
     * Processa a requisição atual
     */
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = $this->getPath();
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchPath($route['path'], $requestPath)) {
                $params = $this->extractParams($route['path'], $requestPath);
                return $this->callCallback($route['callback'], $params);
            }
        }
        
        // Rota não encontrada
        $this->notFound();
    }
    
    /**
     * Obtém o caminho da URL atual
     */
    private function getPath() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove a base URL
        $basePath = str_replace('/public/', '/', BASE_URL);
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath) - 1);
        }
        
        // Remove query string
        if (($pos = strpos($path, '?')) !== false) {
            $path = substr($path, 0, $pos);
        }
        
        return $path === '' ? '/' : $path;
    }
    
    /**
     * Verifica se o caminho corresponde à rota
     */
    private function matchPath($routePath, $requestPath) {
        // Converte parâmetros {id} para regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $requestPath);
    }
    
    /**
     * Extrai parâmetros da URL
     */
    private function extractParams($routePath, $requestPath) {
        $params = [];
        
        // Encontra parâmetros na rota
        preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
        
        if (!empty($paramNames[1])) {
            // Converte rota para regex e extrai valores
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';
            
            preg_match($pattern, $requestPath, $matches);
            
            // Combina nomes com valores
            for ($i = 0; $i < count($paramNames[1]); $i++) {
                $params[$paramNames[1][$i]] = $matches[$i + 1] ?? null;
            }
        }
        
        return $params;
    }
    
    /**
     * Chama o callback da rota
     */
    private function callCallback($callback, $params = []) {
        if (is_string($callback)) {
            // Formato: 'Controller@method'
            list($controller, $method) = explode('@', $callback);
            
            $controllerClass = $controller . 'Controller';
            $controllerFile = __DIR__ . '/../Controllers/' . $controllerClass . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                if (class_exists($controllerClass)) {
                    $instance = new $controllerClass();
                    
                    if (method_exists($instance, $method)) {
                        return call_user_func_array([$instance, $method], $params);
                    }
                }
            }
            
            throw new Exception("Controller ou método não encontrado: {$callback}");
        }
        
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }
        
        throw new Exception("Callback inválido");
    }
    
    /**
     * Página não encontrada
     */
    private function notFound() {
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        exit;
    }
}
?>

