// Alpine.js Store para Gerenciamento de Usuários
document.addEventListener('alpine:init', () => {
    Alpine.store('usersManager', {
        // Estados
        selectedUsers: [],
        bulkActionsVisible: false,
        isLoading: false,
        
        // Métodos
        toggleUser(userId) {
            if (this.selectedUsers.includes(userId)) {
                this.selectedUsers = this.selectedUsers.filter(id => id !== userId);
            } else {
                this.selectedUsers.push(userId);
            }
            this.updateBulkActions();
        },
        
        toggleAll(userIds) {
            if (this.selectedUsers.length === userIds.length) {
                this.selectedUsers = [];
            } else {
                this.selectedUsers = [...userIds];
            }
            this.updateBulkActions();
        },
        
        updateBulkActions() {
            this.bulkActionsVisible = this.selectedUsers.length > 0;
        },
        
        clearSelection() {
            this.selectedUsers = [];
            this.bulkActionsVisible = false;
        },
        
        // Animações de números
        animateNumber(element, target, duration = 1000) {
            let start = 0;
            const startTime = performance.now();
            
            const animate = (currentTime) => {
                const elapsedTime = currentTime - startTime;
                const progress = Math.min(elapsedTime / duration, 1);
                
                // Easing function (ease-out)
                const easedProgress = 1 - Math.pow(1 - progress, 3);
                
                const current = Math.floor(start + (target - start) * easedProgress);
                element.textContent = current;
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            requestAnimationFrame(animate);
        },
        
        // Toasts/Notificações
        showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
            }`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${type === 'success' ? 
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animar entrada
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remover após 5 segundos
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    });
    
    // Componente Alpine para confirmação de ações
    Alpine.data('confirmAction', (message, action) => ({
        showModal: false,
        loading: false,
        
        confirm() {
            this.showModal = true;
        },
        
        async execute() {
            this.loading = true;
            try {
                await action();
                this.showModal = false;
                Alpine.store('usersManager').showToast('Ação executada com sucesso!');
            } catch (error) {
                Alpine.store('usersManager').showToast('Erro ao executar ação', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        cancel() {
            this.showModal = false;
        }
    }));
    
    // Componente para filtros avançados
    Alpine.data('advancedFilters', () => ({
        show: false,
        dateRange: {
            from: '',
            to: ''
        },
        
        toggle() {
            this.show = !this.show;
        },
        
        apply() {
            // Implementar lógica de filtros avançados
            console.log('Applying advanced filters:', this.dateRange);
        },
        
        reset() {
            this.dateRange = { from: '', to: '' };
        }
    }));
});

// Utilitários globais
window.UsersUtils = {
    // Debounce para pesquisa
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Formatação de números
    formatNumber(num) {
        return new Intl.NumberFormat('pt-BR').format(num);
    },
    
    // Formatação de datas
    formatDate(date) {
        return new Intl.DateTimeFormat('pt-BR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date(date));
    }
};
