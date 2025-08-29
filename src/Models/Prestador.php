<?php
/**
 * Model de Prestadores
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/BaseModel.php';

class Prestador extends BaseModel {
    protected $table = 'tabela_prestadores';
    protected $primaryKey = 'id';
    
    /**
     * Busca prestadores ativos
     */
    public function ativos() {
        return $this->all('nome_prestador ASC');
    }
    
    /**
     * Busca prestador por CPF
     */
    public function porCpf($cpf) {
        return $this->whereFirst('cpf_prestador = ?', [$cpf]);
    }
    
    /**
     * Busca prestador por email
     */
    public function porEmail($email) {
        return $this->whereFirst('email_prestador = ?', [$email]);
    }
    
    /**
     * Busca prestadores por serviço
     */
    public function porServico($servico) {
        return $this->where('servico_prestador LIKE ?', ["%{$servico}%"], 'nome_prestador ASC');
    }
    
    /**
     * Busca prestadores por cidade
     */
    public function porCidade($cidade) {
        return $this->where('cidade_prestador = ?', [$cidade], 'nome_prestador ASC');
    }
    
    /**
     * Busca prestadores por estado
     */
    public function porEstado($estado) {
        return $this->where('estado = ?', [$estado], 'cidade_prestador ASC, nome_prestador ASC');
    }
    
    /**
     * Pesquisa prestadores por nome
     */
    public function pesquisar($termo) {
        $termo = "%{$termo}%";
        return $this->where(
            'nome_prestador LIKE ? OR email_prestador LIKE ? OR cpf_prestador LIKE ?',
            [$termo, $termo, $termo],
            'nome_prestador ASC'
        );
    }
    
    /**
     * Valida dados do prestador
     */
    public function validar($dados, $id = null) {
        $erros = [];
        
        // Nome obrigatório
        if (empty($dados['nome_prestador'])) {
            $erros['nome_prestador'] = 'Nome é obrigatório';
        }
        
        // CPF obrigatório e único
        if (empty($dados['cpf_prestador'])) {
            $erros['cpf_prestador'] = 'CPF é obrigatório';
        } else {
            $cpfExistente = $this->porCpf($dados['cpf_prestador']);
            if ($cpfExistente && (!$id || $cpfExistente['id'] != $id)) {
                $erros['cpf_prestador'] = 'CPF já cadastrado';
            }
        }
        
        // Email obrigatório e único
        if (empty($dados['email_prestador'])) {
            $erros['email_prestador'] = 'Email é obrigatório';
        } elseif (!filter_var($dados['email_prestador'], FILTER_VALIDATE_EMAIL)) {
            $erros['email_prestador'] = 'Email inválido';
        } else {
            $emailExistente = $this->porEmail($dados['email_prestador']);
            if ($emailExistente && (!$id || $emailExistente['id'] != $id)) {
                $erros['email_prestador'] = 'Email já cadastrado';
            }
        }
        
        // Telefone obrigatório
        if (empty($dados['telefone_1_prestador'])) {
            $erros['telefone_1_prestador'] = 'Telefone é obrigatório';
        }
        
        // Serviço obrigatório
        if (empty($dados['servico_prestador'])) {
            $erros['servico_prestador'] = 'Serviço é obrigatório';
        }
        
        // Endereço obrigatório
        if (empty($dados['endereco_prestador'])) {
            $erros['endereco_prestador'] = 'Endereço é obrigatório';
        }
        
        if (empty($dados['cidade_prestador'])) {
            $erros['cidade_prestador'] = 'Cidade é obrigatória';
        }
        
        if (empty($dados['estado'])) {
            $erros['estado'] = 'Estado é obrigatório';
        }
        
        // Dados bancários obrigatórios
        if (empty($dados['codigo_do_banco'])) {
            $erros['codigo_do_banco'] = 'Código do banco é obrigatório';
        }
        
        if (empty($dados['titular_conta'])) {
            $erros['titular_conta'] = 'Titular da conta é obrigatório';
        }
        
        if (empty($dados['tipo_de_conta'])) {
            $erros['tipo_de_conta'] = 'Tipo de conta é obrigatório';
        }
        
        if (empty($dados['agencia_prestadores'])) {
            $erros['agencia_prestadores'] = 'Agência é obrigatória';
        }
        
        if (empty($dados['conta_prestadores'])) {
            $erros['conta_prestadores'] = 'Conta é obrigatória';
        }
        
        return $erros;
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
     * Obtém estatísticas dos prestadores
     */
    public function estatisticas() {
        $total = $this->count();
        
        $porEstado = $this->db->fetchAll("
            SELECT estado, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY estado 
            ORDER BY total DESC
        ");
        
        $porServico = $this->db->fetchAll("
            SELECT servico_prestador, COUNT(*) as total 
            FROM {$this->table} 
            GROUP BY servico_prestador 
            ORDER BY total DESC
        ");
        
        return [
            'total' => $total,
            'por_estado' => $porEstado,
            'por_servico' => $porServico
        ];
    }
}
?>

