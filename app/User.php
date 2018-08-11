<?php

namespace App;
use App\Todo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','themeColor'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function todos()
    {
        return $this->belongsToMany(Todo::class);
    }   

    public function friends()
	{
		return $this->belongsToMany('User', 'friends_users', 'user_id', 'friend_id');
	}
}
