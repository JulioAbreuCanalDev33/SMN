<?php
/**
 * Model de Ocorrências Veiculares
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/BaseModel.php';

class OcorrenciaVeicular extends BaseModel {
    protected $table = 'ocorrencias_veiculares';
    protected $primaryKey = 'id';
    
    /**
     * Busca todas as ocorrências ordenadas por data
     */
    public function todas() {
        return $this->all('created_at DESC');
    }
    
    /**
     * Busca ocorrências por cliente
     */
    public function porCliente($cliente) {
        return $this->where('cliente LIKE ?', ["%{$cliente}%"], 'created_at DESC');
    }
    
    /**
     * Busca ocorrências por tipo
     */
    public function porTipo($tipo) {
        return $this->where('tipo_de_ocorrencia = ?', [$tipo], 'created_at DESC');
    }
    
    /**
     * Busca ocorrências por prestador
     */
    public function porPrestador($prestador) {
        return $this->where('prestador LIKE ?', ["%{$prestador}%"], 'created_at DESC');
    }
    
    /**
     * Busca ocorrências por cidade
     */
    public function porCidade($cidade) {
        return $this->where('cidade = ?', [$cidade], 'created_at DESC');
    }
    
    /**
     * Busca ocorrências por estado
     */
    public function porEstado($estado) {
        return $this->where('estado = ?', [$estado], 'created_at DESC');
    }
    
    /**
     * Pesquisa ocorrências
     */
    public function pesquisar($termo) {
        $termo = "%{$termo}%";
        return $this->where(
            'cliente LIKE ? OR solicitante LIKE ? OR motivo LIKE ? OR endereco_da_ocorrencia LIKE ? OR prestador LIKE ?',
            [$termo, $termo, $termo, $termo, $termo],
            'created_at DESC'
        );
    }
    
    /**
     * Busca ocorrências por período
     */
    public function porPeriodo($dataInicio, $dataFim) {
        return $this->where(
            'DATE(data_hora_evento) BETWEEN ? AND ?',
            [$dataInicio, $dataFim],
            'data_hora_evento DESC'
        );
    }
    
    /**
     * Valida dados da ocorrência
     */
    public function validar($dados, $id = null) {
        $erros = [];
        
        // Cliente obrigatório
        if (empty($dados['cliente'])) {
            $erros['cliente'] = 'Cliente é obrigatório';
        }
        
        // Serviço obrigatório
        if (empty($dados['servico'])) {
            $erros['servico'] = 'Serviço é obrigatório';
        }
        
        // Solicitante obrigatório
        if (empty($dados['solicitante'])) {
            $erros['solicitante'] = 'Solicitante é obrigatório';
        }
        
        // Motivo obrigatório
        if (empty($dados['motivo'])) {
            $erros['motivo'] = 'Motivo é obrigatório';
        }
        
        // Endereço obrigatório
        if (empty($dados['endereco_da_ocorrencia'])) {
            $erros['endereco_da_ocorrencia'] = 'Endereço da ocorrência é obrigatório';
        }
        
        // Cidade obrigatória
        if (empty($dados['cidade'])) {
            $erros['cidade'] = 'Cidade é obrigatória';
        }
        
        // Estado obrigatório
        if (empty($dados['estado'])) {
            $erros['estado'] = 'Estado é obrigatório';
        }
        
        // Tipo de ocorrência obrigatório
        if (empty($dados['tipo_de_ocorrencia'])) {
            $erros['tipo_de_ocorrencia'] = 'Tipo de ocorrência é obrigatório';
        }
        
        // Validações de valores numéricos
        $camposNumericos = [
            'valor_veicular', 'latitude', 'longitude', 'franquia_km',
            'km_inicial_atendimento', 'km_final_atendimento', 
            'total_km_percorrido', 'gastos_adicionais'
        ];
        
        foreach ($camposNumericos as $campo) {
            if (!empty($dados[$campo]) && !is_numeric($dados[$campo])) {
                $erros[$campo] = 'Campo deve ser numérico';
            }
        }
        
        // Validação de datas
        $camposData = [
            'data_hora_evento', 'data_hora_deslocamento', 'data_hora_transmissao',
            'data_hora_local', 'data_hora_inicio_atendimento', 'data_hora_fim_atendimento'
        ];
        
        foreach ($camposData as $campo) {
            if (!empty($dados[$campo]) && !$this->validarDataHora($dados[$campo])) {
                $erros[$campo] = 'Data/hora inválida';
            }
        }
        
        return $erros;
    }
    
    /**
     * Valida formato de data e hora
     */
    private function validarDataHora($dataHora) {
        $d = DateTime::createFromFormat('Y-m-d H:i:s', $dataHora);
        return $d && $d->format('Y-m-d H:i:s') === $dataHora;
    }
    
    /**
     * Calcula total de KM percorrido
     */
    public function calcularKmPercorrido($kmInicial, $kmFinal) {
        if (is_numeric($kmInicial) && is_numeric($kmFinal) && $kmFinal >= $kmInicial) {
            return $kmFinal - $kmInicial;
        }
        return 0;
    }
    
    /**
     * Calcula total de horas de atendimento
     */
    public function calcularHorasAtendimento($dataInicio, $dataFim) {
        if (!empty($dataInicio) && !empty($dataFim)) {
            $inicio = new DateTime($dataInicio);
            $fim = new DateTime($dataFim);
            
            if ($fim >= $inicio) {
                $diff = $inicio->diff($fim);
                return $diff->format('%H:%I:%S');
            }
        }
        return '00:00:00';
    }
    
    /**
     * Obtém estatísticas das ocorrências
     */
    public function estatisticas() {
        $total = $this->count();
        
        // Por tipo de ocorrência
        $porTipo = $this->db->fetchAll("
            SELECT tipo_de_ocorrencia, COUNT(*) as total 
            FROM {$this->table} 
            WHERE tipo_de_ocorrencia IS NOT NULL AND tipo_de_ocorrencia != ''
            GROUP BY tipo_de_ocorrencia 
            ORDER BY total DESC
        ");
        
        // Por estado
        $porEstado = $this->db->fetchAll("
            SELECT estado, COUNT(*) as total 
            FROM {$this->table} 
            WHERE estado IS NOT NULL AND estado != ''
            GROUP BY estado 
            ORDER BY total DESC
        ");
        
        // Por mês
        $porMes = $this->db->fetchAll("
            SELECT 
                strftime('%Y-%m', created_at) as mes,
                COUNT(*) as total
            FROM {$this->table}
            WHERE created_at >= date('now', '-12 months')
            GROUP BY strftime('%Y-%m', created_at)
            ORDER BY mes ASC
        ");
        
        // Valor total veicular
        $valorTotal = $this->db->fetch("
            SELECT SUM(valor_veicular) as total 
            FROM {$this->table} 
            WHERE valor_veicular IS NOT NULL
        ");
        
        return [
            'total' => $total,
            'por_tipo' => $porTipo,
            'por_estado' => $porEstado,
            'por_mes' => $porMes,
            'valor_total' => $valorTotal['total'] ?? 0
        ];
    }
    
    /**
     * Relatório de ocorrências por período
     */
    public function relatorioPorPeriodo($dataInicio, $dataFim) {
        return $this->where(
            'DATE(created_at) BETWEEN ? AND ?',
            [$dataInicio, $dataFim],
            'created_at DESC'
        );
    }
    
    /**
     * Busca tipos de ocorrência únicos
     */
    public function tiposOcorrencia() {
        $tipos = $this->db->fetchAll("
            SELECT DISTINCT tipo_de_ocorrencia 
            FROM {$this->table} 
            WHERE tipo_de_ocorrencia IS NOT NULL AND tipo_de_ocorrencia != ''
            ORDER BY tipo_de_ocorrencia ASC
        ");
        
        return array_column($tipos, 'tipo_de_ocorrencia');
    }
    
    /**
     * Busca prestadores únicos
     */
    public function prestadores() {
        $prestadores = $this->db->fetchAll("
            SELECT DISTINCT prestador 
            FROM {$this->table} 
            WHERE prestador IS NOT NULL AND prestador != ''
            ORDER BY prestador ASC
        ");
        
        return array_column($prestadores, 'prestador');
    }
    
    /**
     * Relatório financeiro das ocorrências
     */
    public function relatorioFinanceiro($dataInicio = null, $dataFim = null) {
        $where = "1=1";
        $params = [];
        
        if ($dataInicio && $dataFim) {
            $where .= " AND DATE(created_at) BETWEEN ? AND ?";
            $params = [$dataInicio, $dataFim];
        }
        
        return $this->db->fetch("
            SELECT 
                COUNT(*) as total_ocorrencias,
                SUM(valor_veicular) as valor_total_veicular,
                SUM(gastos_adicionais) as gastos_adicionais_total,
                AVG(valor_veicular) as valor_medio_veicular,
                SUM(total_km_percorrido) as km_total_percorrido
            FROM {$this->table}
            WHERE {$where}
        ", $params);
    }
}
?>

