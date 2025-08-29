<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-person me-2 text-primary"></i><?php echo htmlspecialchars($agente['nome_agente'] ?? 'Agente'); ?>
        </h1>
        <p class="text-muted mb-0">Detalhes completos do agente</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>agentes/<?php echo $agente['id']; ?>/editar" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo BASE_URL; ?>agentes" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person me-2"></i>Dados Pessoais
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label text-muted">Nome Completo</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($agente['nome_agente'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">CPF</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($agente['cpf_agente'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="fw-bold">
                            <a href="mailto:<?php echo htmlspecialchars($agente['email_agente'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($agente['email_agente'] ?? '-'); ?>
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Telefone</label>
                        <p class="fw-bold">
                            <a href="tel:<?php echo htmlspecialchars($agente['telefone_agente'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($agente['telefone_agente'] ?? '-'); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-briefcase me-2"></i>Informações Profissionais
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Equipe</label>
                        <p class="fw-bold">
                            <span class="badge bg-info fs-6"><?php echo htmlspecialchars($agente['equipe'] ?? 'N/A'); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Função</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($agente['funcao'] ?? '-'); ?></p>
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
                    <a href="<?php echo BASE_URL; ?>agentes/<?php echo $agente['id']; ?>/editar" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Editar Dados
                    </a>
                    <a href="<?php echo BASE_URL; ?>atendimentos/criar?agente=<?php echo $agente['id']; ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Novo Atendimento
                    </a>
                    <a href="mailto:<?php echo htmlspecialchars($agente['email_agente'] ?? ''); ?>" class="btn btn-outline-primary">
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
                    <strong>ID:</strong> #<?php echo $agente['id'] ?? '-'; ?><br>
                    <strong>Cadastrado em:</strong> <?php echo date('d/m/Y H:i', strtotime($agente['created_at'] ?? 'now')); ?><br>
                </small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

