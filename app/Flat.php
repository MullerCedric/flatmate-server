<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    protected $fillable = [
        'label', 'avatar', 'creator_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'creator_id' => 'integer',
    ];

    protected $with = ['creator'];

    public function discussions()
    {
        return $this->hasMany('App\Discussion');
    }

    public function events()
    {
        return $this->hasMany("App\Event");
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function participants()
    {
        return $this->morphToMany('App\User', 'participation', 'participants');
    }
}
