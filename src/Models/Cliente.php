<?php
/**
 * Model de Clientes
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/BaseModel.php';

class Cliente extends BaseModel {
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    
    /**
     * Busca todos os clientes ordenados por nome
     */
    public function todos() {
        return $this->all('nome_empresa ASC');
    }
    
    /**
     * Busca cliente por CNPJ
     */
    public function porCnpj($cnpj) {
        return $this->whereFirst('cnpj = ?', [$cnpj]);
    }
    
    /**
     * Pesquisa clientes por nome ou CNPJ
     */
    public function pesquisar($termo) {
        $termo = "%{$termo}%";
        return $this->where(
            'nome_empresa LIKE ? OR cnpj LIKE ? OR contato LIKE ?',
            [$termo, $termo, $termo],
            'nome_empresa ASC'
        );
    }
    
    /**
     * Busca clientes com atendimentos
     */
    public function comAtendimentos() {
        return $this->leftJoin(
            'atendimentos',
            'clientes.id_cliente = atendimentos.id_cliente',
            'clientes.*, COUNT(atendimentos.id_atendimento) as total_atendimentos',
            '',
            [],
            'clientes.nome_empresa ASC'
        );
    }
    
    /**
     * Valida dados do cliente
     */
    public function validar($dados, $id = null) {
        $erros = [];
        
        // Nome da empresa obrigatório
        if (empty($dados['nome_empresa'])) {
            $erros['nome_empresa'] = 'Nome da empresa é obrigatório';
        }
        
        // CNPJ obrigatório e único
        if (empty($dados['cnpj'])) {
            $erros['cnpj'] = 'CNPJ é obrigatório';
        } else {
            // Valida formato do CNPJ
            $cnpj = $this->limparCnpj($dados['cnpj']);
            if (!$this->validarCnpj($cnpj)) {
                $erros['cnpj'] = 'CNPJ inválido';
            } else {
                // Verifica se já existe
                $cnpjExistente = $this->porCnpj($cnpj);
                if ($cnpjExistente && (!$id || $cnpjExistente[$this->primaryKey] != $id)) {
                    $erros['cnpj'] = 'CNPJ já cadastrado';
                }
            }
        }
        
        // Contato obrigatório
        if (empty($dados['contato'])) {
            $erros['contato'] = 'Contato é obrigatório';
        }
        
        // Telefone obrigatório
        if (empty($dados['telefone'])) {
            $erros['telefone'] = 'Telefone é obrigatório';
        }
        
        return $erros;
    }
    
    /**
     * Valida CNPJ
     */
    public function validarCnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        // Verifica se tem 14 dígitos
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        // Calcula os dígitos verificadores
        $soma = 0;
        $multiplicador = 5;
        
        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $multiplicador;
            $multiplicador = ($multiplicador == 2) ? 9 : $multiplicador - 1;
        }
        
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;
        
        if ($cnpj[12] != $digito1) {
            return false;
        }
        
        $soma = 0;
        $multiplicador = 6;
        
        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $multiplicador;
            $multiplicador = ($multiplicador == 2) ? 9 : $multiplicador - 1;
        }
        
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;
        
        return $cnpj[13] == $digito2;
    }
    
    /**
     * Formata CNPJ para exibição
     */
    public function formatarCnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }
    
    /**
     * Remove formatação do CNPJ
     */
    public function limparCnpj($cnpj) {
        return preg_replace('/[^0-9]/', '', $cnpj);
    }
    
    /**
     * Formata telefone para exibição
     */
    public function formatarTelefone($telefone) {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        
        if (strlen($telefone) == 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } elseif (strlen($telefone) == 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }
        
        return $telefone;
    }
    
    /**
     * Obtém estatísticas dos clientes
     */
    public function estatisticas() {
        $total = $this->count();
        
        // Clientes com mais atendimentos
        $maisAtendimentos = $this->db->fetchAll("
            SELECT c.nome_empresa, COUNT(a.id_atendimento) as total_atendimentos
            FROM {$this->table} c
            LEFT JOIN atendimentos a ON c.id_cliente = a.id_cliente
            GROUP BY c.id_cliente, c.nome_empresa
            ORDER BY total_atendimentos DESC
            LIMIT 10
        ");
        
        return [
            'total' => $total,
            'mais_atendimentos' => $maisAtendimentos
        ];
    }
    
    /**
     * Busca atendimentos do cliente
     */
    public function atendimentos($clienteId) {
        return $this->db->fetchAll("
            SELECT a.*, ag.nome as nome_agente
            FROM atendimentos a
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
            WHERE a.id_cliente = ?
            ORDER BY a.created_at DESC
        ", [$clienteId]);
    }
}
?>

