// Enhanced Contact Form JavaScript - BookBee Project
// Fixes for UX/UI improvements: text visibility and step indicator functionality

// Step Indicator System
class StepIndicator {
  constructor() {
    this.currentStep = 1;
    this.totalSteps = 4;
    this.initializeSteps();
  }
  
  initializeSteps() {
    const stepIndicator = document.querySelector('.form-step-indicator');
    if (!stepIndicator) return;
    
    const steps = stepIndicator.querySelectorAll('.step-item');
    steps.forEach((step, index) => {
      step.addEventListener('click', () => this.goToStep(index + 1));
    });
    
    // Set initial state
    this.updateStepIndicator();
  }
  
  goToStep(stepNumber) {
    if (stepNumber < 1 || stepNumber > this.totalSteps) return;
    
    this.currentStep = stepNumber;
    this.updateStepIndicator();
    this.animateStepTransition();
  }
  
  updateStepIndicator() {
    const steps = document.querySelectorAll('.step-item');
    steps.forEach((step, index) => {
      const stepNum = index + 1;
      step.classList.remove('active', 'completed');
      
      if (stepNum === this.currentStep) {
        step.classList.add('active');
      } else if (stepNum < this.currentStep) {
        step.classList.add('completed');
      }
    });
    
    // Update any progress lines if they exist
    const progressLine = document.querySelector('.step-progress-line');
    if (progressLine) {
      const progress = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
      progressLine.style.width = `${progress}%`;
    }
  }
  
  animateStepTransition() {
    // Add visual feedback for step changes
    const activeSteps = document.querySelectorAll('.step-item.active');
    activeSteps.forEach(step => {
      step.style.transform = 'scale(1.15)';
      setTimeout(() => {
        step.style.transform = 'scale(1.1)';
      }, 200);
    });
  }
}

// Smart Step Progression
function initializeSmartStepProgression() {
  const personalInputs = document.querySelectorAll('[data-group="personal"] input');
  const contactInputs = document.querySelectorAll('[data-group="contact"] input');
  const messageInput = document.querySelector('[data-group="message"] textarea');
  
  // Monitor personal info completion
  personalInputs.forEach(input => {
    input.addEventListener('input', () => {
      if (arePersonalFieldsComplete()) {
        window.stepIndicator.goToStep(2);
      }
    });
  });
  
  // Monitor contact info completion
  contactInputs.forEach(input => {
    input.addEventListener('input', () => {
      if (arePersonalFieldsComplete() && areContactFieldsComplete()) {
        window.stepIndicator.goToStep(3);
      }
    });
  });
  
  // Monitor message completion
  if (messageInput) {
    messageInput.addEventListener('input', () => {
      if (arePersonalFieldsComplete() && areContactFieldsComplete() && messageInput.value.trim().length > 10) {
        window.stepIndicator.goToStep(4);
      }
    });
  }
}

function arePersonalFieldsComplete() {
  const nameInput = document.getElementById('name');
  const phoneInput = document.getElementById('phone');
  return nameInput && nameInput.value.trim().length > 2 && 
         phoneInput && phoneInput.value.trim().length > 9;
}

function areContactFieldsComplete() {
  const emailInput = document.getElementById('email');
  const addressInput = document.getElementById('address');
  return emailInput && emailInput.checkValidity() && emailInput.value.trim().length > 0 &&
         addressInput && addressInput.value.trim().length > 5;
}

// Enhanced Input Text Visibility
function enhanceInputVisibility() {
  const inputs = document.querySelectorAll('.form-input, .form-textarea');
  
  inputs.forEach(input => {
    // Force text visibility on all events
    const forceVisibility = () => {
      input.style.color = '#1f2937 !important';
      input.style.webkitTextFillColor = '#1f2937 !important';
      input.style.background = '#ffffff !important';
      input.style.opacity = '1 !important';
    };
    
    input.addEventListener('input', forceVisibility);
    input.addEventListener('focus', forceVisibility);
    input.addEventListener('blur', forceVisibility);
    input.addEventListener('change', forceVisibility);
    
    // Initial visibility setup
    forceVisibility();
    
    // Handle autofill
    setTimeout(forceVisibility, 100);
    setTimeout(forceVisibility, 500);
  });
}

// Performance Optimizations
function optimizePerformance() {
  // Enable GPU acceleration for animations
  const animatedElements = document.querySelectorAll('.gpu-accelerated, .form-card, .submit-button');
  animatedElements.forEach(element => {
    element.style.transform = 'translateZ(0)';
    element.style.willChange = 'transform, opacity';
  });
  
  // Optimize input performance
  const inputs = document.querySelectorAll('.optimized-input');
  inputs.forEach(input => {
    input.style.willChange = 'border-color, background-color';
  });
  
  // Debounce resize events
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      // Recalculate layouts if needed
      console.log('Layout recalculated');
    }, 100);
  });
}

// Form Validation Enhancements
function enhanceFormValidation() {
  const form = document.getElementById('contactForm');
  if (!form) return;
  
  const inputs = form.querySelectorAll('input[required], textarea[required]');
  
  inputs.forEach(input => {
    input.addEventListener('blur', () => {
      validateField(input);
    });
    
    input.addEventListener('input', () => {
      // Clear previous validation styles
      input.classList.remove('error', 'success');
      
      // Real-time validation
      if (input.value.trim()) {
        validateField(input);
      }
    });
  });
}

function validateField(input) {
  const isValid = input.checkValidity() && input.value.trim().length > 0;
  
  input.classList.remove('error', 'success');
  
  if (isValid) {
    input.classList.add('success');
    input.style.borderColor = '#10b981';
  } else {
    input.classList.add('error');
    input.style.borderColor = '#ef4444';
    
    // Shake animation for errors
    input.style.animation = 'inputShake 0.4s ease-in-out';
    setTimeout(() => {
      input.style.animation = '';
    }, 400);
  }
}

// Initialize all systems when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  // Initialize step indicator system
  window.stepIndicator = new StepIndicator();
  
  // Add smart step progression
  initializeSmartStepProgression();
  
  // Enhance input text visibility
  enhanceInputVisibility();
  
  // Add performance optimizations
  optimizePerformance();
  
  // Enhance form validation
  enhanceFormValidation();
  
  console.log('BookBee Contact Form Enhanced - All systems initialized');
});

// Export for external use
window.BookBeeContact = {
  StepIndicator,
  enhanceInputVisibility,
  initializeSmartStepProgression,
  arePersonalFieldsComplete,
  areContactFieldsComplete
};
