<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-building-gear me-2 text-primary"></i>Editar Cliente
        </h1>
        <p class="text-muted mb-0">Alterar dados do cliente: <strong><?php echo htmlspecialchars($cliente['nome_cliente'] ?? ''); ?></strong></p>
    </div>
    <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>clientes/<?php echo $cliente['id']; ?>/atualizar" method="POST">
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="nome_cliente" class="form-label">Razão Social *</label>
                    <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" 
                           value="<?php echo htmlspecialchars($cliente['nome_cliente'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="cnpj_cliente" class="form-label">CNPJ *</label>
                    <input type="text" class="form-control" id="cnpj_cliente" name="cnpj_cliente" 
                           value="<?php echo htmlspecialchars($cliente['cnpj_cliente'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email_cliente" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email_cliente" name="email_cliente" 
                           value="<?php echo htmlspecialchars($cliente['email_cliente'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="telefone_cliente" class="form-label">Telefone *</label>
                    <input type="text" class="form-control" id="telefone_cliente" name="telefone_cliente" 
                           value="<?php echo htmlspecialchars($cliente['telefone_cliente'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="endereco_cliente" class="form-label">Endereço *</label>
                    <input type="text" class="form-control" id="endereco_cliente" name="endereco_cliente" 
                           value="<?php echo htmlspecialchars($cliente['endereco_cliente'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="cidade_cliente" class="form-label">Cidade *</label>
                    <input type="text" class="form-control" id="cidade_cliente" name="cidade_cliente" 
                           value="<?php echo htmlspecialchars($cliente['cidade_cliente'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                </button>
                <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

