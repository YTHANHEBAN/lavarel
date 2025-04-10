<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
</head>
<body>
    <h1>Thông Tin Cá Nhân</h1>
    <p><strong>ID:</strong> {{ $info['id'] }}</p>
    <p><strong>Họ tên:</strong> {{ $info['name'] }}</p>
    <p><strong>Chức vụ:</strong> {{ $info['profile'] }}</p>
    <p><strong>Email:</strong> {{ $info['email'] }}</p>
    <p><strong>Số điện thoại:</strong> {{ $info['phone'] }}</p>
</body>
</html>
