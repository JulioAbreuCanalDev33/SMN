<?php
/**
 * Model de Atendimentos
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/BaseModel.php';

class Atendimento extends BaseModel {
    protected $table = 'atendimentos';
    protected $primaryKey = 'id_atendimento';
    
    /**
     * Busca todos os atendimentos com dados relacionados
     */
    public function todosComRelacionamentos() {
        return $this->db->fetchAll("
            SELECT 
                a.*,
                c.nome_empresa as cliente_nome,
                ag.nome as agente_nome
            FROM {$this->table} a
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
            ORDER BY a.created_at DESC
        ");
    }
    
    /**
     * Busca atendimento com relacionamentos
     */
    public function comRelacionamentos($id) {
        return $this->db->fetch("
            SELECT 
                a.*,
                c.nome_empresa as cliente_nome,
                c.cnpj as cliente_cnpj,
                c.contato as cliente_contato,
                ag.nome as agente_nome,
                ag.funcao as agente_funcao
            FROM {$this->table} a
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
            WHERE a.id_atendimento = ?
        ", [$id]);
    }
    
    /**
     * Busca atendimentos em andamento
     */
    public function emAndamento() {
        return $this->where('status_atendimento = ?', ['Em andamento'], 'created_at DESC');
    }
    
    /**
     * Busca atendimentos finalizados
     */
    public function finalizados() {
        return $this->where('status_atendimento = ?', ['Finalizado'], 'created_at DESC');
    }
    
    /**
     * Busca atendimentos por cliente
     */
    public function porCliente($clienteId) {
        return $this->where('id_cliente = ?', [$clienteId], 'created_at DESC');
    }
    
    /**
     * Busca atendimentos por agente
     */
    public function porAgente($agenteId) {
        return $this->where('id_agente = ?', [$agenteId], 'created_at DESC');
    }
    
    /**
     * Busca atendimentos por tipo de serviço
     */
    public function porTipoServico($tipo) {
        return $this->where('tipo_de_servico = ?', [$tipo], 'created_at DESC');
    }
    
    /**
     * Pesquisa atendimentos
     */
    public function pesquisar($termo) {
        $termo = "%{$termo}%";
        return $this->db->fetchAll("
            SELECT 
                a.*,
                c.nome_empresa as cliente_nome,
                ag.nome as agente_nome
            FROM {$this->table} a
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
            WHERE a.solicitante LIKE ? 
               OR a.motivo LIKE ? 
               OR a.endereco LIKE ?
               OR c.nome_empresa LIKE ?
               OR ag.nome LIKE ?
            ORDER BY a.created_at DESC
        ", [$termo, $termo, $termo, $termo, $termo]);
    }
    
    /**
     * Valida dados do atendimento
     */
    public function validar($dados, $id = null) {
        $erros = [];
        
        // Solicitante obrigatório
        if (empty($dados['solicitante'])) {
            $erros['solicitante'] = 'Solicitante é obrigatório';
        }
        
        // Motivo obrigatório
        if (empty($dados['motivo'])) {
            $erros['motivo'] = 'Motivo é obrigatório';
        }
        
        // Cliente obrigatório
        if (empty($dados['id_cliente'])) {
            $erros['id_cliente'] = 'Cliente é obrigatório';
        }
        
        // Endereço obrigatório
        if (empty($dados['endereco'])) {
            $erros['endereco'] = 'Endereço é obrigatório';
        }
        
        // Cidade obrigatória
        if (empty($dados['cidade'])) {
            $erros['cidade'] = 'Cidade é obrigatória';
        }
        
        // Estado obrigatório
        if (empty($dados['estado'])) {
            $erros['estado'] = 'Estado é obrigatório';
        }
        
        // Status obrigatório
        if (empty($dados['status_atendimento'])) {
            $erros['status_atendimento'] = 'Status é obrigatório';
        } elseif (!in_array($dados['status_atendimento'], ['Em andamento', 'Finalizado'])) {
            $erros['status_atendimento'] = 'Status deve ser "Em andamento" ou "Finalizado"';
        }
        
        // Tipo de serviço obrigatório
        if (empty($dados['tipo_de_servico'])) {
            $erros['tipo_de_servico'] = 'Tipo de serviço é obrigatório';
        } elseif (!in_array($dados['tipo_de_servico'], ['Ronda', 'Preservação'])) {
            $erros['tipo_de_servico'] = 'Tipo de serviço deve ser "Ronda" ou "Preservação"';
        }
        
        // Valor patrimonial deve ser numérico se informado
        if (!empty($dados['valor_patrimonial']) && !is_numeric($dados['valor_patrimonial'])) {
            $erros['valor_patrimonial'] = 'Valor patrimonial deve ser numérico';
        }
        
        return $erros;
    }
    
    /**
     * Finaliza um atendimento
     */
    public function finalizar($id) {
        return $this->update($id, [
            'status_atendimento' => 'Finalizado',
            'hora_saida' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Obtém estatísticas dos atendimentos
     */
    public function estatisticas() {
        $total = $this->count();
        $emAndamento = $this->count('status_atendimento = ?', ['Em andamento']);
        $finalizados = $this->count('status_atendimento = ?', ['Finalizado']);
        
        // Por tipo de serviço
        $porTipo = $this->db->fetchAll("
            SELECT tipo_de_servico, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY tipo_de_servico 
            ORDER BY total DESC
        ");
        
        // Por status
        $porStatus = $this->db->fetchAll("
            SELECT status_atendimento, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY status_atendimento 
            ORDER BY total DESC
        ");
        
        // Atendimentos por mês (últimos 12 meses)
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
            'em_andamento' => $emAndamento,
            'finalizados' => $finalizados,
            'por_tipo' => $porTipo,
            'por_status' => $porStatus,
            'por_mes' => $porMes
        ];
    }
    
    /**
     * Busca fotos do atendimento
     */
    public function fotos($atendimentoId) {
        return $this->db->fetchAll("
            SELECT * FROM fotos_atendimentos 
            WHERE id_atendimento = ? 
            ORDER BY data_upload DESC
        ", [$atendimentoId]);
    }
    
    /**
     * Adiciona foto ao atendimento
     */
    public function adicionarFoto($atendimentoId, $caminhoFoto, $legenda = '') {
        return $this->db->execute("
            INSERT INTO fotos_atendimentos (id_atendimento, caminho_foto, legenda, data_upload)
            VALUES (?, ?, ?, ?)
        ", [$atendimentoId, $caminhoFoto, $legenda, date('Y-m-d H:i:s')]);
    }
    
    /**
     * Remove foto do atendimento
     */
    public function removerFoto($fotoId) {
        return $this->db->execute("
            DELETE FROM fotos_atendimentos WHERE id_foto = ?
        ", [$fotoId]);
    }
    
    /**
     * Relatório de atendimentos por período
     */
    public function relatorioPorPeriodo($dataInicio, $dataFim) {
        return $this->db->fetchAll("
            SELECT 
                a.*,
                c.nome_empresa as cliente_nome,
                ag.nome as agente_nome
            FROM {$this->table} a
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
            WHERE DATE(a.created_at) BETWEEN ? AND ?
            ORDER BY a.created_at DESC
        ", [$dataInicio, $dataFim]);
    }
}
?>

