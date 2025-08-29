<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-person-plus me-2 text-primary"></i>Novo Agente
        </h1>
        <p class="text-muted mb-0">Cadastrar novo agente de campo</p>
    </div>
    <a href="<?php echo BASE_URL; ?>agentes" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo BASE_URL; ?>agentes/salvar" method="POST">
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="nome_agente" class="form-label">Nome Completo *</label>
                    <input type="text" class="form-control" id="nome_agente" name="nome_agente" required>
                </div>
                <div class="col-md-4">
                    <label for="cpf_agente" class="form-label">CPF *</label>
                    <input type="text" class="form-control" id="cpf_agente" name="cpf_agente" 
                           data-mask="000.000.000-00" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email_agente" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email_agente" name="email_agente" required>
                </div>
                <div class="col-md-6">
                    <label for="telefone_agente" class="form-label">Telefone *</label>
                    <input type="text" class="form-control" id="telefone_agente" name="telefone_agente" 
                           data-mask="(00) 00000-0000" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="equipe" class="form-label">Equipe</label>
                    <select class="form-select" id="equipe" name="equipe">
                        <option value="">Selecione a equipe</option>
                        <option value="A">Equipe A</option>
                        <option value="B">Equipe B</option>
                        <option value="C">Equipe C</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="funcao" class="form-label">Função</label>
                    <input type="text" class="form-control" id="funcao" name="funcao" 
                           placeholder="Ex: Vigilante, Supervisor, etc.">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="endereco_agente" class="form-label">Endereço Completo</label>
                    <input type="text" class="form-control" id="endereco_agente" name="endereco_agente">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-12">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3" 
                              placeholder="Informações adicionais sobre o agente..."></textarea>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar Agente
                </button>
                <button type="reset" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i>Limpar
                </button>
                <a href="<?php echo BASE_URL; ?>agentes" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.mask) {
        $('#cpf_agente').mask('000.000.000-00');
        $('#telefone_agente').mask('(00) 00000-0000');
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

