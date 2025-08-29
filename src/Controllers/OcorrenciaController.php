<?php
/**
 * Controller de Ocorrências Veiculares
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/OcorrenciaVeicular.php';

class OcorrenciaController extends Controller {
    private $ocorrenciaModel;
    
    public function __construct() {
        parent::__construct();
        $this->ocorrenciaModel = new OcorrenciaVeicular();
    }
    
    public function index() {
        $ocorrencias = $this->ocorrenciaModel->todas();
        
        $data = [
            'title' => 'Ocorrências Veiculares - Sistema de Monitoramento',
            'ocorrencias' => $ocorrencias
        ];
        
        $this->view('ocorrencias/index', $data);
    }
    
    public function create() {
        $data = [
            'title' => 'Nova Ocorrência - Sistema de Monitoramento',
            'ocorrencia' => [],
            'errors' => []
        ];
        
        $this->view('ocorrencias/create', $data);
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('ocorrencias/criar');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->ocorrenciaModel->validar($dados);
        
        if (empty($errors)) {
            $id = $this->ocorrenciaModel->create($dados);
            
            if ($id) {
                $_SESSION['success'] = 'Ocorrência cadastrada com sucesso!';
                $this->redirect('ocorrencias/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar ocorrência.';
                $this->redirect('ocorrencias/criar');
            }
        } else {
            $data = [
                'title' => 'Nova Ocorrência - Sistema de Monitoramento',
                'ocorrencia' => $dados,
                'errors' => $errors
            ];
            
            $this->view('ocorrencias/create', $data);
        }
    }
    
    public function show($id) {
        $ocorrencia = $this->ocorrenciaModel->find($id);
        
        if (!$ocorrencia) {
            $_SESSION['error'] = 'Ocorrência não encontrada.';
            $this->redirect('ocorrencias');
            return;
        }
        
        $data = [
            'title' => 'Ocorrência #' . $ocorrencia['id'],
            'ocorrencia' => $ocorrencia
        ];
        
        $this->view('ocorrencias/show', $data);
    }
    
    public function edit($id) {
        $ocorrencia = $this->ocorrenciaModel->find($id);
        
        if (!$ocorrencia) {
            $_SESSION['error'] = 'Ocorrência não encontrada.';
            $this->redirect('ocorrencias');
            return;
        }
        
        $data = [
            'title' => 'Editar Ocorrência #' . $ocorrencia['id'],
            'ocorrencia' => $ocorrencia,
            'errors' => []
        ];
        
        $this->view('ocorrencias/edit', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('ocorrencias/' . $id . '/editar');
            return;
        }
        
        $ocorrencia = $this->ocorrenciaModel->find($id);
        
        if (!$ocorrencia) {
            $_SESSION['error'] = 'Ocorrência não encontrada.';
            $this->redirect('ocorrencias');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->ocorrenciaModel->validar($dados, $id);
        
        if (empty($errors)) {
            if ($this->ocorrenciaModel->update($id, $dados)) {
                $_SESSION['success'] = 'Ocorrência atualizada com sucesso!';
                $this->redirect('ocorrencias/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao atualizar ocorrência.';
                $this->redirect('ocorrencias/' . $id . '/editar');
            }
        } else {
            $data = [
                'title' => 'Editar Ocorrência #' . $ocorrencia['id'],
                'ocorrencia' => array_merge($ocorrencia, $dados),
                'errors' => $errors
            ];
            
            $this->view('ocorrencias/edit', $data);
        }
    }
    
    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('ocorrencias');
            return;
        }
        
        if ($this->ocorrenciaModel->delete($id)) {
            $_SESSION['success'] = 'Ocorrência excluída com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir ocorrência.';
        }
        
        $this->redirect('ocorrencias');
    }
}
?>

