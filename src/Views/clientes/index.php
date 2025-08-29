<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-building me-2 text-primary"></i>Clientes
        </h1>
        <p class="text-muted mb-0">Gerenciar clientes do sistema</p>
    </div>
    <a href="<?php echo BASE_URL; ?>clientes/criar" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo Cliente
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Nome, email ou CNPJ..." 
                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label for="cidade" class="form-label">Cidade</label>
                <select class="form-select" id="cidade" name="cidade">
                    <option value="">Todas as cidades</option>
                    <option value="São Paulo" <?php echo ($_GET['cidade'] ?? '') == 'São Paulo' ? 'selected' : ''; ?>>São Paulo</option>
                    <option value="Rio de Janeiro" <?php echo ($_GET['cidade'] ?? '') == 'Rio de Janeiro' ? 'selected' : ''; ?>>Rio de Janeiro</option>
                    <option value="Belo Horizonte" <?php echo ($_GET['cidade'] ?? '') == 'Belo Horizonte' ? 'selected' : ''; ?>>Belo Horizonte</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos os estados</option>
                    <option value="SP" <?php echo ($_GET['estado'] ?? '') == 'SP' ? 'selected' : ''; ?>>São Paulo</option>
                    <option value="RJ" <?php echo ($_GET['estado'] ?? '') == 'RJ' ? 'selected' : ''; ?>>Rio de Janeiro</option>
                    <option value="MG" <?php echo ($_GET['estado'] ?? '') == 'MG' ? 'selected' : ''; ?>>Minas Gerais</option>
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

<!-- Lista de Clientes -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($clientes)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NOME</th>
                        <th>CNPJ</th>
                        <th>EMAIL</th>
                        <th>TELEFONE</th>
                        <th>CIDADE/ESTADO</th>
                        <th width="120">AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <strong><?php echo htmlspecialchars($cliente['nome_cliente']); ?></strong>
                                    <?php if (!empty($cliente['nome_fantasia'])): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($cliente['nome_fantasia']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($cliente['cnpj_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['email_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefone_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['cidade_cliente'] . '/' . $cliente['estado_cliente']); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?php echo BASE_URL; ?>clientes/<?php echo $cliente['id']; ?>" 
                                   class="btn btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>clientes/<?php echo $cliente['id']; ?>/editar" 
                                   class="btn btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Excluir"
                                        onclick="confirmarExclusao(<?php echo $cliente['id']; ?>, '<?php echo htmlspecialchars($cliente['nome_cliente']); ?>')">
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
            <i class="bi bi-building display-1 text-muted"></i>
            <h4 class="mt-3">Nenhum cliente encontrado</h4>
            <p class="text-muted">Comece cadastrando o primeiro cliente do sistema.</p>
            <a href="<?php echo BASE_URL; ?>clientes/criar" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Cadastrar Cliente
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja realizar esta ação?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmButton">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarExclusao(id, nome) {
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    document.querySelector('#confirmModal .modal-body p').textContent = 
        `Tem certeza que deseja excluir o cliente "${nome}"?`;
    
    document.getElementById('confirmButton').onclick = function() {
        window.location.href = `<?php echo BASE_URL; ?>clientes/${id}/excluir`;
    };
    
    modal.show();
}

// Auto-submit do formulário de filtros
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const cidadeSelect = document.getElementById('cidade');
    const estadoSelect = document.getElementById('estado');
    
    let timeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
    
    cidadeSelect.addEventListener('change', function() {
        this.form.submit();
    });
    
    estadoSelect.addEventListener('change', function() {
        this.form.submit();
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

