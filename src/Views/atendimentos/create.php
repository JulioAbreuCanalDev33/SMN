<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-clipboard-plus me-2 text-primary"></i>Novo Atendimento
        </h1>
        <p class="text-muted mb-0">Cadastrar novo atendimento</p>
    </div>
    <a href="<?php echo BASE_URL; ?>atendimentos" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>atendimentos/salvar" method="POST" enctype="multipart/form-data">
            
            <!-- Informações Básicas -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary mb-3">
                        <i class="bi bi-info-circle me-2"></i>Informações Básicas
                    </h5>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="id_cliente" class="form-label">Cliente *</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Selecione o cliente</option>
                        <?php if (!empty($clientes)): ?>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?php echo $cliente['id']; ?>">
                                    <?php echo htmlspecialchars($cliente['nome_cliente']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="id_prestador" class="form-label">Prestador *</label>
                    <select class="form-select" id="id_prestador" name="id_prestador" required>
                        <option value="">Selecione o prestador</option>
                        <?php if (!empty($prestadores)): ?>
                            <?php foreach ($prestadores as $prestador): ?>
                                <option value="<?php echo $prestador['id']; ?>">
                                    <?php echo htmlspecialchars($prestador['nome_prestador']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="data_atendimento" class="form-label">Data do Atendimento *</label>
                    <input type="datetime-local" class="form-control" id="data_atendimento" name="data_atendimento" 
                           value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="status_atendimento" class="form-label">Status *</label>
                    <select class="form-select" id="status_atendimento" name="status_atendimento" required>
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluido">Concluído</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                
                <div class="col-md-4 mb-3">
                    <label for="prioridade" class="form-label">Prioridade</label>
                    <select class="form-select" id="prioridade" name="prioridade">
                        <option value="baixa">Baixa</option>
                        <option value="normal" selected>Normal</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
            </div>

            <!-- Descrição -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary mb-3">
                        <i class="bi bi-chat-text me-2"></i>Descrição do Atendimento
                    </h5>
                </div>
                
                <div class="col-12 mb-3">
                    <label for="descricao_atendimento" class="form-label">Descrição *</label>
                    <textarea class="form-control" id="descricao_atendimento" name="descricao_atendimento" 
                              rows="4" required placeholder="Descreva detalhadamente o atendimento..."></textarea>
                </div>
                
                <div class="col-12 mb-3">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" 
                              rows="3" placeholder="Observações adicionais..."></textarea>
                </div>
            </div>

            <!-- Upload de Fotos -->
            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="text-primary mb-3">
                        <i class="bi bi-camera me-2"></i>Fotos do Atendimento
                    </h5>
                </div>
                
                <div class="col-12 mb-3">
                    <label for="fotos" class="form-label">Selecionar Fotos</label>
                    <input type="file" class="form-control" id="fotos" name="fotos[]" 
                           multiple accept="image/*" onchange="previewImages(this)">
                    <div class="form-text">Selecione uma ou mais fotos (JPG, PNG, GIF). Máximo 5MB por foto.</div>
                </div>
                
                <div class="col-12">
                    <div id="imagePreview" class="row g-2"></div>
                </div>
            </div>

            <!-- Botões -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Salvar Atendimento
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>Limpar
                        </button>
                        <a href="<?php echo BASE_URL; ?>atendimentos" class="btn btn-outline-danger">
                            <i class="bi bi-x-lg me-1"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewImages(input) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';
                    col.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-muted">${file.name}</small>
                                <input type="text" class="form-control form-control-sm mt-1" 
                                       name="legendas[]" placeholder="Legenda da foto...">
                            </div>
                        </div>
                    `;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// Validação do formulário
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

