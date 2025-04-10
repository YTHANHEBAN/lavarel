<?php

namespace App\Http\Controllers;

use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{

    public function index_2()
  {
    $categories = Category::whereNull('parent_id')->with('children')->get();
    return view('categories.index', compact('categories'));
  }
  public function index()
  {
    $categories = DB::table('categories')->select('id', 'name')->paginate(4);
    return view('categories.list', compact('categories'));
  }

  public function create()
  {
    return view('categories.create');
  }

  public function store(Request $request)
  {
    $dataValidator = $request->validate(
      [
        'name' => 'required|max:255',
        'parent_id' => 'nullable|integer|exists:categories,id',
      ]
    );
    $category = Category::create($dataValidator);
    Session::flash('message', 'thêm thành công');
    return redirect()->route('categories.list');
  }

  public function delete($id)
  {
    $category = Category::find($id);
    $category->delete();
    Session::flash('message', 'xóa thành công');
    return redirect()->route('categories.list');
  }

  public function edit($id)
  {
    $category = Category::find($id);
    return view('categories.edit', compact('category'));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|max:255',
    ]);

    $category = Category::findOrFail($id);

    $category->name = $request->name;
    $category->save();

    Session::flash('message', 'Cập nhật danh mục thành công!');
    return redirect()->route('categories.list');
  }
}
