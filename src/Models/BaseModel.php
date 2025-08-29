<?php
/**
 * Classe Base Model
 * Autor: Julio Abreu
 */

class BaseModel {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Busca todos os registros
     */
    public function all($orderBy = null) {
        $query = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }
        return $this->db->fetchAll($query);
    }
    
    /**
     * Busca um registro por ID
     */
    public function find($id) {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->fetch($query, [$id]);
    }
    
    /**
     * Busca registros com condições
     */
    public function where($conditions, $params = [], $orderBy = null) {
        $query = "SELECT * FROM {$this->table} WHERE {$conditions}";
        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }
        return $this->db->fetchAll($query, $params);
    }
    
    /**
     * Busca um registro com condições
     */
    public function whereFirst($conditions, $params = []) {
        $query = "SELECT * FROM {$this->table} WHERE {$conditions} LIMIT 1";
        return $this->db->fetch($query, $params);
    }
    
    /**
     * Cria um novo registro
     */
    public function create($data) {
        // Remove campos que não devem ser inseridos
        unset($data[$this->primaryKey]);
        
        // Adiciona timestamps se não existirem
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $query = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        if ($this->db->execute($query, array_values($data))) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Atualiza um registro
     */
    public function update($id, $data) {
        // Remove campos que não devem ser atualizados
        unset($data[$this->primaryKey]);
        
        // Atualiza timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $fields = array_keys($data);
        $setClause = implode(' = ?, ', $fields) . ' = ?';
        
        $query = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";
        
        $params = array_values($data);
        $params[] = $id;
        
        return $this->db->execute($query, $params);
    }
    
    /**
     * Exclui um registro
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->execute($query, [$id]);
    }
    
    /**
     * Conta registros
     */
    public function count($where = '', $params = []) {
        return $this->db->count($this->table, $where, $params);
    }
    
    /**
     * Paginação
     */
    public function paginate($page = 1, $limit = ITEMS_PER_PAGE, $where = '', $params = [], $orderBy = null) {
        $orderBy = $orderBy ?: $this->primaryKey . ' DESC';
        return $this->db->paginate($this->table, $page, $limit, $where, $params, $orderBy);
    }
    
    /**
     * Verifica se um registro existe
     */
    public function exists($id) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $result = $this->db->fetch($query, [$id]);
        return $result['total'] > 0;
    }
    
    /**
     * Busca com JOIN
     */
    public function join($joinTable, $joinCondition, $select = '*', $where = '', $params = []) {
        $query = "SELECT {$select} FROM {$this->table} 
                  JOIN {$joinTable} ON {$joinCondition}";
        
        if (!empty($where)) {
            $query .= " WHERE {$where}";
        }
        
        return $this->db->fetchAll($query, $params);
    }
    
    /**
     * Busca com LEFT JOIN
     */
    public function leftJoin($joinTable, $joinCondition, $select = '*', $where = '', $params = []) {
        $query = "SELECT {$select} FROM {$this->table} 
                  LEFT JOIN {$joinTable} ON {$joinCondition}";
        
        if (!empty($where)) {
            $query .= " WHERE {$where}";
        }
        
        return $this->db->fetchAll($query, $params);
    }
}
?>

