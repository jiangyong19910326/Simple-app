<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /*
     * @var size
     */
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
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

        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
    // 一个用户拥有多条微博 一对多的关系
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }
    // 动态展示该方法来获取当前用户关注的人发布过的所有微博动态
    public function feed()
    {
        return $this->statuses()
            ->orderBy('created_at', 'desc');
    }
}
