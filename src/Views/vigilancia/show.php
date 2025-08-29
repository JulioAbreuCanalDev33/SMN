<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-eye me-2 text-primary"></i>Vigilância #<?php echo str_pad($vigilancia['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?>
        </h1>
        <p class="text-muted mb-0">Detalhes completos da vigilância veicular</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>vigilancia/<?php echo $vigilancia['id']; ?>/editar" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo BASE_URL; ?>vigilancia" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações da Vigilância
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Cliente</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($vigilancia['cliente_nome'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Placa do Veículo</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($vigilancia['placa_veiculo'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Data/Hora Início</label>
                        <p class="fw-bold"><?php echo date('d/m/Y H:i', strtotime($vigilancia['data_inicio'] ?? 'now')); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Data/Hora Fim</label>
                        <p class="fw-bold">
                            <?php echo !empty($vigilancia['data_fim']) ? date('d/m/Y H:i', strtotime($vigilancia['data_fim'])) : 'Em andamento'; ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Local</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($vigilancia['local_vigilancia'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tipo de Vigilância</label>
                        <p class="fw-bold">
                            <span class="badge bg-info fs-6"><?php echo ucfirst($vigilancia['tipo_vigilancia'] ?? 'Preventiva'); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-bold">
                            <?php
                            $status = $vigilancia['status_vigilancia'] ?? 'ativa';
                            $badgeClass = match($status) {
                                'finalizada' => 'bg-success',
                                'ativa' => 'bg-primary',
                                'pausada' => 'bg-warning',
                                'cancelada' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $badgeClass; ?> fs-6"><?php echo ucfirst($status); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Duração</label>
                        <p class="fw-bold">
                            <?php
                            if (!empty($vigilancia['data_fim'])) {
                                $inicio = new DateTime($vigilancia['data_inicio']);
                                $fim = new DateTime($vigilancia['data_fim']);
                                $duracao = $inicio->diff($fim);
                                echo $duracao->format('%h horas e %i minutos');
                            } else {
                                $inicio = new DateTime($vigilancia['data_inicio']);
                                $agora = new DateTime();
                                $duracao = $inicio->diff($agora);
                                echo $duracao->format('%h horas e %i minutos') . ' (em andamento)';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-chat-text me-2"></i>Descrição da Vigilância
                </h5>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($vigilancia['descricao_vigilancia'] ?? 'Sem descrição')); ?></p>
                
                <?php if (!empty($vigilancia['observacoes'])): ?>
                <hr>
                <h6 class="text-muted">Observações:</h6>
                <p><?php echo nl2br(htmlspecialchars($vigilancia['observacoes'])); ?></p>
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
                    <a href="<?php echo BASE_URL; ?>vigilancia/<?php echo $vigilancia['id']; ?>/editar" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Editar Vigilância
                    </a>
                    <a href="<?php echo BASE_URL; ?>vigilancia/criar" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Nova Vigilância
                    </a>
                    <?php if ($vigilancia['status_vigilancia'] == 'ativa'): ?>
                    <a href="<?php echo BASE_URL; ?>vigilancia/<?php echo $vigilancia['id']; ?>/finalizar" class="btn btn-warning">
                        <i class="bi bi-stop-circle me-2"></i>Finalizar
                    </a>
                    <?php endif; ?>
                    <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>Imprimir
                    </button>
                    <a href="<?php echo BASE_URL; ?>relatorios/vigilancia/<?php echo $vigilancia['id']; ?>" class="btn btn-outline-info">
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
                    <strong>ID:</strong> #<?php echo $vigilancia['id'] ?? '-'; ?><br>
                    <strong>Criada em:</strong> <?php echo date('d/m/Y H:i', strtotime($vigilancia['created_at'] ?? 'now')); ?><br>
                    <?php if (!empty($vigilancia['updated_at'])): ?>
                    <strong>Atualizada em:</strong> <?php echo date('d/m/Y H:i', strtotime($vigilancia['updated_at'])); ?><br>
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

