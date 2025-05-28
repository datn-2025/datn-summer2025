@extends('layouts.app')
@section('title', 'Liên hệ')

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Contact Form CSS -->
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">

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
    background: #ffffff !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    color: #1f2937 !important;
    position: relative;
    backdrop-filter: blur(10px);
    -webkit-text-fill-color: #1f2937 !important;
    -webkit-appearance: none;
    opacity: 1 !important;
    caret-color: #3b82f6;
  }

  .form-input:hover, .form-textarea:hover {
    border-color: #d1d5db;
    transform: translateY(-1px);
    box-shadow: 
      0 4px 12px rgba(0, 0, 0, 0.05),
      0 2px 4px rgba(0, 0, 0, 0.02);
    background: #ffffff !important;
    color: #1f2937 !important;
    -webkit-text-fill-color: #1f2937 !important;
  }

  .form-input:focus, .form-textarea:focus {
    border-color: #3b82f6;
    background: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 
      0 0 0 4px rgba(59, 130, 246, 0.1),
      0 8px 25px rgba(59, 130, 246, 0.1),
      0 4px 12px rgba(0, 0, 0, 0.05);
    color: #1f2937 !important;
    -webkit-text-fill-color: #1f2937 !important;
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

  /* Advanced Floating Particles Background */
  .particles-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
    z-index: 0;
  }

  .particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: linear-gradient(45deg, #3b82f6, #10b981);
    border-radius: 50%;
    opacity: 0.6;
    animation: floatUp 15s infinite linear;
  }

  .particle:nth-child(odd) {
    background: linear-gradient(45deg, #f59e0b, #ef4444);
    animation-duration: 20s;
  }

  .particle:nth-child(3n) {
    background: linear-gradient(45deg, #8b5cf6, #ec4899);
    animation-duration: 18s;
  }

  @keyframes floatUp {
    0% {
      transform: translateY(100vh) rotate(0deg);
      opacity: 0;
    }
    10% {
      opacity: 0.6;
    }
    90% {
      opacity: 0.6;
    }
    100% {
      transform: translateY(-10vh) rotate(360deg);
      opacity: 0;
    }
  }

  /* Smart Focus Enhancement */
  .form-field.smart-focus {
    transform: scale(1.02);
    z-index: 10;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .form-field.smart-focus::before {
    content: '';
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    background: linear-gradient(45deg, transparent, rgba(59, 130, 246, 0.05), transparent);
    border-radius: 16px;
    z-index: -1;
    animation: smartGlow 2s ease-in-out infinite;
  }

  @keyframes smartGlow {
    0%, 100% { opacity: 0; }
    50% { opacity: 1; }
  }

  /* Enhanced Input Ripple Effects */
  .input-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(59, 130, 246, 0.3);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
  }

  @keyframes ripple {
    to {
      transform: scale(4);
      opacity: 0;
    }
  }

  /* Advanced Button Hover Effects */
  .submit-button::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
    transition: all 0.6s ease;
    transform: translate(-50%, -50%);
    border-radius: 50%;
  }

  .submit-button:hover::after {
    width: 300px;
    height: 300px;
  }

  /* Interactive Form Validation Messages */
  .validation-message {
    position: absolute;
    bottom: -1.8rem;
    left: 0;
    font-size: 0.75rem;
    color: #ef4444;
    opacity: 0;
    transform: translateY(-5px);
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    background: rgba(255, 255, 255, 0.95);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
  }

  .validation-message.show {
    opacity: 1;
    transform: translateY(0);
  }

  .validation-message.success {
    color: #10b981;
    background: rgba(240, 253, 244, 0.95);
  }

  .validation-message i {
    font-size: 0.75rem;
  }

  /* Typing Indicator */
  .typing-indicator {
    position: absolute;
    bottom: -1.5rem;
    right: 0;
    font-size: 0.7rem;
    color: #3b82f6;
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }

  .typing-indicator.show {
    opacity: 1;
  }

  .typing-dots {
    display: flex;
    gap: 2px;
  }

  .typing-dot {
    width: 3px;
    height: 3px;
    background: #3b82f6;
    border-radius: 50%;
    animation: typingDots 1.4s infinite ease-in-out;
  }

  .typing-dot:nth-child(1) { animation-delay: -0.32s; }
  .typing-dot:nth-child(2) { animation-delay: -0.16s; }

  @keyframes typingDots {
    0%, 80%, 100% {
      transform: scale(0);
      opacity: 0.5;
    }
    40% {
      transform: scale(1);
      opacity: 1;
    }
  }

  /* Enhanced Mobile Gestures */
  .form-field.swipe-left {
    animation: swipeLeft 0.3s ease-out;
  }

  .form-field.swipe-right {
    animation: swipeRight 0.3s ease-out;
  }

  @keyframes swipeLeft {
    0% { transform: translateX(0); }
    50% { transform: translateX(-10px); }
    100% { transform: translateX(0); }
  }

  @keyframes swipeRight {
    0% { transform: translateX(0); }
    50% { transform: translateX(10px); }
    100% { transform: translateX(0); }
  }

  /* Smart Auto-Complete Suggestions */
  .autocomplete-container {
    position: relative;
  }

  .autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 20;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
  }

  .autocomplete-suggestions.show {
    opacity: 1;
    transform: translateY(0);
  }

  .suggestion-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .suggestion-item:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #3b82f6;
    transform: translateX(5px);
  }

  .suggestion-item:last-child {
    border-bottom: none;
  }

  /* Voice Input Indicator */
  .voice-input-btn {
    position: absolute;
    right: 3rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0.25rem;
    border-radius: 4px;
  }

  .voice-input-btn:hover {
    color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
  }

  .voice-input-btn.listening {
    color: #ef4444;
    animation: pulse 1s infinite;
  }

  @keyframes pulse {
    0%, 100% { transform: translateY(-50%) scale(1); }
    50% { transform: translateY(-50%) scale(1.1); }
  }

  /* Form Completion Celebration */
  .form-complete-celebration {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
  }

  .confetti {
    position: absolute;
    width: 8px;
    height: 8px;
    background: #3b82f6;
    animation: confettiFall 3s ease-out forwards;
  }

  .confetti:nth-child(odd) { background: #10b981; }
  .confetti:nth-child(3n) { background: #f59e0b; }
  .confetti:nth-child(4n) { background: #ef4444; }
  .confetti:nth-child(5n) { background: #8b5cf6; }

  @keyframes confettiFall {
    0% {
      transform: translateY(-100vh) rotate(0deg);
      opacity: 1;
    }
    100% {
      transform: translateY(100vh) rotate(720deg);
      opacity: 0;
    }
  }

  /* Enhanced Mobile Experience */
  @media (max-width: 768px) {
    .form-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .particles-container {
      display: none;
    }

    .form-field.smart-focus {
      transform: none;
    }

    .form-field.smart-focus::before {
      display: none;
    }

    .voice-input-btn {
      display: none;
    }
  }

  /* Accessibility Enhancements */
  .form-input:focus-visible,
  .form-textarea:focus-visible {
    outline: 3px solid #3b82f6;
    outline-offset: 2px;
  }

  .high-contrast .form-input,
  .high-contrast .form-textarea {
    border-width: 3px;
    font-weight: 600;
  }

  .high-contrast .form-label {
    font-weight: 700;
    font-size: 1rem;
  }

  /* Form Loading State */
  .form-submitting {
    pointer-events: none;
    filter: blur(1px);
    opacity: 0.7;
    transition: all 0.3s ease;
    position: relative;
  }

  .form-submitting::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(59, 130, 246, 0.1) 50%, transparent 70%);
    animation: shimmer 2s infinite;
  }

  @keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
  }

  /* Advanced Multi-step Visual Flow */
  .form-step-indicator {
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 16px;
    backdrop-filter: blur(10px);
  }

  .step-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    margin: 0 0.5rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.7);
    border: 2px solid #e5e7eb;
    color: #6b7280;
  }

  .step-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
    transition: left 0.6s ease;
  }

  .step-item.active::before {
    left: 100%;
  }

  .step-item.completed {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    transform: scale(1.05);
    border-color: #10b981;
  }

  .step-item.active {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    transform: scale(1.1);
    border-color: #3b82f6;
  }

  .step-icon {
    margin-right: 0.5rem;
    font-size: 0.875rem;
  }

  .step-text {
    font-size: 0.75rem;
    font-weight: 600;
  }

  /* Intelligent Form Suggestions */
  .smart-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(20px);
    max-height: 180px;
    overflow-y: auto;
    z-index: 30;
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .smart-suggestions.show {
    opacity: 1;
    transform: translateY(0) scale(1);
  }

  .suggestion-category {
    padding: 0.75rem 1rem 0.25rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: rgba(249, 250, 251, 0.8);
    border-bottom: 1px solid #f3f4f6;
  }

  .smart-suggestion-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid rgba(243, 244, 246, 0.5);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.875rem;
  }

  .smart-suggestion-item:hover {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%);
    transform: translateX(8px);
    border-left: 3px solid #3b82f6;
  }

  .smart-suggestion-item:last-child {
    border-bottom: none;
  }

  .suggestion-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
  }

  /* Advanced Touch Gestures */
  .touch-gesture-indicator {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 8px 32px rgba(59, 130, 246, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 100;
    opacity: 0.8;
  }

  .touch-gesture-indicator:hover {
    transform: scale(1.1);
    opacity: 1;
  }

  .gesture-tooltip {
    position: absolute;
    bottom: 70px;
    right: 0;
    background: #1f2937;
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
  }

  .touch-gesture-indicator:hover .gesture-tooltip {
    opacity: 1;
    transform: translateY(0);
  }

  /* Smart Error Recovery */
  .error-recovery-panel {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    max-width: 400px;
    width: 90%;
    opacity: 0;
    visibility: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .error-recovery-panel.show {
    opacity: 1;
    visibility: visible;
    transform: translate(-50%, -50%) scale(1);
  }

  .error-recovery-header {
    text-align: center;
    margin-bottom: 1.5rem;
  }

  .error-recovery-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
    animation: errorPulse 2s ease-in-out infinite;
  }

  @keyframes errorPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
  }

  .error-recovery-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
  }

  .error-recovery-description {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.6;
  }

  .error-recovery-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
  }

  .recovery-btn {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
  }

  .recovery-btn.primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
  }

  .recovery-btn.secondary {
    background: #f3f4f6;
    color: #6b7280;
  }

  .recovery-btn:hover {
    transform: translateY(-2px);
  }

  /* Enhanced Performance Optimizations */
  .optimized-input {
    will-change: transform, box-shadow;
    transform: translateZ(0);
  }

  .gpu-accelerated {
    transform: translate3d(0, 0, 0);
    backface-visibility: hidden;
  }

  /* Smart Field Grouping */
  .field-group {
    background: rgba(248, 250, 252, 0.5);
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(226, 232, 240, 0.5);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .field-group::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #10b981, #f59e0b);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .field-group.active::before {
    opacity: 1;
  }

  .field-group-title {
    font-size: 1rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .group-icon {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #3b82f6, #10b981);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
  }

  /* Advanced Mobile Responsiveness */
  @media (max-width: 768px) {
    .form-step-indicator {
      overflow-x: auto;
      justify-content: flex-start;
      padding: 0.75rem;
    }

    .step-item {
      flex-shrink: 0;
      min-width: 80px;
    }

    .step-text {
      display: none;
    }

    .smart-suggestions {
      position: fixed;
      top: auto;
      bottom: 0;
      left: 0;
      right: 0;
      border-radius: 20px 20px 0 0;
      max-height: 50vh;
    }

    .touch-gesture-indicator {
      bottom: 1rem;
      right: 1rem;
      width: 50px;
      height: 50px;
    }

    .field-group {
      margin: 0 -1rem 1.5rem;
      border-radius: 0;
      border-left: none;
      border-right: none;
    }
  }

  /* Dark Mode Support */
  @media (prefers-color-scheme: dark) {
    .form-card {
      background: #1f2937;
      border-color: #374151;
      color: #f9fafb;
    }

    .form-input,
    .form-textarea {
      background: #374151;
      border-color: #4b5563;
      color: #f9fafb;
    }

    .form-input:focus,
    .form-textarea:focus {
      background: #4b5563;
      border-color: #3b82f6;
    }

    .smart-suggestions {
      background: rgba(31, 41, 55, 0.95);
      border-color: #4b5563;
    }

    .smart-suggestion-item:hover {
      background: rgba(59, 130, 246, 0.1);
    }
  }

  /* Reduced Motion Support */
  @media (prefers-reduced-motion: reduce) {
    * {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
    }

    .particle {
      display: none;
    }

    .form-card:hover {
      transform: none;
    }
  }
</style>

@section('content')
<div class="contact-container">
  <!-- Floating Particles Background -->
  <div class="particles-container" id="particles"></div>
  
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
      <!-- Advanced Multi-step Visual Flow -->
      <div class="form-step-indicator" id="stepIndicator">
        <div class="step-item active" data-step="1">
          <i class="fas fa-user step-icon"></i>
          <span class="step-text">Thông tin cá nhân</span>
        </div>
        <div class="step-item" data-step="2">
          <i class="fas fa-envelope step-icon"></i>
          <span class="step-text">Liên hệ</span>
        </div>
        <div class="step-item" data-step="3">
          <i class="fas fa-comment step-icon"></i>
          <span class="step-text">Nội dung</span>
        </div>
        <div class="step-item" data-step="4">
          <i class="fas fa-check step-icon"></i>
          <span class="step-text">Hoàn thành</span>
        </div>
      </div>

      <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
        @csrf
        
        <!-- Smart Field Grouping -->
        <div class="field-group" data-group="personal">
          <div class="field-group-title">
            <div class="group-icon">
              <i class="fas fa-user"></i>
            </div>
            Thông tin cá nhân
          </div>
          
          <div class="form-grid">
            <div class="form-field autocomplete-container" data-tooltip="Nhập họ và tên đầy đủ của bạn">
              <label for="name" class="form-label">
                <i class="fas fa-user label-icon"></i>
                Họ tên
              </label>
              <div class="input-wrapper">
                <input type="text" name="name" id="name" autocomplete="name" required
                       class="form-input optimized-input"
                       placeholder="Nhập họ tên" value="{{ old('name') }}">
                <button type="button" class="voice-input-btn" id="voiceName" title="Nhập bằng giọng nói">
                  <i class="fas fa-microphone"></i>
                </button>
                <i class="fas fa-user input-icon"></i>
              </div>
              <div class="smart-suggestions" id="name-suggestions"></div>
              <div class="validation-message" id="name-validation"></div>
              <div class="typing-indicator" id="name-typing">
                <span>Đang nhập</span>
                <div class="typing-dots">
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                </div>
              </div>
            </div>

            <div class="form-field" data-tooltip="Số điện thoại để chúng tôi liên hệ trực tiếp">
              <label for="phone" class="form-label">
                <i class="fas fa-phone label-icon"></i>
                Số điện thoại
              </label>
              <div class="input-wrapper">
                <input type="text" name="phone" id="phone" autocomplete="tel" required
                       class="form-input optimized-input"
                       placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
                <button type="button" class="voice-input-btn" id="voicePhone" title="Nhập bằng giọng nói">
                  <i class="fas fa-microphone"></i>
                </button>
                <i class="fas fa-phone input-icon"></i>
              </div>
              <div class="validation-message" id="phone-validation"></div>
              <div class="typing-indicator" id="phone-typing">
                <span>Đang nhập</span>
                <div class="typing-dots">
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="field-group" data-group="contact">
          <div class="field-group-title">
            <div class="group-icon">
              <i class="fas fa-envelope"></i>
            </div>
            Thông tin liên hệ
          </div>

          <div class="form-grid">
            <div class="form-field full-width autocomplete-container" data-tooltip="Email để nhận phản hồi từ chúng tôi">
              <label for="email" class="form-label">
                <i class="fas fa-envelope label-icon"></i>
                Email
              </label>
              <div class="input-wrapper">
                <input type="email" name="email" id="email" autocomplete="email" required
                       class="form-input optimized-input"
                       placeholder="Nhập email" value="{{ old('email') }}">
                <button type="button" class="voice-input-btn" id="voiceEmail" title="Nhập bằng giọng nói">
                  <i class="fas fa-microphone"></i>
                </button>
                <i class="fas fa-envelope input-icon"></i>
              </div>
              <div class="autocomplete-suggestions" id="email-suggestions"></div>
              <div class="validation-message" id="email-validation"></div>
              <div class="typing-indicator" id="email-typing">
                <span>Đang nhập</span>
                <div class="typing-dots">
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                </div>
              </div>
            </div>

            <div class="form-field full-width" data-tooltip="Địa chỉ chi tiết để hỗ trợ tốt hơn">
              <label for="address" class="form-label">
                <i class="fas fa-map-marker-alt label-icon"></i>
                Địa chỉ
              </label>
              <div class="input-wrapper">
                <input type="text" name="address" id="address" autocomplete="address" required
                       class="form-input optimized-input"
                       placeholder="Nhập địa chỉ" value="{{ old('address') }}">
                <button type="button" class="voice-input-btn" id="voiceAddress" title="Nhập bằng giọng nói">
                  <i class="fas fa-microphone"></i>
                </button>
                <i class="fas fa-map-marker-alt input-icon"></i>
              </div>
              <div class="smart-suggestions" id="address-suggestions"></div>
              <div class="validation-message" id="address-validation"></div>
              <div class="typing-indicator" id="address-typing">
                <span>Đang nhập</span>
                <div class="typing-dots">
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="field-group" data-group="message">
          <div class="field-group-title">
            <div class="group-icon">
              <i class="fas fa-comment"></i>
            </div>
            Nội dung tin nhắn
          </div>

          <div class="form-grid">
            <div class="form-field full-width" data-tooltip="Mô tả chi tiết vấn đề hoặc góp ý của bạn">
              <label for="note" class="form-label">
                <i class="fas fa-comment-alt label-icon"></i>
                Nội dung
              </label>
              <div class="input-wrapper">
                <textarea name="note" id="note" rows="4" required
                          class="form-textarea optimized-input"
                          placeholder="Nhập nội dung chi tiết...">{{ old('note') }}</textarea>
                <button type="button" class="voice-input-btn" id="voiceNote" title="Nhập bằng giọng nói" 
                        style="top: 1.25rem; transform: none;">
                  <i class="fas fa-microphone"></i>
                </button>
                <i class="fas fa-comment-alt input-icon textarea-icon"></i>
              </div>
              <div class="smart-suggestions" id="note-suggestions"></div>
              <div class="validation-message" id="note-validation"></div>
              <div class="typing-indicator" id="note-typing">
                <span>Đang nhập</span>
                <div class="typing-dots">
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                  <div class="typing-dot"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" class="submit-button gpu-accelerated" id="submitBtn">
          <i class="fas fa-paper-plane button-icon"></i>
          <span class="button-text">Gửi liên hệ</span>
        </button>
      </form>
    </div>

    <!-- Advanced Touch Gestures -->
    <div class="touch-gesture-indicator" id="gestureHelper">
      <i class="fas fa-hand-paper"></i>
      <div class="gesture-tooltip">Vuốt để điều hướng</div>
    </div>

    <!-- Smart Error Recovery Panel -->
    <div class="error-recovery-panel" id="errorRecoveryPanel">
      <div class="error-recovery-header">
        <div class="error-recovery-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="error-recovery-title">Có lỗi xảy ra</h3>
        <p class="error-recovery-description">
          Chúng tôi đã phát hiện một số vấn đề với form. Bạn có muốn chúng tôi giúp khắc phục không?
        </p>
      </div>
      <div class="error-recovery-actions">
        <button class="recovery-btn secondary" id="dismissError">Bỏ qua</button>
        <button class="recovery-btn primary" id="fixError">Tự động sửa</button>
      </div>
    </div>
  </div>
</div>
        
        <div class="form-grid">
          <div class="form-field autocomplete-container" data-tooltip="Nhập họ và tên đầy đủ của bạn">
            <label for="name" class="form-label">
              <i class="fas fa-user label-icon"></i>
              Họ tên
            </label>
            <div class="input-wrapper">
              <input type="text" name="name" id="name" autocomplete="name" required
                     class="form-input"
                     placeholder="Nhập họ tên" value="{{ old('name') }}">
              <button type="button" class="voice-input-btn" id="voiceName" title="Nhập bằng giọng nói">
                <i class="fas fa-microphone"></i>
              </button>
              <i class="fas fa-user input-icon"></i>
            </div>
            <div class="validation-message" id="name-validation"></div>
            <div class="typing-indicator" id="name-typing">
              <span>Đang nhập</span>
              <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
              </div>
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
              <button type="button" class="voice-input-btn" id="voicePhone" title="Nhập bằng giọng nói">
                <i class="fas fa-microphone"></i>
              </button>
              <i class="fas fa-phone input-icon"></i>
            </div>
            <div class="validation-message" id="phone-validation"></div>
            <div class="typing-indicator" id="phone-typing">
              <span>Đang nhập</span>
              <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
              </div>
            </div>
          </div>

          <div class="form-field full-width autocomplete-container" data-tooltip="Email để nhận phản hồi từ chúng tôi">
            <label for="email" class="form-label">
              <i class="fas fa-envelope label-icon"></i>
              Email
            </label>
            <div class="input-wrapper">
              <input type="email" name="email" id="email" autocomplete="email" required
                     class="form-input"
                     placeholder="Nhập email" value="{{ old('email') }}">
              <button type="button" class="voice-input-btn" id="voiceEmail" title="Nhập bằng giọng nói">
                <i class="fas fa-microphone"></i>
              </button>
              <i class="fas fa-envelope input-icon"></i>
            </div>
            <div class="autocomplete-suggestions" id="email-suggestions"></div>
            <div class="validation-message" id="email-validation"></div>
            <div class="typing-indicator" id="email-typing">
              <span>Đang nhập</span>
              <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
              </div>
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
              <button type="button" class="voice-input-btn" id="voiceAddress" title="Nhập bằng giọng nói">
                <i class="fas fa-microphone"></i>
              </button>
              <i class="fas fa-map-marker-alt input-icon"></i>
            </div>
            <div class="validation-message" id="address-validation"></div>
            <div class="typing-indicator" id="address-typing">
              <span>Đang nhập</span>
              <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
              </div>
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
              <button type="button" class="voice-input-btn" id="voiceNote" title="Nhập bằng giọng nói" 
                      style="top: 1.25rem; transform: none;">
                <i class="fas fa-microphone"></i>
              </button>
              <i class="fas fa-comment-alt input-icon textarea-icon"></i>
            </div>
            <div class="validation-message" id="note-validation"></div>
            <div class="typing-indicator" id="note-typing">
              <span>Đang nhập</span>
              <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
              </div>
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
// Create floating particles
function createParticles() {
  const particlesContainer = document.getElementById('particles');
  if (!particlesContainer) return;
  
  for (let i = 0; i < 50; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.animationDelay = Math.random() * 15 + 's';
    particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
    particlesContainer.appendChild(particle);
  }
}

// Enhanced form submission with success animation
document.getElementById('contactForm').addEventListener('submit', function(e) {
  const submitBtn = document.getElementById('submitBtn');
  const buttonText = submitBtn.querySelector('.button-text');
  const buttonIcon = submitBtn.querySelector('.button-icon');
  const formCard = document.querySelector('.form-card');
  
  // Add submitting state
  formCard.classList.add('form-submitting');
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

// Enhanced real-time validation with animations
document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
  const field = input.closest('.form-field');
  const fieldId = input.id;
  const validationMessage = document.getElementById(fieldId + '-validation');
  const typingIndicator = document.getElementById(fieldId + '-typing');
  
  let typingTimer;
  
  input.addEventListener('input', function() {
    const icon = this.parentElement.querySelector('.input-icon');
    
    // Show typing indicator
    if (typingIndicator) {
      typingIndicator.classList.add('show');
      clearTimeout(typingTimer);
      
      typingTimer = setTimeout(() => {
        typingIndicator.classList.remove('show');
      }, 1000);
    }
    
    // Smart focus enhancement
    field.classList.add('smart-focus');
    
    // Real-time validation
    if (this.checkValidity() && this.value.length > 0) {
      this.style.borderColor = '#10b981';
      this.style.background = 'linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%)';
      if (icon) {
        icon.style.color = '#10b981';
        icon.style.animation = 'completionCelebration 0.6s ease-out';
      }
      
      if (validationMessage) {
        validationMessage.innerHTML = '<i class="fas fa-check"></i> Hợp lệ';
        validationMessage.classList.add('success', 'show');
        validationMessage.classList.remove('error');
      }
    } else if (this.value.length > 0 && !this.checkValidity()) {
      this.style.borderColor = '#ef4444';
      this.style.background = 'linear-gradient(135deg, #fef2f2 0%, #ffffff 100%)';
      if (icon) {
        icon.style.color = '#ef4444';
        icon.style.animation = 'shake 0.5s ease-in-out';
      }
      
      // Show validation error
      if (validationMessage) {
        let errorMsg = 'Vui lòng nhập đúng định dạng';
        if (this.type === 'email') errorMsg = 'Email không hợp lệ';
        if (this.type === 'tel') errorMsg = 'Số điện thoại không hợp lệ';
        
        validationMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + errorMsg;
        validationMessage.classList.add('show');
        validationMessage.classList.remove('success');
      }
      
      // Add field shake animation
      field.style.animation = 'fieldErrorShake 0.4s ease-in-out';
    } else {
      this.style.borderColor = '#e5e7eb';
      this.style.background = '#fafbfc';
      if (icon) {
        icon.style.color = '#9ca3af';
        icon.style.animation = 'none';
      }
      
      if (validationMessage) {
        validationMessage.classList.remove('show', 'success');
      }
    }
    
    // Reset animations
    setTimeout(() => {
      field.style.animation = 'none';
      if (icon) icon.style.animation = 'none';
    }, 600);
  });
  
  input.addEventListener('focus', function() {
    field.classList.add('smart-focus');
    
    // Create ripple effect
    const rect = this.getBoundingClientRect();
    const ripple = document.createElement('div');
    ripple.className = 'input-ripple';
    const size = Math.max(rect.width, rect.height);
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = '50%';
    ripple.style.top = '50%';
    ripple.style.transform = 'translate(-50%, -50%) scale(0)';
    
    this.parentElement.appendChild(ripple);
    
    setTimeout(() => {
      if (ripple.parentElement) {
        ripple.parentElement.removeChild(ripple);
      }
    }, 600);
  });
  
  input.addEventListener('blur', function() {
    field.classList.remove('smart-focus');
    const typingIndicator = document.getElementById(this.id + '-typing');
    if (typingIndicator) {
      typingIndicator.classList.remove('show');
    }
  });
});

// Smart phone number formatting
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
});

// Email autocomplete suggestions
const emailSuggestions = ['@gmail.com', '@yahoo.com', '@hotmail.com', '@outlook.com'];
const emailInput = document.getElementById('email');
const emailSuggestionsContainer = document.getElementById('email-suggestions');

emailInput.addEventListener('input', function() {
  const value = this.value;
  const atIndex = value.indexOf('@');
  
  if (atIndex > 0 && atIndex === value.length - 1) {
    // Show suggestions when user types @
    emailSuggestionsContainer.innerHTML = '';
    emailSuggestions.forEach(suggestion => {
      const item = document.createElement('div');
      item.className = 'suggestion-item';
      item.innerHTML = `<i class="fas fa-at"></i> ${value}${suggestion.substring(1)}`;
      item.addEventListener('click', () => {
        emailInput.value = value + suggestion.substring(1);
        emailSuggestionsContainer.classList.remove('show');
        emailInput.focus();
      });
      emailSuggestionsContainer.appendChild(item);
    });
    emailSuggestionsContainer.classList.add('show');
  } else {
    emailSuggestionsContainer.classList.remove('show');
  }
});

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
  if (!emailInput.contains(e.target) && !emailSuggestionsContainer.contains(e.target)) {
    emailSuggestionsContainer.classList.remove('show');
  }
});

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
  
  updateStepContent() {
    // Remove the field group hiding logic - all fields should remain visible
    // This method is kept for compatibility but doesn't manipulate visibility
    console.log(`Step ${this.currentStep} activated`);
  }
  
  updateNavigationButtons() {
    // Remove navigation button creation since we want a single-page form
    // This method is kept for compatibility
    console.log('Navigation buttons would be updated here if needed');
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
  
  animateStepTransition() {
    const activeGroup = document.querySelector('.field-group.step-active');
    if (activeGroup) {
      activeGroup.style.opacity = '0';
      activeGroup.style.transform = 'translateX(20px)';
      
      setTimeout(() => {
        activeGroup.style.opacity = '1';
        activeGroup.style.transform = 'translateX(0)';
      }, 150);
    }
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
    
    // Hide suggestions when clicking outside
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
    
    // Add suggestions header
    const header = document.createElement('div');
    header.className = 'suggestions-header';
    header.innerHTML = `
      <i class="fas fa-lightbulb"></i>
      <span>Gợi ý thông minh</span>
    `;
    container.appendChild(header);
    
    // Add suggestions
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
    
    // Show gesture helper on first touch
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
    
    // Show gesture feedback
    this.showGestureFeedback(deltaX, deltaY);
  }
  
  handleTouchEnd(e) {
    if (!this.isTouch) return;
    
    const deltaX = e.changedTouches[0].clientX - this.startX;
    const deltaY = e.changedTouches[0].clientY - this.startY;
    
    // Only process horizontal gestures
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
    // Navigate to next step
    if (window.stepIndicator && window.stepIndicator.currentStep < window.stepIndicator.totalSteps) {
      if (window.stepIndicator.validateCurrentStep()) {
        window.stepIndicator.goToStep(window.stepIndicator.currentStep + 1);
        this.showGestureConfirmation('Chuyển bước tiếp theo');
      }
    }
  }
  
  handleSwipeRight() {
    // Navigate to previous step
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
    // Visual feedback for gesture direction
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
    
    // Monitor form for errors
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

// Voice input simulation (placeholder for real voice recognition)
document.querySelectorAll('.voice-input-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const inputId = this.id.replace('voice', '').toLowerCase();
    const input = document.getElementById(inputId);
    
    // Add listening animation
    this.classList.add('listening');
    
    // Simulate voice recognition
    setTimeout(() => {
      this.classList.remove('listening');
      this.classList.add('processing');
      
      // Sample voice input based on field type
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

// Initialize all systems
document.addEventListener('DOMContentLoaded', function() {
  // Initialize step indicator system
  window.stepIndicator = new StepIndicator();
  
  // Initialize smart suggestions
  window.smartSuggestions = new SmartSuggestions();
  
  // Initialize touch gestures
  window.touchGestures = new TouchGestures();
  
  // Initialize error recovery
  window.errorRecovery = new ErrorRecovery();
  
  // Add performance optimizations
  optimizePerformance();
  
  // Initialize particles and progress tracking
  createParticles();
  updateProgressIndicator();
  
  // Add smart step progression
  initializeSmartStepProgression();
});

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

// Additional animation styles for new features
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
  
  .step-navigation {
    display: flex;
    justify-content: space-between;
    margin: 2rem 0;
    gap: 1rem;
  }
  
  .step-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid #e5e7eb;
    background: white;
    color: #374151;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
  }
  
  .step-btn:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
  }
  
  .step-btn:active {
    transform: translateY(0);
  }
  
  .field-group {
    transition: all 0.3s ease;
  }
  
  .field-group.step-active {
    animation: stepSlideIn 0.4s ease-out;
  }
  
  @keyframes stepSlideIn {
    from {
      opacity: 0;
      transform: translateX(20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
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
  
  .touch-gesture-indicator.show {
    animation: gestureSlideIn 0.3s ease-out;
  }
  
  @keyframes gestureSlideIn {
    from {
      transform: translateX(100%);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }
  
  .error-recovery-panel.show {
    animation: errorPanelSlideUp 0.4s ease-out;
  }
  
  @keyframes errorPanelSlideUp {
    from {
      transform: translateY(100%);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }
  
  .suggestions-header {
    padding: 0.5rem 0.75rem;
    background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .suggestion-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f3f4f6;
  }
  
  .suggestion-item:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    transform: translateX(4px);
  }
  
  .suggestion-item:last-child {
    border-bottom: none;
  }
  
  .suggestion-text {
    flex: 1;
    color: #374151;
    font-weight: 500;
  }
  
  .suggestion-action {
    font-size: 0.75rem;
    color: #6b7280;
    opacity: 0;
    transition: opacity 0.2s ease;
  }
  
  .suggestion-item:hover .suggestion-action {
    opacity: 1;
  }
  
  .smart-suggestions.show {
    animation: suggestionsSlideDown 0.3s ease-out;
  }
  
  @keyframes suggestionsSlideDown {
    from {
      opacity: 0;
      transform: translateY(-10px) scale(0.95);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }
  
  /* Responsive enhancements for mobile */
  @media (max-width: 768px) {
    .step-navigation {
      flex-direction: column;
    }
    
    .step-btn {
      justify-content: center;
    }
    
    .touch-gesture-indicator {
      bottom: 20px;
      right: 20px;
    }
    
    .error-recovery-panel {
      margin: 1rem;
      border-radius: 12px;
    }
  }
  
  /* Accessibility improvements */
  @media (prefers-reduced-motion: reduce) {
    .step-btn,
    .suggestion-item,
    .field-group,
    .voice-input-btn {
      transition: none;
      animation: none;
    }
  }
  
  /* High contrast mode support */
  @media (prefers-contrast: high) {
    .step-btn {
      border-width: 3px;
    }
    
    .suggestion-item {
      border-bottom-width: 2px;
    }
    
    .smart-suggestions {
      border-width: 2px;
    }
  }
`;
document.head.appendChild(additionalStyles);
    
    // Simulate voice recognition delay
    setTimeout(() => {
      this.classList.remove('listening');
      // In real implementation, this would use Web Speech API
      input.focus();
    }, 2000);
  });
});

// Auto-resize textarea
const textarea = document.getElementById('note');
let textareaTypingTimer;

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
    
    document.querySelector('.form-grid').before(progressBar);
  }
  
  const progressFill = progressBar.querySelector('.progress-fill');
  const progressPercent = progressBar.querySelector('.progress-percent');
  
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
      document.body.removeChild(celebration);
    }, 3000);
    
    // Enable form submission visual cue
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.style.animation = 'readyPulse 2s ease-in-out infinite';
  }
}

// Add event listeners for progress tracking
document.querySelectorAll('.form-input, .form-textarea').forEach(input => {
  input.addEventListener('input', updateProgressIndicator);
  input.addEventListener('blur', updateProgressIndicator);
});

// Initialize particles and progress
createParticles();
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
  
  @keyframes readyPulse {
    0%, 100% { box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.25); }
    50% { box-shadow: 0 8px 25px 0 rgba(59, 130, 246, 0.4); }
  }
  
  .form-progress {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
  }
  
  .progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
  }
  
  .progress-label {
    color: #374151;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .progress-bar {
    width: 100%;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
  }
  
  .progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #10b981);
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 4px;
    position: relative;
  }
`;
document.head.appendChild(style);
</script>

<!-- Enhanced Contact Form JavaScript -->
<script src="{{ asset('js/contact.js') }}"></script>
@endsection
