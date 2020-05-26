<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'author_id', 'flat_id', 'locked_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'author_id' => 'integer',
        'flat_id' => 'integer',
        'locked_at' => 'datetime'
    ];

    protected $with = ['author'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function participants()
    {
        return $this->morphToMany('App\User', 'participation', 'participants');
    }
}
