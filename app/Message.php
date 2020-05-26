<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'from_id', 'discussion_id', 'content', 'type'
    ];

    protected $casts = [
        'id' => 'integer',
        'from_id' => 'integer',
        'discussion_id' => 'integer'
    ];

    protected $with = ['from'];

    public function from()
    {
        return $this->belongsTo('App\User', 'from_id');
    }

    public function discussion()
    {
        return $this->belongsTo('App\Discussion');
    }
}
