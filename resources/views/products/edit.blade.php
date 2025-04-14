@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary">Chỉnh Sửa Sản Phẩm</h2>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $product->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Giá Nhập</label>
            <input type="number" name="input_price" class="form-control @error('input_price') is-invalid @enderror"
                   value="{{ old('input_price', $product->input_price) }}" required>
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Giá Bán</label>
            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                   value="{{ old('price', $product->price) }}" required>
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Số lượng</label>
            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                   value="{{ old('quantity', $product->quantity) }}" required>
            @error('quantity')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Chọn danh mục sản phẩm -->
        <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Ảnh chính -->
        <div class="mb-3">
            <label class="form-label">Ảnh chính</label><br>
            @if($product->image)
                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="rounded mb-2" style="max-width: 150px;">
            @endif
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <!-- Ảnh phụ -->
        <div class="mb-3">
            <label class="form-label">Ảnh phụ</label><br>
            @foreach($product->images as $img)
                <img src="{{ asset('images/' . $img->imagePath) }}" alt="Ảnh phụ" class="rounded mb-2" style="max-width: 100px;">
            @endforeach
            <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror" multiple>
            @error('images')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Lưu thay đổi</button>
        <a href="{{ route('products.list') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Hủy</a>
    </form>
</div>
@endsection
