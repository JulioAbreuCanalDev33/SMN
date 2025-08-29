<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-person me-2 text-primary"></i><?php echo htmlspecialchars($prestador['nome_prestador'] ?? 'Prestador'); ?>
        </h1>
        <p class="text-muted mb-0">Detalhes completos do prestador</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>prestadores/<?php echo $prestador['id']; ?>/editar" class="btn btn-primary">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="<?php echo BASE_URL; ?>prestadores" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="row">
    <!-- Dados Principais -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person me-2"></i>Dados Pessoais
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label text-muted">Nome Completo</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['nome_prestador'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">CPF</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['cpf_prestador'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Email</label>
                        <p class="fw-bold">
                            <a href="mailto:<?php echo htmlspecialchars($prestador['email_prestador'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($prestador['email_prestador'] ?? '-'); ?>
                            </a>
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-muted">Telefone Principal</label>
                        <p class="fw-bold">
                            <a href="tel:<?php echo htmlspecialchars($prestador['telefone_1_prestador'] ?? ''); ?>" class="text-decoration-none">
                                <?php echo htmlspecialchars($prestador['telefone_1_prestador'] ?? '-'); ?>
                            </a>
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-muted">Telefone Secundário</label>
                        <p class="fw-bold">
                            <?php if (!empty($prestador['telefone_2_prestador'])): ?>
                                <a href="tel:<?php echo htmlspecialchars($prestador['telefone_2_prestador']); ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($prestador['telefone_2_prestador']); ?>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Serviços -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-briefcase me-2"></i>Serviços
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Tipo de Serviço</label>
                        <p class="fw-bold">
                            <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($prestador['servico_prestador'] ?? '-'); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Status</label>
                        <p class="fw-bold">
                            <span class="badge bg-success fs-6">Ativo</span>
                        </p>
                    </div>
                    <?php if (!empty($prestador['descricao_servico'])): ?>
                    <div class="col-12">
                        <label class="form-label text-muted">Descrição do Serviço</label>
                        <p><?php echo nl2br(htmlspecialchars($prestador['descricao_servico'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-geo-alt me-2"></i>Endereço
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label text-muted">Endereço Completo</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['endereco_prestador'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">CEP</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['cep_prestador'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Cidade</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['cidade_prestador'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Estado</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['estado'] ?? '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dados Bancários -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bank me-2"></i>Dados Bancários
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-muted">Banco</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['codigo_do_banco'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-9 mb-3">
                        <label class="form-label text-muted">Titular da Conta</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['titular_conta'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Tipo de Conta</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['tipo_de_conta'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Agência</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['agencia_prestadores'] ?? '-'); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-muted">Conta</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($prestador['conta_prestadores'] ?? '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Observações -->
        <?php if (!empty($prestador['observacoes'])): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-chat-text me-2"></i>Observações
                </h5>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($prestador['observacoes'])); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Ações Rápidas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo BASE_URL; ?>prestadores/<?php echo $prestador['id']; ?>/editar" class="btn btn-primary">
                        <i class="bi bi-pencil me-2"></i>Editar Dados
                    </a>
                    <a href="<?php echo BASE_URL; ?>atendimentos/criar?prestador=<?php echo $prestador['id']; ?>" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Novo Atendimento
                    </a>
                    <a href="mailto:<?php echo htmlspecialchars($prestador['email_prestador'] ?? ''); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-envelope me-2"></i>Enviar Email
                    </a>
                    <a href="tel:<?php echo htmlspecialchars($prestador['telefone_1_prestador'] ?? ''); ?>" class="btn btn-outline-success">
                        <i class="bi bi-telephone me-2"></i>Ligar
                    </a>
                </div>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>Estatísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">0</h4>
                            <small class="text-muted">Atendimentos</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success mb-0">0</h4>
                        <small class="text-muted">Concluídos</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">0</h4>
                            <small class="text-muted">Em Andamento</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-0">0</h4>
                        <small class="text-muted">Rondas</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Sistema -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>Informações do Sistema
                </h5>
            </div>
            <div class="card-body">
                <small class="text-muted">
                    <strong>ID:</strong> #<?php echo $prestador['id'] ?? '-'; ?><br>
                    <strong>Cadastrado em:</strong> <?php echo date('d/m/Y H:i', strtotime($prestador['created_at'] ?? 'now')); ?><br>
                    <?php if (!empty($prestador['updated_at'])): ?>
                    <strong>Última atualização:</strong> <?php echo date('d/m/Y H:i', strtotime($prestador['updated_at'])); ?><br>
                    <?php endif; ?>
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este prestador?</p>
                <p class="text-danger"><strong>Esta ação não pode ser desfeita!</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?php echo BASE_URL; ?>prestadores/<?php echo $prestador['id']; ?>/excluir" class="btn btn-danger">
                    Excluir Prestador
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

