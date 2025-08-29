<?php
/**
 * Classe de Conexão com Banco de Dados
 * Autor: Julio Abreu
 */

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            $this->connection = new PDO('sqlite:' . DB_PATH);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            // Habilitar foreign keys no SQLite
            $this->connection->exec('PRAGMA foreign_keys = ON');
        } catch (PDOException $e) {
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Executa uma query SELECT e retorna todos os resultados
     */
    public function fetchAll($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            throw new Exception('Erro na consulta: ' . $e->getMessage());
        }
    }
    
    /**
     * Executa uma query SELECT e retorna um único resultado
     */
    public function fetch($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new Exception('Erro na consulta: ' . $e->getMessage());
        }
    }
    
    /**
     * Executa uma query INSERT, UPDATE ou DELETE
     */
    public function execute($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception('Erro na execução: ' . $e->getMessage());
        }
    }
    
    /**
     * Retorna o ID do último registro inserido
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Inicia uma transação
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Confirma uma transação
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Desfaz uma transação
     */
    public function rollback() {
        return $this->connection->rollback();
    }
    
    /**
     * Conta o número de registros de uma tabela com condições opcionais
     */
    public function count($table, $where = '', $params = []) {
        $query = "SELECT COUNT(*) as total FROM {$table}";
        if (!empty($where)) {
            $query .= " WHERE {$where}";
        }
        
        $result = $this->fetch($query, $params);
        return $result['total'] ?? 0;
    }
    
    /**
     * Busca registros com paginação
     */
    public function paginate($table, $page = 1, $limit = ITEMS_PER_PAGE, $where = '', $params = [], $orderBy = 'id DESC') {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM {$table}";
        if (!empty($where)) {
            $query .= " WHERE {$where}";
        }
        $query .= " ORDER BY {$orderBy} LIMIT {$limit} OFFSET {$offset}";
        
        return $this->fetchAll($query, $params);
    }
}
?>

