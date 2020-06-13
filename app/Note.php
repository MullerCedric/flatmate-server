<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'author_id', 'flat_id', 'label', 'content'
    ];

    protected $casts = [
        'id' => 'integer',
        'author_id' => 'integer',
        'flat_id' => 'integer',
    ];

    protected $with = ['author', 'categories'];

    public function categories()
    {
        return $this->belongsToMany('App\Category');
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
