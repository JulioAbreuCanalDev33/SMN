<?php
/**
 * Configuração de Rotas do Sistema
 * Autor: Julio Abreu
 */

// Definir rotas do sistema
$routes = [
    // Página inicial
    '' => 'HomeController@index',
    'home' => 'HomeController@index',
    'dashboard' => 'HomeController@index',
    
    // API do dashboard
    'home/api-dashboard' => 'HomeController@apiDashboard',
    'home/buscar-rapida' => 'HomeController@buscarRapida',
    'home/notificacoes' => 'HomeController@notificacoes',
    
    // Prestadores
    'prestadores' => 'PrestadorController@index',
    'prestadores/criar' => 'PrestadorController@create',
    'prestadores/salvar' => 'PrestadorController@store',
    'prestadores/{id}' => 'PrestadorController@show',
    'prestadores/{id}/editar' => 'PrestadorController@edit',
    'prestadores/{id}/atualizar' => 'PrestadorController@update',
    'prestadores/{id}/excluir' => 'PrestadorController@delete',
    'prestadores/api' => 'PrestadorController@api',
    'prestadores/validar-cpf' => 'PrestadorController@validarCpf',
    'prestadores/validar-email' => 'PrestadorController@validarEmail',
    
    // Clientes
    'clientes' => 'ClienteController@index',
    'clientes/criar' => 'ClienteController@create',
    'clientes/salvar' => 'ClienteController@store',
    'clientes/{id}' => 'ClienteController@show',
    'clientes/{id}/editar' => 'ClienteController@edit',
    'clientes/{id}/atualizar' => 'ClienteController@update',
    'clientes/{id}/excluir' => 'ClienteController@delete',
    'clientes/api' => 'ClienteController@api',
    'clientes/validar-cnpj' => 'ClienteController@validarCnpj',
    'clientes/buscar-cep' => 'ClienteController@buscarCep',
    
    // Agentes
    'agentes' => 'AgenteController@index',
    'agentes/criar' => 'AgenteController@create',
    'agentes/salvar' => 'AgenteController@store',
    'agentes/{id}' => 'AgenteController@show',
    'agentes/{id}/editar' => 'AgenteController@edit',
    'agentes/{id}/atualizar' => 'AgenteController@update',
    'agentes/{id}/excluir' => 'AgenteController@delete',
    'agentes/{id}/toggle-status' => 'AgenteController@toggleStatus',
    'agentes/api' => 'AgenteController@api',
    'agentes/performance' => 'AgenteController@relatorioPerformance',
    
    // Atendimentos
    'atendimentos' => 'AtendimentoController@index',
    'atendimentos/criar' => 'AtendimentoController@create',
    'atendimentos/salvar' => 'AtendimentoController@store',
    'atendimentos/{id}' => 'AtendimentoController@show',
    'atendimentos/{id}/editar' => 'AtendimentoController@edit',
    'atendimentos/{id}/atualizar' => 'AtendimentoController@update',
    'atendimentos/{id}/excluir' => 'AtendimentoController@delete',
    'atendimentos/{id}/finalizar' => 'AtendimentoController@finalizar',
    
    // Rondas Periódicas
    'rondas' => 'RondaController@index',
    'rondas/criar' => 'RondaController@create',
    'rondas/salvar' => 'RondaController@store',
    'rondas/{id}' => 'RondaController@show',
    'rondas/{id}/editar' => 'RondaController@edit',
    'rondas/{id}/atualizar' => 'RondaController@update',
    'rondas/{id}/excluir' => 'RondaController@delete',
    
    // Ocorrências Veiculares
    'ocorrencias' => 'OcorrenciaController@index',
    'ocorrencias/criar' => 'OcorrenciaController@create',
    'ocorrencias/salvar' => 'OcorrenciaController@store',
    'ocorrencias/{id}' => 'OcorrenciaController@show',
    'ocorrencias/{id}/editar' => 'OcorrenciaController@edit',
    'ocorrencias/{id}/atualizar' => 'OcorrenciaController@update',
    'ocorrencias/{id}/excluir' => 'OcorrenciaController@delete',
    
    // Vigilância Veicular
    'vigilancia' => 'VigilanciaController@index',
    'vigilancia/criar' => 'VigilanciaController@create',
    'vigilancia/salvar' => 'VigilanciaController@store',
    'vigilancia/{id}' => 'VigilanciaController@show',
    'vigilancia/{id}/editar' => 'VigilanciaController@edit',
    'vigilancia/{id}/atualizar' => 'VigilanciaController@update',
    'vigilancia/{id}/excluir' => 'VigilanciaController@delete',
    'vigilancia/{id}/finalizar' => 'VigilanciaController@finalizar',
    
    // Upload de Fotos
    'upload/atendimento/{id}' => 'UploadController@atendimento',
    'upload/vigilancia/{id}' => 'UploadController@vigilancia',
    'upload/excluir' => 'UploadController@excluir',
    'upload/listar/{tipo}/{id}' => 'UploadController@listar',
    
    // Relatórios
    'relatorios' => 'RelatorioController@index',
    'relatorios/prestadores/pdf' => 'RelatorioController@prestadoresPdf',
    'relatorios/prestadores/excel' => 'RelatorioController@prestadoresExcel',
    'relatorios/atendimentos/pdf' => 'RelatorioController@atendimentosPdf',
    'relatorios/atendimentos/excel' => 'RelatorioController@atendimentosExcel',
];

return $routes;
?>

