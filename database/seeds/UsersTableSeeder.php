<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());

        $user = User::find(1);
        $user->name = 'weibo';
        $user->email = 'saburo90@163.com';

        // 添加第一个用户为管理员
        $user->is_admin = true;
        $user->save();
    }
}
