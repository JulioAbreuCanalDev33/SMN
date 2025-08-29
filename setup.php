<?php
/**
 * Script de Instalação do Sistema de Monitoramento
 * Autor: Julio Abreu
 */

echo "=== SISTEMA DE MONITORAMENTO - INSTALAÇÃO ===\n";
echo "Autor: Julio Abreu\n\n";

// Verificar versão do PHP
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die("ERRO: PHP 8.0 ou superior é necessário. Versão atual: " . PHP_VERSION . "\n");
}

echo "✓ PHP " . PHP_VERSION . " detectado\n";

// Verificar extensões necessárias
$extensoes = ['pdo', 'pdo_sqlite', 'gd', 'json'];
$faltando = [];

foreach ($extensoes as $ext) {
    if (!extension_loaded($ext)) {
        $faltando[] = $ext;
    } else {
        echo "✓ Extensão $ext encontrada\n";
    }
}

if (!empty($faltando)) {
    die("ERRO: Extensões PHP faltando: " . implode(', ', $faltando) . "\n");
}

// Criar diretórios necessários
$diretorios = [
    'database',
    'public/uploads',
    'public/uploads/atendimentos',
    'public/uploads/vigilancia',
    'logs'
];

echo "\nCriando diretórios...\n";
foreach ($diretorios as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✓ Diretório $dir criado\n";
        } else {
            echo "✗ Erro ao criar diretório $dir\n";
        }
    } else {
        echo "✓ Diretório $dir já existe\n";
    }
}

// Criar arquivo .gitkeep nos diretórios de upload
$gitkeepDirs = ['public/uploads', 'logs'];
foreach ($gitkeepDirs as $dir) {
    $gitkeepFile = $dir . '/.gitkeep';
    if (!file_exists($gitkeepFile)) {
        file_put_contents($gitkeepFile, '');
        echo "✓ Arquivo .gitkeep criado em $dir\n";
    }
}

// Configurar permissões
echo "\nConfigurando permissões...\n";
$permissoes = [
    'database' => 0755,
    'public/uploads' => 0755,
    'logs' => 0755
];

foreach ($permissoes as $dir => $perm) {
    if (chmod($dir, $perm)) {
        echo "✓ Permissões configuradas para $dir\n";
    } else {
        echo "⚠ Aviso: Não foi possível configurar permissões para $dir\n";
    }
}

// Criar banco de dados
echo "\nCriando banco de dados...\n";

try {
    $dbPath = 'database/database.sqlite';
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Ler e executar schema
    $schema = file_get_contents('database/schema.sql');
    if ($schema) {
        $pdo->exec($schema);
        echo "✓ Banco de dados criado com sucesso\n";
        echo "✓ Tabelas criadas\n";
    } else {
        echo "✗ Erro: Arquivo schema.sql não encontrado\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro ao criar banco de dados: " . $e->getMessage() . "\n";
}

// Verificar configuração do servidor web
echo "\nVerificando configuração...\n";

if (isset($_SERVER['HTTP_HOST'])) {
    $baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
    echo "✓ URL base detectada: $baseUrl\n";
} else {
    echo "⚠ Execute via navegador para detectar URL base automaticamente\n";
}

// Criar arquivo de configuração local (se não existir)
$configLocal = 'config/local.php';
if (!file_exists($configLocal)) {
    $configContent = "<?php\n";
    $configContent .= "// Configurações locais\n";
    $configContent .= "// Sobrescreve as configurações padrão em config.php\n\n";
    $configContent .= "// Exemplo:\n";
    $configContent .= "// define('DEBUG_MODE', true);\n";
    $configContent .= "// define('DB_PATH', __DIR__ . '/../database/database.sqlite');\n";
    
    file_put_contents($configLocal, $configContent);
    echo "✓ Arquivo de configuração local criado\n";
}

echo "\n=== INSTALAÇÃO CONCLUÍDA ===\n";
echo "\nPróximos passos:\n";
echo "1. Configure seu servidor web para apontar para a pasta 'public/'\n";
echo "2. Certifique-se de que o mod_rewrite está habilitado (Apache)\n";
echo "3. Acesse o sistema pelo navegador\n";
echo "4. O sistema está pronto para uso!\n\n";

echo "Estrutura de URLs:\n";
echo "- Dashboard: /\n";
echo "- Prestadores: /prestadores\n";
echo "- Clientes: /clientes\n";
echo "- Agentes: /agentes\n";
echo "- Atendimentos: /atendimentos\n";
echo "- Relatórios: /relatorios\n\n";

echo "Desenvolvido por: Julio Abreu\n";
echo "Versão: 1.0.0\n";
?>

