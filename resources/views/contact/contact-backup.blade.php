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

<!-- Minimal JavaScript -->
<script src="{{ asset('js/contact/contact-minimal.js') }}"></script>
@endsection
        
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

<!-- Enhanced Contact Form JavaScript -->
<script src="{{ asset('js/contact.js') }}"></script>
@endsection
