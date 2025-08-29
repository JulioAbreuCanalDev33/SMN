<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-building-add me-2 text-primary"></i>Novo Cliente
        </h1>
        <p class="text-muted mb-0">Cadastrar novo cliente</p>
    </div>
    <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>clientes/salvar" method="POST" id="formCliente">
                    
                    <!-- Dados da Empresa -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-building me-2"></i>Dados da Empresa
                            </h5>
                        </div>
                        
                        <div class="col-md-8 mb-3">
                            <label for="nome_cliente" class="form-label">Razão Social *</label>
                            <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="cnpj_cliente" class="form-label">CNPJ *</label>
                            <input type="text" class="form-control" id="cnpj_cliente" name="cnpj_cliente" 
                                   data-mask="00.000.000/0000-00" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                            <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="inscricao_estadual" class="form-label">Inscrição Estadual</label>
                            <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual">
                        </div>
                    </div>

                    <!-- Contato -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-telephone me-2"></i>Contato
                            </h5>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email_cliente" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email_cliente" name="email_cliente" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="telefone_cliente" class="form-label">Telefone *</label>
                            <input type="text" class="form-control" id="telefone_cliente" name="telefone_cliente" 
                                   data-mask="(00) 00000-0000" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="telefone_2_cliente" class="form-label">Telefone 2</label>
                            <input type="text" class="form-control" id="telefone_2_cliente" name="telefone_2_cliente" 
                                   data-mask="(00) 00000-0000">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="contato_responsavel" class="form-label">Responsável</label>
                            <input type="text" class="form-control" id="contato_responsavel" name="contato_responsavel">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="cargo_responsavel" class="form-label">Cargo do Responsável</label>
                            <input type="text" class="form-control" id="cargo_responsavel" name="cargo_responsavel">
                        </div>
                    </div>

                    <!-- Endereço -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-geo-alt me-2"></i>Endereço
                            </h5>
                        </div>
                        
                        <div class="col-md-8 mb-3">
                            <label for="endereco_cliente" class="form-label">Endereço Completo *</label>
                            <input type="text" class="form-control" id="endereco_cliente" name="endereco_cliente" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="cep_cliente" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep_cliente" name="cep_cliente" 
                                   data-mask="00000-000">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="cidade_cliente" class="form-label">Cidade *</label>
                            <input type="text" class="form-control" id="cidade_cliente" name="cidade_cliente" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado_cliente" class="form-label">Estado *</label>
                            <select class="form-select" id="estado_cliente" name="estado_cliente" required>
                                <option value="">Selecione o estado</option>
                                <option value="SP">São Paulo</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="PR">Paraná</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="BA">Bahia</option>
                                <option value="GO">Goiás</option>
                                <option value="PE">Pernambuco</option>
                                <option value="CE">Ceará</option>
                            </select>
                        </div>
                    </div>

                    <!-- Observações -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-chat-text me-2"></i>Observações
                            </h5>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="observacoes" class="form-label">Observações Gerais</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="4" 
                                      placeholder="Informações adicionais sobre o cliente..."></textarea>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Salvar Cliente
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpar
                                </button>
                                <a href="<?php echo BASE_URL; ?>clientes" class="btn btn-outline-danger">
                                    <i class="bi bi-x-lg me-1"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscaras
    if (typeof $ !== 'undefined' && $.fn.mask) {
        $('#cnpj_cliente').mask('00.000.000/0000-00');
        $('#telefone_cliente, #telefone_2_cliente').mask('(00) 00000-0000');
        $('#cep_cliente').mask('00000-000');
    }
    
    // Validação
    const form = document.getElementById('formCliente');
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

