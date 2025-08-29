<?php
/**
 * Model de Rondas Periódicas
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/BaseModel.php';

class RondaPeriodica extends BaseModel {
    protected $table = 'rondas_periodicas';
    protected $primaryKey = 'id_ronda';
    
    /**
     * Busca todas as rondas com dados do atendimento
     */
    public function todasComAtendimento() {
        return $this->db->fetchAll("
            SELECT 
                r.*,
                a.solicitante,
                a.endereco,
                a.cidade,
                a.estado,
                c.nome_empresa as cliente_nome
            FROM {$this->table} r
            LEFT JOIN atendimentos a ON r.id_atendimento = a.id_atendimento
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            ORDER BY r.created_at DESC
        ");
    }
    
    /**
     * Busca ronda com dados do atendimento
     */
    public function comAtendimento($id) {
        return $this->db->fetch("
            SELECT 
                r.*,
                a.solicitante,
                a.endereco,
                a.cidade,
                a.estado,
                a.motivo,
                c.nome_empresa as cliente_nome,
                ag.nome as agente_nome
            FROM {$this->table} r
            LEFT JOIN atendimentos a ON r.id_atendimento = a.id_atendimento
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
            WHERE r.id_ronda = ?
        ", [$id]);
    }
    
    /**
     * Busca rondas por atendimento
     */
    public function porAtendimento($atendimentoId) {
        return $this->where('id_atendimento = ?', [$atendimentoId], 'created_at DESC');
    }
    
    /**
     * Busca rondas pagas
     */
    public function pagas() {
        return $this->where('pagamento = ?', ['Pago'], 'created_at DESC');
    }
    
    /**
     * Busca rondas pendentes de pagamento
     */
    public function pendentes() {
        return $this->where('pagamento = ?', ['Pendente'], 'created_at DESC');
    }
    
    /**
     * Busca rondas que vencem hoje
     */
    public function vencemHoje() {
        $hoje = date('Y-m-d');
        return $this->db->fetchAll("
            SELECT 
                r.*,
                a.solicitante,
                a.endereco,
                c.nome_empresa as cliente_nome
            FROM {$this->table} r
            LEFT JOIN atendimentos a ON r.id_atendimento = a.id_atendimento
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            WHERE r.data_final = ?
            ORDER BY r.created_at DESC
        ", [$hoje]);
    }
    
    /**
     * Busca rondas vencidas
     */
    public function vencidas() {
        $hoje = date('Y-m-d');
        return $this->db->fetchAll("
            SELECT 
                r.*,
                a.solicitante,
                a.endereco,
                c.nome_empresa as cliente_nome
            FROM {$this->table} r
            LEFT JOIN atendimentos a ON r.id_atendimento = a.id_atendimento
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            WHERE r.data_final < ?
            ORDER BY r.data_final ASC
        ", [$hoje]);
    }
    
    /**
     * Valida dados da ronda
     */
    public function validar($dados, $id = null) {
        $erros = [];
        
        // Atendimento obrigatório
        if (empty($dados['id_atendimento'])) {
            $erros['id_atendimento'] = 'Atendimento é obrigatório';
        }
        
        // Quantidade de rondas obrigatória
        if (empty($dados['quantidade_rondas'])) {
            $erros['quantidade_rondas'] = 'Quantidade de rondas é obrigatória';
        } elseif (!is_numeric($dados['quantidade_rondas']) || $dados['quantidade_rondas'] <= 0) {
            $erros['quantidade_rondas'] = 'Quantidade deve ser um número maior que zero';
        }
        
        // Data final obrigatória
        if (empty($dados['data_final'])) {
            $erros['data_final'] = 'Data final é obrigatória';
        } elseif (!$this->validarData($dados['data_final'])) {
            $erros['data_final'] = 'Data final inválida';
        }
        
        // Status de pagamento obrigatório
        if (empty($dados['pagamento'])) {
            $erros['pagamento'] = 'Status de pagamento é obrigatório';
        } elseif (!in_array($dados['pagamento'], ['Pago', 'Pendente'])) {
            $erros['pagamento'] = 'Status deve ser "Pago" ou "Pendente"';
        }
        
        // Validações dos campos de verificação (Sim/Não)
        $camposSimNao = [
            'contato_no_local', 'verificado_fiacao', 'quadro_eletrico',
            'verificado_portas_entradas', 'local_energizado', 
            'sirene_disparada', 'local_violado'
        ];
        
        foreach ($camposSimNao as $campo) {
            if (!empty($dados[$campo]) && !in_array($dados[$campo], ['Sim', 'Não'])) {
                $erros[$campo] = 'Campo deve ser "Sim" ou "Não"';
            }
        }
        
        return $erros;
    }
    
    /**
     * Valida formato de data
     */
    private function validarData($data) {
        $d = DateTime::createFromFormat('Y-m-d', $data);
        return $d && $d->format('Y-m-d') === $data;
    }
    
    /**
     * Marca pagamento como pago
     */
    public function marcarPago($id) {
        return $this->update($id, ['pagamento' => 'Pago']);
    }
    
    /**
     * Marca pagamento como pendente
     */
    public function marcarPendente($id) {
        return $this->update($id, ['pagamento' => 'Pendente']);
    }
    
    /**
     * Obtém estatísticas das rondas
     */
    public function estatisticas() {
        $total = $this->count();
        $pagas = $this->count('pagamento = ?', ['Pago']);
        $pendentes = $this->count('pagamento = ?', ['Pendente']);
        
        $hoje = date('Y-m-d');
        $vencemHoje = $this->count('data_final = ?', [$hoje]);
        $vencidas = $this->count('data_final < ?', [$hoje]);
        
        // Rondas por mês
        $porMes = $this->db->fetchAll("
            SELECT 
                strftime('%Y-%m', created_at) as mes,
                COUNT(*) as total
            FROM {$this->table}
            WHERE created_at >= date('now', '-12 months')
            GROUP BY strftime('%Y-%m', created_at)
            ORDER BY mes ASC
        ");
        
        return [
            'total' => $total,
            'pagas' => $pagas,
            'pendentes' => $pendentes,
            'vencem_hoje' => $vencemHoje,
            'vencidas' => $vencidas,
            'por_mes' => $porMes
        ];
    }
    
    /**
     * Relatório de rondas por período
     */
    public function relatorioPorPeriodo($dataInicio, $dataFim) {
        return $this->db->fetchAll("
            SELECT 
                r.*,
                a.solicitante,
                a.endereco,
                a.cidade,
                a.estado,
                c.nome_empresa as cliente_nome,
                ag.nome as agente_nome
            FROM {$this->table} r
            LEFT JOIN atendimentos a ON r.id_atendimento = a.id_atendimento
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
            WHERE DATE(r.created_at) BETWEEN ? AND ?
            ORDER BY r.created_at DESC
        ", [$dataInicio, $dataFim]);
    }
    
    /**
     * Busca rondas que precisam de atenção
     */
    public function precisamAtencao() {
        $hoje = date('Y-m-d');
        $proximaSemana = date('Y-m-d', strtotime('+7 days'));
        
        return $this->db->fetchAll("
            SELECT 
                r.*,
                a.solicitante,
                a.endereco,
                c.nome_empresa as cliente_nome,
                CASE 
                    WHEN r.data_final < ? THEN 'Vencida'
                    WHEN r.data_final = ? THEN 'Vence Hoje'
                    WHEN r.data_final <= ? THEN 'Vence em Breve'
                    ELSE 'Normal'
                END as urgencia
            FROM {$this->table} r
            LEFT JOIN atendimentos a ON r.id_atendimento = a.id_atendimento
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            WHERE r.data_final <= ? OR r.pagamento = 'Pendente'
            ORDER BY r.data_final ASC
        ", [$hoje, $hoje, $proximaSemana, $proximaSemana]);
    }
}
?>

