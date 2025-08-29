<?php
/**
 * Sistema de Monitoramento - Ponto de Entrada
 * Autor: Julio Abreu
 */

// Inicia a sessão
session_start();

// Inclui as configurações
require_once __DIR__ . '/../config/config.php';

// Inclui as classes principais
require_once __DIR__ . '/../src/Core/Database.php';
require_once __DIR__ . '/../src/Core/Router.php';
require_once __DIR__ . '/../src/Core/Controller.php';

// Cria o roteador
$router = new Router();

// Define as rotas principais
$router->get('/', 'Home@index');
$router->get('/home', 'Home@index');

// Rotas dos Prestadores
$router->get('/prestadores', 'Prestador@index');
$router->get('/prestadores/criar', 'Prestador@create');
$router->post('/prestadores/criar', 'Prestador@store');
$router->get('/prestadores/{id}', 'Prestador@show');
$router->get('/prestadores/{id}/editar', 'Prestador@edit');
$router->post('/prestadores/{id}/editar', 'Prestador@update');
$router->post('/prestadores/{id}/excluir', 'Prestador@delete');

// Rotas dos Clientes
$router->get('/clientes', 'Cliente@index');
$router->get('/clientes/criar', 'Cliente@create');
$router->post('/clientes/criar', 'Cliente@store');
$router->get('/clientes/{id}', 'Cliente@show');
$router->get('/clientes/{id}/editar', 'Cliente@edit');
$router->post('/clientes/{id}/editar', 'Cliente@update');
$router->post('/clientes/{id}/excluir', 'Cliente@delete');

// Rotas dos Agentes
$router->get('/agentes', 'Agente@index');
$router->get('/agentes/criar', 'Agente@create');
$router->post('/agentes/criar', 'Agente@store');
$router->get('/agentes/{id}', 'Agente@show');
$router->get('/agentes/{id}/editar', 'Agente@edit');
$router->post('/agentes/{id}/editar', 'Agente@update');
$router->post('/agentes/{id}/excluir', 'Agente@delete');

// Rotas dos Atendimentos
$router->get('/atendimentos', 'Atendimento@index');
$router->get('/atendimentos/criar', 'Atendimento@create');
$router->post('/atendimentos/criar', 'Atendimento@store');
$router->get('/atendimentos/{id}', 'Atendimento@show');
$router->get('/atendimentos/{id}/editar', 'Atendimento@edit');
$router->post('/atendimentos/{id}/editar', 'Atendimento@update');
$router->post('/atendimentos/{id}/excluir', 'Atendimento@delete');

// Rotas das Rondas
$router->get('/rondas', 'Ronda@index');
$router->get('/rondas/criar', 'Ronda@create');
$router->post('/rondas/criar', 'Ronda@store');
$router->get('/rondas/{id}', 'Ronda@show');
$router->get('/rondas/{id}/editar', 'Ronda@edit');
$router->post('/rondas/{id}/editar', 'Ronda@update');
$router->post('/rondas/{id}/excluir', 'Ronda@delete');

// Rotas das Ocorrências
$router->get('/ocorrencias', 'Ocorrencia@index');
$router->get('/ocorrencias/criar', 'Ocorrencia@create');
$router->post('/ocorrencias/criar', 'Ocorrencia@store');
$router->get('/ocorrencias/{id}', 'Ocorrencia@show');
$router->get('/ocorrencias/{id}/editar', 'Ocorrencia@edit');
$router->post('/ocorrencias/{id}/editar', 'Ocorrencia@update');
$router->post('/ocorrencias/{id}/excluir', 'Ocorrencia@delete');

// Rotas da Vigilância
$router->get('/vigilancia', 'Vigilancia@index');
$router->get('/vigilancia/criar', 'Vigilancia@create');
$router->post('/vigilancia/criar', 'Vigilancia@store');
$router->get('/vigilancia/{id}', 'Vigilancia@show');
$router->get('/vigilancia/{id}/editar', 'Vigilancia@edit');
$router->post('/vigilancia/{id}/editar', 'Vigilancia@update');
$router->post('/vigilancia/{id}/excluir', 'Vigilancia@delete');

// Rotas de Upload
$router->post('/upload/foto', 'Upload@foto');
$router->post('/upload/documento', 'Upload@documento');

// Rotas de Relatórios
$router->get('/relatorios', 'Relatorio@index');
$router->get('/relatorios/prestadores/pdf', 'Relatorio@prestadoresPdf');
$router->get('/relatorios/prestadores/excel', 'Relatorio@prestadoresExcel');
$router->get('/relatorios/atendimentos/pdf', 'Relatorio@atendimentosPdf');
$router->get('/relatorios/atendimentos/excel', 'Relatorio@atendimentosExcel');

// Processa a requisição
try {
    $router->dispatch();
} catch (Exception $e) {
    // Em produção, registrar o erro em log
    error_log($e->getMessage());
    
    // Exibir página de erro amigável
    http_response_code(500);
    echo "<h1>Erro interno do servidor</h1>";
    echo "<p>Ocorreu um erro inesperado. Tente novamente mais tarde.</p>";
    
    // Em desenvolvimento, mostrar detalhes do erro
    if (ini_get('display_errors')) {
        echo "<hr>";
        echo "<h3>Detalhes do erro:</h3>";
        echo "<pre>" . $e->getMessage() . "</pre>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}
?>

