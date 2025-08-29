<?php
/**
 * Controller de Atendimentos
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Atendimento.php';
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Agente.php';

class AtendimentoController extends Controller {
    private $atendimentoModel;
    private $clienteModel;
    private $agenteModel;
    
    public function __construct() {
        parent::__construct();
        $this->atendimentoModel = new Atendimento();
        $this->clienteModel = new Cliente();
        $this->agenteModel = new Agente();
    }
    
    public function index() {
        $page = (int)$this->getGet('page', 1);
        $search = $this->getGet('search', '');
        $status = $this->getGet('status', '');
        $tipo = $this->getGet('tipo', '');
        
        $where = '';
        $params = [];
        
        if (!empty($search)) {
            $where .= ($where ? ' AND ' : '') . '(a.solicitante LIKE ? OR a.motivo LIKE ? OR a.endereco LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($status)) {
            $where .= ($where ? ' AND ' : '') . 'a.status_atendimento = ?';
            $params[] = $status;
        }
        
        if (!empty($tipo)) {
            $where .= ($where ? ' AND ' : '') . 'a.tipo_de_servico = ?';
            $params[] = $tipo;
        }
        
        $query = "
            SELECT 
                a.*,
                c.nome_empresa as cliente_nome,
                ag.nome as agente_nome
            FROM atendimentos a
            LEFT JOIN clientes c ON a.id_cliente = c.id_cliente
            LEFT JOIN agentes ag ON a.id_agente = ag.id_agente
        ";
        
        if ($where) {
            $query .= " WHERE {$where}";
        }
        
        $query .= " ORDER BY a.created_at DESC";
        
        $atendimentos = $this->db->fetchAll($query, $params);
        
        $data = [
            'title' => 'Atendimentos - Sistema de Monitoramento',
            'atendimentos' => $atendimentos,
            'search' => $search,
            'status' => $status,
            'tipo' => $tipo
        ];
        
        $this->view('atendimentos/index', $data);
    }
    
    public function create() {
        $clientes = $this->clienteModel->todos();
        $agentes = $this->agenteModel->ativos();
        
        $data = [
            'title' => 'Novo Atendimento - Sistema de Monitoramento',
            'atendimento' => [],
            'errors' => [],
            'clientes' => $clientes,
            'agentes' => $agentes
        ];
        
        $this->view('atendimentos/create', $data);
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('atendimentos/criar');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->atendimentoModel->validar($dados);
        
        if (empty($errors)) {
            $id = $this->atendimentoModel->create($dados);
            
            if ($id) {
                $_SESSION['success'] = 'Atendimento cadastrado com sucesso!';
                $this->redirect('atendimentos/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar atendimento.';
                $this->redirect('atendimentos/criar');
            }
        } else {
            $clientes = $this->clienteModel->todos();
            $agentes = $this->agenteModel->ativos();
            
            $data = [
                'title' => 'Novo Atendimento - Sistema de Monitoramento',
                'atendimento' => $dados,
                'errors' => $errors,
                'clientes' => $clientes,
                'agentes' => $agentes
            ];
            
            $this->view('atendimentos/create', $data);
        }
    }
    
    public function show($id) {
        $atendimento = $this->atendimentoModel->comRelacionamentos($id);
        
        if (!$atendimento) {
            $_SESSION['error'] = 'Atendimento não encontrado.';
            $this->redirect('atendimentos');
            return;
        }
        
        $fotos = $this->atendimentoModel->fotos($id);
        
        $data = [
            'title' => 'Atendimento #' . $atendimento['id_atendimento'],
            'atendimento' => $atendimento,
            'fotos' => $fotos
        ];
        
        $this->view('atendimentos/show', $data);
    }
    
    public function edit($id) {
        $atendimento = $this->atendimentoModel->find($id);
        
        if (!$atendimento) {
            $_SESSION['error'] = 'Atendimento não encontrado.';
            $this->redirect('atendimentos');
            return;
        }
        
        $clientes = $this->clienteModel->todos();
        $agentes = $this->agenteModel->ativos();
        
        $data = [
            'title' => 'Editar Atendimento #' . $atendimento['id_atendimento'],
            'atendimento' => $atendimento,
            'errors' => [],
            'clientes' => $clientes,
            'agentes' => $agentes
        ];
        
        $this->view('atendimentos/edit', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('atendimentos/' . $id . '/editar');
            return;
        }
        
        $atendimento = $this->atendimentoModel->find($id);
        
        if (!$atendimento) {
            $_SESSION['error'] = 'Atendimento não encontrado.';
            $this->redirect('atendimentos');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->atendimentoModel->validar($dados, $id);
        
        if (empty($errors)) {
            if ($this->atendimentoModel->update($id, $dados)) {
                $_SESSION['success'] = 'Atendimento atualizado com sucesso!';
                $this->redirect('atendimentos/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao atualizar atendimento.';
                $this->redirect('atendimentos/' . $id . '/editar');
            }
        } else {
            $clientes = $this->clienteModel->todos();
            $agentes = $this->agenteModel->ativos();
            
            $data = [
                'title' => 'Editar Atendimento #' . $atendimento['id_atendimento'],
                'atendimento' => array_merge($atendimento, $dados),
                'errors' => $errors,
                'clientes' => $clientes,
                'agentes' => $agentes
            ];
            
            $this->view('atendimentos/edit', $data);
        }
    }
    
    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('atendimentos');
            return;
        }
        
        $atendimento = $this->atendimentoModel->find($id);
        
        if (!$atendimento) {
            $_SESSION['error'] = 'Atendimento não encontrado.';
            $this->redirect('atendimentos');
            return;
        }
        
        if ($this->atendimentoModel->delete($id)) {
            $_SESSION['success'] = 'Atendimento excluído com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir atendimento.';
        }
        
        $this->redirect('atendimentos');
    }
    
    public function finalizar($id) {
        if (!$this->isPost()) {
            $this->redirect('atendimentos/' . $id);
            return;
        }
        
        if ($this->atendimentoModel->finalizar($id)) {
            $_SESSION['success'] = 'Atendimento finalizado com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao finalizar atendimento.';
        }
        
        $this->redirect('atendimentos/' . $id);
    }
}
?>

