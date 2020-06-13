<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'label', 'flat_id', 'start_date', 'end_date', 'interval', 'duration', 'confirm'
    ];

    protected $casts = [
        'id' => 'integer',
        'flat_id' => 'integer',
        'category_id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'interval' => 'integer',
        'duration' => 'integer',
    ];

    protected $with = ['flat', 'categories', 'participants'];

    public function flat()
    {
        return $this->belongsTo('App\Flat');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function participants()
    {
        return $this->morphToMany('App\User', 'participation', 'participants');
    }

    public function confirmedBy()
    {
        return $this->belongsToMany('App\User', 'confirmations')
            ->withTimestamps()->withPivot('is_accepted', 'event_repeat_instance');
    }

    public function scopeForFlat($query, $flat_id)
    {
        if (!!$flat_id) {
            return $query->where('flat_id', $flat_id)->orWhereNull('flat_id');
        } else {
            return $query->whereNull('flat_id');
        }
    }

    public function scopeOneOff($query)
    {
        return $query->whereNull('interval');
    }

    public function scopeRecurring($query)
    {
        return $query->whereNotNull('interval');
    }
}
