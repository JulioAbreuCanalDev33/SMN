<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-person-gear me-2 text-primary"></i>Editar Agente
        </h1>
        <p class="text-muted mb-0">Alterar dados do agente: <strong><?php echo htmlspecialchars($agente['nome_agente'] ?? ''); ?></strong></p>
    </div>
    <a href="<?php echo BASE_URL; ?>agentes" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>agentes/<?php echo $agente['id']; ?>/atualizar" method="POST">
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="nome_agente" class="form-label">Nome Completo *</label>
                    <input type="text" class="form-control" id="nome_agente" name="nome_agente" 
                           value="<?php echo htmlspecialchars($agente['nome_agente'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="cpf_agente" class="form-label">CPF *</label>
                    <input type="text" class="form-control" id="cpf_agente" name="cpf_agente" 
                           value="<?php echo htmlspecialchars($agente['cpf_agente'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email_agente" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email_agente" name="email_agente" 
                           value="<?php echo htmlspecialchars($agente['email_agente'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="telefone_agente" class="form-label">Telefone *</label>
                    <input type="text" class="form-control" id="telefone_agente" name="telefone_agente" 
                           value="<?php echo htmlspecialchars($agente['telefone_agente'] ?? ''); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="equipe" class="form-label">Equipe</label>
                    <select class="form-select" id="equipe" name="equipe">
                        <option value="">Selecione a equipe</option>
                        <option value="A" <?php echo ($agente['equipe'] ?? '') == 'A' ? 'selected' : ''; ?>>Equipe A</option>
                        <option value="B" <?php echo ($agente['equipe'] ?? '') == 'B' ? 'selected' : ''; ?>>Equipe B</option>
                        <option value="C" <?php echo ($agente['equipe'] ?? '') == 'C' ? 'selected' : ''; ?>>Equipe C</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="funcao" class="form-label">Função</label>
                    <input type="text" class="form-control" id="funcao" name="funcao" 
                           value="<?php echo htmlspecialchars($agente['funcao'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Alterações
                </button>
                <a href="<?php echo BASE_URL; ?>agentes" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

