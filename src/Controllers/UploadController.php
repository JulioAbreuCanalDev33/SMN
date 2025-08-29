<?php
/**
 * Controller de Upload de Fotos
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Atendimento.php';
require_once __DIR__ . '/../Models/VigilanciaVeicular.php';

class UploadController extends Controller {
    
    /**
     * Upload de foto para atendimento
     */
    public function atendimento($id) {
        if (!$this->isPost()) {
            $this->json(['error' => 'Método não permitido'], 405);
            return;
        }
        
        $atendimentoModel = new Atendimento();
        $atendimento = $atendimentoModel->find($id);
        
        if (!$atendimento) {
            $this->json(['error' => 'Atendimento não encontrado'], 404);
            return;
        }
        
        $resultado = $this->processarUpload('atendimentos', $id);
        
        if ($resultado['success']) {
            // Salvar no banco
            $legenda = $this->getPost('legenda', '');
            $atendimentoModel->adicionarFoto($id, $resultado['caminho'], $legenda);
            
            $this->json([
                'success' => true,
                'message' => 'Foto enviada com sucesso!',
                'foto' => [
                    'caminho' => $resultado['caminho'],
                    'url' => $resultado['url'],
                    'legenda' => $legenda
                ]
            ]);
        } else {
            $this->json(['error' => $resultado['error']], 400);
        }
    }
    
    /**
     * Upload de foto para vigilância veicular
     */
    public function vigilancia($id) {
        if (!$this->isPost()) {
            $this->json(['error' => 'Método não permitido'], 405);
            return;
        }
        
        $vigilanciaModel = new VigilanciaVeicular();
        $vigilancia = $vigilanciaModel->find($id);
        
        if (!$vigilancia) {
            $this->json(['error' => 'Vigilância não encontrada'], 404);
            return;
        }
        
        $resultado = $this->processarUpload('vigilancia', $id);
        
        if ($resultado['success']) {
            // Salvar no banco
            $legenda = $this->getPost('legenda', '');
            $vigilanciaModel->adicionarFoto($id, $resultado['caminho'], $legenda);
            
            $this->json([
                'success' => true,
                'message' => 'Foto enviada com sucesso!',
                'foto' => [
                    'caminho' => $resultado['caminho'],
                    'url' => $resultado['url'],
                    'legenda' => $legenda
                ]
            ]);
        } else {
            $this->json(['error' => $resultado['error']], 400);
        }
    }
    
    /**
     * Processa o upload da foto
     */
    private function processarUpload($tipo, $id) {
        // Verificar se foi enviado arquivo
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Nenhum arquivo foi enviado ou erro no upload'];
        }
        
        $arquivo = $_FILES['photo'];
        
        // Validar tipo de arquivo
        $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($arquivo['type'], $tiposPermitidos)) {
            return ['success' => false, 'error' => 'Tipo de arquivo não permitido. Use JPG, PNG ou GIF'];
        }
        
        // Validar tamanho (máximo 5MB)
        $tamanhoMaximo = 5 * 1024 * 1024; // 5MB
        if ($arquivo['size'] > $tamanhoMaximo) {
            return ['success' => false, 'error' => 'Arquivo muito grande. Máximo 5MB'];
        }
        
        // Gerar nome único
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $nomeArquivo = $tipo . '_' . $id . '_' . uniqid() . '.' . $extensao;
        
        // Definir diretório de destino
        $diretorioDestino = PUBLIC_PATH . 'uploads/' . $tipo . '/';
        
        // Criar diretório se não existir
        if (!is_dir($diretorioDestino)) {
            mkdir($diretorioDestino, 0755, true);
        }
        
        $caminhoCompleto = $diretorioDestino . $nomeArquivo;
        $caminhoRelativo = 'uploads/' . $tipo . '/' . $nomeArquivo;
        
        // Mover arquivo
        if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            // Redimensionar imagem se necessário
            $this->redimensionarImagem($caminhoCompleto, 1200, 800);
            
            return [
                'success' => true,
                'caminho' => $caminhoRelativo,
                'url' => ASSETS_URL . $caminhoRelativo
            ];
        } else {
            return ['success' => false, 'error' => 'Erro ao salvar arquivo'];
        }
    }
    
    /**
     * Redimensiona imagem mantendo proporção
     */
    private function redimensionarImagem($caminho, $larguraMax, $alturaMax) {
        $info = getimagesize($caminho);
        
        if (!$info) {
            return false;
        }
        
        $larguraOriginal = $info[0];
        $alturaOriginal = $info[1];
        $tipo = $info[2];
        
        // Verificar se precisa redimensionar
        if ($larguraOriginal <= $larguraMax && $alturaOriginal <= $alturaMax) {
            return true;
        }
        
        // Calcular novas dimensões mantendo proporção
        $proporcao = min($larguraMax / $larguraOriginal, $alturaMax / $alturaOriginal);
        $novaLargura = round($larguraOriginal * $proporcao);
        $novaAltura = round($alturaOriginal * $proporcao);
        
        // Criar imagem original
        switch ($tipo) {
            case IMAGETYPE_JPEG:
                $imagemOriginal = imagecreatefromjpeg($caminho);
                break;
            case IMAGETYPE_PNG:
                $imagemOriginal = imagecreatefrompng($caminho);
                break;
            case IMAGETYPE_GIF:
                $imagemOriginal = imagecreatefromgif($caminho);
                break;
            default:
                return false;
        }
        
        if (!$imagemOriginal) {
            return false;
        }
        
        // Criar nova imagem
        $novaImagem = imagecreatetruecolor($novaLargura, $novaAltura);
        
        // Preservar transparência para PNG e GIF
        if ($tipo === IMAGETYPE_PNG || $tipo === IMAGETYPE_GIF) {
            imagealphablending($novaImagem, false);
            imagesavealpha($novaImagem, true);
            $transparente = imagecolorallocatealpha($novaImagem, 255, 255, 255, 127);
            imagefill($novaImagem, 0, 0, $transparente);
        }
        
        // Redimensionar
        imagecopyresampled(
            $novaImagem, $imagemOriginal,
            0, 0, 0, 0,
            $novaLargura, $novaAltura,
            $larguraOriginal, $alturaOriginal
        );
        
        // Salvar nova imagem
        switch ($tipo) {
            case IMAGETYPE_JPEG:
                imagejpeg($novaImagem, $caminho, 85);
                break;
            case IMAGETYPE_PNG:
                imagepng($novaImagem, $caminho, 8);
                break;
            case IMAGETYPE_GIF:
                imagegif($novaImagem, $caminho);
                break;
        }
        
        // Limpar memória
        imagedestroy($imagemOriginal);
        imagedestroy($novaImagem);
        
        return true;
    }
    
    /**
     * Excluir foto
     */
    public function excluir() {
        if (!$this->isPost()) {
            $this->json(['error' => 'Método não permitido'], 405);
            return;
        }
        
        $tipo = $this->getPost('tipo'); // 'atendimento' ou 'vigilancia'
        $fotoId = $this->getPost('foto_id');
        
        if (!$tipo || !$fotoId) {
            $this->json(['error' => 'Parâmetros inválidos'], 400);
            return;
        }
        
        if ($tipo === 'atendimento') {
            $model = new Atendimento();
            $sucesso = $model->removerFoto($fotoId);
        } elseif ($tipo === 'vigilancia') {
            $model = new VigilanciaVeicular();
            $sucesso = $model->removerFoto($fotoId);
        } else {
            $this->json(['error' => 'Tipo inválido'], 400);
            return;
        }
        
        if ($sucesso) {
            $this->json(['success' => true, 'message' => 'Foto excluída com sucesso!']);
        } else {
            $this->json(['error' => 'Erro ao excluir foto'], 500);
        }
    }
    
    /**
     * Listar fotos
     */
    public function listar($tipo, $id) {
        if ($tipo === 'atendimento') {
            $model = new Atendimento();
            $fotos = $model->fotos($id);
        } elseif ($tipo === 'vigilancia') {
            $model = new VigilanciaVeicular();
            $fotos = $model->fotos($id);
        } else {
            $this->json(['error' => 'Tipo inválido'], 400);
            return;
        }
        
        // Adicionar URL completa para cada foto
        foreach ($fotos as &$foto) {
            $foto['url'] = ASSETS_URL . $foto['caminho_foto'];
        }
        
        $this->json(['success' => true, 'fotos' => $fotos]);
    }
}
?>

