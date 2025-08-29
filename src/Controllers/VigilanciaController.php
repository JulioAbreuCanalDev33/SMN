<?php
/**
 * Controller de Vigilância Veicular
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/VigilanciaVeicular.php';

class VigilanciaController extends Controller {
    private $vigilanciaModel;
    
    public function __construct() {
        parent::__construct();
        $this->vigilanciaModel = new VigilanciaVeicular();
    }
    
    public function index() {
        $vigilancias = $this->vigilanciaModel->todas();
        
        $data = [
            'title' => 'Vigilância Veicular - Sistema de Monitoramento',
            'vigilancias' => $vigilancias
        ];
        
        $this->view('vigilancia/index', $data);
    }
    
    public function create() {
        $data = [
            'title' => 'Nova Vigilância - Sistema de Monitoramento',
            'vigilancia' => [],
            'errors' => []
        ];
        
        $this->view('vigilancia/create', $data);
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('vigilancia/criar');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->vigilanciaModel->validar($dados);
        
        if (empty($errors)) {
            $id = $this->vigilanciaModel->create($dados);
            
            if ($id) {
                $_SESSION['success'] = 'Vigilância cadastrada com sucesso!';
                $this->redirect('vigilancia/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar vigilância.';
                $this->redirect('vigilancia/criar');
            }
        } else {
            $data = [
                'title' => 'Nova Vigilância - Sistema de Monitoramento',
                'vigilancia' => $dados,
                'errors' => $errors
            ];
            
            $this->view('vigilancia/create', $data);
        }
    }
    
    public function show($id) {
        $vigilancia = $this->vigilanciaModel->find($id);
        
        if (!$vigilancia) {
            $_SESSION['error'] = 'Vigilância não encontrada.';
            $this->redirect('vigilancia');
            return;
        }
        
        $fotos = $this->vigilanciaModel->fotos($id);
        
        $data = [
            'title' => 'Vigilância #' . $vigilancia['id'],
            'vigilancia' => $vigilancia,
            'fotos' => $fotos
        ];
        
        $this->view('vigilancia/show', $data);
    }
    
    public function edit($id) {
        $vigilancia = $this->vigilanciaModel->find($id);
        
        if (!$vigilancia) {
            $_SESSION['error'] = 'Vigilância não encontrada.';
            $this->redirect('vigilancia');
            return;
        }
        
        $data = [
            'title' => 'Editar Vigilância #' . $vigilancia['id'],
            'vigilancia' => $vigilancia,
            'errors' => []
        ];
        
        $this->view('vigilancia/edit', $data);
    }
    
    public function update($id) {
        if (!$this->isPost()) {
            $this->redirect('vigilancia/' . $id . '/editar');
            return;
        }
        
        $vigilancia = $this->vigilanciaModel->find($id);
        
        if (!$vigilancia) {
            $_SESSION['error'] = 'Vigilância não encontrada.';
            $this->redirect('vigilancia');
            return;
        }
        
        $dados = $this->getPost();
        $errors = $this->vigilanciaModel->validar($dados, $id);
        
        if (empty($errors)) {
            if ($this->vigilanciaModel->update($id, $dados)) {
                $_SESSION['success'] = 'Vigilância atualizada com sucesso!';
                $this->redirect('vigilancia/' . $id);
            } else {
                $_SESSION['error'] = 'Erro ao atualizar vigilância.';
                $this->redirect('vigilancia/' . $id . '/editar');
            }
        } else {
            $data = [
                'title' => 'Editar Vigilância #' . $vigilancia['id'],
                'vigilancia' => array_merge($vigilancia, $dados),
                'errors' => $errors
            ];
            
            $this->view('vigilancia/edit', $data);
        }
    }
    
    public function delete($id) {
        if (!$this->isPost()) {
            $this->redirect('vigilancia');
            return;
        }
        
        if ($this->vigilanciaModel->delete($id)) {
            $_SESSION['success'] = 'Vigilância excluída com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao excluir vigilância.';
        }
        
        $this->redirect('vigilancia');
    }
    
    public function finalizar($id) {
        if (!$this->isPost()) {
            $this->redirect('vigilancia/' . $id);
            return;
        }
        
        if ($this->vigilanciaModel->finalizar($id)) {
            $_SESSION['success'] = 'Vigilância finalizada com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao finalizar vigilância.';
        }
        
        $this->redirect('vigilancia/' . $id);
    }
}
?>

