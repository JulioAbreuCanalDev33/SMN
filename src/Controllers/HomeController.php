<?php
/**
 * Controller da Página Inicial
 * Autor: Julio Abreu
 */

require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Prestador.php';
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Agente.php';
require_once __DIR__ . '/../Models/Atendimento.php';
require_once __DIR__ . '/../Models/RondaPeriodica.php';
require_once __DIR__ . '/../Models/OcorrenciaVeicular.php';
require_once __DIR__ . '/../Models/VigilanciaVeicular.php';

class HomeController extends Controller {
    
    /**
     * Página inicial do sistema
     */
    public function index() {
        // Carrega estatísticas gerais
        $prestadorModel = new Prestador();
        $clienteModel = new Cliente();
        $agenteModel = new Agente();
        $atendimentoModel = new Atendimento();
        $rondaModel = new RondaPeriodica();
        $ocorrenciaModel = new OcorrenciaVeicular();
        $vigilanciaModel = new VigilanciaVeicular();
        
        // Estatísticas básicas
        $estatisticas = [
            'prestadores' => $prestadorModel->count(),
            'clientes' => $clienteModel->count(),
            'agentes' => $agenteModel->count(),
            'atendimentos' => $atendimentoModel->count(),
            'rondas' => $rondaModel->count(),
            'ocorrencias' => $ocorrenciaModel->count(),
            'vigilancias' => $vigilanciaModel->count()
        ];
        
        // Estatísticas detalhadas dos atendimentos
        $estatisticasAtendimentos = $atendimentoModel->estatisticas();
        
        // Estatísticas dos agentes
        $estatisticasAgentes = $agenteModel->estatisticas();
        
        // Rondas que precisam de atenção
        $rondasAtencao = $rondaModel->precisamAtencao();
        
        // Atendimentos recentes
        $atendimentosRecentes = $atendimentoModel->todosComRelacionamentos();
        $atendimentosRecentes = array_slice($atendimentosRecentes, 0, 5);
        
        // Vigilâncias em andamento
        $vigilanciasAndamento = $vigilanciaModel->emAndamento();
        $vigilanciasAndamento = array_slice($vigilanciasAndamento, 0, 5);
        
        // Dados para gráficos
        $dadosGraficos = [
            'atendimentos_por_mes' => $estatisticasAtendimentos['por_mes'] ?? [],
            'atendimentos_por_status' => $estatisticasAtendimentos['por_status'] ?? [],
            'agentes_por_funcao' => $estatisticasAgentes['por_funcao'] ?? []
        ];
        
        $data = [
            'title' => 'Dashboard - Sistema de Monitoramento',
            'estatisticas' => $estatisticas,
            'estatisticas_atendimentos' => $estatisticasAtendimentos,
            'estatisticas_agentes' => $estatisticasAgentes,
            'rondas_atencao' => $rondasAtencao,
            'atendimentos_recentes' => $atendimentosRecentes,
            'vigilancias_andamento' => $vigilanciasAndamento,
            'dados_graficos' => $dadosGraficos
        ];
        
        $this->view('home/index', $data);
    }
    
    /**
     * API para dados do dashboard (AJAX)
     */
    public function apiDashboard() {
        if (!$this->isAjax()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $tipo = $this->getGet('tipo', 'geral');
        
        switch ($tipo) {
            case 'atendimentos':
                $model = new Atendimento();
                $data = $model->estatisticas();
                break;
                
            case 'agentes':
                $model = new Agente();
                $data = $model->estatisticas();
                break;
                
            case 'rondas':
                $model = new RondaPeriodica();
                $data = $model->estatisticas();
                break;
                
            case 'vigilancias':
                $model = new VigilanciaVeicular();
                $data = $model->estatisticas();
                break;
                
            default:
                // Estatísticas gerais
                $prestadorModel = new Prestador();
                $clienteModel = new Cliente();
                $agenteModel = new Agente();
                $atendimentoModel = new Atendimento();
                
                $data = [
                    'prestadores' => $prestadorModel->count(),
                    'clientes' => $clienteModel->count(),
                    'agentes' => $agenteModel->count(),
                    'atendimentos' => $atendimentoModel->count()
                ];
                break;
        }
        
        $this->json(['success' => true, 'data' => $data]);
    }
    
    /**
     * Busca rápida (AJAX)
     */
    public function buscarRapida() {
        if (!$this->isAjax()) {
            $this->json(['error' => 'Acesso negado'], 403);
            return;
        }
        
        $termo = $this->getGet('q', '');
        
        if (strlen($termo) < 3) {
            $this->json(['success' => true, 'resultados' => []]);
            return;
        }
        
        $resultados = [];
        
        // Buscar em prestadores
        $prestadorModel = new Prestador();
        $prestadores = $prestadorModel->pesquisar($termo);
        foreach ($prestadores as $prestador) {
            $resultados[] = [
                'tipo' => 'Prestador',
                'titulo' => $prestador['nome_prestador'],
                'subtitulo' => $prestador['servico_prestador'],
                'url' => BASE_URL . 'prestadores/' . $prestador['id']
            ];
        }
        
        // Buscar em clientes
        $clienteModel = new Cliente();
        $clientes = $clienteModel->pesquisar($termo);
        foreach ($clientes as $cliente) {
            $resultados[] = [
                'tipo' => 'Cliente',
                'titulo' => $cliente['nome_empresa'],
                'subtitulo' => $cliente['cnpj'],
                'url' => BASE_URL . 'clientes/' . $cliente['id_cliente']
            ];
        }
        
        // Buscar em agentes
        $agenteModel = new Agente();
        $agentes = $agenteModel->pesquisar($termo);
        foreach ($agentes as $agente) {
            $resultados[] = [
                'tipo' => 'Agente',
                'titulo' => $agente['nome'],
                'subtitulo' => $agente['funcao'],
                'url' => BASE_URL . 'agentes/' . $agente['id_agente']
            ];
        }
        
        // Buscar em atendimentos
        $atendimentoModel = new Atendimento();
        $atendimentos = $atendimentoModel->pesquisar($termo);
        foreach (array_slice($atendimentos, 0, 5) as $atendimento) {
            $resultados[] = [
                'tipo' => 'Atendimento',
                'titulo' => 'Atendimento #' . $atendimento['id_atendimento'],
                'subtitulo' => $atendimento['solicitante'] . ' - ' . $atendimento['cliente_nome'],
                'url' => BASE_URL . 'atendimentos/' . $atendimento['id_atendimento']
            ];
        }
        
        // Limitar resultados
        $resultados = array_slice($resultados, 0, 20);
        
        $this->json(['success' => true, 'resultados' => $resultados]);
    }
    
    /**
     * Notificações do sistema
     */
    public function notificacoes() {
        $rondaModel = new RondaPeriodica();
        $atendimentoModel = new Atendimento();
        $vigilanciaModel = new VigilanciaVeicular();
        
        $notificacoes = [];
        
        // Rondas que vencem hoje
        $rondasVencemHoje = $rondaModel->vencemHoje();
        foreach ($rondasVencemHoje as $ronda) {
            $notificacoes[] = [
                'tipo' => 'warning',
                'titulo' => 'Ronda vence hoje',
                'mensagem' => "Ronda #{$ronda['id_ronda']} - {$ronda['cliente_nome']}",
                'url' => BASE_URL . 'rondas/' . $ronda['id_ronda'],
                'data' => $ronda['data_final']
            ];
        }
        
        // Rondas vencidas
        $rondasVencidas = $rondaModel->vencidas();
        foreach (array_slice($rondasVencidas, 0, 5) as $ronda) {
            $notificacoes[] = [
                'tipo' => 'danger',
                'titulo' => 'Ronda vencida',
                'mensagem' => "Ronda #{$ronda['id_ronda']} - {$ronda['cliente_nome']}",
                'url' => BASE_URL . 'rondas/' . $ronda['id_ronda'],
                'data' => $ronda['data_final']
            ];
        }
        
        // Atendimentos em andamento há mais de 24h
        $atendimentosAntigos = $atendimentoModel->where(
            "status_atendimento = 'Em andamento' AND created_at < datetime('now', '-1 day')",
            [],
            'created_at ASC'
        );
        
        foreach (array_slice($atendimentosAntigos, 0, 5) as $atendimento) {
            $notificacoes[] = [
                'tipo' => 'info',
                'titulo' => 'Atendimento em andamento',
                'mensagem' => "Atendimento #{$atendimento['id_atendimento']} há mais de 24h",
                'url' => BASE_URL . 'atendimentos/' . $atendimento['id_atendimento'],
                'data' => $atendimento['created_at']
            ];
        }
        
        // Ordenar por data
        usort($notificacoes, function($a, $b) {
            return strtotime($b['data']) - strtotime($a['data']);
        });
        
        if ($this->isAjax()) {
            $this->json(['success' => true, 'notificacoes' => $notificacoes]);
        } else {
            $this->view('home/notificacoes', ['notificacoes' => $notificacoes]);
        }
    }
}
?>

