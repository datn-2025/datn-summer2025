/**
 * BookBee Contact Form - Enhanced UX/UI JavaScript
 * Author: BookBee Development Team
 * Description: Complete JavaScript functionality for the contact form
 */

// Background particles animation
function createParticles() {
  const canvas = document.getElementById('particleCanvas');
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;

  const particles = [];
  const particleCount = 50;

  for (let i = 0; i < particleCount; i++) {
    particles.push({
      x: Math.random() * canvas.width,
      y: Math.random() * canvas.height,
      vx: (Math.random() - 0.5) * 0.5,
      vy: (Math.random() - 0.5) * 0.5,
      radius: Math.random() * 2 + 1,
      alpha: Math.random() * 0.5 + 0.2
    });
  }

  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    particles.forEach(particle => {
      particle.x += particle.vx;
      particle.y += particle.vy;
      
      if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
      if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;
      
      ctx.beginPath();
      ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(59, 130, 246, ${particle.alpha})`;
      ctx.fill();
    });
    
    requestAnimationFrame(animate);
  }
  
  animate();
  
  window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  });
}

// Form submission handler
function initializeFormSubmission() {
  const form = document.getElementById('contactForm');
  const submitBtn = document.getElementById('submitBtn');
  
  if (!form || !submitBtn) return;
  
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
      <div class="loading-spinner"></div>
      <span>Đang gửi...</span>
    `;
    
    // Simulate form submission
    setTimeout(() => {
      // Reset button
      submitBtn.disabled = false;
      submitBtn.innerHTML = `
        <i class="fas fa-paper-plane"></i>
        <span>Gửi liên hệ</span>
        <div class="button-ripple"></div>
      `;
      
      // Show success message
      showSuccessMessage();
      
      // Reset form
      form.reset();
      updateProgressIndicator();
    }, 2000);
  });
}

// Real-time validation
function initializeRealTimeValidation() {
  const inputs = document.querySelectorAll('.form-input, .form-textarea');
  
  inputs.forEach(input => {
    input.addEventListener('input', function() {
      validateField(this);
    });
    
    input.addEventListener('blur', function() {
      validateField(this);
    });
  });
}

function validateField(field) {
  const isValid = field.checkValidity() && field.value.trim() !== '';
  const fieldGroup = field.closest('.field-group');
  
  if (fieldGroup) {
    fieldGroup.classList.toggle('valid', isValid);
    fieldGroup.classList.toggle('invalid', !isValid && field.value.trim() !== '');
  }
  
  // Add visual feedback
  if (isValid) {
    field.style.borderColor = '#10b981';
    field.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
  } else if (field.value.trim() !== '') {
    field.style.borderColor = '#ef4444';
    field.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
  } else {
    field.style.borderColor = '';
    field.style.boxShadow = '';
  }
}

// Phone number formatting
function initializePhoneFormatting() {
  const phoneInput = document.getElementById('phone');
  if (!phoneInput) return;
  
  phoneInput.addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    
    if (value.length > 0) {
      if (value.length <= 3) {
        value = value;
      } else if (value.length <= 6) {
        value = value.slice(0, 3) + ' ' + value.slice(3);
      } else if (value.length <= 10) {
        value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
      } else {
        value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
      }
    }
    
    this.value = value;
  });
}

// Email autocomplete
function initializeEmailAutocomplete() {
  const emailInput = document.getElementById('email');
  if (!emailInput) return;
  
  const suggestions = ['@gmail.com', '@yahoo.com', '@hotmail.com', '@outlook.com'];
  
  emailInput.addEventListener('input', function() {
    const value = this.value;
    const atIndex = value.indexOf('@');
    
    if (atIndex > 0 && atIndex === value.length - 1) {
      // Show suggestions when @ is typed at the end
      showEmailSuggestions(this, suggestions);
    }
  });
}

function showEmailSuggestions(input, suggestions) {
  let dropdown = input.nextElementSibling;
  if (!dropdown || !dropdown.classList.contains('email-suggestions')) {
    dropdown = document.createElement('div');
    dropdown.className = 'email-suggestions';
    input.parentNode.appendChild(dropdown);
  }
  
  dropdown.innerHTML = '';
  dropdown.style.display = 'block';
  
  suggestions.forEach(suggestion => {
    const item = document.createElement('div');
    item.className = 'suggestion-item';
    item.textContent = input.value + suggestion;
    
    item.addEventListener('click', () => {
      input.value = item.textContent;
      dropdown.style.display = 'none';
      input.dispatchEvent(new Event('input'));
    });
    
    dropdown.appendChild(item);
  });
  
  // Hide on outside click
  document.addEventListener('click', function hideDropdown(e) {
    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.style.display = 'none';
      document.removeEventListener('click', hideDropdown);
    }
  });
}

// Step Indicator Class
class StepIndicator {
  constructor() {
    this.currentStep = 1;
    this.totalSteps = 4;
    this.stepItems = document.querySelectorAll('.step-item');
    this.initializeSteps();
  }
  
  initializeSteps() {
    if (this.stepItems.length === 0) return;
    
    this.stepItems.forEach((step, index) => {
      step.addEventListener('click', () => {
        this.goToStep(index + 1);
      });
    });
    
    // Set initial step
    this.goToStep(1);
  }
  
  goToStep(stepNumber) {
    if (stepNumber < 1 || stepNumber > this.totalSteps) return;
    
    this.currentStep = stepNumber;
    this.updateStepIndicator();
    this.animateStepTransition();
    this.updateStepContent();
  }
  
  updateStepIndicator() {
    this.stepItems.forEach((step, index) => {
      const stepNum = index + 1;
      step.classList.toggle('active', stepNum === this.currentStep);
      step.classList.toggle('completed', stepNum < this.currentStep);
      
      const stepIcon = step.querySelector('.step-icon');
      const stepNumber = step.querySelector('.step-number');
      
      if (stepNum < this.currentStep) {
        if (stepIcon) stepIcon.innerHTML = '<i class="fas fa-check"></i>';
      } else {
        if (stepIcon && stepNumber) {
          stepIcon.innerHTML = stepNumber.textContent;
        }
      }
    });
  }
  
  animateStepTransition() {
    const activeSteps = document.querySelectorAll('.step-item.active');
    activeSteps.forEach(step => {
      step.style.transform = 'scale(1.15)';
      setTimeout(() => {
        step.style.transform = 'scale(1.1)';
      }, 200);
    });
  }
  
  updateStepContent() {
    console.log(`Step ${this.currentStep} activated`);
  }
  
  validateCurrentStep() {
    const activeGroup = document.querySelector('.field-group.step-active');
    if (!activeGroup) return true;
    
    const inputs = activeGroup.querySelectorAll('.form-input[required], .form-textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
      if (!input.checkValidity() || !input.value.trim()) {
        input.focus();
        input.style.animation = 'fieldErrorShake 0.4s ease-in-out';
        isValid = false;
      }
    });
    
    return isValid;
  }
}

// Smart Suggestions System
class SmartSuggestions {
  constructor() {
    this.suggestions = {
      name: ['Nguyễn Văn', 'Trần Thị', 'Lê Văn', 'Phạm Thị', 'Hoàng Văn'],
      email: ['@gmail.com', '@yahoo.com', '@hotmail.com', '@outlook.com', '@icloud.com'],
      address: ['Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ'],
      note: [
        'Tôi muốn biết thêm về sản phẩm',
        'Tôi cần hỗ trợ kỹ thuật',
        'Tôi muốn góp ý về dịch vụ',
        'Tôi có thắc mắc về đơn hàng',
        'Tôi muốn đặt lịch tư vấn'
      ]
    };
    
    this.initializeSuggestions();
  }
  
  initializeSuggestions() {
    Object.keys(this.suggestions).forEach(fieldName => {
      const input = document.getElementById(fieldName);
      if (input) {
        this.setupSuggestionForField(input, fieldName);
      }
    });
  }
  
  setupSuggestionForField(input, fieldName) {
    const suggestionContainer = input.parentElement.querySelector('.smart-suggestions') || 
                               document.getElementById(`${fieldName}-suggestions`);
    
    if (!suggestionContainer) return;
    
    input.addEventListener('input', () => {
      this.showSuggestions(input, suggestionContainer, fieldName);
    });
    
    input.addEventListener('focus', () => {
      if (input.value.length > 0) {
        this.showSuggestions(input, suggestionContainer, fieldName);
      }
    });
    
    document.addEventListener('click', (e) => {
      if (!input.contains(e.target) && !suggestionContainer.contains(e.target)) {
        suggestionContainer.classList.remove('show');
      }
    });
  }
  
  showSuggestions(input, container, fieldName) {
    const value = input.value.toLowerCase();
    const suggestions = this.suggestions[fieldName];
    
    if (value.length < 1) {
      container.classList.remove('show');
      return;
    }
    
    const filteredSuggestions = suggestions.filter(suggestion => 
      suggestion.toLowerCase().includes(value)
    );
    
    if (filteredSuggestions.length === 0) {
      container.classList.remove('show');
      return;
    }
    
    container.innerHTML = '';
    container.className = 'smart-suggestions';
    
    const header = document.createElement('div');
    header.className = 'suggestions-header';
    header.innerHTML = `
      <i class="fas fa-lightbulb"></i>
      <span>Gợi ý thông minh</span>
    `;
    container.appendChild(header);
    
    filteredSuggestions.slice(0, 5).forEach(suggestion => {
      const item = document.createElement('div');
      item.className = 'suggestion-item';
      
      const icon = this.getSuggestionIcon(fieldName);
      item.innerHTML = `
        <i class="${icon}"></i>
        <span class="suggestion-text">${suggestion}</span>
        <span class="suggestion-action">Chọn</span>
      `;
      
      item.addEventListener('click', () => {
        if (fieldName === 'email' && suggestion.startsWith('@')) {
          const atIndex = input.value.indexOf('@');
          if (atIndex > 0) {
            input.value = input.value.substring(0, atIndex) + suggestion;
          } else {
            input.value += suggestion;
          }
        } else {
          input.value = suggestion;
        }
        
        container.classList.remove('show');
        input.focus();
        input.dispatchEvent(new Event('input'));
      });
      
      container.appendChild(item);
    });
    
    container.classList.add('show');
  }
  
  getSuggestionIcon(fieldName) {
    const icons = {
      name: 'fas fa-user',
      email: 'fas fa-envelope',
      address: 'fas fa-map-marker-alt',
      note: 'fas fa-comment'
    };
    return icons[fieldName] || 'fas fa-lightbulb';
  }
}

// Advanced Touch Gestures
class TouchGestures {
  constructor() {
    this.startX = 0;
    this.startY = 0;
    this.isTouch = false;
    this.gestureThreshold = 50;
    this.initializeGestures();
  }
  
  initializeGestures() {
    const form = document.getElementById('contactForm');
    if (!form) return;
    
    form.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: true });
    form.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: true });
    form.addEventListener('touchend', (e) => this.handleTouchEnd(e), { passive: true });
    
    document.addEventListener('touchstart', () => {
      this.showGestureHelper();
    }, { once: true });
  }
  
  handleTouchStart(e) {
    this.isTouch = true;
    this.startX = e.touches[0].clientX;
    this.startY = e.touches[0].clientY;
  }
  
  handleTouchMove(e) {
    if (!this.isTouch) return;
    
    const deltaX = e.touches[0].clientX - this.startX;
    const deltaY = e.touches[0].clientY - this.startY;
    
    this.showGestureFeedback(deltaX, deltaY);
  }
  
  handleTouchEnd(e) {
    if (!this.isTouch) return;
    
    const deltaX = e.changedTouches[0].clientX - this.startX;
    const deltaY = e.changedTouches[0].clientY - this.startY;
    
    if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > this.gestureThreshold) {
      if (deltaX > 0) {
        this.handleSwipeRight();
      } else {
        this.handleSwipeLeft();
      }
    }
    
    this.isTouch = false;
    this.hideGestureFeedback();
  }
  
  handleSwipeLeft() {
    if (window.stepIndicator && window.stepIndicator.currentStep < window.stepIndicator.totalSteps) {
      if (window.stepIndicator.validateCurrentStep()) {
        window.stepIndicator.goToStep(window.stepIndicator.currentStep + 1);
        this.showGestureConfirmation('Chuyển bước tiếp theo');
      }
    }
  }
  
  handleSwipeRight() {
    if (window.stepIndicator && window.stepIndicator.currentStep > 1) {
      window.stepIndicator.goToStep(window.stepIndicator.currentStep - 1);
      this.showGestureConfirmation('Quay lại bước trước');
    }
  }
  
  showGestureHelper() {
    const helper = document.getElementById('gestureHelper');
    if (helper) {
      helper.classList.add('show');
      setTimeout(() => {
        helper.classList.remove('show');
      }, 3000);
    }
  }
  
  showGestureFeedback(deltaX, deltaY) {
    const feedback = document.querySelector('.gesture-feedback') || this.createGestureFeedback();
    
    if (Math.abs(deltaX) > Math.abs(deltaY)) {
      feedback.style.display = 'block';
      feedback.innerHTML = deltaX > 0 ? 
        '<i class="fas fa-arrow-left"></i> Bước trước' : 
        '<i class="fas fa-arrow-right"></i> Bước tiếp';
      
      const opacity = Math.min(Math.abs(deltaX) / this.gestureThreshold, 1);
      feedback.style.opacity = opacity;
    }
  }
  
  hideGestureFeedback() {
    const feedback = document.querySelector('.gesture-feedback');
    if (feedback) {
      feedback.style.display = 'none';
    }
  }
  
  createGestureFeedback() {
    const feedback = document.createElement('div');
    feedback.className = 'gesture-feedback';
    feedback.style.cssText = `
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 1rem 2rem;
      border-radius: 50px;
      display: none;
      z-index: 1000;
      pointer-events: none;
      font-size: 1rem;
      font-weight: 600;
    `;
    document.body.appendChild(feedback);
    return feedback;
  }
  
  showGestureConfirmation(message) {
    const confirmation = document.createElement('div');
    confirmation.className = 'gesture-confirmation';
    confirmation.textContent = message;
    confirmation.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background: #10b981;
      color: white;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      z-index: 1000;
      animation: slideInRight 0.3s ease-out;
    `;
    
    document.body.appendChild(confirmation);
    
    setTimeout(() => {
      confirmation.style.animation = 'slideOutRight 0.3s ease-in forwards';
      setTimeout(() => {
        if (confirmation.parentNode) {
          confirmation.parentNode.removeChild(confirmation);
        }
      }, 300);
    }, 2000);
  }
}

// Error Recovery System
class ErrorRecovery {
  constructor() {
    this.errorPanel = document.getElementById('errorRecoveryPanel');
    this.initializeErrorRecovery();
  }
  
  initializeErrorRecovery() {
    if (!this.errorPanel) return;
    
    const dismissBtn = document.getElementById('dismissError');
    const fixBtn = document.getElementById('fixError');
    
    if (dismissBtn) {
      dismissBtn.addEventListener('click', () => this.dismissError());
    }
    
    if (fixBtn) {
      fixBtn.addEventListener('click', () => this.autoFixErrors());
    }
    
    this.setupErrorMonitoring();
  }
  
  setupErrorMonitoring() {
    const inputs = document.querySelectorAll('.form-input, .form-textarea');
    let errorCount = 0;
    
    inputs.forEach(input => {
      input.addEventListener('invalid', () => {
        errorCount++;
        if (errorCount >= 2) {
          this.showErrorPanel();
        }
      });
      
      input.addEventListener('input', () => {
        if (input.checkValidity() && errorCount > 0) {
          errorCount = Math.max(0, errorCount - 1);
          if (errorCount === 0) {
            this.hideErrorPanel();
          }
        }
      });
    });
  }
  
  showErrorPanel() {
    if (this.errorPanel) {
      this.errorPanel.classList.add('show');
    }
  }
  
  hideErrorPanel() {
    if (this.errorPanel) {
      this.errorPanel.classList.remove('show');
    }
  }
  
  dismissError() {
    this.hideErrorPanel();
  }
  
  autoFixErrors() {
    const inputs = document.querySelectorAll('.form-input, .form-textarea');
    let fixedCount = 0;
    
    inputs.forEach(input => {
      if (!input.checkValidity()) {
        // Auto-fix common issues
        if (input.type === 'email' && input.value && !input.value.includes('@')) {
          input.value += '@gmail.com';
          fixedCount++;
        } else if (input.type === 'tel' && input.value && input.value.length < 10) {
          input.value = '0' + input.value.replace(/\D/g, '');
          fixedCount++;
        } else if (input.required && !input.value.trim()) {
          input.focus();
          input.style.animation = 'fieldErrorShake 0.4s ease-in-out';
        }
        
        // Trigger validation
        input.dispatchEvent(new Event('input'));
      }
    });
    
    if (fixedCount > 0) {
      this.showFixConfirmation(`Đã sửa ${fixedCount} lỗi`);
      setTimeout(() => {
        this.hideErrorPanel();
      }, 2000);
    } else {
      this.hideErrorPanel();
    }
  }
  
  showFixConfirmation(message) {
    const confirmation = document.createElement('div');
    confirmation.className = 'fix-confirmation';
    confirmation.innerHTML = `
      <i class="fas fa-check-circle"></i>
      <span>${message}</span>
    `;
    confirmation.style.cssText = `
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: #10b981;
      color: white;
      padding: 1rem 2rem;
      border-radius: 8px;
      z-index: 1001;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      animation: slideInDown 0.3s ease-out;
    `;
    
    document.body.appendChild(confirmation);
    
    setTimeout(() => {
      confirmation.style.animation = 'slideOutUp 0.3s ease-in forwards';
      setTimeout(() => {
        if (confirmation.parentNode) {
          confirmation.parentNode.removeChild(confirmation);
        }
      }, 300);
    }, 3000);
  }
}

// Voice input functionality
function initializeVoiceInput() {
  document.querySelectorAll('.voice-input-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const inputId = this.id.replace('voice', '').toLowerCase();
      const input = document.getElementById(inputId);
      
      this.classList.add('listening');
      
      setTimeout(() => {
        this.classList.remove('listening');
        this.classList.add('processing');
        
        const sampleTexts = {
          name: 'Nguyễn Văn An',
          phone: '0123456789',
          email: 'user@gmail.com',
          address: 'Hà Nội, Việt Nam',
          note: 'Tôi muốn biết thêm thông tin về sản phẩm'
        };
        
        setTimeout(() => {
          if (input && sampleTexts[inputId]) {
            input.value = sampleTexts[inputId];
            input.dispatchEvent(new Event('input'));
          }
          this.classList.remove('processing');
          this.classList.add('success');
          
          setTimeout(() => {
            this.classList.remove('success');
          }, 1000);
        }, 1500);
      }, 2000);
    });
  });
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
      updateProgressIndicator();
    }, 100);
  });
  
  // Preload critical resources
  const criticalImages = [
    // Add any critical images here
  ];
  
  criticalImages.forEach(src => {
    const img = new Image();
    img.src = src;
  });
}

// Auto-resize textarea
function initializeTextareaAutoResize() {
  const textarea = document.getElementById('note');
  if (!textarea) return;
  
  textarea.addEventListener('input', function() {
    // Auto-resize
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
    
    // Character counter
    const charCount = this.value.length;
    let counter = this.parentElement.querySelector('.char-counter');
    
    if (!counter) {
      counter = document.createElement('div');
      counter.className = 'char-counter';
      counter.style.cssText = `
        position: absolute;
        bottom: 8px;
        right: 50px;
        font-size: 0.75rem;
        color: #9ca3af;
        transition: all 0.3s ease;
        font-weight: 500;
        background: rgba(255, 255, 255, 0.9);
        padding: 2px 6px;
        border-radius: 4px;
        backdrop-filter: blur(5px);
      `;
      this.parentElement.appendChild(counter);
    }
    
    counter.textContent = `${charCount}/1000`;
    
    if (charCount > 800) {
      counter.style.color = '#ef4444';
      counter.style.background = 'rgba(254, 242, 242, 0.9)';
    } else if (charCount > 500) {
      counter.style.color = '#f59e0b';
      counter.style.background = 'rgba(254, 251, 242, 0.9)';
    } else {
      counter.style.color = '#9ca3af';
      counter.style.background = 'rgba(255, 255, 255, 0.9)';
    }
  });
}

// Form completion progress tracking
function updateProgressIndicator() {
  const inputs = document.querySelectorAll('.form-input, .form-textarea');
  const completed = Array.from(inputs).filter(input => 
    input.value.trim() !== '' && input.checkValidity()
  ).length;
  
  const progress = (completed / inputs.length) * 100;
  
  let progressBar = document.querySelector('.form-progress');
  if (!progressBar) {
    progressBar = document.createElement('div');
    progressBar.className = 'form-progress';
    progressBar.innerHTML = `
      <div class="progress-header">
        <span class="progress-label">
          <i class="fas fa-tasks"></i>
          Tiến độ hoàn thành
        </span>
        <span class="progress-percent">0%</span>
      </div>
      <div class="progress-bar">
        <div class="progress-fill"></div>
      </div>
    `;
    progressBar.style.cssText = `
      margin-bottom: 2rem;
      padding: 1.5rem;
      background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    `;
    
    const formGrid = document.querySelector('.form-grid');
    if (formGrid) {
      formGrid.before(progressBar);
    }
  }
  
  const progressFill = progressBar.querySelector('.progress-fill');
  const progressPercent = progressBar.querySelector('.progress-percent');
  
  if (progressFill && progressPercent) {
    progressFill.style.width = `${progress}%`;
    progressPercent.textContent = `${Math.round(progress)}%`;
    
    if (progress === 100) {
      progressBar.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%)';
      progressBar.style.borderColor = '#10b981';
      
      // Celebration effect
      const celebration = document.createElement('div');
      celebration.className = 'form-complete-celebration';
      for (let i = 0; i < 30; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = Math.random() * 100 + '%';
        confetti.style.animationDelay = Math.random() * 3 + 's';
        celebration.appendChild(confetti);
      }
      document.body.appendChild(celebration);
      
      setTimeout(() => {
        if (celebration.parentNode) {
          document.body.removeChild(celebration);
        }
      }, 3000);
      
      // Enable form submission visual cue
      const submitBtn = document.getElementById('submitBtn');
      if (submitBtn) {
        submitBtn.style.animation = 'readyPulse 2s ease-in-out infinite';
      }
    }
  }
}

// Success message display
function showSuccessMessage() {
  const message = document.createElement('div');
  message.className = 'success-message';
  message.innerHTML = `
    <div class="success-content">
      <i class="fas fa-check-circle"></i>
      <h3>Gửi thành công!</h3>
      <p>Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
    </div>
  `;
  message.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease-out;
  `;
  
  document.body.appendChild(message);
  
  setTimeout(() => {
    message.style.animation = 'fadeOut 0.3s ease-in forwards';
    setTimeout(() => {
      if (message.parentNode) {
        document.body.removeChild(message);
      }
    }, 300);
  }, 3000);
}

// Add enhanced animation styles dynamically
function addDynamicStyles() {
  const additionalStyles = document.createElement('style');
  additionalStyles.textContent = `
    @keyframes slideInRight {
      from { transform: translateX(100%); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes slideInDown {
      from { transform: translate(-50%, -100%); opacity: 0; }
      to { transform: translate(-50%, 0); opacity: 1; }
    }
    
    @keyframes slideOutUp {
      from { transform: translate(-50%, 0); opacity: 1; }
      to { transform: translate(-50%, -100%); opacity: 0; }
    }
    
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
    
    .voice-input-btn.listening {
      background: #ef4444 !important;
      animation: voicePulse 1s ease-in-out infinite;
    }
    
    .voice-input-btn.processing {
      background: #f59e0b !important;
      animation: voiceProcess 0.8s ease-in-out infinite;
    }
    
    .voice-input-btn.success {
      background: #10b981 !important;
      animation: voiceSuccess 0.6s ease-out;
    }
    
    @keyframes voicePulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }
    
    @keyframes voiceProcess {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.6; }
    }
    
    @keyframes voiceSuccess {
      0% { transform: scale(1); }
      50% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }
    
    @keyframes fieldErrorShake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-4px); }
      75% { transform: translateX(4px); }
    }
    
    @keyframes readyPulse {
      0%, 100% { box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.25); }
      50% { box-shadow: 0 8px 25px 0 rgba(59, 130, 246, 0.4); }
    }
  `;
  document.head.appendChild(additionalStyles);
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  console.log('BookBee Contact Form - Initializing all systems...');
  
  // Initialize core systems
  window.stepIndicator = new StepIndicator();
  window.smartSuggestions = new SmartSuggestions();
  window.touchGestures = new TouchGestures();
  window.errorRecovery = new ErrorRecovery();
  
  // Initialize form functionality
  initializeFormSubmission();
  initializeRealTimeValidation();
  initializePhoneFormatting();
  initializeEmailAutocomplete();
  initializeVoiceInput();
  initializeTextareaAutoResize();
  
  // Initialize UI enhancements
  createParticles();
  updateProgressIndicator();
  initializeSmartStepProgression();
  optimizePerformance();
  addDynamicStyles();
  
  // Add event listeners for progress tracking
  document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
    input.addEventListener('input', updateProgressIndicator);
    input.addEventListener('blur', updateProgressIndicator);
  });
  
  console.log('BookBee Contact Form Enhanced - All systems initialized successfully');
});

// Export for external use
window.BookBeeContact = {
  StepIndicator,
  SmartSuggestions,
  TouchGestures,
  ErrorRecovery,
  initializeSmartStepProgression,
  arePersonalFieldsComplete,
  areContactFieldsComplete,
  updateProgressIndicator,
  createParticles
};