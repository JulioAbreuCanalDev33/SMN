<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-clipboard-check me-2 text-primary"></i>Atendimento #<?php echo str_pad($atendimento['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?>
        </h1>
        <p class="text-muted mb-0">Detalhes completos do atendimento</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>atendimentos/<?php echo $atendimento['id']; ?>/editar" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo BASE_URL; ?>atendimentos" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações do Atendimento
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Cliente</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($atendimento['cliente_nome'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Prestador</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($atendimento['prestador_nome'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Data/Hora</label>
                        <p class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($atendimento['data_atendimento'] ?? 'now')); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-bold">
                            <?php
                            $status = $atendimento['status_atendimento'] ?? 'pendente';
                            $badgeClass = match($status) {
                                'concluido' => 'bg-success',
                                'em_andamento' => 'bg-warning',
                                'cancelado' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> fs-6"><?php echo ucfirst($status); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Prioridade</label>
                        <p class="fw-bold">
                            <?php
                            $prioridade = $atendimento['prioridade'] ?? 'normal';
                            $priorityClass = match($prioridade) {
                                'alta' => 'bg-danger',
                                'urgente' => 'bg-danger',
                                'media' => 'bg-warning',
                                default => 'bg-info'
                            };
                            ?>
                            <span class="badge <?php echo $priorityClass; ?> fs-6"><?php echo ucfirst($prioridade); ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-chat-text me-2"></i>Descrição
                </h5>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($atendimento['descricao_atendimento'] ?? 'Sem descrição')); ?></p>
                
                <?php if (!empty($atendimento['observacoes'])): ?>
                <hr>
                <h6 class="text-muted">Observações:</h6>
                <p><?php echo nl2br(htmlspecialchars($atendimento['observacoes'])); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Fotos do Atendimento -->
        <?php if (!empty($atendimento['fotos'])): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-camera me-2"></i>Fotos do Atendimento
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($atendimento['fotos'] as $foto): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo BASE_URL . 'uploads/atendimentos/' . $foto['arquivo']; ?>" 
                                 class="card-img-top" style="height: 200px; object-fit: cover;" 
                                 alt="Foto do atendimento">
                            <?php if (!empty($foto['legenda'])): ?>
                            <div class="card-body p-2">
                                <small class="text-muted"><?php echo htmlspecialchars($foto['legenda']); ?></small>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
                    <a href="<?php echo BASE_URL; ?>atendimentos/<?php echo $atendimento['id']; ?>/editar" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Editar Atendimento
                    </a>
                    <a href="<?php echo BASE_URL; ?>atendimentos/criar" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Novo Atendimento
                    </a>
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Imprimir
                    </button>
                    <a href="<?php echo BASE_URL; ?>relatorios/atendimento/<?php echo $atendimento['id']; ?>" class="btn btn-outline-info">
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
                    <strong>ID:</strong> #<?php echo $atendimento['id'] ?? '-'; ?><br>
                    <strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($atendimento['created_at'] ?? 'now')); ?><br>
                    <?php if (!empty($atendimento['updated_at'])): ?>
                    <strong>Atualizado em:</strong> <?php echo date('d/m/Y H:i', strtotime($atendimento['updated_at'])); ?><br>
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

