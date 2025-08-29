<?php
/**
 * Controller de Rondas Periódicas
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/RondaPeriodica.php';
require_once __DIR__ . '/../Models/Atendimento.php';

class RondaController extends Controller {
    private $rondaModel;
    private $atendimentoModel;
    
    public function __construct() {
        parent::__construct();
        $this->rondaModel = new RondaPeriodica();
        $this->atendimentoModel = new Atendimento();
    }
    
    public function index() {
        $rondas = $this->rondaModel->todasComAtendimento();
        
        $data = [
            'title' => 'Rondas Periódicas - Sistema de Monitoramento',
            'rondas' => $rondas
        ];
        
        $this->view('rondas/index', $data);
    }
    
    public function create() {
        $atendimentos = $this->atendimentoModel->todosComRelacionamentos();
        
        $data = [
            'title' => 'Nova Ronda - Sistema de Monitoramento',
            'ronda' => [],
            'errors' => [],
            'atendimentos' => $atendimentos
        ];
        
        $this->view('rondas/create', $data);
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('rondas/criar');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->rondaModel->validar($dados);
        
        if (empty($errors)) {
            $id = $this->rondaModel->create($dados);
            
            if ($id) {
                $_SESSION['success'] = 'Ronda cadastrada com sucesso!';
                $this->redirect('rondas/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar ronda.';
                $this->redirect('rondas/criar');
            }
        } else {
            $atendimentos = $this->atendimentoModel->todosComRelacionamentos();
            
            $data = [
                'title' => 'Nova Ronda - Sistema de Monitoramento',
                'ronda' => $dados,
                'errors' => $errors,
                'atendimentos' => $atendimentos
            ];
            
            $this->view('rondas/create', $data);
        }
    }
    
    public function show($id) {
        $ronda = $this->rondaModel->comAtendimento($id);
        
        if (!$ronda) {
            $_SESSION['error'] = 'Ronda não encontrada.';
            $this->redirect('rondas');
            return;
        }
        
        $data = [
            'title' => 'Ronda #' . $ronda['id_ronda'],
            'ronda' => $ronda
        ];
        
        $this->view('rondas/show', $data);
    }
    
    public function edit($id) {
        $ronda = $this->rondaModel->find($id);
        
        if (!$ronda) {
            $_SESSION['error'] = 'Ronda não encontrada.';
            $this->redirect('rondas');
            return;
        }
        
        $atendimentos = $this->atendimentoModel->todosComRelacionamentos();
        
        $data = [
            'title' => 'Editar Ronda #' . $ronda['id_ronda'],
            'ronda' => $ronda,
            'errors' => [],
            'atendimentos' => $atendimentos
        ];
        
        $this->view('rondas/edit', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('rondas/' . $id . '/editar');
            return;
        }
        
        $ronda = $this->rondaModel->find($id);
        
        if (!$ronda) {
            $_SESSION['error'] = 'Ronda não encontrada.';
            $this->redirect('rondas');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->rondaModel->validar($dados, $id);
        
        if (empty($errors)) {
            if ($this->rondaModel->update($id, $dados)) {
                $_SESSION['success'] = 'Ronda atualizada com sucesso!';
                $this->redirect('rondas/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao atualizar ronda.';
                $this->redirect('rondas/' . $id . '/editar');
            }
        } else {
            $atendimentos = $this->atendimentoModel->todosComRelacionamentos();
            
            $data = [
                'title' => 'Editar Ronda #' . $ronda['id_ronda'],
                'ronda' => array_merge($ronda, $dados),
                'errors' => $errors,
                'atendimentos' => $atendimentos
            ];
            
            $this->view('rondas/edit', $data);
        }
    }
    
    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('rondas');
            return;
        }
        
        if ($this->rondaModel->delete($id)) {
            $_SESSION['success'] = 'Ronda excluída com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir ronda.';
        }
        
        $this->redirect('rondas');
    }
}
?>

