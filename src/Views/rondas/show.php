<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-shield-check me-2 text-primary"></i>Ronda #<?php echo str_pad($ronda['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?>
        </h1>
        <p class="text-muted mb-0">Detalhes completos da ronda periódica</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>rondas/<?php echo $ronda['id']; ?>/editar" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo BASE_URL; ?>rondas" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações da Ronda
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Cliente</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($ronda['cliente_nome'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Agente Responsável</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($ronda['agente_nome'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Data/Hora</label>
                        <p class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($ronda['data_ronda'] ?? 'now')); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tipo de Ronda</label>
                        <p class="fw-bold">
                            <span class="badge bg-info fs-6"><?php echo ucfirst($ronda['tipo_ronda'] ?? 'Diurna'); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-bold">
                            <?php
                            $status = $ronda['status_ronda'] ?? 'agendada';
                            $badgeClass = match($status) {
                                'concluida' => 'bg-success',
                                'em_andamento' => 'bg-warning',
                                'cancelada' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> fs-6"><?php echo ucfirst($status); ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-chat-text me-2"></i>Descrição da Ronda
                </h5>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($ronda['descricao_ronda'] ?? 'Sem descrição')); ?></p>
                
                <?php if (!empty($ronda['observacoes'])): ?>
                <hr>
                <h6 class="text-muted">Observações:</h6>
                <p><?php echo nl2br(htmlspecialchars($ronda['observacoes'])); ?></p>
                <?php endif; ?>
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
                    <a href="<?php echo BASE_URL; ?>rondas/<?php echo $ronda['id']; ?>/editar" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Editar Ronda
                    </a>
                    <a href="<?php echo BASE_URL; ?>rondas/criar" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Nova Ronda
                    </a>
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Imprimir
                    </button>
                    <a href="<?php echo BASE_URL; ?>relatorios/ronda/<?php echo $ronda['id']; ?>" class="btn btn-outline-info">
                        <i class="bi bi-file-pdf me-2"></i>Gerar PDF
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
                    <strong>ID:</strong> #<?php echo $ronda['id'] ?? '-'; ?><br>
                    <strong>Criada em:</strong> <?php echo date('d/m/Y H:i', strtotime($ronda['created_at'] ?? 'now')); ?><br>
                    <?php if (!empty($ronda['updated_at'])): ?>
                    <strong>Atualizada em:</strong> <?php echo date('d/m/Y H:i', strtotime($ronda['updated_at'])); ?><br>
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

