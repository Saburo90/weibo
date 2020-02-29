<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        // 使用laravel自带中间件Auth，进行登录访问权限控制
        $this->middleware('auth', [
           'except' => ['create', 'show', 'store', 'index']
        ]);

        // 使用guest中间件，限制仅未登录态可访问注册页
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index()
    {
        // 分页展示用户列表
        $users = User::paginate(10);
//        $users = User::all();
        return view('users.index', compact('users'));
    }

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

    public function edit(User $user)
    {
        // 鉴权
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        // 鉴权
        $this->authorize('update', $user);

        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $user->update([
            'name' => $request->name,
            'password' => bcrypt($request->password)
        ]);

        session()->flash('success', '个人资料更新成功！');
        // 编辑用户信息成功，跳转至用户中心
        return redirect()->route('users.show', $user->id);
    }
}
