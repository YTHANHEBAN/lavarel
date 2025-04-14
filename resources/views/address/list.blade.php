@extends('layouts.app2')

@section('content')
<br><br><br><br>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            <h2>Danh sách địa chỉ</h2>

            <a href="{{ route('addresses.create') }}" class="btn btn-primary mb-3">Thêm địa chỉ mới</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($addresses as $address)
                    <tr>
                        <td>{{ $address->name }}</td>
                        <td>{{ $address->phone }}</td>
                        <td>{{ $address->address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</td>
                        <td>
                            <a href="{{ route('addresses.edit', $address->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
