<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Followable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use function Symfony\Component\Translation\t;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Followable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    protected $fillable = [
//        'username',
//        'name',
//        'email',
//        'password',
//    ];
    // you can add attr avatar or use :
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAvatarAttribute($value)
    {
//        return "https://i.pravatar.cc/200?u=" . $this->email;

        if (!$this->attributes['avatar']) {
            return asset('/images/default-avatar.jpeg');
        }

        return asset('storage/' . $value);

    }

    public
    function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public
    function timeline()
    {
        $friends = $this->follows()->pluck('id');

        return Tweet::whereIn('user_id', $friends)
            ->orWhere('user_id', $this->id)
            ->withLikes()
            ->latest()
            ->paginate(50);
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class)
            ->latest();
    }


    /*
     * if you want to reach the profile without using pk in laravel 6
     * and bellow use this method but other than in route page
     *   public function getRouteKeyName()
     *   {
     *          return 'name';
     *   }
     *
     */

    public
    function path($append = '')
    {
        $path = route('profile', $this->username);

        return $append ? "{$path}/{$append}" : $path;
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

}
