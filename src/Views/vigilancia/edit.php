<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-eye-gear me-2 text-primary"></i>Editar Vigilância
        </h1>
        <p class="text-muted mb-0">Alterar dados da vigilância #<?php echo str_pad($vigilancia['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?></p>
    </div>
    <a href="<?php echo BASE_URL; ?>vigilancia" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>vigilancia/<?php echo $vigilancia['id']; ?>/atualizar" method="POST">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_cliente" class="form-label">Cliente *</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Selecione o cliente</option>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>" 
                                        <?php echo ($vigilancia['id_cliente'] ?? '') == $cliente['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cliente['nome_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="placa_veiculo" class="form-label">Placa do Veículo *</label>
                    <input type="text" class="form-control" id="placa_veiculo" name="placa_veiculo" 
                           value="<?php echo htmlspecialchars($vigilancia['placa_veiculo'] ?? ''); ?>" 
                           placeholder="ABC-1234" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="data_inicio" class="form-label">Data/Hora Início *</label>
                    <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" 
                           value="<?php echo date('Y-m-d\TH:i', strtotime($vigilancia['data_inicio'] ?? 'now')); ?>" required>
                </div>
                
                <div class="col-md-4">
                    <label for="data_fim" class="form-label">Data/Hora Fim</label>
                    <input type="datetime-local" class="form-control" id="data_fim" name="data_fim" 
                           value="<?php echo !empty($vigilancia['data_fim']) ? date('Y-m-d\TH:i', strtotime($vigilancia['data_fim'])) : ''; ?>">
                </div>
                
                <div class="col-md-4">
                    <label for="status_vigilancia" class="form-label">Status *</label>
                    <select class="form-select" id="status_vigilancia" name="status_vigilancia" required>
                        <option value="ativa" <?php echo ($vigilancia['status_vigilancia'] ?? '') == 'ativa' ? 'selected' : ''; ?>>Ativa</option>
                        <option value="pausada" <?php echo ($vigilancia['status_vigilancia'] ?? '') == 'pausada' ? 'selected' : ''; ?>>Pausada</option>
                        <option value="finalizada" <?php echo ($vigilancia['status_vigilancia'] ?? '') == 'finalizada' ? 'selected' : ''; ?>>Finalizada</option>
                        <option value="cancelada" <?php echo ($vigilancia['status_vigilancia'] ?? '') == 'cancelada' ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="local_vigilancia" class="form-label">Local da Vigilância *</label>
                    <input type="text" class="form-control" id="local_vigilancia" name="local_vigilancia" 
                           value="<?php echo htmlspecialchars($vigilancia['local_vigilancia'] ?? ''); ?>" required>
                </div>
                
                <div class="col-md-6">
                    <label for="tipo_vigilancia" class="form-label">Tipo de Vigilância</label>
                    <select class="form-select" id="tipo_vigilancia" name="tipo_vigilancia">
                        <option value="preventiva" <?php echo ($vigilancia['tipo_vigilancia'] ?? '') == 'preventiva' ? 'selected' : ''; ?>>Preventiva</option>
                        <option value="investigativa" <?php echo ($vigilancia['tipo_vigilancia'] ?? '') == 'investigativa' ? 'selected' : ''; ?>>Investigativa</option>
                        <option value="recuperacao" <?php echo ($vigilancia['tipo_vigilancia'] ?? '') == 'recuperacao' ? 'selected' : ''; ?>>Recuperação</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="descricao_vigilancia" class="form-label">Descrição da Vigilância *</label>
                    <textarea class="form-control" id="descricao_vigilancia" name="descricao_vigilancia" 
                              rows="4" required><?php echo htmlspecialchars($vigilancia['descricao_vigilancia'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" 
                              rows="3"><?php echo htmlspecialchars($vigilancia['observacoes'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                </button>
                <a href="<?php echo BASE_URL; ?>vigilancia/<?php echo $vigilancia['id']; ?>" class="btn btn-outline-info">
                    <i class="bi bi-eye me-1"></i>Visualizar
                </a>
                <a href="<?php echo BASE_URL; ?>vigilancia" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

