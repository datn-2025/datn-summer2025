<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xác nhận tài khoản BookBee</h2>
        
        <p>Xin chào {{ $userName }},</p>
        
        <p>Cảm ơn bạn đã đăng ký tài khoản tại BookBee. Để hoàn tất quá trình đăng ký, vui lòng nhấp vào nút bên dưới để kích hoạt tài khoản của bạn:</p>
        
        <a href="{{ $activationUrl }}" class="button">Kích hoạt tài khoản</a>
        
        <p>Nếu bạn không thể nhấp vào nút trên, vui lòng sao chép và dán đường link sau vào trình duyệt:</p>
        <p>{{ $activationUrl }}</p>
        
        <p>Liên kết này sẽ hết hạn sau 24 giờ.</p>
        
        <div class="footer">
            <p>Nếu bạn không đăng ký tài khoản này, vui lòng bỏ qua email này.</p>
            <p>© {{ date('Y') }} BookBee. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
