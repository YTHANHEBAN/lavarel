@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Thêm sản phẩm</h2>
    <form action="/products/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Tên sản phẩm</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            @error('name')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Danh Mục</label>
            <select class="form-select" id="sel1" name="category_id">
                <option value="">Select category</option>
                @foreach ( $categories as $category )
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="input_price">Giá Nhập</label>
            <input type="number" name="input_price" id="input_price" class="form-control @error('input_price') is-invalid @enderror" value="{{ old('input_price') }}">
            @error('price')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="price">Giá Bán </label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
            @error('price')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="images">Hình ảnh Phụ</label>
            <input type="file" name="images[]" multiple accept="image/*" placeholder="images" class="form-control" />
            @error('images')
            <small class="alert-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="quantity">Số lượng</label>
            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}">
            @error('quantity')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Thêm sản phẩm</button>
    </form>
</div>
@endsection
