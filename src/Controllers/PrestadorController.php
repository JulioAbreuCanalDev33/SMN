<?php
/**
 * Controller de Prestadores
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Prestador.php';

class PrestadorController extends Controller {
    private $prestadorModel;
    
    public function __construct() {
        parent::__construct();
        $this->prestadorModel = new Prestador();
    }
    
    /**
     * Lista todos os prestadores
     */
    public function index() {
        $page = (int)$this->getGet('page', 1);
        $search = $this->getGet('search', '');
        $servico = $this->getGet('servico', '');
        $estado = $this->getGet('estado', '');
        
        $where = '';
        $params = [];
        
        // Filtros
        if (!empty($search)) {
            $where .= ($where ? ' AND ' : '') . 
                     '(nome_prestador LIKE ? OR email_prestador LIKE ? OR cpf_prestador LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($servico)) {
            $where .= ($where ? ' AND ' : '') . 'servico_prestador LIKE ?';
            $params[] = "%{$servico}%";
        }
        
        if (!empty($estado)) {
            $where .= ($where ? ' AND ' : '') . 'estado = ?';
            $params[] = $estado;
        }
        
        $result = $this->paginate('tabela_prestadores', $page, $where, $params);
        
        // Buscar serviços únicos para filtro
        $servicos = $this->db->fetchAll("
            SELECT DISTINCT servico_prestador 
            FROM tabela_prestadores 
            WHERE servico_prestador IS NOT NULL AND servico_prestador != ''
            ORDER BY servico_prestador
        ");
        
        // Buscar estados únicos para filtro
        $estados = $this->db->fetchAll("
            SELECT DISTINCT estado 
            FROM tabela_prestadores 
            WHERE estado IS NOT NULL AND estado != ''
            ORDER BY estado
        ");
        
        $data = [
            'title' => 'Prestadores - Sistema de Monitoramento',
            'prestadores' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'servico' => $servico,
            'estado' => $estado,
            'servicos' => array_column($servicos, 'servico_prestador'),
            'estados' => array_column($estados, 'estado')
        ];
        
        $this->view('prestadores/index', $data);
    }
    
    /**
     * Exibe formulário de criação
     */
    public function create() {
        $data = [
            'title' => 'Novo Prestador - Sistema de Monitoramento',
            'prestador' => [],
            'errors' => []
        ];
        
        $this->view('prestadores/create', $data);
    }
    
    /**
     * Salva novo prestador
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('prestadores/criar');
            return;
        }
        
        $dados = $this->getPost();
        
        // Limpar CPF
        if (!empty($dados['cpf_prestador'])) {
            $dados['cpf_prestador'] = $this->prestadorModel->limparCpf($dados['cpf_prestador']);
        }
        
        // Validar dados
        $errors = $this->prestadorModel->validar($dados);
        
        if (empty($errors)) {
            $id = $this->prestadorModel->create($dados);
            
            if ($id) {
                $_SESSION['success'] = 'Prestador cadastrado com sucesso!';
                $this->redirect('prestadores/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar prestador.';
                $this->redirect('prestadores/criar');
            }
        } else {
            $data = [
                'title' => 'Novo Prestador - Sistema de Monitoramento',
                'prestador' => $dados,
                'errors' => $errors
            ];
            
            $this->view('prestadores/create', $data);
        }
    }
    
    /**
     * Exibe detalhes do prestador
     */
    public function show($id) {
        $prestador = $this->prestadorModel->find($id);
        
        if (!$prestador) {
            $_SESSION['error'] = 'Prestador não encontrado.';
            $this->redirect('prestadores');
            return;
        }
        
        $data = [
            'title' => 'Prestador: ' . $prestador['nome_prestador'],
            'prestador' => $prestador
        ];
        
        $this->view('prestadores/show', $data);
    }
    
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $prestador = $this->prestadorModel->find($id);
        
        if (!$prestador) {
            $_SESSION['error'] = 'Prestador não encontrado.';
            $this->redirect('prestadores');
            return;
        }
        
        $data = [
            'title' => 'Editar Prestador: ' . $prestador['nome_prestador'],
            'prestador' => $prestador,
            'errors' => []
        ];
        
        $this->view('prestadores/edit', $data);
    }
    
    /**
     * Atualiza prestador
     */
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('prestadores/' . $id . '/editar');
            return;
        }
        
        $prestador = $this->prestadorModel->find($id);
        
        if (!$prestador) {
            $_SESSION['error'] = 'Prestador não encontrado.';
            $this->redirect('prestadores');
            return;
        }
        
        $dados = $this->getPost();
        
        // Limpar CPF
        if (!empty($dados['cpf_prestador'])) {
            $dados['cpf_prestador'] = $this->prestadorModel->limparCpf($dados['cpf_prestador']);
        }
        
        // Validar dados
        $errors = $this->prestadorModel->validar($dados, $id);
        
        if (empty($errors)) {
            if ($this->prestadorModel->update($id, $dados)) {
                $_SESSION['success'] = 'Prestador atualizado com sucesso!';
                $this->redirect('prestadores/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao atualizar prestador.';
                $this->redirect('prestadores/' . $id . '/editar');
            }
        } else {
            $data = [
                'title' => 'Editar Prestador: ' . $prestador['nome_prestador'],
                'prestador' => array_merge($prestador, $dados),
                'errors' => $errors
            ];
            
            $this->view('prestadores/edit', $data);
        }
    }
    
    /**
     * Exclui prestador
     */
    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('prestadores');
            return;
        }
        
        $prestador = $this->prestadorModel->find($id);
        
        if (!$prestador) {
            $_SESSION['error'] = 'Prestador não encontrado.';
            $this->redirect('prestadores');
            return;
        }
        
        // Verificar se há dependências (implementar se necessário)
        
        if ($this->prestadorModel->delete($id)) {
            $_SESSION['success'] = 'Prestador excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir prestador.';
        }
        
        $this->redirect('prestadores');
    }
    
    /**
     * API para buscar prestadores (AJAX)
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
                $prestadores = $this->prestadorModel->pesquisar($termo);
                
                $resultado = array_map(function($p) {
                    return [
                        'id' => $p['id'],
                        'text' => $p['nome_prestador'] . ' - ' . $p['servico_prestador']
                    ];
                }, $prestadores);
                
                $this->json(['results' => $resultado]);
                break;
                
            case 'stats':
                $stats = $this->prestadorModel->estatisticas();
                $this->json(['success' => true, 'data' => $stats]);
                break;
                
            default:
                $this->json(['error' => 'Ação não encontrada'], 404);
        }
    }
    
    /**
     * Validação de CPF via AJAX
     */
    public function validarCpf() {
        if (!$this->isAjax() || !$this->isPost()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $cpf = $this->getPost('cpf');
        $id = $this->getPost('id');
        
        if (empty($cpf)) {
            $this->json(['valid' => false, 'message' => 'CPF é obrigatório']);
            return;
        }
        
        $cpfLimpo = $this->prestadorModel->limparCpf($cpf);
        
        // Verificar se já existe
        $existente = $this->prestadorModel->porCpf($cpfLimpo);
        
        if ($existente && (!$id || $existente['id'] != $id)) {
            $this->json(['valid' => false, 'message' => 'CPF já cadastrado']);
            return;
        }
        
        $this->json(['valid' => true]);
    }
    
    /**
     * Validação de email via AJAX
     */
    public function validarEmail() {
        if (!$this->isAjax() || !$this->isPost()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $email = $this->getPost('email');
        $id = $this->getPost('id');
        
        if (empty($email)) {
            $this->json(['valid' => false, 'message' => 'Email é obrigatório']);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['valid' => false, 'message' => 'Email inválido']);
            return;
        }
        
        // Verificar se já existe
        $existente = $this->prestadorModel->porEmail($email);
        
        if ($existente && (!$id || $existente['id'] != $id)) {
            $this->json(['valid' => false, 'message' => 'Email já cadastrado']);
            return;
        }
        
        $this->json(['valid' => true]);
    }
}
?>

