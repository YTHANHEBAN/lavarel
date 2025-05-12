<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Category;
use App\Models\Images;
use Illuminate\Support\Facades\Session;
class ProductController extends Controller
{
    // public function index()
    // {
    //     $products = Product::all();
    //     return view('products.list', compact('products'));
    // }
    // public function user_index()
    // {
    //     $products = Product::all();
    //     return view('user_products.list', compact('products'));
    // }
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $products = $query->orderBy('id', 'desc')->get();

        return view('products.list', compact('products'));
    }

//

//     public function user_index(Request $request)
// {
//     $query = Product::query();

//     if ($request->has('search')) {
//         $search = $request->input('search');
//         $query->where('name', 'LIKE', "%{$search}%");
//     }

//     $products = $query->paginate(6);

//     return view('user_products.list2', compact('products'));
// }
public function user_index(Request $request)
{
    $query = Product::query();

    // Tìm kiếm theo tên sản phẩm
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('name', 'LIKE', "%{$search}%");
    }

    // Lọc theo mức giá
    // if ($request->has('price_range') && $request->price_range !== '') {
    //     switch ($request->price_range) {
    //         case '1':
    //             $query->where('price', '<', 100000);
    //             break;
    //         case '2':
    //             $query->whereBetween('price', [100000, 300000]);
    //             break;
    //         case '3':
    //             $query->whereBetween('price', [300000, 500000]);
    //             break;
    //         case '4':
    //             $query->where('price', '>', 500000);
    //             break;
    //     }
    // }

    // Lọc theo mức sao (1 sao đến 5 sao)
    if ($request->has('rating_range') && $request->rating_range !== '') {
        switch ($request->rating_range) {
            case '1':
                $query->whereHas('reviews', function($q) {
                    $q->where('rating', '=', 1);
                });
                break;
            case '2':
                $query->whereHas('reviews', function($q) {
                    $q->where('rating', '=', 2);
                });
                break;
            case '3':
                $query->whereHas('reviews', function($q) {
                    $q->where('rating', '=', 3);
                });
                break;
            case '4':
                $query->whereHas('reviews', function($q) {
                    $q->where('rating', '=', 4);
                });
                break;
            case '5':
                $query->whereHas('reviews', function($q) {
                    $q->where('rating', '=', 5);
                });
                break;
        }
    }

    // Lấy danh sách sản phẩm và thêm số lượng đánh giá cùng số sao trung bình
    $products = $query->orderBy('id', 'desc')->paginate(6);

    // Thêm số lượng đánh giá và số sao trung bình cho mỗi sản phẩm
    foreach ($products as $product) {
        $product->review_count = $product->reviews()->count(); // Số lượng đánh giá
        $product->average_rating = $product->reviews()->avg('rating'); // Số sao trung bình
    }

    // Lấy danh sách sản phẩm bán chạy (top sản phẩm)
    $products_top = $query->orderBy('quantity', 'asc')->limit(6)->get();
    // Thêm số lượng đánh giá và số sao trung bình cho mỗi sản phẩm
    foreach ($products_top as $product) {
        $product->review_count = $product->reviews()->count(); // Số lượng đánh giá
        $product->average_rating = $product->reviews()->avg('rating'); // Số sao trung bình
    }

    return view('user_products.list2', [
        'products' => $products,
        'currentSearch' => $request->search,
        'currentPriceRange' => $request->price_range,
        'currentRatingRange' => $request->rating_range,
        'products_top' => $products_top
    ]);
}

// public function user_products(Request $request)
// {
//     $query = Product::query();

//     if ($request->has('search')) {
//         $search = $request->input('search');
//         $query->where('name', 'LIKE', "%{$search}%");
//     }

//     $products = $query->paginate(6);

//     return view('user_products.products', compact('products'));
// }
public function user_products(Request $request)
{
    $query = Product::query();

    // Lọc theo danh mục
    if ($request->has('category_id') && $request->category_id != '') {
        $query->where('category_id', $request->category_id);
    }

    // Lọc theo khoảng giá
    if ($request->has('price_min') && $request->price_min != '') {
        $query->where('price', '>=', $request->price_min);
    }

    if ($request->has('price_max') && $request->price_max != '') {
        $query->where('price', '<=', $request->price_max);
    }

    $products = $query->paginate(6)->appends($request->query()); // giữ query khi phân trang
    $categories = Category::all();

    foreach ($products as $product) {
        $product->review_count = $product->reviews()->count(); // Số lượng đánh giá
        $product->average_rating = $product->reviews()->avg('rating'); // Số sao trung bình
    }

    return view('user_products.products',[ 'currentPriceRange' => $request->price_range,
    'currentRatingRange' => $request->rating_range,], compact('products', 'categories'));
}



    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        $categories = Category::all();
        return view('products.create',compact('categories'));
    }

    // Lưu sản phẩm vào database
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric|min:0',
    //         'image' => 'nullable|string',
    //         'quantity' => 'required|integer|min:0',
    //     ]);

    //     Product::create($request->all());

    //     return redirect()->route('products.list')->with('success', 'Sản phẩm đã được thêm thành công!');
    // }

//     public function store(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'price' => 'required|numeric|min:0',
//         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
//         'quantity' => 'required|integer|min:0',
//     ]);

//     $data = $request->all();

//     // Kiểm tra nếu có file ảnh được upload
//     if ($request->hasFile('image')) {
//         $imagePath = $request->file('image')->store('products', 'public');
//         $data['image'] = $imagePath; // Lưu đường dẫn ảnh vào cơ sở dữ liệu
//     }

//     Product::create($data);

//     return redirect()->route('products.list')->with('success', 'Sản phẩm đã được thêm thành công!');
// }

// public function store(Request $request)
//     {
//         $datavalidate = $request->validate([
//             'name' => 'required',
//             'price' => 'required|numeric|min:0',
//             'description' => 'required',
//             'image' => 'required',
//             'quantity' => 'required|numeric|min:0',
//         ]);
//         $imageName = time() . '.' . $request->file('image')->extension();
//         $request->file('image')->move(public_path('images'), $imageName);
//         $datavalidate['image'] = $imageName;
//         DB::table('products')->insert($datavalidate);
//         Session::flash('message', 'thêm thành công');
//         return redirect()->route('products.list');
//     }


public function store(Request $request)
  {
    $data = $request->validate([
      'name' => 'required|max:255',
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
      'description' => 'nullable',
      'input_price' => 'required|numeric|min:0',
      'price' => 'required|numeric|min:0',
      'quantity' => 'required|numeric|min:0',
      'category_id' => 'required|numeric|min:0|exists:categories,id',
      'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
    ]);

    // Tạo slug ngẫu nhiên
    $random = Str::random(5);
    $data['slug'] = Str::slug($request->title . '-' . $random);

    // Lưu ảnh chính
    if ($request->hasFile('image')) {
      $imageName = uniqid() . '.' . $request->image->extension();
      $request->file('image')->move(public_path('images'), $imageName);
      $data['image'] = $imageName;
    }
    // Tạo product
    $product = Product::create($data);
    // Lưu các ảnh phụ
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $subImageName = uniqid() . '.' . $image->extension();
            $image->move(public_path('images'), $subImageName); // Sử dụng $image thay vì $request->file('images')

            Images::create([
                'product_id' => $product->id,
                'imagePath' => $subImageName
            ]);
        }
    }
    Session::flash('message', 'Thêm sản phẩm thành công!');
    return redirect()->route('products.list');
  }

    // Hiển thị chi tiết sản phẩm
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function product_detail(Product $product)
{
    // Lấy danh sách sản phẩm mới nhất (8 sản phẩm mỗi trang)
    $products_categories = Product::orderBy('id', 'desc')->paginate(8);

    // Lấy danh sách ảnh phụ từ quan hệ
    $images = $product->images()->orderBy('id', 'desc')->get();

    return view('user_products.products_detail', compact('product', 'products_categories', 'images'));
}





    // Hiển thị form chỉnh sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product','categories'));
    }

    // Cập nhật sản phẩm trong database
    // public function update(Request $request, Product $product)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric|min:0',
    //         'image' => 'nullable|string',
    //         'quantity' => 'required|integer|min:0',
    //     ]);

    //     $product->update($request->all());

    //     return redirect()->route('products.list')->with('success', 'Sản phẩm đã được cập nhật!');
    // }

    // public function update(Request $request, $id)
    // {
    //     $datavalidate = $request->validate([
    //         'name' => 'required',
    //         'price' => 'required|numeric|min:0',
    //         'description' => 'required',
    //         'image' => 'required',
    //         'quantity' => 'required|numeric|min:0',
    //     ]);
    //     $imageName = time() . '.' . $request->file('image')->extension();
    //     $request->file('image')->move(public_path('images'), $imageName);
    //     $datavalidate['image'] = $imageName;

    //     DB::table('products')->where('id', $id)->update($datavalidate);
    //     Session::flash('message', 'Cập nhật thành công');
    //     return redirect()->route('products.list');
    // }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description' => 'nullable',
            'input_price' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'category_id' => 'required|numeric|min:0|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // Tìm sản phẩm
        $product = Product::findOrFail($id);

        // Cập nhật slug nếu tên thay đổi
        if ($request->name !== $product->name) {
            $random = Str::random(5);
            $data['slug'] = Str::slug($request->name . '-' . $random);
        } else {
            $data['slug'] = $product->slug;
        }

        // Cập nhật ảnh chính nếu có file mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($product->image && file_exists(public_path('images/' . $product->image))) {
                unlink(public_path('images/' . $product->image));
            }
            // Lưu ảnh mới
            $imageName = uniqid() . '.' . $request->image->extension();
            $request->file('image')->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        // Cập nhật thông tin sản phẩm
        $product->update($data);

        // Nếu có ảnh phụ mới, xóa ảnh phụ cũ và cập nhật lại
        if ($request->hasFile('images')) {
            // Xóa ảnh phụ cũ
            $oldImages = Images::where('product_id', $product->id)->get();
            foreach ($oldImages as $oldImage) {
                if (file_exists(public_path('images/' . $oldImage->imagePath))) {
                    unlink(public_path('images/' . $oldImage->imagePath));
                }
                $oldImage->delete();
            }

            // Lưu ảnh phụ mới
            foreach ($request->file('images') as $image) {
                $subImageName = uniqid() . '.' . $image->extension();
                $image->move(public_path('images'), $subImageName);

                Images::create([
                    'product_id' => $product->id,
                    'imagePath' => $subImageName
                ]);
            }
        }

        Session::flash('message', 'Cập nhật sản phẩm thành công!');
        return redirect()->route('products.list');
    }

    // Xóa sản phẩm
    public function destroy(Product $product)
{
    // Xóa tất cả ảnh có product_id bằng với id của sản phẩm
    Images::where('product_id', $product->id)->delete();

    // Xóa sản phẩm
    $product->delete();

    return redirect()->route('products.list')->with('success', 'Sản phẩm và ảnh liên quan đã được xóa!');
}

}
