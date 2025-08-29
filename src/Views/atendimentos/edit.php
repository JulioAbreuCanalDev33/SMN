<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-clipboard-gear me-2 text-primary"></i>Editar Atendimento
        </h1>
        <p class="text-muted mb-0">Alterar dados do atendimento #<?php echo str_pad($atendimento['id'] ?? '0', 4, '0', STR_PAD_LEFT); ?></p>
    </div>
    <a href="<?php echo BASE_URL; ?>atendimentos" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>atendimentos/<?php echo $atendimento['id']; ?>/atualizar" method="POST" enctype="multipart/form-data">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_cliente" class="form-label">Cliente *</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Selecione o cliente</option>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>" 
                                        <?php echo ($atendimento['id_cliente'] ?? '') == $cliente['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cliente['nome_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="id_prestador" class="form-label">Prestador *</label>
                    <select class="form-select" id="id_prestador" name="id_prestador" required>
                        <option value="">Selecione o prestador</option>
                        <?php if (!empty($prestadores)): ?>
                            <?php foreach ($prestadores as $prestador): ?>
                                <option value="<?php echo $prestador['id']; ?>"
                                        <?php echo ($atendimento['id_prestador'] ?? '') == $prestador['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($prestador['nome_prestador']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="data_atendimento" class="form-label">Data do Atendimento *</label>
                    <input type="datetime-local" class="form-control" id="data_atendimento" name="data_atendimento" 
                           value="<?php echo date('Y-m-d\TH:i', strtotime($atendimento['data_atendimento'] ?? 'now')); ?>" required>
                </div>
                
                <div class="col-md-4">
                    <label for="status_atendimento" class="form-label">Status *</label>
                    <select class="form-select" id="status_atendimento" name="status_atendimento" required>
                        <option value="pendente" <?php echo ($atendimento['status_atendimento'] ?? '') == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="em_andamento" <?php echo ($atendimento['status_atendimento'] ?? '') == 'em_andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                        <option value="concluido" <?php echo ($atendimento['status_atendimento'] ?? '') == 'concluido' ? 'selected' : ''; ?>>Concluído</option>
                        <option value="cancelado" <?php echo ($atendimento['status_atendimento'] ?? '') == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label for="prioridade" class="form-label">Prioridade</label>
                    <select class="form-select" id="prioridade" name="prioridade">
                        <option value="baixa" <?php echo ($atendimento['prioridade'] ?? '') == 'baixa' ? 'selected' : ''; ?>>Baixa</option>
                        <option value="normal" <?php echo ($atendimento['prioridade'] ?? '') == 'normal' ? 'selected' : ''; ?>>Normal</option>
                        <option value="alta" <?php echo ($atendimento['prioridade'] ?? '') == 'alta' ? 'selected' : ''; ?>>Alta</option>
                        <option value="urgente" <?php echo ($atendimento['prioridade'] ?? '') == 'urgente' ? 'selected' : ''; ?>>Urgente</option>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="descricao_atendimento" class="form-label">Descrição *</label>
                    <textarea class="form-control" id="descricao_atendimento" name="descricao_atendimento" 
                              rows="4" required><?php echo htmlspecialchars($atendimento['descricao_atendimento'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" 
                              rows="3"><?php echo htmlspecialchars($atendimento['observacoes'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                </button>
                <a href="<?php echo BASE_URL; ?>atendimentos/<?php echo $atendimento['id']; ?>" class="btn btn-outline-info">
                    <i class="bi bi-eye me-1"></i>Visualizar
                </a>
                <a href="<?php echo BASE_URL; ?>atendimentos" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

