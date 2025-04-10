@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Thêm sản phẩm</h2>
    <form action="/brands/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Tên Thương Hiệu</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Danh Mục</label>
            <select class="form-select" id="sel1" name="category_id">
                <option value="">Chọn Danh Mục</option>
                @foreach ( $categories as $category )
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>Lưu </button>
    </form>
</div>
@endsection
