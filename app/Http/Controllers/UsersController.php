<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function store(Request $request)
    {
        // 注册用户信息后端验证
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        // 用户信息入库
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // 用户注册成功后自动登录
        Auth::login($user);

        // 缓存登录成功提示消息
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        // 登录成功重定向至个人中心页
        return redirect()->route('users.show', [$user]);
    }
}
