<?php
/**
 * Model de Agentes
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/BaseModel.php';

class Agente extends BaseModel {
    protected $table = 'agentes';
    protected $primaryKey = 'id_agente';
    
    /**
     * Busca todos os agentes ordenados por nome
     */
    public function todos() {
        return $this->all('nome ASC');
    }
    
    /**
     * Busca agentes ativos
     */
    public function ativos() {
        return $this->where('status = ?', ['Ativo'], 'nome ASC');
    }
    
    /**
     * Busca agentes inativos
     */
    public function inativos() {
        return $this->where('status = ?', ['Inativo'], 'nome ASC');
    }
    
    /**
     * Busca agentes por função
     */
    public function porFuncao($funcao) {
        return $this->where('funcao = ?', [$funcao], 'nome ASC');
    }
    
    /**
     * Pesquisa agentes por nome ou função
     */
    public function pesquisar($termo) {
        $termo = "%{$termo}%";
        return $this->where(
            'nome LIKE ? OR funcao LIKE ?',
            [$termo, $termo],
            'nome ASC'
        );
    }
    
    /**
     * Busca agentes com atendimentos
     */
    public function comAtendimentos() {
        return $this->db->fetchAll("
            SELECT a.*, COUNT(at.id_atendimento) as total_atendimentos
            FROM {$this->table} a
            LEFT JOIN atendimentos at ON a.id_agente = at.id_agente
            GROUP BY a.id_agente
            ORDER BY a.nome ASC
        ");
    }
    
    /**
     * Valida dados do agente
     */
    public function validar($dados, $id = null) {
        $erros = [];
        
        // Nome obrigatório
        if (empty($dados['nome'])) {
            $erros['nome'] = 'Nome é obrigatório';
        }
        
        // Função obrigatória
        if (empty($dados['funcao'])) {
            $erros['funcao'] = 'Função é obrigatória';
        }
        
        // Status obrigatório
        if (empty($dados['status'])) {
            $erros['status'] = 'Status é obrigatório';
        } elseif (!in_array($dados['status'], ['Ativo', 'Inativo'])) {
            $erros['status'] = 'Status deve ser Ativo ou Inativo';
        }
        
        return $erros;
    }
    
    /**
     * Ativa um agente
     */
    public function ativar($id) {
        return $this->update($id, ['status' => 'Ativo']);
    }
    
    /**
     * Inativa um agente
     */
    public function inativar($id) {
        return $this->update($id, ['status' => 'Inativo']);
    }
    
    /**
     * Obtém estatísticas dos agentes
     */
    public function estatisticas() {
        $total = $this->count();
        $ativos = $this->count('status = ?', ['Ativo']);
        $inativos = $this->count('status = ?', ['Inativo']);
        
        // Agentes por função
        $porFuncao = $this->db->fetchAll("
            SELECT funcao, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY funcao 
            ORDER BY total DESC
        ");
        
        // Agentes com mais atendimentos
        $maisAtendimentos = $this->db->fetchAll("
            SELECT a.nome, a.funcao, COUNT(at.id_atendimento) as total_atendimentos
            FROM {$this->table} a
            LEFT JOIN atendimentos at ON a.id_agente = at.id_agente
            WHERE a.status = 'Ativo'
            GROUP BY a.id_agente
            ORDER BY total_atendimentos DESC
            LIMIT 10
        ");
        
        return [
            'total' => $total,
            'ativos' => $ativos,
            'inativos' => $inativos,
            'por_funcao' => $porFuncao,
            'mais_atendimentos' => $maisAtendimentos
        ];
    }
    
    /**
     * Busca atendimentos do agente
     */
    public function atendimentos($agenteId) {
        return $this->db->fetchAll("
            SELECT a.*, c.nome_empresa as cliente_nome
            FROM atendimentos a
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            WHERE a.id_agente = ?
            ORDER BY a.created_at DESC
        ", [$agenteId]);
    }
    
    /**
     * Busca agentes disponíveis para atendimento
     */
    public function disponiveis() {
        return $this->ativos();
    }
    
    /**
     * Lista de funções disponíveis
     */
    public function funcoes() {
        $funcoes = $this->db->fetchAll("
            SELECT DISTINCT funcao 
            FROM {$this->table} 
            ORDER BY funcao ASC
        ");
        
        return array_column($funcoes, 'funcao');
    }
    
    /**
     * Relatório de performance dos agentes
     */
    public function relatorioPerformance($dataInicio = null, $dataFim = null) {
        $where = "a.status = 'Ativo'";
        $params = [];
        
        if ($dataInicio && $dataFim) {
            $where .= " AND at.created_at BETWEEN ? AND ?";
            $params = [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'];
        }
        
        return $this->db->fetchAll("
            SELECT 
                a.nome,
                a.funcao,
                COUNT(at.id_atendimento) as total_atendimentos,
                COUNT(CASE WHEN at.status_atendimento = 'Finalizado' THEN 1 END) as finalizados,
                COUNT(CASE WHEN at.status_atendimento = 'Em andamento' THEN 1 END) as em_andamento
            FROM {$this->table} a
            LEFT JOIN atendimentos at ON a.id_agente = at.id_agente
            WHERE {$where}
            GROUP BY a.id_agente
            ORDER BY total_atendimentos DESC
        ", $params);
    }
}
?>

