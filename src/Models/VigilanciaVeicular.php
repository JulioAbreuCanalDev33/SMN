<?php
/**
 * Model de Vigilância Veicular
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/BaseModel.php';

class VigilanciaVeicular extends BaseModel {
    protected $table = 'vigilancia_veicular';
    protected $primaryKey = 'id';
    
    /**
     * Busca todas as vigilâncias ordenadas por data
     */
    public function todas() {
        return $this->all('created_at DESC');
    }
    
    /**
     * Busca vigilâncias em andamento
     */
    public function emAndamento() {
        return $this->where('status_do_atendimento = ?', ['Em andamento'], 'created_at DESC');
    }
    
    /**
     * Busca vigilâncias finalizadas
     */
    public function finalizadas() {
        return $this->where('status_do_atendimento = ?', ['Finalizado'], 'created_at DESC');
    }
    
    /**
     * Busca vigilâncias por placa
     */
    public function porPlaca($placa) {
        return $this->where('placa LIKE ?', ["%{$placa}%"], 'created_at DESC');
    }
    
    /**
     * Busca vigilâncias por marca
     */
    public function porMarca($marca) {
        return $this->where('marca LIKE ?', ["%{$marca}%"], 'created_at DESC');
    }
    
    /**
     * Busca vigilâncias por cidade
     */
    public function porCidade($cidade) {
        return $this->where('cidade = ?', [$cidade], 'created_at DESC');
    }
    
    /**
     * Busca veículos recuperados
     */
    public function veiculosRecuperados() {
        return $this->where('veiculo_foi_recuperado = ?', ['Sim'], 'created_at DESC');
    }
    
    /**
     * Busca veículos não recuperados
     */
    public function veiculosNaoRecuperados() {
        return $this->where('veiculo_foi_recuperado = ?', ['Não'], 'created_at DESC');
    }
    
    /**
     * Pesquisa vigilâncias
     */
    public function pesquisar($termo) {
        $termo = "%{$termo}%";
        return $this->where(
            'placa LIKE ? OR marca LIKE ? OR modelo LIKE ? OR nome_do_condutor LIKE ? OR cpf_condutor LIKE ?',
            [$termo, $termo, $termo, $termo, $termo],
            'created_at DESC'
        );
    }
    
    /**
     * Valida dados da vigilância
     */
    public function validar($dados, $id = null) {
        $erros = [];
        
        // Placa obrigatória
        if (empty($dados['placa'])) {
            $erros['placa'] = 'Placa é obrigatória';
        }
        
        // Status de recuperação obrigatório
        if (empty($dados['veiculo_foi_recuperado'])) {
            $erros['veiculo_foi_recuperado'] = 'Status de recuperação é obrigatório';
        } elseif (!in_array($dados['veiculo_foi_recuperado'], ['Sim', 'Não'])) {
            $erros['veiculo_foi_recuperado'] = 'Status deve ser "Sim" ou "Não"';
        }
        
        // Condutor é proprietário obrigatório
        if (empty($dados['condutor_e_proprietario'])) {
            $erros['condutor_e_proprietario'] = 'Informação sobre condutor/proprietário é obrigatória';
        } elseif (!in_array($dados['condutor_e_proprietario'], ['Sim', 'Não'])) {
            $erros['condutor_e_proprietario'] = 'Campo deve ser "Sim" ou "Não"';
        }
        
        // Status do atendimento obrigatório
        if (empty($dados['status_do_atendimento'])) {
            $erros['status_do_atendimento'] = 'Status do atendimento é obrigatório';
        } elseif (!in_array($dados['status_do_atendimento'], ['Em andamento', 'Finalizado'])) {
            $erros['status_do_atendimento'] = 'Status deve ser "Em andamento" ou "Finalizado"';
        }
        
        // Validação de CPF se informado
        if (!empty($dados['cpf_condutor'])) {
            $cpf = $this->limparCpf($dados['cpf_condutor']);
            if (!$this->validarCpf($cpf)) {
                $erros['cpf_condutor'] = 'CPF inválido';
            }
        }
        
        return $erros;
    }
    
    /**
     * Valida CPF
     */
    public function validarCpf($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) != 11) {
            return false;
        }
        
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Formata CPF para exibição
     */
    public function formatarCpf($cpf) {
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }
    
    /**
     * Remove formatação do CPF
     */
    public function limparCpf($cpf) {
        return preg_replace('/[^0-9]/', '', $cpf);
    }
    
    /**
     * Finaliza vigilância
     */
    public function finalizar($id) {
        return $this->update($id, ['status_do_atendimento' => 'Finalizado']);
    }
    
    /**
     * Obtém estatísticas das vigilâncias
     */
    public function estatisticas() {
        $total = $this->count();
        $emAndamento = $this->count('status_do_atendimento = ?', ['Em andamento']);
        $finalizadas = $this->count('status_do_atendimento = ?', ['Finalizado']);
        $recuperados = $this->count('veiculo_foi_recuperado = ?', ['Sim']);
        $naoRecuperados = $this->count('veiculo_foi_recuperado = ?', ['Não']);
        
        // Por marca
        $porMarca = $this->db->fetchAll("
            SELECT marca, COUNT(*) as total 
            FROM {$this->table} 
            WHERE marca IS NOT NULL AND marca != ''
            GROUP BY marca 
            ORDER BY total DESC
            LIMIT 10
        ");
        
        // Por cidade
        $porCidade = $this->db->fetchAll("
            SELECT cidade, COUNT(*) as total 
            FROM {$this->table} 
            WHERE cidade IS NOT NULL AND cidade != ''
            GROUP BY cidade 
            ORDER BY total DESC
            LIMIT 10
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
        
        return [
            'total' => $total,
            'em_andamento' => $emAndamento,
            'finalizadas' => $finalizadas,
            'recuperados' => $recuperados,
            'nao_recuperados' => $naoRecuperados,
            'por_marca' => $porMarca,
            'por_cidade' => $porCidade,
            'por_mes' => $porMes
        ];
    }
    
    /**
     * Busca fotos da vigilância
     */
    public function fotos($vigilanciaId) {
        return $this->db->fetchAll("
            SELECT * FROM fotos_vigilancia_veicular 
            WHERE vigilancia_id = ? 
            ORDER BY data_upload DESC
        ", [$vigilanciaId]);
    }
    
    /**
     * Adiciona foto à vigilância
     */
    public function adicionarFoto($vigilanciaId, $caminhoFoto, $legenda = '') {
        return $this->db->execute("
            INSERT INTO fotos_vigilancia_veicular (vigilancia_id, foto, legenda, data_upload)
            VALUES (?, ?, ?, ?)
        ", [$vigilanciaId, $caminhoFoto, $legenda, date('Y-m-d H:i:s')]);
    }
    
    /**
     * Remove foto da vigilância
     */
    public function removerFoto($fotoId) {
        return $this->db->execute("
            DELETE FROM fotos_vigilancia_veicular WHERE id = ?
        ", [$fotoId]);
    }
    
    /**
     * Relatório de vigilâncias por período
     */
    public function relatorioPorPeriodo($dataInicio, $dataFim) {
        return $this->where(
            'DATE(created_at) BETWEEN ? AND ?',
            [$dataInicio, $dataFim],
            'created_at DESC'
        );
    }
    
    /**
     * Busca marcas únicas
     */
    public function marcas() {
        $marcas = $this->db->fetchAll("
            SELECT DISTINCT marca 
            FROM {$this->table} 
            WHERE marca IS NOT NULL AND marca != ''
            ORDER BY marca ASC
        ");
        
        return array_column($marcas, 'marca');
    }
    
    /**
     * Busca modelos por marca
     */
    public function modelosPorMarca($marca) {
        $modelos = $this->db->fetchAll("
            SELECT DISTINCT modelo 
            FROM {$this->table} 
            WHERE marca = ? AND modelo IS NOT NULL AND modelo != ''
            ORDER BY modelo ASC
        ", [$marca]);
        
        return array_column($modelos, 'modelo');
    }
    
    /**
     * Relatório de recuperação de veículos
     */
    public function relatorioRecuperacao($dataInicio = null, $dataFim = null) {
        $where = "1=1";
        $params = [];
        
        if ($dataInicio && $dataFim) {
            $where .= " AND DATE(created_at) BETWEEN ? AND ?";
            $params = [$dataInicio, $dataFim];
        }
        
        return $this->db->fetch("
            SELECT 
                COUNT(*) as total_vigilancias,
                COUNT(CASE WHEN veiculo_foi_recuperado = 'Sim' THEN 1 END) as recuperados,
                COUNT(CASE WHEN veiculo_foi_recuperado = 'Não' THEN 1 END) as nao_recuperados,
                ROUND(
                    (COUNT(CASE WHEN veiculo_foi_recuperado = 'Sim' THEN 1 END) * 100.0 / COUNT(*)), 2
                ) as taxa_recuperacao
            FROM {$this->table}
            WHERE {$where}
        ", $params);
    }
}
?>

