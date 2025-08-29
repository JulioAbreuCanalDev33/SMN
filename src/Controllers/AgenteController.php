<?php
/**
 * Controller de Agentes
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Agente.php';

class AgenteController extends Controller {
    private $agenteModel;
    
    public function __construct() {
        parent::__construct();
        $this->agenteModel = new Agente();
    }
    
    /**
     * Lista todos os agentes
     */
    public function index() {
        $page = (int)$this->getGet('page', 1);
        $search = $this->getGet('search', '');
        $status = $this->getGet('status', '');
        $funcao = $this->getGet('funcao', '');
        
        $where = '';
        $params = [];
        
        // Filtros
        if (!empty($search)) {
            $where .= ($where ? ' AND ' : '') . '(nome LIKE ? OR funcao LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($status)) {
            $where .= ($where ? ' AND ' : '') . 'status = ?';
            $params[] = $status;
        }
        
        if (!empty($funcao)) {
            $where .= ($where ? ' AND ' : '') . 'funcao = ?';
            $params[] = $funcao;
        }
        
        $result = $this->paginate('agentes', $page, $where, $params);
        
        // Buscar funções únicas para filtro
        $funcoes = $this->agenteModel->funcoes();
        
        $data = [
            'title' => 'Agentes - Sistema de Monitoramento',
            'agentes' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'status' => $status,
            'funcao' => $funcao,
            'funcoes' => $funcoes
        ];
        
        $this->view('agentes/index', $data);
    }
    
    /**
     * Exibe formulário de criação
     */
    public function create() {
        $funcoes = $this->agenteModel->funcoes();
        
        $data = [
            'title' => 'Novo Agente - Sistema de Monitoramento',
            'agente' => [],
            'errors' => [],
            'funcoes' => $funcoes
        ];
        
        $this->view('agentes/create', $data);
    }
    
    /**
     * Salva novo agente
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('agentes/criar');
            return;
        }
        
        $dados = $this->getPost();
        
        // Validar dados
        $errors = $this->agenteModel->validar($dados);
        
        if (empty($errors)) {
            $id = $this->agenteModel->create($dados);
            
            if ($id) {
                $_SESSION['success'] = 'Agente cadastrado com sucesso!';
                $this->redirect('agentes/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar agente.';
                $this->redirect('agentes/criar');
            }
        } else {
            $funcoes = $this->agenteModel->funcoes();
            
            $data = [
                'title' => 'Novo Agente - Sistema de Monitoramento',
                'agente' => $dados,
                'errors' => $errors,
                'funcoes' => $funcoes
            ];
            
            $this->view('agentes/create', $data);
        }
    }
    
    /**
     * Exibe detalhes do agente
     */
    public function show($id) {
        $agente = $this->agenteModel->find($id);
        
        if (!$agente) {
            $_SESSION['error'] = 'Agente não encontrado.';
            $this->redirect('agentes');
            return;
        }
        
        // Buscar atendimentos do agente
        $atendimentos = $this->agenteModel->atendimentos($id);
        
        $data = [
            'title' => 'Agente: ' . $agente['nome'],
            'agente' => $agente,
            'atendimentos' => $atendimentos
        ];
        
        $this->view('agentes/show', $data);
    }
    
    /**
     * Exibe formulário de edição
     */
    public function edit($id) {
        $agente = $this->agenteModel->find($id);
        
        if (!$agente) {
            $_SESSION['error'] = 'Agente não encontrado.';
            $this->redirect('agentes');
            return;
        }
        
        $funcoes = $this->agenteModel->funcoes();
        
        $data = [
            'title' => 'Editar Agente: ' . $agente['nome'],
            'agente' => $agente,
            'errors' => [],
            'funcoes' => $funcoes
        ];
        
        $this->view('agentes/edit', $data);
    }
    
    /**
     * Atualiza agente
     */
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('agentes/' . $id . '/editar');
            return;
        }
        
        $agente = $this->agenteModel->find($id);
        
        if (!$agente) {
            $_SESSION['error'] = 'Agente não encontrado.';
            $this->redirect('agentes');
            return;
        }
        
        $dados = $this->getPost();
        
        // Validar dados
        $errors = $this->agenteModel->validar($dados, $id);
        
        if (empty($errors)) {
            if ($this->agenteModel->update($id, $dados)) {
                $_SESSION['success'] = 'Agente atualizado com sucesso!';
                $this->redirect('agentes/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao atualizar agente.';
                $this->redirect('agentes/' . $id . '/editar');
            }
        } else {
            $funcoes = $this->agenteModel->funcoes();
            
            $data = [
                'title' => 'Editar Agente: ' . $agente['nome'],
                'agente' => array_merge($agente, $dados),
                'errors' => $errors,
                'funcoes' => $funcoes
            ];
            
            $this->view('agentes/edit', $data);
        }
    }
    
    /**
     * Exclui agente
     */
    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('agentes');
            return;
        }
        
        $agente = $this->agenteModel->find($id);
        
        if (!$agente) {
            $_SESSION['error'] = 'Agente não encontrado.';
            $this->redirect('agentes');
            return;
        }
        
        // Verificar se há atendimentos vinculados
        $atendimentos = $this->agenteModel->atendimentos($id);
        
        if (!empty($atendimentos)) {
            $_SESSION['error'] = 'Não é possível excluir agente com atendimentos vinculados.';
            $this->redirect('agentes/' . $id);
            return;
        }
        
        if ($this->agenteModel->delete($id)) {
            $_SESSION['success'] = 'Agente excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir agente.';
        }
        
        $this->redirect('agentes');
    }
    
    /**
     * Ativa/Inativa agente via AJAX
     */
    public function toggleStatus($id) {
        if (!$this->isAjax() || !$this->isPost()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $agente = $this->agenteModel->find($id);
        
        if (!$agente) {
            $this->json(['error' => 'Agente não encontrado'], 404);
            return;
        }
        
        $novoStatus = $agente['status'] === 'Ativo' ? 'Inativo' : 'Ativo';
        
        if ($this->agenteModel->update($id, ['status' => $novoStatus])) {
            $this->json([
                'success' => true,
                'status' => $novoStatus,
                'message' => "Agente {$novoStatus} com sucesso!"
            ]);
        } else {
            $this->json(['error' => 'Erro ao alterar status'], 500);
        }
    }
    
    /**
     * API para buscar agentes (AJAX)
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
                $agentes = $this->agenteModel->pesquisar($termo);
                
                $resultado = array_map(function($a) {
                    return [
                        'id' => $a['id_agente'],
                        'text' => $a['nome'] . ' - ' . $a['funcao']
                    ];
                }, $agentes);
                
                $this->json(['results' => $resultado]);
                break;
                
            case 'ativos':
                $agentes = $this->agenteModel->ativos();
                
                $resultado = array_map(function($a) {
                    return [
                        'id' => $a['id_agente'],
                        'text' => $a['nome'] . ' - ' . $a['funcao']
                    ];
                }, $agentes);
                
                $this->json(['results' => $resultado]);
                break;
                
            case 'stats':
                $stats = $this->agenteModel->estatisticas();
                $this->json(['success' => true, 'data' => $stats]);
                break;
                
            case 'performance':
                $dataInicio = $this->getGet('data_inicio');
                $dataFim = $this->getGet('data_fim');
                
                $performance = $this->agenteModel->relatorioPerformance($dataInicio, $dataFim);
                $this->json(['success' => true, 'data' => $performance]);
                break;
                
            default:
                $this->json(['error' => 'Ação não encontrada'], 404);
        }
    }
    
    /**
     * Relatório de performance dos agentes
     */
    public function relatorioPerformance() {
        $dataInicio = $this->getGet('data_inicio', date('Y-m-01'));
        $dataFim = $this->getGet('data_fim', date('Y-m-d'));
        
        $performance = $this->agenteModel->relatorioPerformance($dataInicio, $dataFim);
        
        $data = [
            'title' => 'Relatório de Performance - Agentes',
            'performance' => $performance,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim
        ];
        
        $this->view('agentes/performance', $data);
    }
}
?>

