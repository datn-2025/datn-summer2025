<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo cập nhật hồ sơ</title>
</head>
<body>
    <h2>Xin chào {{ $user->name }},</h2>
    <p>Bạn vừa cập nhật thông tin hồ sơ cá nhân trên hệ thống BookBee.</p>
    <ul>
        <li><strong>Họ tên:</strong> {{ $user->name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        @if($user->phone)
        <li><strong>Số điện thoại:</strong> {{ $user->phone }}</li>
        @endif
    </ul>
    <p>Nếu bạn không thực hiện thay đổi này, vui lòng liên hệ ngay với bộ phận hỗ trợ của chúng tôi.</p>
    <p>Trân trọng,<br>Đội ngũ BookBee</p>
</body>
</html>
