<?php
/**
 * Controller de Clientes
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Cliente.php';

class ClienteController extends Controller {
    private $clienteModel;
    
    public function __construct() {
        parent::__construct();
        $this->clienteModel = new Cliente();
    }
    
    /**
     * Lista todos os clientes
     */
    public function index() {
        $page = (int)$this->getGet('page', 1);
        $search = $this->getGet('search', '');
        
        $where = '';
        $params = [];
        
        // Filtro de busca
        if (!empty($search)) {
            $where = 'nome_empresa LIKE ? OR cnpj LIKE ? OR contato LIKE ?';
            $params = ["%{$search}%", "%{$search}%", "%{$search}%"];
        }
        
        $result = $this->paginate('clientes', $page, $where, $params);
        
        // Formatar CNPJ para exibição
        foreach ($result['data'] as &$cliente) {
            if (!empty($cliente['cnpj'])) {
                $cliente['cnpj_formatado'] = $this->clienteModel->formatarCnpj($cliente['cnpj']);
            }
            if (!empty($cliente['telefone'])) {
                $cliente['telefone_formatado'] = $this->clienteModel->formatarTelefone($cliente['telefone']);
            }
        }
        
        $data = [
            'title' => 'Clientes - Sistema de Monitoramento',
            'clientes' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search
        ];
        
        $this->view('clientes/index', $data);
    }
    
    /**
     * Exibe formulário de criação
     */
    public function create() {
        $data = [
            'title' => 'Novo Cliente - Sistema de Monitoramento',
            'cliente' => [],
            'errors' => []
        ];
        
        $this->view('clientes/create', $data);
    }
    
    /**
     * Salva novo cliente
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('clientes/criar');
            return;
        }
        
        $dados = $this->getPost();
        
        // Limpar CNPJ
        if (!empty($dados['cnpj'])) {
            $dados['cnpj'] = $this->clienteModel->limparCnpj($dados['cnpj']);
        }
        
        // Validar dados
        $errors = $this->clienteModel->validar($dados);
        
        if (empty($errors)) {
            $id = $this->clienteModel->create($dados);
            
            if ($id) {
                $_SESSION['success'] = 'Cliente cadastrado com sucesso!';
                $this->redirect('clientes/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar cliente.';
                $this->redirect('clientes/criar');
            }
        } else {
            $data = [
                'title' => 'Novo Cliente - Sistema de Monitoramento',
                'cliente' => $dados,
                'errors' => $errors
            ];
            
            $this->view('clientes/create', $data);
        }
    }
    
    /**
     * Exibe detalhes do cliente
     */
    public function show($id) {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            $this->redirect('clientes');
            return;
        }
        
        // Buscar atendimentos do cliente
        $atendimentos = $this->clienteModel->atendimentos($id);
        
        // Formatar dados para exibição
        if (!empty($cliente['cnpj'])) {
            $cliente['cnpj_formatado'] = $this->clienteModel->formatarCnpj($cliente['cnpj']);
        }
        if (!empty($cliente['telefone'])) {
            $cliente['telefone_formatado'] = $this->clienteModel->formatarTelefone($cliente['telefone']);
        }
        
        $data = [
            'title' => 'Cliente: ' . $cliente['nome_empresa'],
            'cliente' => $cliente,
            'atendimentos' => $atendimentos
        ];
        
        $this->view('clientes/show', $data);
    }
    
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            $this->redirect('clientes');
            return;
        }
        
        // Formatar CNPJ para edição
        if (!empty($cliente['cnpj'])) {
            $cliente['cnpj'] = $this->clienteModel->formatarCnpj($cliente['cnpj']);
        }
        
        $data = [
            'title' => 'Editar Cliente: ' . $cliente['nome_empresa'],
            'cliente' => $cliente,
            'errors' => []
        ];
        
        $this->view('clientes/edit', $data);
    }
    
    /**
     * Atualiza cliente
     */
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('clientes/' . $id . '/editar');
            return;
        }
        
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            $this->redirect('clientes');
            return;
        }
        
        $dados = $this->getPost();
        
        // Limpar CNPJ
        if (!empty($dados['cnpj'])) {
            $dados['cnpj'] = $this->clienteModel->limparCnpj($dados['cnpj']);
        }
        
        // Validar dados
        $errors = $this->clienteModel->validar($dados, $id);
        
        if (empty($errors)) {
            if ($this->clienteModel->update($id, $dados)) {
                $_SESSION['success'] = 'Cliente atualizado com sucesso!';
                $this->redirect('clientes/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao atualizar cliente.';
                $this->redirect('clientes/' . $id . '/editar');
            }
        } else {
            $data = [
                'title' => 'Editar Cliente: ' . $cliente['nome_empresa'],
                'cliente' => array_merge($cliente, $dados),
                'errors' => $errors
            ];
            
            $this->view('clientes/edit', $data);
        }
    }
    
    /**
     * Exclui cliente
     */
    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('clientes');
            return;
        }
        
        $cliente = $this->clienteModel->find($id);
        
        if (!$cliente) {
            $_SESSION['error'] = 'Cliente não encontrado.';
            $this->redirect('clientes');
            return;
        }
        
        // Verificar se há atendimentos vinculados
        $atendimentos = $this->clienteModel->atendimentos($id);
        
        if (!empty($atendimentos)) {
            $_SESSION['error'] = 'Não é possível excluir cliente com atendimentos vinculados.';
            $this->redirect('clientes/' . $id);
            return;
        }
        
        if ($this->clienteModel->delete($id)) {
            $_SESSION['success'] = 'Cliente excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir cliente.';
        }
        
        $this->redirect('clientes');
    }
    
    /**
     * API para buscar clientes (AJAX)
     */
    public function api() {
        if (!$this->isAjax()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $action = $this->getGet('action');
        
        switch ($action) {
            case 'search':
                $termo = $this->getGet('q', '');
                $clientes = $this->clienteModel->pesquisar($termo);
                
                $resultado = array_map(function($c) {
                    return [
                        'id' => $c['id_cliente'],
                        'text' => $c['nome_empresa'] . ' - ' . $this->clienteModel->formatarCnpj($c['cnpj'])
                    ];
                }, $clientes);
                
                $this->json(['results' => $resultado]);
                break;
                
            case 'stats':
                $stats = $this->clienteModel->estatisticas();
                $this->json(['success' => true, 'data' => $stats]);
                break;
                
            default:
                $this->json(['error' => 'Ação não encontrada'], 404);
        }
    }
    
    /**
     * Validação de CNPJ via AJAX
     */
    public function validarCnpj() {
        if (!$this->isAjax() || !$this->isPost()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $cnpj = $this->getPost('cnpj');
        $id = $this->getPost('id');
        
        if (empty($cnpj)) {
            $this->json(['valid' => false, 'message' => 'CNPJ é obrigatório']);
            return;
        }
        
        $cnpjLimpo = $this->clienteModel->limparCnpj($cnpj);
        
        // Validar formato
        if (!$this->clienteModel->validarCnpj($cnpjLimpo)) {
            $this->json(['valid' => false, 'message' => 'CNPJ inválido']);
            return;
        }
        
        // Verificar se já existe
        $existente = $this->clienteModel->porCnpj($cnpjLimpo);
        
        if ($existente && (!$id || $existente['id_cliente'] != $id)) {
            $this->json(['valid' => false, 'message' => 'CNPJ já cadastrado']);
            return;
        }
        
        $this->json(['valid' => true]);
    }
    
    /**
     * Busca CEP via API externa (AJAX)
     */
    public function buscarCep() {
        if (!$this->isAjax()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $cep = $this->getGet('cep');
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) != 8) {
            $this->json(['error' => 'CEP inválido'], 400);
            return;
        }
        
        // Buscar CEP via ViaCEP
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $response = @file_get_contents($url);
        
        if ($response === false) {
            $this->json(['error' => 'Erro ao consultar CEP'], 500);
            return;
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['erro'])) {
            $this->json(['error' => 'CEP não encontrado'], 404);
            return;
        }
        
        $this->json([
            'success' => true,
            'endereco' => $data['logradouro'],
            'bairro' => $data['bairro'],
            'cidade' => $data['localidade'],
            'estado' => $data['uf']
        ]);
    }
}
?>

