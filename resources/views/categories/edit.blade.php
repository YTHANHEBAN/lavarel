@extends('layouts.admin')

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
 </head>

 <body class="bg-light">

     <div class="container">
         <h2 class="text-center mb-4">Edit Category</h2>
         <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
             @csrf
             @method('PUT')
             <div class="mb-3">
                 <label class="form-label">Name</label>
                 <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                 @error('name')
                 <p class="text-danger">{{ $message }}</p>
                 @enderror
             </div>
             <div class="mb-3">
                 <label for="parent_id" class="form-label">Parent Category</label>
                 <select class="form-select" id="parent_id" name="parent_id">
                     <option value="{{ old('name', $category->parent_id) }}"></option>
                     @foreach ($categories as $category)
                     <option value="{{ $category->id }}">{{ $category->name }}</option>
                     @endforeach
                 </select>
             </div>
             <button type="submit" class="btn btn-success btn-submit">Update</button>
             <a href="{{ route('categories.list') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
         </form>
     </div>
 </body>
 @endsection
