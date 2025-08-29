<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-building me-2 text-primary"></i><?php echo htmlspecialchars($cliente['nome_cliente'] ?? 'Cliente'); ?>
        </h1>
        <p class="text-muted mb-0">Detalhes completos do cliente</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>clientes/<?php echo $cliente['id']; ?>/editar" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-building me-2"></i>Dados da Empresa
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label text-muted">Razão Social</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($cliente['nome_cliente'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">CNPJ</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($cliente['cnpj_cliente'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="fw-bold">
                            <a href="mailto:<?php echo htmlspecialchars($cliente['email_cliente'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($cliente['email_cliente'] ?? '-'); ?>
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Telefone</label>
                        <p class="fw-bold">
                            <a href="tel:<?php echo htmlspecialchars($cliente['telefone_cliente'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($cliente['telefone_cliente'] ?? '-'); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-geo-alt me-2"></i>Endereço
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label text-muted">Endereço Completo</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($cliente['endereco_cliente'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Cidade</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($cliente['cidade_cliente'] ?? '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo BASE_URL; ?>clientes/<?php echo $cliente['id']; ?>/editar" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Editar Dados
                    </a>
                    <a href="<?php echo BASE_URL; ?>atendimentos/criar?cliente=<?php echo $cliente['id']; ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Novo Atendimento
                    </a>
                    <a href="mailto:<?php echo htmlspecialchars($cliente['email_cliente'] ?? ''); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Enviar Email
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações do Sistema
                </h5>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <strong>ID:</strong> #<?php echo $cliente['id'] ?? '-'; ?><br>
                    <strong>Cadastrado em:</strong> <?php echo date('d/m/Y H:i', strtotime($cliente['created_at'] ?? 'now')); ?><br>
                </small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

