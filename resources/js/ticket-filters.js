// Filtros da página de tickets
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do formulário
    const filterForm = document.querySelector('form[action*="tickets.index"]');
    const clearFiltersBtn = document.querySelector('a[href*="tickets.index"]:not([href*="create"])');
    
    // Auto-aplicar filtros quando mudar select
    const selects = filterForm.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            // Pequeno delay para melhor UX
            setTimeout(() => {
                filterForm.submit();
            }, 100);
        });
    });
    
    // Aplicar filtros ao pressionar Enter nos campos de texto
    const textInputs = filterForm.querySelectorAll('input[type="text"], input[type="date"]');
    textInputs.forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterForm.submit();
            }
        });
    });
    
    // Contador de filtros ativos
    function updateFilterCount() {
        const activeFilters = filterForm.querySelectorAll('select, input[type="text"], input[type="date"]');
        let count = 0;
        
        activeFilters.forEach(filter => {
            if (filter.value && filter.value !== '') {
                count++;
            }
        });
        
        // Atualizar o botão de limpar filtros
        if (count > 0) {
            clearFiltersBtn.textContent = `Limpar Filtros (${count})`;
            clearFiltersBtn.classList.add('bg-red-500', 'hover:bg-red-700');
            clearFiltersBtn.classList.remove('bg-gray-500', 'hover:bg-gray-700');
        } else {
            clearFiltersBtn.textContent = 'Limpar Filtros';
            clearFiltersBtn.classList.remove('bg-red-500', 'hover:bg-red-700');
            clearFiltersBtn.classList.add('bg-gray-500', 'hover:bg-gray-700');
        }
    }
    
    // Executar na inicialização
    updateFilterCount();
    
    // Executar quando qualquer filtro mudar
    const allFilters = filterForm.querySelectorAll('select, input[type="text"], input[type="date"]');
    allFilters.forEach(filter => {
        filter.addEventListener('change', updateFilterCount);
        filter.addEventListener('input', updateFilterCount);
    });
    
    // Funcionalidade de collapse/expand para filtros em telas pequenas
    function createFilterToggle() {
        const filterContainer = document.querySelector('.bg-gray-50');
        const filterGrid = filterContainer.querySelector('.grid');
        
        // Criar botão de toggle apenas em telas pequenas
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'md:hidden w-full flex items-center justify-between p-2 bg-blue-100 rounded mb-2';
        toggleBtn.innerHTML = `
            <span class="font-medium">Filtros</span>
            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        `;
        
        filterContainer.insertBefore(toggleBtn, filterGrid);
        
        // Inicialmente ocultar em telas pequenas
        filterGrid.classList.add('hidden', 'md:block');
        
        toggleBtn.addEventListener('click', function() {
            const isHidden = filterGrid.classList.contains('hidden');
            const svg = toggleBtn.querySelector('svg');
            
            if (isHidden) {
                filterGrid.classList.remove('hidden');
                svg.classList.add('rotate-180');
            } else {
                filterGrid.classList.add('hidden');
                svg.classList.remove('rotate-180');
            }
        });
    }
    
    // Criar toggle para mobile
    createFilterToggle();
    
    // Adicionar indicador de loading durante filtros
    function addLoadingIndicator() {
        const submitBtn = filterForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        filterForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Filtrando...
            `;
            
            // Restaurar botão após um tempo (caso algo dê errado)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }, 5000);
        });
    }
    
    addLoadingIndicator();
});
