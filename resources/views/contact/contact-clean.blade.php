@extends('layouts.app')
@section('title', 'Liên hệ')

@push('styles')
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome Icons (minimal) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Minimal Contact Form CSS -->
<link rel="stylesheet" href="{{ asset('css/contact/contact-minimal.css') }}">
@endpush

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
        
        <!-- Thông tin cá nhân -->
        <div class="field-group" data-group="personal">
          <div class="field-group-title">
            <div class="group-icon">
              <i class="fas fa-user"></i>
            </div>
            Thông tin cá nhân
          </div>
          
          <div class="form-grid">
            <div class="form-field">
              <label for="name" class="form-label">
                <i class="fas fa-user label-icon"></i>
                Họ tên
              </label>
              <div class="input-wrapper">
                <input type="text" name="name" id="name" required
                       class="form-input"
                       placeholder="Nhập họ tên của bạn" value="{{ old('name') }}">
              </div>
            </div>

            <div class="form-field">
              <label for="phone" class="form-label">
                <i class="fas fa-phone label-icon"></i>
                Số điện thoại
              </label>
              <div class="input-wrapper">
                <input type="tel" name="phone" id="phone" required
                       class="form-input"
                       placeholder="Nhập số điện thoại" value="{{ old('phone') }}">
              </div>
            </div>
          </div>
        </div>

        <!-- Thông tin liên hệ -->
        <div class="field-group" data-group="contact">
          <div class="field-group-title">
            <div class="group-icon">
              <i class="fas fa-envelope"></i>
            </div>
            Thông tin liên hệ
          </div>

          <div class="form-grid">
            <div class="form-field full-width">
              <label for="email" class="form-label">
                <i class="fas fa-envelope label-icon"></i>
                Email
              </label>
              <div class="input-wrapper">
                <input type="email" name="email" id="email" required
                       class="form-input"
                       placeholder="Nhập địa chỉ email" value="{{ old('email') }}">
              </div>
            </div>

            <div class="form-field full-width">
              <label for="address" class="form-label">
                <i class="fas fa-map-marker-alt label-icon"></i>
                Địa chỉ
              </label>
              <div class="input-wrapper">
                <input type="text" name="address" id="address"
                       class="form-input"
                       placeholder="Nhập địa chỉ của bạn" value="{{ old('address') }}">
              </div>
            </div>
          </div>
        </div>

        <!-- Nội dung tin nhắn -->
        <div class="field-group" data-group="message">
          <div class="field-group-title">
            <div class="group-icon">
              <i class="fas fa-comment"></i>
            </div>
            Nội dung tin nhắn
          </div>

          <div class="form-grid">
            <div class="form-field full-width">
              <label for="message" class="form-label">
                <i class="fas fa-comment-alt label-icon"></i>
                Tin nhắn
              </label>
              <div class="input-wrapper">
                <textarea name="message" id="message" required
                          class="form-textarea"
                          placeholder="Nhập nội dung tin nhắn...">{{ old('message') }}</textarea>
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
@endsection

@push('scripts')
<!-- Minimal JavaScript -->
<script src="{{ asset('js/contact/contact-minimal.js') }}"></script>
@endpush
