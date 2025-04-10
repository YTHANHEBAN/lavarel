<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $users = $query->orderBy('id', 'desc')->get();

        return view('users.list', compact('users'));
    }

    public function update(Request $request, $id)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'role' => 'required|in:admin,user,editor',
    ]);

    // Tìm người dùng
    $user = User::findOrFail($id);

    // Cập nhật role
    $user->role = $request->role;
    $user->save();

    // Thông báo thành công
    Session::flash('message', 'Cập nhật quyền thành công');

    return redirect()->route('users.list');
}

    public function delete($id)
    {
        $users = User::find($id);
        $users->delete();
        Session::flash('message', 'xóa thành công');
        return redirect()->route('users.list');
    }
}
