<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = [
        'label', 'flat_id', 'locked_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'flat_id' => 'integer',
        'locked_at' => 'datetime',
    ];

    public function scopeFromFlat($query, $flatId)
    {
        return $query->where('flat_id', $flatId);
    }

    public function flat()
    {
        return $this->belongsTo('App\Flat');
    }

    public function messages()
    {
        return $this->hasMany('App\Message')->latest()->orderBy('id', 'desc');
    }

    public function participants()
    {
        return $this->morphToMany('App\User', 'participation', 'participants');
    }
}
