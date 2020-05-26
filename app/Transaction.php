<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'label', 'amount', 'flat_id', 'from_id', 'to_id', 'tags', 'start_date', 'end_date', 'interval'
    ];

    protected $casts = [
        'id' => 'integer',
        'flat_id' => 'integer',
        'from_id' => 'integer',
        'to_id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    protected $with = ['from', 'to'];

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function flat()
    {
        return $this->belongsTo('App\Flat');
    }

    public function from()
    {
        return $this->belongsTo('App\User', 'from_id');
    }

    public function to()
    {
        return $this->belongsTo('App\User', 'to_id');
    }
}
