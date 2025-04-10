<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <label for="password">Mật khẩu hiện tại:</label>
    <input type="password" name="password" required>

    <label for="new_password">Mật khẩu mới:</label>
    <input type="password" name="new_password" required>

    <label for="new_password_confirmation">Xác nhận mật khẩu mới:</label>
    <input type="password" name="new_password_confirmation" required>

    <button type="submit">Đổi mật khẩu</button>
</form>
