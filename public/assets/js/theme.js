/**
 * Gerenciador de Tema Claro/Escuro
 * Autor: Julio Abreu
 */

class ThemeManager {
    constructor() {
        this.currentTheme = this.getStoredTheme() || this.getPreferredTheme();
        this.init();
    }
    
    init() {
        // Aplicar tema inicial
        this.setTheme(this.currentTheme);
        
        // Configurar eventos
        this.setupEventListeners();
        
        // Atualizar ícone
        this.updateThemeIcon();
        
        // Escutar mudanças na preferência do sistema
        this.watchSystemTheme();
    }
    
    setupEventListeners() {
        // Botão de toggle do tema
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                this.toggleTheme();
            });
        }
        
        // Atalho de teclado (Ctrl/Cmd + Shift + T)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'T') {
                e.preventDefault();
                this.toggleTheme();
            }
        });
    }
    
    getStoredTheme() {
        return localStorage.getItem('theme');
    }
    
    setStoredTheme(theme) {
        localStorage.setItem('theme', theme);
    }
    
    getPreferredTheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }
        return 'light';
    }
    
    setTheme(theme) {
        this.currentTheme = theme;
        
        // Aplicar ao HTML
        document.documentElement.setAttribute('data-bs-theme', theme);
        
        // Salvar preferência
        this.setStoredTheme(theme);
        
        // Atualizar ícone
        this.updateThemeIcon();
        
        // Disparar evento personalizado
        this.dispatchThemeChangeEvent(theme);
        
        // Aplicar classes específicas se necessário
        this.applyThemeClasses(theme);
    }
    
    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
        
        // Animação suave
        this.animateThemeChange();
        
        // Feedback visual
        this.showThemeChangeNotification(newTheme);
    }
    
    updateThemeIcon() {
        const themeIcon = document.getElementById('themeIcon');
        if (themeIcon) {
            if (this.currentTheme === 'dark') {
                themeIcon.className = 'bi bi-moon-fill';
            } else {
                themeIcon.className = 'bi bi-sun-fill';
            }
        }
    }
    
    applyThemeClasses(theme) {
        const body = document.body;
        
        // Remover classes de tema anteriores
        body.classList.remove('theme-light', 'theme-dark');
        
        // Adicionar classe do tema atual
        body.classList.add(`theme-${theme}`);
        
        // Atualizar meta theme-color para mobile
        this.updateMetaThemeColor(theme);
    }
    
    updateMetaThemeColor(theme) {
        let metaThemeColor = document.querySelector('meta[name="theme-color"]');
        
        if (!metaThemeColor) {
            metaThemeColor = document.createElement('meta');
            metaThemeColor.name = 'theme-color';
            document.head.appendChild(metaThemeColor);
        }
        
        const color = theme === 'dark' ? '#121212' : '#ffffff';
        metaThemeColor.content = color;
    }
    
    animateThemeChange() {
        // Adicionar classe de transição
        document.body.classList.add('theme-transitioning');
        
        // Remover após a transição
        setTimeout(() => {
            document.body.classList.remove('theme-transitioning');
        }, 300);
    }
    
    showThemeChangeNotification(theme) {
        // Criar notificação temporária
        const notification = document.createElement('div');
        notification.className = 'theme-notification';
        notification.innerHTML = `
            <i class="bi bi-${theme === 'dark' ? 'moon' : 'sun'}-fill me-2"></i>
            Tema ${theme === 'dark' ? 'escuro' : 'claro'} ativado
        `;
        
        // Estilos da notificação
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: theme === 'dark' ? '#1e1e1e' : '#ffffff',
            color: theme === 'dark' ? '#ffffff' : '#000000',
            padding: '12px 20px',
            borderRadius: '8px',
            boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
            zIndex: '9999',
            fontSize: '14px',
            fontWeight: '500',
            opacity: '0',
            transform: 'translateX(100%)',
            transition: 'all 0.3s ease'
        });
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remover após 2 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 2000);
    }
    
    watchSystemTheme() {
        if (window.matchMedia) {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            mediaQuery.addEventListener('change', (e) => {
                // Só aplicar se não houver preferência salva
                if (!this.getStoredTheme()) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    this.setTheme(newTheme);
                }
            });
        }
    }
    
    dispatchThemeChangeEvent(theme) {
        const event = new CustomEvent('themechange', {
            detail: { theme: theme }
        });
        document.dispatchEvent(event);
    }
    
    // Métodos públicos para uso externo
    getCurrentTheme() {
        return this.currentTheme;
    }
    
    isDarkMode() {
        return this.currentTheme === 'dark';
    }
    
    isLightMode() {
        return this.currentTheme === 'light';
    }
    
    resetToSystemPreference() {
        localStorage.removeItem('theme');
        const systemTheme = this.getPreferredTheme();
        this.setTheme(systemTheme);
    }
}

// Inicializar gerenciador de tema quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
});

// Aplicar tema imediatamente para evitar flash
(function() {
    const storedTheme = localStorage.getItem('theme');
    const preferredTheme = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    const theme = storedTheme || preferredTheme;
    
    document.documentElement.setAttribute('data-bs-theme', theme);
})();

// Escutar eventos de mudança de tema para atualizar gráficos, etc.
document.addEventListener('themechange', (e) => {
    const theme = e.detail.theme;
    
    // Atualizar cores dos gráficos Chart.js se existirem
    if (typeof Chart !== 'undefined' && Chart.instances) {
        Object.values(Chart.instances).forEach(chart => {
            if (chart && chart.options) {
                updateChartTheme(chart, theme);
            }
        });
    }
    
    // Atualizar outros componentes que dependem do tema
    updateComponentsTheme(theme);
});

// Função para atualizar tema dos gráficos
function updateChartTheme(chart, theme) {
    const isDark = theme === 'dark';
    const textColor = isDark ? '#ffffff' : '#495057';
    const gridColor = isDark ? '#404040' : '#dee2e6';
    
    if (chart.options.scales) {
        Object.keys(chart.options.scales).forEach(scaleKey => {
            const scale = chart.options.scales[scaleKey];
            if (scale.ticks) {
                scale.ticks.color = textColor;
            }
            if (scale.grid) {
                scale.grid.color = gridColor;
            }
        });
    }
    
    if (chart.options.plugins && chart.options.plugins.legend) {
        chart.options.plugins.legend.labels = chart.options.plugins.legend.labels || {};
        chart.options.plugins.legend.labels.color = textColor;
    }
    
    chart.update();
}

// Função para atualizar outros componentes
function updateComponentsTheme(theme) {
    // Atualizar mapas, editores de texto, etc.
    console.log(`Tema alterado para: ${theme}`);
    
    // Exemplo: atualizar cores de componentes específicos
    const isDark = theme === 'dark';
    
    // Atualizar variáveis CSS customizadas se necessário
    const root = document.documentElement;
    if (isDark) {
        root.style.setProperty('--custom-bg', '#1e1e1e');
        root.style.setProperty('--custom-text', '#ffffff');
    } else {
        root.style.setProperty('--custom-bg', '#ffffff');
        root.style.setProperty('--custom-text', '#495057');
    }
}

// Exportar para uso global
window.ThemeManager = ThemeManager;

