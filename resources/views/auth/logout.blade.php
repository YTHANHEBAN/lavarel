@extends('layouts.app2')
@section('content')
<br><br><br><br><br>
<div class="container">
    <h2>Chào mừng {{ Auth::user()->name }}!</h2>
    <p>Đây là trang Dashboard.</p>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit" class="btn btn-danger">Đăng Xuất</button>
    </form>
</div>
@endsection
