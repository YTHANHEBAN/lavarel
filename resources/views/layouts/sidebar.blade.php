<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Dropdown</title>
    <style>
        .sidebar {
            width: 250px;
            padding: 15px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            color: black;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: red;
        }

        .dropdown-content {
            display: none;
            padding-left: 15px;
        }

        .avatar-wrapper {
            text-align: center;
            margin-bottom: 15px;
        }

        .avatar-wrapper img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: block;
            margin: 0 auto;
        }

        .dropdown-btn {
            cursor: pointer;
            font-weight: bold;
            display: block;
            padding: 10px;
        }

        body {
            background-color: #f8f9fa;
        }

        .profile-container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

    </style>
</head>

<body>
    <div class="sidebar">
        <div class="dropdown">
            <span class="dropdown-btn" onclick="toggleDropdown()"><i class="fa-solid fa-user"></i> Tài Khoản Của Tôi</span>
            <div id="dropdownContent" class="dropdown-content">
                <a href="/profile" class="text-danger">Hồ Sơ</a>
                <a href="/addresses">Địa Chỉ</a>
                <a href="/change-password">Đổi Mật Khẩu</a>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("dropdownContent");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }
    </script>
</body>

</html>


<!-- <div class="avatar-wrapper">
    <img id="profileImage" src="default-avatar.png" alt="Avatar">
    <input type="file" id="fileInput" style="display: none;">
    <button class="btn btn-outline-secondary mt-2">Chọn Ảnh</button>
</div> -->
