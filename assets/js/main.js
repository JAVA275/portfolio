// Menu mobile
document.addEventListener('DOMContentLoaded', function() {
    // Animation au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.skill-card, .project-card, .contact-info, .contact-form').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
    
    // Fermer les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);
});

// Validation formulaire
const contactForm = document.querySelector('.contact-form form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        const name = document.querySelector('[name="name"]');
        const email = document.querySelector('[name="email"]');
        const message = document.querySelector('[name="message"]');
        let hasError = false;
        
        if (!name.value.trim()) {
            showError(name, 'Nom requis');
            hasError = true;
        }
        
        if (!email.value.trim() || !isValidEmail(email.value)) {
            showError(email, 'Email valide requis');
            hasError = true;
        }
        
        if (!message.value.trim()) {
            showError(message, 'Message requis');
            hasError = true;
        }
        
        if (hasError) {
            e.preventDefault();
        }
    });
}

function showError(field, message) {
    field.style.borderColor = '#ef4444';
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.cssText = 'color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem';
    field.parentNode.appendChild(errorDiv);
    
    setTimeout(() => errorDiv.remove(), 3000);
    field.addEventListener('input', () => {
        field.style.borderColor = '';
        errorDiv.remove();
    });
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}