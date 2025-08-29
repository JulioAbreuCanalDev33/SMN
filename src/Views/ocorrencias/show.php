<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-exclamation-triangle me-2 text-primary"></i>Ocorrência #<?php echo str_pad($ocorrencia['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?>
        </h1>
        <p class="text-muted mb-0">Detalhes completos da ocorrência veicular</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>ocorrencias/<?php echo $ocorrencia['id']; ?>/editar" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo BASE_URL; ?>ocorrencias" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações da Ocorrência
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Cliente</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($ocorrencia['cliente_nome'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Placa do Veículo</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($ocorrencia['placa_veiculo'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Data/Hora</label>
                        <p class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($ocorrencia['data_ocorrencia'] ?? 'now')); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tipo de Ocorrência</label>
                        <p class="fw-bold">
                            <span class="badge bg-warning fs-6"><?php echo ucfirst($ocorrencia['tipo_ocorrencia'] ?? 'N/A'); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-bold">
                            <?php
                            $status = $ocorrencia['status_ocorrencia'] ?? 'registrada';
                            $badgeClass = match($status) {
                                'resolvida' => 'bg-success',
                                'investigando' => 'bg-warning',
                                'arquivada' => 'bg-secondary',
                                default => 'bg-danger'
                            };
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> fs-6"><?php echo ucfirst($status); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Local da Ocorrência</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($ocorrencia['local_ocorrencia'] ?? '-'); ?></p>
                    </div>
                    <?php if (!empty($ocorrencia['valor_prejuizo'])): ?>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Valor do Prejuízo</label>
                        <p class="fw-bold text-danger">R$ <?php echo number_format($ocorrencia['valor_prejuizo'], 2, ',', '.'); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-chat-text me-2"></i>Descrição da Ocorrência
                </h5>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($ocorrencia['descricao_ocorrencia'] ?? 'Sem descrição')); ?></p>
                
                <?php if (!empty($ocorrencia['observacoes'])): ?>
                <hr>
                <h6 class="text-muted">Observações:</h6>
                <p><?php echo nl2br(htmlspecialchars($ocorrencia['observacoes'])); ?></p>
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
                    <a href="<?php echo BASE_URL; ?>ocorrencias/<?php echo $ocorrencia['id']; ?>/editar" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Editar Ocorrência
                    </a>
                    <a href="<?php echo BASE_URL; ?>ocorrencias/criar" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Nova Ocorrência
                    </a>
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Imprimir
                    </button>
                    <a href="<?php echo BASE_URL; ?>relatorios/ocorrencia/<?php echo $ocorrencia['id']; ?>" class="btn btn-outline-info">
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
                    <strong>ID:</strong> #<?php echo $ocorrencia['id'] ?? '-'; ?><br>
                    <strong>Registrada em:</strong> <?php echo date('d/m/Y H:i', strtotime($ocorrencia['created_at'] ?? 'now')); ?><br>
                    <?php if (!empty($ocorrencia['updated_at'])): ?>
                    <strong>Atualizada em:</strong> <?php echo date('d/m/Y H:i', strtotime($ocorrencia['updated_at'])); ?><br>
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

