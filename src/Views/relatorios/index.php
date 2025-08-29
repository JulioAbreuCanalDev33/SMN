<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-file-earmark-text me-2 text-primary"></i>Relatórios
        </h1>
        <p class="text-muted mb-0">Gerar relatórios do sistema</p>
    </div>
</div>

<div class="row">
    <!-- Filtros -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>Filtros
                </h5>
            </div>
            <div class="card-body">
                <form id="filtrosRelatorio">
                    <div class="mb-3">
                        <label for="tipo_relatorio" class="form-label">Tipo de Relatório</label>
                        <select class="form-select" id="tipo_relatorio" name="tipo_relatorio" required>
                            <option value="">Selecione o tipo</option>
                            <option value="atendimentos">Atendimentos</option>
                            <option value="prestadores">Prestadores</option>
                            <option value="clientes">Clientes</option>
                            <option value="agentes">Agentes</option>
                            <option value="rondas">Rondas Periódicas</option>
                            <option value="ocorrencias">Ocorrências Veiculares</option>
                            <option value="vigilancia">Vigilância Veicular</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="data_inicio" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" 
                               value="<?php echo date('Y-m-01'); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="data_fim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim" 
                               value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Todos os status</option>
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                            <option value="pendente">Pendente</option>
                            <option value="concluido">Concluído</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="formato" class="form-label">Formato</label>
                        <select class="form-select" id="formato" name="formato" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-download me-1"></i>Gerar Relatório
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="visualizarPreview()">
                            <i class="bi bi-eye me-1"></i>Visualizar Preview
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Preview -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-eye me-2"></i>Preview do Relatório
                </h5>
            </div>
            <div class="card-body">
                <div id="previewArea" class="text-center py-5">
                    <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                    <h4 class="mt-3">Selecione os filtros e clique em "Visualizar Preview"</h4>
                    <p class="text-muted">O preview do relatório será exibido aqui antes da geração final.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Relatórios Rápidos -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Relatórios Rápidos
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="bi bi-clipboard-check display-6 text-primary"></i>
                                <h6 class="mt-2">Atendimentos do Mês</h6>
                                <div class="d-grid gap-1">
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=atendimentos&periodo=mes&formato=pdf" 
                                       class="btn btn-sm btn-outline-primary">PDF</a>
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=atendimentos&periodo=mes&formato=excel" 
                                       class="btn btn-sm btn-outline-success">Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="bi bi-people display-6 text-success"></i>
                                <h6 class="mt-2">Prestadores Ativos</h6>
                                <div class="d-grid gap-1">
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=prestadores&status=ativo&formato=pdf" 
                                       class="btn btn-sm btn-outline-primary">PDF</a>
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=prestadores&status=ativo&formato=excel" 
                                       class="btn btn-sm btn-outline-success">Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="bi bi-building display-6 text-info"></i>
                                <h6 class="mt-2">Clientes Cadastrados</h6>
                                <div class="d-grid gap-1">
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=clientes&formato=pdf" 
                                       class="btn btn-sm btn-outline-primary">PDF</a>
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=clientes&formato=excel" 
                                       class="btn btn-sm btn-outline-success">Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="bi bi-shield-check display-6 text-warning"></i>
                                <h6 class="mt-2">Rondas do Mês</h6>
                                <div class="d-grid gap-1">
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=rondas&periodo=mes&formato=pdf" 
                                       class="btn btn-sm btn-outline-primary">PDF</a>
                                    <a href="<?php echo BASE_URL; ?>relatorios/gerar?tipo=rondas&periodo=mes&formato=excel" 
                                       class="btn btn-sm btn-outline-success">Excel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('filtrosRelatorio').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    // Gerar relatório
    window.open(`<?php echo BASE_URL; ?>relatorios/gerar?${params.toString()}`, '_blank');
});

function visualizarPreview() {
    const tipoRelatorio = document.getElementById('tipo_relatorio').value;
    const dataInicio = document.getElementById('data_inicio').value;
    const dataFim = document.getElementById('data_fim').value;
    
    if (!tipoRelatorio) {
        alert('Selecione o tipo de relatório');
        return;
    }
    
    const previewArea = document.getElementById('previewArea');
    previewArea.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Gerando preview...</p>
        </div>
    `;
    
    // Simular carregamento do preview
    setTimeout(() => {
        previewArea.innerHTML = `
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome/Descrição</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#001</td>
                            <td>Exemplo de registro</td>
                            <td>${new Date().toLocaleDateString('pt-BR')}</td>
                            <td><span class="badge bg-success">Ativo</span></td>
                        </tr>
                        <tr>
                            <td>#002</td>
                            <td>Outro exemplo</td>
                            <td>${new Date().toLocaleDateString('pt-BR')}</td>
                            <td><span class="badge bg-warning">Pendente</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-2"></i>
                Este é um preview do relatório de <strong>${tipoRelatorio}</strong> 
                ${dataInicio ? `de ${new Date(dataInicio).toLocaleDateString('pt-BR')}` : ''} 
                ${dataFim ? `até ${new Date(dataFim).toLocaleDateString('pt-BR')}` : ''}.
            </div>
        `;
    }, 1500);
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

