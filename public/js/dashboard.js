// Dashboard JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        if (document.hidden) return; // Don't refresh if tab is not visible
        
        // Refresh only if no form is being filled
        const forms = document.querySelectorAll('form');
        let hasActiveForm = false;
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                if (input.value && input.value.trim() !== '') {
                    hasActiveForm = true;
                }
            });
        });
        
        if (!hasActiveForm) {
            location.reload();
        }
    }, 300000); // 5 minutes
    
    // Handle period change
    const periodSelect = document.getElementById('period');
    if (periodSelect) {
        periodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                showCustomDateFields();
            } else {
                hideCustomDateFields();
                this.form.submit();
            }
        });
    }
    
    // Show/hide custom date fields
    function showCustomDateFields() {
        let customFields = document.getElementById('custom-date-fields');
        if (!customFields) {
            customFields = document.createElement('div');
            customFields.id = 'custom-date-fields';
            customFields.className = 'flex items-center space-x-2 mt-2';
            customFields.innerHTML = `
                <input type="date" name="start_date" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <span class="text-gray-500">até</span>
                <input type="date" name="end_date" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Filtrar
                </button>
            `;
            periodSelect.parentNode.appendChild(customFields);
        }
        customFields.style.display = 'flex';
    }
    
    function hideCustomDateFields() {
        const customFields = document.getElementById('custom-date-fields');
        if (customFields) {
            customFields.style.display = 'none';
        }
    }
    
    // Smooth animations for metric cards
    const metricCards = document.querySelectorAll('.metric-card');
    metricCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Real-time clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR');
        const dateString = now.toLocaleDateString('pt-BR');
        
        let clockElement = document.getElementById('real-time-clock');
        if (!clockElement) {
            clockElement = document.createElement('div');
            clockElement.id = 'real-time-clock';
            clockElement.className = 'text-sm text-gray-500';
            
            const header = document.querySelector('h1');
            if (header && header.parentNode) {
                header.parentNode.appendChild(clockElement);
            }
        }
        
        clockElement.innerHTML = `${dateString} - ${timeString}`;
    }
    
    // Update clock every second
    updateClock();
    setInterval(updateClock, 1000);
    
    // Notification system for alerts
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'error' ? 'bg-red-500 text-white' :
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'warning' ? 'bg-yellow-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    ×
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
    
    // Export functions for global use
    window.dashboardUtils = {
        showNotification: showNotification
    };
});
