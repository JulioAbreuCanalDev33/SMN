<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-shield-gear me-2 text-primary"></i>Editar Ronda
        </h1>
        <p class="text-muted mb-0">Alterar dados da ronda #<?php echo str_pad($ronda['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?></p>
    </div>
    <a href="<?php echo BASE_URL; ?>rondas" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>rondas/<?php echo $ronda['id']; ?>/atualizar" method="POST">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_cliente" class="form-label">Cliente *</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Selecione o cliente</option>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>" 
                                        <?php echo ($ronda['id_cliente'] ?? '') == $cliente['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cliente['nome_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="id_agente" class="form-label">Agente *</label>
                    <select class="form-select" id="id_agente" name="id_agente" required>
                        <option value="">Selecione o agente</option>
                        <?php if (!empty($agentes)): ?>
                            <?php foreach ($agentes as $agente): ?>
                                <option value="<?php echo $agente['id']; ?>"
                                        <?php echo ($ronda['id_agente'] ?? '') == $agente['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($agente['nome_agente']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="data_ronda" class="form-label">Data da Ronda *</label>
                    <input type="datetime-local" class="form-control" id="data_ronda" name="data_ronda" 
                           value="<?php echo date('Y-m-d\TH:i', strtotime($ronda['data_ronda'] ?? 'now')); ?>" required>
                </div>
                
                <div class="col-md-4">
                    <label for="status_ronda" class="form-label">Status *</label>
                    <select class="form-select" id="status_ronda" name="status_ronda" required>
                        <option value="agendada" <?php echo ($ronda['status_ronda'] ?? '') == 'agendada' ? 'selected' : ''; ?>>Agendada</option>
                        <option value="em_andamento" <?php echo ($ronda['status_ronda'] ?? '') == 'em_andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                        <option value="concluida" <?php echo ($ronda['status_ronda'] ?? '') == 'concluida' ? 'selected' : ''; ?>>Concluída</option>
                        <option value="cancelada" <?php echo ($ronda['status_ronda'] ?? '') == 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="tipo_ronda" class="form-label">Tipo de Ronda</label>
                    <select class="form-select" id="tipo_ronda" name="tipo_ronda">
                        <option value="diurna" <?php echo ($ronda['tipo_ronda'] ?? '') == 'diurna' ? 'selected' : ''; ?>>Diurna</option>
                        <option value="noturna" <?php echo ($ronda['tipo_ronda'] ?? '') == 'noturna' ? 'selected' : ''; ?>>Noturna</option>
                        <option value="madrugada" <?php echo ($ronda['tipo_ronda'] ?? '') == 'madrugada' ? 'selected' : ''; ?>>Madrugada</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="descricao_ronda" class="form-label">Descrição da Ronda *</label>
                    <textarea class="form-control" id="descricao_ronda" name="descricao_ronda" 
                              rows="4" required><?php echo htmlspecialchars($ronda['descricao_ronda'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" 
                              rows="3"><?php echo htmlspecialchars($ronda['observacoes'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                </button>
                <a href="<?php echo BASE_URL; ?>rondas/<?php echo $ronda['id']; ?>" class="btn btn-outline-info">
                    <i class="bi bi-eye me-1"></i>Visualizar
                </a>
                <a href="<?php echo BASE_URL; ?>rondas" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

