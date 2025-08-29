<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-clipboard-check me-2 text-primary"></i>Atendimentos
        </h1>
        <p class="text-muted mb-0">Gerenciar atendimentos do sistema</p>
    </div>
    <a href="<?php echo BASE_URL; ?>atendimentos/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo Atendimento
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Cliente, prestador..." 
                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos os status</option>
                    <option value="pendente" <?php echo ($_GET['status'] ?? '') == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                    <option value="em_andamento" <?php echo ($_GET['status'] ?? '') == 'em_andamento' ? 'selected' : ''; ?>>Em Andamento</option>
                    <option value="concluido" <?php echo ($_GET['status'] ?? '') == 'concluido' ? 'selected' : ''; ?>>Concluído</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="data_inicio" class="form-label">Data Início</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                       value="<?php echo htmlspecialchars($_GET['data_inicio'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label for="data_fim" class="form-label">Data Fim</label>
                <input type="date" class="form-control" id="data_fim" name="data_fim" 
                       value="<?php echo htmlspecialchars($_GET['data_fim'] ?? ''); ?>">
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($atendimentos)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CLIENTE</th>
                        <th>PRESTADOR</th>
                        <th>DATA/HORA</th>
                        <th>STATUS</th>
                        <th>PRIORIDADE</th>
                        <th width="120">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($atendimentos as $atendimento): ?>
                    <tr>
                        <td>
                            <strong>#<?php echo str_pad($atendimento['id'], 4, '0', STR_PAD_LEFT); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($atendimento['cliente_nome'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($atendimento['prestador_nome'] ?? 'N/A'); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($atendimento['data_atendimento'] ?? 'now')); ?></td>
                        <td>
                            <?php
                            $status = $atendimento['status_atendimento'] ?? 'pendente';
                            $badgeClass = match($status) {
                                'concluido' => 'bg-success',
                                'em_andamento' => 'bg-warning',
                                'cancelado' => 'bg-danger',
                                default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                        </td>
                        <td>
                            <?php
                            $prioridade = $atendimento['prioridade'] ?? 'normal';
                            $priorityClass = match($prioridade) {
                                'alta' => 'bg-danger',
                                'media' => 'bg-warning',
                                default => 'bg-info'
                            };
                            ?>
                            <span class="badge <?php echo $priorityClass; ?>"><?php echo ucfirst($prioridade); ?></span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>atendimentos/<?php echo $atendimento['id']; ?>" 
                                   class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>atendimentos/<?php echo $atendimento['id']; ?>/editar" 
                                   class="btn btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Excluir"
                                        onclick="confirmarExclusao(<?php echo $atendimento['id']; ?>)">
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
            <i class="bi bi-clipboard-check display-1 text-muted"></i>
            <h4 class="mt-3">Nenhum atendimento encontrado</h4>
            <p class="text-muted">Comece cadastrando o primeiro atendimento do sistema.</p>
            <a href="<?php echo BASE_URL; ?>atendimentos/criar" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Novo Atendimento
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmarExclusao(id) {
    if (confirm(`Tem certeza que deseja excluir o atendimento #${id}?`)) {
        window.location.href = `<?php echo BASE_URL; ?>atendimentos/${id}/excluir`;
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

