/**
 * JavaScript Principal do Sistema
 * Autor: Julio Abreu
 */

// Configurações globais
window.APP = {
    config: {
        baseUrl: '',
        assetsUrl: '',
        systemName: 'Sistema de Monitoramento',
        version: '1.0.0'
    },
    
    // Utilitários
    utils: {
        // Formatar CPF
        formatCpf: function(cpf) {
            return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        },
        
        // Formatar CNPJ
        formatCnpj: function(cnpj) {
            return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
        },
        
        // Formatar telefone
        formatPhone: function(phone) {
            if (phone.length === 10) {
                return phone.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else if (phone.length === 11) {
                return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
            return phone;
        },
        
        // Formatar data
        formatDate: function(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        },
        
        // Formatar data e hora
        formatDateTime: function(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR');
        },
        
        // Validar CPF
        validateCpf: function(cpf) {
            cpf = cpf.replace(/[^\d]/g, '');
            
            if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
                return false;
            }
            
            let sum = 0;
            for (let i = 0; i < 9; i++) {
                sum += parseInt(cpf.charAt(i)) * (10 - i);
            }
            
            let remainder = 11 - (sum % 11);
            if (remainder === 10 || remainder === 11) remainder = 0;
            if (remainder !== parseInt(cpf.charAt(9))) return false;
            
            sum = 0;
            for (let i = 0; i < 10; i++) {
                sum += parseInt(cpf.charAt(i)) * (11 - i);
            }
            
            remainder = 11 - (sum % 11);
            if (remainder === 10 || remainder === 11) remainder = 0;
            
            return remainder === parseInt(cpf.charAt(10));
        },
        
        // Validar CNPJ
        validateCnpj: function(cnpj) {
            cnpj = cnpj.replace(/[^\d]/g, '');
            
            if (cnpj.length !== 14) return false;
            
            // Eliminar CNPJs inválidos conhecidos
            if (/^(\d)\1{13}$/.test(cnpj)) return false;
            
            // Validar primeiro dígito verificador
            let length = cnpj.length - 2;
            let numbers = cnpj.substring(0, length);
            let digits = cnpj.substring(length);
            let sum = 0;
            let pos = length - 7;
            
            for (let i = length; i >= 1; i--) {
                sum += numbers.charAt(length - i) * pos--;
                if (pos < 2) pos = 9;
            }
            
            let result = sum % 11 < 2 ? 0 : 11 - sum % 11;
            if (result !== parseInt(digits.charAt(0))) return false;
            
            // Validar segundo dígito verificador
            length = length + 1;
            numbers = cnpj.substring(0, length);
            sum = 0;
            pos = length - 7;
            
            for (let i = length; i >= 1; i--) {
                sum += numbers.charAt(length - i) * pos--;
                if (pos < 2) pos = 9;
            }
            
            result = sum % 11 < 2 ? 0 : 11 - sum % 11;
            
            return result === parseInt(digits.charAt(1));
        },
        
        // Mostrar loading
        showLoading: function(message = 'Carregando...') {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.querySelector('.loading-content p').textContent = message;
                overlay.style.display = 'flex';
            }
        },
        
        // Esconder loading
        hideLoading: function() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.style.display = 'none';
            }
        },
        
        // Mostrar notificação
        showNotification: function(message, type = 'info', duration = 5000) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto-remover após o tempo especificado
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 150);
                }
            }, duration);
        }
    },
    
    // Inicialização
    init: function() {
        this.setupGlobalEvents();
        this.setupFormValidation();
        this.setupAjaxDefaults();
        this.setupTooltips();
        this.setupMasks();
    },
    
    // Configurar eventos globais
    setupGlobalEvents: function() {
        // Confirmação de exclusão
        $(document).on('click', '[data-confirm]', function(e) {
            e.preventDefault();
            const message = $(this).data('confirm') || 'Tem certeza que deseja realizar esta ação?';
            
            if (confirm(message)) {
                const href = $(this).attr('href');
                const form = $(this).closest('form');
                
                if (form.length) {
                    form.submit();
                } else if (href) {
                    window.location.href = href;
                }
            }
        });
        
        // Auto-submit em mudança de select
        $(document).on('change', '[data-auto-submit]', function() {
            $(this).closest('form').submit();
        });
        
        // Busca em tempo real
        let searchTimeout;
        $(document).on('input', '[data-live-search]', function() {
            const input = $(this);
            const target = input.data('live-search');
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = input.val();
                if (query.length >= 2) {
                    // Implementar busca AJAX aqui
                    console.log('Buscando:', query);
                }
            }, 300);
        });
    },
    
    // Configurar validação de formulários
    setupFormValidation: function() {
        // Validação de CPF em tempo real
        $(document).on('blur', 'input[data-validate="cpf"]', function() {
            const input = $(this);
            const cpf = input.val().replace(/[^\d]/g, '');
            
            if (cpf && !APP.utils.validateCpf(cpf)) {
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').text('CPF inválido');
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        // Validação de CNPJ em tempo real
        $(document).on('blur', 'input[data-validate="cnpj"]', function() {
            const input = $(this);
            const cnpj = input.val().replace(/[^\d]/g, '');
            
            if (cnpj && !APP.utils.validateCnpj(cnpj)) {
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').text('CNPJ inválido');
            } else {
                input.removeClass('is-invalid');
            }
        });
        
        // Validação de email
        $(document).on('blur', 'input[type="email"]', function() {
            const input = $(this);
            const email = input.val();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                input.addClass('is-invalid');
                input.siblings('.invalid-feedback').text('Email inválido');
            } else {
                input.removeClass('is-invalid');
            }
        });
    },
    
    // Configurar padrões AJAX
    setupAjaxDefaults: function() {
        $.ajaxSetup({
            beforeSend: function() {
                APP.utils.showLoading();
            },
            complete: function() {
                APP.utils.hideLoading();
            },
            error: function(xhr, status, error) {
                let message = 'Erro na requisição';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    message = xhr.responseJSON.error;
                } else if (xhr.status === 404) {
                    message = 'Recurso não encontrado';
                } else if (xhr.status === 500) {
                    message = 'Erro interno do servidor';
                }
                
                APP.utils.showNotification(message, 'danger');
            }
        });
    },
    
    // Configurar tooltips
    setupTooltips: function() {
        // Inicializar tooltips do Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    },
    
    // Configurar máscaras de input
    setupMasks: function() {
        // Máscara de CPF
        $(document).on('input', 'input[data-mask="cpf"]', function() {
            let value = this.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            this.value = value;
        });
        
        // Máscara de CNPJ
        $(document).on('input', 'input[data-mask="cnpj"]', function() {
            let value = this.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1/$2');
            value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            this.value = value;
        });
        
        // Máscara de telefone
        $(document).on('input', 'input[data-mask="phone"]', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{4})(\d)/, '$1-$2');
            } else {
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            this.value = value;
        });
        
        // Máscara de CEP
        $(document).on('input', 'input[data-mask="cep"]', function() {
            let value = this.value.replace(/\D/g, '');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            this.value = value;
        });
    }
};

// Inicializar quando o documento estiver pronto
$(document).ready(function() {
    APP.init();
    
    // Configurar APP.config com valores do PHP
    if (typeof window.APP_CONFIG !== 'undefined') {
        APP.config = Object.assign(APP.config, window.APP_CONFIG);
    }
});

// Exportar para uso global
window.APP = APP;

