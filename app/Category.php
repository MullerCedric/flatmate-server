<?php

namespace App;

use App\Scopes\FromUserScope;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'label', 'color', 'weight', 'total', 'user_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new FromUserScope());
    }

    public function events()
    {
        return $this->hasMany('App\Event');
    }

    public function notes()
    {
        return $this->hasMany('App\Notes');
    }

    public function transactions()
    {
        return $this->belongsToMany('App\Transaction');
    }

    public function user()
    {
        return $this->belongsToMany('App\User');
    }
}
