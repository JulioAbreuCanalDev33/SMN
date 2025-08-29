# Documentação do Sistema de Monitoramento

**Autor:** Julio Abreu

## 1. Introdução

Esta documentação detalha a arquitetura, funcionalidades e estrutura do Sistema de Monitoramento e Atendimento Patrimonial. O sistema foi desenvolvido em PHP, seguindo a arquitetura MVC (Model-View-Controller) para garantir um código limpo, organizado e de fácil manutenção.

### 1.1. Objetivo do Sistema

O objetivo principal é fornecer uma plataforma completa para empresas de monitoramento gerenciarem suas operações, incluindo:

- Cadastro e gestão de clientes, prestadores de serviço e agentes de campo.
- Registro e acompanhamento de atendimentos e rondas periódicas.
- Gestão de ocorrências e vigilâncias veiculares.
- Geração de relatórios detalhados para análise e tomada de decisão.
- Armazenamento de evidências fotográficas com legendas.

### 1.2. Tecnologias

- **Linguagem de Programação:** PHP 8+
- **Banco de Dados:** SQLite 3
- **Arquitetura:** MVC (Model-View-Controller)
- **Front-end:** Bootstrap 5.3, JavaScript (ES6+), jQuery, Chart.js
- **Estilo de Código:** PSR-12 (com adaptações para simplicidade)

## 2. Estrutura de Diretórios

A estrutura de diretórios foi projetada para separar as responsabilidades da aplicação:

- `config/`: Contém os arquivos de configuração, como `config.php` (constantes globais) e `database.php` (configuração do banco de dados).
- `database/`: Armazena o schema do banco de dados (`schema.sql`) e o próprio arquivo do banco (`database.sqlite`).
- `public/`: É o ponto de entrada da aplicação. Contém o `index.php` (Front Controller), e os diretórios `assets` (CSS, JS, imagens) e `uploads`.
- `src/`: O coração da aplicação.
  - `Core/`: Classes fundamentais do framework MVC (Controller, Model, Router, Database).
  - `Controllers/`: Classes que recebem as requisições, processam os dados (com a ajuda dos Models) e decidem qual View exibir.
  - `Models/`: Classes responsáveis pela lógica de negócio e interação com o banco de dados.
  - `Views/`: Arquivos de template (HTML + PHP) que compõem a interface do usuário.

## 3. Fluxo da Requisição

1.  Toda requisição é direcionada para `public/index.php`.
2.  O `index.php` inicializa as configurações e o autoloader.
3.  O `Router` (`src/Core/Router.php`) analisa a URL para determinar qual `Controller` e qual `método` devem ser executados.
4.  O `Controller` correspondente é instanciado e o método é chamado.
5.  O `Controller` interage com o `Model` necessário para buscar ou manipular dados.
6.  O `Model` executa as queries no banco de dados e retorna os resultados para o `Controller`.
7.  O `Controller` carrega a `View` apropriada, passando os dados que ela precisa para ser renderizada.
8.  A `View` é renderizada e o HTML final é enviado como resposta ao navegador.

## 4. Componentes Principais

### 4.1. Core (Núcleo)

- **`Database.php`**: Classe Singleton que gerencia a conexão com o banco de dados SQLite usando PDO. Garante que apenas uma conexão seja aberta por requisição.
- **`Router.php`**: Um roteador simples que mapeia URLs para `Controllers` e métodos. Suporta parâmetros na URL.
- **`Controller.php`**: Classe base para todos os outros controladores. Fornece métodos úteis como `view()`, `redirect()`, `json()`, `isPost()`, `getPost()`, etc.
- **`BaseModel.php`**: Classe base para todos os modelos. Implementa métodos CRUD genéricos (`find`, `all`, `create`, `update`, `delete`, `where`), economizando a reescrita de código.

### 4.2. Models

Cada tabela principal do banco de dados possui um `Model` correspondente. Além das operações CRUD herdadas de `BaseModel`, cada modelo implementa métodos específicos para suas regras de negócio.

- **`Atendimento.php`**: Contém lógicas para buscar atendimentos com dados de clientes e agentes, finalizar atendimentos, adicionar fotos, etc.
- **`Cliente.php`**: Inclui validações de CNPJ, formatação de dados e busca de clientes por diversos critérios.
- **`Agente.php`**: Gerencia o status dos agentes (ativo/inativo) e busca agentes por função.
- ... e assim por diante para cada entidade.

### 4.3. Views

As `Views` são organizadas em subdiretórios correspondentes aos seus `Controllers` (ex: `src/Views/prestadores/`).

- **Layouts (`src/Views/layouts/`)**: Contém o `header.php` and `footer.php`, que são incluídos em todas as páginas, garantindo uma estrutura consistente e evitando repetição de código.
- **Templates de Página**: Cada arquivo (ex: `index.php`, `create.php`, `edit.php`) representa uma página ou um formulário específico. Eles recebem dados do `Controller` através de um array `$data`.

### 4.4. Controllers

Os `Controllers` orquestram a lógica da aplicação.

- **`HomeController.php`**: Responsável pela página principal (Dashboard), agregando dados de vários modelos para exibir estatísticas e gráficos.
- **Controllers de CRUD (ex: `PrestadorController.php`)**: Implementam os métodos padrão para gerenciar uma entidade:
  - `index()`: Lista todos os registros, com paginação e filtros.
  - `create()`: Exibe o formulário de criação.
  - `store()`: Valida e salva os dados do novo registro.
  - `show($id)`: Exibe os detalhes de um registro específico.
  - `edit($id)`: Exibe o formulário de edição.
  - `update($id)`: Valida e atualiza os dados de um registro existente.
  - `delete($id)`: Exclui um registro.

## 5. Funcionalidades Especiais

### 5.1. Modo Claro/Escuro

- **CSS:** Utiliza variáveis CSS (`:root`) para definir as cores. Um seletor de atributo `[data-bs-theme="dark"]` sobrescreve essas variáveis para o modo escuro.
- **JavaScript (`public/assets/js/theme.js`):**
  - Detecta a preferência do sistema operacional do usuário.
  - Salva a escolha do usuário no `localStorage` para persistência.
  - Adiciona um `event listener` ao botão de toggle para alternar o atributo `data-bs-theme` no elemento `<html>`.

### 5.2. Upload de Fotos

- **Front-end (`public/assets/js/photo-manager.js`):**
  - Gerencia a abertura do modal de upload.
  - Exibe um preview da imagem antes do envio.
  - Envia a foto e a legenda via AJAX para o `UploadController`.
  - Atualiza a galeria de fotos dinamicamente após o upload ou exclusão.
- **Back-end (`src/Controllers/UploadController.php`):**
  - Recebe o arquivo e valida tipo e tamanho.
  - Gera um nome de arquivo único para evitar conflitos.
  - Salva o arquivo no diretório `public/uploads/{tipo}/`.
  - Redimensiona a imagem para um tamanho máximo, otimizando o armazenamento e a exibição.
  - Salva o caminho da foto e a legenda no banco de dados, associando-a ao registro correspondente (Atendimento ou Vigilância).

### 5.3. Relatórios PDF e Excel

- **`RelatorioController.php`**: Contém os métodos para gerar os relatórios.
- **Geração de Excel:** O sistema gera uma tabela HTML simples e a envia com o `Content-Type` `application/vnd.ms-excel`. A maioria dos softwares de planilha (Excel, Google Sheets, LibreOffice Calc) consegue interpretar este formato corretamente.
- **Geração de PDF:** Para simplificar, o sistema gera um HTML bem formatado. A impressão para PDF pode ser feita diretamente pelo navegador (`Ctrl+P` > Salvar como PDF), que utilizará os estilos de impressão definidos em `style.css` (`@media print`) para um layout limpo.

## 6. Banco de Dados

O schema do banco de dados está definido em `database/schema.sql`. Ele foi baseado no script fornecido, com as seguintes observações:

- A tabela `usuarios` foi **ignorada**, conforme solicitado.
- Foram adicionadas tabelas para gerenciar as fotos (`fotos_atendimentos`, `fotos_vigilancia_veicular`).
- Índices podem ser adicionados em colunas frequentemente consultadas (chaves estrangeiras, campos de busca) para melhorar a performance.

## 7. Como Estender o Sistema

Para adicionar um novo módulo (ex: 


Equipamentos):

### 7.1. Criando um Novo Model

1. **Criar o arquivo do Model** em `src/Models/Equipamento.php`:

```php
<?php
require_once __DIR__ . '/BaseModel.php';

class Equipamento extends BaseModel {
    protected $table = 'equipamentos';
    protected $primaryKey = 'id_equipamento';
    
    // Campos obrigatórios para validação
    protected $required = ['nome_equipamento', 'tipo_equipamento'];
    
    // Métodos específicos do modelo
    public function buscarPorTipo($tipo) {
        return $this->where('tipo_equipamento', $tipo);
    }
    
    public function equipamentosAtivos() {
        return $this->where('status', 'ativo');
    }
}
?>
```

### 7.2. Criando um Novo Controller

2. **Criar o arquivo do Controller** em `src/Controllers/EquipamentoController.php`:

```php
<?php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Models/Equipamento.php';

class EquipamentoController extends Controller {
    
    public function index() {
        $equipamentoModel = new Equipamento();
        
        // Busca e filtros
        $search = $this->getGet('search', '');
        $tipo = $this->getGet('tipo', '');
        
        $equipamentos = $equipamentoModel->all();
        
        // Aplicar filtros se necessário
        if ($search) {
            $equipamentos = array_filter($equipamentos, function($eq) use ($search) {
                return stripos($eq['nome_equipamento'], $search) !== false;
            });
        }
        
        $data = [
            'title' => 'Equipamentos',
            'equipamentos' => $equipamentos,
            'search' => $search,
            'tipo' => $tipo
        ];
        
        $this->view('equipamentos/index', $data);
    }
    
    public function create() {
        $data = ['title' => 'Novo Equipamento'];
        $this->view('equipamentos/create', $data);
    }
    
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('equipamentos');
            return;
        }
        
        $equipamentoModel = new Equipamento();
        
        $dados = [
            'nome_equipamento' => $this->getPost('nome_equipamento'),
            'tipo_equipamento' => $this->getPost('tipo_equipamento'),
            'descricao' => $this->getPost('descricao'),
            'status' => $this->getPost('status', 'ativo'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($equipamentoModel->create($dados)) {
            $this->redirect('equipamentos?success=1');
        } else {
            $this->redirect('equipamentos/criar?error=1');
        }
    }
    
    // Implementar outros métodos: show, edit, update, delete
}
?>
```

### 7.3. Criando as Views

3. **Criar o diretório** `src/Views/equipamentos/`

4. **Criar a view de listagem** `src/Views/equipamentos/index.php`:

```php
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-gear me-2 text-primary"></i>Equipamentos
        </h1>
        <p class="text-muted mb-0">Gerenciar equipamentos do sistema</p>
    </div>
    <a href="<?php echo BASE_URL; ?>equipamentos/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo Equipamento
    </a>
</div>

<!-- Resto da implementação similar aos outros módulos -->

<?php include __DIR__ . '/../layouts/footer.php'; ?>
```

### 7.4. Adicionando as Rotas

5. **Editar** `config/routes.php` e adicionar:

```php
// Equipamentos
'equipamentos' => 'EquipamentoController@index',
'equipamentos/criar' => 'EquipamentoController@create',
'equipamentos/salvar' => 'EquipamentoController@store',
'equipamentos/{id}' => 'EquipamentoController@show',
'equipamentos/{id}/editar' => 'EquipamentoController@edit',
'equipamentos/{id}/atualizar' => 'EquipamentoController@update',
'equipamentos/{id}/excluir' => 'EquipamentoController@delete',
```

### 7.5. Criando a Tabela no Banco

6. **Adicionar ao schema** `database/schema.sql`:

```sql
CREATE TABLE IF NOT EXISTS equipamentos (
    id_equipamento INTEGER PRIMARY KEY AUTOINCREMENT,
    nome_equipamento TEXT NOT NULL,
    tipo_equipamento TEXT NOT NULL,
    descricao TEXT,
    status TEXT DEFAULT 'ativo',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 7.6. Adicionando ao Menu

7. **Editar** `src/Views/layouts/header.php` e adicionar no menu:

```php
<li class="nav-item">
    <a class="nav-link" href="<?php echo BASE_URL; ?>equipamentos">
        <i class="bi bi-gear me-1"></i>Equipamentos
    </a>
</li>
```

## 8. Boas Práticas de Desenvolvimento

### 8.1. Padrões de Nomenclatura

- **Classes:** PascalCase (ex: `EquipamentoController`)
- **Métodos:** camelCase (ex: `buscarPorTipo()`)
- **Variáveis:** snake_case (ex: `$nome_equipamento`)
- **Arquivos:** PascalCase para classes, lowercase para outros

### 8.2. Estrutura de Métodos nos Controllers

Mantenha sempre a mesma estrutura nos controllers:

```php
// Listagem
public function index() { }

// Formulário de criação
public function create() { }

// Salvar novo registro
public function store() { }

// Exibir detalhes
public function show($id) { }

// Formulário de edição
public function edit($id) { }

// Atualizar registro
public function update($id) { }

// Excluir registro
public function delete($id) { }
```

### 8.3. Validações

Sempre implemente validações tanto no front-end quanto no back-end:

```php
// No Controller
private function validarDados($dados) {
    $erros = [];
    
    if (empty($dados['nome_equipamento'])) {
        $erros[] = 'Nome do equipamento é obrigatório';
    }
    
    if (empty($dados['tipo_equipamento'])) {
        $erros[] = 'Tipo do equipamento é obrigatório';
    }
    
    return $erros;
}
```

### 8.4. Tratamento de Erros

Use try-catch para operações críticas:

```php
try {
    $resultado = $model->create($dados);
    if ($resultado) {
        $this->redirect('equipamentos?success=1');
    }
} catch (Exception $e) {
    error_log('Erro ao criar equipamento: ' . $e->getMessage());
    $this->redirect('equipamentos/criar?error=1');
}
```

## 9. Personalização e Configuração

### 9.1. Configurações Personalizadas

Adicione suas configurações em `config/config.php`:

```php
// Configurações de upload
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Configurações de paginação
define('ITEMS_PER_PAGE', 20);

// Configurações de email (se implementar)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
```

### 9.2. Temas Personalizados

Para criar novos temas, edite `public/assets/css/style.css`:

```css
/* Tema personalizado */
[data-bs-theme="custom"] {
    --bs-primary: #your-color;
    --bs-secondary: #your-secondary-color;
    /* ... outras variáveis */
}
```

### 9.3. Adicionando Novos Campos

Para adicionar campos a uma entidade existente:

1. **Alterar a tabela** no banco de dados
2. **Atualizar o Model** se necessário
3. **Modificar as Views** (formulários e listagens)
4. **Ajustar o Controller** para processar os novos campos

## 10. Manutenção e Atualizações

### 10.1. Backup do Banco de Dados

Crie rotinas de backup regulares:

```bash
# Backup simples
cp database/database.sqlite database/backup_$(date +%Y%m%d).sqlite

# Backup com compressão
tar -czf backup_$(date +%Y%m%d).tar.gz database/database.sqlite
```

### 10.2. Logs de Sistema

Implemente logs para monitoramento:

```php
// Função de log simples
function logSystem($message, $level = 'INFO') {
    $logFile = __DIR__ . '/../logs/system.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}
```

### 10.3. Versionamento

Use Git para controle de versão:

```bash
git init
git add .
git commit -m "Versão inicial do sistema"
git tag v1.0.0
```

## 11. Considerações de Segurança

### 11.1. Validação de Entrada

Sempre valide e sanitize dados de entrada:

```php
// Sanitização básica
$nome = filter_var($input, FILTER_SANITIZE_STRING);
$email = filter_var($input, FILTER_SANITIZE_EMAIL);

// Validação
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Email inválido');
}
```

### 11.2. Proteção contra SQL Injection

Use sempre prepared statements (já implementado no BaseModel):

```php
// Correto (já implementado)
$stmt = $this->db->prepare("SELECT * FROM tabela WHERE campo = ?");
$stmt->execute([$valor]);

// NUNCA faça isso
$query = "SELECT * FROM tabela WHERE campo = '$valor'"; // VULNERÁVEL!
```

### 11.3. Proteção de Arquivos

Configure adequadamente as permissões:

```bash
# Diretórios
chmod 755 database/ public/uploads/ logs/

# Arquivos sensíveis
chmod 644 config/*.php database/schema.sql

# Arquivo do banco (somente leitura/escrita para o proprietário)
chmod 600 database/database.sqlite
```

## 12. Performance e Otimização

### 12.1. Índices no Banco de Dados

Adicione índices para campos frequentemente consultados:

```sql
-- Índices para melhorar performance
CREATE INDEX idx_prestador_cpf ON prestadores(cpf_prestador);
CREATE INDEX idx_cliente_cnpj ON clientes(cnpj_cliente);
CREATE INDEX idx_atendimento_status ON atendimentos(status_atendimento);
CREATE INDEX idx_atendimento_data ON atendimentos(created_at);
```

### 12.2. Cache Simples

Implemente cache para consultas pesadas:

```php
class SimpleCache {
    private static $cache = [];
    
    public static function get($key) {
        return isset(self::$cache[$key]) ? self::$cache[$key] : null;
    }
    
    public static function set($key, $value, $ttl = 300) {
        self::$cache[$key] = [
            'data' => $value,
            'expires' => time() + $ttl
        ];
    }
    
    public static function isValid($key) {
        return isset(self::$cache[$key]) && 
               self::$cache[$key]['expires'] > time();
    }
}
```

---

## Conclusão

Este sistema foi projetado para ser facilmente extensível e mantível. Seguindo os padrões estabelecidos e as boas práticas documentadas, você pode adicionar novos módulos e funcionalidades sem comprometer a estrutura existente.

Para dúvidas ou sugestões de melhorias, consulte a documentação técnica ou entre em contato com o desenvolvedor.

**Desenvolvido por:** Julio Abreu  
**Versão da Documentação:** 1.0  
**Última Atualização:** Dezembro 2024

