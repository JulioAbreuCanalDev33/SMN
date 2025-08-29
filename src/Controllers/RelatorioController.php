<?php
/**
 * Controller de Relatórios
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

class RelatorioController extends Controller {
    
    public function index() {
        $data = [
            'title' => 'Relatórios - Sistema de Monitoramento'
        ];
        
        $this->view('relatorios/index', $data);
    }
    
    /**
     * Relatório de Prestadores em PDF
     */
    public function prestadoresPdf() {
        $prestadorModel = new Prestador();
        $prestadores = $prestadorModel->ativos();
        
        // Configurar cabeçalhos para PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="relatorio_prestadores_' . date('Y-m-d') . '.pdf"');
        
        // Gerar PDF simples (implementação básica)
        $html = $this->gerarHtmlPrestadores($prestadores);
        
        // Aqui você implementaria a geração real do PDF
        // Por exemplo, usando TCPDF ou DomPDF
        echo $html; // Temporário - substituir por geração real de PDF
    }
    
    /**
     * Relatório de Prestadores em Excel
     */
    public function prestadoresExcel() {
        $prestadorModel = new Prestador();
        $prestadores = $prestadorModel->ativos();
        
        // Configurar cabeçalhos para Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="relatorio_prestadores_' . date('Y-m-d') . '.xls"');
        
        echo $this->gerarExcelPrestadores($prestadores);
    }
    
    /**
     * Relatório de Atendimentos em PDF
     */
    public function atendimentosPdf() {
        $atendimentoModel = new Atendimento();
        $dataInicio = $this->getGet('data_inicio', date('Y-m-01'));
        $dataFim = $this->getGet('data_fim', date('Y-m-d'));
        
        $atendimentos = $atendimentoModel->relatorioPorPeriodo($dataInicio, $dataFim);
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="relatorio_atendimentos_' . date('Y-m-d') . '.pdf"');
        
        $html = $this->gerarHtmlAtendimentos($atendimentos, $dataInicio, $dataFim);
        echo $html; // Temporário - substituir por geração real de PDF
    }
    
    /**
     * Relatório de Atendimentos em Excel
     */
    public function atendimentosExcel() {
        $atendimentoModel = new Atendimento();
        $dataInicio = $this->getGet('data_inicio', date('Y-m-01'));
        $dataFim = $this->getGet('data_fim', date('Y-m-d'));
        
        $atendimentos = $atendimentoModel->relatorioPorPeriodo($dataInicio, $dataFim);
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="relatorio_atendimentos_' . date('Y-m-d') . '.xls"');
        
        echo $this->gerarExcelAtendimentos($atendimentos, $dataInicio, $dataFim);
    }
    
    /**
     * Gera HTML para relatório de prestadores
     */
    private function gerarHtmlPrestadores($prestadores) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Relatório de Prestadores</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                h1 { text-align: center; }
            </style>
        </head>
        <body>
            <h1>Relatório de Prestadores</h1>
            <p>Gerado em: ' . date('d/m/Y H:i:s') . '</p>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Serviço</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($prestadores as $prestador) {
            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($prestador['nome_prestador']) . '</td>
                        <td>' . htmlspecialchars($prestador['servico_prestador']) . '</td>
                        <td>' . htmlspecialchars($prestador['email_prestador']) . '</td>
                        <td>' . htmlspecialchars($prestador['telefone_1_prestador']) . '</td>
                        <td>' . htmlspecialchars($prestador['cidade_prestador']) . '</td>
                        <td>' . htmlspecialchars($prestador['estado']) . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Gera Excel para relatório de prestadores
     */
    private function gerarExcelPrestadores($prestadores) {
        $excel = '
        <table border="1">
            <tr>
                <th>Nome</th>
                <th>Serviço</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Cidade</th>
                <th>Estado</th>
            </tr>';
        
        foreach ($prestadores as $prestador) {
            $excel .= '
            <tr>
                <td>' . htmlspecialchars($prestador['nome_prestador']) . '</td>
                <td>' . htmlspecialchars($prestador['servico_prestador']) . '</td>
                <td>' . htmlspecialchars($prestador['email_prestador']) . '</td>
                <td>' . htmlspecialchars($prestador['telefone_1_prestador']) . '</td>
                <td>' . htmlspecialchars($prestador['cidade_prestador']) . '</td>
                <td>' . htmlspecialchars($prestador['estado']) . '</td>
            </tr>';
        }
        
        $excel .= '</table>';
        
        return $excel;
    }
    
    /**
     * Gera HTML para relatório de atendimentos
     */
    private function gerarHtmlAtendimentos($atendimentos, $dataInicio, $dataFim) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Relatório de Atendimentos</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                h1 { text-align: center; }
            </style>
        </head>
        <body>
            <h1>Relatório de Atendimentos</h1>
            <p>Período: ' . date('d/m/Y', strtotime($dataInicio)) . ' a ' . date('d/m/Y', strtotime($dataFim)) . '</p>
            <p>Gerado em: ' . date('d/m/Y H:i:s') . '</p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Cliente</th>
                        <th>Agente</th>
                        <th>Status</th>
                        <th>Tipo</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($atendimentos as $atendimento) {
            $html .= '
                    <tr>
                        <td>' . $atendimento['id_atendimento'] . '</td>
                        <td>' . htmlspecialchars($atendimento['solicitante']) . '</td>
                        <td>' . htmlspecialchars($atendimento['cliente_nome']) . '</td>
                        <td>' . htmlspecialchars($atendimento['agente_nome']) . '</td>
                        <td>' . htmlspecialchars($atendimento['status_atendimento']) . '</td>
                        <td>' . htmlspecialchars($atendimento['tipo_de_servico']) . '</td>
                        <td>' . date('d/m/Y', strtotime($atendimento['created_at'])) . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        </body>
        </html>';
        
        return $html;
    }
    
    /**
     * Gera Excel para relatório de atendimentos
     */
    private function gerarExcelAtendimentos($atendimentos, $dataInicio, $dataFim) {
        $excel = '
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Solicitante</th>
                <th>Cliente</th>
                <th>Agente</th>
                <th>Status</th>
                <th>Tipo</th>
                <th>Data</th>
            </tr>';
        
        foreach ($atendimentos as $atendimento) {
            $excel .= '
            <tr>
                <td>' . $atendimento['id_atendimento'] . '</td>
                <td>' . htmlspecialchars($atendimento['solicitante']) . '</td>
                <td>' . htmlspecialchars($atendimento['cliente_nome']) . '</td>
                <td>' . htmlspecialchars($atendimento['agente_nome']) . '</td>
                <td>' . htmlspecialchars($atendimento['status_atendimento']) . '</td>
                <td>' . htmlspecialchars($atendimento['tipo_de_servico']) . '</td>
                <td>' . date('d/m/Y', strtotime($atendimento['created_at'])) . '</td>
            </tr>';
        }
        
        $excel .= '</table>';
        
        return $excel;
    }
}
?>

