@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh mục sản phẩm</h2>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCategory" aria-controls="navbarCategory" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCategory">
                <ul class="navbar-nav flex-column">
                    @foreach ($categories as $category)
                        <li class="nav-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <a class="nav-link" href="#">{{ $category->name }}</a>
                                @if ($category->children->isNotEmpty())
                                    <button class="btn btn-toggle" data-bs-toggle="collapse" data-bs-target="#category{{ $category->id }}" aria-expanded="false" aria-controls="category{{ $category->id }}">+</button>
                                @endif
                            </div>
                            @if ($category->children->isNotEmpty())
                                <div class="collapse" id="category{{ $category->id }}">
                                    <ul class="nav flex-column ps-3">
                                        @foreach ($category->children as $child)
                                            <li>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <a class="nav-link" href="#">{{ $child->name }}</a>
                                                    @if ($child->children->isNotEmpty())
                                                        <button class="btn btn-toggle" data-bs-toggle="collapse" data-bs-target="#child{{ $child->id }}" aria-expanded="false" aria-controls="child{{ $child->id }}">+</button>
                                                    @endif
                                                </div>
                                                @if ($child->children->isNotEmpty())
                                                    <div class="collapse" id="child{{ $child->id }}">
                                                        <ul class="nav flex-column ps-4">
                                                            @foreach ($child->children as $subChild)
                                                                <li><a class="nav-link" href="#">{{ $subChild->name }}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
</div>

<style>
/* Định dạng danh mục dạng sổ xuống dưới */
.navbar-nav .nav-link {
    font-weight: 500;
    color: #333;
    padding: 10px;
    display: block;
}

.navbar-nav .nav-link:hover {
    color: #007bff;
}

.btn-toggle {
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    padding: 5px 10px;
    transition: transform 0.3s;
}

.btn-toggle[aria-expanded="true"] {
    transform: rotate(180deg);
}

.btn-toggle:focus {
    outline: none;
}

.collapse {
    margin-top: 5px;
}

.collapse ul {
    border-left: 2px solid #ddd;
    margin-left: 15px;
    padding-left: 10px;
}
</style>
@endsection
