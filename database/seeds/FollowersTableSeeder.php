<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        // 获取除用户1以外所有用户ID数组
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        // 用户1关注除自己以外所有用户
        $user->follow($follower_ids);

        // 除了用户1以外用户全部关注用户1
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
