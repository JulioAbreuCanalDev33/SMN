<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="bi bi-person-plus me-2 text-primary"></i>Novo Prestador
        </h1>
        <p class="text-muted mb-0">Cadastrar novo prestador de serviços</p>
    </div>
    <a href="<?php echo BASE_URL; ?>prestadores" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>prestadores/salvar" method="POST" id="formPrestador">
                    
                    <!-- Dados Pessoais -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-person me-2"></i>Dados Pessoais
                            </h5>
                        </div>
                        
                        <div class="col-md-8 mb-3">
                            <label for="nome_prestador" class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" id="nome_prestador" name="nome_prestador" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="cpf_prestador" class="form-label">CPF *</label>
                            <input type="text" class="form-control" id="cpf_prestador" name="cpf_prestador" 
                                   data-mask="000.000.000-00" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email_prestador" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email_prestador" name="email_prestador" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="telefone_1_prestador" class="form-label">Telefone Principal *</label>
                            <input type="text" class="form-control" id="telefone_1_prestador" name="telefone_1_prestador" 
                                   data-mask="(00) 00000-0000" required>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="telefone_2_prestador" class="form-label">Telefone Secundário</label>
                            <input type="text" class="form-control" id="telefone_2_prestador" name="telefone_2_prestador" 
                                   data-mask="(00) 00000-0000">
                        </div>
                    </div>

                    <!-- Serviços -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-briefcase me-2"></i>Serviços
                            </h5>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="servico_prestador" class="form-label">Tipo de Serviço *</label>
                            <select class="form-select" id="servico_prestador" name="servico_prestador" required>
                                <option value="">Selecione o serviço</option>
                                <option value="Segurança Patrimonial">Segurança Patrimonial</option>
                                <option value="Vigilância">Vigilância</option>
                                <option value="Monitoramento">Monitoramento</option>
                                <option value="Portaria">Portaria</option>
                                <option value="Ronda">Ronda</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="descricao_servico" class="form-label">Descrição do Serviço</label>
                            <textarea class="form-control" id="descricao_servico" name="descricao_servico" rows="3"></textarea>
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
                            <label for="endereco_prestador" class="form-label">Endereço Completo *</label>
                            <input type="text" class="form-control" id="endereco_prestador" name="endereco_prestador" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="cep_prestador" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep_prestador" name="cep_prestador" 
                                   data-mask="00000-000">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="cidade_prestador" class="form-label">Cidade *</label>
                            <input type="text" class="form-control" id="cidade_prestador" name="cidade_prestador" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select" id="estado" name="estado" required>
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

                    <!-- Dados Bancários -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">
                                <i class="bi bi-bank me-2"></i>Dados Bancários
                            </h5>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label for="codigo_do_banco" class="form-label">Código do Banco *</label>
                            <input type="text" class="form-control" id="codigo_do_banco" name="codigo_do_banco" 
                                   data-mask="000" required>
                        </div>
                        
                        <div class="col-md-9 mb-3">
                            <label for="titular_conta" class="form-label">Titular da Conta *</label>
                            <input type="text" class="form-control" id="titular_conta" name="titular_conta" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tipo_de_conta" class="form-label">Tipo de Conta *</label>
                            <select class="form-select" id="tipo_de_conta" name="tipo_de_conta" required>
                                <option value="">Selecione o tipo</option>
                                <option value="Corrente">Conta Corrente</option>
                                <option value="Poupança">Conta Poupança</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="agencia_prestadores" class="form-label">Agência *</label>
                            <input type="text" class="form-control" id="agencia_prestadores" name="agencia_prestadores" 
                                   data-mask="0000-0" required>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="conta_prestadores" class="form-label">Conta *</label>
                            <input type="text" class="form-control" id="conta_prestadores" name="conta_prestadores" 
                                   data-mask="00000000-0" required>
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
                                      placeholder="Informações adicionais sobre o prestador..."></textarea>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Salvar Prestador
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Limpar
                                </button>
                                <a href="<?php echo BASE_URL; ?>prestadores" class="btn btn-outline-danger">
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
// Máscaras de input
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar máscaras se a biblioteca estiver disponível
    if (typeof $ !== 'undefined' && $.fn.mask) {
        $('#cpf_prestador').mask('000.000.000-00');
        $('#telefone_1_prestador, #telefone_2_prestador').mask('(00) 00000-0000');
        $('#cep_prestador').mask('00000-000');
        $('#codigo_do_banco').mask('000');
        $('#agencia_prestadores').mask('0000-0');
        $('#conta_prestadores').mask('00000000-0');
    }
    
    // Validação do formulário
    const form = document.getElementById('formPrestador');
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

