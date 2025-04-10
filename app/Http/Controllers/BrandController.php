<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $brands = $query->orderBy('id', 'desc')->get();
        $categories = Category::all();

        return view('brands.list', compact('brands', 'categories'));
    }
    public function create()
    {
        $categories = Category::all();
        return view('brands.create',compact('categories'));
    }
    public function store(Request $request)
    {
        $dataValidator = $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'nullable|numeric|exists:categories,id', // Cho phép null
        ]);

        // Tạo thương hiệu
        $brand = Brand::create($dataValidator);

        Session::flash('message', 'Thêm thương hiệu thành công!');
        return redirect()->route('brands.list');
    }

    public function update(Request $request, $id)
{
    // Kiểm tra xem thương hiệu có tồn tại không
    $brand = Brand::findOrFail($id);

    // Validate dữ liệu đầu vào
    $dataValidator = $request->validate([
        'name' => 'required|max:255',
        'category_id' => 'nullable|numeric|exists:categories,id', // Cho phép null
    ]);

    // Cập nhật thương hiệu
    $brand->update($dataValidator);

    // Thông báo cập nhật thành công
    Session::flash('message', 'Cập nhật thương hiệu thành công!');

    return redirect()->route('brands.list');
}


    public function edit(Brand $brand)
    {
        $categories = Category::all();
        return view('brands.edit', compact('brand','categories'));
    }
    public function destroy($id)
    {
        // Tìm thương hiệu cần xóa
        $brand = Brand::findOrFail($id);

        // Xóa thương hiệu
        $brand->delete();

        // Thông báo xóa thành công
        Session::flash('message', 'Xóa thương hiệu thành công!');

        return redirect()->route('brands.list');
    }

}
