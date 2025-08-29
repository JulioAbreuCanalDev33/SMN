# Sistema de Monitoramento e Atendimento Patrimonial

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

Sistema completo para gestão de monitoramento e atendimento patrimonial, desenvolvido em PHP com arquitetura MVC, utilizando Bootstrap 5 para o front-end e SQLite3 como banco de dados.

**Autor:** Julio Abreu

---

## 🚀 Funcionalidades Principais

- **Arquitetura MVC:** Código organizado, escalável e de fácil manutenção.
- **Dashboard Dinâmico:** Visão geral com estatísticas e gráficos interativos.
- **CRUD Completo:** Gestão total de Prestadores, Clientes, Agentes, Atendimentos, Rondas, Ocorrências e Vigilâncias.
- **Relatórios Avançados:** Exportação de dados em formatos **PDF** e **Excel (XLS)**.
- **Upload de Fotos:** Envio de imagens com legendas para Atendimentos e Vigilâncias.
- **Modo Claro e Escuro:** Interface adaptável para preferência do usuário, com persistência em `localStorage`.
- **Interface Responsiva:** Construído com **Bootstrap 5**, garantindo compatibilidade com desktops e dispositivos móveis.
- **Busca e Filtros:** Ferramentas de busca e filtragem em todos os módulos principais.
- **Validações:** Validações robustas no back-end e interativas no front-end (via AJAX).
- **Notificações em Tempo Real:** Alertas sobre rondas vencidas e atendimentos importantes.
- **Sem Dependências Externas (Login):** O sistema não possui sistema de login, permitindo integração com qualquer método de autenticação.

## 🛠️ Tecnologias Utilizadas

- **Back-end:** PHP 8+ (Estruturado, seguindo princípios MVC)
- **Front-end:** HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3
- **Banco de Dados:** SQLite 3
- **Bibliotecas JS:** jQuery, Chart.js (para gráficos)

## 📋 Estrutura do Projeto

O projeto segue uma estrutura MVC para garantir a separação de responsabilidades:

```
/sistema-monitoramento-novo
├── config/                 # Arquivos de configuração (banco de dados, rotas, etc.)
├── database/               # Schema do banco de dados e arquivos de migração
├── public/                 # Arquivos públicos (index.php, assets, uploads)
│   ├── assets/             # CSS, JS, Imagens
│   ├── uploads/            # Diretório para fotos enviadas
│   └── index.php           # Ponto de entrada da aplicação (Front Controller)
├── src/                    # Código-fonte da aplicação
│   ├── Core/               # Núcleo do sistema (Controller, Model, Router)
│   ├── Controllers/        # Controladores (lógica da aplicação)
│   ├── Models/             # Modelos (interação com o banco de dados)
│   └── Views/              # Arquivos de visualização (HTML com PHP)
├── vendor/                 # Dependências do Composer (se houver)
└── README.md               # Este arquivo
```

## ⚙️ Instalação e Configuração

Siga os passos abaixo para executar o projeto em seu ambiente local.

**Requisitos:**
- PHP >= 8.0
- Extensão `pdo_sqlite` do PHP habilitada
- Composer (opcional, para gerenciamento de dependências)

**Passos:**

1.  **Clone o repositório:**
    ```bash
    git clone <URL_DO_REPOSITORIO>
    cd sistema-monitoramento-novo
    ```

2.  **Crie o banco de dados:**
    O sistema criará o arquivo de banco de dados `database/database.sqlite` automaticamente na primeira execução.

3.  **Configure as permissões:**
    Garanta que o servidor web (Apache, Nginx) tenha permissão de escrita nos diretórios `database/` e `public/uploads/`.
    ```bash
    sudo chmod -R 775 database public/uploads
    sudo chown -R www-data:www-data database public/uploads
    ```

4.  **Configure o servidor web:**
    Aponte a raiz do seu servidor (DocumentRoot) para o diretório `public/`.

    **Exemplo para Apache (.htaccess):**
    O arquivo `.htaccess` na pasta `public/` já deve lidar com o redirecionamento de rotas. Certifique-se de que o `mod_rewrite` está habilitado.

5.  **Acesse o sistema:**
    Abra seu navegador e acesse a URL configurada (ex: `http://localhost/sistema-monitoramento-novo/public`).

## 🖼️ Telas do Sistema

*Capturas de tela das principais funcionalidades do sistema.*

**Dashboard Principal:**
![Dashboard](public/assets/images/dashboard-principal.png)

**Lista de Prestadores:**
![Prestadores](public/assets/images/lista-prestadores.png)

**Modo Claro e Escuro:**
O sistema possui alternância entre modo claro e escuro, com persistência da preferência do usuário.

## 📄 Licença

Este projeto está licenciado sob a Licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

> Desenvolvido com ❤️ por **Julio Abreu**.

