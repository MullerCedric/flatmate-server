<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function flatsOwned()
    {
        return $this->hasMany('App\Flat', 'creator_id');
    }

    public function notesOwned()
    {
        return $this->hasMany('App\Note', 'author_id');
    }

    public function discussions()
    {
        return $this->morphedByMany('App\Discussion', 'participation', 'participants');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'from_id');
    }

    public function messagesRead()
    {
        return $this->belongsToMany('App\Message')->withTimestamps();
    }

    public function transactionsOut()
    {
        return $this->hasMany('App\Transaction', 'from_id');
    }

    public function transactionsIn()
    {
        return $this->hasMany('App\Transaction', 'to_id');
    }

    public function events()
    {
        return $this->morphedByMany('App\Event', 'participation', 'participants');
    }

    public function flats()
    {
        return $this->morphedByMany('App\Flat', 'participation', 'participants');
    }

    public function notes()
    {
        return $this->morphedByMany('App\Discussion', 'participation', 'participants');
    }
}
