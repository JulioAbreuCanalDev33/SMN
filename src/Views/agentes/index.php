<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-people me-2 text-primary"></i>Agentes
        </h1>
        <p class="text-muted mb-0">Gerenciar agentes de campo</p>
    </div>
    <a href="<?php echo BASE_URL; ?>agentes/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo Agente
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Nome, email ou CPF..." 
                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos os status</option>
                    <option value="ativo" <?php echo ($_GET['status'] ?? '') == 'ativo' ? 'selected' : ''; ?>>Ativo</option>
                    <option value="inativo" <?php echo ($_GET['status'] ?? '') == 'inativo' ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="equipe" class="form-label">Equipe</label>
                <select class="form-select" id="equipe" name="equipe">
                    <option value="">Todas as equipes</option>
                    <option value="A" <?php echo ($_GET['equipe'] ?? '') == 'A' ? 'selected' : ''; ?>>Equipe A</option>
                    <option value="B" <?php echo ($_GET['equipe'] ?? '') == 'B' ? 'selected' : ''; ?>>Equipe B</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($agentes)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NOME</th>
                        <th>CPF</th>
                        <th>EMAIL</th>
                        <th>TELEFONE</th>
                        <th>EQUIPE</th>
                        <th>STATUS</th>
                        <th width="120">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agentes as $agente): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <i class="bi bi-person"></i>
                                </div>
                                <strong><?php echo htmlspecialchars($agente['nome_agente']); ?></strong>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($agente['cpf_agente']); ?></td>
                        <td><?php echo htmlspecialchars($agente['email_agente']); ?></td>
                        <td><?php echo htmlspecialchars($agente['telefone_agente']); ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo htmlspecialchars($agente['equipe'] ?? 'N/A'); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-success">Ativo</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>agentes/<?php echo $agente['id']; ?>" 
                                   class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>agentes/<?php echo $agente['id']; ?>/editar" 
                                   class="btn btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Excluir"
                                        onclick="confirmarExclusao(<?php echo $agente['id']; ?>, '<?php echo htmlspecialchars($agente['nome_agente']); ?>')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-people display-1 text-muted"></i>
            <h4 class="mt-3">Nenhum agente encontrado</h4>
            <p class="text-muted">Comece cadastrando o primeiro agente do sistema.</p>
            <a href="<?php echo BASE_URL; ?>agentes/criar" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Cadastrar Agente
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmarExclusao(id, nome) {
    if (confirm(`Tem certeza que deseja excluir o agente "${nome}"?`)) {
        window.location.href = `<?php echo BASE_URL; ?>agentes/${id}/excluir`;
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

