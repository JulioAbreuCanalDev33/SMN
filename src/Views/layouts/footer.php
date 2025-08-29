        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer bg-light border-top mt-auto">
        <div class="container-fluid py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="text-muted">
                        © <?php echo date('Y'); ?> <?php echo SYSTEM_NAME; ?> - 
                        Desenvolvido por <strong><?php echo SYSTEM_AUTHOR; ?></strong>
                    </span>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="text-muted">
                        Versão <?php echo SYSTEM_VERSION; ?> | 
                        <i class="bi bi-database me-1"></i>SQLite3 | 
                        <i class="bi bi-bootstrap me-1"></i>Bootstrap 5
                    </span>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Modais -->
    
    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage">Tem certeza que deseja realizar esta ação?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmButton">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Upload de Fotos -->
    <div class="modal fade" id="photoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-camera me-2"></i>Upload de Fotos
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="photoUploadForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="photoFile" class="form-label">Selecionar Foto</label>
                            <input type="file" class="form-control" id="photoFile" name="photo" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="photoLegend" class="form-label">Legenda</label>
                            <textarea class="form-control" id="photoLegend" name="legenda" rows="3" placeholder="Descreva a foto..."></textarea>
                        </div>
                        <div class="mb-3">
                            <div id="photoPreview" class="text-center" style="display: none;">
                                <img id="previewImage" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="uploadPhotoBtn">
                        <i class="bi bi-upload me-1"></i>Fazer Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="mt-2">Carregando...</p>
        </div>
    </div>
    
    <!-- Scripts -->
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo ASSETS_URL; ?>js/app.js"></script>
    <script src="<?php echo ASSETS_URL; ?>js/theme.js"></script>
    <script src="<?php echo ASSETS_URL; ?>js/photo-manager.js"></script>
    
    <!-- Scripts específicos da página -->
    <?php if (isset($scripts)): ?>
        <?php foreach ($scripts as $script): ?>
            <script src="<?php echo ASSETS_URL; ?>js/<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- JavaScript inline -->
    <script>
        // Configurações globais
        window.APP_CONFIG = {
            baseUrl: '<?php echo BASE_URL; ?>',
            assetsUrl: '<?php echo ASSETS_URL; ?>',
            systemName: '<?php echo SYSTEM_NAME; ?>',
            version: '<?php echo SYSTEM_VERSION; ?>'
        };
        
        // Inicializar aplicação
        $(document).ready(function() {
            // Inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Inicializar popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Auto-hide alerts após 5 segundos
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
            
            // Carregar notificações
            loadNotifications();
            
            // Atualizar notificações a cada 30 segundos
            setInterval(loadNotifications, 30000);
        });
        
        // Função para carregar notificações
        function loadNotifications() {
            $.get(window.APP_CONFIG.baseUrl + 'home/notificacoes', function(data) {
                if (data.success && data.notificacoes) {
                    updateNotificationBadge(data.notificacoes.length);
                    updateNotificationList(data.notificacoes);
                }
            }).fail(function() {
                console.log('Erro ao carregar notificações');
            });
        }
        
        // Atualizar badge de notificações
        function updateNotificationBadge(count) {
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'block' : 'none';
            }
        }
        
        // Atualizar lista de notificações
        function updateNotificationList(notifications) {
            const list = document.getElementById('notificationList');
            if (list && notifications.length > 0) {
                list.innerHTML = notifications.slice(0, 5).map(notification => `
                    <li>
                        <a class="dropdown-item" href="${notification.url || '#'}">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-${getNotificationIcon(notification.tipo)} me-2 text-${notification.tipo}"></i>
                                <div>
                                    <strong>${notification.titulo}</strong><br>
                                    <small class="text-muted">${notification.mensagem}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                `).join('');
            }
        }
        
        // Ícone da notificação baseado no tipo
        function getNotificationIcon(tipo) {
            const icons = {
                'success': 'check-circle',
                'warning': 'exclamation-triangle',
                'danger': 'exclamation-circle',
                'info': 'info-circle'
            };
            return icons[tipo] || 'bell';
        }
    </script>
    
</body>
</html>

