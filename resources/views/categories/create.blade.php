lisst @extends('layouts.admin')
@section('content')
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-submit {
            width: 100%;
            margin-top: 20px;
        }
    </style>
<body class="bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Add New Category</h2>
        <form action="/categories/store" method="post" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter category name">
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-submit">Create</button>
        </form>
    </div>
</body>
@endsection
