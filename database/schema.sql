-- Sistema de Monitoramento - Schema do Banco de Dados
-- Autor: Julio Abreu
-- Versão: 1.0.0

-- Tabela de Configurações do Sistema
CREATE TABLE configuracoes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    chave TEXT UNIQUE NOT NULL,
    valor TEXT,
    descricao TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Prestadores
CREATE TABLE tabela_prestadores (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome_prestador TEXT NOT NULL,
    equipes TEXT,
    servico_prestador TEXT NOT NULL,
    cpf_prestador TEXT UNIQUE NOT NULL,
    rg_prestador TEXT,
    email_prestador TEXT UNIQUE NOT NULL,
    telefone_1_prestador TEXT NOT NULL,
    telefone_2_prestador TEXT,
    cep_prestador TEXT NOT NULL,
    endereco_prestador TEXT NOT NULL,
    numero_prestador TEXT NOT NULL,
    bairro_prestador TEXT NOT NULL,
    cidade_prestador TEXT NOT NULL,
    estado TEXT NOT NULL,
    observacao TEXT,
    documento_prestador TEXT,
    foto_prestador TEXT,
    codigo_do_banco TEXT NOT NULL,
    pix_banco_prestadores TEXT,
    titular_conta TEXT NOT NULL,
    tipo_de_conta TEXT NOT NULL,
    agencia_prestadores TEXT NOT NULL,
    digito_agencia_prestadores TEXT,
    conta_prestadores TEXT NOT NULL,
    digito_conta_prestadores TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Clientes
CREATE TABLE clientes (
    id_cliente INTEGER PRIMARY KEY AUTOINCREMENT,
    nome_empresa TEXT,
    cnpj TEXT,
    contato TEXT,
    endereco TEXT,
    telefone TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Agentes
CREATE TABLE agentes (
    id_agente INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT,
    funcao TEXT,
    status TEXT CHECK(status IN ('Ativo','Inativo')) DEFAULT 'Ativo',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Atendimentos
CREATE TABLE atendimentos (
    id_atendimento INTEGER PRIMARY KEY AUTOINCREMENT,
    solicitante TEXT,
    motivo TEXT,
    valor_patrimonial REAL,
    id_cliente INTEGER,
    conta TEXT,
    id_validacao TEXT,
    filial TEXT,
    ordem_servico TEXT,
    cep TEXT,
    estado TEXT,
    cidade TEXT,
    endereco TEXT,
    numero TEXT,
    latitude REAL,
    longitude REAL,
    agentes_aptos TEXT,
    id_agente INTEGER,
    equipe TEXT,
    responsavel TEXT,
    estabelecimento TEXT,
    hora_solicitada TIME,
    hora_local DATETIME,
    hora_saida DATETIME,
    status_atendimento TEXT CHECK(status_atendimento IN ('Em andamento','Finalizado')),
    tipo_de_servico TEXT CHECK(tipo_de_servico IN ('Ronda','Preservação')),
    tipos_de_dados TEXT,
    estabelecida_inicio TIME,
    estabelecida_fim TIME,
    indeterminado INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE SET NULL,
    FOREIGN KEY (id_agente) REFERENCES agentes(id_agente) ON DELETE SET NULL
);

-- Tabela de Rondas Periódicas
CREATE TABLE rondas_periodicas (
    id_ronda INTEGER PRIMARY KEY AUTOINCREMENT,
    id_atendimento INTEGER,
    quantidade_rondas INTEGER,
    data_final DATE,
    pagamento TEXT CHECK(pagamento IN ('Pago','Pendente')),
    contato_no_local TEXT CHECK(contato_no_local IN ('Sim','Não')),
    nome_local TEXT,
    funcao_local TEXT,
    verificado_fiacao TEXT CHECK(verificado_fiacao IN ('Sim','Não')),
    quadro_eletrico TEXT CHECK(quadro_eletrico IN ('Sim','Não')),
    verificado_portas_entradas TEXT CHECK(verificado_portas_entradas IN ('Sim','Não')),
    local_energizado TEXT CHECK(local_energizado IN ('Sim','Não')),
    sirene_disparada TEXT CHECK(sirene_disparada IN ('Sim','Não')),
    local_violado TEXT CHECK(local_violado IN ('Sim','Não')),
    observacao TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_atendimento) REFERENCES atendimentos(id_atendimento) ON DELETE CASCADE
);

-- Tabela de Fotos dos Atendimentos
CREATE TABLE fotos_atendimentos (
    id_foto INTEGER PRIMARY KEY AUTOINCREMENT,
    id_atendimento INTEGER,
    legenda TEXT,
    caminho_foto TEXT,
    data_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_atendimento) REFERENCES atendimentos(id_atendimento) ON DELETE CASCADE
);

-- Tabela de Ocorrências Veiculares
CREATE TABLE ocorrencias_veiculares (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    cliente TEXT,
    servico TEXT,
    id_validacao TEXT,
    valor_veicular REAL,
    cep TEXT,
    estado TEXT,
    cidade TEXT,
    solicitante TEXT,
    motivo TEXT,
    endereco_da_ocorrencia TEXT,
    numero TEXT,
    latitude REAL,
    longitude REAL,
    agentes_aptos TEXT,
    prestador TEXT,
    equipe TEXT,
    tipo_de_ocorrencia TEXT,
    data_hora_evento DATETIME,
    data_hora_deslocamento DATETIME,
    data_hora_transmissao DATETIME,
    data_hora_local DATETIME,
    data_hora_inicio_atendimento DATETIME,
    data_hora_fim_atendimento DATETIME,
    franquia_hora TIME,
    franquia_km REAL,
    km_inicial_atendimento REAL,
    km_final_atendimento REAL,
    total_horas_atendimento TIME,
    total_km_percorrido REAL,
    descricao_fatos TEXT,
    gastos_adicionais REAL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Vigilância Veicular
CREATE TABLE vigilancia_veicular (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    veiculo_foi_recuperado TEXT CHECK(veiculo_foi_recuperado IN ('Sim','Não')) NOT NULL,
    condutor_e_proprietario TEXT CHECK(condutor_e_proprietario IN ('Sim','Não')) NOT NULL,
    tipo_de_equipamento_embarcado TEXT,
    placa TEXT NOT NULL,
    renavam TEXT,
    cor TEXT,
    marca TEXT,
    modelo TEXT,
    cidade TEXT,
    dados_adicionais_veiculo TEXT,
    placa_carreta TEXT,
    renavam_carreta TEXT,
    cor_carreta TEXT,
    marca_carreta TEXT,
    modelo_carreta TEXT,
    cidade_carreta TEXT,
    dados_adicionais_carreta TEXT,
    nome_do_condutor TEXT,
    cpf_condutor TEXT,
    cnh_condutor TEXT,
    telefone_condutor TEXT,
    status_do_atendimento TEXT CHECK(status_do_atendimento IN ('Em andamento','Finalizado')) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Fotos da Vigilância Veicular
CREATE TABLE fotos_vigilancia_veicular (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vigilancia_id INTEGER,
    legenda TEXT,
    foto TEXT,
    data_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vigilancia_id) REFERENCES vigilancia_veicular(id) ON DELETE CASCADE
);

-- Inserção de dados iniciais
INSERT INTO configuracoes (chave, valor, descricao) VALUES 
('sistema_nome', 'Sistema de Monitoramento', 'Nome do sistema'),
('sistema_versao', '1.0.0', 'Versão do sistema'),
('sistema_autor', 'Julio Abreu', 'Autor do sistema'),
('tema_padrao', 'claro', 'Tema padrão do sistema'),
('max_upload_size', '5242880', 'Tamanho máximo de upload em bytes'),
('timezone', 'America/Sao_Paulo', 'Fuso horário do sistema');

-- Dados de exemplo para Clientes
INSERT INTO clientes (nome_empresa, cnpj, contato, endereco, telefone) VALUES
('Empresa ABC Ltda', '12.345.678/0001-90', 'João Silva', 'Rua das Flores, 123', '(11) 99999-9999'),
('Corporação XYZ S.A.', '98.765.432/0001-10', 'Maria Santos', 'Av. Principal, 456', '(11) 88888-8888'),
('Indústria DEF Ltda', '11.222.333/0001-44', 'Carlos Oliveira', 'Rua Industrial, 789', '(11) 77777-7777');

-- Dados de exemplo para Agentes
INSERT INTO agentes (nome, funcao, status) VALUES
('Carlos Oliveira', 'Agente de Segurança', 'Ativo'),
('Ana Costa', 'Supervisora', 'Ativo'),
('Pedro Almeida', 'Agente de Campo', 'Ativo'),
('Mariana Silva', 'Coordenadora', 'Ativo'),
('Roberto Santos', 'Agente de Ronda', 'Ativo');

-- Dados de exemplo para Prestadores
INSERT INTO tabela_prestadores (
    nome_prestador, equipes, servico_prestador, cpf_prestador, rg_prestador, 
    email_prestador, telefone_1_prestador, telefone_2_prestador, cep_prestador, 
    endereco_prestador, numero_prestador, bairro_prestador, cidade_prestador, 
    estado, observacao, codigo_do_banco, titular_conta, tipo_de_conta, 
    agencia_prestadores, conta_prestadores
) VALUES
('João da Silva', 'Equipe A', 'Segurança Patrimonial', '123.456.789-00', '12.345.678-9', 
 'joao@email.com', '(11) 99999-1111', '(11) 88888-1111', '01234-567', 
 'Rua das Palmeiras', '100', 'Centro', 'São Paulo', 'SP', 
 'Prestador experiente', '001', 'João da Silva', 'Conta Corrente', '1234', '56789-0'),

('Maria Santos', 'Equipe B', 'Vigilância', '987.654.321-00', '98.765.432-1', 
 'maria@email.com', '(11) 99999-2222', '(11) 88888-2222', '09876-543', 
 'Av. Paulista', '200', 'Bela Vista', 'São Paulo', 'SP', 
 'Especialista em vigilância', '237', 'Maria Santos', 'Poupança', '5678', '12345-6');

