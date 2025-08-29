<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-exclamation-triangle-gear me-2 text-primary"></i>Editar Ocorrência
        </h1>
        <p class="text-muted mb-0">Alterar dados da ocorrência #<?php echo str_pad($ocorrencia['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?></p>
    </div>
    <a href="<?php echo BASE_URL; ?>ocorrencias" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>ocorrencias/<?php echo $ocorrencia['id']; ?>/atualizar" method="POST">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_cliente" class="form-label">Cliente *</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Selecione o cliente</option>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>" 
                                        <?php echo ($ocorrencia['id_cliente'] ?? '') == $cliente['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cliente['nome_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="placa_veiculo" class="form-label">Placa do Veículo *</label>
                    <input type="text" class="form-control" id="placa_veiculo" name="placa_veiculo" 
                           value="<?php echo htmlspecialchars($ocorrencia['placa_veiculo'] ?? ''); ?>" 
                           placeholder="ABC-1234" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="data_ocorrencia" class="form-label">Data da Ocorrência *</label>
                    <input type="datetime-local" class="form-control" id="data_ocorrencia" name="data_ocorrencia" 
                           value="<?php echo date('Y-m-d\TH:i', strtotime($ocorrencia['data_ocorrencia'] ?? 'now')); ?>" required>
                </div>
                
                <div class="col-md-4">
                    <label for="tipo_ocorrencia" class="form-label">Tipo de Ocorrência *</label>
                    <select class="form-select" id="tipo_ocorrencia" name="tipo_ocorrencia" required>
                        <option value="furto" <?php echo ($ocorrencia['tipo_ocorrencia'] ?? '') == 'furto' ? 'selected' : ''; ?>>Furto</option>
                        <option value="roubo" <?php echo ($ocorrencia['tipo_ocorrencia'] ?? '') == 'roubo' ? 'selected' : ''; ?>>Roubo</option>
                        <option value="vandalismo" <?php echo ($ocorrencia['tipo_ocorrencia'] ?? '') == 'vandalismo' ? 'selected' : ''; ?>>Vandalismo</option>
                        <option value="acidente" <?php echo ($ocorrencia['tipo_ocorrencia'] ?? '') == 'acidente' ? 'selected' : ''; ?>>Acidente</option>
                        <option value="outros" <?php echo ($ocorrencia['tipo_ocorrencia'] ?? '') == 'outros' ? 'selected' : ''; ?>>Outros</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="status_ocorrencia" class="form-label">Status *</label>
                    <select class="form-select" id="status_ocorrencia" name="status_ocorrencia" required>
                        <option value="registrada" <?php echo ($ocorrencia['status_ocorrencia'] ?? '') == 'registrada' ? 'selected' : ''; ?>>Registrada</option>
                        <option value="investigando" <?php echo ($ocorrencia['status_ocorrencia'] ?? '') == 'investigando' ? 'selected' : ''; ?>>Investigando</option>
                        <option value="resolvida" <?php echo ($ocorrencia['status_ocorrencia'] ?? '') == 'resolvida' ? 'selected' : ''; ?>>Resolvida</option>
                        <option value="arquivada" <?php echo ($ocorrencia['status_ocorrencia'] ?? '') == 'arquivada' ? 'selected' : ''; ?>>Arquivada</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="descricao_ocorrencia" class="form-label">Descrição da Ocorrência *</label>
                    <textarea class="form-control" id="descricao_ocorrencia" name="descricao_ocorrencia" 
                              rows="4" required><?php echo htmlspecialchars($ocorrencia['descricao_ocorrencia'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="local_ocorrencia" class="form-label">Local da Ocorrência</label>
                    <input type="text" class="form-control" id="local_ocorrencia" name="local_ocorrencia" 
                           value="<?php echo htmlspecialchars($ocorrencia['local_ocorrencia'] ?? ''); ?>">
                </div>
                
                <div class="col-md-6">
                    <label for="valor_prejuizo" class="form-label">Valor do Prejuízo (R$)</label>
                    <input type="number" class="form-control" id="valor_prejuizo" name="valor_prejuizo" 
                           value="<?php echo htmlspecialchars($ocorrencia['valor_prejuizo'] ?? ''); ?>" 
                           step="0.01" min="0">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" 
                              rows="3"><?php echo htmlspecialchars($ocorrencia['observacoes'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                </button>
                <a href="<?php echo BASE_URL; ?>ocorrencias/<?php echo $ocorrencia['id']; ?>" class="btn btn-outline-info">
                    <i class="bi bi-eye me-1"></i>Visualizar
                </a>
                <a href="<?php echo BASE_URL; ?>ocorrencias" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

