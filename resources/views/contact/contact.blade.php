@extends('layouts.app')
@section('title', 'Liên hệ')

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: #1f2937;
  }

  .contact-container {
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    min-height: 100vh;
    padding: 3rem 0;
    position: relative;
  }

  .contact-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
      radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
    pointer-events: none;
  }

  .contact-wrapper {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
  }

  .contact-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .contact-title {
    font-size: 2.75rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 1rem;
    letter-spacing: -0.025em;
    background: linear-gradient(135deg, #1f2937 0%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
  }

  .contact-subtitle {
    font-size: 1.125rem;
    color: #6b7280;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
    font-weight: 400;
  }

  .form-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 3.5rem;
    box-shadow: 
      0 1px 3px 0 rgba(0, 0, 0, 0.1),
      0 1px 2px 0 rgba(0, 0, 0, 0.06);
    border: 1px solid #f3f4f6;
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .form-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #10b981, #f59e0b, #ef4444);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .form-card:hover::before {
    opacity: 1;
  }

  .form-card:hover {
    box-shadow: 
      0 20px 25px -5px rgba(0, 0, 0, 0.1),
      0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transform: translateY(-4px);
  }

  .form-header {
    text-align: center;
    margin-bottom: 2.5rem;
  }

  .form-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
  }

  .form-subtitle {
    font-size: 1rem;
    color: #6b7280;
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.6;
  }

  /* Floating Label Effects */
  .form-field {
    position: relative;
    margin-bottom: 2rem;
  }

  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    transform-origin: left center;
  }

  .form-label::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #3b82f6, #10b981);
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 1px;
  }

  .form-field:focus-within .form-label::after {
    width: 100%;
  }

  .form-field:focus-within .form-label {
    color: #3b82f6;
    transform: translateY(-2px) scale(1.05);
  }

  .form-field.field-completed .form-label {
    color: #10b981;
    font-weight: 700;
  }

  .form-field.field-completed .form-label::after {
    width: 100%;
    background: #10b981;
  }

  /* Form Field Styling */
  .form-field {
    position: relative;
    margin-bottom: 1.5rem;
  }

  .form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    position: relative;
  }

  .form-label::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 0;
    height: 2px;
    background: #3b82f6;
    transition: width 0.3s ease;
  }

  .form-field:focus-within .form-label::after {
    width: 100%;
  }

  .form-field:focus-within .form-label {
    color: #3b82f6;
    transform: translateY(-2px);
  }

  .form-input, .form-textarea {
    width: 100%;
    padding: 1rem 3rem 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    font-family: inherit;
    background: #fafbfc;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    color: #1f2937;
    position: relative;
    backdrop-filter: blur(10px);
  }

  .form-input:hover, .form-textarea:hover {
    border-color: #d1d5db;
    transform: translateY(-1px);
    box-shadow: 
      0 4px 12px rgba(0, 0, 0, 0.05),
      0 2px 4px rgba(0, 0, 0, 0.02);
    background: #ffffff;
  }

  .form-input:focus, .form-textarea:focus {
    border-color: #3b82f6;
    background: #ffffff;
    transform: translateY(-2px);
    box-shadow: 
      0 0 0 4px rgba(59, 130, 246, 0.1),
      0 8px 25px rgba(59, 130, 246, 0.1),
      0 4px 12px rgba(0, 0, 0, 0.05);
  }

  .form-input:valid:not(:placeholder-shown) {
    border-color: #10b981;
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
    box-shadow: 
      0 0 0 1px rgba(16, 185, 129, 0.1),
      0 2px 8px rgba(16, 185, 129, 0.05);
  }

  .form-input:invalid:not(:placeholder-shown) {
    border-color: #ef4444;
    background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
    box-shadow: 
      0 0 0 1px rgba(239, 68, 68, 0.1),
      0 2px 8px rgba(239, 68, 68, 0.05);
    animation: inputShake 0.4s ease-in-out;
  }

  @keyframes inputShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    75% { transform: translateX(2px); }
  }

  .form-textarea {
    min-height: 140px;
    resize: vertical;
    line-height: 1.6;
    padding-right: 2.5rem;
  }

  /* Input Icons */
  .input-wrapper {
    position: relative;
  }

  .input-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.1rem;
    color: #9ca3af;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    pointer-events: none;
    z-index: 2;
  }

  .textarea-icon {
    top: 1.25rem;
    transform: none;
  }

  .form-field:focus-within .input-icon {
    color: #3b82f6;
    transform: translateY(-50%) scale(1.15) rotate(5deg);
  }

  .form-field:focus-within .textarea-icon {
    color: #3b82f6;
    transform: scale(1.15) rotate(5deg);
  }

  .form-input:valid:not(:placeholder-shown) + .input-icon,
  .form-textarea:valid:not(:placeholder-shown) + .input-icon {
    color: #10b981;
    transform: translateY(-50%) scale(1.1);
  }

  .form-textarea:valid:not(:placeholder-shown) + .textarea-icon {
    color: #10b981;
    transform: scale(1.1);
  }

  .form-input:invalid:not(:placeholder-shown) + .input-icon,
  .form-textarea:invalid:not(:placeholder-shown) + .input-icon {
    color: #ef4444;
    animation: shake 0.5s ease-in-out;
  }

  @keyframes shake {
    0%, 100% { transform: translateY(-50%) translateX(0); }
    25% { transform: translateY(-50%) translateX(-3px); }
    75% { transform: translateY(-50%) translateX(3px); }
  }

  /* Enhanced Icon Animations */
  .input-icon.fas.fa-user:hover { transform: translateY(-50%) scale(1.2) rotate(10deg); }
  .input-icon.fas.fa-phone:hover { transform: translateY(-50%) scale(1.2) rotate(-10deg); }
  .input-icon.fas.fa-envelope:hover { transform: translateY(-50%) scale(1.2) rotateY(15deg); }
  .input-icon.fas.fa-map-marker-alt:hover { transform: translateY(-50%) scale(1.3) bounce; }
  .textarea-icon.fas.fa-comment-alt:hover { transform: scale(1.2) rotate(10deg); }

  /* Success Alert */
  .success-alert {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 1.25rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: slideInDown 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 
      0 8px 25px rgba(16, 185, 129, 0.25),
      0 4px 12px rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
  }

  .success-alert::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: successShimmer 2s ease-in-out;
  }

  .success-alert .fas {
    font-size: 1.25rem;
    animation: successIcon 0.8s ease-out 0.3s both;
  }

  @keyframes successShimmer {
    0% { left: -100%; }
    100% { left: 100%; }
  }

  @keyframes successIcon {
    0% { 
      opacity: 0;
      transform: scale(0) rotate(-180deg);
    }
    50% {
      transform: scale(1.2) rotate(0deg);
    }
    100% { 
      opacity: 1;
      transform: scale(1) rotate(0deg);
    }
  }

  @keyframes slideInDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Button Styling */
  .submit-button {
    width: 100%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    padding: 1.25rem 2rem;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    outline: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    box-shadow: 
      0 4px 14px 0 rgba(59, 130, 246, 0.25),
      inset 0 1px 0 rgba(255, 255, 255, 0.1);
  }

  .submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
  }

  .submit-button:hover::before {
    left: 100%;
  }

  .submit-button:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    transform: translateY(-3px);
    box-shadow: 
      0 12px 32px 0 rgba(59, 130, 246, 0.35),
      inset 0 1px 0 rgba(255, 255, 255, 0.15);
  }

  .submit-button:hover .button-icon {
    transform: translateX(5px) rotate(15deg);
  }

  .submit-button:active {
    transform: translateY(-1px);
    box-shadow: 
      0 6px 20px 0 rgba(59, 130, 246, 0.25),
      inset 0 1px 0 rgba(255, 255, 255, 0.1);
  }

  .submit-button:focus {
    box-shadow: 
      0 0 0 4px rgba(59, 130, 246, 0.2),
      0 12px 32px 0 rgba(59, 130, 246, 0.35);
  }

  .button-icon {
    font-size: 1rem;
    transition: all 0.3s ease;
  }

  /* Loading state */
  .submit-button.loading {
    background: linear-gradient(135deg, #9ca3af, #6b7280);
    cursor: not-allowed;
    transform: none;
  }

  .submit-button.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 3;
  }

  /* Success State Animation */
  .submit-button.success {
    background: linear-gradient(135deg, #10b981, #059669);
    transform: scale(1.05);
  }

  .submit-button.success::before {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.5rem;
    font-weight: bold;
    z-index: 3;
    animation: successPop 0.6s ease-out;
  }

  @keyframes successPop {
    0% { transform: translate(-50%, -50%) scale(0) rotate(-180deg); }
    50% { transform: translate(-50%, -50%) scale(1.2) rotate(0deg); }
    100% { transform: translate(-50%, -50%) scale(1) rotate(0deg); }
  }

  /* Tooltip System */
  .form-field[data-tooltip] {
    position: relative;
  }

  .form-field[data-tooltip]::before {
    content: attr(data-tooltip);
    position: absolute;
    top: -45px;
    left: 50%;
    transform: translateX(-50%);
    background: #1f2937;
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 10;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .form-field[data-tooltip]::after {
    content: '';
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: #1f2937;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 10;
  }

  .form-field[data-tooltip]:hover::before,
  .form-field[data-tooltip]:hover::after {
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-2px);
  }

  /* Pulse Effect for Interactive Elements */
  @keyframes gentlePulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
  }

  .form-card:hover {
    animation: gentlePulse 2s ease-in-out infinite;
  }

  /* Label Icon Styling */
  .label-icon {
    font-size: 0.875rem;
    margin-right: 0.5rem;
    color: #6b7280;
    transition: all 0.3s ease;
  }

  .form-field:focus-within .label-icon {
    color: #3b82f6;
    transform: scale(1.1) rotate(5deg);
  }

  .form-field.field-completed .label-icon {
    color: #10b981;
    filter: drop-shadow(0 0 2px currentColor);
  }

  /* Form Grid Responsive */
  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .form-field.full-width {
    grid-column: 1 / -1;
  }

  @media (max-width: 768px) {
    .contact-container {
      padding: 2rem 0;
    }

    .contact-title {
      font-size: 2.25rem;
    }

    .contact-subtitle {
      font-size: 1rem;
    }

    .form-card {
      padding: 2.5rem 2rem;
      border-radius: 20px;
    }
    
    .label-icon {
      display: none;
    }
    
    .form-field[data-tooltip]::before,
    .form-field[data-tooltip]::after {
      display: none;
    }
  }

  @media (max-width: 480px) {
    .contact-container {
      padding: 1.5rem 0;
    }

    .contact-title {
      font-size: 1.75rem;
    }

    .form-card {
      padding: 1.5rem 1rem;
      margin: 0 0.5rem;
    }
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>

@section('content')
<div class="contact-container">
  <div class="contact-wrapper">
    <div class="contact-header">
      <h2 class="contact-title">Liên hệ với chúng tôi</h2>
      <p class="contact-subtitle">Hãy gửi góp ý hoặc câu hỏi, chúng tôi sẽ phản hồi qua email của bạn.</p>
      
      @if(session('success'))
        <div class="success-alert">
          <i class="fas fa-check-circle"></i>
          {{ session('success') }}
        </div>
      @endif
    </div>

    <div class="form-card">
      <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
        @csrf
        
        <div class="form-grid">
          <div class="form-field" data-tooltip="Nhập họ và tên đầy đủ của bạn">
            <label for="name" class="form-label">
              <i class="fas fa-user label-icon"></i>
              Họ tên
            </label>
            <div class="input-wrapper">
              <input type="text" name="name" id="name" autocomplete="name" required
                     class="form-input"
                     placeholder="Nhập họ tên" value="{{ old('name') }}">
              <i class="fas fa-user input-icon"></i>
            </div>
          </div>

          <div class="form-field" data-tooltip="Số điện thoại để chúng tôi liên hệ trực tiếp">
            <label for="phone" class="form-label">
              <i class="fas fa-phone label-icon"></i>
              Số điện thoại
            </label>
            <div class="input-wrapper">
              <input type="text" name="phone" id="phone" autocomplete="tel" required
                     class="form-input"
                     placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
              <i class="fas fa-phone input-icon"></i>
            </div>
          </div>

          <div class="form-field full-width" data-tooltip="Email để nhận phản hồi từ chúng tôi">
            <label for="email" class="form-label">
              <i class="fas fa-envelope label-icon"></i>
              Email
            </label>
            <div class="input-wrapper">
              <input type="email" name="email" id="email" autocomplete="email" required
                     class="form-input"
                     placeholder="Nhập email" value="{{ old('email') }}">
              <i class="fas fa-envelope input-icon"></i>
            </div>
          </div>

          <div class="form-field full-width" data-tooltip="Địa chỉ chi tiết để hỗ trợ tốt hơn">
            <label for="address" class="form-label">
              <i class="fas fa-map-marker-alt label-icon"></i>
              Địa chỉ
            </label>
            <div class="input-wrapper">
              <input type="text" name="address" id="address" autocomplete="address" required
                     class="form-input"
                     placeholder="Nhập địa chỉ" value="{{ old('address') }}">
              <i class="fas fa-map-marker-alt input-icon"></i>
            </div>
          </div>

          <div class="form-field full-width" data-tooltip="Mô tả chi tiết vấn đề hoặc góp ý của bạn">
            <label for="note" class="form-label">
              <i class="fas fa-comment-alt label-icon"></i>
              Nội dung
            </label>
            <div class="input-wrapper">
              <textarea name="note" id="note" rows="4" required
                        class="form-textarea"
                        placeholder="Nhập nội dung chi tiết...">{{ old('note') }}</textarea>
              <i class="fas fa-comment-alt input-icon textarea-icon"></i>
            </div>
          </div>
        </div>

        <button type="submit" class="submit-button" id="submitBtn">
          <i class="fas fa-paper-plane button-icon"></i>
          <span class="button-text">Gửi liên hệ</span>
        </button>
      </form>
    </div>
  </div>
</div>

<script>
// Enhanced form submission with success animation
document.getElementById('contactForm').addEventListener('submit', function(e) {
  const submitBtn = document.getElementById('submitBtn');
  const buttonText = submitBtn.querySelector('.button-text');
  const buttonIcon = submitBtn.querySelector('.button-icon');
  
  submitBtn.classList.add('loading');
  submitBtn.disabled = true;
  
  // Simulate processing stages
  setTimeout(() => {
    if (submitBtn.classList.contains('loading')) {
      buttonText.textContent = 'Đang xử lý...';
      buttonText.style.opacity = '1';
    }
  }, 800);
  
  setTimeout(() => {
    if (submitBtn.classList.contains('loading')) {
      buttonText.textContent = 'Đang gửi...';
    }
  }, 1500);
});

// Advanced real-time validation with enhanced animations
document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
  input.addEventListener('blur', function() {
    const field = this.closest('.form-field');
    
    if (this.value.trim() !== '' && this.checkValidity()) {
      this.classList.add('has-value');
      field.classList.add('field-completed');
      
      // Add completion celebration
      const icon = field.querySelector('.input-icon');
      if (icon) {
        icon.style.animation = 'completionCelebration 0.6s ease-out';
        setTimeout(() => {
          icon.style.animation = 'none';
        }, 600);
      }
    } else {
      this.classList.remove('has-value');
      field.classList.remove('field-completed');
    }
  });
  
  input.addEventListener('input', function() {
    const icon = this.parentElement.querySelector('.input-icon');
    const field = this.closest('.form-field');
    
    if (this.checkValidity() && this.value.length > 0) {
      this.style.borderColor = '#10b981';
      this.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%)';
      if (icon) {
        icon.style.color = '#10b981';
        icon.style.animation = 'validPulse 0.6s ease-out';
      }
    } else if (this.value.length > 0 && !this.checkValidity()) {
      this.style.borderColor = '#ef4444';
      this.style.background = 'linear-gradient(135deg, #fef2f2 0%, #ffffff 100%)';
      if (icon) {
        icon.style.color = '#ef4444';
        icon.style.animation = 'shake 0.5s ease-in-out';
      }
      // Add error field shake
      field.style.animation = 'fieldErrorShake 0.4s ease-in-out';
    } else {
      this.style.borderColor = '#e5e7eb';
      this.style.background = '#fafbfc';
      if (icon) {
        icon.style.color = '#9ca3af';
        icon.style.animation = 'none';
      }
    }
    
    // Reset field animation
    setTimeout(() => {
      field.style.animation = 'none';
      if (icon) icon.style.animation = 'none';
    }, 600);
  });
  
  // Enhanced focus effects with ripple animation
  input.addEventListener('focus', function() {
    const field = this.closest('.form-field');
    field.classList.add('field-focused');
    
    // Create ripple effect
    const ripple = document.createElement('div');
    ripple.className = 'focus-ripple';
    ripple.style.cssText = `
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      background: rgba(59, 130, 246, 0.1);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      animation: rippleExpand 0.6s ease-out;
      pointer-events: none;
      z-index: 1;
    `;
    
    this.parentElement.appendChild(ripple);
    
    setTimeout(() => {
      if (ripple.parentElement) {
        ripple.parentElement.removeChild(ripple);
      }
    }, 600);
  });
  
  input.addEventListener('blur', function() {
    const field = this.closest('.form-field');
    field.classList.remove('field-focused');
  });
});

// Smart phone number formatting with international support
document.getElementById('phone').addEventListener('input', function(e) {
  let value = e.target.value.replace(/\D/g, '');
  
  if (value.length >= 10) {
    value = value.substring(0, 10);
    // Vietnamese phone format
    if (value.startsWith('0')) {
      value = value.replace(/(\d{4})(\d{3})(\d{3})/, '$1 $2 $3');
    } else {
      value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
    }
  }
  
  e.target.value = value;
  
  // Phone validation indicator
  const isValid = value.replace(/\D/g, '').length === 10;
  if (isValid) {
    this.style.borderColor = '#10b981';
    this.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%)';
  }
});

// Advanced textarea with smart features
const textarea = document.getElementById('note');
let typingTimer;

textarea.addEventListener('input', function() {
  // Auto-resize with smooth animation
  this.style.height = 'auto';
  this.style.height = (this.scrollHeight) + 'px';
  
  // Character counter with color coding
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
    `;
    this.parentElement.appendChild(counter);
  }
  
  counter.textContent = `${charCount}/1000`;
  
  if (charCount > 800) {
    counter.style.color = '#ef4444';
    counter.style.background = '#fef2f2';
  } else if (charCount > 500) {
    counter.style.color = '#f59e0b';
    counter.style.background = '#fefbf2';
  } else {
    counter.style.color = '#9ca3af';
    counter.style.background = 'rgba(255, 255, 255, 0.9)';
  }
  
  // Typing indicator
  clearTimeout(typingTimer);
  this.classList.add('typing');
  
  typingTimer = setTimeout(() => {
    this.classList.remove('typing');
  }, 1000);
});

// Progressive form completion with celebration
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
    
    const progressHeader = progressBar.querySelector('.progress-header');
    progressHeader.style.cssText = `
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      font-size: 0.875rem;
      font-weight: 600;
    `;
    
    const progressLabel = progressBar.querySelector('.progress-label');
    progressLabel.style.cssText = `
      color: #374151;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    `;
    
    const progressBarElement = progressBar.querySelector('.progress-bar');
    progressBarElement.style.cssText = `
      width: 100%;
      height: 8px;
      background: #e2e8f0;
      border-radius: 4px;
      overflow: hidden;
      position: relative;
    `;
    
    const progressFill = progressBar.querySelector('.progress-fill');
    progressFill.style.cssText = `
      height: 100%;
      background: linear-gradient(90deg, #3b82f6, #10b981);
      transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 4px;
      position: relative;
    `;
    
    document.querySelector('.form-grid').before(progressBar);
  }
  
  const progressFill = progressBar.querySelector('.progress-fill');
  const progressPercent = progressBar.querySelector('.progress-percent');
  
  progressFill.style.width = `${progress}%`;
  progressPercent.textContent = `${Math.round(progress)}%`;
  
  if (progress === 100) {
    progressBar.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%)';
    progressBar.style.borderColor = '#10b981';
    progressBar.style.transform = 'scale(1.02)';
    
    // Celebration effect
    setTimeout(() => {
      progressBar.style.transform = 'scale(1)';
    }, 300);
    
    // Enable form submission visual cue
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.style.animation = 'readyPulse 2s ease-in-out infinite';
  } else {
    progressBar.style.background = 'linear-gradient(135deg, #f8fafc 0%, #ffffff 100%)';
    progressBar.style.borderColor = '#e2e8f0';
    progressBar.style.transform = 'scale(1)';
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.style.animation = 'none';
  }
}

// Add event listeners for progress tracking
document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
  input.addEventListener('input', updateProgressIndicator);
  input.addEventListener('blur', updateProgressIndicator);
});

// Initialize progress indicator
updateProgressIndicator();

// Add enhanced animation styles
const style = document.createElement('style');
style.textContent = `
  @keyframes completionCelebration {
    0% { transform: scale(1); }
    25% { transform: scale(1.3) rotate(10deg); }
    50% { transform: scale(1.1) rotate(-5deg); }
    75% { transform: scale(1.2) rotate(5deg); }
    100% { transform: scale(1.1); }
  }
  
  @keyframes fieldErrorShake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
  }
  
  @keyframes rippleExpand {
    0% { width: 0; height: 0; opacity: 1; }
    100% { width: 200px; height: 200px; opacity: 0; }
  }
  
  @keyframes readyPulse {
    0%, 100% { box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.25); }
    50% { box-shadow: 0 8px 25px 0 rgba(59, 130, 246, 0.4); }
  }
  
  .typing {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1) !important;
  }
  
  .field-focused .input-icon {
    animation: focusBounce 0.4s ease-out;
  }
  
  @keyframes focusBounce {
    0% { transform: translateY(-50%) scale(1); }
    50% { transform: translateY(-50%) scale(1.2) rotate(10deg); }
    100% { transform: translateY(-50%) scale(1.1); }
  }
  
  .field-completed .input-icon {
    filter: drop-shadow(0 0 4px currentColor);
  }
`;
document.head.appendChild(style);
</script>
@endsection
