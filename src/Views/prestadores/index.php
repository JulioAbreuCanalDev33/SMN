<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-person-badge me-2 text-primary"></i>Prestadores
        </h1>
        <p class="text-muted mb-0">Gerenciar prestadores de serviços</p>
    </div>
    <a href="<?php echo BASE_URL; ?>prestadores/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo Prestador
    </a>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Buscar</label>
                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Nome, email ou CPF...">
            </div>
            <div class="col-md-3">
                <label class="form-label">Serviço</label>
                <select class="form-select" name="servico">
                    <option value="">Todos os serviços</option>
                    <?php foreach ($servicos as $srv): ?>
                        <option value="<?php echo htmlspecialchars($srv); ?>" <?php echo $servico === $srv ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($srv); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select class="form-select" name="estado">
                    <option value="">Todos os estados</option>
                    <?php foreach ($estados as $est): ?>
                        <option value="<?php echo htmlspecialchars($est); ?>" <?php echo $estado === $est ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($est); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i>Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Prestadores -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (!empty($prestadores)): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Serviço</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Cidade/Estado</th>
                            <th width="120">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestadores as $prestador): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-person text-primary"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($prestador['nome_prestador']); ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($prestador['servico_prestador']); ?></td>
                                <td><?php echo htmlspecialchars($prestador['email_prestador']); ?></td>
                                <td><?php echo htmlspecialchars($prestador['telefone_1_prestador']); ?></td>
                                <td><?php echo htmlspecialchars($prestador['cidade_prestador'] . '/' . $prestador['estado']); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo BASE_URL; ?>prestadores/<?php echo $prestador['id']; ?>" 
                                           class="btn btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>prestadores/<?php echo $prestador['id']; ?>/editar" 
                                           class="btn btn-outline-secondary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                onclick="confirmDelete(<?php echo $prestador['id']; ?>)" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <?php if ($pagination['total_pages'] > 1): ?>
                <div class="card-footer bg-transparent">
                    <nav>
                        <ul class="pagination pagination-sm justify-content-center mb-0">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&search=<?php echo urlencode($search); ?>&servico=<?php echo urlencode($servico); ?>&estado=<?php echo urlencode($estado); ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i === $pagination['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&servico=<?php echo urlencode($servico); ?>&estado=<?php echo urlencode($estado); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&search=<?php echo urlencode($search); ?>&servico=<?php echo urlencode($servico); ?>&estado=<?php echo urlencode($estado); ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <h5 class="mt-3 text-muted">Nenhum prestador encontrado</h5>
                <p class="text-muted">Cadastre o primeiro prestador para começar.</p>
                <a href="<?php echo BASE_URL; ?>prestadores/criar" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Novo Prestador
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir este prestador?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.APP_CONFIG.baseUrl + 'prestadores/' + id + '/excluir';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

