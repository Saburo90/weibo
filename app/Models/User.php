<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();

        // 监听用户生成之前，生成激活码
        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // 指明一个用户拥有多条微博
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function feed()
    {
        // 首页展示用户及其所关注的用户动态
        $user_ids = $this->followings->pluck('id')->toArray();
        array_push($user_ids, $this->id);

        // with 关联模型 预加载解决n+1问题
        # 使用with关联模型之前
        // select * from statuses; 1次查询n条微博数据
        // select * from users where id = status.id; n次查询用户数据
        # 使用with关联模型之后
        // select * from statuses; 1次查询n条微博数据
        // select * from users where id in (status.id1, status.id2, ...) 1次查询n条用户数据
        return Status::whereIn('user_id', $user_ids)->with('user')->orderBy('created_at', 'desc');
    }

    // 粉丝模型
    public  function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 关注模型
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 关注
    public function follow($user_ids)
    {
        if (! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        return $this->followings()->sync($user_ids, false);
    }

    // 取消关注
    public function unfollow($user_ids)
    {
        if (! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }

        return $this->followings()->detach($user_ids);
    }

    public function isFollowing($user_id)
    {
        // 因模型中定义followings关联方法，所以可使用laravel的动态属性直接判断followings属性中是否含$user_id
        return $this->followings->contains($user_id);
    }
}
