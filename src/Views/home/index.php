<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard
        </h1>
        <p class="text-muted mb-0">Visão geral do sistema de monitoramento</p>
    </div>
    <div>
        <span class="badge bg-success fs-6">
            <i class="bi bi-circle-fill me-1"></i>Sistema Online
        </span>
    </div>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-person-badge fs-4 text-primary"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Prestadores</h6>
                        <h3 class="mb-0"><?php echo number_format($estatisticas['prestadores']); ?></h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> Ativos
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-building fs-4 text-success"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Clientes</h6>
                        <h3 class="mb-0"><?php echo number_format($estatisticas['clientes']); ?></h3>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> Cadastrados
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-person-check fs-4 text-info"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Agentes</h6>
                        <h3 class="mb-0"><?php echo number_format($estatisticas['agentes']); ?></h3>
                        <small class="text-info">
                            <i class="bi bi-people"></i> Equipe
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-headset fs-4 text-warning"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="card-title text-muted mb-1">Atendimentos</h6>
                        <h3 class="mb-0"><?php echo number_format($estatisticas['atendimentos']); ?></h3>
                        <small class="text-warning">
                            <i class="bi bi-clock"></i> Total
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos e Informações -->
<div class="row g-4 mb-4">
    <!-- Gráfico de Atendimentos por Status -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Atendimentos por Status
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Gráfico de Atendimentos por Mês -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-0 pb-0">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Atendimentos por Mês
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabelas de Informações -->
<div class="row g-4">
    <!-- Atendimentos Recentes -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>Atendimentos Recentes
                </h5>
                <a href="<?php echo BASE_URL; ?>atendimentos" class="btn btn-sm btn-outline-primary">
                    Ver Todos <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($atendimentos_recentes)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Solicitante</th>
                                    <th>Cliente</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($atendimentos_recentes as $atendimento): ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">#<?php echo $atendimento['id_atendimento']; ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($atendimento['solicitante']); ?></td>
                                        <td><?php echo htmlspecialchars($atendimento['cliente_nome']); ?></td>
                                        <td>
                                            <?php if ($atendimento['status_atendimento'] === 'Em andamento'): ?>
                                                <span class="badge bg-warning">Em Andamento</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Finalizado</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($atendimento['created_at'])); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>atendimentos/<?php echo $atendimento['id_atendimento']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox display-4 text-muted"></i>
                        <p class="text-muted mt-2">Nenhum atendimento encontrado</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Alertas e Notificações -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0">
                    <i class="bi bi-exclamation-triangle me-2"></i>Alertas
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($rondas_atencao)): ?>
                    <?php foreach (array_slice($rondas_atencao, 0, 5) as $ronda): ?>
                        <div class="alert alert-warning alert-sm mb-2" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <div class="flex-grow-1">
                                    <strong>Ronda #<?php echo $ronda['id_ronda']; ?></strong><br>
                                    <small><?php echo htmlspecialchars($ronda['cliente_nome']); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-check-circle display-6 text-success"></i>
                        <p class="text-muted mt-2 mb-0">Tudo em ordem!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Estatísticas Rápidas -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Estatísticas Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-primary mb-1"><?php echo $estatisticas['rondas']; ?></h4>
                            <small class="text-muted">Rondas</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-info mb-1"><?php echo $estatisticas['ocorrencias']; ?></h4>
                            <small class="text-muted">Ocorrências</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-warning mb-1"><?php echo $estatisticas['vigilancias']; ?></h4>
                            <small class="text-muted">Vigilâncias</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-success mb-1"><?php echo $estatisticas_atendimentos['em_andamento'] ?? 0; ?></h4>
                            <small class="text-muted">Em Andamento</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos da página -->
<script>
    // Dados para os gráficos
    const statusData = <?php echo json_encode($dados_graficos['atendimentos_por_status'] ?? []); ?>;
    const monthData = <?php echo json_encode($dados_graficos['atendimentos_por_mes'] ?? []); ?>;
    
    // Gráfico de Status
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.status_atendimento),
            datasets: [{
                data: statusData.map(item => item.total),
                backgroundColor: [
                    '#ffc107',
                    '#198754',
                    '#dc3545',
                    '#0d6efd'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de Meses
    const monthCtx = document.getElementById('monthChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'line',
        data: {
            labels: monthData.map(item => {
                const date = new Date(item.mes + '-01');
                return date.toLocaleDateString('pt-BR', { month: 'short', year: 'numeric' });
            }),
            datasets: [{
                label: 'Atendimentos',
                data: monthData.map(item => item.total),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

