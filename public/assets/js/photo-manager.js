/**
 * Gerenciador de Upload de Fotos
 * Autor: Julio Abreu
 */

class PhotoManager {
    constructor() {
        this.initializeEvents();
        this.currentType = null;
        this.currentId = null;
    }
    
    initializeEvents() {
        // Preview da foto selecionada
        $(document).on('change', '#photoFile', (e) => {
            this.previewPhoto(e.target);
        });
        
        // Upload da foto
        $(document).on('click', '#uploadPhotoBtn', () => {
            this.uploadPhoto();
        });
        
        // Botões de upload nas páginas
        $(document).on('click', '.btn-upload-photo', (e) => {
            const button = $(e.target).closest('.btn-upload-photo');
            this.currentType = button.data('type');
            this.currentId = button.data('id');
            
            $('#photoModal').modal('show');
        });
        
        // Excluir foto
        $(document).on('click', '.btn-delete-photo', (e) => {
            e.preventDefault();
            const button = $(e.target).closest('.btn-delete-photo');
            const fotoId = button.data('foto-id');
            const tipo = button.data('tipo');
            
            this.deletePhoto(fotoId, tipo);
        });
        
        // Limpar modal ao fechar
        $('#photoModal').on('hidden.bs.modal', () => {
            this.resetModal();
        });
    }
    
    previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#photoPreview').show();
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    uploadPhoto() {
        const fileInput = document.getElementById('photoFile');
        const legenda = document.getElementById('photoLegend').value;
        
        if (!fileInput.files[0]) {
            this.showAlert('Selecione uma foto para fazer upload.', 'warning');
            return;
        }
        
        if (!this.currentType || !this.currentId) {
            this.showAlert('Erro: tipo ou ID não definido.', 'danger');
            return;
        }
        
        const formData = new FormData();
        formData.append('photo', fileInput.files[0]);
        formData.append('legenda', legenda);
        
        // Mostrar loading
        $('#uploadPhotoBtn').prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i>Enviando...');
        
        $.ajax({
            url: `${window.APP_CONFIG.baseUrl}upload/${this.currentType}/${this.currentId}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success) {
                    this.showAlert(response.message, 'success');
                    $('#photoModal').modal('hide');
                    this.refreshPhotoGallery();
                } else {
                    this.showAlert(response.error || 'Erro ao fazer upload.', 'danger');
                }
            },
            error: (xhr) => {
                let message = 'Erro ao fazer upload da foto.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    message = xhr.responseJSON.error;
                }
                this.showAlert(message, 'danger');
            },
            complete: () => {
                $('#uploadPhotoBtn').prop('disabled', false).html('<i class="bi bi-upload me-1"></i>Fazer Upload');
            }
        });
    }
    
    deletePhoto(fotoId, tipo) {
        if (!confirm('Tem certeza que deseja excluir esta foto?')) {
            return;
        }
        
        $.ajax({
            url: `${window.APP_CONFIG.baseUrl}upload/excluir`,
            type: 'POST',
            data: {
                foto_id: fotoId,
                tipo: tipo
            },
            success: (response) => {
                if (response.success) {
                    this.showAlert(response.message, 'success');
                    this.refreshPhotoGallery();
                } else {
                    this.showAlert(response.error || 'Erro ao excluir foto.', 'danger');
                }
            },
            error: (xhr) => {
                let message = 'Erro ao excluir foto.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    message = xhr.responseJSON.error;
                }
                this.showAlert(message, 'danger');
            }
        });
    }
    
    refreshPhotoGallery() {
        if (!this.currentType || !this.currentId) {
            return;
        }
        
        $.ajax({
            url: `${window.APP_CONFIG.baseUrl}upload/listar/${this.currentType}/${this.currentId}`,
            type: 'GET',
            success: (response) => {
                if (response.success) {
                    this.updatePhotoGallery(response.fotos);
                }
            },
            error: () => {
                console.log('Erro ao carregar galeria de fotos');
            }
        });
    }
    
    updatePhotoGallery(fotos) {
        const gallery = $('.photo-gallery');
        
        if (gallery.length === 0) {
            return;
        }
        
        if (fotos.length === 0) {
            gallery.html(`
                <div class="text-center py-4">
                    <i class="bi bi-camera display-4 text-muted"></i>
                    <p class="text-muted mt-2">Nenhuma foto encontrada</p>
                </div>
            `);
            return;
        }
        
        let html = '<div class="row g-3">';
        
        fotos.forEach(foto => {
            html += `
                <div class="col-md-4 col-lg-3">
                    <div class="card">
                        <img src="${foto.url}" class="card-img-top" alt="Foto" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-2">
                            ${foto.legenda ? `<p class="card-text small mb-2">${foto.legenda}</p>` : ''}
                            <small class="text-muted">${this.formatDate(foto.data_upload)}</small>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-photo" 
                                        data-foto-id="${foto.id_foto || foto.id}" data-tipo="${this.currentType}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <a href="${foto.url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        gallery.html(html);
    }
    
    resetModal() {
        $('#photoFile').val('');
        $('#photoLegend').val('');
        $('#photoPreview').hide();
        $('#previewImage').attr('src', '');
        this.currentType = null;
        this.currentId = null;
    }
    
    showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Adicionar no topo da página
        $('.main-content .container-fluid').prepend(alertHtml);
        
        // Auto-hide após 5 segundos
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }
    
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}

// Inicializar quando o documento estiver pronto
$(document).ready(() => {
    window.photoManager = new PhotoManager();
});

// Função global para abrir modal de upload
window.openPhotoModal = function(type, id) {
    window.photoManager.currentType = type;
    window.photoManager.currentId = id;
    $('#photoModal').modal('show');
};

// Função global para carregar galeria
window.loadPhotoGallery = function(type, id) {
    window.photoManager.currentType = type;
    window.photoManager.currentId = id;
    window.photoManager.refreshPhotoGallery();
};

